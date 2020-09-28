<?php
/**
 * 服务商登陆.
 * User: Hongfei
 * Date: 2019/3/14
 * Time: 下午2:23
 */

namespace app\server\controller;


use think\Controller;
use think\Request;
use think\Session;


class Login extends Controller
{
    /**
     * 服务商登录页面
     */
    public function login()
    {
        $this->assign('title','服务商管理 - 登录');
        return view();
    }

    /**
     * 服务商验证登录
     */
    public function checkLogin()
    {
        $request = Request::instance();
        $name = $request->post('name');
        $password = $request->post('password');
        $result = model('Provider')->login($name, $password);
        return $result;
    }

    /**
     * 服务商退出登陆
     * @return bool
     */
    public function logout()
    {
        $out = Session::delete('provider_info');
        if ($out){
            return true;
        }
    }


}