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


$router->group(['middleware' => ['cors']], function($router){
	//获取验证码
	$router->post('app/user/smscode', ['uses' => 'Terminal\ClientController@smscode']);
	//注册
	$router->post('app/user/add', ['uses' => 'Terminal\ClientController@add']);
	//重置密码
	$router->post('app/user/resetpwd', ['uses' => 'Terminal\ClientController@resetPwd']);
	//登录
	$router->post('app/user/login', ['uses' => 'Terminal\ClientController@login']);

    //动态列表
    $router->post('app/user/dynamic', []);
    //动态详情

    //动态分类

});

$router->group(['middleware' => ['cors', 'auth']], function ($router){
    //个人信息修改

    //上传头像

    //获取个人信息

    //我喜欢的

    //已有分类

    //新增分类

    //编辑分类

    //已有日记

    //新增日记

    //编辑日记

    //设置喜欢

    //评论动态

    //跟评（追加别人的评论）


});
