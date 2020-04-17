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
        $statusPeserta = DB::table('users')->where('id',$id)->pluck('status')->first();
        if($statusPeserta=='AUDISI'){
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
                DB::table('laporans')->where('id_user',$id)->update([
                    'jumlah_vote'=>$count+1,
                ]);
                $countValue=DB::table('users')->where('id',$id)->pluck('count_vote')->first();
                $res='Voted';
                return response()->json([
                    'status'=>$res,
                    'count'=>$countValue,
                ],200);
            }else{
                $res='Error Vote, Anda tidak dalam masa Vote';
                $countValue=0;
                return response()->json([
                    'error'=>$res,
                    'count'=>$countValue,
                ],400);
            }
        }else{
            return response()->json([
                'error' =>'Peserta tidak dalam status audisi'
            ],400);
        }
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
        $statusPeserta = DB::table('users')->where('id',$id)->pluck('status')->first();
        $statusJuri = DB::table('users')->where('id',$request->get('id_juri'))->pluck('status')->first();
        if($statusJuri=='Aktif'){
            if($statusPeserta=='AUDISI'){
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
                            DB::table('laporans')->where('id_user',$id)->update([
                                'jumlah_vote'=>$count+5,
                            ]);
                            $countValue=DB::table('users')->where('id',$id)->pluck('count_vote')->first();
                            $res='Voted';
                            return response()->json([
                                'status'=>$res,
                                'count'=>$countValue,
                            ],200);
                    }else{
                        $res='Id Juri Sudah Melakukan Vote';
                        $countValue=0;
                        return response()->json([
                            'error'=>$res,
                            'count'=>$countValue,
                        ],400);
                    }
                }else{
                    $res='Sesi Vote telah abis';
                    $countValue=0;
                    return response()->json([
                        'error'=>$res,
                        'count'=>$countValue,
                    ],400);
                }

            }else{
                return response()->json([
                    'error' =>'Peserta tidak dalam status audisi'
                ],400);
            }
        }else{
            return response()->json([
                'error' =>'Status Juri Tidak Aktif'
            ],400);
        }
    }
    public function listVoute(){
        $sesi = DB::table('sesi_votes')->pluck('status_sesi')->last();
        if($sesi==1){
            $user =DB::table('users')->where('status','AUDISI')->select('id','name','email')->get();
            return response()->json(compact('user'));
        }else{
            return response()->json([
                'error' => 'tidak dalam masa/sesi vote',
            ],400);
        }

    }

    public function getListHasilVouteByKet($id){
        $list = DB::table('laporans')
                    ->join('users','laporans.id_user','=','users.id')
                    ->where('id_sesi_vote',$id)
                    ->select('id_user','users.name','jumlah_vote')
                    ->get();
        return response()->json(compact('list'));

    }

    public function eliminasiPeserta(){
        $updateCount = DB::table('users')
                        ->where('status','ADMIN')
                        ->orWhere('status','Aktif')
                        ->orWhere('status','Tidak Aktif')
                        ->orWhere('status','ELIMINASI')
                        ->update(['count_vote'=>null,]);
        $id = DB::table('users')
                    ->where('count_vote',DB::raw("(select min(count_vote) from users)"))
                    ->pluck('id');
        $countEliminasi = count($id);
        foreach($id as $value){
            $eliminasi = DB::table('users')->where('id',$value)->update(['status'=>'ELIMINASI']);
        }
        $idSesiVote = DB::table('sesi_votes')->pluck('id_sesi_vote')->last();
        $tutupSesiVote = DB::table('sesi_votes')->where('id_sesi_vote',$idSesiVote)->update(['status_sesi'=>0]);

        return response()->json([
            'jumlahTerelimiasi' => $countEliminasi,
            'idSesiVoteDitutup' => $idSesiVote,
        ],200);
    }
}

