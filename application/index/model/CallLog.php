<?php
/**
 * 呼叫通话记录模块
 * User: Hongfei
 * Date: 2019/1/17
 * Time: 下午1:47
 */

namespace app\index\model;

use think\Model;
use think\Log;

class CallLog extends Model
{

    /**
     * 获取录音
     * @param $id
     * @return array
     */
    public function getAudio($id)
    {
        try {
            $yimi = new YiMi();

            $callId = $this->where(['id'=>$id])->value('call_id');
            $call = $yimi->callRecordUrl($callId);

            if ($call['resp']['respCode'] === 0){
                return ['code'=>0,'msg'=>'获取成功','data'=>$call['resp']['callRecordUrl']['url']];
            }
            return ['code'=>1,'msg'=>$yimi->getMsgError($call['resp']['respCode'])];
        }catch (\Exception $e){
            Log::write('获取录音异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取通话历史记录
     * @param $call_id
     * @return array|false|\PDOStatement|string|Model
     */
    public function getCallLog($call_id)
    {
        try {
            $data = $this->where(['call_id'=>$call_id])
                ->order('id desc')->find();
            return $data;
        }catch (\Exception $e){
            Log::write('获取通话历史记录异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 回调添加记录
     * @param $param
     * @return array
     */
    public function addCallLog($param)
    {
        try {
            $this->insert($param);
        }catch (\Exception $e){
            Log::write('回调添加记录异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }

}