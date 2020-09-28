<?php

/**
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/3/20
 * Time: 下午1:12
 */

namespace app\wechat\controller;


use think\Controller;
use think\Session;

class Base extends Controller
{
    public $wx_user;
    /**
     * 初始化
     */
    public function _initialize()
    {


    }


}