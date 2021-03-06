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
Route::get('/emailCheck/{email}/{contact}', 'ProfileController@emailCheck');

// READ ALL API
Route::get('/read', 'ProfileController@read');
// UPDATE API
Route::put('/update/{id}', 'ProfileController@update');
// DELETE API
Route::delete('/delete/{id}', 'ProfileController@delete');
//GET ID API
Route::get('/edit/{id}', 'ProfileController@readID');


//SEND EMAIL
Route::get('/sendEmail/{email}', 'ProfileController@send');
Route::get('/confirmRegistration/{token}', 'ProfileController@confirmRegistration');

// SEND SMS
Route::get('/sendSMS/{contact}', 'ProfileController@itexmo');
Route::get('/SMSverify/{id}', 'ProfileController@SMSverify');

//PAYPAL API
Route::post('create-payment', 'PaypalController@create');
Route::post('execute-payment', 'PaypalController@execute');

Route::get('/subscribe/{id}', 'ProfileController@subscribe');

