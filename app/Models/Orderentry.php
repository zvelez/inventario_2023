<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Orderentry extends Model {

  use HasFactory;

  protected $fillable = [
    'date',
    'solicitantarea',
    'solicitantmanager',
    'solicitantdate',
    'deliveryaddress',
    'deliverydate',
    'deliveryreceptionist',
    'estimateddeliverydate',
    'deliverynote',
    'deliveryref',
    'deliverydoc',
    'supplier_id',
  ];

  protected $appends = ['amounttotal'];

  public function getAmounttotalAttribute() {
    $total = 0;
    if(isset($this->attributes['id'])) {
      $supplies = Supply::where('orderentry_id', '=', $this->attributes['id'])->get();
      foreach($supplies as $supp) {
        $calc = doubleval($supp->amount) * doubleval($supp->unitprice);
        $total = $total + round($calc, 2);
      }
    }
    return $total;
  }

  public function supplies(): HasMany {
    return $this->hasMany(Supply::class);
  }

  public function supplier(): BelongsTo {
    return $this->belongsTo(Supplier::class);
  }
}
