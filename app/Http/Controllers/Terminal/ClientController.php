<?php 

namespace App\Http\Controller\Terminal;

use Illuminate\Http\Request;

class ClientController extends BaseController{


	//获取验证码
	public function smscode(Request $request){

	}

	//注册
	public function add(Request $request){
		system('whoami');
		$this->validatorRequest($request->all(), [
			'mobile' => 'required',
			'nickname' => 'required',
			'passwd' => 'required',
		]);
		$mobile = $request->post('mobile');
		$nickname = $request->input('nickname');
		$passwd = $request->input('passwd');

		return '111'.$mobile.$nickname.$passwd;
	}

	//登录
	public function login(){

	}

	//重置密码
	public function resetpwd(){

	}


}