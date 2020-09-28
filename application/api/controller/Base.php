<?php

/**
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/3/20
 * Time: 下午1:12
 */

namespace app\api\controller;


use think\Controller;
use think\Session;
use app\api\model\ProviderStaff;

class Base extends Controller
{
    public $wx_user;
    /**
     * 初始化
     */
    public function _initialize()
    {
        $this->wx_user = Session::get('wx_user');
        header("Content-type: text/html;charset=utf-8");

        $sign = input('sign');
        $staff = new ProviderStaff();
        $this->wx_user = $staff->staffSign($sign);
        if(!$this->wx_user){
            $this->ajaxReturn(array('code'=>-199,'msg'=>'身份验证过期'));
        }

    }

    /**
     * 返回信息
     * @param $res
     */
    public function ajaxReturn($res) {
        exit(json_encode($res));
    }
}