<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    

	public function hook(){
		//查看当前账户，我服务器用的是nginx，所以这里返回的用户是‘nginx’
        system('whoami');

        //重定位
        system('sudo cd /home/diary');
        //这一步很关键
        system('sudo unset GIT_DIR');
        system('sudo git pull');
        return 'Hello ly !';
	}

}
