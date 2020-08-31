<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_User extends Model
{

    public $timestamps = false;

    protected $table = 'user';
    protected $primaryKey = 'id_user';
    protected $fillable = ['username', 'email','password','nama','no_telepon','foto_ktp','selfi_ktp','foto_profil','alamat','role_user', 'aktif'];

}