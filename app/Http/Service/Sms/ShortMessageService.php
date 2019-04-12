<?php

/**
 * Created by PhpStorm.
 * User: ly
 * Date: 2019/4/12
 * Time: 下午6:12
 */

namespace App\Http\Service\Sms;

use App\Http\Model\Client\ClientModel;
use App\Http\Service\BaseService;
use Illuminate\Support\Facades\Cache;

class ShortMessageService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    //1:注册 2：重置登陆密码
    public function smsCode($mobile, $type){
        $client = (new ClientModel())->getByMobile($mobile);
        if ($type == 1){
            if ($client){
                throw new \Exception('用户已存在', 110309);
            }
        }else if ($type == 2){
            if ($client == null){
                throw new \Exception('该手机号未注册', 110308);
            }
        }

        $code = rand(1000,9999);
        Cache::put('STAR:SMSCODE:' . $mobile, $code, 3);

        return $code;
    }
}