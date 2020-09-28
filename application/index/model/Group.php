<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/4
 * Time: 16:20
 * 服务对象分组
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;

class Group extends Common {
    /**
     * 分组列表
     * @param array $where 搜索条件
     * @param int $limit 分页条数
     */
    public function groupList($where=[], $limit=20)
    {
        try {
            $data = Db::name('client_group')
                ->field('id,name,remarks,create_time,modify_time')
                ->where($where)
                ->order('id desc')
                ->paginate($limit,false);
            $page = $data->render();
            $group = $data->toArray();
            foreach ($group['data'] as &$value) {
                $value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
                $value['modify_time'] = $value['modify_time'] ? date('Y-m-d H:i:s',$value['modify_time']) : '';
            }
            return ['group'=>$group,'page'=>$page];
        } catch (\Exception $e) {
            Log::write('服务对象分组列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取分组名称，逗号隔开
     * @param array $where 搜索条件
     * @return string $name 分组名称，逗号隔开
     */
    public function groupName($where=[])
    {
        try {
            $sql = "SELECT GROUP_CONCAT(`name`) AS name FROM yc_client_group WHERE ".$where;
            $name = Db::query($sql);
            return $name[0]['name'];
        } catch (\Exception $e) {
            Log::write('获取分组名称异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 提交分组信息
     * @param int $id 分组id
     * @param string $name 分组名称
     * @param string $remarks 分组备注
     * @return array ['code'=>0,'msg'=>'提交结果']
     */
    public function submitGroup($id, $name, $remarks)
    {
        try {
            // 分组唯一
            $where = "`name`='".$name."'";
            $where .= $id ? " AND `id`!=".$id : '';
            $sql_uniq = "SELECT `id` FROM yc_client_group WHERE ".$where;
            $uniq = Db::query($sql_uniq);
            if($uniq){
                return ['code'=>1,'msg'=>'分组名重复'];
            }
            if($id){
                return $this->updateGroup($id, $name, $remarks);
            }
            return $this->addGroup($name, $remarks);
        } catch (\Exception $e) {
            Log::write('提交分组信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 新增分组
     * @param string $name 分组名称
     * @param string $remarks 分组备注
     * @return array ['code'=>0,'msg'=>'新增结果']
     */
    protected function addGroup($name, $remarks)
    {
        try {
            $time = time();
            $sql = "INSERT INTO yc_client_group (`name`,`remarks`,`create_time`) VALUES ('".$name."','".$remarks."',".$time.")";
            $insert = Db::execute($sql);
            if(!$insert){
                return ['code'=>2,'msg'=>'新增分组失败'];
            }
            return ['code'=>0,'msg'=>'新增分组成功'];
        } catch (\Exception $e) {
            Log::write('新增服务对象分组异常'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 编辑分组
     * @param int $id 分组id
     * @param string $name 分组名称
     * @param string $remarks 分组备注
     * @return array ['code'=>0,'msg'=>'编辑结果']
     */
    protected function updateGroup($id, $name, $remarks)
    {
        try {
            $time = time();
            $sql = "UPDATE yc_client_group SET `name`='".$name."',`remarks`='".$remarks."',`modify_time`=".$time." WHERE `id`=".$id;
            $update = Db::execute($sql);
            if(!$update){
                return ['code'=>2,'msg'=>'编辑分组失败'];
            }
            return ['code'=>0,'msg'=>'编辑分组成功'];
        } catch (\Exception $e) {
            Log::write('编辑服务对象分组异常'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 删除分组
     * @param int $id 分组id
     * @return array ['code'=>0,'msg'=>'删除结果']
     */
    public function delGroup($id)
    {
        try {
            $sql = "DELETE FROM yc_client_group WHERE `id`=".$id;
            $delete = Db::execute($sql);
            if(!$delete){
                return ['code'=>1,'msg'=>'分组删除失败'];
            }
            return ['code'=>0,'msg'=>'分组删除成功'];
        } catch (\Exception $e) {
            Log::write('删除服务对象分组异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

}