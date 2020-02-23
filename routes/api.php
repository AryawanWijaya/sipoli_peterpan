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
Route::post('/admin/createPeserta', 'adminController@createPeserta');
Route::post('/admin/updatePeserta', 'adminController@updatePeserta');
Route::get('/admin/getAllPeserta', 'adminController@readAllData');
Route::post('/admin/getOnePeserta', 'adminController@readOneData');
Route::post('/admin/deletePeserta', 'adminController@deletePeserta');


