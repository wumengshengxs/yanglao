<?php
/**
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/3/14
 * Time: 下午2:23
 */

namespace app\server\controller;

use think\Controller;
use think\Session;

class Common extends Controller
{
    /**
     * 登陆用户信息
     */
    public $user_info;

    /**
     * 初始化
     */
    public function _initialize()
    {
        $this->user_info = Session::get('provider_info');
        // 未登录状态
        if(!$this->user_info){
            $this->redirect('/server/Login/login');
            exit;
        }
    }
}