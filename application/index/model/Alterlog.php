<?php
/**
*设备维修记录
*/
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;
use think\Session;

class Alterlog extends Common {

	/*
    *设备出厂维修记录添加
    */
    public function DeviceMaintainAdd(){
        try {
            $info = input();
            $usinfo = Session::get('S_USER_INFO');
            if ($usinfo['type']==1) {
                //管理员
                $arr['utype'] = 2;
                $arr['uid'] = $usinfo['id'];
            }else{
                //话务员
                $arr['utype'] = 1;
                $arr['uid'] = $usinfo['work_number'];
            }
            //查询设备是否存在
            $tmp['imei'] = ['eq',$info['imei']];
            $d_info = db::name('device')->field('imei,uid,pid')->where($tmp)->find();

            if (!$d_info['imei']) {
                return ['code'=>1,'msg'=>'您输入的设备号码为找到,请核实'];
            }

            //查询当前设备是否有过维修且正在处理
            $where['imei'] = ['eq',$d_info['imei']];
            $where['type'] = ['eq',1];
            $alog = db::name('alterlog')->where($where)->select();
            if ($alog) {
                return ['code'=>1,'msg'=>'您输入的设备目前已经存在维修记录中'];
            }
            //插入记录
            $arr['imei'] = $d_info['imei'];
            $arr['addtime'] = time();
            $arr['content'] = $info['content'];
            $arr['state'] = 1;
            $arr['pid'] = $d_info['pid'];
            $arr['cuid'] = $d_info['uid'];
            $result = db::name('alterlog')->insert($arr);
            if ($result) {
                return ['code'=>0,'msg'=>'添加成功'];
            }
            return ['code'=>1,'msg'=>'添加失败'];
        } catch (Exception $e) {
            Log::write('添加设备出厂维修异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }


    /*
    *设备维修记录列表查询
    *$state int 查询类型
    *return array|bool
    */
    public function GetDeviceMaintainInfo($state){
    	try {
            $info = input();

            //创建时间搜索
            if ( (isset($info['start_create']) && $info['start_create']!=='') && (isset($info['end_create']) && $info['end_create']!=='')) {
                $where['addtime'] = ['between',[strtotime($info['start_create']),strtotime($info['end_create'])] ];
                $query['start_create']=$info['start_create'];
                $query['end_create']=$info['end_create'];
                $item_value[] = ['name'=>'create','item'=>'创建时间','value'=>$info['start_create'].'至'.$info['end_create']];

            }else if (isset($info['end_create']) && $info['end_create']!=='') {
               $where['addtime'] = ['lt',strtotime($info['end_create'])];
               $query['end_create']=$info['end_create'];
               $item_value[] = ['name'=>'end_create','item'=>'创建时间','value'=>$info['end_create']];

            }else if (isset($info['start_create']) && $info['start_create']!=='') {
               $where['addtime'] = ['lt',strtotime($info['start_create'])];
               $query['start_create']=$info['start_create'];
               $item_value[] = ['name'=>'start_create','item'=>'创建时间','value'=>$info['start_create']];
            }

            //设备厂商搜索
            if (isset($info['pid']) && $info['pid']!=='') {
                $where['pid'] = ['eq',$info['pid']];
                $query['pid']=$info['pid'];
                $pname = db::name('device_passage')->where('id',$info['pid'])->value('name');
                $item_value[] = ['name'=>'pid','item'=>'设备厂商','value'=>$pname];
            }

            //设备号码搜索
            if (isset($info['s_imei']) && $info['s_imei']!=='') {
                $did = db::name('device')->where('imei',$info['s_imei'])->value('id');
                $where['did'] = ['eq',$did];
                $query['s_imei']=$info['s_imei'];
                $item_value[] = ['name'=>'s_imei','item'=>'imei','value'=>$info['s_imei']];
            }
    		$where['state'] = ['eq',$state];
            $query['state'] = $state;
    		$list = db::name('alterlog')->where($where)->order('addtime desc')->paginate(30,false,['query'=>$query]);
    		$page = $list->render();
    		$show = $list->toArray();
            // bug($show);die;
    		foreach ($show['data'] as $key=>&$value) {

    			//查询服务对象信息
    			$tmp['b.id'] = ['eq',$value['cuid']];
    			$userinfo = db::name('client b')
                ->field('b.name,b.id_number,b.mobile,b.sex,b.address')
                ->where($tmp)
                ->find();
    			$value['name'] = $userinfo['name'];
    			$value['id_number'] = $userinfo['id_number'];
                if($userinfo['sex']){
                    $value['sex'] = $userinfo['sex'] == 1 ? '男' : '女';
                }else{
                    $value['sex'] = '--';
                }
    			$value['address'] = $userinfo['address'];
                $value['pname'] = db::name('device_passage')->where('id',$value['pid'])->value('name');
    			//查询该维修记录负责人信息
    			if ($value['utype']==1) {
    				//查询话务员
    				$value['uname'] = db::name('staff_user')->where('work_number',$value['uid'])->find();
    			}else{
    				//查询管理员
    				$value['uname'] = db::name('user')->where('id',$value['uid'])->value('name');
    			}
                $value['type'] = ($value['type']==1) ? '未处理' : '已处理';

                //查询处理结果
                $condition['aid'] = ['eq',$value['id']];
                $log_res = db::name('settlelog')->where($condition)->order('addtime desc')->find();
                // echo db::name('settlelog')->getLastSql();
                // bug($log_res);
                //最终备注信息
                $value['remarks'] = $log_res['remarks'];
                if ($log_res['utype']==2) {
                    $value['case_people'] =  db::name('user')->where('id',$log_res['uid'])->value('name');
                }else{
                    $value['case_people'] = db::name('staff_user')->where('work_number',$log_res['uid'])->find();
                }
    		}
    		$arr = [
    			'page'=>$page,
    			'list'=>$show,
                'query'=>$query,
                'item'=>$item_value
    		];
    		return $arr;
    	} catch (Exception $e) {
    		Log::write('设备登记列表异常：'.$e->getMessage(),'error');
            return fasle;
    	}
    }


    /*
    *维修记录编辑
    */
    public function DeviceMaintainSave(){
        try {
            $info = input();
            $usinfo = Session::get('S_USER_INFO');
            if ($usinfo['type']==1) {
                //管理员
                $arr['utype'] = 2;
                $arr['uid'] = $usinfo['id'];
            }else{
                //话务员
                $arr['utype'] = 1;
                $arr['uid'] = $usinfo['work_number'];
            }
            //查询设备是否存在
            $where['imei'] = ['eq',$info['imei']];
            $did = db::name('device')->where($where)->find();
            if (!$did) {
                return ['code'=>1,'msg'=>'未查询到设备信息,请核实'];
            }
            $arr['imei'] = $info['imei'];
            $arr['pid'] = $did['pid'];
            $arr['content'] = $info['content'];
            $res = db::name('alterlog')->where('id',$info['hid'])->update($arr);
            if ($res!==fasle) {
                return ['code'=>0,'msg'=>'修改成功'];
            }
            return ['code'=>1,'msg'=>'修改失败'];
        } catch (Exception $e) {
            Log::write('设备登记列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /*
    *维修记录删除
    *return array
    */
    public  function DeviceMaintainDel(){
        try {
            $id = input('id');
            $info = db::name('alterlog')->where('id',$id)->delete();
            if ($info) {
                return ['code'=>0,'msg'=>'删除成功'];
            }
            return ['code'=>1,'msg'=>'删除失败'];
        } catch (Exception $e) {
            Log::write('设备登记列表删除异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /*
    *设备维修记录导出
    *state int 设备登记类型
    *$pid int 设备通道id
    */
    public function DeviceMaintainDown($state,$pid){
        try {
            // excel的header头
            $header = ['用户姓名', '身份证号码', '联系方式','性别','地址信息','故障描述','IMEI','设备通道','记录人','受理时间'];
            // 文件名
            $fileName = date('Y-m-d').'设备维修记录表格';

            //设备登记查询条件
            $where['type'] = ['eq',1];
            $where['state'] = ['eq',$state];
            $where['pid'] = ['eq',$pid];
            $list = db::name('alterlog')->where($where)->order('addtime desc')->select();
            foreach ($list as $key=>&$value) {
                $data[$key]['content'] = $value['content'];
                $data[$key]['addtime'] = date('Y-m-d H:i:s',$value['addtime']);
                //查询服务对象信息
                $tmp['b.id'] = ['eq',$value['cuid']];
                $userinfo = db::name('client b')
                ->field('b.name,b.id_number,b.mobile,b.sex,b.address')
                ->where($tmp)
                ->find();

                $data[$key]['name'] = $userinfo['name'];
                $data[$key]['id_number'] = $userinfo['id_number'];
                $data[$key]['sex'] = ($userinfo['sex'] == 1) ? '男' : '女';
                $data[$key]['address'] = $userinfo['address'];
                $data[$key]['imei'] = $userinfo['imei'];
                $data[$key]['pname'] = db::name('device_passage')->where('id',$value['pid'])->value('name');
                $data[$key]['mobile'] = $userinfo['mobile'];
                $data[$key]['imei'] = $value['iemi'];
                //查询该维修记录负责人信息
                if ($value['utype']==1) {
                    //查询话务员
                    $data[$key]['uname'] = db::name('staff_user')->where('work_number',$value['uid'])->find();
                }else{
                    //查询管理员
                    $data[$key]['uname'] = db::name('user')->where('id',$value['uid'])->value('name');
                }
            }

            unset($list);
            $show=[
                'name',
                'id_number',
                'mobile',
                'sex',
                'address',
                'content',
                'imei',
                'pname',
                'uname',
                'addtime',
            ];
            F_export_excel($data, $header, $show, $fileName);
        } catch (Exception $e) {
            Log::write('设备维修导出异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /*
    *完结维修时确认清单
    */
    public function CheckGetDeviceMaintainInfo(){
        try {
            $arr = input();
            $id = implode($arr['ids'],',');
            $where['a.id'] = ['in',$id];
            $where['a.state'] = ['eq',1];
            $res = db::name('alterlog a')
            ->field('b.name,c.imei,a.uid,a.utype,type,c.uid as cuid')
            ->join('device_passage b','a.pid=b.id','left')
            ->join('device c','a.imei=c.imei','left')
            ->where($where)
            ->select();
            foreach ($res as $key=>&$value) {
                if ($value['utype']==1) {
                    $value['uname'] = db::name('staff_user')->where('work_number',$value['uid'])->value('work_number');
                }else{
                    $value['uname'] = db::name('user')->where('id',$value['uid'])->value('name');
                }
                $cname = db::name('client')->where('id',$value['cuid'])->value('name');
                $value['cname'] = $cname ? $cname : '--';
                unset($value['uid']);
                unset($value['utype']);
                unset($value['cuid']);
            }
            return $res;
        } catch (Exception $e) {
            Log::write('设备维修完结前查询异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /*
    *维修记录办结选项
    */
    public function CreateDeviceMaintainInfo(){
        Db::startTrans();
        try {
            $info = input();
            //这里是办结维修记录
            $where['id']=['in',$info['ids']];
            $arr['type'] = 2;
            $res = db::name('alterlog')->where($where)->update($arr);

            //插入记录
            $log_arr= explode(',',$info['ids']);
            $usinfo = Session::get('S_USER_INFO');
            if ($usinfo['type']==1) {
                //管理员
                $log['utype'] = 2;
                $log['uid'] = $usinfo['id'];
            }else{
                //话务员
                $log['utype'] = 1;
                $log['uid'] = $usinfo['work_number'];
            }
            $tmp = true;

            foreach ($log_arr as $key=>&$value) {
                $log['aid'] = $value;
                $log['addtime'] = time();
                $log['remarks'] = $info['conclude'];
                $log_res = Db::name('settlelog')->insert($log);
                if (!$log_res) {
                    $tmp = false;
                }
            }
            if ($res!==false && $tmp) {
                Db::commit();
                return ['code'=>0,'msg'=>'备注成功'];
            }
            return ['code'=>0,'msg'=>'备注失败'];
        } catch (Exception $e) {
            Db::rollback();
            Log::write('设备维修完结操作异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }


    /*
    *设备退换添加记录
    *return array
    */
    public function WithdrawAdd(){
        try {
            $info = input();
            $usinfo = Session::get('S_USER_INFO');
            if ($usinfo['type']==1) {
                //管理员
                $arr['utype'] = 2;
                $arr['uid'] = $usinfo['id'];
            }else{
                //话务员
                $arr['utype'] = 1;
                $arr['uid'] = $usinfo['work_number'];
            }
            //查询设备是否存在
            $tmp['imei'] = ['eq',$info['imei']];
            $s_info = db::name('device')->where($tmp)->find();

            if (!$s_info) {
                return ['code'=>1,'msg'=>'您输入的设备号码为找到,请核实'];
            }
            //退换腕表必须是绑定过
            if (!$s_info['uid']) {
                return ['code'=>1,'msg'=>'请核实设备是否绑定'];
            }
             //插入记录
            $arr['imei'] = $info['imei'];
            $arr['addtime'] = time();
            $arr['content'] = $info['comment'];
            $arr['state'] = 2;
            $arr['pid'] = $s_info['pid'];
            $arr['cuid'] = $s_info['uid'];
            $result = db::name('alterlog')->insert($arr);
            if ($result) {
                return ['code'=>0,'msg'=>'添加成功'];
            }
            return ['code'=>1,'msg'=>'添加失败'];
        } catch (Exception $e) {
            Log::write('设备退换添加记录操作异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /*
    *设备退换记录删除
    *return array
    */
    public function WithdrawDel(){
        try {
            $id = input('id');
            $result = db::name('alterlog')->where('id',$id)->delete();
            if ($result) {
                return ['code'=>0,'msg'=>'删除成功'];
            }
            return ['code'=>1,'msg'=>'删除失败'];
        } catch (Exception $e) {
            Log::write('设备退换删除记录操作异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /*
    *设备变更记录添加
    *return array
    */
    public function DevicechangeAdd(){
        Db::startTrans();
        try {
            $info = input();
            /*
            *验证原始设备与新设备是否存在,且设备是否绑定过用户在使用
            */
            $where['imei'] = ['eq',$info['y_imei']];
            $y_info = Db::name('device')->field('uid,id')->where($where)->find();
            if (!$y_info['id']) {
                return ['code'=>1,'msg'=>$info['y_imei'].'未找到该设备,请核实设备imei号码'];
            }
            if (!$y_info['uid']) {
                return ['code'=>1,'msg'=>$info['y_imei'].'该设备未绑定用户'];
            }
            /*
            *验证新设备
            */
            $tmp['imei'] = ['eq',$info['n_imei']];
            $n_info = Db::name('device')->field('uid,id,pid')->where($tmp)->find();
            if (!$n_info['id']) {
                return ['code'=>1,'msg'=>$info['imei'].'未找到该设备,请核实设备imei号码'];
            }
            if ($n_info['uid']) {
                return ['code'=>1,'msg'=>$info['n_imei'].'该设备已经绑定用户在使用,如需重新发放,请先解绑'];
            }

            /*
            *查询记录反馈表是否拥有该记录
            */
            $tmp_check['imei']=['eq',$info['y_imei']];
            $tmp_check['type']=['eq',1];
            $check = Db::name('alterlog')->where($tmp_check)->select();

            if ($check) {
                return ['code'=>1,'msg'=>$info['imei'].'该设备记录已存在'];
            }
            /*
            *产生换表记录与维修记录
            */
            $usinfo = Session::get('S_USER_INFO');
            if ($usinfo['type']==1) {
                //管理员
                $arr['utype'] = 2;
                $arr['uid'] = $usinfo['id'];
            }else{
                //话务员
                $arr['utype'] = 1;
                $arr['uid'] = $usinfo['work_number'];
            }
            $arr['imei'] = $info['y_imei'];
            $arr['addtime'] = time();
            $arr['content'] = $info['comment'];
            $arr['cuid'] = $y_info['uid'];
            $arr['pid'] = $n_info['pid'];
            $arr['state'] = 3;
            //产生勾选的反馈记录
            $result = Db::name('alterlog')->insert($arr);
            $aid = Db::name('alterlog')->getLastInsID();
            //设备变更表
            $ectype = [
                'imei'=>$info['n_imei'],
                'state'=>$info['passage'],
                'addtime'=>time(),
                'aid'=>$aid,
            ];
            $res = Db::name('alterlog_ectype')->insert($ectype);
            if ($result && $res) {
                Db::commit();
                return ['code'=>0,'msg'=>'提交成功'];
            }
            Db::rollback();
            return ['code'=>1,'msg'=>'提交失败'];
        } catch (Exception $e) {
            Db::rollback();
            Log::write('设备变更记录添加操作异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /*
    *设备变更记录首页
    */
    public function GetDeviceMaintainAllInfo(){
        try {
            $info = input();
            //创建时间搜索
            if ( (isset($info['start_create']) && $info['start_create']!=='') && (isset($info['end_create']) && $info['end_create']!=='')) {
                $where['addtime'] = ['between',[strtotime($info['start_create']),strtotime($info['end_create'])] ];
                $query['start_create']=$info['start_create'];
                $query['end_create']=$info['end_create'];
                $item_value[] = ['name'=>'create','item'=>'创建时间','value'=>$info['start_create'].'至'.$info['end_create']];

            }else if (isset($info['end_create']) && $info['end_create']!=='') {
               $where['addtime'] = ['lt',strtotime($info['end_create'])];
               $query['end_create']=$info['end_create'];
               $item_value[] = ['name'=>'end_create','item'=>'创建时间','value'=>$info['end_create']];

            }else if (isset($info['start_create']) && $info['start_create']!=='') {
               $where['addtime'] = ['lt',strtotime($info['start_create'])];
               $query['start_create']=$info['start_create'];
               $item_value[] = ['name'=>'start_create','item'=>'创建时间','value'=>$info['start_create']];
            }

            //设备厂商搜索
            if (isset($info['pid']) && $info['pid']!=='') {
                $where['pid'] = ['eq',$info['pid']];
                $query['pid']=$info['pid'];
                $pname = db::name('device_passage')->where('id',$info['pid'])->value('name');
                $item_value[] = ['name'=>'pid','item'=>'设备厂商','value'=>$pname];
            }

            //设备号码搜索
            if (isset($info['s_imei']) && $info['s_imei']!=='') {
                $did = db::name('device')->where('imei',$info['s_imei'])->value('id');
                $where['did'] = ['eq',$did];
                $query['s_imei']=$info['s_imei'];
                $item_value[] = ['name'=>'s_imei','item'=>'imei','value'=>$info['s_imei']];
            }
            $where['state'] = ['eq',3];
            $query['state'] = 3;
            $list = db::name('alterlog')->where($where)->order('addtime desc')->paginate(30,false,['query'=>$query]);
            $page = $list->render();
            $show = $list->toArray();

            foreach ($show['data'] as $key=>&$value) {
                //查询服务对象信息
                $tmp['b.id'] = ['eq',$value['cuid']];
                $userinfo = db::name('client b')
                ->field('b.name,b.id_number,b.mobile,b.sex,b.address')
                ->where($tmp)
                ->find();
                $value['name'] = $userinfo['name'];
                $value['id_number'] = $userinfo['id_number'];
                $value['sex'] = ($userinfo['sex'] == 1) ? '男' : '女';
                $value['address'] = $userinfo['address'];
                $value['pname'] = db::name('device_passage')->where('id',$value['pid'])->value('name');
                //查询该维修记录负责人信息
                if ($value['utype']==1) {
                    //查询话务员
                    $value['uname'] = db::name('staff_user')->where('work_number',$value['uid'])->find();
                }else{
                    //查询管理员
                    $value['uname'] = db::name('user')->where('id',$value['uid'])->value('name');
                }
                $value['type'] = ($value['type']==1) ? '未处理' : '已处理';

                //查询处理结果
                $condition['aid'] = ['eq',$value['id']];
                $log_res = db::name('settlelog')->where($condition)->order('addtime desc')->find();
                //最终备注信息
                $value['remarks'] = $log_res['remarks'];
                if ($log_res['utype']==2) {
                    $value['case_people'] =  db::name('user')->where('id',$log_res['uid'])->value('name');
                }else{
                    $value['case_people'] = db::name('staff_user')->where('work_number',$log_res['uid'])->find();
                }

                //查询附表记录
                $ec_imei = db::name('alterlog_ectype')->where('aid',$value['id'])->find();
                $value['state'] = $ec_imei['state'];
                $value['ec_imei'] = $ec_imei['imei'];
            }
            $arr = [
                'page'=>$page,
                'list'=>$show,
                'query'=>$query,
                'item'=>$item_value
            ];
            return $arr;
        } catch (Exception $e) {
            Log::write('设备变更列表查询异常：'.$e->getMessage(),'error');
            return fasle;
        }
    }

    /*
    *设备变更记录删除
    *return array
    */
    public function DevicechangeDel(){
        Db::startTrans();
        try {
            $id = input('id');
            $res = Db::name('alterlog')->where('id',$id)->delete();
            $result = Db::name('alterlog_ectype')->where('aid',$id)->delete();
            if ($result && $res) {
                Db::commit();
                return ['code'=>0,'msg'=>'删除成功'];
            }
            Db::rollback();
            return ['code'=>1,'msg'=>'删除失败'];
        } catch (Exception $e) {
            Db::rollback();
            Log::write('设备变更列表删除操作异常：'.$e->getMessage(),'error');
            return fasle;
        }
    }

    /*
    *设备变更记录修改
    *
    *
    */
    public function DevicechangeUpdate(){
        Db::startTrans();
        try {
            $info = input();
            /*
            *验证原始设备与新设备是否存在,且设备是否绑定过用户在使用
            */
            $where['imei'] = ['eq',$info['s_y_imei']];
            $y_info = Db::name('device')->field('uid,id')->where($where)->find();
            if (!$y_info['id']) {
                return ['code'=>1,'msg'=>$info['s_y_imei'].'未找到该设备,请核实设备imei号码'];
            }
            if (!$y_info['uid']) {
                return ['code'=>1,'msg'=>$info['s_y_imei'].'该设备未绑定用户'];
            }
            /*
            *验证新设备
            */
            $tmp['imei'] = ['eq',$info['s_n_imei']];
            $n_info = Db::name('device')->field('uid,id,pid')->where($tmp)->find();
            if (!$n_info['id']) {
                return ['code'=>1,'msg'=>$info['imei'].'未找到该设备,请核实设备imei号码'];
            }
            if ($n_info['uid']) {
                return ['code'=>1,'msg'=>$info['s_n_imei'].'该设备已经绑定用户在使用,如需重新发放,请先解绑'];
            }

            $usinfo = Session::get('S_USER_INFO');
            if ($usinfo['type']==1) {
                //管理员
                $arr['utype'] = 2;
                $arr['uid'] = $usinfo['id'];
            }else{
                //话务员
                $arr['utype'] = 1;
                $arr['uid'] = $usinfo['work_number'];
            }
            $arr['imei'] = $info['s_y_imei'];
            $arr['addtime'] = time();
            $arr['content'] = $info['s_comment'];
            $arr['cuid'] = $y_info['uid'];
            $arr['pid'] = $n_info['pid'];
            //产生勾选的反馈记录
            $result = Db::name('alterlog')->where('id',$info['h_id'])->update($arr);

            //设备变更表
            $ectype = [
                'imei'=>$info['s_n_imei'],
                'state'=>$info['passage'],
            ];
            $res = Db::name('alterlog_ectype')->where('aid',$info['h_id'])->update($ectype);
            if ($result!==false && $res!==false) {
                Db::commit();
                return ['code'=>0,'msg'=>'提交成功'];
            }
            Db::rollback();
            return ['code'=>1,'msg'=>'提交失败'];
        } catch (Exception $e) {
            Db::rollback();
            Log::write('设备变更记录编辑操作异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /*
    *变更记录导出
    */
    public function DevicechangeDownInfo(){
        try {
            $id = input('id');
            $where['pid'] = ['eq',$id];
            $where['state'] = ['eq',3];
            $data = db::name('alterlog')->where($where)->select();
            
            foreach ($data as $key=>&$value) {

                //变更前的IMEI
                $show[$key]['imei'] = $value['imei'];

                //绑定的用户信息
                $usinfo = db::name('client')->field('name,id_number,sex,address')->where('id',$value['cuid'])->find();
                $show[$key]['name'] = $usinfo['name'];
                $show[$key]['id_number'] = $usinfo['id_number'];
                $show[$key]['sex'] = ($usinfo['sex']==1) ? '男' : '女';
                $show[$key]['address'] = $usinfo['address'] ? $usinfo['address'] :  '--';
                $show[$key]['content'] = $value['content'];

                //变更后的IMEI
                $show[$key]['nimei']= db::name('alterlog_ectype')->where('aid',$value['id'])->value('imei');

                //跟进人员
                if ($value['utype']==2) {
                    $show[$key]['uname'] = db::name('user')->where('id',$value['uid'])->value('name');
                }else{
                    $show[$key]['uname'] = db::name('staff_user')->where('work_number',$value['uid'])->value('display_name');
                }
                $show[$key]['addtime'] = date('Y-m-d H:i:s',$value['addtime']);
            }
            unset($data);
            // excel的header头
            $header = ['IMEI','用户姓名', '身份证号码','性别','地址信息','描述','新IMEI','记录人','受理时间'];
            // 文件名
            $fileName = date('Y-m-d').'设备维修记录表格';
            $list=[
                'imei',
                'name',
                'id_number',
                'sex',
                'address',
                'content',
                'nimei',
                'uname',
                'addtime',
            ];
            F_export_excel($show, $header, $list, $fileName);
        } catch (Exception $e) {
            Log::write('设备变更记录导出操作异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }
}