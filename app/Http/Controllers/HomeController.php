<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
  public function index(Request $request)
  {
    $userId = Auth::id();
    $posts = Post::query() // 1- SELECT * FROM posts
      ->withCount('reactions') // 2- SELECT COUNT(*) from reactions
      ->with([
        'comments' => function ($query) use ($userId) {
          $query->withCount('reactions'); // 3- SELECT * FROM comments WHERE post_id IN (1)
          // SELECT COUNT(*) from reactions
        },
        'reactions' => function ($query) use ($userId) {
          $query->where('user_id', $userId); // SELECT * FROM reactions WHERE user_id = ?authuser
        }
      ])
      ->latest()
      ->paginate(10);

    $posts = PostResource::collection($posts);
    if ($request->wantsJson()) {
      return $posts;
    }

    return Inertia::render('Home', [
      'posts' => $posts
    ]);
  }
}
