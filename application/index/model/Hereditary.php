<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/15
 * Time: 9:48
 * 服务对象的遗传病史
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;

class Hereditary extends Common {
    /**
     * 遗传病史列表记录
     * @param int $uid 服务对象id
     * @return array ['hereditary'=>'记录信息','page'=>'分页信息']
     */
    public function hereditaryList($uid)
    {
        try {
            $where['cid'] = ['eq',$uid];
            $data = Db::name('client_hereditary')
                ->field('id,name,relationship,remarks')
                ->where($where)
                ->paginate(20,false);
            $page = $data->render();
            $hereditary = $data->toArray();
            return ['hereditary'=>$hereditary,'page'=>$page];
        } catch (\Exception $e) {
            Log::write('遗传病史列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 提交遗传病史信息
     * @param int $uid 服务对象id
     * @param int $id 遗传病史记录id
     * @param string $name 遗传病名称
     * @param string $relationship 关系
     * @param string $remarks 备注
     * @return array ['code'=>0,'msg'=>'提交结果']
     */
    public function submitHereditary($uid, $id, $name, $relationship, $remarks)
    {
        try {
            $is_exit = model('Client')->userInfo($uid);
            if(!$is_exit){
                return ['code'=>1,'msg'=>'服务对象不存在'];
            }
            $time = time();
            if($id){
                return $this->updateHereditary($uid, $id, $name, $relationship, $remarks, $time);
            }
            return $this->addHereditary($uid, $name, $relationship, $remarks, $time);
        } catch (\Exception $e) {
            Log::write('提交遗传病史信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 添加服务对象的遗传病史信息
     * @param int $uid 服务对象id
     * @param string $name 遗传病名称
     * @param string $relationship 关系
     * @param string $remarks 备注
     * @param string $time 添加时间
     * @return array ['code'=>0,'msg'=>'添加结果']
     */
    protected function addHereditary($uid, $name, $relationship, $remarks, $time)
    {
        try {
            $sql = "INSERT INTO yc_client_hereditary (`cid`,`name`,`relationship`,`remarks`,`create_time`) VALUES (".$uid.",'".$name."','".$relationship."','".$remarks."',".$time.")";
            $add = Db::execute($sql);
            if(!$add){
                return ['code'=>2,'msg'=>'添加遗传病史失败'];
            }
            return ['code'=>0,'msg'=>'添加遗传病史成功'];
        } catch (\Exception $e) {
            Log::write('添加遗传病史信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 更新服务对象的遗传病史信息
     * @param int $uid 服务对象id
     * @param int $id 遗传病史记录id
     * @param string $name 遗传病名称
     * @param string $relationship 关系
     * @param string $remarks 备注
     * @param string $time 更新时间
     * @return array ['code'=>0,'msg'=>'更新结果']
     */
    protected function updateHereditary($uid, $id, $name, $relationship, $remarks, $time)
    {
        try {
            $sql = "UPDATE yc_client_hereditary SET `name`='".$name."',`relationship`='".$relationship."',`remarks`='".$remarks."',`modify_time`=".$time." WHERE `id`=".$id." AND `cid`=".$uid;
            $update = Db::execute($sql);
            if($update === false){
                return ['code'=>2,'msg'=>'更新遗传病史失败'];
            }
            return ['code'=>0,'msg'=>'更新遗传病史成功'];
        } catch (\Exception $e) {
            Log::write('更新遗传病史信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 删除遗传病史记录
     * @param int $uid 服务对象id
     * @param int $id 遗传病史记录id
     * @return array ['code'=>0,'msg'=>'删除结果']
     */
    public function deleteHereditary($uid, $id)
    {
        try {
            $sql = "DELETE FROM yc_client_hereditary WHERE `id`=".$id." AND `cid`=".$uid;
            $delete = Db::execute($sql);
            if(!$delete){
                return ['code'=>1,'msg'=>'删除失败'];
            }
            return ['code'=>0,'msg'=>'删除成功'];
        } catch (\Exception $e) {
            Log::write('删除遗产病史异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

}