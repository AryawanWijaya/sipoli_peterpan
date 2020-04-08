<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class laporan extends Model
{
    protected function sesiVote(){
        return $this->hasMany('App\Sesi_vote','id_sesi_vote','id_sesi_vote');
    }

    protected function user(){
        return $this->hasMany('App\User','id','id_user');
    }
}
