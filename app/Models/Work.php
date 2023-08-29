<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

  public function deliveries(): HasMany {
    return $this->hasMany(Delivery::class);
  }

  public function comments(): HasMany {
    return $this->hasMany(Comment::class);
  }

  public function products(): HasMany {
    return $this->hasMany(Product::class);
  }
}
