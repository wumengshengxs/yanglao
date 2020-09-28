<?php
/**
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/3/22
 * Time: 下午2:57
 */

namespace app\server\model;


use think\Log;
use think\Model;

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
     * @param $pid=>服务商id
     * @param $param
     * @return array|\think\Paginator
     */
    public function getWorkList($pid,$param)
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
                ->field('w.id,w.site,w.status,w.money,w.title,w.client,c.name as cname,s.name as sname,w.sid,w.state,w.create_time,w.end_time')
                ->join('client c','w.client=c.id','LEFT')
                ->join('provider_staff s','w.sid=s.id','LEFT')
                ->where(['w.pid'=>$pid])
                ->where($where)
                ->order('w.id DESC')
                ->paginate(30, false,$query);

            //获取服务类型
            $provider =  model('Provider')->projectList();
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

    //统计当月金额
    static public function countWork($id)
    {
        try {
            $mStart = mktime(0,0,0,date('m'),1,date('Y'));
            $mEnd = mktime(23,59,59,date('m'),date('t'),date('Y'));
            $where['end_time'] = ['between',[$mStart,$mEnd]];
            $where['sid'] = $id;
            $where['state'] = 3;

            $money = self::where($where)->sum('money');
            return $money ? $money : 0;
        }catch (\Exception $e){
            Log::write('统计当月金额失败：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

}