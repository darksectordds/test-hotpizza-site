<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartProduct extends Model
{
    use HasFactory;

    protected $table = 'cart_product';

    protected $fillable = [
        'cart_id',
        'product_id',
        'count',
        'created_at',
    ];

    public $timestamps = false;

    /*
     |---------------------------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------------------
     */

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
