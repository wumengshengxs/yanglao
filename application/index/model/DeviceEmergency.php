<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/24
 * Time: 16:12
 * 腕表的sos
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;

class DeviceEmergency extends Common {
    /**
     * 获取设备绑定的紧急联系人信息
     * @param int $id 设备id
     * @return array
     */
    public function deviceEmergencyList($id)
    {
        try {
            $sql = "SELECT `did`,`name`,`mobile` FROM yc_device_emergency WHERE `did`=".$id;
            $sos = Db::query($sql);
            return $sos;
        } catch (\Exception $e) {
            Log::write('设备的紧急联系人列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 提交设备的sos信息
     * @param int $did 设备id
     * @param array $sos 紧急联系人数组信息
     */
    public function deviceSos($did, $sos=[])
    {
        try {
            if (!is_array($sos)) {
                return ['code'=>1,'msg'=>'号码参数有误'];
            }
            // 指令下发查询
            $where['a.id'] = ['eq',$did];
            $de_info = db::name('device a')->field('a.imei,b.p_status,a.pid,a.uid')->join('device_passage b','a.pid=b.id')->where($where)->find();
            if ($de_info['uid'] && $de_info['p_status']==2) {
                return ['code'=>1,'msg'=>'当前设备通道未开启,不能进行指令下发'];
            }
            
            switch ($de_info['pid']) {
                //小亿
                case 1:
                    $xy_res =$this->LySetNumber($did,$de_info['uid'],$de_info['imei'],$sos);
                    return $xy_res;
                //科强
                case 2:
                    $Kq_res = $this->KqSetNumber($did,$de_info['imei'],$sos);
                    return $Kq_res;
                default:
                   return ['code'=>1,'msg'=>'当前设备未找到设备通道,不能进行指令下发'];
            }
            
        } catch (\Exception $e) {
            Log::write('提交设备sos信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /*
    *设置科强号码
    *$did int 设备id 
    *$imei string 设备imei号码 
    *$arr array 手环号码
    *return array 
    */
    public function KqSetNumber($did,$imei,$arr){
        try {
            $number = '';
            foreach ($arr as $key =>&$v) {
                $number .=$v['mobile'].'&';
            }
            //拼接命令
            $command = '@B#@|01|CMDS|008|'.$imei.'|'.rtrim($number,'&').'|'.date('YmdHis').'|'.F_CreateRandom(32).'|@E#@';
            //连接服务器下发指令
            $result = F_SendCommand($command,9077);
            //终端未连接服务器
            if (!is_null($result) && $result['code']==1) {
                return $result;
            }
            //设置成功后返回
            if ($result['code']==0) {
                $result = $this->UpdateDeciveInfo($did,$arr);
                return $result;
            }
        } catch (Exception $e) {
            Log::write('提交科强设备sos信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /*
    *更新腕表设置sos等号码
    *did int 设备id
    *sos array 设置号码
    *return array 
    */
    protected function UpdateDeciveInfo($did,$sos){
        try {
            Db::startTrans();
            // 删除原有的数据
            $delete = Db::execute('DELETE FROM yc_device_emergency WHERE `did`='.$did);
            // 新增数据
            $new_emergency = true;
            if(!empty($sos)){
                $sql_new = "INSERT INTO yc_device_emergency (`did`,`name`,`mobile`) VALUE ";
                foreach($sos as $value){
                    $sql_new .= "(".$did.",'".$value['name']."','".$value['mobile']."'),";
                }
                $sql_new = rtrim($sql_new,',');
                $new_emergency = Db::execute($sql_new);
            }
            if($delete !== false && $new_emergency){
                Db::commit();
                return ['code'=>0,'msg'=>'紧急联系人绑定成功，等待生效'];
            }
            Db::rollback();
            return ['code'=>1,'msg'=>'紧急联系人绑定失败']; 
        } catch (Exception $e) {
            Log::write('更新设备sos信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /*
    *设置小亿腕表SOS号码
    *$did int 设备id 
    *$cid int 绑定后的用户id
    *$imei string 设备imei号码 
    *$arr array 手环号码
    *return array 
    */
    public function LySetNumber($did,$cid,$imei,$arr){
        try {
            //名称转换成unicode编码
            $Params='';
            if ($arr[0]['name']!=="" && isset($arr[0])) {
                $name = F_unicode_encode($arr[0]['name']);
                if (!$name) {
                    return ['code'=>1,'message'=>'名称编码错误'];die;
                }
                $Params.=strtoupper($name).'|'.$cid.'|1|'.$arr[0]['mobile'].',';
            }
            if ($arr[1]['name']!=="" && isset($arr[1])) {
                $name = F_unicode_encode($arr[1]['name']);
                if (!$name) {
                    return ['code'=>1,'message'=>'名称编码错误'];die;
                }
                $Params.=strtoupper($name).'|'.$cid.'|1|'.$arr[1]['mobile'].',';
            }
            if ($arr[2]['name']!=="" && isset($arr[2])) {
                $name = F_unicode_encode($arr[2]['name']);
                if (!$name) {
                    return ['code'=>1,'message'=>'名称编码错误'];die;
                }
                $Params.=strtoupper($name).'|'.$cid.'|1|'.$arr[2]['mobile'].',';
            }
            $number = rand(100000,999999);
            $NewParams= rtrim($Params,',');
            //拼接下发指令 WEB是特殊标示
            $command = 'IWBP83,setsos,'.$imei.','.$number.','.$NewParams.'#';
            //连接服务端 下发指令
            $command_result = F_SendCommand($command,9088);
           
            //指令失败
            if ($command_result['code']==1) {
                return $command_result;die;
            }
            //发送成功时设备表记录指令内容
            $saveInfo = $this->UpdateDeciveInfo($did,$arr);
            return $saveInfo;
        } catch (Exception $e) {
            Log::write('更新小亿设备sos信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }
}