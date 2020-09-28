<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/21
 * Time: 9:46
 * 工单管理
 */
namespace app\index\controller;

use app\index\controller\Common;
use think\Request;

class Work extends Common {
    /**
     * 全部工单列表
     */
    public function works()
    {
        $request = Request::instance();
        $work = new \app\index\model\Work;
        //工单列表
        $where = [];
        $item_value = [];
        $param = [
            'start_create'=>$request->get('start_create'),
            'end_create'=>$request->get('end_create'),
            'client'=>addslashes($request->get('client')),
            'work_state'=>addslashes($request->get('work_state')),
            'work_type'=>addslashes($request->get('work_type')),
            'call_result'=>addslashes($request->get('call_result')),
        ];
        //添加时间搜索
        if($param['start_create'] && $param['end_create']) {
            $end = $param['end_create'].' '.'23:59:59';
            $where['w.create_time'] = ['between',[strtotime($param['start_create']),strtotime($end)]];
            $item_value[] = ['name'=>'create','item'=>'创建时间','value'=>$param['start_create'].'至'.$param['end_create']];
        }
        if($param['start_create'] && !$param['end_create']){
            $where['w.create_time'] = ['egt',strtotime($param['start_create'])];
            $item_value[] = ['name'=>'start_create','item'=>'创建时间','value'=>$param['start_create'].'至-'];
        }
        if(!$param['start_create'] && $param['end_create']){
            $end = $param['end_create'].' '.'23:59:59';
            $where['w.create_time'] = ['elt',strtotime($end)];
            $item_value[] = ['name'=>'end_create','item'=>'创建时间','value'=>'-至'.$end];
        }
        //服务对象名称
        if($param['client']) {
            $where['c.name'] = ['like','%'.$param['client'].'%'];
            $item_value[] = ['name'=>'client','item'=>'服务对象','value'=>$param['client']];
        }
        //工单状态
        if($param['work_state']) {
            $where['w.state'] = ['eq',$param['work_state']];
            $item_value[] = ['name'=>'work_state','item'=>'工单状态','value'=>$work->work_state[$param['work_state']]];
        }
        //工单类型
        if($param['work_type']) {
            $where['w.type'] = ['eq',$param['work_type']];
            $item_value[] = ['name'=>'work_type','item'=>'工单类型','value'=>$work->work_type[$param['work_type']]];
        }
        $query = array_filter($param);
        $data = $work->worksList($where, $query);
        $this->assign('work',$data['work']);
        $this->assign('page',$data['page']);
        $this->assign('item_value',$item_value);
        $this->assign('param',$param);
        $this->assign('work_state',$work->work_state);
        $this->assign('work_type',$work->work_type);
        $this->assign('work_result',$work->call_result);
        // 获取工单统计结果
        $statistics = model('Work')->workStatisticalData();
        $this->assign('statistics',$statistics);
        $this->assign('title','养老平台 - 工单中心');
        return view();
    }

    /**
     * 工单详情
     */
    public function workDetails()
    {
        $model = model('Work');
        $request = Request::instance();
        $work_id = $request->get('id');
        $action = $request->get('action');
        // 获取工单详情
        $details = $model->workDetails($work_id);
        $this->assign('details',$details);
        // 获取工单对应的服务对象详情
        $client = model('Client')->userInfo($details['client_id']);
        $this->assign('client',$client);
        // 获取近期工单
        $whereW['w.client_id'] = ['eq',$details['client_id']];
        $work = $model->worksList($whereW, $query=[], $limit=5);
        $this->assign('work',$work['work']['data']);
        // 获取工单的操作日志
        $work_log = $model->workLogDetails($work_id);
        $this->assign('workLog',$work_log);
        $this->assign('action',$action);
        $this->assign('title','养老平台 - 工单详情');
        return view('work_details');
    }

    /**
     * 创建主动外呼工单
     */
    public function outboundCall()
    {
        $request = Request::instance();
        $client_id = (int)$request->post('client');
        $title = addslashes($request->post('title'));
        $u_type = $this->user_info['type'];
        $uid = ($u_type == 1) ? $this->user_info['id'] : $this->user_info['number'];
        $result = model('Work')->outboundCall($uid, $u_type, $client_id, $title);
        return $result;
    }

    /**
     * 受理工单
     */
    public function acceptWork()
    {
        $work_id = Request::instance()->post('id');
        $u_type = $this->user_info['type'];
        $uid = ($u_type == 1) ? $this->user_info['id'] : $this->user_info['number'];
        $result = model('Work')->acceptWork($work_id, $uid, $u_type);
        return $result;
    }
    
    /**
     * 获取话务员
     */
    public function staffUser()
    {
        $staffUser = model('StaffUser')->getUserList($where=[], $query=[], $limit=1000);
        return $staffUser['staff']['data'];
    }

    /**
     * 工单转交
     */
    public function transferWork()
    {
        $request = Request::instance();
        $work_id = (int)$request->post('work_id');
        $staff = (int)$request->post('staff');
        $u_type = $this->user_info['type'];
        $uid = ($u_type == 1) ? $this->user_info['id'] : $this->user_info['number'] ;
        $result = model('Work')->transferWork($work_id, $uid, $u_type, $staff);
        return $result;
    }
    
    /**
     * 关闭工单
     */
    public function closeWork()
    {
        $work_id = Request::instance()->post('id');
        $u_type = $this->user_info['type'];
        $uid = ($u_type == 1) ? $this->user_info['id'] : $this->user_info['number'] ;
        $result = model('Work')->closeWork($work_id, $uid, $u_type);
        return $result;
    }
    
    /**
     * 重新打开工单
     */
    public function openWork()
    {
        $work_id = Request::instance()->post('id');
        $u_type = $this->user_info['type'];
        $uid = ($u_type == 1) ? $this->user_info['id'] : $this->user_info['number'] ;
        $result = model('Work')->openWork($work_id, $uid, $u_type);
        return $result;
    }

    /**
     * 办结工单
     */
    public function finishWork()
    {
        $request = Request::instance();
        $call_result = (int)$request->post('call_result');
        $remarks = addslashes($request->post('remarks'));
        $work_id = (int)$request->post('work_id');
        $u_type = $this->user_info['type'];
        $uid = ($u_type == 1) ? $this->user_info['id'] : $this->user_info['number'] ;
        $result = model('Work')->finishWork($work_id, $uid, $u_type, $call_result, $remarks);
        return $result;
    }

    /**
     * 提交工单备注
     */
    public function subWorkRemarks()
    {
        $request = Request::instance();
        $wid = (int)$request->post('id');
        $remarks = addslashes($request->post('remarks'));
        $uid = $this->user_info['id'];
        $u_type = $this->user_info['type'];
        $result = model('Work')->subWorkRemarks($wid, $uid, $u_type, $remarks);
        return $result;
    }

    /**
     * 计划组列表
     */
    public function planGroup()
    {
        $request = Request::instance();
        $workPlan = new \app\index\model\WorkPlan();
        $param = [
            'start_create'=>addslashes($request->get('start_create')),
            'end_create'=>addslashes($request->get('end_create')),
            'name'=>addslashes($request->get('name')),
            'state'=>addslashes($request->get('state')),
        ];
        $where = [];
        $item_value = [];
        $end = $param['end_create'] ? $param['end_create'].' '.'23:59:59' : '' ;
        if($param['start_create'] && $param['end_create']){
            $where['create_time'] = ['between',strtotime($param['start_create']).','.strtotime($end)];
            $item_value[] = ['name'=>'create','item'=>'创建时间','value'=>$param['start_create'].'至'.$param['end_create']];
        }
        if($param['start_create'] && !$param['end_create']){
            $where['create_time'] = ['egt',strtotime($param['start_create'])];
            $item_value[] = ['name'=>'start_create','item'=>'创建时间','value'=>$param['start_create'].'至-'];
        }
        if(!$param['start_create'] && $param['end_create']){
            $where['create_time'] = ['elt',strtotime($end)];
            $item_value[] = ['name'=>'end_create','item'=>'创建时间','value'=>'-至'.$param['end_create']];
        }
        if($param['name']){
            $where['name'] = ['like','%'.$param['name'].'%'];
            $item_value[] = ['name'=>'name','item'=>'任务名称','value'=>$param['name']];
        }
        if($param['state']){
            $where['state'] = ['eq',$param['state']];
            $item_value[] = ['name'=>'state','item'=>'任务状态','value'=>$workPlan->plan_state[$param['state']]];
        }
        $query = array_filter($param);
        $plan = $workPlan->planGroupList($where, $query);
        foreach($plan['plan']['data'] as &$value){
            $value['start_time'] = date('Y-m-d',$value['start_time']);
            $value['end_time'] = date('Y-m-d',$value['end_time']);
            $value['create_time'] = date('Y-m-d',$value['create_time']);
        }
        $this->assign('plan',$plan['plan']);
        $this->assign('page',$plan['page']);
        $this->assign('param',$param);
        $this->assign('item_value',$item_value);
        $this->assign('title','养老平台 - 计划任务管理');
        return view('plan_group');
    }

    /**
     * 计划任务详情
     */
    public function planGroupDetails()
    {
        $id = Request::instance()->get('id');
        $details = model('WorkPlan')->planGroupDetails($id);
        $this->assign('details',$details);
        $this->assign('title','养老平台 - 计划任务详情');
        return view('plan_group_details');
    }
    
    /**
     * 获取计划任务工单
     */
    public function planGroupWork()
    {
        $request = Request::instance();
        $model = new \app\index\model\Work();
        $staff_number = $request->post('staff');
        $plan_id = $request->post('plan_id');
        $where['w.plan_id'] = ['eq',$plan_id];
        $where['p.state'] = ['eq',1];
        $query['plan_id'] = $plan_id;
        if($staff_number){
            $where['w.staff_number'] = ['eq',$staff_number];
            $query['staff'] = $staff_number;
        }
        $work = $model->planWorkList($where, $query);
        return $work['work'];
    }

    /**
     * 添加计划组页面
     */
    public function addPlanGroup()
    {
        // 获取话务员
        $staff = model('StaffUser')->getUserList($where=[], $query=[], $limit=10000);
        $this->assign('staff',$staff['staff']['data']);
        $this->assign('title','养老平台 - 新建计划任务');
        return view('add_plan_group');
    }
    
    /**
     * 提交计划组信息
     */
    public function subPlanGroup()
    {
        $request = Request::instance();
        $id = (int)$request->post('id');
        $name = addslashes($request->post('name'));
        $start_time = strtotime($request->post('start_time'));
        $end_time = strtotime($request->post('end_time'));
        $client = addslashes($request->post('client'));
        $staff = $request->post()['staff'];
        $allot = (int)$request->post('allot');
        $state = (int)$request->post('state');
        return model('WorkPlan')->submitWorkPlan($id, $name, $start_time, $end_time, $client, $staff, $allot, $state);
    }
    
    /**
     * 启用计划组
     */
    public function enablePlanGroup()
    {
        $plan_id = Request::instance()->post('id');
        $result = model('WorkPlan')->enablePlanGroup($plan_id);
        return $result;
    }

    /**
     * 删除计划组
     */
    public function delPlanGroup()
    {
        $plan_id = Request::instance()->post('id');
        $result = model('WorkPlan')->delPlanGroup($plan_id);
        return $result;
    }

    /**
     * 延期计划组
     */
    public function delayPlanGroup()
    {
        $request = Request::instance();
        $id = $request->post('id');
        $delay_time = strtotime($request->post('delay_time'));
        $result = model('WorkPlan')->delayPlanGroup($id, $delay_time);
        return $result;
    }

    /**
     * 计划工单列表
     */
    public function planWorks()
    {
        $model = new \app\index\model\Work();
        $request = Request::instance();
        $param = [
            'start_create'=>$request->get('start_create'),
            'end_create'=>$request->get('end_create'),
            'plan_state'=>$request->get('plan_state'),
        ];
        $where['w.plan_id'] = ['neq',0];
        $item_value = [];
        if($param['start_create'] && $param['end_create']){
            $end = $param['end_create'].' '.'23:59:59';
            $where['w.create_time'] = ['between',strtotime($param['start_create']).','.strtotime($end)];
            $item_value[] = ['name'=>'create','item'=>'创建时间','value'=>$param['start_create'].'至'.$param['end_create']];
        }
        if($param['start_create'] && !$param['end_create']){
            $where['w.create_time'] = ['egt',strtotime($param['start_create'])];
            $item_value[] = ['name'=>'start_create','item'=>'创建时间','value'=>$param['start_create'].'至-'];
        }
        if(!$param['start_create'] && $param['end_create']){
            $end = $param['end_create'].' '.'23:59:59';
            $where['w.create_time'] = ['elt',strtotime($end)];
            $item_value[] = ['name'=>'end_create','item'=>'创建时间','value'=>'-至'.$param['end_create']];
        }
        if($param['plan_state']){
            $where['w.plan_state'] = ['eq',$param['plan_state']];
            $item_value[] = ['name'=>'plan_state','item'=>'计划状态','value'=>$model->plan_state[$param['plan_state']]];
        }
        $query = array_filter($param);
        $plan_works = $model->planWorkList($where, $query);
        $this->assign('work',$plan_works['work']);
        $this->assign('page',$plan_works['page']);
        $this->assign('param',$param);
        $this->assign('item_value',$item_value);
        $this->assign('plan_state',$model->plan_state);
        $this->assign('title','养老平台 - 全部计划工单');
        return view('plan_works');
    }

    /**
     * 待质检工单
     */
    public function quality()
    {
        $request = Request::instance();
        $param = [
            'start_create'=>addslashes($request->get('start_create')),
            'end_create'=>addslashes($request->get('end_create'))
        ];
        $where['w.is_check'] = ['eq',1];
        $where['w.state'] = ['eq',5];
        $item_value = [];
        if($param['start_create'] && $param['end_create']){
            $end_create = $param['end_create'].' '.'23:59:59';
            $where['w.create_time'] = ['between',strtotime($param['start_create']).','.strtotime($end_create)];
            $item_value[] = ['name'=>'create','item'=>'创建时间','value'=>$param['start_create'].'至'.$param['end_create']];
        }
        if($param['start_create'] && !$param['end_create']){
            $where['w.create_time'] = ['egt',strtotime($param['start_create'])];
            $item_value[] = ['name'=>'start_create','item'=>'创建时间','value'=>$param['start_create'].'至-'];
        }
        if(!$param['start_create'] && $param['end_create']){
            $end_create = $param['end_create'].' '.'23:59:59';
            $where['w.create_time'] = ['elt',strtotime($end_create)];
            $item_value[] = ['name'=>'end_create','item'=>'创建时间','value'=>'-至'.$param['end_create']];
        }
        $query = array_filter($param);
        $work = model('Work')->worksList($where, $query);
        foreach($work['work']['data'] as &$value){
            // 获取处理时长
            $timeStampDiff = $value['finish_time']-$value['create_time'];
            $value['handle_time'] = F_callTime($timeStampDiff);
        }
        $this->assign('work',$work['work']);
        $this->assign('page',$work['page']);
        $this->assign('param',$param);
        $this->assign('item_value',$item_value);
        $this->assign('title','养老平台 - 待质检');
        return view();
    }

    /**
     * 质检结果记录
     */
    public function qualityResult()
    {
        $model = new \app\index\model\Work();
        $request = Request::instance();
        $param = [
            'start_create'=>addslashes($request->get('start_create')),
            'end_create'=>addslashes($request->get('end_create')),
            'client'=>addslashes($request->get('client')),
            'state'=>$request->get('state')
        ];
        $where['l.type'] = ['in','6,7'];
        $item_value = [];
        if($param['start_create'] && $param['end_create']){
            $end = $param['end_create'].' '.' 23:59:59';
            $where['w.create_time'] = ['between',strtotime($param['start_create']).','.strtotime($end)];
            $item_value[] = ['name'=>'create','item'=>'创建时间','value'=>$param['start_create'].'至'.$param['end_create']];
        }
        if($param['start_create'] && !$param['end_create']){
            $where['w.create_time'] = ['egt',strtotime($param['start_create'])];
            $item_value[] = ['name'=>'start_create','item'=>'创建时间','value'=>$param['start_create'].'至-'];
        }
        if(!$param['start_create'] && $param['end_create']){
            $end = $param['end_create'].' '.' 23:59:59';
            $where['w.create_time'] = ['elt',strtotime($end)];
            $item_value[] = ['name'=>'end_create','item'=>'创建时间','value'=>'-至'.$param['end_create']];
        }
        if($param['client']){
            $where['c.name'] = ['like','%'.$param['client'].'%'];
            $item_value[] = ['name'=>'client','item'=>'服务对象','value'=>$param['client']];
        }
        if($param['state']){
            $state = ($param['state'] == 1) ? 7 : 6 ;
            $where['l.type'] = ['eq',$state];
            $item_value[] = ['name'=>'state','item'=>'质检结果','value'=>$model->log_type[$state]];
        }
        $query = array_filter($param);
        $result = $model->qualityWorkRecords($where, $query);
        $this->assign('records',$result['records']);
        $this->assign('page',$result['page']);
        $this->assign('param',$param);
        $this->assign('item_value',$item_value);
        $this->assign('title','养老平台 - 质检结果');
        return view('quality_result');
    }

    /**
     * 通过工单
     */
    public function qualityWork()
    {
        $request = Request::instance();
        $work_id = $request->post('work_id');
        $score = $request->post('score');
        $u_type = $this->user_info['type'];
        $uid = ($u_type == 1) ? $this->user_info['id'] : $this->user_info['number'] ;
        $result = model('Work')->qualityWork($work_id, $uid, $u_type, $score);
        return $result;
    }

    /**
     * 退回工单
     */
    public function returnWork()
    {
        $request = Request::instance();
        $work_id = $request->post('work_id');
        $remarks = addslashes($request->post('remarks'));
        $u_type = $this->user_info['type'];
        $uid = ($u_type == 1) ? $this->user_info['id'] : $this->user_info['number'];
        $return = model('Work')->returnWork($work_id, $uid, $u_type, $remarks);
        return $return;
    }
    
    /*
    *报警消息
    */
    public function mess(){
        $res = model('Work')->GetWarningAll();
        return $res;
    }
}