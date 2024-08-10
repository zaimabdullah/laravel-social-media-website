<?php

namespace App\Http\Controllers;

use App\Http\Enums\ReactionEnum;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostAttachment;
use App\Models\Reaction;
use App\Notifications\CommentDeleted;
use App\Notifications\PostDeleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
  /**
   * Store a newly created resource in storage.
   */
  public function store(StorePostRequest $request)
  {
    $data = $request->validated();
    $user = $request->user();

    DB::beginTransaction();
    $allFilePaths = [];
    try {
      $post = Post::create($data);

      /** @var \Illuminate\Http\UploadedFile[] $files */
      $files = $data['attachments'] ?? [];
      foreach ($files as $file) {
        $path = $file->store('attachments/' . $post->id, 'public');
        $allFilePaths = $path;
        PostAttachment::create([
          'post_id' => $post->id,
          'name' => $file->getClientOriginalName(),
          'path' => $path,
          'mime' => $file->getMimeType(),
          'size' => $file->getSize(),
          'created_by' => $user->id,
        ]);
      }

      // if okay commit
      DB::commit();
    } catch (\Exception $e) {
      foreach ($allFilePaths as $path) {
        Storage::disk('public')->delete($path);
      }
      // if problem, rollback
      DB::rollBack();
      throw $e;
    }

    return back();
  }

  /**
   * Display the specified resource.
   */
  // public function show(Post $post)
  // {
  //
  // }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdatePostRequest $request, Post $post)
  {
    $data = $request->validated();
    $user = $request->user();

    DB::beginTransaction();
    $allFilePaths = [];
    try {
      $post->update($data);

      $deleted_ids = $data['deleted_file_ids'] ?? [];

      $attachments = PostAttachment::query()
        ->where('post_id', $post->id)
        ->whereIn('id', $deleted_ids)
        ->get();

      foreach ($attachments as $attachment) {
        $attachment->delete();
      }

      /** @var \Illuminate\Http\UploadedFile[] $files */
      $files = $data['attachments'] ?? [];
      foreach ($files as $file) {
        $path = $file->store('attachments/' . $post->id, 'public');
        $allFilePaths = $path;
        PostAttachment::create([
          'post_id' => $post->id,
          'name' => $file->getClientOriginalName(),
          'path' => $path,
          'mime' => $file->getMimeType(),
          'size' => $file->getSize(),
          'created_by' => $user->id,
        ]);
      }

      // if okay commit
      DB::commit();
    } catch (\Exception $e) {
      foreach ($allFilePaths as $path) {
        Storage::disk('public')->delete($path);
      }
      // if problem, rollback
      DB::rollBack();
      throw $e;
    }

    return back();
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Post $post)
  {
    $id = Auth::id();

    // curr-user is owner of post OR post is for a group OR cur-user admin of group
    if ($post->isOwner($id) || $post->group && $post->group->isAdmin($id)) {
      $post->delete();

      // if curr-user NOT owner of post, means the other 2 case from above
      if (!$post->isOwner($id)) {
        $post->user->notify(new PostDeleted($post->group));
      }

      return back();
    }

    return response("You don't have permission to delete this post", 403);
  }

  public function downloadAttachment(PostAttachment $attachment)
  {
    // TODO check if user has permission to download that attachment


    return response()->download(Storage::disk('public')->path($attachment->path), $attachment->name);
  }

  public function postReaction(Request $request, Post $post)
  {
    $data = $request->validate([
      'reaction' => [Rule::enum(ReactionEnum::class)],
    ]);

    $userId = Auth::id();
    // query an existing reaction of post
    $reaction = Reaction::where('user_id', $userId)
      ->where('object_id', $post->id)
      ->where('object_type', Post::class)
      ->first();

    if ($reaction) {
      // delete an existing
      $hasReaction = false;
      $reaction->delete();
    } else {
      // create new for not exist
      $hasReaction = true;
      Reaction::create([
        'object_id' => $post->id,
        'object_type' => Post::class,
        'user_id' => $userId,
        'type' => $data['reaction'],
      ]);
    }

    // select number of reaction of post
    $reactions = Reaction::where('object_id', $post->id)->where('object_type', Post::class)->count();

    return response([
      'num_of_reactions' => $reactions,
      'current_user_has_reaction' => $hasReaction,
    ]);
  }

  public function createComment(Request $request, Post $post)
  {
    $data = $request->validate([
      'comment' => ['required'],
      'parent_id' => ['nullable', 'exists:comments,id'],
    ]);

    $comment = Comment::create([
      'post_id' => $post->id,
      'comment' => nl2br($data['comment']),
      'user_id' => Auth::id(),
      'parent_id' => $data['parent_id'] ?: null,
    ]);

    return response(new CommentResource($comment), 201);
  }

  public function deleteComment(Comment $comment)
  {
    $post = $comment->post;
    $id = Auth::id();

    // owner of comment OR owner of post only can delete the comment
    if ($comment->isOwner($id) || $post->isOwner($id)) {
      $comment->delete();

      // notify user or not + user is owner of comment or not
      // current user not the owner of comment BUT owner of post
      if (!$comment->isOwner($id)) {
        $comment->user->notify(new CommentDeleted($comment, $post));
      }

      return response('', 204);
    }

    return response("You don't have permission to delete this comment.", 403);
  }

  public function updateComment(UpdateCommentRequest $request, Comment $comment)
  {
    $data = $request->validated();

    $comment->update([
      'comment' => nl2br($data['comment']),
    ]);

    return new CommentResource($comment);
  }

  public function commentReaction(Request $request, Comment $comment)
  {
    $data = $request->validate([
      'reaction' => [Rule::enum(ReactionEnum::class)],
    ]);

    $userId = Auth::id();
    // query an existing reaction of comment
    $reaction = Reaction::where('user_id', $userId)
      ->where('object_id', $comment->id)
      ->where('object_type', Comment::class)
      ->first();

    if ($reaction) {
      // delete an existing
      $hasReaction = false;
      $reaction->delete();
    } else {
      // create new for not exist
      $hasReaction = true;
      Reaction::create([
        'object_id' => $comment->id,
        'object_type' => Comment::class,
        'user_id' => $userId,
        'type' => $data['reaction'],
      ]);
    }

    // select number of reaction of comment
    $reactions = Reaction::where('object_id', $comment->id)->where('object_type', Comment::class)->count();

    return response([
      'num_of_reactions' => $reactions,
      'current_user_has_reaction' => $hasReaction,
    ]);
  }
}
