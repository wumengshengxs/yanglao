<?php
/**
 * 首页
 * User: Administrator
 * Date: 2019/3/14
 * Time: 13:26
 */
namespace app\server\controller;


class Index extends Common {


    /**
     * 首页
     */
    public function index()
    {
        // 获取登录用户的左侧菜单
        $whereMenu = " `m_status`=1";
        $menu = model('Menu')->menuList($whereMenu);
        $this->assign('menu',$menu);
        $this->assign('title','服务商平台 - 首页');
        return view();
    }

    /**
     * main
     */
    public function main()
    {
        return view();
    }
}