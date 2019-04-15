<?php
/**
 * Created by PhpStorm.
 * User: ly
 * Date: 2019/4/15
 * Time: 下午2:16
 */

namespace App\Http\Controllers\Terminal;


use App\Http\Controllers\IController;
use App\Http\Service\Diary\DiaryService;
use Illuminate\Http\Request;

class DiaryController extends IController
{
    private $diaryService;
    public function __construct()
    {
        parent::__construct();
        $this->diaryService = new DiaryService();
    }


    //已有分类
    public function getCategory(Request $request){
        $this->validatorRequest($request->all(),[
           'cid' => 'required',
        ]);
        $cid = $request->input('cid');
        $list = $this->diaryService->getCategory($cid);
        $this->setContent(['list' => $list]);
        return $this->response();
    }

    //新增分类
    public function addCategory(Request $request){
        $this->validatorRequest($request->all(), [
           'cid' => 'required',
            'title' => 'required',
        ]);
        $cid = $request->input('cid');
        $title = $request->input('title');
        $this->diaryService->addCategory($cid, $title);
        return $this->response();
    }

    //编辑分类
    public function updateCategory(Request $request){
        $this->validatorRequest($request->all(), [
            'id' => 'required',
            'cid' => 'required',
            'title' => 'required',
        ]);
        $id = $request->input('id');
        $cid = $request->input('cid');
        $title = $request->input('title');
        $this->diaryService->updateCategory($id, $cid, $title);
        return $this->response();
    }

    //已有日记
    public function getDiaries(Request $request){
        $this->validatorRequest($request->all(),[
            'cid' => 'required',
            'starttime' => 'required',
            'stoptime' => 'required',
            'skip' => 'required',
            'limit' => 'required',
        ]);
        $cid = $request->input('cid');
        $starttime = $request->input('starttime');
        $stoptime = $request->input('stoptime');
        $skip = $request->input('skip');
        $limit = $request->input('limit');
        $list = $this->diaryService->getDiaries($cid, $starttime, $stoptime, $skip, $limit);
        $this->setContent(['list' => $list]);
        return $this->response();
    }

    //新增日记
    public function addDiary(Request $request){
        $this->validatorRequest($request->all(),[
            'cid' => 'required',
            'categoryid' => 'required',
            'content' => 'required',
        ]);
        $cid = $request->input('cid');
        $categoryId = $request->input('categoryid');
        $content = $request->input('content');
        $address = $request->input('address');
        $moodId = $request->input('moodid');
        $images = $request->file('images');
        $videos = $request->file('videos');
        $this->diaryService->addDiary($cid, $categoryId, $content, $address, $moodId, $images, $videos);
        return $this->response();
    }

    //编辑日记
    public function updateDiary(Request $request){
        $this->validatorRequest($request->all(),[
            'id' => 'required',
            'cid' => 'required',
            'categoryid' => 'required',
            'content' => 'required',
        ]);
        $id = $request->input('id');
        $cid = $request->input('cid');
        $categoryId = $request->input('categoryid');
        $content = $request->input('content');
        $address = $request->input('address');
        $moodId = $request->input('moodid');
        $images = $request->file('images');
        $videos = $request->file('videos');
        $this->diaryService->updateDiary($id, $cid, $categoryId, $content, $address, $moodId, $images, $videos);
        return $this->response();
    }
}