<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [
        'amount',
        'penjualan_id',
        'user_id',
    ];
}
