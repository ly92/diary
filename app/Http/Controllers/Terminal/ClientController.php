<?php 

namespace App\Http\Controllers\Terminal;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class ClientController extends BaseController{


	//获取验证码
	public function smscode(Request $request){

	}

	//注册
	public function add(Request $request){
		// $this->validatorRequest($request->all(), [
		// 	'mobile' => 'required',
		// 	'nickname' => 'required',
		// 	'passwd' => 'required',
		// ]);
		$mobile = $request->input('mobile');
		$nickname = $request->input('nickname');
		$passwd = $request->input('passwd');

		return '111'.$mobile.$nickname.$passwd;
	}

	//登录
	public function login(){
		return 'login';
	}

	//重置密码
	public function resetpwd(){

	}


}