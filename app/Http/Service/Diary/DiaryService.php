<?php

/**
 * Created by PhpStorm.
 * User: ly
 * Date: 2019/4/15
 * Time: 下午2:18
 */

namespace App\Http\Service\Diary;

use App\Http\Model\Diary\DiaryModel;
use App\Http\Service\BaseService;

class DiaryService extends BaseService
{
    private $diaryModel;

    public function __construct()
    {
        parent::__construct();
        $this->diaryModel = new DiaryModel();
    }


    //已有分类
    public function getCategory($cid){
        return $this->diaryModel->getCategory($cid);
    }

    //新增分类
    public function addCategory($cid, $title){
        if (empty($title)){
            throw new \Exception('分类标题不可为空!', 1001);
        }
        return $this->diaryModel->addCategory(['cid' => $cid, 'title' => $title]);
    }

    //编辑分类
    public function updateCategory($id, $cid, $title){
        if (empty($title)){
            throw new \Exception('分类标题不可为空!', 1001);
        }
        return $this->diaryModel->updateCategory($id, $cid, ['title' => $title]);
    }

    //已有日记
    public function getDiaries($cid, $starttime, $stoptime, $skip = 0, $limit = 10){
        if ($starttime > $stoptime){
            throw new \Exception('开始时间不得晚于结束时间!', 1002);
        }
        if ($starttime > time()){
            throw new \Exception('开始时间不得晚于当前时间!', 1002);
        }

        return $this->diaryModel->getDiaries($cid, $starttime, $stoptime, $skip, $limit);
    }

    //新增日记
    public function addDiary($cid, $categoryId, $content, $address, $moodId, $images, $videos){
        if (empty($categoryId) || $this->getCateById($categoryId) == null){
            throw new \Exception('日记分类无效！', 1003);
        }
        if (empty($content)){
            throw new \Exception('日记内容不可为空！', 1004);
        }
        $data['cid'] = $cid;
        $data['category_id'] = $categoryId;
        $data['content'] = $content;
        if (!empty($moodId) && $this->getMoodById($moodId) != null){
            $data['mood_id'] = $moodId;
        }
        if (!empty($address)){
            $data['address'] = $address;
        }

        //图片
        if (!empty($images)) {
            foreach ($images as $key => $image) {
                $len = $key;
                if (!in_array(strtolower($image->extension()), ['jpeg', 'jpg', 'gif', 'gpeg', 'png'])) {
                    throw new \Exception('仅支持jpeg, jpg, gif, gpeg, png格式图片!', 1005);
                }

                if ($image->getClientSize() > 500 * 1024) {
                    throw new \Exception('图片不可超过500kb !', 1005);
                }
            }
            if ($len > 9) {
                throw new \Exception('最多只可上传9张图片!', 1005);
            }

            $m = 0;
            $k = 0;
            for ($i = 0; $i < $len; $i++) {
                //$n 表示第几张图片
                $n = $i + 1;
                if ($images[$i]->isvalid()) {
                    if (in_array(strtolower($images[$i]->extension()), ['jpeg', 'jpg', 'gif', 'gpeg', 'png'])) {
                        $imgName = $images[$i]->getClientOriginalName();//获取上传原文件名
                        $ext = $images[$i]->getClientOriginalExtension();//获取上传文件的后缀名
                        // 重命名
                        $filename = time() . Str::random(6) . "." . $ext;
                        if ($images[$i]->move("uploads/images", $filename)) {
                            $newFileName = '/' . "uploads/images" . '/' . $filename;
                            $m = $m + 1;
                        } else {
                            $k = $k + 1;
                        }
                    }
                }
            }
        }

        //视频


        return $this->diaryModel->addDiary($data);
    }

    //编辑日记
    public function updateDiary($id, $cid, $categoryId, $content, $address, $moodId, $images, $videos){
        if (empty($categoryId) || $this->getCateById($categoryId) == null){
            throw new \Exception('日记分类无效！', 1003);
        }
        if (empty($content)){
            throw new \Exception('日记内容不可为空！', 1004);
        }
        $data['category_id'] = $categoryId;
        $data['content'] = $content;
        if (!empty($moodId) && $this->getMoodById($moodId) != null){
            $data['mood_id'] = $moodId;
        }
        if (!empty($address)){
            $data['address'] = $address;
        }
        return $this->diaryModel->updateDiary($id, $cid, $data);
    }


    //获取分类
    function getCateById($id){
        return $this->diaryModel->getCateById($id);
    }

    //获取心情气氛
    function getMoodById($id){
        return $this->getMoodById($id);
    }
}