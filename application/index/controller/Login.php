<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/2
 * Time: 9:25
 * 登录模块
 */
namespace app\index\controller;

use think\Request;
use think\Controller;
use think\Session;
use app\index\model\StaffUser;

class Login extends Controller {

    /**
     * 检测登录状态
     */
    public function _initialize()
    {
        $action =  Request::instance()->action();
        if (!in_array($action, ['logout'])){
            if(Session::get('S_USER_INFO')){
                $this->redirect('/index/Index/index');
                exit;
            }
        }
    }

    /**
     * 登录页面
     */
    public function login()
    {
        $this->assign('title','养老平台 - 登录');
        return view();
    }

    /**
     * 验证登录
     */
    public function checkLogin()
    {
        $request = Request::instance();
        $name = addslashes($request->post('name'));
        $password = addslashes($request->post('password'));
        $type = addslashes($request->post('type'));
        // 管理员登录
        if($type == 0){
            $result = model('User')->login($name, $password);
            return $result;
        }
        // 话务员登录
        if($type == 1){
            $param = $request->post();
            $result = model('StaffUser')->login($param);
            return $result;
        }
    }

    /**
     * @return bool
     */
    public function logout()
    {
        $userInfo = Session::get('S_USER_INFO');
        if ($userInfo['type'] == 2) {
            $model = new StaffUser();
            $model->logout($userInfo['number']);
        }
        $out = Session::delete('S_USER_INFO');
        if ($out){
            return true;
        }
    }

}