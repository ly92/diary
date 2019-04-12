<?php
/**
 * Created by PhpStorm.
 * User: ly
 * Date: 2019/4/11
 * Time: 下午7:11
 */

namespace App\Http\Model\Client;


use App\Http\Model\BaseModel;
use Illuminate\Support\Facades\DB;

class ClientModel extends BaseModel
{
    private static $table = 'ly_client';

    public function __construct()
    {
        parent::__construct();
    }

    //添加用户
    public function add($array){
        return DB::table(self::$table)->insertGetId($array);
    }

    //获取用户
    public function getClient($cid, $checkStatus){
        $db = DB::table(self::$table)->where('cid', $cid);
        if ($checkStatus){
            $db -> where('isforbidded', 0);
        }
        return $db->first();
    }

    //获取用户
    public function getByMobile($mobile, $checkStatus){
        $db = DB::table(self::$table)->where('mobile', $mobile);
        if ($checkStatus){
            $db -> where('isforbidded', 0);
        }
        return $db->first();
    }

    //更新用户
    public function update($cid, $array){
        return DB::table(self::$table)->update($array)->where('cid', $cid);
    }

}