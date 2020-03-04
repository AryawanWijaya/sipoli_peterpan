<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class sesiVouteController extends Controller
{
    public function createVoute(Request $request){
        $voute = DB::table('sesi_voute')->insert([
            'ket_sesi' => $request->ket_sesi,
            'tgl_mulai_voute' => $request->tgl_mulai_voute,
            'tgl_akhir_voute' =>$request->tgl_akhir_voute,
        ]);
        return response()->json([
            'status' => 'Sesi Created',
            'ket_sesi' => $request->ket_sesi,
            'tgl_mulai_voute' => $request->tgl_mulai_voute,
            'tgl_akhir_voute' =>$request->tgl_akhir_voute,
        ],200);
    }

    public function getAllSesi(){
        $sesi = DB::table('sesi_voute')->get();
        return response()->json(compact('sesi'),200);
    }

    public function getOneSesi($id){
        $sesi = DB::table('sesi_voute')->where('id_sesi_voute',$id)->get();
        return response()->json(compact('sesi'),200);
    }

    public function deleteSesi($id){
        DB::table('sesi_voute')->where('id_sesi_voute',$id)->delete();
        return response()->json([
            'status' => 'sesi voute deleted'
        ],200);
    }

    public function editSesi(Request $request, $id){
        $data = DB::table('sesi_voute')->where('id_sesi_voute',$id)->update([
            'ket_sesi' => $request->ket_sesi,
            'tgl_mulai_voute' => $request->tgl_mulai_voute,
            'tgl_akhir_voute' =>$request->tgl_akhir_voute,
        ]);
        return response()->json([
            'status' => 'data updated',
            'ket_sesi' => $request->ket_sesi,
            'tgl_mulai_voute' => $request->tgl_mulai_voute,
            'tgl_akhir_voute' =>$request->tgl_akhir_voute,
        ],200);
    }
}
