<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_Pemasukan extends Model
{

    public $timestamps = false;

    protected $table = 'pemasukan';
    protected $primaryKey = 'id_pemasukan';
    protected $fillable = ['id_suplai', 'tanggal','jumlah_debit','jumlah_pcs','return_produk', 'id_user', 'id_produk', 'id_target'];

}