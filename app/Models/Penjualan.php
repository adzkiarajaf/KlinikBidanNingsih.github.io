<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';
    protected $guarded = [];

    protected $fillable = ['uuid', 'id_penjualan', 'total_harga', 'total_item', 'bayar', 'id_user'];

    public function kasir()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function penjualanDetail()
    {
        return $this->hasMany(PenjualanDetail::class, 'id_penjualan');
    }
}