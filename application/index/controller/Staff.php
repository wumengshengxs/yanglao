<?php
/**
 * 话务员管理
 * User: Administrator
 * Date: 2019/1/16
 * Time: 11:37
 */
namespace app\index\controller;

use think\Request;
use app\index\model\StaffUser;
use app\index\model\StaffGroup;
use app\index\model\Call;

class Staff extends Common {

    /**
     * 话务员展示
     * @return \think\response\View
     */
    public function staff()
    {
        $model = new StaffUser();
        $staff = $model->getUserList();
        $group = StaffGroup::getUserGroup();
        $number = Call::getNumber();
        $this->assign('staff',$staff['staff']);
        $this->assign('page',$staff['page']);
        $this->assign('number',$number);
        $this->assign('list',$group);
        return view();
    }

    /**
     * 添加话务员
     * @return mixed
     */
    public function addStaff()
    {
        $model = new StaffUser();
        $post = Request::instance()->post();
        $user = $model->addStaffUser($post);
        return $user;
    }

    /**
     * 查看话务员
     * @param $number
     * @return mixed
     */
    public function staffInfo($number)
    {
        $model = new StaffUser();
        $data = $model->getUser($number);
        $this->assign('user',$data);
        return view();
    }

    /**
     * 编辑话务员
     * @param $number
     * @return mixed
     */
    public function editStaff($number)
    {
        $model = new StaffUser();
        if (request()->isPost()){
            $post = Request::instance()->post();
            $staff = $model->editStaff($post);
            return $staff;
        }else{
            $data = $model->getEditUser($number);
            return $data;
        }
    }

    /**
     * 删除话务员
     */
    public function delStaffUser()
    {
        $post = Request::instance()->post();
        $model = new StaffUser();
        $data = $model->delStaffUser($post);
        return $data;
    }

    /**
     * 技能组展示
     * @return mixed
     */
    public function group()
    {

        $model = new StaffGroup();

        $data = $model->groupList();
        $this->assign('group',$data);
        return view();
    }

    /**
     * 添加技能组
     * @return mixed
     */
    public function addGroup()
    {
        $model = new StaffGroup();
        $post = Request::instance()->post();
        $group = $model->addGroup($post);
        return $group;
    }

    /**
     * 删除技能分组
     */
    public function delGroup()
    {
        $id = Request::instance()->post('id');
        $model = new StaffGroup;
        $data = $model->delGroup($id);
        return $data;
    }

}