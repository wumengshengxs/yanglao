<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/28
 * Time: 18:17
 * 服务商类目
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;

class ProviderProject extends Common {
    /**
     * 服务类目列表
     */
    public function projectList()
    {
        try {
            $sql = "SELECT `id`,`name`,`pid`,`remarks` FROM yc_provider_project";
            $project = Db::query($sql);
            $tree = [];
            if($project){  // 获取树状结构
                $tree = model('Menu')->menuTree($project);
            }
            return $tree;
        } catch (\Exception $e) {
            Log::write('服务类目列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 提交服务类目
     * @param int $id 类目id
     * @param int $pid 上级分类id
     * @param string $name 类目名称
     * @param string $remarks 备注
     * @return array ['code'=>0,'msg'=>'提交结果']
     */
    public function submitProject($id=0, $pid=0, $name, $remarks='')
    {
        try {
            // 类目唯一
            $where = " `name`='".$name."'";
            $where .= $id ? " AND `id`!=".$id  : "" ;
            $sql_uniq = "SELECT `id` FROM yc_provider_project WHERE ".$where;
            $uniq = Db::query($sql_uniq);
            if($uniq){
                return ['code'=>1,'msg'=>'服务类目重复'];
            }
            $time = time();
            if($id){
                return $this->updateProject($id, $name, $remarks, $time);
            }
            return $this->addProject($pid, $name, $remarks, $time);
            $result = Db::execute($sql);
            if($result === false){
                return ['code'=>2,'msg'=>''];
            }
        } catch (\Exception $e) {
            Log::write('提交服务类目异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 添加服务类目
     * @param int $pid 上级分类id
     * @param string $name 类目名称
     * @param string $remarks 备注
     * @param string $time 添加的时间戳
     * @return array ['code'=>0,'msg'=>'添加结果']
     */
    protected function addProject($pid=0, $name, $remarks='', $time)
    {
        try {
            $sql = "INSERT INTO yc_provider_project (`name`,`pid`,`remarks`,`create_time`) VALUES ('".$name."',".$pid.",'".$remarks."',".$time.")";
            $add = Db::execute($sql);
            if(!$add){
                return ['code'=>2,'msg'=>'添加服务类目失败'];
            }
            return ['code'=>0,'msg'=>'添加服务类目成功'];
        } catch (\Exception $e) {
            Log::write('添加服务类目异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 更新服务类目信息
     * @param int $id 类目id
     * @param string $name 类目名称
     * @param string $remarks 备注
     * @return array ['code'=>0,'msg'=>'更新结果']
     */
    protected function updateProject($id, $name, $remarks='', $time)
    {
        try {
            $sql = "UPDATE yc_provider_project SET `name`='".$name."',`remarks`='".$remarks."',`modify_time`='".$time."' WHERE `id`=".$id;
            $update = Db::execute($sql);
            if($update === false){
                return ['code'=>1,'msg'=>'编辑服务类目失败'];
            }
            return ['code'=>0,'msg'=>'编辑服务类目成功'];
        } catch (\Exception $e) {
            Log::write('更新服务类目异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 删除服务类目（同时删除子类）
     * @param int $id 服务类目id
     * @return array ['code'=>0,'msg'=>'删除结果']
     */
    public function deleteProject($id)
    {
        try {
            $sql = "DELETE FROM yc_provider_project WHERE `id`=".$id." OR `pid`=".$id;
            $delete = Db::execute($sql);
            if(!$delete){
                return ['code'=>1,'msg'=>'删除失败'];
            }
            return ['code'=>0,'msg'=>'删除成功'];
        } catch (\Exception $e) {
            Log::write('删除服务类目异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

}
