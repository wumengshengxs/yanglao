<?php
/**
*设备退换
*/
namespace app\index\controller;

use app\index\controller\Common;
use think\Request;

class Devicewithdraw extends Common {

	/*
	*设备退换列表
	*/
	public  function index(){
		$data = model('Alterlog')->GetDeviceMaintainInfo($state=2);
		$passage = model('DevicePassage')->passageList();
		$this->assign('passage',$passage['passage']['data']);
		$this->assign('data',$data);
		return $this->fetch();
	}

	/*
	*设备退换添加记录
	*/
	public  function add(){
		if (Request()->isAjax()) {
			$res = model('Alterlog')->WithdrawAdd();
			return $res;
		}	
	}

	/*
	*设备退换记录删除
	*/
	public  function del(){
		if (Request()->isAjax()) {
			$res = model('Alterlog')->WithdrawDel();
			return $res;
		}	
	}

	/*
	*设备退换记录修改
	*/
	public function save(){
		if (Request()->isAjax()) {
			$res = model('Alterlog')->DeviceMaintainSave();
			return $res;
		}	
	}

	/*
	*设备变更模块
	*/
	public function devicechange(){
		$passage = model('DevicePassage')->passageList();
        $this->assign('passage',$passage['passage']['data']);
        $res = model('Alterlog')->GetDeviceMaintainAllInfo();
        // bug($res);
        $this->assign('data',$res);
		return $this->fetch();
	}

	/*
	*设备变更手动添加
	*/
	public function devicechange_add(){
		$res = model('Alterlog')->DevicechangeAdd();
		return $res;
	}

	/*
	*设备变更删除
	*/
	public function devicechange_del(){
		$res = model('Alterlog')->DevicechangeDel();
		return $res;
	}

	/*
	*设备变更记录修改
	*/
	public function devicechange_save(){
		$res = model('Alterlog')->DevicechangeUpdate();
		return $res;
	}

	/*
	*设备变更记录导出
	*/
	public function devicechange_down(){
		model('Alterlog')->DevicechangeDownInfo();
	}
}