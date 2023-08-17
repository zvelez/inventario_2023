<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supply extends Model {

  use HasFactory;

  protected $fillable = [
    'code',
    'description',
    'brand',
    'amount',
    'unitprice',
    'deliverynote',
    'orderentry_id',
  ];

  protected $appends = ['resume_str'];

  public function getResumeStrAttribute() {
    return $this->attributes['code'] . ' | ' . $this->attributes['brand'] . ' | ' . $this->attributes['amount'] . ' a Bs' .  $this->attributes['unitprice'];
  }

  public function order(): HasMany {
    return $this->hasMany(Orderentry::class);
  }
}
