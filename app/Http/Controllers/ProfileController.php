<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Follower;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProfileController extends Controller
{
  public function index(request $request, User $user)
  {
    $isCurrentUserFollower = false;

    // check curr-user has followed the user or not
    if (!Auth::guest()) {
      $isCurrentUserFollower = Follower::where('user_id', $user->id)->where('follower_id', Auth::id())->exists();
    }

    // dd($isCurrentUserFollower);
    $followerCount = Follower::where('user_id', $user->id)->count();

    $posts = Post::postsForTimeline(Auth::id())
      ->where('user_id', $user->id)
      ->paginate(10);

    // laod more functionality of posts
    $posts = PostResource::collection($posts);
    if ($request->wantsJson()) {
      return $posts;
    }

    // make use of func followers() & followings() in User.php

    $followers = $user->followers;
    // User::query()
    //   ->select('users.*')
    //   ->join('followers AS f', 'f.follower_id', 'users.id')
    // not curr-auth-user, but the one curr-auth-user open the profile = $user->id
    // ->where('f.user_id', $user->id)
    // ->get();

    $followings = $user->followings;
    // User::query()
    //   ->select('users.*')
    //   ->join('followers AS f', 'f.user_id', 'users.id')
    //   ->where('f.follower_id', $user->id)
    //   ->get();

    return Inertia::render('Profile/View', [
      'mustVerifyEmail' => $user instanceof MustVerifyEmail,
      'status' => session('status'),
      'success' => session('success'),
      'isCurrentUserFollower' => $isCurrentUserFollower,
      'followerCount' => $followerCount,
      'user' => new UserResource($user),
      'posts' => $posts,
      'followers' => UserResource::collection($followers),
      'followings' => UserResource::collection($followings),
    ]);
  }

  /**
   * Update the user's profile information.
   */
  public function update(ProfileUpdateRequest $request): RedirectResponse
  {
    $request->user()->fill($request->validated());

    if ($request->user()->isDirty('email')) {
      $request->user()->email_verified_at = null;
    }

    $request->user()->save();

    return to_route('profile', $request->user())->with('success', 'Your profile details were updated.');
  }

  /**
   * Delete the user's account.
   */
  public function destroy(Request $request): RedirectResponse
  {
    $request->validate([
      'password' => ['required', 'current_password'],
    ]);

    $user = $request->user();

    Auth::logout();

    $user->delete();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return Redirect::to('/');
  }

  public function updateImage(Request $request)
  {
    $data = $request->validate([
      'cover' => ['nullable', 'image'],
      'avatar' => ['nullable', 'image'],
    ]);

    $user = $request->user();

    /** @var \Illuminate\Http\UploadedFile $cover */
    $cover = $data['cover'] ?? null;
    $avatar = $data['avatar'] ?? null;

    $success = '';
    if ($cover) {
      if ($user->cover_path) {
        Storage::disk('public')->delete($user->cover_path);
      }
      $path = $cover->store('user-' . $user->id, 'public');
      $user->update(['cover_path' => $path]);
      $success = 'Your cover image was updated.';
    }

    if ($avatar) {
      if ($user->avatar_path) {
        Storage::disk('public')->delete($user->avatar_path);
      }
      $path = $avatar->store('user-' . $user->id, 'public');
      $user->update(['avatar_path' => $path]);
      $success = 'Your avatar image was updated.';
    }

    // session('success', 'Cover image has been updated');

    return back()->with('success', $success);
  }
}
