<?php

/**
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/3/20
 * Time: 下午1:12
 */

namespace app\api\controller;

use think\Request;


class Login
{

    /**
     * 登陆 调用wx小程序接口
     */
    public function Login()
    {
        $wxData = Request::instance()->post();
        $login = model('ProviderStaff')->Login($wxData);

        return json_encode($login);

    }



}