<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/21
 * Time: 10:43
 * 计划任务
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;

class WorkPlan extends Common {
    /**
     * 计划任务组状态
     */
    public $plan_state = [
        1=>'启用',
        2=>'草稿'
    ];

    /**
     * 计划任务列表
     * @param array|string $where 搜索条件
     * @param array $query 分页条件
     * @param int $limit 分页条数
     * @return array ['plan'=>'计划任务','page'=>'分页信息']
     */
    public function planGroupList($where=[], $query=[], $limit=20)
    {
        try {
            $data = Db::name('work_plan')
                ->field('id,name,start_time,end_time,state,quantity,create_time')
                ->where($where)
                ->order('id desc')
                ->paginate($limit, false, ['query'=>$query]);
            $page = $data->render();
            $plan = $data->toArray();
            return ['plan'=>$plan,'page'=>$page];
        } catch (\Exception $e) {
            Log::write('获取计划任务列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 查看计划任务详情
     * @param int $id 计划任务id
     * @return array $details
     */
    public function planGroupDetails($id)
    {
        try {
            $details = Db::query("SELECT `id`,`name`,`start_time`,`end_time`,`staff`,`uids`,`quantity`,`allot`,`state`,`create_time` FROM yc_work_plan WHERE `id`=".$id)[0];
            $details['start_time'] = date('Y-m-d',$details['start_time']);
            $details['end_time'] = date('Y-m-d',$details['end_time']);
            $details['create_time'] = date('Y-m-d',$details['create_time']);
            $details['staff'] = json_decode($details['staff'],true);
            foreach($details['staff'] as $key=>&$value){
                // 获取对应的话务员信息
                $staffUser = Db::query("SELECT `display_name`,`number`,`work_number` FROM yc_staff_user WHERE `number`=".$value['number'])[0];
                $value['display_name'] = $staffUser['display_name'];
                $value['number'] = $staffUser['number'];
                $value['work_number'] = $staffUser['work_number'];
            }
            return $details;
        } catch (\Exception $e) {
            Log::write('计划任务详情异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }
    
    /**
     * 提交计划组信息
     * @param int $id 计划组id
     * @param string $name 计划组名称
     * @param string $start_time 计划开始时间戳
     * @param string $end_time 计划截止时间戳
     * @param string $client 服务对象id，逗号隔开
     * @param array $staff 话务员id，逗号隔开
     * @param int $allot 分配策略，默认1（随机平均分配）
     * @param int $state 计划组状态 1：启用；2：草稿
     * @return array ['code'=>0,'msg'=>'提交结果']
     */
    public function submitWorkPlan($id, $name, $start_time, $end_time, $client, $staff, $allot, $state)
    {
        try {
            // 计划组名称文唯一
            $where = " `name`='".$name."'";
            $where .= $id ? " AND `id`!=".$id : "" ;
            $uniq = Db::query("SELECT `id`,`state` FROM yc_work_plan WHERE ".$where);
            if($uniq){
                return ['code'=>1,'msg'=>'计划组名称重复'];
            }
            if($id){
                return $this->updateWorkPlan($id, $name, $start_time, $end_time, $state);
            }
            return $this->createWorkPlan($name, $start_time, $end_time, $client, $staff, $allot, $state);
        } catch (\Exception $e) {
            Log::write('提交计划组信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 新建计划组信息
     * @param string $name 计划组名称
     * @param string $start_time 计划开始时间戳
     * @param string $end_time 计划截止时间戳
     * @param string $client 服务对象id，逗号隔开
     * @param array $staff 话务员id，逗号隔开
     * @param int $allot 分配策略，默认1（随机平均分配）
     * @param int $state 计划组状态 1：启用；2：草稿
     * @return array ['code'=>0,'msg'=>'新建结果']
     */
    protected function createWorkPlan($name, $start_time, $end_time, $client, $staff, $allot, $state)
    {
        Db::startTrans();
        try {
            // 随机给话务员平均分配服务对象
            $client_arr = explode(',',$client);                         // 服务对象个数
            $client_num = count($client_arr);                           // 服务对象个数
            $staff_num = count($staff);                                 // 话务员个数
            $average = round($client_num/$staff_num);                   // 四舍五入
            $percent = sprintf("%.2f",($average/($client_num)))*100;    // $staff_num-1个话务员的服务对象占比
            $last_percent = 100-(($staff_num-1)*$percent);              // 最后一个话务员的服务对象占比
            $last_number = $client_num-(($staff_num-1)*$average);           // 最后一个话务员的服务对象数量
            $staff_arr = [];
            $time = time();
            foreach($staff as $key=>$value){
                if($key+1 == $staff_num){
                    $staff_arr[] = ['number'=>$value,'quantity'=>$last_number,'percent'=>$last_percent];
                    break;
                }
                $staff_arr[] = ['number'=>$value,'quantity'=>$average,'percent'=>$percent];
            }
            $staff_json = json_encode($staff_arr);
            $insert_plan = Db::execute("INSERT INTO yc_work_plan (`name`,`start_time`,`end_time`,`staff`,`uids`,`quantity`,`allot`,`state`,`create_time`) VALUES ('".$name."',".$start_time.",".$end_time.",'".$staff_json."','".$client."',".$client_num.",".$allot.",".$state.",".$time.")");
            $planLastInsID = Db::getLastInsID();
            if($state == 1){
                $tmp_arr = [];
                foreach($staff_arr as $key=>$value){
                    $start = $key ? $staff_arr[$key-1]['quantity'] : 0 ;
                    $tmp_arr[$value['number']] = array_slice($client_arr,$start, $value['quantity']);
                }
                // 启用状态下插入工单记录及操作日志
                foreach($tmp_arr as $key=>$value){
                    foreach($value as $k=>$v){
                        $insert_work = Db::execute("INSERT INTO yc_work (`staff_number`,`client_id`,`plan_id`,`type`,`state`,`create_time`) VALUES (".$key.",".$v.",".$planLastInsID.",4,2,".$time.")");
                        $workLastInsID = Db::getLastInsID();
                        $insert_log = Db::execute("INSERT INTO yc_work_log (`w_id`,`uid`,`create_time`) VALUES (".$workLastInsID.",".$this->user_info['id'].",".$time.")");
                    }
                }
            }

            Db::commit();
            return ['code'=>0,'msg'=>'新建计划任务成功'];
        } catch (\Exception $e) {
            Db::rollback();
            Log::write('新建计划任务异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }
    
    /**
     * 更新计划任务信息
     * @param string $name 计划组名称
     * @param string $start_time 计划开始时间戳
     * @param string $end_time 计划截止时间戳
     * @param int $state 计划组状态 1：启用；2：草稿
     * @return array ['code'=>0,'msg'=>'新建结果']
     */
    protected function updateWorkPlan($id, $name, $start_time, $end_time, $state)
    {
        Db::startTrans();
        try {
            $time = time();
            $set = $state ? ",`state`=".$state : "" ;
            $update_plan = Db::execute("UPDATE yc_work_plan SET `name`='".$name."',`start_time`=".$start_time.",`end_time`=".$end_time.$set." WHERE `id`=".$id);
            if($state == 1){
                // 启用状态
                $details = Db::query("SELECT `staff`,`uids` FROM yc_work_plan WHERE `id`=".$id)[0];
                $staff_arr = json_decode($details['staff'],true);
                $client_arr = explode(',',$details['uids']);
                $tmp_arr = [];
                foreach($staff_arr as $key=>$value){
                    $start = $key ? $staff_arr[$key-1]['quantity'] : 0 ;
                    $tmp_arr[$value['number']] = array_slice($client_arr,$start, $value['quantity']);
                }
                // 启用状态下插入工单记录及操作日志
                foreach($tmp_arr as $key=>$value){
                    foreach($value as $k=>$v){
                        $insert_work = Db::execute("INSERT INTO yc_work (`staff_number`,`client_id`,`plan_id`,`type`,`state`,`create_time`) VALUES (".$key.",".$v.",".$id.",4,2,".$time.")");
                        $workLastInsID = Db::getLastInsID();
                        $insert_log = Db::execute("INSERT INTO yc_work_log (`w_id`,`uid`,`create_time`) VALUES (".$workLastInsID.",".$this->user_info['id'].",".$time.")");
                    }
                }
            }
            Db::commit();
            return ['code'=>0,'msg'=>'编辑计划任务成功'];
        } catch (\Exception $e) {
            Db::rollback();
            Log::write('更新计划任务异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 启用计划任务
     * @param int $id 计划任务id
     * @return array ['code'=>0,'msg'=>'启用结果']
     */
    public function enablePlanGroup($id)
    {
        Db::startTrans();
        try {
            $details = Db::query("SELECT `state`,`staff`,`uids` FROM yc_work_plan WHERE `id`=".$id)[0];
            if($details['state'] != 2){
                return ['code'=>1,'msg'=>'启用失败'];
            }
            $update_plan = Db::execute("UPDATE yc_work_plan SET `state`=1 WHERE `id`=".$id);
            $staff_arr = json_decode($details['staff'],true);
            $client_arr = explode(',',$details['uids']);
            $tmp_arr = [];
            $time = time();
            foreach($staff_arr as $key=>$value){
                $start = $key ? $staff_arr[$key-1]['quantity'] : 0 ;
                $tmp_arr[$value['number']] = array_slice($client_arr,$start, $value['quantity']);
            }
            // 启用状态下插入工单记录及操作日志
            foreach($tmp_arr as $key=>$value){
                foreach($value as $k=>$v){
                    $insert_work = Db::execute("INSERT INTO yc_work (`staff_number`,`client_id`,`plan_id`,`type`,`state`,`create_time`) VALUES (".$key.",".$v.",".$id.",4,2,".$time.")");
                    $workLastInsID = Db::getLastInsID();
                    $insert_log = Db::execute("INSERT INTO yc_work_log (`w_id`,`uid`,`create_time`) VALUES (".$workLastInsID.",".$this->user_info['id'].",".$time.")");
                }
            }
            Db::commit();
            return ['code'=>0,'msg'=>'启用成功'];
        } catch (\Exception $e) {
            Db::rollback();
            Log::write('启用计划任务异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 删除计划任务
     * @param int $id 计划任务id
     * @return array ['code'=>0,'msg'=>'删除结果']
     */
    public function delPlanGroup($id)
    {
        try {
            $details = Db::query("SELECT `state` FROM yc_work_plan WHERE `id`=".$id)[0];
            if($details['state'] != 2){
                return ['code'=>1,'msg'=>'已启用的计划任务不能删除'];
            }
            $delete = Db::execute("DELETE FROM yc_work_plan WHERE `id`=".$id);
            if(!$delete){
                return ['code'=>2,'msg'=>'删除失败'];
            }
            return ['code'=>0,'msg'=>'删除成功'];
        } catch (\Exception $e) {
            Log::write('删除计划任务异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 延期计划组
     * @param int $id 计划组id
     * @param string $delay_time 延期时间时间戳
     * @return array ['code'=>0,'msg'=>'延期结果']
     */
    public function delayPlanGroup($id, $delay_time)
    {
        try {
            $details = Db::query("SELECT `end_time` FROM yc_work_plan WHERE `id`=".$id)[0];
            if($details['end_time'] >= $delay_time){
                return ['code'=>1,'msg'=>'延期日期应大于当前截止日期'];
            }
            $delay = Db::execute("UPDATE yc_work_plan SET `end_time`=".$delay_time." WHERE `id`=".$id);
            if(!$delay){
                return ['code'=>2,'msg'=>'延期失败'];
            }
            return ['code'=>0,'msg'=>'延期成功'];
        } catch (\Exception $e) {
            Log::write('延期计划组异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

//    /**
//     * 获取计划任务生成计划工单的状态
//     * @param string $start_time 开始时间戳
//     * @param string $end_time 结束时间戳
//     * @return int $state 状态类型
//     */
//    protected function planState($start_time, $end_time)
//    {
//        try {
//            $time = time();
//            switch ($time) {
//                case ($time<$start_time):
//                    return 1;
//                case ($time >= $start_time && $time <= $end_time):
//                    return 2;
//                case ($time > $end_time):
//                    return 3;
//            }
//        } catch (\Exception $e) {
//            Log::write('判断计划工单状态异常：'.$e->getMessage(),'error');
//            return ['code'=>-1,'msg'=>'服务器异常'];
//        }
//    }
    
}