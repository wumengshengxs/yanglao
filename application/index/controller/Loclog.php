<?php
/**
*定位记录
*/
namespace app\index\controller;

use think\Request;

class Loclog extends Common{

	/*
	*定位记录列表
	*/
	public function index(){
		$data = model('Gpslog')->GetGpsAll();
		$this->assign('data',$data);
		return $this->fetch();
	}

	/*
	*定位地图
	*/
	public function map(){
		$data = model('Gpslog')->GetGpsMapAll();
		$this->assign('data',$data['info']);
		$this->assign('info',$data['query']);
		return $this->fetch();
	}

	/*
	*定位记录详情查看
	*/
	public function mapdetails(){
		$info = model('Gpslog')->GetGpsMapDetails();
		$this->assign('info',$info);
		return $this->fetch();
	}

	/*
	*大屏信息地图
	*/
	public function bigmap(){
		if (Request()->isAjax()) {
			$data = model('Gpslog')->GetGpsMapAll();
			return $data['info'];
		}
	}
}