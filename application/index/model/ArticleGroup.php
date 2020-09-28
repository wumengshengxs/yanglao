<?php
/**
 * 资讯文章分类模块
 * User: Hongfei
 * Date: 2019/1/22
 * Time: 下午1:47
 */

namespace app\index\model;

use think\Model;
use think\Log;
use think\Db;

class ArticleGroup extends Common
{
    public $group;

    public function __construct()
    {
        parent::__construct();
        $this->group = db::name('Article_group');
    }

    /**
     * 获取栏目列表
     * @return array
     */
    public function getGroup()
    {
        try {
            $data = $this->group
                ->order('weight desc')
                ->where(['del'=>1])
                ->select();
            $menu = new Menu();
            $group = $menu->menuTree($data);
            return $group;
        } catch (\Exception $e) {
            Log::write('获取栏目列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }

    /**
     * 获取顶级栏目
     */
    public function getTopGroup()
    {
        try {
            $data = $this->group->field('id,name')
                ->where(['pid'=>0,'del'=>1])
                ->order('weight desc')
                ->select();

            $str = "<option value='0' >顶级栏目</option >";
            foreach($data as $val){
                $str .= '<option value='.$val['id'].' >'.$val['name'].'</option >';
            }
            return $str;
        } catch (\Exception $e) {
            Log::write('获取顶级栏目异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }


    /**
     * 获取栏目信息
     * @param $id
     * @return array|string
     */
    public function getTopGroupone($id)
    {
        try {
            $data = $this->group->field('id,name')
                ->where(['pid'=>0,'id'=>$id])
                ->order('weight desc')
                ->select();
            if (empty($data)){
                return ['code'=>2,'msg'=>'请选择顶级栏目'];
            }

            $str = '';
            foreach($data as $val){
                $str .= '<option value='.$val['id'].' >'.$val['name'].'</option >';
            }
            return $str;
        } catch (\Exception $e) {
            Log::write('获取顶级栏目异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }

    /**
     * 获取修改信息
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     */
    public function getEditGroup($id)
    {
        try {
            $data = $this->group->where(['id'=>$id])->find();
            return $data;
        } catch (\Exception $e) {
            Log::write('获取修改信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }

    /**
     * 删除类型
     * @param $id
     * @return array
     */
    public function delGroup($id)
    {
        try {
            $group = $this->group->where(['pid'=>$id])->find();
            if ($group){
                return ['code'=>-1,'msg'=>'删除失败,该目录下有子目录'];
            }
            $this->group->where(['id'=>$id])->update(['del'=>2]);
            return ['code'=>0,'msg'=>'删除成功'];
        } catch (\Exception $e) {
            Log::write('获取修改信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 添加文章类型
     * @param $param
     * @return array
     */
    public function addGroup($param)
    {
        try {
            $validate = validate('ArticleGroup');
            if(!$validate->check($param)){
                $data = [ 'code'=>1,'msg'=>$validate->getError()];
                return $data;
            }
            if (empty($param['id'])){
                $param['create_time'] = time();
                $this->group->insert($param);
                return ['code'=>0, 'msg'=>'添加成功'];
            }

            $this->group->update($param);
            return ['code'=>0, 'msg'=>'修改成功'];
        } catch (\Exception $e) {
            Log::write('添加文章类型异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }

}