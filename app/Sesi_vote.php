<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sesi_vote extends Model
{
    protected function vote(){
        return $this->hasMany('App\Vote','id_sesi_vote','id_sesi_vote');
    }
}
