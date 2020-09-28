<?php
/**
*居家安防三件套
*/
namespace app\device\model;

use app\device\model\Common;
use think\Exception;
use think\Log;
use think\Db;

class Security  extends Common {
    
    /*
    *烟感报警消息接受
    *报警消息接受后推送到gateway,由gateway转发到系统层进行报警提示
    */
    public function smokedetector(){
      try {
        $info =  file_get_contents('php://input');
        $data = json_decode($info,true);
        /*
        *心跳包处理，更新设备电量
        *messageType =>1,区分告警/心跳
        *batteryType =>2,区分电压/电量
        */
        if ($data['data']['messageType']==1 && $data['data']['batteryType']==2) {
          //心跳包更新设备信息
          $arr = [
            'electric'=>$data['data']['battery'],
          ];
          db::name('device')->where('imei',$data['data']['serialNumber'])->update($arr);
          die(date('Y-m-d H:i:s').' 接收到居家三件套烟感报警器心跳包,更新设备电量');
        }

        /*
        *生成烟感报警工单
        */
        $uid = db::name('device')->where('imei',$data['data']['serialNumber'])->value('uid');

        $order = [
          'staff_number'=>1009,
          'client_id'=>$uid,
          'type'=>7, //烟感工单
          'state'=>2, //工单状态
          'lng'=>$data['data']['lng'],
          'lat'=>$data['data']['lat'],
          'address'=>$data['data']['addr'],
          'alarm_time'=>strtotime($data['data']['reportTime']),
          'create_time'=>time(),
        ];
        db::name('work')->insert($order);
        $w_id = db::name('work')->getLastInsID();
        $log_arr = [
          'w_id'=>$w_id,
          'remarks'=>'系统接收到报警消息,自动创建工单',
          'create_time'=>time(),
        ];
        db::name('work_log')->insert($log_arr);
        //系统消息转发
        $this->sendWarningMess();
      } catch (Exception $e) {
        Log::write('处理居家安防三件套烟感报警异常'.$e->getMessage(),'error');
      }
    }

    /*
    *燃气报警
    *报警消息接受后推送到gateway,由gateway转发到系统层进行报警提示
    */
    public function fuelgas(){
      try {
        $info = file_get_contents('php://input');
        $data = json_decode($info,true);
        if ($data['data']['messageType']==1 && $data['data']['batteryType']==2) {
          die(date('Y-m-d H:i:s').' 更新设备电量');
        }
        /*
        *生成燃气报警工单
        */
        $uid = db::name('device')->where('imei',$data['data']['serialNumber'])->value('uid');
        // $staff_number = parent::getuser();
        
        // if (!$staff_number) {
        //   die(date('Y-m-d H:i:s').'获取话务员信息异常');
        // }
        $order = [
          'staff_number'=>1009,
          'client_id'=>$uid,
          'type'=>8, //燃气工单
          'state'=>2, //工单状态
          'lng'=>$data['data']['longitude'],
          'lat'=>$data['data']['latitude'],
          'address'=>$data['data']['address'],
          'alarm_time'=>strtotime($data['data']['reportTime']),
          'create_time'=>time(),
        ];
        db::name('work')->insert($order);
        $w_id = db::name('work')->getLastInsID();
        $log_arr = [
          'w_id'=>$w_id,
          'remarks'=>'系统接收到报警消息自动创建工单',
          'create_time'=>time(),
        ];
        db::name('work_log')->insert($log_arr);
        //系统消息转发
        $this->sendWarningMess();
      } catch (Exception $e) {
        Log::write('处理居家安防三件套燃气报警异常'.$e->getMessage(),'error');
      }
    }

  /*
  *红外报警信息
  */
  public function infrared(){
    try {
      $info = file_get_contents('php://input');
      $data = json_decode($info,true);
      if ($data['data']['type']==2) {
        die(date('Y-m-d H:i:s').' 更新设备电量');
      }

      /*
      *生成红外报警工单
      */
      if ($data['data']['type']==1) {
        $uid = db::name('device')->where('imei',$data['data']['serialNumber'])->value('uid');
        
        $order = [
          'staff_number'=>1009,
          'client_id'=>$uid,
          'type'=>9, //红外工单
          'state'=>2, //工单状态
          'alarm_time'=>strtotime($data['data']['reportTime']),
          'create_time'=>time(),
        ];
        db::name('work')->insert($order);
        $w_id = db::name('work')->getLastInsID();
        $log_arr = [
          'w_id'=>$w_id,
          'remarks'=>'系统接收到报警消息自动创建工单',
          'create_time'=>time(),
        ];
        db::name('work_log')->insert($log_arr);
        //系统消息转发
        $this->sendWarningMess();
      }
    } catch (Exception $e) {
      Log::write('处理居家安防三件套红外报警异常'.$e->getMessage(),'error');
    }
  }

  /*
  *推送到tcp服务器做消息转发,这里可直接发送到对接完成的手环或腕表开通的tcp服务器【目前先推送到腕表tcp服务】
  */
  protected function sendWarningMess(){
    try {
      $command = '@B#@|big|protection|@E#@';
      /*
      *连接服务器,发送指令
      */
      $res = F_SendCommand($command,9077);
      /*
      *这里是转发记录
      */
      $arr = [
        'addtime' =>time(), //创建时间
        'port' =>'101.89.115.24:9077',  //端口号
        'type' =>1,    //居家安防转发类型
      ];
      if ($res) {
        $arr['status'] = 2;
      }
      db::name('protection_log')->insert($arr);
    } catch (Exception $e) {
      Log::write('推送居家安防报警异常'.$e->getMessage(),'error');
    }
  }
}