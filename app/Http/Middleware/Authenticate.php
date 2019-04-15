<?php

namespace App\Http\Middleware;

use App\Http\Service\Client\ClientService;
use App\Http\Service\Helper\CommonHelper;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Filesystem\Cache;

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
        if (CommonHelper::isPro()){
            $time = 30;
        }else{
            $time = 60 * 24 * 60;
        }

        //时间是否过期
        if (abs(time() - $ts) > $time * 60){
            throw new \Exception('登录信息已经过期，请重新登录!', 1228);
        }
        //验证密码
        $clientService = new ClientService();
        $client = $clientService->getClient($cid);
        if ($client == null){
            throw new \Exception('无此用户！', 1226);
        }

        $localSign = md5($cid . $ts . $cmdno . $client['passwd']);
        if ($localSign != $sign){
            app('log')->debug('local: ' . $localSign . ', sign: ' . $sign);
            throw new \Exception('鉴权失败，请重新登录！', 1226);
        }
        Cache::put('STAR:CMDNO:'.$cid.$cmdno, 1, 30);

        //判断是不是需要统计的接口 如果是则进行计数
//        try{
//            $routeInfo = $request->route()[1];
//            if (isset($routeInfo['accessLogType']) && $routeInfo['accessLogType']){
////                (new Access)
//            }
//        } catch (\Exception $e){
//
//        }

        return $next($request);
    }
}
