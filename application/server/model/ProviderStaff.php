<?php
/**
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/3/20
 * Time: 上午9:39
 */

namespace app\server\model;


use think\Db;
use think\Model;
use think\Log;

class ProviderStaff extends Model
{
    /**
     * 获取人员列表
     * @param $param
     * @param $pid
     * @return array|\think\Paginator
     */
    public function getStaffList($param,$pid)
    {
        try {

            $query = [];
            $where = [];
            if ($param['mobile']) {//账号
                $where['mobile'] = ['like','%'.$param['mobile'].'%'];
                $query['mobile'] = $param['mobile'];
            }
            if ($param['name']) {//姓名
                $where['name'] = ['like','%'.$param['name'].'%'];
                $query['name'] = $param['mobile'];
            }
            if ($param['status']) {//类型
                $where['status'] = $param['status'];
                $query['status'] = $param['status'];
            }
            if ($param['state']) {//状态
                $where['state'] = $param['state'];
                $query['state'] = $param['state'];
            }
            //时间
            if (!empty($param['start_create']) && !empty($param['end_create'])) {
                $Start = strtotime(date('Y-m-d 00:00:00', strtotime($param['start_create'])));
                $End = strtotime(date('Y-m-d 23:59:59', strtotime($param['end_create'])));

                $where['create_time'] = ['between',[$Start,$End]];
                $que['start_create'] = $param['start_create'];
                $que['end_create'] = $param['end_create'];
            }
            //工单查询约束 获取本月时间戳
            $where['s.pid'] = $pid;
            $staff = $this
                ->field('id,name,mobile,pid,status,state,create_time')
                ->where($where)
                ->order('id DESC')
                ->paginate(30, false,$query);
            //获取服务类型
            $provider =  model('Provider')->projectList();
            $provider = array_column($provider, 'name', 'id');
            foreach ($staff as $key=>$val){
                $staff[$key]['money'] = ProviderWork::countWork($val['id']);
                $staff[$key]['state'] = $val['state'] == 2 ? '禁止' : '正常';
                $staff[$key]['status'] = $provider[$val['status']];
            }

            return $staff;
        }catch (\Exception $e){
            Log::write('获取话务员异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 添加服务人员
     * @param $param
     * @param $pid => 服务商id
     * @return array
     */
    public function addStaff($param,$pid)
    {
        try {
            $validate = validate('ProviderStaff');
            if(!$validate->check($param)){
                $error = ['code'=>1,'msg'=>$validate->getError()];
                return $error;
            }

            $param['create_time'] = time();
            $param['pid'] = $pid;
            $param['password'] = md5(md5($param['password']).'pension');
            $res = $this->insert($param);
            if ($res){
                return [ 'code'=>0,'msg'=>'添加成功'];
            }
            return [ 'code'=>1,'msg'=>'添加失败'];

        }catch (\Exception $e){
            Log::write('添加服务人员异常'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取编辑人员信息
     * @param $id=>人员id
     * @return array
     */
    public function getStaff($id)
    {
        try {
            $staff = $this
                ->field('id,name,mobile,status,state')
                ->where(['id'=>$id])
                ->find();
            //获取服务类型
            $provider =  model('Provider')->projectList();
            $str = '';
            foreach ($provider as $val){
                if ($staff['id'] == $val['status']){
                    $str .= '<option value='.$val['id'].' selected="selected" >'.$val['name'].'</option >';
                }else{
                    $str .= '<option value='.$val['id'].' >'.$val['name'].'</option >';
                }
            }
            $staff['provider'] = $str;
            return $staff;
        }catch (\Exception $e){
            Log::write('获取编辑人员信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 修改服务人员信息
     * @param $param
     * @return array
     */
    public function editStaff($param)
    {
        try {
            $validate = validate('ProviderStaff');
            if(!$validate->scene('editStaff')->check($param)){
                $call = ['code'=>1,'msg'=>$validate->getError()];
                return $call;
            }
            if (!empty($param['password'])){
                $param['password'] = md5(md5($param['password']).'pension');
            }else{
                unset($param['password']);
            }
            $res = $this->update($param);
            if ($res){
                return [ 'code'=>0,'msg'=>'修改成功'];
            }
            return [ 'code'=>1,'msg'=>'修改失败'];

        }catch (\Exception $e){
            Log::write('修改服务人员信息异常'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

}