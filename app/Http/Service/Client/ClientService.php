<?php

/**
 * Created by PhpStorm.
 * User: ly
 * Date: 2019/4/11
 * Time: 下午7:06
 */

namespace App\Http\Service\Client;

use App\Http\Model\Client\ClientModel;
use App\Http\Service\BaseService;
use Illuminate\Support\Facades\DB;

class ClientService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    //添加用户
    public function add($mobile, $nickname, $passwd){
        $id = (new ClientModel())->add([
            "mobile" => $mobile,
            "realname" => $nickname,
            "passwd" => $passwd,
            "creationtime" => time()
        ]);
        if ($id <= 0){
            throw new \Exception('添加用户失败', 10311);
        }
        return $id;
    }

    //获取用户
    public function getClient($cid){
        return (new ClientModel())->getClient($cid, true);
    }

    //获取用户
    public function getByMobile($mobile, $checkStatus){
        return (new ClientModel())->getByMobile($mobile, $checkStatus);
    }

    //更新用户
    public function update($cid, $array){
        return (new ClientModel())->update($cid, $array);
    }

}