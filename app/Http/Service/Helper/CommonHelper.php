<?php
/**
 * Created by PhpStorm.
 * User: ly
 * Date: 2019/4/12
 * Time: 下午7:49
 */

namespace App\Http\Service\Helper;


use App\Http\Service\BaseService;

class CommonHelper extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    //检查当前环境是否为测试环境
    public static function isPro(){
        $result = true;
        if (env('APP_ENV') != 'production'){
            $result = false;
        }
        return $result;
    }

    //无限分类遍历方法
    public static function getDataTree($rows, $id = 'privilegeid', $pid = 'parentid', $child = 'children', $root = 0){
        $tree = [];
        if (is_array($rows)){
            //设置每一个的下级为数组
            foreach ($rows as &$row) {
                $row[$child] = [];
            }
            //以ID为key，新建数组
            $array = [];
            foreach ($rows as $key => $item){
                $array[$item[$id]] = &$rows[$key];
            }

            //加入树
            foreach ($rows as $key => $item){
                $parentid = $item[$pid];
                if ($parentid == $root){
                    $tree[] = &$rows[$key];
                }else{
                    if (isset($array[$item[$parentid]])){
                        $parent = &$array[$parentid];
                        $parent[$child] = &$rows[$key];
                    }
                }
            }
        }
        return $tree;
    }

    //小时、分钟、秒转化为秒数
    public static function timeToSec($time){
        $sec = strtotime($time);
        if ($sec !== false){
            $sec = $sec - strtotime('Y-m-d', time());
        }
        return $sec;
    }

    //秒数转化为小时、分钟、秒
    public static function secToTime($sec, $format = 'H:i:s'){
        if (is_numeric($sec)){
            return date($format, strtotime(date('Y-m-d', time()) + $sec));
        }
        return false;
    }

    //二维数组，按照key归类
    static function array_group_by($arr, $key){
        $grouped = [];

        if (is_array($arr)){
            foreach ($arr as $value){
                $grouped[$value[$key]][] = $value;
            }
        }

        if (func_num_args() > 2){
            $args = func_get_args();
            foreach ($grouped as $key => $value){
                $params = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array('array_group_by', $params);
            }
        }
        return $grouped;
    }

}