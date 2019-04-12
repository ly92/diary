<?php 

namespace App\Http\Controllers\Terminal;

use App\Http\Controllers\IController;
use App\Http\Service\Client\ClientService;
use App\Http\Service\Sms\ShortMessageService;
use Hamcrest\Thingy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ClientController extends IController {

    private $clientService;
    private $shortMessageService;

    public function __construct()
    {
        parent::__construct();
        $this->clientService = new ClientService();
        $this->shortMessageService = new ShortMessageService();
    }

    //获取验证码
	public function smscode(Request $request){
        $this->validatorRequest($request->all(), [
            'mobile' => 'required',
            'type' => 'required'//1:注册 2：重置登陆密码
        ]);
        $mobile = $request->input('mobile');
        $type = $request->input('type');

        $code = $this->shortMessageService->smsCode($mobile, $type);
        $this->setContent(['code' => $code]);
        return $this->response();
	}

	//注册
	public function add(Request $request){
		 $this->validatorRequest($request->all(), [
		 	'mobile' => 'required',
		 	'code' => 'required',
		 	'nickname' => 'required',
		 	'passwd' => 'required|size:32',
		 ]);
		$mobile = $request->input('mobile');
		$nickname = $request->input('nickname');
		$passwd = $request->input('passwd');
		$code = $request->input('code');

        $this->checkSmsCode($mobile, $code);

		try{
		    DB::beginTransaction();
		    $cid = $this->clientService->add($mobile, $nickname, $passwd);
		    $client = $this->clientService->getClient($cid);
		    DB::commit();
		    $this->setContent(['user' => $client]);
        }catch (\Exception $e){
		    DB::rollBack();
		    throw $e;
        }
		return $this->response();
	}

	//登录
	public function login(Request $request){
		$this->validatorRequest($request->all(),[
            'mobile' => 'required',
            'ts' => 'required',
            'sign' => 'required',
            'platform' => 'required',
            'device' => 'required'
        ]);
		$mobile = $request->input('mobile');
		$ts = $request->input('ts');
		$sign = $request->input('sign');
		$platform = $request->input('platform');
		$device = $request->input('device');

        //时间检验
        if (abs(time() - $ts) > 60 * 120){
            throw new \Exception('无效的时间', 1226);
        }
        $client = $this->getClientByMobile($mobile);
        //密码检验
        $localSign = md5($mobile . $ts . $client['passwd']);
        if ($localSign != $sign){
            throw new \Exception('帐号密码验证失败，请重新登陆', 10307);
        }
        //更新登陆时间
        $this->clientService->update($client['cid'], ['last_login_time' => time()]);

        $this->setContent([
            'user' => $client
        ]);
        return $this->response();
	}

	//重置密码
	public function resetPwd(Request $request){
        $this->validatorRequest($request->all(),[
            'mobile' => 'required',
            'passwd' => 'required',
            'code' => 'required'
        ]);
        $code = $request->input('code');
        $mobile = $request->input('mobile');
        $passwd = $request->input('passwd');

        $this->checkSmsCode($mobile, $code);

        $client = $this->getClientByMobile($mobile);
        if ($client){
            $this->clientService->update($client['cid'], ['passwd' => $passwd]);
        }else{
            throw new \Exception('该手机未注册', 10305);
        }
        return $this->response();
	}

	//修改密码
    public function updatePwd(Request $request){
        $this->validatorRequest($request->all(), [
            'cid' => 'required',
            'oldpwd' => 'required|size:32',
            'newpwd' => 'required|size:32'
        ]);
        $cid = $request->input('cid');
        $oldpwd = $request->input('oldpwd');
        $newpwd = $request->input('newpwd');
        if ($oldpwd == $newpwd){
            throw new \Exception('新旧密码不可一样', 10304);
        }
        $client = $this->clientService->getClient($cid);
        if ($client['passwd'] != $oldpwd){
            throw new \Exception('原密码错误', 10303);
        }
        $this->clientService->update($cid, ['passwd' => $newpwd, 'last_update' => time()]);
        return $this->response();
    }


	//根据手机号获取用户
    private function getClientByMobile($mobile){
	    $client = $this->clientService->getByMobile($mobile, true);
	    if ($client == null){
	        throw new \Exception('用户不存在', 10302);
        }
        return $client;
    }

    //用户详情
    public function get(Request $request){
        $this->validatorRequest($request->all(), [
           'cid' => 'required'
        ]);
        $cid = $request->input('cid');
        $client = $this->clientService->getClient($cid);
        $this->setContent(['user' => $client]);
        return $this->response();
    }

    //修改名称、性别
    public function modify(Request $request){
        $this->validatorRequest($request->all(), [
           'cid' => 'required'
        ]);
        $cid = $request->input('cid');

        $client = $this->clientService->getClient($cid);
        $nickname = $request->input('nickname', $client['nickname']);
        $gender = $request->input('gender', $client['gender']);
        $this->clientService->update($cid, ['nickname' => $nickname, 'gender' => $gender]);
        return $this->response();
    }

    //检验手机验证码
    private function checkSmsCode($mobile, $code){
        $code1 = Cache::get('STAR:SMSCODE:' . $mobile);
        if ($code1 == null || $code1 != $code){
            throw new \Exception('验证码无效', 10301);
        }
        Cache::forget('STAR:SMSCODE:' . $mobile);
    }
}