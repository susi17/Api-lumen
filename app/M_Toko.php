<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_Toko extends Model
{

    public $timestamps = false;

    protected $table = 'toko';
    protected $primaryKey = 'id_toko';
    protected $fillable = ['nama_toko', 'nama_pemilik','alamat','latitude','longitude','no_hp','foto_toko','status_toko'];

}