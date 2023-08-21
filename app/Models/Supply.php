<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supply extends Model {

  use HasFactory;

  protected $fillable = [
    'code',
    'description',
    'brand',
    'unit',
    'amount',
    'unitprice',
    'deliverynote',
    'orderentry_id',
  ];

  protected $appends = ['resume_str', 'totalprice'];

  public function getResumeStrAttribute() {
    return isset($this->attributes['code']) ? 
        $this->attributes['code'] . ' | ' . $this->attributes['brand'] . ' | ' . 
          $this->attributes['amount']. $this->attributes['unit'] . ' a Bs' .  $this->attributes['unitprice'] : '';
  }

  public function getTotalpriceAttribute() {
    return round(doubleval($this->amount) * doubleval($this->unitprice), 2);
  }

  public function order(): BelongsTo {
    return $this->belongsTo(Orderentry::class);
  }
}
