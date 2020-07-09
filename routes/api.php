<?php

use Illuminate\Http\Request;

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

// CREATE API
Route::post('/create', 'ProfileController@create');
// READ ALL API
Route::get('/read', 'ProfileController@read');
// UPDATE API
Route::put('/update/{id}', 'ProfileController@update');
// DELETE API
Route::delete('/delete/{id}', 'ProfileController@delete');
//GET ID API
Route::get('/read/{id}', 'ProfileController@readID');



