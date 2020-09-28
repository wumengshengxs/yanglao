<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/2
 * Time: 9:21
 * 公共模块
 */
namespace app\index\controller;

use think\Controller;
use think\Session;
use think\Request;

class Common extends Controller {
    /**
     * 登陆用户信息
     */
    public $user_info;

    /**
     * 初始化
     */
    public function _initialize()
    {
        $this->user_info = Session::get('S_USER_INFO');
        // 未登录状态
        if(!$this->user_info){
            $this->redirect('/index/Login/login');
            exit;
        }
    }

}