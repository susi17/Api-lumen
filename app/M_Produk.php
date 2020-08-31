<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_Produk extends Model
{

    public $timestamps = false;

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    protected $fillable = ['nama_produk', 'harga_dasar','keterangan','foto_produk','stok_produk','harga_jual','berat_satuan'];

}