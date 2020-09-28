<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/21
 * Time: 9:53
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;

class Work extends Common {
    /**
     * 工单类型
     */
    public $work_type = [
        1=>'紧急呼叫',
        2=>'越界报警',
        3=>'心率报警',
        4=>'主动关怀',
        5=>'主动外呼',
        6=>'主动呼入',
        7=>'烟感报警',
        8=>'燃气报警',
        9=>'红外报警'
    ];

    /**
     * 工单状态
     */
    public $work_state = [
        1=>'未分配',
        2=>'未受理',
        3=>'受理中',
        4=>'已关闭',
        5=>'已办结'
    ];

    /**
     * 外呼结果
     */
    public $call_result = [
        1=>'正常接听',
        2=>'未接听',
        3=>'挂断',
        4=>'听不清或无声',
        5=>'SOS报警外呼',
        6=>'回访',
        7=>'联系亲属'
    ];

    /**
     * 计划工单状态
     */
    public $plan_state = [
        1=>'未进行',
        2=>'进行中',
        3=>'按期完成',
        4=>'延期完成',
        5=>'已逾期'
    ];

    /**
     * 日志类型
     */
    public $log_type = [
        1=>'创建订单',
        2=>'受理工单',
        3=>'转交工单',
        4=>'关闭工单',
        5=>'办结工单',
        6=>'退回工单',
        7=>'通过工单',
        8=>'工单备注',
        9=>'重新打开工单'
    ];

    /**
     * 获取全部工单列表
     * @param array|string $where 搜索条件
     * @param array $query 分页条件
     * @param int $limit 分页条数
     * @param array ['work'=>'工单列表数组','page'=>'分页信息']
     */
    public function worksList($where=[], $query=[], $limit=20)
    {
        try {
            $data = Db::name('work')->alias('w')
                ->field('w.id,w.title,w.staff_number,w.plan_id,w.client_id,w.type,w.state,w.create_time,(case when w.plan_id!=0 then (select name from yc_work_plan where id=w.plan_id) else 0 end) as plan_title,(case when w.state=5 then (select concat_ws(";",call_result,create_time) from yc_work_log where w_id=w.id order by id desc limit 1) else 0 end) as work_log,c.name as userName,s.display_name')
                ->join('client c','w.client_id=c.id','LEFT')
                ->join('staff_user s','w.staff_number=s.number','LEFT')
                ->where($where)
                ->order('w.id desc')
                ->paginate($limit, false, ['query'=>$query]);
            $page = $data->render();
            $work = $data->toArray();
            foreach ($work['data'] as $key=>&$value) {
                $work_type = $this->work_type[$value['type']];
                if(in_array($value['type'],[1,2,3,7,8,9])){
                    $value['title'] = $work_type;
                }
                if($value['type'] == 4){
                    $value['title'] = $value['plan_title'];
                }
                $value['call_result'] = '--';
                if($value['work_log']){
                    $work_log = explode(';',$value['work_log']);
                    $value['call_result'] = $this->call_result[$work_log[0]];
                    $value['finish_time'] = $work_log[1];
                }
                $value['type'] = $work_type;
                $value['state'] = $this->work_state[$value['state']];
                unset($value['work_log']);
            }
            return ['work'=>$work,'page'=>$page];
        } catch (\Exception $e) {
            Log::write('获取全部工单异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 工单详情
     * @param int $id 工单id
     * @return array
     */
    public function workDetails($id)
    {
        try {
            $sql = "SELECT `w`.`id`,`w`.`title`,`w`.`type`,`w`.`client_id`,`w`.`state`,`w`.`plan_state`,`w`.`address`,`w`.`lng`,`w`.`lat`,`w`.`heart`,`w`.`alarm_time`,`w`.`create_time`,`u`.`name` AS `clientName`,`s`.`display_name`,(CASE WHEN `w`.`state`=5 THEN (SELECT CONCAT_WS(';',`call_result`,`remarks`,`create_time`) FROM yc_work_log WHERE `type`=5 AND `w_id`=".$id." ORDER BY `id` DESC LIMIT 1) ELSE 0 END) AS `work_log`,(CASE WHEN `w`.`type`=4 THEN (SELECT CONCAT_WS(';',`name`,`start_time`,`end_time`) FROM yc_work_plan WHERE `id`=`w`.`plan_id`) ELSE 0 END) AS `work_plan` FROM `yc_work` AS `w` LEFT JOIN `yc_client` AS `u` ON `w`.`client_id`=`u`.`id` LEFT JOIN `yc_staff_user` AS `s` ON `w`.`staff_number`=`s`.`number` WHERE w.`id`=".$id;
            $details = Db::query($sql);
            if($details[0]['state'] == 5){
                $log = explode(';',$details[0]['work_log']);
                $details[0]['call_result'] = $this->call_result[$log[0]];
                $details[0]['remarks'] = $log[1] ? $log[1] : '--';
                $details[0]['finish_time'] = date('Y-m-d H:i:s',$log[2]);
            }
            $details[0]['alarm_time'] = $details[0]['alarm_time'] ? date('Y-m-d H:i:s',$details[0]['alarm_time']) : date('Y-m-d H:i:s',$details[0]['create_time']);
            $details[0]['content'] = '--';
            if($details[0]['type'] == 1){
                $details[0]['content'] = '用户：'.$details[0]['clientName'].' 在'.$details[0]['alarm_time'].'触发了紧急呼叫，报警地址：'.$details[0]['address'];
            }
            if($details[0]['type'] == 2){
                $details[0]['content'] = '用户：'.$details[0]['clientName'].' 在'.$details[0]['alarm_time'].'触发了越界报警，报警地址：'.$details[0]['address'];
            }
            if($details[0]['type'] == 3){
                $details[0]['content'] = '用户：'.$details[0]['clientName'].' 在'.$details[0]['alarm_time'].'触发了心率报警，心率值：'.$details[0]['heart'];
            }
            if($details[0]['type'] == 7 || $details[0]['type'] == 8 || $details[0]['type'] == 9){
                $details[0]['content'] = '居家安防设备'.' 在'.$details[0]['alarm_time'].'产生了报警,请及时联系'.'用户：'.$details[0]['clientName'];
            }
            $work_type = $this->work_type[$details[0]['type']];
            $work_state = $this->work_state[$details[0]['state']];
            if($details[0]['type'] == 4){
                $work_plan = explode(';',$details[0]['work_plan']);
                $details[0]['title'] = $work_plan[0];
                $details[0]['start_time'] = date('Y-m-d',$work_plan[1]);
                $details[0]['end_time'] = date('Y-m-d',$work_plan[2]);
            }
            if(in_array($details[0]['type'],[1,2,3,7,8,9])){
                $details[0]['title'] = $work_type;
            }
            $details[0]['type'] = $work_type;
            $details[0]['state'] = $work_state;
            $details[0]['plan_state'] = $this->plan_state[$details[0]['plan_state']];
            $details[0]['create_time'] = date('Y-m-d H:i:s',$details[0]['create_time']);
            return $details[0];
        } catch (\Exception $e) {
            Log::write('工单详情异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取计划工单列表
     * @param array|string $where 搜索条件
     * @param array $query 分页条件
     * @param int $limit 分页条数
     * @param array ['work'=>'计划工单列表','page'=>'分页信息']
     */
    public function planWorkList($where=[], $query=[], $limit=20)
    {
        try {
            $data = Db::name('work')->alias('w')
                ->field('w.id,w.plan_state,w.create_time,c.name as userName,p.start_time,p.end_time,(case when w.state=5 then (select create_time from yc_work_log where w_id=w.id and type=5 order by id desc limit 1) else 0 end) as finish_time')
                ->join('client c','w.client_id=c.id','LEFT')
                ->join('work_plan p','w.plan_id=p.id','LEFT')
                ->where($where)
                ->order('w.id desc')
                ->paginate($limit, false, ['query'=>$query]);
            $page = $data->render();
            $work = $data->toArray();
            foreach($work['data'] as &$value){
                $value['create_time'] = date('Y-m-d',$value['create_time']);
                $value['start_time'] = date('Y-m-d',$value['start_time']);
                $value['end_time'] = date('Y-m-d',$value['end_time']);
                $value['plan_state'] = $this->plan_state[$value['plan_state']];
                $value['finish_time'] = $value['finish_time'] ? date('Y-m-d',$value['finish_time']) : '--';
            }
            return ['work'=>$work,'page'=>$page];
        } catch (\Exception $e) {
            Log::write('获取计划工单列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 工单统计数据
     * @param array|string $where 统计条件
     * @return array $statistical 统计数据数组
     */
    public function workStatisticalData($where=[])
    {
        try {
            $statistical = Db::name('work')
                ->field('
            sum(case when state=1 then 1 else 0 end) as unallocated,
            sum(case when state=2 then 1 else 0 end) as noaccepted,
            sum(case when state=3 then 1 else 0 end) as accepted,
            sum(case when state=4 then 1 else 0 end) as closed,
            sum(case when state=5 then 1 else 0 end) as completed,
            sum(case when plan_id!=0 then 1 else 0 end) as plan
            ')
                ->where($where)
                ->select();
            return $statistical[0];
        } catch (\Exception $e) {
            Log::write('获取工单统计数据异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取工单质检结果
     * @param array|string $where 搜索条件
     * @param array $query 分页条件
     * @param int $limit 分页条数
     * @return array ['log'=>'日志信息','page'=>'分页信息']
     */
    public function qualityWorkRecords($where=[], $query=[], $limit=20)
    {
        try {
            $data = Db::name('work_log')->alias('l')
                ->field('l.w_id,l.type,l.create_time as quality_time,l.quality_score,w.type as work_type,w.create_time,c.name as clientName,s.display_name as staffUser')
                ->join('work w','l.w_id=w.id','LEFT')
                ->join('client c','c.id=w.client_id','LEFT')
                ->join('staff_user s','s.number=w.staff_number','LEFT')
                ->where($where)
                ->order('l.id desc')
                ->paginate($limit, false, ['query'=>$query]);
            $page = $data->render();
            $records = $data->toArray();
            foreach($records['data'] as &$value){
                // 获取最后一次的办结记录
                $finish = Db::query("SELECT `create_time` FROM yc_work_log WHERE `type`=5 AND `w_id`=".$value['w_id']." ORDER BY `id` DESC LIMIT 1")[0];
                $diffT_time = $finish['create_time']-$value['create_time'];
                $value['handle_time'] = F_callTime($diffT_time);
                $value['finish_time'] = date('Y-m-d H:i:s',$finish['create_time']);
                $value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
                $value['quality_time'] = date('Y-m-d H:i:s',$value['quality_time']);
                $value['type'] = $this->log_type[$value['type']];
                $value['work_type'] = $this->work_type[$value['work_type']];
            }
            return ['records'=>$records,'page'=>$page];
        } catch (\Exception $e) {
            Log::write('工单操作日志异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取指定工单的操作日志
     * @param int $wid 工单ID
     * @return array []
     */
    public function workLogDetails($wid)
    {
        try {
            $sql = "SELECT l.`type`,(CASE WHEN `l`.`call_id`!=0 THEN (SELECT CONCAT_WS(',','call_id','caller') FROM yc_call_log WHERE `call_id`=`l`.`call_id`) ELSE 0 END) AS `call_log`,(CASE WHEN `l`.`u_type`=1 THEN (SELECT `name` FROM yc_user WHERE `id`=`l`.`uid`) ELSE (SELECT `display_name` FROM yc_staff_user WHERE `number`=`l`.`uid`) END) AS `user_name`,(CASE WHEN `l`.`staff_number`!=0 THEN (SELECT `display_name` FROM yc_staff_user WHERE `number`=`l`.`staff_number`) ELSE 0 END) AS staff_user,(CASE WHEN `w`.`plan_id`!=0 AND `l`.`type`=1 THEN (SELECT `name` FROM yc_work_plan WHERE `id`=`w`.`plan_id`) ELSE 0 END) AS `plan_name`,`l`.`remarks`,`l`.`call_result`,`l`.`create_time`,`w`.`type` AS `work_type` FROM yc_work_log AS `l` LEFT JOIN yc_work AS `w` ON `l`.`w_id`=`w`.`id` WHERE l.`w_id`=".$wid." ORDER BY `l`.`id` DESC";
            $logs = Db::query($sql);
            foreach($logs as &$value){
                $log_type = $this->log_type[$value['type']];
                if(in_array($value['work_type'],[1,2,3,7,8,9]) && $value['type'] == 1){
                    $value['content'] = "系统接收报警信息 自动创建工单";
                }
                if($value['work_type'] == 4 && $value['type'] == 1){
                    $value['content'] = "由计划任务组：".$value['plan_name']." 自动创建工单";
                }
                if($value['work_type'] == 5 && $value['type'] == 1){
                    $value['content'] = "系统主动外呼 自动创建工单";
                }
                if($value['work_type'] == 6 && $value['type'] == 1){
                    $value['content'] = "系统接到电话呼入 自动创建工单";
                }
                if(in_array($value['type'],[2,9])){
                    $value['content'] = $value['user_name']." ".$log_type;
                }
                if($value['type'] == 3){
                    $value['content'] = $value['user_name']." 转交工单给话务员：".$value['staff_user'];
                }
                if(in_array($value['type'],[4,6,7])){
                    $value['content'] = $value['user_name']." ".$log_type;
                    $value['content'] .= $value['remarks'] ? ' 备注：'.$value['remarks'] : '' ;
                }
                if($value['type'] == 5){
                    $value['content'] = $value['user_name']." 办结工单，办结原因：".$this->call_result[$value['call_result']]."，办结备注：".($value['remarks'] ? $value['remarks'] : '--');
                }
                if($value['type'] == 8){
                    $value['content'] = $value['user_name']." 备注工单，内容：".$value['remarks'];
                }
                // 有通话记录
                if($value['call_log']){

                }
                $value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
                $value['type'] = $log_type;
            }
            return $logs;
        } catch (\Exception $e) {
            Log::write('指定工单操作日志记录异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 创建主动外呼工单
     * @param int $uid 操作员（话务员）number
     * @param int $u_type 操作员类型
     * @param int $client_id 服务对象ID
     * @param string $title 外呼标题
     * @return array ['code'=>0,'msg'=>'创建结果']
     */
    public function outboundCall($uid, $u_type, $client_id, $title)
    {
        Db::startTrans();
        try {
            $time = time();
            $work = Db::execute("INSERT INTO yc_work (`title`,`staff_number`,`client_id`,`type`,`state`,`create_time`) VALUES ('".$title."',".$uid.",".$client_id.",5,3,".$time.")");
            $workID = Db::getLastInsID();
            $log = Db::execute("INSERT INTO yc_work_log (`w_id`,`uid`,`u_type`,`create_time`) VALUES (".$workID.",".$uid.",".$u_type.",".$time.")");
            Db::commit();
            return ['code'=>0,'msg'=>'创建工单成功'];
        } catch (\Exception $e) {
            Db::rollback();
            Log::write('创建主动外呼工单异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 受理工单
     * @param int $work_id 工单id
     * @param int $uid 受理人id
     * @param int $u_type 受理人类型
     * @param array ['code'=>0,'msg'=>'受理结果']
     */
    public function acceptWork($work_id, $uid, $u_type)
    {
        // 工单详情
        $details = Db::query("SELECT `state`,`plan_id` FROM yc_work WHERE `id`=".$work_id);
        if(!$details || $details[0]['state'] != 2){
            return ['code'=>1,'msg'=>'受理失败'];
        }
        $time = time();
        Db::startTrans();
        try {
            $work = Db::execute("UPDATE yc_work SET `state`=3,`plan_state`=(CASE WHEN `plan_id`!=0 THEN 2 ELSE `plan_state` END) WHERE `id`=".$work_id);
            $log = Db::execute("INSERT INTO yc_work_log (`w_id`,`type`,`uid`,`u_type`,`create_time`) VALUES (".$work_id.",2,".$uid.",".$u_type.",".$time.")");
            Db::commit();
            return ['code'=>0,'msg'=>'受理成功'];
        } catch (\Exception $e) {
            Db::rollback();
            Log::write('受理工单异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }
    
    /**
     * 转交工单
     * @param int $work_id 工单ID
     * @param int $uid 操作人id
     * @param int $u_type 操作人类型
     * @param int $staff 转交的话务员ID
     * @return array ['code'=>0,'msg'=>'转交结果']
     */
    public function transferWork($work_id, $uid, $u_type, $staff)
    {
        Db::startTrans();
        try {
            $time = time();
            $work = Db::execute("UPDATE yc_work SET `staff_number`=".$staff.",`state`=2,`plan_state`=1 WHERE `state` IN (1,2,3) AND `id`=".$work_id);
            $log = Db::execute("INSERT INTO yc_work_log (`w_id`,`type`,`uid`,`u_type`,`staff_number`,`create_time`) VALUES (".$work_id.",3,".$uid.",".$u_type.",".$staff.",".$time.")");
            Db::commit();
            return ['code'=>0,'msg'=>'转交工单成功'];
        } catch (\Exception $e) {
            Db::rollback();
            Log::write('转交工单异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 关闭工单
     * @param int $work_id 工单ID
     * @param int $uid 操作人ID
     * @param int $u_type 操作人类型
     * @return array ['code'=>0,'msg'=>'关闭结果']
     */
    public function closeWork($work_id, $uid, $u_type)
    {
        Db::startTrans();
        try {
            $time = time();
            $work = Db::execute("UPDATE yc_work SET `state`=4,`plan_state`=1 WHERE `state` IN (1,2,3) AND `id`=".$work_id);
            $log = Db::execute("INSERT INTO yc_work_log (`w_id`,`type`,`uid`,`u_type`,`create_time`) VALUES (".$work_id.",4,".$uid.",".$u_type.",".$time.")");
            Db::commit();
            return ['code'=>0,'msg'=>'关闭订单成功'];
        } catch (\Exception $e) {
            Db::rollback();
            Log::write('关闭工单异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }
    
    /**
     * 重新打开工单
     * @param int $work_id 工单ID
     * @param int $uid 操作人ID
     * @param int $u_type 操作人类型
     * @return array ['code'=>0,'msg'=>'重新打开结果']
     */
    public function openWork($work_id, $uid, $u_type)
    {
        Db::startTrans();
        try {
            $time = time();
            $work = Db::execute("UPDATE yc_work SET `state`=3,`is_check`=2,`plan_state`=2 WHERE `state` IN (4,5) AND `id`=".$work_id);
            $log = Db::execute("INSERT INTO yc_work_log (`w_id`,`type`,`uid`,`u_type`,`create_time`) VALUES (".$work_id.",9,".$uid.",".$u_type.",".$time.")");
            Db::commit();
            return ['code'=>0,'msg'=>'重新打开工单成功'];
        } catch (\Exception $e) {
            Db::rollback();
            Log::write('重新打开工单异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 办结工单
     * @param int $work_id 工单ID
     * @param int $uid 操作员ID
     * @param int $u_type 操作员类型
     * @param int $call_result 通话结果
     * @param string $remarks 备注
     * @return array ['code'=>0,'msg'=>'办结结果']
     */
    public function finishWork($work_id, $uid, $u_type, $call_result, $remarks)
    {
        // 获取工单详情
        $details = Db::query("SELECT w.`id`,w.`plan_id`,p.`start_time`,p.`end_time` FROM yc_work AS w LEFT JOIN yc_work_plan AS p ON w.`plan_id`=p.`id` WHERE w.`id`=".$work_id);
        $time = time();
        $plan_state = 1;
        if($details[0]['plan_id']){
            $end_time = strtotime(date('Y-m-d 23:59:59',$details[0]['end_time']));
            $plan_state = ($time <= $end_time) ? 3 : 4 ;
        }
        Db::startTrans();
        try {
            $work = Db::execute("UPDATE yc_work SET `state`=5,`is_check`=1,`plan_state`=(CASE WHEN `plan_id`!=0 THEN ".$plan_state." ELSE `plan_state` END) WHERE `state`=3 AND `id`=".$work_id);
            $log = Db::execute("INSERT INTO yc_work_log (`w_id`,`type`,`uid`,`u_type`,`call_result`,`remarks`,`create_time`) VALUES (".$work_id.",5,".$uid.",".$u_type.",".$call_result.",'".$remarks."',".$time.")");
            Db::commit();
            return ['code'=>0,'msg'=>'办结工单成功'];
        } catch (\Exception $e) {
            Db::rollback();
            Log::write('办结工单异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 提交工单备注信息（未分配/未受理/受理中的工单可以备注）
     * @param int $wid 工单ID
     * @param int $uid 操作人员id
     * @param int $u_type 操作人员类型
     * @param string $remarks 备注信息
     * @return array ['code'=>0,'msg'=>'备注结果']
     */
    public function subWorkRemarks($wid, $uid, $u_type, $remarks)
    {
        try {
            $work_exist = Db::query("SELECT `id` FROM yc_work WHERE `state` IN (1,2,3) AND `id`=".$wid);
            if(!$work_exist){
                return ['code'=>1,'msg'=>'备注失败'];
            }
            $time = time();
            $result = Db::execute("INSERT INTO yc_work_log (`w_id`,`type`,`uid`,`u_type`,`remarks`,`create_time`) VALUES (".$wid.",8,".$uid.",".$u_type.",'".$remarks."',".$time.")");
            if(!$result){
                return ['code'=>2,'msg'=>'备注失败'];
            }
            return ['code'=>0,'msg'=>'备注成功','data'=>['user_name'=>$this->user_info['name'],'create_time'=>date('Y-m-d H:i:s',$time),'remarks'=>$remarks]];
        } catch (\Exception $e) {
            Log::write('备注工单异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 通过工单
     * @param int $work_id 工单ID
     * @param int $uid 质检用户ID
     * @param int $u_type 质检用户类型
     * @param int $score 质检分数
     * @return array ['code'=>0,'msg'=>'质检结果']
     */
    public function qualityWork($work_id, $uid, $u_type, $score)
    {
        Db::startTrans();
        try {
            $time = time();
            $work = Db::execute("UPDATE yc_work SET `is_check`=2 WHERE `state`=5 AND `is_check`=1 AND `id`=".$work_id);
            $log = Db::execute("INSERT INTO yc_work_log (`w_id`,`type`,`uid`,`u_type`,`quality_score`,`create_time`) VALUES (".$work_id.",7,".$uid.",".$u_type.",".$score.",".$time.")");
            Db::commit();
            return ['code'=>0,'msg'=>'质检工单成功'];
        } catch (\Exception $e) {
            Db::rollback();
            Log::write('通过工单异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 退回工单
     * @param int $work_id 工单ID
     * @param int $uid 操作员ID
     * @param int $u_type 操作员类型
     * @param string $remarks 退回原因
     * @return array ['code'=>0,'msg'=>'退回结果']
     */
    public function returnWork($work_id, $uid, $u_type, $remarks)
    {
        Db::startTrans();
        try {
            $time = time();
           $work = Db::execute("UPDATE yc_work SET `state`=3,`is_check`=2,`plan_state`=(CASE WHEN `plan_id`!=0 THEN 2 ELSE `plan_id` END) WHERE `state`=5 AND `id`=".$work_id);
            $log = Db::execute("INSERT INTO yc_work_log (`w_id`,`type`,`uid`,`u_type`,`remarks`,`create_time`) VALUES (".$work_id.",6,".$uid.",".$u_type.",'".$remarks."',".$time.")");
            Db::commit();
            return ['code'=>0,'msg'=>'退回工单成功'];
        } catch (\Exception $e) {
            Db::rollback();
            Log::write('退回工单异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }


//    /*
//    *工单列表顶部数据查询（暂时作废）
//    */
//    public function GetOrderTypeList(){
//        try {
//            //获取每日工单
//            $starttime=mktime(0,0,0,date('m'),date('d'),date('Y'));
//            $endtime=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
//            $where['create_time'] = ['between',[$starttime,$endtime]];
//            $where['state'] = ['in','1,2,3,4'];
//            $group = $this->field('count(id) as number,state')->where($where)->group('state')->select();
//            if ($group) {
//                $number = 0;
//                foreach ($group as $k=>&$v) {
//                    $data[$v['state']]=$v['number'];
//                    $number+=$v['number'];
//                }
//                foreach ($data as $k=>&$v) {
//                    $arr[$k]['ratio'] = sprintf("%.2f",($v/$number)*100);
//                    $arr[$k]['number']=$v;
//                }
//                $arr['count'] = $number;
//                return $arr;
//            }
//        } catch (Exception $e) {
//            Log::write('获取全部工单列表异常：'.$e->getMessage(),'error');
//            return ['code'=>1,'msg'=>'服务器异常'];
//        }
//    }

    /*
    *未读消息统计
    */
    public function GetWarningAll(){
        try {
            $where['is_read'] = ['eq','1'];
            $where['type'] = ['in','1,2,3,7,8,9'];
            $result = db::name('work')->field('group_concat(id) as id,type')->where($where)->group('type')->select();
            $number = 0;
            foreach ($result as $key=>&$value) {
                $value['number'] = count(explode(',',$value['id']));
                $number+=$value['number'];
            }
            $result['count'] = $number;
            return $result;
        } catch (Exception $e) {
            Log::write('未读消息统计异常'.$e->getMessage(),'error');
            return ['code'=>1,'messgae'=>'服务器异常'];
        }
    }

    /**
     * 添加紧急呼叫工单
     * @param $param
     * @param $cid
     * @return array
     */
    public function addCallOrder($param,$cid)
    {
        try {
            $oid = db::name('work')->insertGetId($param);
            if ($param['type'] == 2){
                $msg = '系统收到报警信息，自动创建工单';
            }else{
                $msg = '接到电话呼入，系统创建工单';
            }
            $log = [
                'sid'=>$param['uid'],
                'order_id'=>$oid,
                'callid'=>$cid,
                'remarks'=>$msg,
                'addtime'=>time(),
            ];
            db::name('work_log')->insert($log);
        } catch (Exception $e) {
            Log::write('添加紧急呼叫工单异常：'.$e->getMessage(),'error');
            return ['code'=>1,'msg'=>'服务器异常'];
        }
    }

    /*
    *修改消息状态
    *return array
    */
    public function SaveMessStatus(){
        try {
            $data = input();
            $where['id'] = ['in',$data['id']];
            $result = db::name('work')->where($where)->update(['is_read'=>'2']);
            if ($result!==false) {
                return ['code'=>0,'msg'=>'已读成功'];
            }
            return ['code'=>1,'msg'=>'已读失败'];
        } catch (Exception $e) {
            Log::write('修改未读消息异常'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /*
    *报警消息table
    */
    public function GetWarningList(){
        try {
            $data = input();
            switch ($data['type']) {
                case 'sos':
                    $where['type'] = ['eq',1];
                    break;
                case 'heart':
                    $where['type'] = ['eq',3];
                    break;
                case 'fence':
                    $where['type'] = ['eq',2];
                    break;
            }
            //搜索
            $item = [];
            //分页参数
            $search['type']=$data['type'];

            //添加时间搜索
            if ( isset($data['start_create']) && $data['start_create']!=='' && isset($data['end_create']) && $data['end_create']!=='') {
                $search['start_create']=$data['start_create'];
                $search['end_create']=$data['end_create'];
                $end = $data['end_create'].''.'23:59:59';
                $where['create_time'] = ['between',[strtotime($data['start_create']),strtotime($end)]];
                $item[] = [
                    'k'=>'创建时间',
                    'v'=>$data['start_create'].'至'.$data['end_create'],
                ];
            }else if( isset($data['start_create']) && $data['start_create']!==''){
                $search['start_create']=$data['start_create'];
                $start = strtotime($data['start_create']);
                $end = strtotime($data['start_create'].'23:59:59');
                $where['create_time'] = ['between',[$start,$end]];
                $item[] = [
                    'k'=>'创建时间',
                    'v'=>$data['start_create'],
                ];
            }else if( isset($data['end_create']) && $data['end_create']!==''){
                $search['end_create']=$data['end_create'];
                $start = strtotime($data['end_create']);
                $end = strtotime($data['end_create'].'23:59:59');
                $where['create_time'] = ['between',[$start,$end]];
                $item[] = [
                    'k'=>'创建时间',
                    'v'=>$data['end_create'],
                ];
            }
            //姓名搜索
            if (isset($data['name']) && $data['name']!=='') {
                $search['name']=$data['name'];
                $condition['name']= ['eq',$data['name']];
                $userinfo = db::name('client')->field('group_concat(id) as id')->where($condition)->find();
                $where['client_id'] = ['in',$userinfo['id']]; 
                $item[] = [
                    'k'=>'姓名',
                    'v'=>$data['name'],
                ];
            }
            //身份证搜索
            if (isset($data['cardid']) && $data['cardid']!=='') {
                $search['cardid']=$data['cardid'];
                $card['id_number']= ['eq',$data['cardid']];
                $uid = db::name('client')->where($card)->value('id');
                $where['client_id'] = ['eq',$uid]; 
                $item[] = [
                    'k'=>'身份证号码',
                    'v'=>$data['cardid'],
                ];
            }
            //imei号码搜索
            if (isset($data['imei']) && $data['imei']!=='') {
                $search['imei']=$data['imei'];
                $imei['imei']= ['eq',$data['imei']];
                $uid = db::name('device')->where($imei)->value('uid');
                $where['client_id'] = ['eq',$uid]; 
                $item[] = [
                    'k'=>'imei号码',
                    'v'=>$data['imei'],
                ];
            }
            $list = db::name('work')
            ->field('id,create_time,lng,lat,address,client_id,type,heart')
            ->where($where)
            ->order('id desc')
            ->paginate(30,false,['query'=>$search]);
            $page = $list->render();
            $show = $list->toArray();
            foreach ($show['data'] as $key =>&$value) {
                //查询发起人
                $value['name'] = db::name('client')->where('id',$value['client_id'])->value('name');
                if ($value['type']==3) {
                    $value['content'] = '用户'.$value['name'].'在'.date('Y-m-d H:i:s',$value['create_time']).'触发了心率报警，心率值：'.$value['heart'];
                }
            }
            $arr = [
                'page'=>$page,
                'show'=>$show,
                'work_type'=>$data['type'],
                'search'=>$item,
                'query'=>$search
            ];
            return $arr;
        } catch (Exception $e) {
            Log::write('报警消息查询异常'.$e->getMessage(),'error');
            return false;
        }
    }

    /*
    *报警地图
    */
    public function GetWarningMap(){
        try {
            //工单id
            $id = input('id');
            $where['a.id'] = ['eq',$id];
            $info = db::name('work a')->field('a.lng,a.lat,a.address,a.create_time,a.location_type,b.name,b.head')
            ->join('client b','a.client_id=b.id')->where($where)->find();
            $info['time'] = date('Y-m-d H:i:s',$info['create_time']);
            $info['location_type'] = $this->location_type[$info['location_type']];
            return json_encode($info);
        } catch (Exception $e) {
            Log::write('报警消息查询异常'.$e->getMessage(),'error');
            return false;
        }
    }

    /*
    *定位方式
    */
    protected $location_type = [
        '1'=>'未有任何定位',
        '2'=>'GPS',
        '3'=>'基站',
        '4'=>'WIFI',
        '5'=>'混合定位',
    ];

    /*
    *时时数据
    */
    public function GetOftenInfo(){
        try {
            $where['type'] = ['in','1,2,3,7,8,9'];
            $data = db::name('work a')->field('a.create_time,a.address,a.type,b.name,b.head')
            ->join('client b','a.client_id=b.id','left')
            ->order('a.id desc')
            ->limit(7)
            ->where($where)
            ->select();
            foreach ($data as $key=>&$value) {
                $value['time'] = F_time_trans($value['create_time']);
                $value['addtime'] = date('H:i:s',$value['create_time']);
                $address = explode(';',$value['address']);
                switch ($value['type']) {
                    case 1:
                        $value['content'] = '用户:'.$value['name'].'触发了'.$this->work_type[$value['type']].',靠近'.$address[1];
                        break;
                    case 2:
                        $value['content'] = '用户:'.$value['name'].'触发了'.$this->work_type[$value['type']].',靠近'.$address[1];
                        break;
                    case 3:
                        $value['content'] = '用户:'.$value['name'].'触发了心率报警';
                        break;
                    case 7:
                        $value['content'] = '用户:'.$value['name'].' 居家设备烟感触发了报警';
                        break;
                    case 8:
                        $value['content'] = '用户:'.$value['name'].' 居家设备燃气触发了报警';
                        break;
                    case 9:
                        $value['content'] = '用户:'.$value['name'].' 居家设备红外触发了报警';
                        break;
                }
            }
            return $data;
        } catch (Exception $e) {
            Log::write('大屏信息时时数据查询异常'.$e->getMessage(),'error');
            return false;
        }
    }


    /**
     * 主动关怀统计
     * @param $param
     * @return bool|false|\PDOStatement|string|\think\Collection
     */
    public function dataStaff($param)
    {

        try {
            //时间
            $date = date('Y-m-d');
            $Start = strtotime(date('Y-m-d 00:00:00', time()));
            $End = strtotime(date('Y-m-d 23:59:59', time()));
            if (!empty($param['start_create']) && !empty($param['end_create'])) {
                $Start = strtotime(date('Y-m-d 00:00:00', strtotime($param['start_create'])));
                $End = strtotime(date('Y-m-d 23:59:59', strtotime($param['end_create'])));
                $date = date('Y-m-d', strtotime($param['start_create'])).'至'.date('Y-m-d', strtotime($param['end_create']));
            }
            $where['l.create_time'] = ['between',[$Start,$End]];
            $where['l.call_result'] =  array('neq',0);
            $where['w.type'] = 4;
            $data = Db::name('work_log')
                ->alias('l')
                ->field('s.display_name, count(l.call_result) as total,
                    sum(case when l.call_result=1 then 1 else 0 end) as one,
                    sum(case when l.call_result=2 then 1 else 0 end) as two, 
                    sum(case when l.call_result=3 then 1 else 0 end) as three, 
                    sum(case when l.call_result=4 then 1 else 0 end) as four')
                ->join('work w','l.w_id=w.id','LEFT')
                ->join('staff_user s','w.staff_number=s.number','LEFT')
                ->group('s.number')
                ->where($where)
                ->select();
            foreach ($data as $key=>$val){
                $data[$key]['rest'] = $val['total'] - array_sum([$val['one'],$val['two'],$val['three'],$val['four']]);
                $data[$key]['date'] = $date;
            }
            if (!empty($param['excel'])){
                $excel = [];
                foreach ($data as $key=>$val){
                    $excel[$key] = array(
                        'date'=>$val['date'],
                        'display_name'=>$val['display_name'],
                        'total'=>$val['total'],
                        'one'=>$val['one'],
                        'two'=>$val['two'],
                        'three'=>$val['three'],
                        'four'=>$val['four'],
                        'rest'=>$val['rest']
                    );
                }
                $key = ['date','display_name','total','one','two','three','four','rest'];
                $this->exportTemplet($excel,$key);
                exit();
            }
            return $data;
        } catch (Exception $e) {
            Log::write('主动关怀统计异常'.$e->getMessage(),'error');
            return false;
        }

    }

    /**
     * 导出数据
     * @param $data
     * @param $key
     * @return array
     */
    public function exportTemplet($data,$key)
    {
        try {
            // excel的header头
            $header = ['日期','客服姓名', '外呼关怀数量', '正常接通', '未接通','挂断','无声或听不清','其他'];
            // 文件名
            $fileName = '主动关怀统计';
            F_export_excel($data, $header, $key, $fileName);
        } catch (\Exception $e) {
            Log::write('导出数据异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

}