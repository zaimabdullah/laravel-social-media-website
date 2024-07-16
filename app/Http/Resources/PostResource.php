<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    $comments = $this->comments;
    // name-convention
    return [
      'id' => $this->id,
      'body' => $this->body,
      'created_at' => $this->created_at->format('Y-m-d H:i:s'),
      'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
      'user' => new UserResource($this->user),
      'group' => $this->group,
      'attachments' => PostAttachmentResource::collection($this->attachments),
      'num_of_reactions' => $this->reactions_count,
      'num_of_comments' => count($comments),
      'current_user_has_reaction' => $this->reactions->count() > 0,
      'comments' => self::convertCommentsIntoTree($comments),
    ];
  }

  /**
   * Summary of convertCommentsIntoTree
   * @param \App\Models\Comment[] $comments
   * @param $parentId
   * @return array
   * Not Optimal Ways actually for large num of comments
   */
  private static function convertCommentsIntoTree($comments, $parentId = null): array
  {
    // parentId is sets as null defaultly
    $commentTree = [];

    // at first, we check the comment with parentId = null @ first-level comment
    foreach ($comments as $comment) {
      if ($comment->parent_id === $parentId) {
        // then, Find all comment which has parentId as $comment->id(current first-level comment)
        $children = self::convertCommentsIntoTree($comments, $comment->id);
        // getting number of comments
        $comment->childComments = $children;
        $comment->numOfComments = collect($children)->sum('numOfComments') + count($children);
        $commentTree[] = new CommentResource($comment);
      }
    }

    return $commentTree;
  }
}
