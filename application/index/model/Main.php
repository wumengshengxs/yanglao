<?php
/**
 * 综合首页
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;
use app\index\model\StaffUser;

class Main extends Common {

	/*
	*综合首页话务员查询
	*/
	public function GetStaffUser(){
		try {
			//话务员统计
        	$userStaff = StaffUser::getCallStaffState();
        	$staff = [];
        	$staff['data'] = ['离线','空闲','忙碌','通话中'];
        	$keys = ['离线'=>0,'空闲'=>0,'忙碌'=>0,'通话中'=>0];
        	foreach ($userStaff as $val) {
        	    if ($val['status'] == 4 && $val['status'] == 5){
        	        $val['status'] = 3;
        	    }
        	    $keys[$staff['data'][$val['status']]] ++;
        	}

		} catch (Exception $e) {
			Log::write('综合首页话务员查询异常:'.$e->getMessage(),'error');
			return false;
		}
	}

	/*
	*工单查询
	*/
	public function GetHouseOrderAll(){
		try {
			$where['state'] = ['in','1,2,3,4,5'];
			$info = db::name('work')->field('count(id) as number,state')->where($where)->group('state')->select();
			$tmp = ['1','2','3','4','5'];
			$arr_data = array_column($info,'state');
            $array_diff = array_diff($tmp,$arr_data);
            
            foreach ($array_diff as $k=>$v) {
                $diff[$k]['name'] = model('Work')->work_state[$v];
                $diff[$k]['value'] = 0;
            }
			foreach ($info as $key =>&$value){
				$arr[$key]['name'] = model('Work')->work_state[$value['state']];
				$arr[$key]['value'] = $value['number'] ? $value['number'] : 0 ;
			}
			$new_arr = array_merge($arr,$diff);
			return $new_arr;
		} catch (Exception $e) {
			Log::write('24小时工单查询异常:'.$e->getMessage(),'error');
			return false;
		}
	}

	/*
	*服务对象性别比例
	*/
	public function GetUserAgeOrSex(){
		try {
			$user = db::name('client')->field('count(id) as number,sex')->group('sex')->select();
			foreach ($user as $key =>&$value) {
				$value['name'] = $value['sex']==1 ? '男' : '女';
				$value['value'] = $value['number']; 
			}
			return $user;
		} catch (Exception $e) {
			Log::write('服务对象性别比例查询异常:'.$e->getMessage(),'error');
			return false;
		}
	}

	/*
	*服务对象年龄比例
	*/
	public function GetUserAgeOrAge(){
		try {
			$sql = "select name,count('id') as value from (select case when age<=59 then '60岁以下' when age>=60 and age<=69 then '60到69岁之间' when age>=70 and age<=79 then '70到79之间' when age>=80 and age<=89 then '80到89之间' when age>=90 then '90岁以上' end as name from yc_client )a group by name";
			$info = Db::query($sql);
			return $info;
		} catch (Exception $e) {
			Log::write('服务对象年龄比例查询异常:'.$e->getMessage(),'error');
			return false;
		}
	}

	/*
	*工单趋势
	*/
	public function GetHouseOrderCycleAll(){
		try {
			//数据库查询条件
			$start_date = strtotime(date('Y-m-d',time()-(3600*24*6)));
			$end_date = strtotime(date('Y-m-d 23:59:59'));
			$where['create_time'] = ['between',[$start_date,$end_date]];
			$data = db::name('work')->field("from_unixtime(create_time,'%Y-%m-%d') as name,count(id) as value")->where($where)->group('name')->select();
			for ($j=6;$j>=0;$j--) { 
				$arr[] = date('Y-m-d',time()-(3600*24*$j));
			}
			//查找时间下标
			$arr_data = array_column($data,'name');
			//数组比较返回差集
			$array_diff = array_diff($arr, $arr_data);
			if(count($array_diff)>0){
				foreach ($array_diff as $key=>&$value) {
					$diff[$key]['name'] = $value;
					$diff[$key]['value'] = 0;
				}
				$new_arr = array_merge_recursive($data,$diff);
				//按照日期排序
            	foreach ($new_arr as $al=>&$se){
            	    $volume_arr[$al]  = $se['name'];
            	}
            	array_multisort($volume_arr,$new_arr);
			}else{
				$new_arr = $data;
			}
			
			$return_arr = [
				'day'=>$arr,
				'v' =>$new_arr
			];
			return $return_arr;
		} catch (Exception $e) {	
			Log::write('工单趋势查询异常:'.$e->getMessage(),'error');
			return false;
		}
	}

	/*
	*服务对象分组
	*/
	public function GetUserGroup(){
		try {
			$data = db::name('client_group a')->field('a.name,count(b.cid) as value')
			->join('client_group_map b','a.id=b.gid','left')->group('a.id')->select();
			return $data;
		} catch (Exception $e) {
			Log::write('主页服务对象分组查询异常:'.$e->getMessage(),'error');
			return false;
		}
	}

	/*
	*积分累计情况（暂时作废）
	*/
//	public function GetUserIntergal(){
//		try {
//			$data = db::name('integral')->field('sum(score) as score,type')->group('type')->select();
//			foreach ($data as $key =>&$value) {
//
//				if ($value['type'] == 1) {
//					//发放总积分
//					$arr['give'] = $value['score']? $value['score'] : 0;
//				}
//
//				if ($value['type'] == 2 ) {
//					//核销总积分
//					$arr['cancel'] = $value['score'] ? $value['score'] : 0;
//				}
//			}
//			//可用总积分
//			$arr['residue'] = $arr['give'] - $arr['cancel'];
//
//			$client = db::name('client')->field('max(integral) as max,min(integral) as min,count(id) as id')->select();
//			//人均积分
//			$arr['average'] = sprintf("%.2f",$arr['give'] / $client[0]['id']);
//			$arr['max'] = $client[0]['max'];
//			$arr['min'] = $client[0]['min'];
//			return $arr;
//		} catch (Exception $e) {
//			Log::write('主页积分情况查询异常:'.$e->getMessage(),'error');
//			return false;
//		}
//	}

	/*
	*首页今日寿星
	*/
	public function GetUserBirthday(){
		try {
			$data = db::name('client')->field('birthday,id,name,head,age')->select();
			foreach ($data as $key =>&$value) {
				$date = date('m-d',$value['birthday']);
				if ($date==date('m-d')) {
					$arr[$key]=$value;
				}
			}
			return $arr;
		} catch (Exception $e) {
			Log::write('今日寿星查询异常:'.$e->getMessage(),'error');
			return false;
		}
	}

	/*
	*大屏信息查询24小时工单情况
	*/
	public function GetMonitorsHouseOrderAll(){
		try {
			$tmp = [1,2,3,4,5,6,7,8,9];
			$info = db::name('work')->field('count(id) as number,type')->group('type')->select();
			$arr_data = array_column($info,'type');
            $array_diff = array_diff($tmp,$arr_data);
            
            foreach ($array_diff as $key=>&$value) {
                $diff[$key]['type'] = $this->work_type[$value];
                $diff[$key]['number'] = 0;
            }
			foreach ($info as $key=>&$value){
				$value['type'] = $this->work_type[$value['type']];
				$value['number'] = (int)$value['number'];
				$number+=$value['number'];
			}
			//数组合并
            $arr_val = array_merge($info,$diff);
			$arr=[
				'res'=>$arr_val,
				'count'=>$number,
			];
			return $arr;
		} catch (Exception $e) {
			Log::write('大屏信息查询24小时工单情况异常:'.$e->getMessage(),'error');
			return false;
		}
	}

	//工单类型
	protected $work_type = [
		1=>'SOS报警', 
		2=>'越界报警', 
		3=>'心率报警', 
		4=>'主动关爱',
		5=>'主动外呼',
		6=>'主动呼入',
		7=>'烟感报警',
		8=>'燃气报警',
		9=>'红外报警'
	];

	/*
	*腕表总数
	*/
	public function GetWatchesNumber(){
		try {
			$res = db::name('device')->field('count(id) as number,group_concat(is_binding) as count')->select();
			$count = explode(',',$res[0]['count']);
			$arr['number'] = $res[0]['number'];
			$new_count = array_count_values($count);
			$arr['binding_one'] = (int)$new_count[1];
			$arr['binding_two'] = (int)$new_count[2];
			return $arr;
		} catch (Exception $e) {
			Log::write('大屏信息查询腕表信息异常:'.$e->getMessage(),'error');
			return false;
		}
	}
}