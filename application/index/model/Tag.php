<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/4
 * Time: 13:15
 * 服务对象标签
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;

class Tag extends Common {
    /**
     * 服务对象标签
     */
    public function tagList($param=[])
    {
        try {
            $sql = "SELECT `id`,`name`,`create_time`,`modify_time` FROM yc_client_tag";
            $tag = Db::query($sql);
            foreach($tag as &$value){
                $value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
                $value['modify_time'] = $value['modify_time'] ? date('Y-m-d H:i:s',$value['modify_time']) : '';
            }
            return $tag;
        } catch (\Exception $e) {
            Log::write('服务对象标签列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取标签名称，逗号隔开
     * @param array $where 搜索条件
     * @return string
     */
    public function tagName($where=[])
    {
        try {
            $sql = "SELECT GROUP_CONCAT(`name`) AS name FROM yc_client_tag WHERE ".$where;
            $name = Db::query($sql);
            return $name[0]['name'];
        } catch (\Exception $e) {
            Log::write('获取标签名称异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 提交标签信息
     * @param int $id 标签id
     * @param string $name 标签名称
     * @return array ['code'=>0,'msg'=>'提交结果']
     */
    public function submitTag($id, $name)
    {
        try {
            // 标签唯一
            $where = "`name`='".$name."'";
            $where .= $id ? " AND `id`!=".$id : '';
            $sql_uniq = "SELECT `id` FROM yc_client_tag WHERE ".$where;
            $uniq = Db::query($sql_uniq);
            if($uniq){
                return ['code'=>1,'msg'=>'标签重复'];
            }
            if($id){
                return $this->updateTag($id, $name);
            }
            return $this->addTag($name);
        } catch (\Exception $e) {
            Log::write('提交标签信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 新增标签信息
     * @param string $name 标签名称
     * @return array ['code'=>0,'msg'=>'新增结果']
     */
    protected function addTag($name)
    {
        try {
            $time = time();
            $sql = "INSERT INTO yc_client_tag (`name`,`create_time`) VALUES ('".$name."',".$time.")";
            $add = Db::execute($sql);
            if(!$add){
                return ['code'=>2,'msg'=>'新增标签失败'];
            }
            return ['code'=>0,'msg'=>'新增标签成功'];
        } catch (\Exception $e) {
            Log::write('新增标签异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 更新标签信息
     * @param int $id 标签id
     * @param string $name 标签名称
     * @return array ['code'=>0,'msg'=>'新增结果']
     */
    protected function updateTag($id, $name)
    {
        try {
            $time = time();
            $sql = "UPDATE yc_client_tag SET `name`='".$name."',`modify_time`=".$time." WHERE `id`=".$id;
            $update = Db::execute($sql);
            if($update === false){
                return ['code'=>2,'msg'=>'更新标签失败'];
            }
            return ['code'=>0,'msg'=>'更新标签成功'];
        } catch (\Exception $e) {
            Log::write('更新标签异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 删除标签
     * @param int $int 标签id
     * @return array ['code'=>0,'msg'=>'删除结果']
     */
    public function delTag($id)
    {
        try {
            $sql = "DELETE FROM yc_client_tag WHERE `id`=".$id;
            $delete = Db::execute($sql);
            if(!$delete){
                return ['code'=>1,'msg'=>'删除失败'];
            }
            return ['code'=>0,'msg'=>'删除成功'];
        } catch (\Exception $e) {
            Log::write('标签删除异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

}