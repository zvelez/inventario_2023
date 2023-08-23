<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Manufacturer extends Model {
  
  use HasFactory;

  protected $fillable = [
    'fullname',
    'email',
    'contact',
    'address',
    'description',
    'phone',
  ];

  protected $dates = ['created_at', 'updated_at'];

  public function client(): BelongsTo {
    return $this->belongsTo(Client::class);
  }

}
