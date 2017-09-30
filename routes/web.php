<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
//    return view('welcome');
    return view('index');
});
Route::post('/sign','student\StudentController@sign');
Route::post('/search','student\StudentController@search');
Route::get('/export','excel\ExcelController@export');
Route::get('/export2','excel\ExcelController@export2');
Route::post('/reset','student\StudentController@reset');

Route::post('/sms','sms\smsController@code');

Route::any('/ceshi','sms\ceshiSms@aaa');