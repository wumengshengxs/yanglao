<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/4
 * Time: 12:58
 * 服务对象管理
 */
namespace app\index\controller;

use app\index\controller\Common;
use think\Request;
use think\Db;

class Client extends Common {
    /**
     * 服务对象列表
     */
    public function index()
    {
        $request = Request::instance();
        $param = [
            'name'=>addslashes($request->get('name')),                     // 姓名
            'mobile'=>addslashes($request->get('mobile')),                 // 手机号
            'id_number'=>addslashes($request->get('id_number')),           // 身份证号
            'start_age'=>addslashes($request->get('start_age')),           // 开始年龄
            'end_age'=>addslashes($request->get('end_age')),               // 截止年龄
            'start_create'=>addslashes($request->get('start_create')),     // 开始创建时间
            'end_create'=>addslashes($request->get('end_create')),         // 截止创建时间
            'start_birthday'=>addslashes($request->get('start_birthday')), // 开始出生日期
            'end_birthday'=>addslashes($request->get('end_birthday')),     // 截止出生日期
            'sex'=>addslashes($request->get('sex')),                       // 性别
            'group'=>$request->get()['group'],                             // 分组
            'tag'=>$request->get()['tag']                                  // 标签
        ];
        $where = [];
        $item_value = [];
        if($param['name']){
            array_push($where,"c.name like '%".$param['name']."%'");
            $item_value[] = ['name'=>'name','item'=>'姓名','value'=>$param['name']];
        }
        if($param['mobile']){
            array_push($where,"c.mobile like '%".$param['mobile']."%'");
            $item_value[] = ['name'=>'mobile','item'=>'手机号','value'=>$param['mobile']];
        }
        if($param['id_number']){
            array_push($where,"c.id_number like '%".$param['id_number']."%'");
            $item_value[] = ['name'=>'id_number','item'=>'身份证','value'=>$param['id_number']];
        }
        if($param['start_age'] && $param['end_age']){
            array_push($where,"c.age between ".$param['start_age']." and ".$param['end_age']);
            $item_value[] = ['name'=>'age','item'=>'年龄','value'=>$param['name'].'至'.$param['end_age']];
        }
        if($param['start_age'] && !$param['end_age']){
            array_push($where,"c.age >= ".$param['start_age']);
            $item_value[] = ['name'=>'start_age','item'=>'年龄','value'=>$param['start_age'].'至-'];
        }
        if(!$param['start_age'] && $param['end_age']){
            array_push($where,"c.age <= ".$param['end_age']);
            $item_value[] = ['name'=>'end_age','item'=>'年龄','value'=>'-至'.$param['end_age']];
        }
        if($param['start_create'] && $param['end_create']){
            array_push($where,"c.create_time between ".strtotime($param['start_create'])." and ".strtotime($param['end_create']));
            $item_value[] = ['name'=>'create','item'=>'创建时间','value'=>$param['start_create'].'至'.$param['end_create']];
        }
        if($param['start_create'] && !$param['end_create']){
            array_push($where,"c.create_time >= ".strtotime($param['start_create']));
            $item_value[] = ['name'=>'start_create','item'=>'创建时间','value'=>$param['start_create'].'至-'];
        }
        if(!$param['start_create'] && $param['end_create']){
            array_push($where,"c.create_time <= ".strtotime($param['end_create']));
            $item_value[] = ['name'=>'end_create','item'=>'创建时间','value'=>'-至'.$param['end_create']];
        }
        if($param['start_birthday'] && $param['end_birthday']){
            array_push($where,"c.birthday between ".strtotime($param['start_birthday'])." and ".strtotime($param['end_birthday']));
            $item_value[] = ['name'=>'birthday','item'=>'出生日期','value'=>$param['start_birthday'].'至'.$param['end_birthday']];
        }
        if($param['start_birthday'] && !$param['end_birthday']){
            array_push($where,"c.birthday >= ".strtotime($param['start_birthday']));
            $item_value[] = ['name'=>'start_birthday','item'=>'出生日期','value'=>$param['start_birthday'].'至-'];
        }
        if(!$param['start_birthday'] && $param['end_birthday']){
            array_push($where,"c.birthday <= ".strtotime($param['end_birthday']));
            $item_value[] = ['name'=>'end_birthday','item'=>'出生日期','value'=>'-至'.$param['end_birthday']];
        }
        if($param['sex']){
            array_push($where,"c.sex = ".$param['sex']);
            $sex = ($param['sex'] == 1) ? '男' : '女' ;
            $item_value[] = ['name'=>'sex','item'=>'性别','value'=>$sex];
        }
        if(!empty($param['group'])){
            $whereGroup = '(';
            foreach($param['group'] as $g){
                $whereGroup .= "find_in_set(".$g.",g.gid) OR ";
            }
            array_push($where,rtrim($whereGroup,'OR ') .")");
            // 获取分组信息
            $group_id = implode(',',$param['group']);
            $whereGroup = " `id` IN (".$group_id.")";
            $group = model('Group')->groupName($whereGroup);
            $item_value[] = ['name'=>'group[]','item'=>'分组','value'=>$group];
        }
        if(!empty($param['tag'])){
            $whereTag = '(';
            foreach($param['tag'] as $t){
                $whereTag .= "find_in_set(".$t.",t.tid) OR ";
            }
            array_push($where,rtrim($whereTag,'OR ') .")");
            // 获取标签信息
            $tag_id = implode(',',$param['tag']);
            $whereTag = " `id` IN (".$tag_id.")";
            $tag = model('Tag')->tagName($whereTag);
            $item_value[] = ['name'=>'tag[]','item'=>'标签','value'=>$tag];
        }
        $whereString = join(' and ',$where);
        $arr_filter = array_filter($param);
        $client = model('Client')->clientList($whereString, $arr_filter);
        $this->assign('client',$client['client']);
        $this->assign('page',$client['page']);
        $this->assign('item_value',$item_value);
        $this->assign('param',$arr_filter);
        $this->assign('title','养老平台 - 服务对象');
        return view();
    }

    /**
     * 头像上传
     */
    public function uploadHead()
    {
        $result = F_upload_to_local();
        return $result;
    }

    /**
     * 添加/编辑服务对象基础信息
     */
    public function submitBasicInfo()
    {
        $request = Request::instance();
        $id = addslashes($request->post('id'));
        $name = addslashes($request->post('name'));
        $id_number = addslashes($request->post('id_number'));
        $head = addslashes($request->post('head'));
        $sex = (int)$request->post('sex');
        $birthday = strtotime($request->post('birthday'));
        $mobile = addslashes($request->post('mobile'));
        $address = addslashes($request->post('address'));
        $permanent_address = addslashes($request->post('permanent_address'));
        $result = model('Client')->submitBasicInfo($id, $name, $id_number, $head, $sex, $birthday, $mobile, $address, $permanent_address);
        return $result;
    }

    /**
     * 服务对象基础信息
     */
    public function clientBase()
    {
        $model = model('Client');
        $uid = Request::instance()->get('id');
        // 获取用户信息
        $user = $model->userInfo($uid);
        $this->assign('user',$user);
        // 获取联系人信息
        $contacts = $model->contactsInfo($uid);
        $this->assign('contacts',$contacts);
        // 获取分组信息
        $group = $model->groupInfo($uid);
        $this->assign('group',$group);
        // 获取标签信息
        $tag = $model->tagInfo($uid);
        $this->assign('tag',$tag);
        // 获取用户的其他信息
        $other = $model->userOtherInfo($uid);
        $this->assign('other',$other);
        $this->assign('title','养老平台 - 服务对象基础信息');
        return view('client_base');
    }

    /**
     * 更新服务对象的联系人信息
     */
    public function submitContactsInfo()
    {
        $request = Request::instance();
        $uid = addslashes($request->post('id'));
        $type0 = addslashes($request->post('type0'));
        $name0 = addslashes($request->post('name0'));
        $mobile0 = addslashes($request->post('mobile0'));
        $type1 = addslashes($request->post('type1'));
        $name1 = addslashes($request->post('name1'));
        $mobile1 = addslashes($request->post('mobile1'));
        $type2 = addslashes($request->post('type2'));
        $name2 = addslashes($request->post('name2'));
        $mobile2 = addslashes($request->post('mobile2'));
        $contacts = [];
        if($type0){
            array_push($contacts,['type'=>$type0,'name'=>$name0,'mobile'=>$mobile0]);
        }
        if($type1){
            array_push($contacts,['type'=>$type1,'name'=>$name1,'mobile'=>$mobile1]);
        }
        if($type2){
            array_push($contacts,['type'=>$type2,'name'=>$name2,'mobile'=>$mobile2]);
        }
        $result = model('Client')->updateContactsInfo($uid, $contacts);
        return $result;
    }

    /**
     * 更新服务对象的分组信息
     */
    public function submitClientGroup()
    {
        $request = Request::instance();
        $uid = $request->post('id');
        $group = $request->post()['group'];
        $result = model('Client')->updateClientGroup($uid, $group);
        return $result;
    }

    /**
     * 更新服务对象的标签信息
     */
    public function submitClientTag()
    {
        $request = Request::instance();
        $uid = $request->post('id');
        $tag = $request->post()['tag'];
        $result = model('Client')->updateClientTag($uid, $tag);
        return $result;
    }
    
    /**
     * 更新服务对象的其它信息
     */
    public function submitClientOther()
    {
        $request = Request::instance();
        $uid = addslashes($request->post('id'));
        $native_place = addslashes($request->post('native_place'));
        $nation = addslashes($request->post('nation'));
        $education = addslashes($request->post('education'));
        $political = addslashes($request->post('political'));
        $religion = addslashes($request->post('religion'));
        $hobby = json_encode($request->post()['hobby'],JSON_UNESCAPED_UNICODE);
        $diet_taboo = json_encode($request->post()['diet_taboo'],JSON_UNESCAPED_UNICODE);
        $blood_type = (int)$request->post('blood_type');
        $rh_negative = (int)$request->post('rh_negative');
        $economic_source = addslashes($request->post('economic_source'));
        $livelihood = addslashes($request->post('livelihood'));
        $caregiver = json_encode($request->post()['caregiver'],JSON_UNESCAPED_UNICODE);
        $healthy = addslashes($request->post('healthy'));
        $live = addslashes($request->post('live'));
        $house = addslashes($request->post('house'));
        $provider = addslashes($request->post('provider'));
        $remarks = addslashes($request->post('remarks'));
        $result = model('Client')->updateOtherInfo($uid, $native_place, $nation, $education, $political, $religion, $hobby, $diet_taboo, $blood_type, $rh_negative, $economic_source, $livelihood, $caregiver, $healthy, $live, $house, $provider, $remarks);
        return $result;
    }

    /**
     * 服务对象分组
     */
    public function group()
    {
        $isAjax = Request::instance()->isAjax();
        $limit = $isAjax ? 10000 : 20 ;
        $group = model('Group')->groupList($limit);
        if($isAjax){
            return $group['group']['data'];
        }
        $this->assign('group',$group['group']);
        $this->assign('page',$group['page']);
        $this->assign('title','养老平台 - 服务对象分组');
        return view();
    }

    /**
     * 提交服务对象分组信息
     */
    public function submitGroup()
    {
        $request = Request::instance();
        $id = addslashes($request->post('id'));
        $name = addslashes($request->post('name'));
        $remarks = addslashes($request->post('remarks'));
        $result = model('Group')->submitGroup($id, $name, $remarks);
        return $result;
    }

    /**
     * 删除服务对象分组
     */
    public function delGroup()
    {
        $id = Request::instance()->post('id');
        $result = model('Group')->delGroup($id);
        return $result;
    }

    /**
     * 服务对象标签
     */
    public function tag()
    {
        $tag = model('Tag')->tagList();
        if(Request::instance()->isAjax()){
            return $tag;
        }
        $this->assign('tag',$tag);
        $this->assign('title','养老平台 - 服务对象标签');
        return view();
    }

    /**
     * 提交标签信息
     */
    public function submitTag()
    {
        $request = Request::instance();
        $id = addslashes($request->post('id'));
        $name = addslashes($request->post('name'));
        $result = model('Tag')->submitTag($id, $name);
        return $result;
    }

    /**
     * 删除标签
     */
    public function delTag()
    {
        $id = Request::instance()->post('id');
        $result = model('Tag')->delTag($id);
        return $result;
    }

    /**
     * 健康档案
     */
    public function healthy()
    {
        $request = Request::instance();
        $uid = addslashes($request->get('id'));
        $view = addslashes($request->get('view'));
        // 获取用户信息
        $user = model('Client')->userInfo($uid);
        $this->assign('user',$user);
        $this->assign('userId',$uid);
        $view = (string)$view;
        return $this->$view($uid);
    }

    /**
     * 基础档案
     * @param int $uid 服务对象id
     */
    protected function healthyBase($uid)
    {
        // 获取基础档案信息
        $base = model('Healthy')->healthyBaseInfo($uid);
        $this->assign('base',$base);
        $this->assign('title','养老平台 - 健康档案');
        return view('healthy_base');
    }

    /**
     * 提交基础健康档案信息
     */
    public function submitHealthyBase()
    {
        $request = Request::instance();
        $uid = (int)$request->post('id');
        $height = (int)$request->post('height');
        $weight = (int)$request->post('weight');
        $hearing = addslashes($request->post('hearing'));
        $vision = addslashes($request->post('vision'));
        $sleep = (int)$request->post('sleep');
        $smoke = (int)$request->post('smoke');
        $smoke_frequency = (int)$request->post('smoke_frequency');
        $drink = (int)$request->post('drink');
        $exercise_frequency = (int)$request->post('exercise_frequency');
        $exercise_duration = (int)$request->post('exercise_duration');
        $exercise_type = json_encode($request->post()['exercise_type']);
        $healthy_products = addslashes($request->post('healthy_products'));
        $medical_payment = (int)$request->post('medical_payment');
        $social_activity = json_encode($request->post()['social_activity']);
        $remarks = addslashes($request->post('remarks'));
        $result = model('Healthy')->updateHealthyBase($uid, $height, $weight, $hearing, $vision, $sleep, $smoke, $smoke_frequency, $drink, $exercise_frequency, $exercise_duration, $exercise_type, $healthy_products, $medical_payment, $social_activity, $remarks);
        return $result;
    }

    /**
     * 既往病史
     * @param int $id 服务对象id
     */
    protected function medicalHistory($uid)
    {
        $list = model('Medicalhistory')->medicalHistoryList($uid);
        $this->assign('history',$list['history']);
        $this->assign('page',$list['page']);
        $this->assign('title','养老平台 - 既往病史');
        return view('medical_history');
    }

    /**
     * 提交服务对象的既往病史信息
     */
    public function submitMedicalHistory()
    {
        $request = Request::instance();
        $uid = addslashes($request->post('uid'));
        $id = addslashes($request->post('id'));
        $descript = addslashes($request->post('descript'));
        $diagnostic_time = strtotime($request->post('diagnostic_time'));
        $remarks = addslashes($request->post('remarks'));
        $result = model('Medicalhistory')->submitMedicalHistory($uid, $id, $descript, $diagnostic_time, $remarks);
        return $result;
    }
    
    /**
     * 删除既往病史
     */
    public function delMedicalHistory()
    {
        $request = Request::instance();
        $uid = addslashes($request->post('uid'));
        $id = addslashes($request->post('id'));
        $result = model('Medicalhistory')->deleteMedicalHistory($uid, $id);
        return $result;
    }

    /**
     * 体检记录
     * @param int $uid 服务对象id
     */
    protected function physical($uid)
    {
        $physical = model('Physical')->physicalList($uid);
        $this->assign('physical',$physical['physical']);
        $this->assign('page',$physical['page']);
        $this->assign('title','养老平台 - 体检记录');
        return view('physical');
    }

    /**
     * 提交体检记录
     */
    public function submitPhysical()
    {
        $request = Request::instance();
        $uid = addslashes($request->post('uid'));
        $id = addslashes($request->post('id'));
        $institution = addslashes($request->post('institution'));
        $physical_time = strtotime($request->post('physical_time'));
        $image = json_encode($request->post()['image']);
        $remarks = addslashes($request->post('remarks'));
        $result = model('Physical')->submitPhysical($uid, $id, $institution, $physical_time, $image, $remarks);
        return $result;
    }

    /**
     * 删除体检记录
     */
    public function delPhysical()
    {
        $request = Request::instance();
        $uid = addslashes($request->post('uid'));
        $id = addslashes($request->post('id'));
        $result = model('Physical')->deletePhysical($uid, $id);
        return $result;
    }

    /**
     * 药物过敏史
     * @param int $uid 服务对象id
     */
    public function allergy($uid)
    {
        $allergy = model('Allergy')->allergyInfo($uid);
        $this->assign('allergy',$allergy);
        $this->assign('title','养老平台 - 药物过敏史');
        return view('allergy');
    }

    /**
     * 提交药物过敏信息
     */
    public function submitAllergy()
    {
        $request = Request::instance();
        $uid = addslashes($request->post('uid'));
        $medicine = $request->post()['medicine'];
        $result = model('Allergy')->submitAllergy($uid, $medicine);
        return $result;
    }

    /**
     * 家族遗传病史
     * @param int $uid 服务对象id
     */
    protected function hereditary($uid)
    {
        $hereditary = model('Hereditary')->hereditaryList($uid);
        $this->assign('hereditary',$hereditary['hereditary']);
        $this->assign('page',$hereditary['page']);
        $this->assign('title','养老平台 - 遗传病史');
        return view('hereditary');
    }

    /**
     * 提交遗传病史信息
     */
    public function submitHereditary()
    {
        $request = Request::instance();
        $uid = addslashes($request->post('uid'));
        $id = addslashes($request->post('id'));
        $name = addslashes($request->post('name'));
        $relationship = addslashes($request->post('relationship'));
        $remarks = addslashes($request->post('remarks'));
        $result = model('Hereditary')->submitHereditary($uid, $id, $name, $relationship, $remarks);
        return $result;
    }

    /**
     * 删除遗传病史记录
     */
    public function delHereditary()
    {
        $request = Request::instance();
        $uid = addslashes($request->post('uid'));
        $id = addslashes($request->post('id'));
        $result = model('Hereditary')->deleteHereditary($uid, $id);
        return $result;
    }

    /**
     * 服务对象病历存档
     * @param int $uid 服务对象id
     */
    protected function caseRecord($uid)
    {
        $case = model('Caserecord')->caseRecordList($uid);
        $this->assign('case',$case['case']);
        $this->assign('page',$case['page']);
        $this->assign('title','养老平台 - 病历存档');
        return view('case_record');
    }

    /**
     * 提交服务对象的病历信息
     */
    public function submitCaesRecord()
    {
        $request = Request::instance();
        $uid = addslashes($request->post('uid'));
        $id = addslashes($request->post('id'));
        $institution = addslashes($request->post('institution'));
        $case_time = strtotime($request->post('case_time'));
        $image = json_encode($request->post()['image']);
        $remarks = addslashes($request->post('remarks'));
        $result = model('Caserecord')->submitCaseRecord($uid, $id, $institution, $case_time, $image, $remarks);
        return $result;
    }
    
    /**
     * 删除服务对象的病历
     */
    public function delCaseRecord()
    {
        $request = Request::instance();
        $uid = addslashes($request->post('uid'));
        $id = addslashes($request->post('id'));
        $result = model('Caserecord')->deleteCaseRecord($uid, $id);
        return $result;
    }

    /**
     * 服务对象的积分
     */
    public function integral()
    {
        $model = model('Integral');
        $request = Request::instance();
        $uid = (int)$request->get('id');
        $type = (int)$request->get('type');
        // 统计当前用户的积分数据
        $clientIntegralRecords = $model->sumIntegralRecords($uid);
        $this->assign('clientIntegralRecords',$clientIntegralRecords);
        // 获取当前用户的积分数
        $clientIntegral = $model->clientIntegral($uid);
        $this->assign('clientIntegral',$clientIntegral);
        // 获取用户的积分记录
        $where['u.id'] = ['eq',$uid];
        if($type){
            $where['i.type'] = ['eq',$type];
        }
        $query = [
            'id'=>$uid,
            'type'=>$type
        ];
        $records = $model->integralRecords($where, $query);
        $this->assign('records',$records);
        // 获取用户信息
        $userInfo = model('Client')->userInfo($uid);
        $this->assign('user',$userInfo);
        $this->assign('title','养老平台 - 服务对象积分');
        return view();
    }
    
    /*
    * 实时定位
    */
    public function currentposition(){
        $request = Request::instance();
        //获取用户信息
        $uid = (int)$request->get('id');
        $userInfo = model('Client')->userMapInfo($uid);
        $this->assign('user',$userInfo);
        $this->assign('loc',json_encode($userInfo));
        return view();
    }

    /*
    *创建电子围栏
    */
    public function create_fence(){
        if (Request()->isPost()) {
            $result = model('Client')->CreateFence();
            return $result;
        }
    }

    /*
    *移除电子围栏
    */
    public function rmfence(){
        if (Request()->isPost()) {
            $result = model('Client')->RomoveFence();
            return $result;
        }
    }

    /*
    *点击获取时时定位
    */
    public function getgps(){
        if (Request()->isPost()) {
            $result = model('Client')->GetDeviceLoc();
            return $result;
        }
    }

    /**
     * 服务对象的服务工单
     */
    public function serviceOrder()
    {
        $request = Request::instance();
        $uid = (int)$request->get('id');
        $type = (int)$request->get('type');
        // 获取对应的工单列表
        $whereWork['w.client_id'] = ['eq',$uid];
        $query = [
            'id'=>$uid,
            'type'=>$type,
        ];
        $work = model('Work')->worksList($whereWork, $query);
        $this->assign('work',$work['work']);
        $this->assign('page',$work['page']);
        // 获取工单统计结果
        $whereData['client_id'] = ['eq',$uid];
        $statistics = model('Work')->workStatisticalData($whereData);
        $this->assign('statistics',$statistics);
        // 获取用户信息
        $userInfo = model('Client')->userInfo($uid);
        $this->assign('user',$userInfo);
        $this->assign('title','养老平台 - 服务对象工单');
        return view('service_order');
    }

    /*
    *时时健康
    */
    public function currenthealthy(){
        // 获取当前用户今日健康数据
        $request = Request::instance();
        $uid = (int)$request->get('id');
        $health = model('Client')->GetUserHealth($uid);
        // 获取用户信息
        $userInfo = model('Client')->userInfo($uid);
        $d_info = model('Client')->GetDeviceBindUserInfo($uid);
        $this->assign('device_info',$d_info);
        $this->assign('user',$userInfo);
        $this->assign('health',$health);
        $this->assign('uid',$uid);
        return $this->fetch();
    }

    /*
    *健康心率数据手工入录
    */
    public function addheart(){
        if (Request()->isAjax()) {
            $health = model('Client')->AddUserHealthHeart();
            return $health;
        }
    }

    /*
    *血压健康数据
    */
    public function addblood(){
        if (Request()->isPost()) {
            $health = model('Client')->AddUserHealthBlood();
            return $health;
        }
    }

    /*
    *计步健康数据
    */
    public function addsteep(){
        if (Request()->isPost()) {
            $health = model('Client')->AddUserHealthSteep();
            return $health;
        }
    }

    /*
    *睡眠健康数据入录
    */
    public function addsleep(){
        if (Request()->isPost()) {
            $health = model('Client')->AddUserHealthSleep();
            return $health;
        }
    }

    /*
    *时时健康查询心率与计步图表数据
    */
    public function healthec(){
        if (Request()->isAjax()) {
            $health = model('Client')->GetUserHealthEcharts();
            return $health;
        }
    }

    /*
    *时时健康查询血压与睡眠图表数据
    */
    public function healthboold(){
        if (Request()->isAjax()) {
            $res = model('Client')->GetUserHealthEchartsBoold();
            return $res;
        }
    }

    /*
    *轨迹动画
    */
    public function track(){
        $res = model('Client')->GetGpsLog();
        $this->assign('res',json_encode($res));
        return $this->fetch();
    }

    /**
     *  下载模板
     */
    public function clientDown()
    {
        $model = new \app\index\model\Client();

        $model->exportTemplet();
    }

    /**
     * 上传积分核销的excel
     */
    public function clientSub()
    {
        $result = F_upload_to_local($name='file', $size=2, $type=['xls','xlsx']);
        return $result;
    }

    /**
     * 批量添加人员
     * @return mixed
     */
    public function destoryClient()
    {
        $excel = $_SERVER['DOCUMENT_ROOT'].addslashes(Request::instance()->post('integra_excel'));
        $result = model('Client')->destoryClient($excel);
        return $result;
    }


}