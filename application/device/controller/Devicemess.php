<?php
/*
*联通居家安防三件套数据接入
*/
namespace app\device\controller;

use think\Controller;
use think\Request;

class Devicemess extends Controller {

    /*
    *烟感报警推送信息
    */
    public function warning_sd(){
        if (Request()->isPost()) {
            model('Security')->smokedetector();
        }
    }

    /*
    *燃气报警推送消息
    */
    public function warning_fg(){
    	if (Request()->isPost()) {
            model('Security')->fuelgas();
        }
    }   

    /*
    *红外报警推送消息
    */
    public function warning_ifrared(){
        if (Request()->isPost()) {
            model('Security')->infrared();
        }
    }  

    /*
    *获取IP地址 目前测试为 101.89.115.24 非该IP地址不计入数据
    *这里是添加终端连接记录
    *数据由gatewayworker推送
    */
    public function add_device_connect(){
        $sign = input('sign');
        if ($sign=='yunchiiot') {
            model('Deviceconnect')->AddDeviceConnectLog();
        }
    }
}