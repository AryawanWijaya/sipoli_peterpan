<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VouteController extends Controller
{
    public function voute($id){
        date_default_timezone_set("Asia/Jakarta");
        $awal = DB::table('sesi_voute')->pluck('tgl_mulai_voute')->last();
        $akhir= DB::table('sesi_voute')->pluck('tgl_akhir_voute')->last();
        $idVoute = DB::table('sesi_voute')->pluck('id_sesi_voute')->last();
        $date = date("Y-m-d H:i:s");
        $count=DB::table('users')->where('id',$id)->pluck('count_voute')->first();
        if($date<=$akhir && $date>=$awal){
            // DB::table('voute')->insert([
            //     'id_sesi_voute' =>$idVoute,
            //     'tgl_voute' => $date,
            //     'id' =>$id,
            // ]);
            DB::table('users')->where('id',$id)->update([
                'count_voute'=>$count+1,
            ]);
            $res='Vouted';
        }else{
            $res='Error Voute';
        }
        return response()->json([
            'status'=>$res,
            'count'=>$count+1,
        ],200);
    }
    public function listVoute(){
        $user =DB::table('users')->where('status','AUDISI')->select('id','name','email')->get();
        return response()->json(compact('user'));

    }
}
