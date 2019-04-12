<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $cid = $request->input('cid');
        $ts = $request->input('ts');
        $cmdno = $request->input('cmdno');
        $sign = $request->input('sign');

        if (!$cid || !$sign){
            throw new \Exception('获取鉴权参数失败！', 1227);
        }
        if (!$cmdno){
            throw new \Exception('缺少请求流水参数！', 1229);
        }

        //是否为正式环境

        //时间是否过期

        //


        return $next($request);
    }
}
