<?php
/**
 *模型层公用方法
 */
namespace app\device\model;

use think\Model;
use think\Exception;
use think\Log;
use app\index\model\StaffGroup;
use app\index\model\Call;
use app\index\model\YiMi;
use think\Db;

class Common extends Model {
    
    /*
    *获取话务员信息
     *
    */
    public function getuser(){
    	try {
    		$gid = StaffGroup::getGroupId();
        	$code = Call::getSid();
        	$yimi = new YiMi($code['sid'], $code['token']);
        	$res = $yimi->getCallGroupUsers($gid);
        	$arr = $res['resp']['getGroupUsers']['Users'];
        	foreach ($arr as $key=>&$value) {
        	    $tmp['a.phone'] = ['eq',$value['mobile']];
        	    $tmp['a.work_number'] = ['eq',$value['workNumber']];
        	    $tmp['a.number'] = ['eq',$value['exNumber']];
        	    $number = db::name('staff_user a')->where($tmp)->value('number');
	
        	    //获取工单总数
        	    $count_number = db::name('work')->where('staff_number',$number)->count('id');
        	    //在线话务员
        	    if ($value['status']==1) {
        	        $data[$id] = $count_number;
        	        //按照工单总数排列
        	        krsort($data);
        	        return $number;die;
        	    }
        	    //振铃状态
        	    if ($value['status']==3) {
        	        $data[$id] = $count_number;
        	        //按照工单总数排列
        	        krsort($data);
        	        return $number;die;
        	    }
        	    //通话中状态
        	    if ($value['status']==4) {
        	        $data[$id] = $count_number;
        	        //按照工单总数排列
        	        krsort($data);
        	        return $number;die;
        	    }
        	    return $number;
        	}
    	} catch (Exception $e) {
    		Log::write(date('Y-m-d H:i:s').'获取话务员信息异常'.$e->getMessage(),'error');
    		return false;
    	}
    }
}