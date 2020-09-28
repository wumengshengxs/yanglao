<?php
/**
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/3/22
 * Time: 下午4:37
 */

namespace app\api\model;


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
        3=>'已结单'
    ];

    /**
     * 获取当前和服务人员相匹配的工单
     * @param $status=>工单服务类型
     * @return array
     */
    public function getWorkServer($status)
    {
        $work = $this
            ->alias('w')
            ->field('w.id,w.lat,w.lng,w.site,w.state,w.status,w.money,c.name as client,w.create_time')
            ->join('client c','w.client=c.id','LEFT')
            ->where(['state'=>['in',[1,2]],'status'=>$status])//展示未接单
            ->select();
        $data = [];
        foreach ($work as $key=>$val){
            $data[] = [
                'id'=>$key,
                'state'=> $this->work_state[$val['state']],
                'address'=>$val['site'],
                'iconPath'=>"../../img/marker.png",
                'wid'=>$val['id'],
                'latitude'=>$val['lat'],
                'longitude'=>$val['lng'],
                'name'=>'上海市',
                'client'=>$val['client'],
                'money'=>$val['money'],
                'height'=>'42',
                'width'=>'28'
            ];
        }

        return $data;

    }

    /**
     * 获取选择的工单
     * @param $param
     * @param $status=>人员类型
     * @return array|false|mixed|\PDOStatement|string|Model
     */
    public function workStart($param,$status)
    {
        $wx = new WxChat();
        $id = $param['id'];
        $lat = $param['lat'];
        $lng = $param['lng'];

        if (!empty($id)){
            $work = $this
                ->alias('w')
                ->field('w.*,c.name,c.mobile')
                ->join('client c','w.client=c.id','LEFT')
                ->where(['w.id'=>$id])
                ->find();
            //获取距离单位m
            $distance = $wx->getDistance($lng,$lat,$work['lng'],$work['lat']);
            $work['distance'] = round($distance);

            $work['start_time'] = $work['start_time'] ? date('Y-m-d H:i:s',$work['start_time']) : '未接单';
            $work['end_time'] = $work['end_time'] ? date('Y-m-d H:i:s',$work['end_time']) : '未接单';

            return $work;
        }
        //没有传递id 取最近工单
        $data = $this
            ->alias('w')
            ->field('w.*,c.name,c.mobile')
            ->join('client c','w.client=c.id','LEFT')
            ->where(['status'=>$status,'state'=>1])
            ->select();
        if (!empty($data)){
            foreach ($data as $key=>$val){
                $distance = $wx->getDistance($lng,$lat,$val['lng'],$val['lat']);
                $data[$key]['distance'] = round($distance);
                $data[$key]['start_time'] = $val['start_time'] ? date('Y-m-d H:i:s',$val['start_time']) : '未接单';
            }
            $last = array_column($data,'distance');
            array_multisort($last,SORT_ASC,$data);//根据距离排序

        }

        return $data[0];

    }

    /**
     * 获取上一条下一条记录
     * @param $param
     * @param $status=>人员类型
     * @param $state=>1上一条 2下一条
     * @return mixed
     */
    public function workUp($param,$status,$state)
    {
        $wx = new WxChat();
        $id = $param['id'];
        $lat = $param['lat'];
        $lng = $param['lng'];

        if ($state == 1){
            $sql = 'select w.*,c.name,c.mobile from yc_provider_work as w left outer join yc_client as c 
                  on w.client = c.id where w.id = (select id from yc_provider_work where `state` = 1 AND 
                  `status` = '.$status.' AND id < '.$id.' order by id desc limit 1)';
        }else{
            $sql = 'select w.*,c.name,c.mobile from yc_provider_work as w left outer join yc_client as c 
                on w.client = c.id where w.id = (select id from yc_provider_work where `state` = 1 
                AND `status` = '.$status.' AND id > '.$id.' order by id asc limit 1)';
        }

        $work = Db::query($sql)[0];

        //获取距离单位m
        if(!empty($work)){
            $distance = $wx->getDistance($lng,$lat,$work['lng'],$work['lat']);
            $work['distance'] = round($distance);
            $work['create_time'] = date('Y-m-d H:i:s',$work['create_time']);

            $work['start_time'] = $work['start_time'] ? date('Y-m-d H:i:s',$work['start_time']) : '未接单';

        }
        return $work;

    }

    /**
     * 开始接单
     * @param $param
     * @param $sid=>接单人id
     * @return array
     */
    public function workTake($param,$sid)
    {
        $id = $param['id'];
        $take = $this->field('state,sid')->where(['id'=>$id])->find();
        if ($take['state'] != 1 || !empty($take['sid'])){
            return ['code'=>1,'msg'=>'该工单已被接单'];
        }

        $data = [
            'id'=>$param['id'],
            'state'=>2,
            'sid'=>$sid,
            'start_time'=>time(),
        ];
        $up = $this->update($data);
        if ($up) {
            return ['code' => 0, 'msg' => '接单成功'];
        }

    }

    /**
     * 结单
     * @param $param
     * @return array
     */
    public function workClose($param)
    {
        $id = $param['id'];
        $take = $this->field('state')->where(['id'=>$id])->find();
        if ($take['state'] != 2){
            return ['code'=>1,'msg'=>'该工单已结单'];
        }

        $data = [
            'id'=>$param['id'],
            'state'=>3,
            'end_time'=>time(),
        ];
        $up = $this->update($data);
        if ($up) {
            return ['code' => 0, 'msg' => '结单成功'];
        }
    }


    /**
     * 完结工单详情
     * @param $param
     * @return array|false|\PDOStatement|string|Model
     */
    public function workMark($param)
    {
        $wx = new WxChat();
        $id = $param['id'];
        $lat = $param['lat'];
        $lng = $param['lng'];

        $work = $this
            ->alias('w')
            ->field('w.*,c.name,c.mobile,s.name as sname,s.image')
            ->join('client c','w.client=c.id','LEFT')
            ->join('provider_staff s','w.sid=s.id','LEFT')
            ->where(['w.id'=>$id])
            ->find();

        if ($work['gid'] != 0){
            $work['grade'] = Db::name('provider_work_grade')
                ->where(['id'=>$work['gid']])->find();
        }
        //获取距离单位m
        $distance = $wx->getDistance($lng,$lat,$work['lng'],$work['lat']);
        $work['distance'] = round($distance);

        $work['start_time'] = $work['start_time'] ? date('Y-m-d H:i:s',$work['start_time']) : '未接单';
        $work['end_time'] = $work['end_time'] ? date('Y-m-d H:i:s',$work['end_time']) : '未接单';

        return $work;
    }

    /**
     * 获取工单列表
     * @param $param
     * @param $status=>工单类型
     * @param $id=>人员id
     * @param $state=>所属列表
     * @return mixed
     */
    public function workShow($param,$status,$id,$state)
    {
        $wx = new WxChat();
        $lat = $param['lat'];
        $lng = $param['lng'];
        if (!empty($state)){
            $work = $this
                ->field('title,create_time,id,state,lng,lat')
                ->where(['sid'=>$id,'state'=>['in',$state]])
                ->order('id desc')
                ->select();
        }else{
            $work = $this
                ->field('title,create_time,id,state,lng,lat')
                ->where(['state'=>1,'status'=>$status])
                ->order('id desc')
                ->select();
        }

        foreach ($work as $key=>$val){
            $distance = $wx->getDistance($lng,$lat,$val['lng'],$val['lat']);
            $work[$key]['distance'] = round($distance);
            $work[$key]['type'] =  $this->work_state[$val['state']];


        }

        return $work;
    }




}