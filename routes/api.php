<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

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
Route::post('users/signup', ['uses' => 'App\Http\Controllers\APIController@register']);
Route::post('users/signin', ['uses' => 'App\Http\Controllers\APIController@login']);

Route::middleware('auth:sanctum')->get('/users', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::put('shopping/{id}', 'App\Http\Controllers\ShoppingController@update');
    Route::post('shopping', 'App\Http\Controllers\ShoppingController@store');
    Route::get('shopping', 'App\Http\Controllers\ShoppingController@getAll');
    Route::get('shopping/{id}', 'App\Http\Controllers\ShoppingController@show');
    Route::delete('shopping/{id}', 'App\Http\Controllers\ShoppingController@destroy');
});