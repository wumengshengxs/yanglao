<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/2
 * Time: 13:13
 * 权限模块
 */
namespace app\index\controller;

use app\index\controller\Common;
use think\Request;

class Auth extends Common {
    /**
     * 管理员列表
     */
    public function user()
    {
        $user = model('User')->userList();
        $this->assign('user',$user);
        // 获取角色列表
        $whereRole = " `r_status`=1";
        $role = model('Role')->roleList($whereRole);
        $this->assign('role',$role);
        $this->assign('title','养老平台 - 管理员列表');
        return view();
    }

    /**
     * 提交管理员信息
     */
    public function submitUser()
    {
        $request = Request::instance();
        $id = addslashes($request->post('id'));
        $name = addslashes($request->post('name'));
        $password = addslashes($request->post('password'));
        $role = addslashes($request->post('role'));
        $mobile = addslashes($request->post('mobile'));
        $email = addslashes($request->post('email'));
        $remarks = addslashes($request->post('remarks'));
        $status = !is_null($request->post('status')) ? 1 : 2;
        $result = model('User')->submitUser($id, $name, $password, $role, $mobile, $email, $remarks, $status);
        return $result;
    }

    /**
     * 菜单页面
     */
    public function menu()
    {
        $this->assign('title','养老平台 - 菜单列表');
        return view();
    }

    /**
     * 菜单列表
     */
    public function menuList()
    {
        $status = Request::instance()->post('status');
        $whereMenu = $status ? " `m_status`=".$status : '';
        $menu = model('Menu')->menuList($whereMenu);
        return $menu;
    }

    /**
     * 提交菜单信息
     */
    public function submitMenu()
    {
        $request = Request::instance();
        $id = addslashes($request->post('id'));
        $name = addslashes($request->post('name'));
        $icon = addslashes($request->post('icon'));
        $url = addslashes($request->post('url'));
        $pid = (int)$request->post('pid');
        $status = !is_null($request->post('status')) ? 1 : 2;
        $weight = (int)$request->post('weight');
        $result = model('Menu')->submitMenu($id, $name, $icon, $url, $pid, $status, $weight);
        return $result;
    }


    /**
     * 角色列表
     */
    public function role()
    {
        $role = model('Role')->roleList();
        $this->assign('role',$role);
        $this->assign('title','养老平台 - 角色列表');
        return view();
    }

    /**
     * 提交角色信息
     */
    public function submitRole()
    {
        $request = Request::instance();
        $id = addslashes($request->post('id'));
        $name = addslashes($request->post('name'));
        $remarks = addslashes($request->post('remarks'));
        $status = !is_null($request->post('status')) ? 1 : 2;
        $menu_id = addslashes($request->post('menu_id'));
        $result = model('Role')->submitRole($id, $name, $remarks, $status, $menu_id);
        return $result;
    }

    /**
     * 删除角色
     */
    public function delRole()
    {
        $id = addslashes(Request::instance()->post('id'));
        $result = model('Role')->delRole($id);
        return $result;
    }

    /*
    *节点菜单删除
    */
    public function delmenu(){
        if (Request()->isPost()) {
            $id = addslashes(Request::instance()->post('id'));
            $result = model('Menu')->delMenuInfo($id);
            return $result;
        }
    }
}