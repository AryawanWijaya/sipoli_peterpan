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

    Route::post('logout', 'UserController@logout')->middleware('cors');
});

//peserta --> dilakukan sendiri oleh peserta
Route::post('/registerPeserta', 'UserController@register')->middleware('cors');
Route::post('/pesertaEdit/{id}','UserController@editDataPeserta')->middleware('cors','jwt.verify');
Route::get('/peserta/getAll','UserController@getAllPeserta')->middleware('cors','jwt.verify');
//admin
Route::post('/registerAdmin', 'UserController@registerAdmin')->middleware('cors');
//juri --> dilakukan oleh admin
Route::post('/registerJuri', 'UserController@registerJuri')->middleware('cors','jwt.verify');
Route::post('/juriEdit/{id}','UserController@editDataJuri')->middleware('cors','jwt.verify');
Route::get('/juri/getAll','UserController@getAllJuri')->middleware('cors','jwt.verify');

//umum
Route::post('/login', 'UserController@login')->middleware('cors');
Route::get('/user', 'UserController@getAuthenticatedUser')->middleware('cors','jwt.verify');
Route::post('/user/delete/{id}','UserController@deleteUser')->middleware('cors','jwt.verify');
Route::get('/allPeserta', 'UserController@readAllDataPeserta')->middleware('cors','jwt.verify');

//sesivote
Route::post('/sesi/vote/create','sesiVouteController@createVoute')->middleware('cors','jwt.verify');
Route::get('/sesi/getAll','sesiVouteController@getAllSesi')->middleware('cors','jwt.verify');
Route::get('/sesi/get/{id}','sesiVouteController@getOneSesi')->middleware('cors','jwt.verify');
Route::post('/sesi/vote/update/{id}','sesiVouteController@editSesi')->middleware('cors','jwt.verify');
Route::post('/sesi/vote/delete/{id}','sesiVouteController@deleteSesi')->middleware('cors','jwt.verify');
Route::get('/lastSesi','sesiVouteController@getStatusLastSesi')->middleware('cors','jwt.verify');

//vote
Route::post('/vote/{id}','VouteController@vote')->middleware('cors');
Route::get('/vote/list','VouteController@listVoute')->middleware('cors');
Route::post('/vote/juri/{id}','VouteController@voteJuri')->middleware('cors','jwt.verify');

// Route::get('/vote/hasilVote/{id}','VouteController@getListHasilVouteByKet');
Route::get('/vote/hasilVote/{id}','VouteController@getListHasilVouteByKet')->middleware('cors','jwt.verify');
Route::post('/eliminasi','VouteController@eliminasiPeserta')->middleware('cors','jwt.verify');


