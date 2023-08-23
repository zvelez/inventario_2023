<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model {
  
  use HasFactory;

  protected $fillable = [
    'manufacturer_id',
    'work_id',
    'code',
    'name',
    'amount',
    'unitprice',
  ];

  public function work(): BelongsTo {
    return $this->belongsTo(Work::class);
  }

  public function manufacturer(): BelongsTo {
    return $this->belongsTo(Manufacturer::class);
  }
}
