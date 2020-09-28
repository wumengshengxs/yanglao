<?php
/**
 * 人员管理
 * User: Hongfei
 * Date: 2019/3/19
 * Time: 上午11:16
 */

namespace app\server\controller;

use think\Request;

class Staff extends Common
{

    /**
     * 人员管理列表
     */
    public function index()
    {
        $param = Request::instance()->get();
        $project = model('Provider')->projectList();
        $pid = $this->user_info['id'];
        $staff   = model('ProviderStaff')->getStaffList($param,$pid);
        $this->assign('title','服务商平台 - 人员管理');
        $this->assign('project',$project);
        $this->assign('staff',$staff);

        return view();
    }

    /**
     * 添加管理人员
     */
    public function addStaff()
    {
        $post = Request::instance()->post();
        $pid = $this->user_info['id'];
        $user = model('ProviderStaff')->addStaff($post,$pid);

        return $user;
    }

    /**
     * 编辑人员
     * @param $id=>人员id
     * @return mixed
     */
    public function editStaff($id)
    {
        if (request()->isPost()){
            $post = Request::instance()->post();
            $staff= model('ProviderStaff')->editStaff($post);
        }else{
            $staff= model('ProviderStaff')->getStaff($id);
        }
        return $staff;

    }

}








