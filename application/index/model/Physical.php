<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/14
 * Time: 17:18
 * 体检记录管理
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;

class Physical extends Common {
    /**
     * 体检记录列表
     * @param int $uid 服务对象id
     * @return array ['physical'=>'体检记录','page'=>'分页信息']
     */
    public function physicalList($uid)
    {
        try {
            $where['cid'] = ['eq',$uid];
            $data = Db::name('client_physical')
                ->field('id,institution,physical_time,remarks,image')
                ->where($where)
                ->order('id desc')
                ->paginate(20,false);
            $page = $data->render();
            $physical = $data->toArray();
            foreach($physical['data'] as &$value){
                $value['physical_time'] = date('Y-m-d',$value['physical_time']);
                $value['image'] = json_decode($value['image'],true);
                $value['image_count'] = count($value['image']);
            }
            return ['physical'=>$physical,'page'=>$page];
        } catch (\Exception $e) {
            Log::write('体检记录列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 提交体检信息
     * @param int $uid 服务对象id
     * @param int $id 体检记录id
     * @param string $institution 体检机构
     * @param string $physical_time 体检时间戳
     * @param string $image 体检照片，json格式
     * @param string $remarks 备注
     * @return array ['code'=>0,'msg'=>'提交结果']
     */
    public function submitPhysical($uid, $id, $institution, $physical_time, $image, $remarks)
    {
        try {
            $is_exit = model('Client')->userInfo($uid);
            if(!$is_exit){
                return ['code'=>1,'msg'=>'服务对象不存在'];
            }
            $time = time();
            if($id){
                return $this->updatePhysical($uid, $id, $institution, $physical_time, $image, $remarks, $time);
            }
            return $this->addPhysical($uid, $institution, $physical_time, $image, $remarks, $time);
        } catch (\Exception $e) {
            Log::write('提交体检信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 添加体检信息
     * @param int $uid 服务对象id
     * @param string $institution 体检机构
     * @param string $physical_time 体检时间戳
     * @param string $image 体检照片，json格式
     * @param string $remarks 备注
     * @param string $time 添加时间
     * @return array ['code'=>0,'msg'=>'添加结果']
     */
    protected function addPhysical($uid, $institution, $physical_time, $image, $remarks, $time)
    {
        try {
            $sql = "INSERT INTO yc_client_physical (`cid`,`institution`,`physical_time`,`image`,`remarks`,`create_time`) VALUES (".$uid.",'".$institution."',".$physical_time.",'".$image."','".$remarks."',".$time.")";
            $add = Db::execute($sql);
            if(!$add){
                return ['code'=>1,'msg'=>'新增体检信息失败'];
            }
            return ['code'=>0,'msg'=>'新增体检信息成功'];
        } catch (\Exception $e) {
            Log::write('添加体检信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 更新体检信息
     * @param int $uid 服务对象id
     * @param int $id 体检记录id
     * @param string $institution 体检机构
     * @param string $physical_time 体检时间戳
     * @param string $image 体检照片，json格式
     * @param string $remarks 备注
     * @param string $time 更新时间
     * @return array ['code'=>0,'msg'=>'更新体检信息结果']
     */
    protected function updatePhysical($uid, $id, $institution, $physical_time, $image, $remarks, $time)
    {
        try {
            $sql = "UPDATE yc_client_physical SET `institution`='".$institution."',`physical_time`=".$physical_time.",`remarks`='".$remarks."',`modify_time`=".$time.",`image`='".$image."' WHERE `id`=".$id." AND `cid`=".$uid;
            $update = Db::execute($sql);
            if($sql === false){
                return ['code'=>1,'msg'=>'更新体检信息失败'];
            }
            return ['code'=>0,'msg'=>'更新体检信息成功'];
        } catch (\Exception $e) {
            Log::write('更新体检信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 删除体检信息
     * @param int $uid 服务对象id
     * @param int $id 体检记录id
     * @return array ['code'=>0,'msg'=>'删除结果']
     */
    public function deletePhysical($uid, $id)
    {
        try {
            $sql = "DELETE FROM yc_client_physical WHERE `id`=".$id." AND `cid`=".$uid;
            $delete = Db::execute($sql);
            if(!$delete){
                return ['code'=>1,'msg'=>'删除体检信息失败'];
            }
            return ['code'=>0,'msg'=>'删除体检信息成功'];
        } catch (\Exception $e) {
            Log::write('删除体检信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

}