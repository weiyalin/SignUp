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
    return view('index');
});
/**
 *
 *
 */
Route::post('/sign','student\StudentController@sign');                  //学生报名
Route::post('/search','student\StudentController@search');              //查找学生信息
Route::get('/export','excel\ExcelController@export');                   //导出学生表
Route::post('/reset','student\StudentController@reset');                //修改学生信息

Route::post('/sms','sms\smsController@code');                           //发送短信验证

Route::any('/ceshi','sms\ceshiSms@aaa');
//
//支付宝支付
Route::group(['prefix' => 'alipay'],function() {
    Route::post('wappay','Alipay\AlipayWapController@alipayWapPay');     //
    Route::get('return','Alipay\AlipayWapController@alipayReturn');     //
    Route::get('notify','Alipay\AlipayWapController@alipayNotify');     //
});
//微信支付
Route::get('getopenid','Alipay\WechatPayController@index');             //刚进入页面,获取用户的oppenid
Route::group(['prefix' => 'wechatpay'],function () {
    Route::post('getpay','Alipay\WechatPayController@getPay');          //前端向后端发送支付请求
    Route::get('wexinotify','Alipay\WechatPayController@wechatNotify'); //支付成功后，微信回调的路由
    Route::post('updateOrder','Alipay\WechatPayController@updateOrder');//更新用户的订单状态
});
Route::get('trace','Alipay\WechatTokenController@traceHttp');           //TOKEN 验证
  //微信支付回调