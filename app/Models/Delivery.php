<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model {
  
  use HasFactory;

  protected $fillable = [
    'work_id',
    'in',
    'code',
    'amount',
    'deliverydate',
    'estimatedate',
    'responsible',
    'contact',
    'dnicontact',
    'observations',
    'user_id',
  ];

  protected $appends = ['in_str'];

  protected $dates = ['created_at', 'updated_at'];

  public function getInStrAttribute() {
    return $this->in? 'Insumo' : 'Producto';
  }
}
