<?php
/**
 * Created by PhpStorm.
 * User: ly
 * Date: 2019/4/1
 * Time: 下午6:33
 */

namespace App\Http\Controllers;



use Illuminate\Support\Facades\Validator;

class IController extends Controller
{
    public function __construct()
    {
    }

    // 验证错误信息
    public $validateMessage = [
        'required' => ':attribute为必填项',
        'max' => ':attribute超出允许最大值',
        'min' => ':attribute超出允许最小值',
        'in' => ':attribute无效',
        'numeric' => ':attribute需要为数值',
        'array' => ':attribute期望值为数组',
        'date_format' => ':attribute时间格式为 2019-04-01',
    ];

    public $validateReturnString = true;

    /**
     * @param array $validator
     * @param array $rules
     * @param array $customAttributes
     * @param array $messages
     * @return array|string
     */
    protected function validatorHandle(array $validator, array $rules, array $customAttributes = [], array $messages = []){
        $messages = $messages ? $messages : $this->validateMessage;
        $validator = Validator::make($validator, $rules, $messages, $customAttributes);
        if ($this->validateReturnString){
            $result = '';
            if ($validator->fails()){
                $errors = $validator->errors()->all();
                $result = implode(',', $errors);
            }
        }else{
            $result = [];
            if ($validator->fails()){
                $errors = $validator->errors()->all();
                $result = $errors;
            }
        }
        return $result;
    }

    protected function validatorRequest(array $validator, array $rules, array $customAttributes = [], array $messages = []){
        $request = $this->validatorHandle($validator,$rules, $customAttributes, $messages);
        if ($request){
            throw  new \Exception($request, 9999);
        }
    }


    private $statusCode = 200;
    private $code = 0;
    private $message = '';
    private $content;
    private $contentEncrypt;

    public function getStatusCode(){
        return $this->statusCode;
    }

    public function setStatusCode($statusCode = 0){
        $this->statusCode = (int)$statusCode;
        return $this;
    }

    public function getCode(){
        return $this->code;
    }

    public function setCode($code = 0){
        $this->code = (int)$code;
        return $this;
    }

    public function getMessage(){
        return [
            'code' => $this->code,
            'message' => $this->message,
            'content' => $this->content === null ? '' : $this->content,
            'contentEncrypt' => $this->contentEncrypt ? $this->contentEncrypt : '',
        ];
    }

    public function setMessage($message = ''){
        $this->message = trim($message);
        return $this;
    }

    public function setContent(array $content){
        $this->content = $content;
        return $this;
    }

    public function singleContent(){
        app('log')->info('[REQUEST]' . app('request')->getPathInfo() . '(' . json_encode(app('request')->all()) . ') ==============>' . json_encode($this->content));
        return response()->json($this->content);
    }

    public function setError(\Exception $e){
        $message = $e->getMessage();
        $code = $e->getCode();
        $code = $code ? $code : 9999;
        $this->setMessage($message)->setCode($code);
        $this->content = null;
        app('log')->error($e);
        return $this;
    }

    public function response(){
        app('log')->info(' [REQUEST] ' . app('request')->getPathInfo() . '(' . json_encode(app('request')->all()). ') =============>' . json_encode($this->getMessage()));
        return response()->json($this->getMessage());
    }

    public function getErrFromException(\Exception $e){
        $code = $e->getCode();
        $message = $e->getMessage();
        return [$message, $code ? $code : 9999];
    }

}