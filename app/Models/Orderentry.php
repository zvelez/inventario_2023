<?php

namespace App\Models;

use Carbon\Carbon;
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

  protected $appends = ['amounttotal', 'date_str', 'solicitantdate_str', 'deliverydate_str', 'estimateddeliverydate_str'];

  protected $dates = ['created_at', 'updated_at'];

  public function getAmounttotalAttribute() {
    $total = 0;
    if(isset($this->attributes['id'])) {
      $supplies = Supply::where('orderentry_id', '=', $this->attributes['id'])->get();
      foreach($supplies as $supp) {
        $calc = doubleval($supp->amount) * doubleval($supp->unitprice);
        $total = $total + $calc;
      }
    }
    return round($total, 2);
  }

  public function getDateStrAttribute() {
    return !empty($this->attributes['date']) ? 
                  Carbon::createFromTimestamp(strtotime($this->attributes['date']))->isoFormat('DD/MM/YYYY') : '';
  }

  public function getSolicitantdateStrAttribute() {
    return !empty($this->attributes['solicitantdate']) ? 
                  Carbon::createFromTimestamp(strtotime($this->attributes['solicitantdate']))->isoFormat('DD/MM/YYYY') : '';
  }

  public function getDeliverydateStrAttribute() {
    return !empty($this->attributes['deliverydate']) ? 
                  Carbon::createFromTimestamp(strtotime($this->attributes['deliverydate']))->isoFormat('DD/MM/YYYY') : '';
  }

  public function getEstimateddeliverydateStrAttribute() {
    return !empty($this->attributes['estimateddeliverydate']) ? 
                  Carbon::createFromTimestamp(strtotime($this->attributes['estimateddeliverydate']))->isoFormat('DD/MM/YYYY') : '';
  }

  public function supplies(): HasMany {
    return $this->hasMany(Supply::class);
  }

  public function supplier(): BelongsTo {
    return $this->belongsTo(Supplier::class);
  }
}
