<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianItem extends Model
{
    protected $fillable =[
        'price',
        'quantity',
        'product_id',
        'pembelian_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
