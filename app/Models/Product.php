<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'barcode',

        'harga_beli',
        'price',
        'kategori_id',
        'status'
        // 'quantity',


    ];

    public function kategori() {
        return $this->belongsTo(Kategori::class);

    }
    public function stok() {
        return $this->belongsTo(Stok::class);

    }


}
