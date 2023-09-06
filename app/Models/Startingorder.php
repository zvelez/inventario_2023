<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Startingorder extends Model {

  use HasFactory;

  protected $fillables = [
    'work_id',
    'user_id',
    'comment',
    'dispatchdate',
    'receivedby',
    'document',
  ];

  public function work(): BelongsTo {
    return $this->belongsTo(Work::class);
  }

  public function user(): BelongsTo {
    return $this->belongsTo(User::class);
  }
}
