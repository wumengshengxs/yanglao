<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/2
 * Time: 13:21
 * 菜单模块
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;

class Menu extends Common {
    /**
     * 菜单列表
     * @param string $where 搜索条件
     * @return array
     */
    public function menuList($where='')
    {
        try {
            $where = $where ? " WHERE ".$where : '';
            $sql = "SELECT `id`,`name`,`url`,`icon`,`pid`,`level`,`m_status`,`weight`,`create_time` FROM yc_menu ".$where." ORDER BY `weight` ASC,`id` ASC";
            $menu = Db::query($sql);
            $tree = [];
            if($menu){  // 获取树状结构
                $tree = $this->menuTree($menu);
            }
            return $tree;
        } catch (\Exception $e) {
            Log::write('获取菜单列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 多级菜单树状结构
     * @param array $menu 菜单数组
     * $param string $id 分类主键字段
     * $param string $pid 上级分类id字段
     * $param string $name 分类名称字段
     * $param string $child 子分类数组的下标
     * $param int $level 根节点的pid
     * @return array $tree 树状结构的数组
     */
    public function menuTree($menu=[], $id='id', $pid='pid', $name='name', $child='nodes', $level=0)
    {
        try {
            $tree = [];
            $tmp_data = [];
            foreach($menu as $value){
                $tmp_data[$value[$id]] = $value;
            }
            foreach($tmp_data as $key=>$value){
                $tmp_data[$key]['text'] = $value[$name];    // 符合树状分类的结构
                if($value[$pid] == $level){   // 最顶级分类
                    $tree[] = &$tmp_data[$key];
                    continue;
                }
                //找到其父类
                $tmp_data[$value[$pid]][$child][] = &$tmp_data[$key];
            }
            return $tree;
        } catch (\Exception $e) {
            Log::write('树形结构菜单异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 提交菜单信息
     * @param int $id 菜单id
     * @param string $name 菜单名称
     * @param string $icon 菜单图标
     * @param string $url 菜单地址
     * @param int $pid 上级菜单id
     * @param int $status 菜单状态 1：启用；2：禁用
     * @param int $weight 菜单权重
     * @return array ['code'=>0,'msg'=>'提交结果']
     */
    public function submitMenu($id, $name, $icon, $url, $pid, $status, $weight)
    {
        try {
            // 菜单名称唯一
            $where = " `name`='".$name."'";
            $where .= $id ? " AND `id`!=".$id : '';
            $sql_uniq = "SELECT `id` FROM yc_menu WHERE ".$where;
            $uniq = Db::query($sql_uniq);
            if($uniq){
                return ['code'=>1,'msg'=>'菜单名称重复'];
            }
            // 更新菜单信息
            if($id){
                return $this->updateMenu($id, $name, $icon, $url, $pid, $status, $weight);
            }
            // 新增菜单
            return $this->addMenu($name, $icon, $url, $pid, $status, $weight);
        } catch (\Exception $e) {
            Log::write('提交菜单信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 新增菜单信息
     * @param string $name 菜单名称
     * @param string $icon 菜单图标
     * @param string $url 菜单地址
     * @param int $pid 上级菜单id
     * @param int $status 菜单状态 1：启用；2：禁用
     * @param int $weight 菜单权重
     * @return array ['code'=>0,'msg'=>'新增结果']
     */
    public function addMenu($name, $icon, $url, $pid, $status, $weight)
    {
        try {
            $level = 1;
            // 获取上级菜单的level
            if($pid){
                $sql_parent = "SELECT `level` FROM yc_menu WHERE id=".$pid;
                $parent = Db::query($sql_parent);
                $level = (int)$parent[0]['level']+1;
            }
            if($level > 3){
                return ['code'=>2,'msg'=>'最多添加三级菜单'];
            }
            $time = time();
            $sql = "INSERT INTO yc_menu (`name`,`icon`,`url`,`pid`,`level`,`m_status`,`weight`,`create_time`) VALUES ('".$name."','".$icon."','".$url."',".$pid.",".$level.",".$status.",".$weight.",".$time.")";
            $insert = Db::execute($sql);
            if(!$insert){
                return ['code'=>3,'msg'=>'添加菜单失败'];
            }
            return ['code'=>0,'msg'=>'添加菜单成功'];
        } catch (\Exception $e) {
            Log::write('新增菜单异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 更新菜单信息
     * @param int $id 菜单id
     * @param string $name 菜单名称
     * @param string $icon 菜单图标
     * @param string $url 菜单地址
     * @param int $pid 上级菜单id
     * @param int $status 菜单状态 1：启用；2：禁用
     * @param int $weight 菜单权重
     * @return array ['code'=>0,'msg'=>'更新结果']
     */
    protected function updateMenu($id, $name, $icon, $url, $pid, $status, $weight)
    {
        try {
            $time = time();
            $sql = "UPDATE yc_menu SET `name`='".$name."',`icon`='".$icon."',`url`='".$url."',`weight`=".$weight.",`m_status`=".$status.",`modify_time`=".$time." WHERE `id`=".$id;
            $update = Db::execute($sql);
            if($update === false){
                return ['code'=>2,'msg'=>'更新菜单失败'];
            }
            return ['code'=>0,'msg'=>'更新菜单成功'];
        } catch (\Exception $e) {
            Log::write('更新菜单异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }


    /*
    *编辑节点菜单
    *$id int 节点主键
    *return array
    */
    public  function delMenuInfo($id){
        try {
            $pid = db::name('menu')->where('id',$id)->value('pid');
            if ($pid==0) {
                return ['code'=>1,'msg'=>'顶级菜单不可删除'];
            }
            $res = db::name('menu')->where('id',$id)->delete();
            if ($res) {
                return ['code'=>0,'msg'=>'删除成功'];
            }
            return ['code'=>1,'msg'=>'删除失败'];
        } catch (Exception $e) {
            Log::write('编辑菜单异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }
}