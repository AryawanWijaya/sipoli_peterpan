<?php

use Illuminate\Http\Request;
// use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('jwt.auth')->group(function(){

    Route::post('logout', 'UserController@logout');
});

//peserta --> dilakukan sendiri oleh peserta
Route::post('/registerPeserta', 'UserController@register');
Route::post('/pesertaEdit/{id}','UserController@editDataPeserta')->middleware('jwt.verify');
Route::get('/peserta/getAll','UserController@getAllPeserta')->middleware('jwt.verify');
//admin
Route::post('/registerAdmin', 'UserController@registerAdmin');
//juri --> dilakukan oleh admin
Route::post('/registerJuri', 'UserController@registerJuri')->middleware('jwt.verify');
Route::post('/juriEdit/{id}','UserController@editDataJuri')->middleware('jwt.verify');
Route::get('/juri/getAll','UserController@getAllJuri')->middleware('jwt.verify');

//umum
Route::post('/login', 'UserController@login');
Route::get('/user', 'UserController@getAuthenticatedUser')->middleware('jwt.verify');
Route::post('/user/delete/{id}','UserController@deleteUser')->middleware('jwt.verify');
Route::get('/allPeserta', 'UserController@readAllDataPeserta')->middleware('jwt.verify');

//sesivote
Route::post('/sesi/vote/create','sesiVouteController@createVoute')->middleware('jwt.verify');
Route::get('/sesi/getAll','sesiVouteController@getAllSesi')->middleware('jwt.verify');
Route::get('/sesi/get/{id}','sesiVouteController@getOneSesi')->middleware('jwt.verify');
Route::post('/sesi/vote/update/{id}','sesiVouteController@editSesi')->middleware('jwt.verify');
Route::post('/sesi/vote/delete/{id}','sesiVouteController@deleteSesi')->middleware('jwt.verify');

//vote
Route::post('/vote/{id}','VouteController@vote');
Route::get('/vote/list','VouteController@listVoute');
Route::post('/vote/juri/{id}','VouteController@voteJuri')->middleware('jwt.verify');

Route::get('/vote/hasilVote/{id}','VouteController@getListHasilVouteByKet')->middleware('jwt.verify');
Route::post('/eliminasi','VouteController@eliminasiPeserta')->middleware('jwt.verify');


