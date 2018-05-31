<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $fillable = [
        'nik', 'nama', 'telp', 'alamat', 'no_rek', 'chat_id'
    ];
}
