<?php
/**
 * 资讯文章模块
 * User: Hongfei
 * Date: 2019/1/22
 * Time: 下午1:47
 */

namespace app\index\model;

use think\Model;
use think\Log;

class Article extends Model
{

    /**
     * 获取文章列表
     * @param $param
     * @return array|\think\Paginator
     */
    public function getArticle($param)
    {
        try {

            $where = [];
            //姓名
            if (!empty($param['title'])) {
                $where['a.title'] = ['like','%'.$param['title'].'%'];
                $que['title'] = $param['title'];
            }

            //性别
            if (isset($param['state']) && $param['state'] !== '') {
                $where['a.state'] = $param['state'];
                $que['state'] = $param['state'];
            }

            //年龄
            if (!empty($param['start']) && !empty($param['end'])) {

                if ($param['start'] > $param['end']){
                    return ['code'=>1,'msg'=>'请输入正确的时间范围'];
                }
                $Start = strtotime(date('Y-m-d 00:00:00', strtotime($param['start'])));
                $End = strtotime(date('Y-m-d 23:59:59', strtotime($param['end'])));

                $where['u.age'] = ['between',[$Start,$End]];
                $que['start'] = $param['start'];
                $que['end'] = $param['end'];
            }

            $data = $this
                ->alias('a')
                ->field('a.id,a.title,g.name,a.create_time,a.weight,a.state')
                ->join('article_group g','a.gid = g.id','left')
                ->where(['a.del'=>1])
                ->where($where)
                ->order('id desc')
                ->paginate(30,false,['query'=>$que]);

            return $data;
        } catch (\Exception $e) {
            Log::write('获取文章列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }

    /**
     * 添加修改文章
     * @param $param
     * @return array
     */
    public function addArticle($param)
    {
        try {
            $validate = validate('Article');
            if(!$validate->check($param)){
                $call = ['code'=>1,'msg'=>$validate->getError()];
                return $call;
            }

            if (empty($param['id'])){
                $param['create_time'] = time();
                $this->insert($param);
                return ['code'=>0, 'msg'=>'添加成功'];
            }

            $this->update($param);
            return ['code'=>0, 'msg'=>'修改成功'];
        } catch (\Exception $e) {
            Log::write('添加修改文章异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 查看修改文章
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     */
    public function getEditArticle($id)
    {
        try {
            $data = $this->where(['id'=>$id])->find();
            return $data;
        } catch (\Exception $e) {
            Log::write('查看修改文章异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 删除文章
     * @param $id
     * @return array
     */
    public function delArticle($id)
    {
        try {
            $this->where(['id'=>$id])->update(['del'=>2]);
            return ['code'=>0,'msg'=>'删除成功'];
        } catch (\Exception $e) {
            Log::write('获取修改信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }


}