<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Work extends Model {

  use HasFactory;

  protected $fillable = [
    'deadline',
    'status',
    'client_id',
  ];

  protected $dates = ['created_at', 'updated_at'];

  public function client(): BelongsTo {
    return $this->belongsTo(Client::class);
  }
}
