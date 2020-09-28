<?php

/**
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/3/20
 * Time: 下午1:12
 */

namespace app\api\controller;


use think\Request;

class Server extends Base
{

    /**
     * 获取工单列表
     * @return string
     */
    public function index()
    {
        $status = $this->wx_user['status'];
        $work   = model('ProviderWork')->getWorkServer($status);

        $this->ajaxReturn(array('code'=>0,'msg'=>'获取成功','data'=>$work));
    }

    /**
     * 最新工单查看
     */
    public function workStart()
    {
        $post   = Request::instance()->post();
        $status = $this->wx_user['status'];
        $work   = model('ProviderWork')->workStart($post,$status);
        $this->ajaxReturn(array('code'=>0,'msg'=>'获取成功','data'=>$work));
    }

    /**
     * 获取上一条记录
     */
    public function workUp()
    {
        $post   = Request::instance()->post();
        $status = $this->wx_user['status'];
        $work   = model('ProviderWork')->workUp($post,$status,1);
        $this->ajaxReturn(array('code'=>0,'msg'=>'获取成功','data'=>$work));
    }

    /**
     * 获取下一条记录
     */
    public function workNext()
    {
        $post   = Request::instance()->post();
        $status = $this->wx_user['status'];
        $work   = model('ProviderWork')->workUp($post,$status,2);
        $this->ajaxReturn(array('code'=>0,'msg'=>'获取成功','data'=>$work));
    }

    /**
     * 开始接单
     */
    public function workTake()
    {
        $post = Request::instance()->post();
        $sid  = $this->wx_user['id'];
        $work = model('ProviderWork')->workTake($post,$sid);
        $this->ajaxReturn($work);
    }

    /**
     * 结单
     */
    public function workClose()
    {
        $post = Request::instance()->post();
        $work = model('ProviderWork')->workClose($post);
        $this->ajaxReturn($work);
    }

    /**
     * 已结工单
     */
    public function workMark()
    {
        $post = Request::instance()->post();
        $work = model('ProviderWork')->workMark($post);

        $this->ajaxReturn(array('code'=>0,'msg'=>'获取成功','data'=>$work));
    }

    /**
     * 工单列表查看
     */
    public function workShow()
    {
        $post = Request::instance()->post();
        $status = $this->wx_user['status'];
        $id     = $this->wx_user['id'];
        $work[0]   = model('ProviderWork')->workShow($post,$status,$id,0);
        $work[1]   = model('ProviderWork')->workShow($post,$status,$id,[2]);
        $work[2]   = model('ProviderWork')->workShow($post,$status,$id,[3]);
        $work[3]   = model('ProviderWork')->workShow($post,$status,$id,[2,3]);

        $this->ajaxReturn(array('code'=>0,'msg'=>'获取成功','data'=>$work));
    }

}









