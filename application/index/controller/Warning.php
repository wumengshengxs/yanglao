<?php
/**
*报警消息
*/
namespace app\index\controller;

use think\Request;

class Warning extends Common{

	/*
	*报警列表
	*/
	public function index(){
		$data = model('Work')->GetWarningList();
		$this->assign('data',$data);
		return $this->fetch();
	}

	/*
	*报警地图查看
	*/
	public function map(){
		$info = model('Work')->GetWarningMap();
		$this->assign('info',$info);
		return $this->fetch();
	}

	/*
	*大屏信息报警数据查询
	*/
	public function getofteninfo(){
		if (Request()->isAjax()) {
			$data = model('Work')->GetOftenInfo();
			return $data;
		}
	}
}