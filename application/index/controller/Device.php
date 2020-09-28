<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/18
 * Time: 13:04
 * 设备管理
 */
namespace app\index\controller;

use app\index\controller\Common;
use think\Request;

class Device extends Common {
    /**
     * 设备列表
     */
    public function index()
    {
        $request = Request::instance();
        $param = [
            'passage'=>addslashes($request->get('passage')),
            'imei'=>addslashes($request->get('imei')),
            'version'=>addslashes($request->get('version')),
            'bind'=>addslashes($request->get('bind')),
            'start_time'=>addslashes($request->get('start_time')),
            'end_time'=>addslashes($request->get('end_time')),
        ];
        $where = [];
        $item_value = [];
        if($param['passage']){
            $where['d.pid'] = ['eq',$param['passage']];
            $passage = model('DevicePassage')->passageDetails($param['passage']);
            $item_value[] = ['name'=>'passage','item'=>'采购通道','value'=>$passage['name']];
        }
        if($param['imei']){
            $where['d.imei'] = ['like','%'.$param['imei'].'%'];
            $item_value[] = ['name'=>'imei','item'=>'IMEI号','value'=>$param['imei']];
        }
        if($param['version']){
            $where['d.version'] = ['like','%'.$param['version'].'%'];
            $item_value[] = ['name'=>'version','item'=>'固件版本号','value'=>$param['version']];
        }
        if($param['bind']){
            $where['d.is_binding'] = ['eq',(int)$param['bind']];
            $item_value[] = ['name'=>'bind','item'=>'是否绑定','value'=>$param['bind']];
        }
        if($param['start_time'] && $param['end_time']){
            $where['d.bind_time'] = ['between',strtotime($param['start_time']).','.strtotime($param['end_time'])];
            $item_value[] = ['name'=>'time','item'=>'绑定时间','value'=>$param['start_time'].'至'.$param['end_time']];
        }
        if($param['start_time'] && !$param['end_time']){
            $where['d.bind_time'] = ['egt',strtotime($param['start_time'])];
            $item_value[] = ['name'=>'start_time','item'=>'绑定时间','value'=>$param['start_time'].'至-'];
        }
        if(!$param['start_time'] && $param['end_time']){
            $where['d.bind_time'] = ['elt',strtotime($param['end_time'])];
            $item_value[] = ['name'=>'end_time','item'=>'绑定时间','value'=>'-至'.$param['start_time']];
        }
        $param_filter = array_filter($param);
        $device = model('Device')->deviceList($where, $param_filter);
        $this->assign('device',$device['device']);
        $this->assign('page',$device['page']);
        $this->assign('item_value',$item_value);
        $this->assign('param',$param);
        // 获取采购通道
        $wherePassage['p_status'] = ['eq',1];
        $passage = model('DevicePassage')->passageList($wherePassage);
        $this->assign('passage',$passage['passage']['data']);
        $this->assign('title','养老平台 - 设备列表');
        return view();
    }

    /**
     * 设备详情
     */
    public function details()
    {
        $request = Request::instance();
        $id = addslashes($request->get('id'));
        $details = model('Device')->deviceDetails($id);
        $this->assign('details',$details);
        if($details['is_binding'] == 1){
            // 获取绑定用户信息
            $bind_user = model('Client')->userInfo($details['uid']);
            $this->assign('bind_user',$bind_user);
            // 获取紧急联系人
            $emergency = model('DeviceEmergency')->deviceEmergencyList($details['id']);
            $this->assign('emergency',$emergency);
        }
        $this->assign('title','养老平台 - 设备详情');
        return view();
    }

    /**
     * 上传设备信息的excel文件
     */
    public function uploadExcel()
    {
        $result = F_upload_to_local($name='file', $size=2, $type=['xls','xlsx']);
        return $result;
    }

    /**
     * 提交设备信息
     */
    public function submitDevice()
    {
        $request = Request::instance();
        $passage = (int)$request->post('passage');
        $excel = $_SERVER['DOCUMENT_ROOT'].addslashes($request->post('device_excel'));
        $result = model('Device')->importDevice($passage, $excel);
        return $result;
    }

    /**
     * 设备通道
     */
    public function passage()
    {
        $passage = model('DevicePassage')->passageList();
        $this->assign('passage',$passage['passage']);
        $this->assign('page',$passage['page']);
        $this->assign('title','养老平台 - 设备通道');
        return view();
    }
    
    /**
     * 获取通道列表
     */
    public function passageList()
    {
        $passage = model('DevicePassage')->passageList();
        return $passage['passage']['data'];
    }

    /**
     * 提交通道信息
     */
    public function submitPassage()
    {
        $request = Request::instance();
        $id = addslashes($request->post('id'));
        $name = addslashes($request->post('name'));
        $param = addslashes($request->post('param'));
        $status = $request->post('status') ? 1 : 2 ;
        $result = model('DevicePassage')->submitPassage($id, $name, $param, $status);
        return $result;
    }
    
    /**
     * 获取设备绑定用户的基础信息
     */
    public function clientList()
    {
        $request = Request::instance();
        $param = [
            'name'=>addslashes($request->post('name')),
            'id_number'=>addslashes($request->post('id_number'))
        ];
        $where = [];
        if($param['name']){
            $where['c.name'] = ['like','%'.$param['name'].'%'];
        }
        if($param['id_number']){
            $where['c.id_number'] = ['like','%'.$param['id_number'].'%'];
        }
        $query = array_filter($param);
        $client = model('Client')->clientList($where, $query);
        return $client['client'];
    }

    /**
     * 提交设备绑定用户的
     */
    public function submitBindInfo()
    {
        $request = Request::instance();
        $did = addslashes($request->post('did'));
        $uid = addslashes($request->post('uid'));
        $result = model('Device')->bindUser($did, $uid);
        return $result;
    }

    /**
     * 取消用户绑定
     */
    public function submitCancelBind()
    {
        $id = Request::instance()->post('id');
        $result = model('Device')->cancelBind($id);
        return $result;
    }

    /**
     * 提交设备发放信息
     */
    public function submitDeviceSend()
    {
        $request = Request::instance();
        $id = addslashes($request->post('id'));
        $msisdn = addslashes($request->post('mobile'));
        $is_send = addslashes($request->post('is_send'));
        $send_time = strtotime($request->post('send_time'));
        $iccid = addslashes($request->post('iccid'));
        $result = model('Device')->deviceSend($id, $msisdn, $is_send, $send_time,$iccid);
        return $result;
    }

    /**
     * 提交设备的sos信息
     */
    public function submitDeviceSos()
    {
        $request = Request::instance();
        $did = addslashes($request->post('did'));
        $name0 = addslashes($request->post('name0'));
        $mobile0 = addslashes($request->post('mobile0'));
        $name1 = addslashes($request->post('name1'));
        $mobile1 = addslashes($request->post('mobile1'));
        $name2 = addslashes($request->post('name2'));
        $mobile2 = addslashes($request->post('mobile2'));
        $sos = [];
        if($name0){
            $sos[] = ['name'=>$name0,'mobile'=>$mobile0];
        }
        if($name1){
            $sos[] = ['name'=>$name1,'mobile'=>$mobile1];
        }
        if($name2){
            $sos[] = ['name'=>$name2,'mobile'=>$mobile2];
        }
        $result = model('DeviceEmergency')->deviceSos($did, $sos);
        return $result;
    }

    /*
    *设置心率或gps监测时间段
    */
    public function submitDeviceGpsOrHeart(){
        if (Request()->isPost()) {
            $res = model('DeciveInterval')->CreateTimes();
            return $res;
        }
    }

    /*
    *设置定位工作时间段
    */
    public function submitDeviceGpstiming(){
        if (Request()->isPost()) {
            $res = model('DeciveInterval')->CreateTimings();
            return $res;
        }
    }

    /*
    *设备详情查看是否在线
    */
    public function savedevice(){
        if (Request()->isPost()) {
            $res = model('Device')->UpdateDeviceIsline();
            return $res;
        }
    }

    /*
    *添加设备维修记录
    */
    public  function maintain(){
        $Alterlog = model('Alterlog');
        if (Request()->isPost()) {
           $res = model('Alterlog')->DeviceMaintainAdd();
           return $res;die;
        }
        //这里是查询维修记录
        $res = $Alterlog->GetDeviceMaintainInfo($state=1);
        $passage = model('DevicePassage')->passageList();
        $this->assign('page',$res['page']);
        $this->assign('res',$res);
        $this->assign('passage',$passage['passage']['data']);
        return $this->fetch();
    }

    /*
    *设备维修记录编辑
    */
    public function maintainsave(){
        if (Request()->isPost()) {
           $res = model('Alterlog')->DeviceMaintainSave();
           return $res;
        }
    }

    /*
    *设备维修记录删除
    */
    public function maintaindel(){
        if (Request()->isPost()) {
           $res = model('Alterlog')->DeviceMaintainDel();
           return $res;
        }
    }

    /*
    *设备维修记录导出
    */
    public function down(){
        $pid = input('id');
        $state = input('state');
        $res = model('Alterlog')->DeviceMaintainDown($state,$pid);
    }

    /*
    *维修记录完结前查询记录清单
    */
    public function check_maintaininfo(){
        if (Request()->isPost()) {
            $res = model('Alterlog')->CreateDeviceMaintainInfo();
            return $res;die;
        }
        $result = model('Alterlog')->CheckGetDeviceMaintainInfo();
        return $result;
    }

    /*
    *手动入录设备
    */
    public function useroperation(){
        if (Request()->isPost()) {
            $res = model('Device')->UserOperationInfo();
            return $res;
        }
    }
}