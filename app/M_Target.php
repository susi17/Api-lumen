<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_Target extends Model
{

    public $timestamps = false;

    protected $table = 'target';
    protected $primaryKey = 'id_target';
    protected $fillable = ['tanggal', 'target_pcs','id_user','id_produk','sisa_target','selisih_target'];

}