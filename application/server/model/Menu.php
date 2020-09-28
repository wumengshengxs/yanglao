<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/2
 * Time: 13:21
 * 菜单模块
 */
namespace app\server\model;

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
    public function menuList()
    {
        try {
            //菜单
            $menu = $this->getMemu();
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
     * 获取菜单
     * @return array
     */
    public function getMemu()
    {
        $menu = [
            ['id'=>1,'name'=>'工单管理','url'=>'javascript:;','icon'=>'fa fa-bank','pid'=>0],
            ['id'=>2,'name'=>'工单列表','url'=>'/server/work/index','icon'=>'','pid'=>1],
            ['id'=>3,'name'=>'人员管理','url'=>'javascript:;','icon'=>'fa fa-group','pid'=>0],
            ['id'=>4,'name'=>'人员列表','url'=>'/server/staff/index','icon'=>'','pid'=>3],
            ['id'=>7,'name'=>'消息中心','url'=>'javascript:;','icon'=>'fa fa-commenting','pid'=>0],
            ['id'=>8,'name'=>'消息列表','url'=>'/server/news/index','icon'=>'','pid'=>7],
            ['id'=>9,'name'=>'资产管理','url'=>'javascript:;','icon'=>'fa fa-credit-card','pid'=>0],
            ['id'=>10,'name'=>'资产列表','url'=>'/server/property/index','icon'=>'','pid'=>9],
            ['id'=>11,'name'=>'统计管理','url'=>'javascript:;','icon'=>'fa fa-bar-chart','pid'=>0],
            ['id'=>12,'name'=>'统计列表','url'=>'/server/count/index','icon'=>'','pid'=>11],
            ['id'=>13,'name'=>'系统设置','url'=>'javascript:;','icon'=>'fa fa-gears','pid'=>0],

        ];

        return $menu;
    }


}