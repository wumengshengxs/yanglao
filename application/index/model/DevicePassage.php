<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/18
 * Time: 17:45
 * 设备通道管理
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;

class DevicePassage extends Common {
    /**
     * 通道列表
     * @param array|string $where 搜索条件
     * @return array
     */
    public function passageList($where='')
    {
        try {
            $data = Db::name('device_passage')
                ->field('id,name,param,p_status,create_time')
                ->where($where)
                ->order('id desc')
                ->paginate(20, false);
            $page = $data->render();
            $passage = $data->toArray();
            foreach($passage['data'] as &$value){
                $value['status'] = ($value['p_status'] == 1) ? '启用' : '禁用' ;
                $value['create_time'] = date('Y-m-d',$value['create_time']);
            }
            return ['passage'=>$passage,'page'=>$page];
        } catch (\Exception $e) {
            Log::write('通道列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 通道信息
     * @param int $id 通道id
     * @return array $details 通道信息
     */
    public function passageDetails($id)
    {
        try {
            $sql = "SELECT `id`,`name` FROM yc_device_passage WHERE `id`=".$id;
            $details = Db::query($sql);
            return $details[0];
        } catch (\Exception $e) {
            Log::write('通道信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 提交通道信息
     * @param int $id 通道id
     * @param string $name 通道名称
     * @param string $param 通道参数
     * @param int $status 通道状态 1：启用；2：禁用
     * @return array ['code'=>0,'msg'=>'提交结果']
     */
    public function submitPassage($id, $name, $param, $status)
    {
        try {
            // 通道唯一
            $where = " `name`='".$name."'";
            $where .= $id ? " AND `id`!=".$id : '';
            $sql_uniq = "SELECT `id` FROM yc_device_passage WHERE ".$where;
            $uniq = Db::query($sql_uniq);
            if($uniq){
                return ['code'=>1,'msg'=>'通道已存在'];
            }
            $time = time();
            if($id){
                return $this->updatePassage($id, $name, $param, $status, $time);
            }
            return $this->addPassage($name, $param, $status, $time);
        } catch (\Exception $e) {
            Log::write('提交通道信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 新增通道
     * @param string $name 通道名称
     * @param string $param 通道参数
     * @param int $status 通道状态 1：启用；2：禁用
     * @param string $time 添加时间
     * @return array ['code'=>0,'msg'=>'新增结果']
     */
    protected function addPassage($name, $param, $status, $time)
    {
        try {
            $sql = "INSERT INTO yc_device_passage (`name`,`param`,`p_status`,`create_time`) VALUES ('".$name."','".$param."',".$status.",".$time.")";
            $add = Db::execute($sql);
            if(!$add){
                return ['code'=>2,'msg'=>'新增通道失败'];
            }
            return ['code'=>0,'msg'=>'新增通道成功'];
        } catch (\Exception $e) {
            Log::write('新增通道异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 更新通道信息
     * @param int $id 通道id
     * @param string $name 通道名称
     * @param string $param 通道参数
     * @param int $status 通道状态 1：启用；2：禁用
     * @param string $time 添加时间
     * @return array ['code'=>0,'msg'=>'更新结果']
     */
    protected function updatePassage($id, $name, $param, $status, $time)
    {
        try {
            $sql = "UPDATE yc_device_passage SET `name`='".$name."',`param`='".$param."',`p_status`=".$status.",`modify_time`=".$time." WHERE `id`=".$id;
            $update = Db::execute($sql);
            if($update === false){
                return ['code'=>2,'msg'=>'更新通道失败'];
            }
            return ['code'=>0,'msg'=>'更新通道成功'];
        } catch (\Exception $e) {
            Log::write('更新通道信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

}