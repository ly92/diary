<?php
/**
 * Created by PhpStorm.
 * User: ly
 * Date: 2019/4/11
 * Time: 下午5:35
 */

namespace App\Http\Middleware;

//跨域中间件
class BeforeCorsMiddleware{
    public function handle($request, \Closure $next){
        //解决代码抛错400 或者代码执行错误 都报错500问题
        header('Access-Control-Allow-Origin:*');//允许访问的地址
        header('Access-Control-Allow-Methods:GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');//允许访问的方法
        header('Access-Control-Allow-Headers:Access-Control-Allow-Origin:x_requested_with');//允许的参数
        return $next($request);
    }
}