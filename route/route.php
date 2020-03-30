<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');

use think\facade\Route;
//注册
//Route::any('/login/regist', 'login/Login/regist');
//
//Route::get('/login$', 'login/Login/login');

//分组路由
Route::group('login', function() {
    Route::post('/', 'login');
    Route::post('/regist', 'regist');
})->prefix('login/Login/')->ext('html');

//不建议
//Route::group('login', [
//    '/' => 'login',
//    '/regist' => 'regist'
//])->prefix('login/Login/')->ext('html');

Route::get('/test', 'login/Test/test')->middleware(['Auth']);

return [

];
