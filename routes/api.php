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

//peserta --> dilakukan sendiri oleh peserta
Route::post('/registerPeserta', 'UserController@register');
Route::post('/pesertaEdit/{id}','UserController@editDataPeserta')->middleware('jwt.verify');

//admin
Route::post('/registerAdmin', 'UserController@registerAdmin');
//juri --> dilakukan oleh admin
Route::post('/registerJuri', 'UserController@registerJuri')->middleware('jwt.verify');
Route::post('/juriEdit/{id}','UserController@editDataJuri')->middleware('jwt.verify');

//umum
Route::post('/login', 'UserController@login');
Route::get('/user', 'UserController@getAuthenticatedUser')->middleware('jwt.verify');
Route::get('/allPeserta', 'UserController@readAllDataPeserta')->middleware('jwt.verify');

// Route::post('/admin/createPeserta', 'adminController@createPeserta');
// Route::post('/admin/updatePeserta', 'adminController@updatePeserta');
// Route::get('/admin/getAllPeserta', 'adminController@readAllData')->middleware('jwt.verify');
// Route::post('/admin/getOnePeserta', 'adminController@readOneData');
// Route::post('/admin/deletePeserta', 'adminController@deletePeserta');


