<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{

    public function pelanggan(){
        return $this->belongsTo('App\Pelanggan');
    }
}
