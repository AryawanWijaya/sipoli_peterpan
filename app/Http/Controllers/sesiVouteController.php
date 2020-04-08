<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class sesiVouteController extends Controller
{
    public function createVoute(Request $request){
        $status = DB::table('sesi_votes')->pluck('status_sesi')->last();
        if($status!=null){
            if($status!=1) {
                //create sesi di table sesi vote
                $vote = DB::table('sesi_votes')->insert([
                    'ket_sesi' => $request->ket_sesi,
                    'tgl_mulai_vote' => $request->tgl_mulai_vote,
                    'tgl_akhir_vote' =>$request->tgl_akhir_vote,
                    'status_sesi' => 1,
                ]);
                //update count_vote di user jadi 0 semua
                $user = DB::table('users')->update([
                    'count_vote'=>0,
                ]);
                $sesiVoteTerbaru = DB::table('sesi_votes')->pluck('id_sesi_vote')->last();
                //insert di table laporan data seluruh peserta
                $idPeserta = DB::table('users')->where('status','AUDISI')->pluck('id');
                foreach($idPeserta as $value){
                    DB::table('laporans')->insert([
                    'id_sesi_vote'=>$sesiVoteTerbaru,
                    'id_user'=>$value,
                    'jumlah_vote'=>0,
                    ]);
                }

                return response()->json([
                    'status' => 'Sesi Created',
                    'ket_sesi' => $request->ket_sesi,
                    'tgl_mulai_vote' => $request->tgl_mulai_vote,
                    'tgl_akhir_vote' =>$request->tgl_akhir_vote,
                    'status_sesi' => 1,
                ],200);
            }else{
                return response()->json([
                    'error' => 'Sesi sebelumnya belum di hitung'
                ]);
            }
        }else{
            $vote = DB::table('sesi_votes')->insert([
                'ket_sesi' => $request->ket_sesi,
                'tgl_mulai_vote' => $request->tgl_mulai_vote,
                'tgl_akhir_vote' =>$request->tgl_akhir_vote,
                'status_sesi' => 1,
            ]);
            $user = DB::table('users')->update([
                'count_vote'=>0,
            ]);

            $sesiVoteTerbaru = DB::table('sesi_votes')->pluck('id_sesi_vote')->last();
            $idPeserta = DB::table('users')->where('status','AUDISI')->pluck('id');
                foreach($idPeserta as $value){
                    DB::table('laporans')->insert([
                    'id_sesi_vote'=>$sesiVoteTerbaru,
                    'id_user'=>$value,
                    'jumlah_vote'=>0,
                    ]);
            }
            return response()->json([
                'status' => 'Sesi Created',
                'ket_sesi' => $request->ket_sesi,
                'tgl_mulai_vote' => $request->tgl_mulai_vote,
                'tgl_akhir_vote' =>$request->tgl_akhir_vote,
                'status_sesi' => 1,
            ],200);
        }

    }

    public function getAllSesi(){
        $sesi = DB::table('sesi_votes')->get();
        return response()->json(compact('sesi'),200);
    }

    public function getOneSesi($id){
        $sesi = DB::table('sesi_votes')->where('id_sesi_vote',$id)->get();
        return response()->json(compact('sesi'),200);
    }

    public function deleteSesi($id){
        DB::table('sesi_votes')->where('id_sesi_vote',$id)->delete();
        return response()->json([
            'status' => 'sesi vote deleted'
        ],200);
    }

    public function editSesi(Request $request, $id){
        $data = DB::table('sesi_votes')->where('id_sesi_vote',$id)->update([
            'ket_sesi' => $request->ket_sesi,
            'tgl_mulai_vote' => $request->tgl_mulai_vote,
            'tgl_akhir_vote' =>$request->tgl_akhir_vote,
        ]);
        return response()->json([
            'status' => 'data updated',
            'ket_sesi' => $request->ket_sesi,
            'tgl_mulai_vote' => $request->tgl_mulai_vote,
            'tgl_akhir_vote' =>$request->tgl_akhir_vote,
        ],200);
    }


}
