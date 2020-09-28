<?php
/**
*终端设备连接记录
*/
namespace app\device\model;

use app\device\model\Common;
use think\Exception;
use think\Log;
use think\Db;

class Deviceconnect  extends Common {
	/*
	*添加终端连接记录
	*/
	public function AddDeviceConnectLog(){
		try {
			//获取数据源
        	$data = input();
			//当前系统时间
        	$time = time();
        	//格式化当前系统时间
        	$house = strtotime(date('Y-m-d H:00:00',time()));
        	/*
        	*查询该时刻该设备是否存在
        	*不存在则插入记录
        	*/
        	$where['imei'] = $data['imei'];
        	$where['uid'] = $data['uid'];
        	$where['addtime'] = $house;
        	$info = db::name('device_connect')->where($where)->select();
        	if (!$info) {
        	    //插入连接记录
        	    $add = [
        	        'imei'=>$data['imei'],
        	        'uid'=>$data['uid'],
        	        'addtime'=>$house
        	    ];
        	    db::name('device_connect')->insert($add);
        	}
		} catch (Exception $e) {
			Log::write('添加终端连接记录异常'.$e->getMessage(),'error');
		}
	}
}
?>