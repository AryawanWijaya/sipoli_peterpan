<?php

namespace App\Http\Controllers;

use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class adminController extends Controller
{
    public function createPeserta (Request $req){
        $pass = $req->pass_peserta;
        $peserta = DB::table('peserta')->insert([
            'username_peserta' => $req->username_peserta,
            'pass_peserta' => sha1($pass),
            'nama_peserta' => $req->nama_peserta,
            'status_peserta' => $req->status_peserta,
            'id_user' => 2
        ]);
        return Response()->json([
            'status' => "Data Created",
            'username_peserta' => $req->username_peserta,
            'pass_peserta' => sha1($pass),
            'nama_peserta' => $req->nama_peserta,
            'status_peserta' => $req->status_peserta,
        ], 200);
    }

    public function readAllData(){
        $data=DB::table('peserta')->get();
        return response()->json(
            compact('data'),200);
    }

    public function readOneData(Request $req){

        $data = DB::table('peserta')->where('id_peserta',$req->id_peserta)->get();
        return response()->json(compact('data'),200);
    }
    public function updatePeserta (Request $req){
        $pass = $req->pass_peserta;
        DB::table('peserta')->where('id_peserta',$req->id_peserta)->update([
            'username_peserta' => $req->username_peserta,
            'pass_peserta' => sha1($pass),
            'nama_peserta' => $req->nama_peserta,
            'status_peserta' => $req->status_peserta,
        ]);
        return Response()->json([
            'status' =>"Data Updated",
            'username_peserta' => $req->username_peserta,
            'pass_peserta' => sha1($pass),
            'nama_peserta' => $req->nama_peserta,
            'status_peserta' => $req->status_peserta,
        ],200);
    }

    public function deletePeserta(Request $req)
    {
        DB::table('peserta')->where('id_peserta',$req->id)->delete();
        return response()->json([
            'status' => "Data Deleted"
        ],200);
    }
}
