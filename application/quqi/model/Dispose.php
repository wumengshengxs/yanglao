<?php
namespace app\quqi\model;


use think\Db;
use think\Exception;
use think\Log;
use think\Model;

/**
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/8/15
 * Time: 下午1:27
 */
class Dispose extends Model
{

    /**
     * 定位处理
     * @param $param
     * @return bool
     */
    public function location($param)
    {
        try {
            $imei = $param['imei'];
            $uid = $this->getUid($imei);
            if(empty($uid)){
                return false;
            }
            //获取转换后的经纬度
            $encrypt = F_bd_encrypt($param['lon'],$param['lat']);

            $data = [
                'imei'=>$imei,
                'location_type'=>$param['type'],
                'addtime'=>time(),
                'address'=>$param['address'],
                'text'=>$encrypt['bd_lon'].','.$encrypt['bd_lat'],
                'uid'=>$uid,
            ];

            $fence = Db::name('fence')->field('')->where(['imei'=>$imei])->find();
            if (!empty($fence)){
                $check_res  = $this->ismap($encrypt['bd_lon'],$encrypt['bd_lat'],$fence);
                if (!$check_res){
                    //生成越界工单
                    $warning = [
                        'staff_number' => 1009,
                        'client_id' => $uid,
                        'type' => '2',
                        'lat' => $encrypt['bd_lat'],
                        'lng' => $encrypt['bd_lon'],
                        'address' => $param['address'],
                        'location_type' => $param['type'],
                        'create_time' => time(),
                        'alarm_time'=>time(),
                        'state' => '2',
                    ];
                    Db::startTrans();
                    try{
                        $wid = Db::name('work')->insertGetId($warning);

                        $log_arr = [
                            'w_id'=>$wid,
                            'remarks'=>'系统接到越界报警消息自动创建工单',
                            'type' => 1,
                            'create_time'=>time(),
                        ];
                        db::name('work_log')->insert($log_arr);
                        // 提交事务
                        Db::commit();
                    } catch (\Exception $e) {
                        // 回滚事务
                        Db::rollback();
                    }

                }
            }

            Db::name('gpslog')->insert($data);

        } catch (Exception $e) {
            Log::write('曲奇定位处理异常'.$e->getMessage(),'error');
        }
    }

    /**
     * sos报警
     * @param $param
     * @return bool
     */
    public function sosData($param)
    {
        try {
            $imei = $param['imei'];
            $uid = $this->getUid($imei);
            if(empty($uid)){
                return false;
            }
            $encrypt = F_bd_encrypt($param['lon'],$param['lat']);
            $data = [
                'staff_number'=>1009,
                'client_id'=>$uid,
                'type'=>1, //sos
                'state'=>2, //工单状态
                'location_type'=>$param['type'],
                'lng'=>$encrypt['bd_lon'],
                'lat'=>$encrypt['bd_lat'],
                'address'=>$param['address'],
                'alarm_time'=>strtotime($param['time_begin']),
                'create_time'=>time(),
            ];

            Db::startTrans();
            try{
                $wid = db::name('work')->insertGetId($data);
                $log_arr = [
                    'w_id'=>$wid,
                    'remarks'=>'系统接收到报警消息自动创建工单',
                    'create_time'=>time(),
                ];
                db::name('work_log')->insert($log_arr);
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }


        } catch (Exception $e) {
            Log::write('曲奇sos报警处理异常'.$e->getMessage(),'error');
        }

    }

    /**
     * 计步
     * @param $param
     * @return bool
     */
    public function step($param)
    {
        try {
            $imei = $param['imei'];
            $uid = $this->getUid($imei);
            if(empty($uid)){
                return false;
            }
            $data = [
                'imei'=>$imei,
                'addtime'=>time(),
                'uid'=>$uid,
                'steep'=>$param['value'],
            ];
            Db::name('health')->insert($data);

        } catch (Exception $e) {
            Log::write('曲奇计步处理异常'.$e->getMessage(),'error');
        }
    }

    /**
     * 获取电量
     * @param $param
     * @return mixed
     */
    public function electric($param)
    {
        try {
            $imei = $param['imei'];
            Db::name('device')
                ->where(['imei'=>$imei])
                ->update(['electric'=>$param['remaining_power']]);
        } catch (Exception $e) {
            Log::write('曲奇获取uid异常'.$e->getMessage(),'error');
        }
    }

    /**
     * 获取uid
     * @param $imei
     * @return mixed
     */
    public function getUid($imei)
    {
        try {
            $uid = Db::name('device')->where(['imei'=>$imei])->value('uid');
            return $uid;
        } catch (Exception $e) {
            Log::write('曲奇获取uid异常'.$e->getMessage(),'error');
        }
    }

    /**
     * 是否超过围栏
     * @param $lng
     * @param $lat
     * @param $data
     * @return bool
     */
    public function ismap($lng,$lat,$data)
    {
        $obj = new Convert();
        $point = ['lng'=>(float)$lng,'lat'=>(float)$lat];
        $circle = [
            'center'=>['lng'=>(float)$data['lng'],'lat'=>(float)$data['lat']],
            'radius'=>(float)$data['radius'],
        ];
        $bool = $obj->is_point_in_circle($point,$circle);
        return $bool;
    }



}