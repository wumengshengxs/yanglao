<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/11
 * Time: 16:43
 * 服务对象的健康档案
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;

class Healthy extends Common {
    /**
     * 服务对象健康管理列表
     */
    public function healthyRecordsList($where='', $query=[], $limit=20)
    {
        try {
            $data = Db::name('client')->alias('c')
                ->field('c.id as clientId,c.name as clientName,c.age,g.name as groupName,t.name as tagName')
                ->join(['v_client_group'=>'g'],'c.id=g.cid','LEFT')
                ->join(['v_client_tag'=>'t'],'c.id=t.cid','LEFT')
                ->where($where)
                ->order('c.id desc')
                ->group('c.id')
                ->paginate($limit, false, ['query'=>$query]);
            $page = $data->render();
            $healthy = $data->toArray();
            foreach($healthy['data'] as &$value){
                // 是否有既往病史
                $medicine_history = Db::query('SELECT `id` FROM yc_client_medical_history WHERE `cid`='.$value['clientId']);
                $value['medicine_history'] = $medicine_history ? '有' : '无';
                $allergy = Db::query('SELECT `cid` FROM yc_client_allergy WHERE `cid`='.$value['clientId']);
                $value['allergy'] = $allergy ? '有' : '无' ;
                $case = Db::query('SELECT `id` FROM yc_client_case WHERE `cid`='.$value['clientId']);
                $value['case'] = $case ? '有' : '无' ;
            }
            return ['healthy'=>$healthy,'page'=>$page];
        } catch (\Exception $e) {
            Log::write('健康管理列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 睡眠情况
     */
    protected $sleep = [
        '1'=>'睡眠充足（5-8小时）',
        '2'=>'睡眠不足（不足4小时）',
    ];

    /**
     * 吸烟情况
     */
    protected $smoke = [
        '1'=>'是',
        '2'=>'否',
    ];

    protected $drink = [
        '1'=>'是',
        '2'=>'否',
    ];

    /**
     * 锻炼时长
     */
    protected $exercise_duration = [
        '1'=>'30分钟以内/次',
        '2'=>'30-60分钟/次',
        '3'=>'60分钟以上/次'
    ];

    /**
     * 锻炼方式
     */
    protected $exercise_type = [
        '1'=>'跑步',
        '2'=>'健身操',
        '3'=>'球类',
        '4'=>'游泳',
        '5'=>'其它'
    ];

    /**
     * 医疗费支付方式
     */
    protected $medical_payment = [
        '1'=>'自费',
        '2'=>'半自费',
        '3'=>'劳保',
        '4'=>'公费',
        '5'=>'社保'
    ];

    /**
     * 社交活动
     */
    protected $social_activity = [
        '1'=>'公园',
        '2'=>'老年活动站',
        '3'=>'老年大学',
        '4'=>'其它'
    ];

    /**
     * 服务对象健康档案的基础信息
     * @param int $uid 服务对象id
     * @return array $info 基础健康档案信息
     */
    public function healthyBaseInfo($uid)
    {
        try {
            $sql = "SELECT `height`,`weight`,`vision`,`hearing`,`sleep`,`smoke`,`smoke_frequency`,`drink`,`exercise_frequency`,`exercise_duration`,`exercise_type`,`healthy_products`,`medical_payment`,`social_activity`,`remarks` FROM yc_client_healthy WHERE `cid`=".$uid;
            $info = Db::query($sql);
            foreach($info as &$value){
                $value['sleep_text'] = $this->sleep[$value['sleep']];
                $value['smoke_text'] = $this->smoke[$value['smoke']];
                $value['smoke_frequency'] = ($value['smoke'] == 1) ? $value['smoke_frequency'] : '' ;
                $value['drink_text'] = $this->drink[$value['drink']];
                $value['exercise_duration_text'] = $this->exercise_duration[$value['exercise_duration']];
                $value['exercise_type'] = json_decode($value['exercise_type'],true);
                if(is_array($value['exercise_type'])){
                    foreach($value['exercise_type'] as $type){
                        $value['exercise_type_text'] .= $this->exercise_type[$type].'&nbsp;';
                    }
                }
                $value['medical_payment_text'] = $this->medical_payment[$value['medical_payment']];
                $value['social_activity'] = json_decode($value['social_activity'],true);
                if(is_array($value['social_activity'])){
                    foreach($value['social_activity'] as $social){
                        $value['social_activity_text'] .= $this->social_activity[$social].'&nbsp;';
                    }
                }
            }
            return $info ? $info[0] : false;
        } catch (\Exception $e) {
            Log::write('服务对象健康档案的基础信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }
    
    /**
     * 更新服务对象基础档案信息
     * @param int $uid 服务对象id
     * @param int $height 身高
     * @param int $weight 体重
     * @param string $hearing 听力情况
     * @param string $vision 视力情况
     * @param int $sleep 睡眠情况 1：充足；2：不足
     * @param int $smoke 是否吸烟 1：是；2：否
     * @param int $smoke_frequency 平均每天吸烟量
     * @param int $drink 是否饮酒1：是；2：否
     * @param int $exercise_frequency 平均每周锻炼次数
     * @param int $exercise_duration 每次的锻炼时长1：30分钟以内；2:30-60分钟；3:60分钟以上
     * @param string $exercise_type 锻炼方式，json格式
     * @param string $healthy_products 服用的保健品说明
     * @param int $medical_payment 医疗费支付方式
     * @param string $social_activity 社交活动，json格式
     * @param string $remarks 备注
     * @return array ['code'=>0,'msg'=>'更新结果']
     */
    public function updateHealthyBase($uid, $height, $weight, $hearing, $vision, $sleep, $smoke, $smoke_frequency, $drink, $exercise_frequency, $exercise_duration, $exercise_type, $healthy_products, $medical_payment, $social_activity, $remarks)
    {
        try {
            // 验证服务对象是否存在
            $is_exit = model('Client')->userInfo($uid);
            if(!$is_exit){
                return ['code'=>1,'msg'=>'服务对象不存在'];
            }
            // 是否存在信息
            $sql_exit = "SELECT `cid` FROM yc_client_healthy WHERE `cid`=".$uid;
            $exit = Db::query($sql_exit);
            $time = time();
            if($exit){      // 更新
                $sql = "UPDATE yc_client_healthy SET `height`=".$height.",`weight`=".$weight.",`vision`='". $vision."',`hearing`='".$hearing."',`sleep`=".$sleep.",`smoke`=".$smoke.",`smoke_frequency`=".$smoke_frequency.",`drink`=".$drink.",`exercise_frequency`=".$exercise_frequency.",`exercise_duration`=".$exercise_duration.",`exercise_type`='".$exercise_type."',`healthy_products`='".$healthy_products."',`medical_payment`=".$medical_payment.",`social_activity`='".$social_activity."',`remarks`='".$remarks."',`modify_time`=".$time." WHERE `cid`=".$uid;
            } else {        // 新增
                $sql = "INSERT INTO yc_client_healthy (`cid`,`height`,`weight`,`vision`,`hearing`,`sleep`,`smoke`,`smoke_frequency`,`drink`,`exercise_frequency`,`exercise_duration`,`exercise_type`,`healthy_products`,`medical_payment`,`social_activity`,`remarks`,`create_time`) VALUES (".$uid.",".$height.",".$weight.",'".$vision."','".$hearing."',".$sleep.",".$smoke.",".$smoke_frequency.",".$drink.",".$exercise_frequency.",".$exercise_duration.",'".$exercise_type."','".$healthy_products."',".$medical_payment.",'".$social_activity."','".$remarks."',".$time.")";
            }
            $result = Db::execute($sql);
            if(($exit && $result !== false) || $result){
                return ['code'=>0,'msg'=>'保存基础健康信息成功'];
            }
            return ['code'=>2,'msg'=>'保存健康基础信息失败'];
        } catch (\Exception $e) {
            Log::write('更新健康基础信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /*
    *时时健康数据
    */
    public function GetOftenHealth(){
        try {
            $info = input();
            //添加时间 
            $start = mktime(0,0,0,date('m'),date('d'),date('Y'));
            $end = mktime(23,59,59,date('m'),date('d'),date('Y'));
            $where['addtime'] = ['between',[$start,$end]];

            //用户名
            if (isset($info['name']) && $info['name']!=='') {
                $tmp['name'] = ['like','%'.$info['name'].'%'];
                $query['name']=$info['name'];
                $search[]=[
                    'item'=>'姓名',
                    'v'=>$info['name']
                ];
                $arr_uid = db::name('client')->where($tmp)->column('id');
                $uid = implode(',',$arr_uid);
                $where['uid'] = ['in',$uid];
            }

            //身份证
            if (isset($info['id_number']) && $info['id_number']!=='') {
                $query['id_number']=$info['id_number'];
                $tmpA['id_number'] = ['eq',$info['id_number']];
                $search[]=['item'=>'身份证','v'=>$info['id_number']];
                $arr_uid = db::name('client')->where($tmpA)->value('id');
                $where['uid'] = ['eq',$uid];
            }   

            //开始时间 与 结束时间
            if ( isset($info['start_create']) && $info['start_create']!=='' && isset($info['end_create']) && $info['end_create']!=='') {
                $query['start_create']=$info['start_create'];
                $query['end_create']=$info['end_create'];
                $start = strtotime($info['start_create']);
                $end = strtotime($info['end_create'].''.'23:59:59');
                $search[]=['item'=>'创建时间','v'=>$info['start_create'].'至'.$info['end_create']];
                $where['addtime'] = ['between',[$start,$end]];

            }else if( isset($info['start_create']) && $info['start_create']!==''){
                $query['start_create']=$info['start_create'];
                $search[]=['item'=>'创建时间','v'=>$info['start_create'].'至 --'];
                $start = strtotime($info['start_create']);
                $where['addtime'] = ['egt',$start];

            }else if( isset($info['end_create']) && $info['end_create']!==''){
                $query['end_create']=$info['end_create'];
                $end = strtotime($info['end_create'].''.'23:59:59');
                $search[]=['item'=>'创建时间','v'=>'--至'.$info['end_create']];
                $where['addtime'] = ['elt',$end];
            }
            $data = db::name('health')->where($where)->order('id desc')->paginate(30,false,['query'=>$query]);
            $page = $data->render();
            $list = $data->toArray();
            foreach ($list['data'] as $key=>&$value) {
                $c_info = db::name('client')->field('name,age')->where('id',$value['uid'])->find();
                $value['name'] = $c_info['name'];
                $value['age'] = $c_info['age'];
            }
            $arr = [
                'page'=>$page,
                'list'=>$list,
                'query'=>$query,
                'search'=>$search,
            ];
            return $arr;
        } catch (Exception $e) {
             Log::write('时时健康数据查询异常：'.$e->getMessage(),'error');
            return false;
        }
    }

    //健康数据类型
    protected $h_type = [
        1=>'血压',
        2=>'心率', 
        3=>'久坐', 
        4=>'计步', 
        5=>'血糖',
        6=>'睡眠',
    ];
}