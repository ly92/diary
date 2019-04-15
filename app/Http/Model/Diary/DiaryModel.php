<?php

/**
 * Created by PhpStorm.
 * User: ly
 * Date: 2019/4/15
 * Time: 下午2:17
 */
namespace App\Http\Model\Diary;

use App\Http\Model\BaseModel;
use Illuminate\Support\Facades\DB;


class DiaryModel extends BaseModel
{
    private static $categoryTable = 'ly_diary_category';
    private static $diariesTable = 'ly_diary';
    private static $moodTable = 'ly_diary_mood';

    public function __construct()
    {
        parent::__construct();
    }

    //已有分类
    public function getCategory($cid){
        return DB::table(self::$categoryTable)->select('*')->where('cid', $cid)->get();
    }

    //新增分类
    public function addCategory($array){
        return DB::table(self::$categoryTable)->insertGetId($array);
    }

    //编辑分类
    public function updateCategory($id, $cid, $array){
        return DB::table(self::$categoryTable)->update($array)->where('cid', $cid)->where('id', $id);
    }

    //已有日记
    public function getDiaries($cid, $starttime, $stoptime, $skip, $limit){
        $db = DB::table(self::$diariesTable)->select('*')
            ->where('cid', $cid)
            ->where('creationtime', '>', $starttime)
            ->where('creationtime', '<', $stoptime);
        return $db->skip($skip)->limit($limit)->get();
    }

    //新增日记
    public function addDiary($array){
        return DB::table(self::$diariesTable)->insertGetId($array);
    }

    //编辑日记
    public function updateDiary($id, $cid, $array){
        return DB::table(self::$diariesTable)->update($array)->where('id', $id)->where('cid', $cid);
    }

    //获取分类
    function getCateById($id){
        return DB::table(self::$categoryTable)->select('*')->where('id', $id);
    }

    //获取心情气氛
    function getMoodById($id){
        return DB::table(self::$moodTable)->select('*')->where('id', $id);
    }
}