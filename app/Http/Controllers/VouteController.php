<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Boolean;

class VouteController extends Controller
{
    public function vote($id){
        date_default_timezone_set("Asia/Jakarta");
        $awal = DB::table('sesi_votes')->pluck('tgl_mulai_vote')->last();
        $akhir= DB::table('sesi_votes')->pluck('tgl_akhir_vote')->last();
        $idVoute = DB::table('sesi_votes')->pluck('id_sesi_vote')->last();
        $date = date("Y-m-d H:i:s");
        $count=DB::table('users')->where('id',$id)->pluck('count_vote')->first();
        if($date<=$akhir && $date>=$awal){
            DB::table('votes')->insert([
                'id_sesi_vote' =>$idVoute,
                'tgl_vote' => $date,
                'id_org_di_vote' =>$id,
                'id_org_yg_vote' =>null,
            ]);
            DB::table('users')->where('id',$id)->update([
                'count_vote'=>$count+1,
            ]);
            $countValue=DB::table('users')->where('id',$id)->pluck('count_vote')->first();
            $res='Voted';
        }else{
            $res='Error Vote';
            $countValue=0;
        }
        return response()->json([
            'status'=>$res,
            'count'=>$countValue,
        ],200);
    }

    private function cekJuri($id_juri, $id_sesi){
        $juri=DB::table('votes')->where([
            ['id_org_yg_vote','=',$id_juri],
            ['id_sesi_vote','=',$id_sesi],
            ])->get()->count();
        if($juri==0){
            return true;
        }else{
            return false;
        }
    }
    public function voteJuri($id, Request $request){
        date_default_timezone_set("Asia/Jakarta");
        $awal = DB::table('sesi_votes')->pluck('tgl_mulai_vote')->last();
        $akhir= DB::table('sesi_votes')->pluck('tgl_akhir_vote')->last();
        $idVoute = DB::table('sesi_votes')->pluck('id_sesi_vote')->last();
        $date = date("Y-m-d H:i:s");
        $id_sesi = DB::table('sesi_votes')->pluck('id_sesi_vote')->last();
        $count=DB::table('users')->where('id',$id)->pluck('count_vote')->first();
        if($date<=$akhir && $date>=$awal){
            if($this->cekJuri($request->get('id_juri'),$id_sesi)==true){
                for ($i=0;$i<5;$i++){
                    DB::table('votes')->insert([
                        'id_sesi_vote' =>$idVoute,
                        'tgl_vote' => $date,
                        'id_org_di_vote' =>$id,
                        'id_org_yg_vote' =>$request->get('id_juri'),
                    ]);
                }
                    DB::table('users')->where('id',$id)->update([
                        'count_vote'=>$count+5,
                    ]);
                    $countValue=DB::table('users')->where('id',$id)->pluck('count_vote')->first();
                    $res='Voted';
            }else{
                $res='Id Juri Sudah Melakukan Vote';
                $countValue=0;
            }
        }else{
            $res='Sesi Vote telah abis';
            $countValue=0;
        }
        return response()->json([
            'status'=>$res,
            'count'=>$countValue,
        ],200);
    }
    public function listVoute(){
        $user =DB::table('users')->where('status','AUDISI')->select('id','name','email')->get();
        return response()->json(compact('user'));

    }

    public function getListHasilVouteByKet($id){
        $list = DB::table('votes')
                    ->where('id_sesi_vote',$id)
                    ->select('id_org_di_vote',DB::raw('count(id_org_di_vote) as total'))
                    ->groupBy('id_org_di_vote')
                    ->get();
        return response()->json(compact('list'));

    }
}

