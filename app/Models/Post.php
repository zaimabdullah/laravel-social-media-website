<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
  use HasFactory;
  use SoftDeletes;

  protected $fillable = [
    'user_id',
    'body',
    'group_id',
    'preview',
    'preview_url',
  ];

  protected $casts = [
    'preview' => 'json',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function group(): BelongsTo
  {
    return $this->belongsTo(Group::class);
  }

  public function attachments(): HasMany
  {
    return $this->hasMany(PostAttachment::class)->latest();
  }

  public function reactions(): MorphMany
  {
    return $this->morphMany(Reaction::class, 'object');
  }

  public function comments(): HasMany
  {
    return $this->hasMany(Comment::class)->latest();
  }

  public function latest5Comments(): HasMany
  {
    return $this->hasMany(Comment::class);
  }

  public static function postsForTimeline($userId): Builder
  {
    return Post::query() // 1- SELECT * FROM posts
      ->withCount('reactions') // 2- SELECT COUNT(*) from reactions
      ->with([
        'comments' => function ($query) { // 3- SELECT * FROM comments
          $query->withCount('reactions'); // SELECT COUNT(*) from reactions
        },
        'reactions' => function ($query) use ($userId) {
          $query->where('user_id', $userId); // SELECT * FROM reactions WHERE user_id = ?authuser
        }
      ])
      ->latest();
  }

  public function isOwner($userId)
  {
    return $this->user_id == $userId;
  }
}
