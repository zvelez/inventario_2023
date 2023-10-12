<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productphoto extends Model {

  use HasFactory;

  protected $fillable = [
    'product_id',
    'photourl',
    'description',
  ];
}
