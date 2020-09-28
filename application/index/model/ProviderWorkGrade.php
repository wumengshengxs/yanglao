<?php
/**
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/3/29
 * Time: 下午3:15
 */

namespace app\index\model;


use think\Model;
use think\Log;
use think\Db;

class ProviderWorkGrade extends Model
{
    /**
     * 工单评分
     * @param $param
     * @return array
     */
    public function addGrade($param){
        try {
            $validate = validate('ProviderWorkGrade');
            if(!$validate->check($param)){
                $error = ['code'=>1,'msg'=>$validate->getError()];
                return $error;
            }
            $id = $param['id'];
            unset($param['id']);
            $param['total'] = round(array_sum($param)/count($param),1);
            $param['create_time'] = time();
            $gid = $this->insertGetId($param);
            if ($gid){
                Db::name('provider_work')->update(array('id'=>$id,'gid'=>$gid));
                return [ 'code'=>0,'msg'=>'添加成功'];
            }
            return [ 'code'=>1,'msg'=>'添加失败'];

        }catch (\Exception $e){
            Log::write('服务工单评分异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取服务工单评分信息
     * @param $id
     * @return array
     */
    public function getGrade($id)
    {
        try {
         $grade = $this->where(['id'=>$id])->find();

         return $grade;

        }catch (\Exception $e){
            Log::write('获取服务工单评分信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 修改评分
     * @param $param
     * @return array
     */
    public function saveGrade($param)
    {
        try {
            $validate = validate('ProviderWorkGrade');
            if(!$validate->check($param)){
                $error = ['code'=>1,'msg'=>$validate->getError()];
                return $error;
            }
            $id = $param['id'];
            unset($param['id']);
            $param['total'] = round(array_sum($param)/count($param),1);
            $param['id'] = $id;
            $gid = $this->update($param);
            if ($gid){
                return [ 'code'=>0,'msg'=>'修改成功'];
            }
            return [ 'code'=>1,'msg'=>'修改失败'];

        }catch (\Exception $e){
            Log::write('修改评分异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

}