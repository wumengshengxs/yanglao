<?php

namespace app\quqi\controller;


use think\Controller;
use think\Request;

/**
 * 曲奇推送api
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/8/15
 * Time: 上午9:12
 */
class Api extends Controller
{

    /**
     * 位置推送
     */
    public function location()
    {
        if (Request()->isPost()) {
            $post  = Request::instance()->post();
            model('Dispose')->location($post);
        }
    }

    /**
     * sos数据
     */
    public function sosData()
    {
        if (Request()->isPost()) {
            $post = Request::instance()->post();
            model('Dispose')->sosData($post);
        }
    }

    /**
     * 计步
     */
    public function step()
    {
        if (Request()->isPost()) {
            $post = Request::instance()->post();
            model('Dispose')->step($post);
        }
    }

    /**
     * 电量
     */
    public function electric()
    {
        if (Request()->isPost()) {
            $post = Request::instance()->post();
            model('Dispose')->electric($post);

        }
    }












}











