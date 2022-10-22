<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $fillable = [
        'suplier_id',
        'user_id',
        'created_at',
    ];

    public function items()
    {
        return $this->hasMany(PembelianItem::class);
    }

    public function historystoks()
    {
        return $this->hasMany(HistoryStok::class);
    }

    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class);
    }

    public function suplier()
    {
        return $this->belongsTo(Suplier::class);
    }

    public function getSuplierName()
    {
        if($this->suplier) {
            return $this->suplier->first_name . ' ' . $this->suplier->last_name;
        }
        return 'Working Suplier';
    }

    public function total()
    {
        return $this->items->map(function ($i){
            return $i->price;
        })->sum();
    }

    public function formattedTotal()
    {
        return number_format($this->total(), 2);
    }

    public function receivedAmount()
    {
        return $this->pengeluarans->map(function ($i){
            return $i->amount;
        })->sum();
    }

    public function formattedReceivedAmount()
    {
        return number_format($this->receivedAmount(), 2);
    }
}
