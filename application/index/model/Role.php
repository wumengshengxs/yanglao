<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/3
 * Time: 13:24
 * 角色管理
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;

class Role extends Common {
    /**
     * 角色列表
     * @param string $where 搜索条件
     * @return array $role 角色列表
     */
    public function roleList($where='')
    {
        try {
            $where = $where ? ' WHERE '.$where : '';
            $sql = "SELECT `id`,`name`,`remarks`,`r_status`,`m_id`,`create_time` FROM yc_role ".$where;
            $role = Db::query($sql);
            foreach($role as &$value){
                $value['r_status'] = ($value['r_status'] == 1) ? '启用' : '停用';
                $value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
            }
            return $role;
        } catch (\Exception $e) {
            Log::write('获取角色列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 角色详情
     * @param int $id 角色id
     * @return array $info 角色详情信息
     */
    public function roleInfo($id)
    {
        try {
            $sql = "SELECT `name`,`remarks`,`m_id`,`r_status` FROM yc_role WHERE `id`=".$id;
            $info = Db::query($sql);
            return $info[0];
        } catch (\Exception $e) {
            Log::write('获取角色详情异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 提交角色信息
     * @param int $id 角色id
     * @param string $name 角色名称
     * @param string $remarks 角色说明
     * @param int $status 角色状态 1：启用；2：禁用
     * @param string $menu_id 角色对应的菜单id，多个逗号隔开
     * @return array ['code'=>0,'msg'=>'提交结果']
     */
    public function submitRole($id, $name, $remarks, $status, $menu_id)
    {
        try {
            // 角色名唯一
            $where = " `name`='".$name."'";
            $where .= $id ? " AND `id`!=".$id : '';
            $sql_uniq = "SELECT `id` FROM yc_role WHERE ".$where;
            $uniq = Db::query($sql_uniq);
            if($uniq){
                return ['code'=>1,'msg'=>'角色名重复'];
            }
            if($id){
                return $this->updateRole($id, $name, $remarks, $status, $menu_id);
            }
            return $this->addRole($name, $remarks, $status, $menu_id);
        } catch (\Exception $e) {
            Log::write('角色信息提交异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 新增角色
     * @param string $name 角色名称
     * @param string $remarks 角色说明
     * @param int $status 角色状态 1：启用；2：禁用
     * @param string $menu_id 角色对应的菜单id，多个逗号隔开
     * @return array ['code'=>0,'msg'=>'新增结果']
     */
    protected function addRole($name, $remarks, $status, $menu_id)
    {
        try {
            $time = time();
            $sql = "INSERT INTO yc_role (`name`,`remarks`,`m_id`,`r_status`,`create_time`) VALUES ('".$name."','".$remarks."','".$menu_id."',".$status.",".$time.")";
            $insert = Db::execute($sql);
            if(!$insert){
                return ['code'=>2,'msg'=>'新增角色失败'];
            }
            return ['code'=>0,'msg'=>'新增角色成功'];
        } catch (\Exception $e) {
            Log::write('新增角色异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 更新角色信息
     * @param int $id 角色id
     * @param string $name 角色名称
     * @param string $remarks 角色说明
     * @param int $status 角色状态 1：启用；2：禁用
     * @param string $menu_id 角色对应的菜单id，多个逗号隔开
     * @return array ['code'=>0,'msg'=>'更新结果']
     */
    protected function updateRole($id, $name, $remarks, $status, $menu_id)
    {
        try {
            $time = time();
            $sql = "UPDATE yc_role SET `name`='".$name."',`remarks`='".$remarks."',`r_status`=".$status.",`m_id`='".$menu_id."',`modify_time`=".$time." WHERE `id`=".$id;
            $update = Db::execute($sql);
            if($update === false){
                return ['code'=>2,'msg'=>'更新角色失败'];
            }
            return ['code'=>0,'msg'=>'更新角色成功'];
        } catch (\Exception $e) {
            Log::write('更新角色异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 删除角色
     * @param int $id 角色id
     * @return array ['code'=>0,'msg'=>'删除结果']
     */
    public function delRole($id)
    {
        try {
            $sql = "DELETE FROM yc_role WHERE `id`=".$id;
            $delete = Db::execute($sql);
            if(!$delete){
                return ['code'=>1,'msg'=>'删除失败'];
            }
            return ['code'=>0,'msg'=>'删除成功'];
        } catch (\Exception $e) {
            Log::write('删除角色异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

}