<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/14
 * Time: 13:53
 * 既往病史
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;

class Medicalhistory extends Common {
    /**
     * 服务对象的既往病史列表
     * @param int $uid 服务对象id
     * @return array ['history'=>'既往病史','page'=>'分页信息']
     */
    public function medicalHistoryList($uid)
    {
        try {
            $where['cid'] = ['eq',$uid];
            $data = Db::name('client_medical_history')
                ->field('id,cid,descript,diagnostic_time,remarks')
                ->where($where)
                ->order('id desc')
                ->paginate(20,false);
            $page = $data->render();
            $history = $data->toArray();
            foreach($history['data'] as &$value){
                $value['diagnostic_time'] = date('Y-m-d',$value['diagnostic_time']);
            }
            return ['history'=>$history,'page'=>$page];
        } catch (\Exception $e) {
            Log::write('既往病史列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 提交服务对象的既往病史信息
     * @param int $uid 服务对象id
     * @param int $id 既往病史id
     * @param string $descript 既往病史描述
     * @param string $descript 既往病史描述
     * @return array ['code'=>0,'msg'=>'提交结果']
     */
    public function submitMedicalHistory($uid, $id, $descript, $diagnostic_time, $remarks)
    {
        try {
            $is_exit = model('Client')->userInfo($uid);
            if(!$is_exit){
                return ['code'=>1,'msg'=>'服务对象不存在'];
            }
            $time = time();
            if($id){
                return $this->updateMedicalHistory($uid, $id, $descript, $diagnostic_time, $remarks, $time);
            }
            return $this->addMedicalHistory($uid, $descript, $diagnostic_time, $remarks, $time);
        } catch (\Exception $e) {
            Log::write('提交既往病史信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 添加既往病史信息
     * @param int $uid 服务对象id
     * @param string $descript 既往病史描述
     * @param string $descript 既往病史描述
     * @param string $time 添加时间
     * @return array ['code'=>0,'msg'=>'添加结果']
     */
    protected function addMedicalHistory($uid, $descript, $diagnostic_time, $remarks, $time)
    {
        try {
            $sql = "INSERT INTO yc_client_medical_history (`cid`,`descript`,`diagnostic_time`,`remarks`,`create_time`) VALUES (".$uid.",'".$descript."',".$diagnostic_time.",'".$remarks."',".$time.")";
            $add = Db::execute($sql);
            if(!$add){
                return ['code'=>1,'msg'=>'添加既往病史失败'];
            }
            return ['code'=>0,'msg'=>'添加既往病史成功'];
        } catch (\Exception $e) {
            Log::write('添加既往病史异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 更新既往病史信息
     * @param int $uid 服务对象id
     * @param int $id 既往病史id
     * @param string $descript 既往病史描述
     * @param string $descript 既往病史描述
     * @param string $time 添加时间
     * @return array ['code'=>0,'msg'=>'更新结果']
     */
    protected function updateMedicalHistory($uid, $id, $descript, $diagnostic_time, $remarks, $time)
    {
        try {
            $sql = "UPDATE yc_client_medical_history SET `descript`='".$descript."',`diagnostic_time`=".$diagnostic_time.",`remarks`='".$remarks."',`modify_time`=".$time." WHERE `id`=".$id." AND `cid`=".$uid;
            $update = Db::execute($sql);
            if($update === false){
                return ['code'=>1,'msg'=>'编辑既往病史失败'];
            }
            return ['code'=>0,'msg'=>'编辑既往病史成功'];
        } catch (\Exception $e) {
            Log::write('编辑既往病史异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 删除既往病史
     * @param int $uid 服务对象id
     * @param int $id 既往病史id
     * @return array ['code'=>0,'msg'=>'删除结果']
     */
    public function deleteMedicalHistory($uid, $id)
    {
        try {
            $sql = "DELETE FROM yc_client_medical_history WHERE `id`=".$id." AND `cid`=".$uid;
            $delete = Db::execute($sql);
            if(!$delete){
                return ['code'=>1,'msg'=>'删除既往病史失败'];
            }
            return ['code'=>0,'msg'=>'删除既往病史成功'];
        } catch (\Exception $e) {
            Log::write('删除既往病史异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

}