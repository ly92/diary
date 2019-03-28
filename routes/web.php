<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('hook', ['uses' => 'Controller@hook']);
$router->get('test', ['uses' => 'Controller@test']);


// $router->group(['middleware' => ['cors']], function($router){
	//获取验证码
	$router->post('app/user/smscode', ['uses' => 'Terminal\ClientController@smscode']);
	//注册
	$router->post('app/user/add', ['uses' => 'Terminal\ClientController@add']);
	//登录
	$router->post('app/user/login', ['uses' => 'Terminal\ClientController@login']);
	//重置密码
	$router->post('app/user/resetpwd', ['uses' => 'Terminal\ClientController@resetPwd']);

// });
