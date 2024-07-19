<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupUser extends Model
{
  use HasFactory;

  const UPDATED_AT = null;

  protected $fillable = [
    'status',
    'role',
    'token',
    'token_expire_date',
    'user_id',
    'group_id',
    'created_by',
  ];

  public function adminUser(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function group(): BelongsTo
  {
    return $this->belongsTo(Group::class);
  }
}
