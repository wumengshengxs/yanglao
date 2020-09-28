<?php
namespace app\index\controller;

use app\index\model\CallLog;
use app\index\model\StaffGroup;
use app\index\model\StaffUser;
use app\index\model\Watches;
use app\index\model\Order;
use app\index\model\Call;
use app\index\model\Work;
use think\Controller;
use app\index\model\YiMi;
use think\Request;
use think\Db;

class Api extends Controller
{

    /**
     * 呼叫失败&未挂机通知回调
     * @return string
     */
    public function callHangup()
    {
        $data = file_get_contents('php://input');
        libxml_disable_entity_loader(true);
        $xmlString = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        $attr = json_decode(json_encode($xmlString), true);
        $param = [
            'call_id' => $attr['callId'],
            'caller' => $attr['caller'],
            'called' => $attr['mobile'],
            'state' => $attr['state'],
            'start_time' => strtotime($attr['startTime']),
            'stop_time' => strtotime($attr['stopTime']),
            'duration'=>$attr['duration'],
            'ring_duration'=>$attr['ringDuration'],
            'number'=>$attr['number'],
            'work_number'=>$attr['workNumber'],
            'type'=>$attr['type'],
            'create_time'=>time(),
        ];
        $model = new CallLog();
        $model->addCallLog($param);

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response><retcode>00000</retcode><reason>获取成功</reason></response>';

    }

    /**
     *  呼叫请求或振铃回调
     */
    public function callReq()
    {
        $data = file_get_contents('php://input');
        libxml_disable_entity_loader(true);
        $xmlString = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        $attr = json_decode(json_encode($xmlString), true);

        //type == 5电话呼入创建紧急工单
        if ($attr['callType'] == 5){
            //查找日志有该通话记录则不执行添加
            $callid = db::name('orderlog')->where(['callid'=>$attr['callId']])->value('callid');
            if (!empty($callid)){
                return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?> <response><retcode>00000</retcode><reason>已接收</reason></response>';
            }
            //如果接口未返回坐席则获取随机在线坐席
            if (empty($attr['number'])){
                $staff = new StaffUser();
                $number = $staff->getCallStaff();
                if (empty($number)){
                    //没有在线坐席 则随机分配
                    $staff = StaffUser::getStaffUser();
                    $ids =  array_column($staff, 'id');
                    $sid = $ids[array_rand($ids)];
                }else{
                    $number = $number[array_rand($number)];
                    $sid = StaffUser::getNumber($number);
                }

            }else{
                $sid = StaffUser::getNumber($attr['number']);
            }

            //如果是腕表呼叫则是紧急呼叫否则为其他
            $uid = 0;
            $type = 4;
            $msi = Watches::getMsisdn($attr['caller']);
            if (!empty($msi)){
                $type = 1;
                $uid = $msi;
            }
            $param = [
                'ouid'=>$sid,
                'uid'=>$uid,
                'imei'=>'',
                'type'=> $type,
                'addtime'=>time(),
            ];
            $work = new Work();
            $work->addCallOrder($param,$attr['callId']);

        }
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?> <response><retcode>00000</retcode><reason>已接入</reason></response>';
    }

    /**
     * 呼叫建立接口
     */
    public function callEstablish()
    {
        $data = file_get_contents('php://input');
        libxml_disable_entity_loader(true);
        $xmlString = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        $attr = json_decode(json_encode($xmlString), true);
        $attr['create_time'] = date('Y-m-d H:i:s');

//        file_put_contents('/home/wwwroot/ylpt/test/lish.txt',var_export($attr,true));

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?> <response><retcode>00000</retcode><reason>已接入</reason></response>';

    }

    /**
     * 呼叫事件通知回调
     */
    public function callEvent()
    {
        $data = file_get_contents('php://input');
        libxml_disable_entity_loader(true);
        $xmlString = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        $attr = json_decode(json_encode($xmlString), true);
        $attr['create_time'] = date('Y-m-d H:i:s');

//        file_put_contents('/home/wwwroot/ylpt/test/event.txt',var_export($attr,true));

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?> <response><retcode>00000</retcode> </response>';

    }


    /*
    *为gatewayworker服务器返回话务员ID
    */
    public function getuser(){
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
    }
}