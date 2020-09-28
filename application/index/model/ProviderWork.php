<?php
/**
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/3/28
 * Time: 下午1:19
 */

namespace app\index\model;


use think\Model;
use think\Log;
use think\Db;

class ProviderWork extends Model
{

    /**
     * 工单状态
     */
    public $work_state = [
        1=>'未接单',
        2=>'已接单',
        3=>'已结单',
        4=>'已关闭'
    ];

    /**
     * 获取服务工单列表
     * @param $param
     * @return array|\think\Paginator
     */
    public function getWorkList($param)
    {
        try {
            $query = [];
            $where = [];
            if ($param['name']) {
                $where['c.name'] = ['like','%'.$param['name'].'%'];
                $query['name'] = $param['name'];
            }

            if ($param['name']) {//服务对象名称
                $where['c.name'] = ['like','%'.$param['name'].'%'];
                $query['name'] = $param['name'];
            }
            if ($param['title']) {//工单标题
                $where['w.title'] = ['like','%'.$param['title'].'%'];
                $query['title'] = $param['title'];
            }
            if ($param['site']) {//服务地址 pid
                $where['w.site'] = ['like','%'.$param['site'].'%'];
                $query['site'] = $param['site'];
            }

            if ($param['pid']) {//服务商
                $where['w.pid'] = $param['pid'];
                $query['pid'] = $param['pid'];
            }

            if ($param['status']) {//服务类型
                $where['w.status'] = $param['status'];
                $query['status'] = $param['status'];
            }
            if ($param['sid']) {//服务人员
                $where['w.sid'] = $param['sid'];
                $query['sid'] = $param['sid'];
            }

            //时间
            if (!empty($param['start_create']) && !empty($param['end_create'])) {
                $Start = strtotime(date('Y-m-d 00:00:00', strtotime($param['start_create'])));
                $End = strtotime(date('Y-m-d 23:59:59', strtotime($param['end_create'])));

                $where['w.create_time'] = ['between',[$Start,$End]];
                $que['start_create'] = $param['start_create'];
                $que['end_create'] = $param['end_create'];
            }


            $work = $this
                ->alias('w')
                ->field('w.id,w.site,w.status,w.money,w.title,p.company,w.client,c.name as cname,s.name as sname,w.sid,w.state,w.create_time,w.end_time')
                ->join('client c','w.client=c.id','LEFT')
                ->join('provider_staff s','w.sid=s.id','LEFT')
                ->join('provider p','w.pid=p.id','LEFT')
                ->where($where)
                ->order('w.id DESC')
                ->paginate(30, false,$query);

            //获取服务类型
            $provider =  model('server/Provider')->projectList();
            $provider = array_column($provider, 'name', 'id');

            foreach ($work as $key=>$val){
                $work[$key]['status'] = $provider[$val['status']];
                $work[$key]['state'] = $this->work_state[$val['state']];
            }


            return $work;
        }catch (\Exception $e){
            Log::write('获取服务工单异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }


    /**
     * 添加服务工单
     * @param $param
     * @return array
     */
    public function addWork($param)
    {
        try {
            $validate = validate('ProviderWork');
            if(!$validate->check($param)){
                $error = ['code'=>1,'msg'=>$validate->getError()];
                return $error;
            }

            //调用高德地图api获取输入地址的经纬度
            $url = 'https://restapi.amap.com/v3/geocode/geo?key=d4a1dcba032740436a3fc87de1b695a5&address='.$param['site'].'&city=上海';
            $location = json_decode(curl_get($url),true)['geocodes'][0]['location'];
            $param['lng'] = explode(',',$location)[0];
            $param['lat'] = explode(',',$location)[1];
            $param['create_time'] = time();
            $param['state'] = 1;
            $res = $this->insert($param);
            if ($res){
                return [ 'code'=>0,'msg'=>'添加成功'];
            }
            return [ 'code'=>1,'msg'=>'添加失败'];

        }catch (\Exception $e){
            Log::write('添加服务工单异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取工单详情
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     */
    public function getWorkProvider($id)
    {
        try {
            $work = $this
                ->alias('w')
                ->field('w.*,p.company,c.name as cname,s.name as sname,t.name as tname')
                ->join('client c','w.client=c.id','LEFT')
                ->join('provider_staff s','w.sid=s.id','LEFT')
                ->join('provider_project t','w.status=t.id','LEFT')
                ->join('provider p','w.pid=p.id','LEFT')
                ->where(['w.id'=>$id])
                ->find();

            $work['end_time']   = $work['end_time'] ? date('Y-m-d H:i:s',$work['end_time']) : '未结单';
            $work['start_time'] = $work['start_time'] ? date('Y-m-d H:i:s',$work['start_time']) : '未接单';
            $work['type'] = $this->work_state[$work['state']];

            $work['work'] = $this
                ->alias('w')
                ->field('w.title,w.id,w.create_time,w.state,p.company,s.name as sname,t.name as tname')
                ->join('provider_staff s','w.sid=s.id','LEFT')
                ->join('provider_project t','w.status=t.id','LEFT')
                ->join('provider p','w.pid=p.id','LEFT')
                ->limit('8')
                ->where(['client'=>$work['client']])
                ->order('id desc')
                ->select();

            return $work;
        }catch (\Exception $e){
            Log::write('获取工单详情失败：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }

    /**
     * 关闭工单
     * @param $id
     * @return array
     */
    public function closeWork($id)
    {
        try {
            $state = $this->field('state')->where(['id'=>$id])->find();
            if ($state['state'] != 1){
                return [ 'code'=>1,'msg'=>'该工单已接单或已关闭'];
            }

            $data = [
                'id'=>$id,
                'state'=>4
            ];

            $up = $this->update($data);

            if ($up){
                return [ 'code'=>0,'msg'=>'关闭成功'];
            }
            return [ 'code'=>1,'msg'=>'关闭失败'];
        }catch (\Exception $e){
            Log::write('关闭工单失败：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }





}















