<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_Suplai extends Model
{

    public $timestamps = false;

    protected $table = 'suplai';
    protected $primaryKey = 'id_suplai';
    protected $fillable = ['id_produk', 'id_user','id_toko','tanggal_suplai','jumlah_suplai','status_suplai'];

}