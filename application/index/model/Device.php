<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/21
 * Time: 17:29
 * 设备管理
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;

class Device extends Common {
    /**
     * 设备在线状态
     */
    protected $device_status = [
        1=>'在线',
        2=>'离线',
    ];

    /**
     * 设备是否绑定、是否发放的状态
     */
    protected $bind_send = [
        1=>'是',
        2=>'否',
    ];


    /**
     * 设备列表
     * @param array|string $where 搜索条件
     * @param array $query 搜索条件
     * @param int $limit 分页条数
     * @return array []
     */
    public function deviceList($where, $query=[], $limit=20)
    {
        try {
            $data = Db::name('device')->alias('d')
                ->field('d.id,d.imei,d.iccid,d.msisdn,d.d_status,d.is_send,d.send_time,d.is_binding,d.bind_time,d.version,d.last_connection,p.name')
                ->join('device_passage p','d.pid=p.id','LEFT')
                ->where($where)
                ->order('d.id desc')
                ->paginate($limit, false, ['query'=>$query]);
            $page = $data->render();
            $device = $data->toArray();
            foreach($device['data'] as &$value){
                $value['d_status'] = $this->device_status[$value['d_status']];
                $value['is_send'] = $this->bind_send[$value['is_send']];
                $value['send_time'] = $value['send_time'] ? date('Y-m-d H:i:s',$value['send_time']) : '';
                $value['is_binding'] = $this->bind_send[$value['is_binding']];
                $value['bind_time'] = $value['bind_time'] ? date('Y-m-d H:i:s',$value['bind_time']) : '';
                $value['last_connection'] = $value['last_connection'] ? date('Y-m-d H:i:s',$value['last_connection']) : '';
            }
            return ['device'=>$device,'page'=>$page];
        } catch (\Exception $e) {
            Log::write('设备列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取设备详情
     * @param int $id 设备id
     * @return array
     */
    public function deviceDetails($id)
    {
        try {
            $sql = "SELECT `id`,`imei`,`iccid`,`msisdn`,`d_status`,`is_send`,`send_time`,`is_binding`,`bind_time`,`uid`,`version`,`last_connection`,`electric`,`pid` FROM yc_device WHERE `id`=".$id;
            $details = Db::query($sql);

            if(!$details){
                return false;
            }
            if ($details[0]['is_binding']==1) {
                //查询心率或gps间隔时常
                $sql_two = "SELECT `state`,`times` FROM yc_decive_interval WHERE `did`=".$id;
                $interval = Db::query($sql_two);
                if ($interval) {
                    foreach ($interval as $key=>&$value) {
                        if ($value['state']==1) {
                            $details[0]['heart'] = $value['times'];
                        }else{
                            $details[0]['gps'] = $value['times'];
                        }
                    }
                }
                //查询设备设定的定位工作时间段
                $timing_sql = "SELECT `start_time`,`end_time` FROM yc_gpstiming WHERE `did`=".$id;
                $timing_data = Db::query($timing_sql);
                if ($timing_data) {
                    $details[0]['start_time'] = substr($timing_data[0]['start_time'],0,2).':'.substr($timing_data[0]['start_time'],2,2);
                    $details[0]['end_time'] = substr($timing_data[0]['end_time'],0,2).':'.substr($timing_data[0]['end_time'],2,2);
                }
            }
            $details[0]['d_status'] = $this->device_status[$details[0]['d_status']];
            $details[0]['is_send'] = $this->bind_send[$details[0]['is_send']];
            $details[0]['send_time'] = $details[0]['send_time'] ? date('Y-m-d',$details[0]['send_time']) : '';
            $details[0]['bind_time'] = $details[0]['bind_time'] ? date('Y-m-d',$details[0]['bind_time']) : '';
            $details[0]['last_connection'] = $details[0]['last_connection'] ? date('Y-m-d',$details[0]['last_connection']) : '';
            return $details[0];
        } catch (\Exception $e) {
            Log::write('获取设备详情异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }
    
    /**
     * 导入设备信息
     * @param int $passage 通道id
     * @param string $excel excel文件地址
     * @return array ['code'=>0,'msg'=>'导入结果']
     */
    public function importDevice($passage, $excel)
    {
        try {
            set_time_limit(0);  // 防止excel数据过多执行时间长
            // 验证通道是否存在
            $check_passage = model('DevicePassage')->passageDetails($passage);
            if(!$check_passage){
                return ['code'=>1,'msg'=>'采购通道不存在'];
            }
            // 处理excel文件
            vendor('PHPExcel.PHPExcel.IOFactory');
            $excel = \PHPExcel_IOFactory::load($excel,$encode = 'utf-8');
            $sheet = $excel->getSheet(0); // 获取表中第一个工作表 去除列名称所属行
            $excel_rows = $sheet->getHighestRow(); //取得总行数
            $insert_arr = [];  // 带插入的数组
            $imei_string = '';
            $time = time();
            for($i=2; $i<=$excel_rows; $i++){
                $imei = $excel->getActiveSheet()->getCell("A" . $i)->getValue();        // IMEI
                $iccid = $excel->getActiveSheet()->getCell("B" .$i)->getValue();        // ICCID
                $msisdn = $excel->getActiveSheet()->getCell("C" .$i)->getValue();       // 电话号码
                $version = $excel->getActiveSheet()->getCell("D" .$i)->getValue();      // 固件版本号
                $insert_arr[$i] = [
                    'imei'=>trim($imei),
                    'iccid'=>trim($iccid),
                    'msisdn'=>trim($msisdn),
                    'version'=>trim($version),
                    'pid'=>$passage,
                    'create_time'=>$time
                ];
                $imei_string .= "'".trim($imei)."',";
            }
            if(empty($insert_arr)){
                return ['code'=>2,'msg'=>'excel没有数据'];
            }
            // 查重
            $imei_string = rtrim($imei_string,',');
            $repeat = Db::query("SELECT `id` FROM yc_device WHERE `imei` IN (".$imei_string.")");
            if($repeat){
                return ['code'=>3,'msg'=>'设备的IMEI号已存在，请核对'];
            }
            $insert = Db::name('device')->insertAll($insert_arr);
            if(!$insert){
                return ['code'=>4,'msg'=>'设备录入失败'];
            }
            return ['code'=>0,'msg'=>'设备录入成功','total'=>$insert];
        } catch (\Exception $e) {
            Log::write('设备导入异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 设备绑定用户
     * @param int $did 设备id
     * @param int $uid 用户id
     * @return array ['code'=>0,'msg'=>'绑定结果']
     */
    public function bindUser($did, $uid)
    {   
        try {
            // 设备或者用户是否已经被绑定
            $sql = "select a.is_binding,b.p_status,a.imei,a.id,a.pid from yc_device as a left join yc_device_passage as b on a.pid=b.id where a.id=".$did;
            $data = Db::query($sql);
            // bug($data);die;
            if($data[0]['is_binding']==1){
                return ['code'=>1,'msg'=>'设备或用户已经绑定'];
            }
            if ($data[0]['p_status']==2) {
                return ['code'=>1,'msg'=>'设备通道为开启'];
            }
            //通到ID下发绑定
            switch ($data[0]['pid']) {
                //小亿
                case 1:
                    //发送绑定指令
                    $command = 'IWBP68,bind,'.$data[0]['imei'].','.rand(100000,999999).',1#';
                    $command_result = F_SendCommand($command,9088);
                    //绑定失败
                    if ($command_result['code']==1) {
                        return ['code'=>1,'msg'=>$command_result['message']];
                    }
                    $bind = $this->SaveDeviceInfo($uid,$did);
                    if(!$bind){
                        return ['code'=>1,'msg'=>'绑定失败'];
                    }
                    return ['code'=>0,'msg'=>'绑定成功'];
                //科强腕表,该设备不需要发送绑定指令
                case 2:
                    $kq_res = $this->SaveDeviceInfo($uid,$did);
                    if(!$kq_res){
                        return ['code'=>1,'msg'=>'绑定失败'];
                    }
                    return ['code'=>0,'msg'=>'绑定成功'];
                //健康枕头,该设备不需要发送指令
                case 3:
                    $kq_res = $this->SaveDeviceInfo($uid,$did);
                    if(!$kq_res){
                        return ['code'=>1,'msg'=>'绑定失败'];
                    }
                    return ['code'=>0,'msg'=>'绑定成功'];
                //烟感
                case 4:
                    $lt_res = $this->SaveDeviceInfo($uid,$did);
                    if(!$lt_res){
                        return ['code'=>1,'msg'=>'绑定失败'];
                    }
                    return ['code'=>0,'msg'=>'绑定成功'];
                //红外
                case 5:
                    $lt_res = $this->SaveDeviceInfo($uid,$did);
                    if(!$lt_res){
                        return ['code'=>1,'msg'=>'绑定失败'];
                    }
                    return ['code'=>0,'msg'=>'绑定成功'];
                //燃气
                case 6:
                    $lt_res = $this->SaveDeviceInfo($uid,$did);
                    if(!$lt_res){
                        return ['code'=>1,'msg'=>'绑定失败'];
                    }
                    return ['code'=>0,'msg'=>'绑定成功'];
                default:
                    return ['code'=>1,'msg'=>'该设备未接入,请联系开发人员'];
            }
        } catch (\Exception $e) {
            Log::write('设备绑定异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /*
    *设备绑定后执行更新数据表
    *uid int 服务对象ID
    *did int 设备id
    *return bool
    */
    protected function SaveDeviceInfo($uid,$did){
        try {
            //执行绑定
            $time = time();
            $sql = "UPDATE yc_device SET `is_binding`=1,`uid`=".$uid.",`bind_time`=".$time." WHERE `id`=".$did;
            $res = Db::execute($sql);
            return $res;
        } catch (Exception $e) {
             Log::write('设备绑定后执行更新数据表异常:'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 取消用户绑定
     * @param int $id 设备id
     * @return array ['code'=>0,'msg'=>'取消绑定结果']
     */
    public function cancelBind($id)
    {
        try {
            // 设备或者用户是否已经被绑定
            $sql = "select a.is_binding,b.p_status,a.imei,a.id,a.pid from yc_device as a left join yc_device_passage as b on a.pid=b.id where a.id=".$id;
            $data = Db::query($sql);

            if($data[0]['is_binding']==2){
                return ['code'=>1,'msg'=>'设备未绑定'];
            }
            if ($data[0]['p_status']==2) {
                return ['code'=>1,'msg'=>'设备通道为开启'];
            }
            //通到ID下发绑定
            switch ($data[0]['pid']) {
                case 1:
                    //发送绑定指令
                    $command = 'IWBP68,unbind,'.$data[0]['imei'].','.rand(100000,999999).',0#';
                    $command_result = F_SendCommand($command,9088);
                    if ($command_result['code']==1) {
                        return ['code'=>1,'message'=>$command_result['message']];
                    }
                    $cancel = $this->SaveDeviceInfoNumber($id);
                    if(!$cancel){
                        return ['code'=>1,'msg'=>'取消绑定失败'];
                    }
                    return ['code'=>0,'msg'=>'取消绑定成功'];
                case 2:
                    //科强腕表解绑,解绑完成后需要将设置的紧急联系人号码等清除掉
                    //--------------------------------------除却sos号码后需要将常用联系人与家庭联系人号码清除 目前只是将sos号码清除---
                    $cancel = $this->SaveDeviceInfoNumber($id);
                    if(!$cancel){
                        return ['code'=>1,'msg'=>'取消绑定失败'];
                    }
                    return ['code'=>0,'msg'=>'取消绑定成功'];
                case 3:
                    $cancel = $this->SaveDeviceInfoNumber($id);
                    if(!$cancel){
                        return ['code'=>1,'msg'=>'取消绑定失败'];
                    }
                    return ['code'=>0,'msg'=>'取消绑定成功'];
                default:
                    return ['code'=>1,'msg'=>'该设备未接入,请联系开发人员'];
            }
        } catch (\Exception $e) {
            Log::write('取消用户绑定异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /*
    *腕表解绑
    *id int 设备id
    *return bool
    */
    protected function SaveDeviceInfoNumber($id){
        try {
            //更新腕表解绑
            $device_sql = "UPDATE yc_device SET `is_binding`=2,`uid`=0,`bind_time`=0 WHERE `id`=".$id;
            $result = "SELECT * FROM yc_device_emergency WHERE did=".$id;
            if($result){
                //去除绑定的联系号码
                $number_sql = "DELETE FROM yc_device_emergency  WHERE did=".$id;
                Db::query($number_sql);
            }
            $cancel = Db::execute($device_sql);
            return $cancel;
        } catch (Exception $e) {
            Log::write('取消用户绑定异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 更新设备发放信息
     * @param int $id 设备id
     * @param string $msisdn 设备电话号码
     * @param int $is_send 是否发放 1：是；2：否
     * @param string $send_time 发放的时间戳
     * @param string $iccid 设备iccid
     */
    public function deviceSend($id, $msisdn, $is_send, $send_time,$iccid)
    {
        try {
            $sql = "UPDATE yc_device SET `iccid`='".$iccid."',`msisdn`='".$msisdn."',`is_send`=".(int)$is_send.",`send_time`='".$send_time."' WHERE `id`=".$id." AND `is_binding`=1";
            $send = Db::execute($sql);
            if($send === false){
                return ['code'=>1,'msg'=>'设备发放失败'];
            }
            return ['code'=>0,'msg'=>'设备发放成功'];
        } catch (\Exception $e) {
            Log::write('更新设备发放信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /*
    *更新设备是否在线
    *return array
    */
    public function UpdateDeviceIsline(){
        try {
            $post = input();
            $new_arr = json_decode($post['check'],true);
            if ($new_arr['code']==0) {
                //在线
                $arr = ['d_status'=>1];
            }else{
                //不在线
                $arr = ['d_status'=>2];
            }
            db::name('device')->where('imei',$new_arr['imei'])->update($arr);
            return $arr;
        } catch (Exception $e) {
            Log::write('更新设备是否在线异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /*
    *手动方式录入终端信息
    *return array 
    */
    public function UserOperationInfo(){
        try {
            $data = input();
            
            //查询设备号码是否已添加
            $where['imei'] = ['eq',$data['imei']];
            $d_info = db::name('device')->where($where)->find();
            if ($d_info) {
                return ['code'=>1,'message'=>$data['imei'].'该设备已存在,请核实'];
            }
            $tmp['iccid'] = ['eq',$data['iccid']];
            $i_info = db::name('device')->where($tmp)->select();
            if ($i_info) {
                return ['code'=>1,'message'=>$data['iccid'].'该卡号已存在,请核实'];
            }

            //添加设备
            $arr = [
                'imei'=>$data['imei'],
                'iccid'=>$data['iccid'],
                'msisdn'=>$data['msisdn'],
                'pid'=>$data['pid'],
                'create_time'=>time(),
            ];
            $result = db::name('device')->insert($arr);
            if ($result) {
                return ['code'=>0,'message'=>'添加成功'];
            }
            return ['code'=>1,'message'=>'添加失败'];
        } catch (Exception $e) {
            Log::write('手动录入终端信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }
}