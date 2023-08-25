<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAssigned extends Model {
    use HasFactory;

    protected $table = 'product_assigneds';

    protected $fillable = [
      'product_id',
      'supply_id',
      'amount',
    ];

    public function product(): BelongsTo {
      return $this->belongsTo(Product::class);
    }
  
    public function supply(): BelongsTo {
      return $this->belongsTo(Supply::class);
    }
}
