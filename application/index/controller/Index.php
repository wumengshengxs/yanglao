<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/2
 * Time: 11:37
 * 首页模块
 */
namespace app\index\controller;

use app\index\controller\Common;
use think\Request;
use think\Session;

class Index extends Common {
    /**
     * 首页
     */
    public function index()
    {
        // 获取角色对应的菜单id
        $mid = '';
        if($this->user_info['is_super'] != 1){
            $roleInfo = model('Role')->RoleInfo($this->user_info['r_id']);
            $mid = $roleInfo['m_id'];
        }
        // 获取登录用户的左侧菜单
        $whereMenu = " `m_status`=1";
        $whereMenu .= $mid ? " AND `id` IN (".$mid.")" : "";
        $menu = model('Menu')->menuList($whereMenu);
        $this->assign('menu',$menu);
        $this->assign('user_info',$this->user_info);
        $this->assign('title','养老平台 - 首页');
        return view();
    }

    /**
     * main
     */
    public function main()
    {   
        /*
        *最新紧急呼叫工单
        */
        $where['type'] = ['in','1,2,3'];
        $gency = model('Work')->worksList($where,$query=[],$limit=5);
        $this->assign('gency',$gency['work']['data']);
        /*
        *积分情况
        */
        // 获取用户的总积分数、平均积分、最高和最低积分
        $clientIntegral = model('Integral')->clientIntegral();
        $this->assign('clientIntegral',$clientIntegral);
        // 获取发放和核销的总计分数
        $sumIntegralRecords = model('Integral')->sumIntegralRecords();
        $this->assign('sumIntegralRecords',$sumIntegralRecords);
        //今日寿星
        $Birthday = model('Main')->GetUserBirthday();
        $this->assign('birthday',$Birthday);
        return view();
    }

    /*
    *首页24小时工单查询
    */
    public function main_echarts(){
        
        /*
        *话务员人数
        */
        // $staff_user = model('Main')->GetStaffUser();
        /*
        *工单处理状态
        */
        $arr['order'] = model('Main')->GetHouseOrderAll();
        /*
        *7日工单趋势
        */
        $arr['cycle'] = model('Main')->GetHouseOrderCycleAll();

        /*
        *服务对象比例
        */
        $arr['sex']= model('Main')->GetUserAgeOrSex();

        /*
        *服务对象年龄比例
        */
        $arr['age'] = model('Main')->GetUserAgeOrAge();
        //分组比例
        $arr['group'] = model('Main')->GetUserGroup();
        //返回值
        return $arr;
    }
    /*
    *修改未读消息
    */
    public function savemess(){
        if (Request()->isAjax()) {
            $res = model('Work')->SaveMessStatus();
            return $res;
        }
    }

    /*
    *大屏信息
    */
    public function bigscreen(){
        //腕表数量
        $watches = model('Main')->GetWatchesNumber();
        $this->assign('watches',$watches);
        //分组比例
        $group = model('Main')->GetUserGroup();
        $this->assign('group',$group);
        return $this->fetch();
    }

    /*
    *大屏信息左侧菜单栏查询
    */
    public function leftmonitorsdata(){
        
        $arr['state'] = model('Main')->GetHouseOrderAll();
        /*
        *服务对象比例
        */
        $arr['sex']= model('Main')->GetUserAgeOrSex();
        /*
        *服务对象年龄比例
        */
        $arr['age'] = model('Main')->GetUserAgeOrAge();

        /*
        *进7日工单
        */
        $arr['cycle'] = model('Main')->GetHouseOrderCycleAll();
        //工单信息
        $arr['order_info']= model('Main')->GetMonitorsHouseOrderAll();
        return $arr;
    }
}