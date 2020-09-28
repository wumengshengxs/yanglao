<?php
/*
*定位记录
*/

namespace app\index\model;

use think\Model;
use think\Exception;
use think\Log;
use think\Db;

class Gpslog extends Model{

	//定位方式列表展示图标
	protected $location_typeicon=[
		'1'=>"<i class='fa fa-map-marker' title='未有定位方式'></i>",
		'2'=>"<i class='fa fa-map-marker' title='GPS'></i>",
		'3'=>"<i class='fa fa-map-pin' title='基站'></i>",
		'4'=>"<i class='fa fa-wifi' title='wifi'></i>",
		'5'=>"<i class='fa fa-rss-square' title='混合'></i>",
	];

	/*
	*获取定位记录
	*/
	public function GetGpsAll(){
		try {
			$arr = input();
			//姓名搜索
			if (isset($arr['name']) && $arr['name']!=='') {
				$conditionA['a.name'] = ['like','%'.$arr['name'].'%'];
				$idstring = db::name('client a')->field('group_concat(a.id) as id')->where($conditionA)->select();
				$where['a.uid'] = ['in',$idstring[0]['id']];
				$query['name'] = $arr['name'];
			}
			//身份证号码
			if (isset($arr['cardid']) && $arr['cardid']!=='') {
				$conditionC['id_number'] = ['eq',$arr['cardid']];
				$id = db::name('client')->where($conditionC)->value('id');
				$where['a.uid'] = ['eq',$id];
				$query['cardid'] = $arr['cardid'];
			}
			//IMEI号码
			if (isset($arr['imei']) && $arr['imei']!=='') {
				$uid = db::name('device')->where('imei',$arr['imei'])->value('uid');
				$where['a.uid'] = ['eq',$uid];
				$query['imei'] = $arr['imei'];
			}
			//开始时间 与 结束时间
			if ( isset($arr['start_create']) && $arr['start_create']!=='' && isset($arr['end_create']) && $arr['end_create']!=='') {
				$query['start_create']=$arr['start_create'];
				$query['end_create']=$arr['end_create'];
				$end = $arr['end_create'].''.'23:59:59';
				$where['a.addtime'] = ['between',[strtotime($arr['start_create']),strtotime($end)]];
			}else if( isset($arr['start_create']) && $arr['start_create']!==''){
				$query['start_create']=$arr['start_create'];
				$where['a.addtime'] = ['egt',strtotime($arr['start_create'])];
			}else if( isset($arr['end_create']) && $arr['end_create']!==''){
				$query['end_create']=$arr['end_create'];
				$end = $arr['end_create'].''.'23:59:59';
				$where['a.addtime'] = ['elt',strtotime($end)];
			}
			$data = db::name('gpslog a')
			->field('a.text,a.address,a.location_type,a.addtime,a.imei,a.id,a.uid')
			->where($where)
			->order('a.id desc')
			->paginate(30,false,['query'=>$query]);
			$page = $data->render();
			$list = $data->toArray();
			foreach ($list['data'] as $key =>&$value) {
				$de_info = db::name('client')->field('name,id')->where('id',$value['uid'])->find();
				$value['name'] = $de_info['name'];
				$value['cid'] = $de_info['id'];
				$value['location_type'] = $this->location_typeicon[$value['location_type']];
			}
			$arr=[
				'page'=>$page,
				'list'=>$list,
				'query'=>$query
			];
			return $arr;
		} catch (Exception $e) {
			Log::write("获取定位记录列表异常".$e->getMessage(),'error');
			return fasle;
		}
	}

	/*
	*地图展示
	*/
	public function GetGpsMapAll(){
		try {
			$arr = input();
			//姓名搜索
			if (isset($arr['name']) && $arr['name']!=='') {
				$conditionA['a.name'] = ['like','%'.$arr['name'].'%'];
				$idstring = db::name('client a')->field('group_concat(a.id) as id')->where($conditionA)->select();
				if ($idstring[0]['id']) {
					$conditionB['uid']=['in',$idstring[0]['id']];
					$arr_imei = db::name('device')->field('group_concat(imei) as imei')->where($conditionB)->select();
					$where['a.imei'] = ['in',$arr_imei[0]['imei']];
				}
				$query['name'] = $arr['name'];
			}
			//身份证号码
			if (isset($arr['cardid']) && $arr['cardid']!=='') {
				$conditionC['a.id_number'] = ['eq',$arr['cardid']];
				$imei = db::name('client a')->join('device b','a.id=b.uid','left')->where($conditionC)->value('b.imei');
				$where['a.imei'] = ['eq',$imei];
				$query['cardid'] = $arr['cardid'];
			}
			//IMEI号码
			if (isset($arr['imei']) && $arr['imei']!=='') {
				$where['a.imei'] = ['eq',$arr['imei']];
				$query['imei'] = $arr['imei'];
			}
			//开始时间 与 结束时间
			if ( isset($arr['start_create']) && $arr['start_create']!=='' && isset($arr['end_create']) && $arr['end_create']!=='') {
				$query['start_create']=$arr['start_create'];
				$query['end_create']=$arr['end_create'];
				$end = $arr['end_create'].''.'23:59:59';
				$where['a.addtime'] = ['between',[strtotime($arr['start_create']),strtotime($end)]];
			}else if( isset($arr['start_create']) && $arr['start_create']!==''){
				$query['start_create']=$arr['start_create'];
				$where['a.addtime'] = ['egt',strtotime($arr['start_create'])];
			}else if( isset($arr['end_create']) && $arr['end_create']!==''){
				$query['end_create']=$arr['end_create'];
				$end = $arr['end_create'].''.'23:59:59';
				$where['a.addtime'] = ['elt',strtotime($end)];
			}
			$data = db::name('gpslog a')
			->field('a.text,a.address,a.location_type,a.addtime,a.imei')
			->where($where)
			->order('a.id desc')
			->select();
			foreach ($data as $key =>&$value) {
				$de_info = db::name('device d')->field('c.name,c.head')->join('client c','d.uid=c.id','left')->where('imei',$value['imei'])->find();
				$value['name'] = $de_info['name'];
				$value['img'] = $de_info['head'] ? $de_info['head'] : '/public/static/img/head.jpg';
				$value['location_type'] = $this->location_type[$value['location_type']];
				$lng_arr = explode(',',$value['text']);
				$value['j'] = $lng_arr[0];
				$value['w'] = $lng_arr[1];
				unset($value['text']);
				$value['addtime'] = date('Y-m-d H:i:s',$value['addtime']);
			}
			$arr = [
				'query'=>$query,
				'info'=>json_encode($data),
			];
			return $arr;
		} catch (Exception $e) {
			Log::write("获取定位记录,地图展示异常".$e->getMessage(),'error');
			return fasle;
		}
	}

	/*
    *定位方式
    */
    protected $location_type = [
        '1'=>'未有任何定位',
        '2'=>'GPS',
        '3'=>'基站',
        '4'=>'WIFI',
        '5'=>'混合定位',
    ];

    /*
    *定位记录详情
    */
    public function GetGpsMapDetails(){
    	try {
    		$id = input('id');
    		$where['a.id'] = ['eq',$id];
    		$info = db::name('gpslog a')->field('a.text,a.address,a.location_type,a.addtime,a.imei,c.name,c.head')
    		->join('device b','a.imei=b.imei','left')
    		->join('client c','b.uid=c.id','left')
    		->where($where)
    		->find();
    		$info['time'] = date('Y-m-d H:i:s',$info['addtime']);
    		$info['location_type'] = $this->location_type[$info['location_type']];
    		$info['head'] = $info['head'] ? $info['head'] : '/public/static/img/head.jpg';
    		$arr = explode(',', $info['text']);
    		$info['lng'] = $arr[0];
    		$info['lat'] = $arr[1];
    		unset($info['text']);
    		return json_encode($info);
    	} catch (Exception $e) {
    		Log::write("定位详情异常".$e->getMessage(),'error');
			return fasle;
    	}
    }
}