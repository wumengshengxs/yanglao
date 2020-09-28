<?php
/**
 * 工单管理
 * User: Hongfei
 * Date: 2019/3/19
 * Time: 上午9:35
 */

namespace app\server\controller;

use think\Request;

class Work extends Common
{

    /**
     * 工单列表
     */
    public function index()
    {
        $param = Request::instance()->get();
        $project = model('Provider')->projectList();
        $pid = $this->user_info['id'];
        $work = model('ProviderWork')->getWorkList($pid,$param);
        $this->assign('project',$project);
        $this->assign('work',$work);
        $this->assign('title','服务商平台 - 工单管理');
        return view();
    }


    /**
     * 工单明细
     * @param $id
     * @return \think\response\View
     */
    public function workDetails($id)
    {
        $provider = model('index/ProviderWork')->getWorkProvider($id);
        $this->assign('title','养老平台 - 服务工单明细');
        $this->assign('provider',$provider);

        return view();
    }


}