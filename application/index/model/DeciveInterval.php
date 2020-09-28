<?php
/**
* 腕表的gps与心率的间隔上传
*/
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;

class DeciveInterval extends Common {

	/*
	*设置心率与gps间隔时间
	*/
	public function CreateTimes(){
		try {
			$info = input();
			$pid = db::name('device')->where('id',$info['did'])->value('pid');

			//科强手环
			if ($pid==2) {
				//服务器上行指令
				$key = F_CreateRandom(32);
				if (!$key) {
            	    return ['code'=>1,'msg'=>'下发指令失败'];
            	}
				$str = '@B#@|01|CMDP|014|'.$info['imei'].'|'.$info['state'].'|'.$info['times'].'|'.date('YmdHis').'|'.$key.'|@E#@';
				$tcp_result = F_SendCommand($str,9077);
			}

			//小亿手表
			if ($pid==1) {
				/*
				*手表下发的单位为秒
				*/
				$times = $info['times']*60;
    			$command = 'IWBP15,gpsupload,'.$info['imei'].','.rand(100000,999999).','.$times.'#';
				$tcp_result = F_SendCommand($command,9088);
			}		

			if (!isset($tcp_result['code']) || is_null($tcp_result) || $tcp_result['code']==1) {
                return ['code'=>1,'msg'=>'腕表不在线'];
            }
			$tmp['did'] = ['eq',$info['did']];
			$tmp['state'] = ['eq',$info['state']];
			$data = db::name('decive_interval')->where($tmp)->find();
			if ($data) {
				if ($info['times']=='') {
					//删除
					$result = db::name('decive_interval')->where('did',$info['did'])->delete();
					if ($result!==false) {
						return ['code'=>0,'msg'=>'设置成功'];die;
					}
					return ['code'=>1,'msg'=>'设置失败'];die;
				}
				//更新
				$arr=[
					'state'=>$info['state'],
					'times'=>$info['times'],
				];
				$result = db::name('decive_interval')->where('did',$info['did'])->update($arr);

				if ($result!==false) {
					return ['code'=>0,'msg'=>'设置成功'];die;
				}
				return ['code'=>1,'msg'=>'设置失败'];die;
			}

			//添加
			$arr=[
				'did'=>$info['did'],
				'state'=>$info['state'],
				'times'=>$info['times'],
			];
			$res = db::name('decive_interval')->insert($arr);
			if ($res) {
				return ['code'=>0,'msg'=>'设置成功'];
			}
			return ['code'=>1,'msg'=>'设置失败'];
		} catch (Exception $e) {
			Log::write('设置心率与gps间隔时间异常'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
		}
	}


	/*
	*智能穿戴式终端设置定位工作时间段
	*return array
	*/
	public function CreateTimings(){
		try {
			$info = input();
			$device = db::name('device')->where('id',$info['did'])->find();
			//查询设置是否绑定用户
			if (is_null($device['uid'])) {
				return ['code'=>1,'msg'=>$device['imei'].'未绑定用户'];
			}
			//开始时间
			$start_time_arr = explode(':',$info['start_time']);
			$start = $start_time_arr[0].$start_time_arr[1];
	
			//结束时间
			$end_time_arr = explode(':',$info['end_time']);
			$end = $end_time_arr[0].$end_time_arr[1];

			//科强手环
			if ($device['pid']==2) {
				//服务器上行指令指定流水号
				$key = F_CreateRandom(32);
				if (!$key) {
            	    return ['code'=>1,'msg'=>'下发指令失败'];
            	}
            	//指令构成
				$str = '@B#@|01|CMDT|016|'.$info['imei'].'|1|'.$start.'|'.$end.'|'.date('YmdHis').'|'.$key.'|@E#@';
				//发送指令
				$tcp_result = F_SendCommand($str,9077);
			}

			//乐源腕表
			if ($device['pid']==1) {
				$Common = 'IWBP34,gpsjob,'.$info['imei'].','.rand(100000,999999).',1,'.$start.'@'.$end.'#';
				//发送指令
				$tcp_result = F_SendCommand($Common,9088);
			}

			if (!isset($tcp_result['code']) || is_null($tcp_result) || $tcp_result['code']==1) {
                return ['code'=>1,'msg'=>'腕表不在线'];
            }
            //查询是否设置过间隔时间段
            $tmp['did'] = ['eq',$info['did']];
			$data = db::name('gpstiming')->where($tmp)->find();

			$gpstiming_arr['start_time'] = $start;
			$gpstiming_arr['end_time'] = $end;
			//有记录 ->更新字段
			if ($data) {
				$result = db::name('gpstiming')->where($tmp)->update($gpstiming_arr);
				if ($result!==false) {
					return ['code'=>0,'msg'=>'指令下发成功'];
				}
				return ['code'=>1,'msg'=>'指令下发失败'];
			}
			//插入记录
			$gpstiming_arr['did'] = $info['did'];
			$insert_res = $result = db::name('gpstiming')->insert($gpstiming_arr);
			if ($insert_res) {
				return ['code'=>0,'msg'=>'指令下发成功'];
			}
			return ['code'=>1,'msg'=>'指令下发失败'];
		} catch (Exception $e) {
			Log::write('设置定位工作时间段异常'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
		}
	}
}