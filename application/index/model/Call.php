<?php
/**
 * 易米云对接模块
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/4
 * Time: 16:20
 */
namespace app\index\model;

use think\Db;
use think\Log;
use think\Model;

class Call extends Model
{

    /**
     * 获取子账户列表
     * @return array|false|\PDOStatement|string|\think\Collection
     */
    public function getCallList()
    {
        try {
            $data =  $this->select();
            return $data;
        }catch (\Exception $e){
            Log::write('获取子账户列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }

    /**
     *
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     */
    public function getCall($id)
    {
        try {
            $data = $this->field('id,nickname,mobile,email,sid,token,limit_type,day_limit,week_limit,month_limit')
                ->where(['id'=>$id])->find();
            return $data;
        }catch (\Exception $e){
            Log::write('获取更新信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取最大工号
     * @param int $id
     * @return mixed
     */
    static public function getNumber($id = 1)
    {
        try {
            $sql = "SELECT `big_number` FROM yc_call where id = ".$id;
            $number = Db::query($sql);
            return $number[0]['big_number'];

        }catch (\Exception $e){
            Log::write('获取最大工号异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 更新子账户
     * @param $param
     * @return array
     */
    public function editCall($param)
    {
        try {
            $validate = validate('Call');
            if(!$validate->scene('editCall')->check($param)){
                $call = ['code'=>0,'msg'=>$validate->getError()];
                return $call;
            }

            $data = [
                'subAccountSid'=>$param['sid'],
                'mobile'=>$param['mobile'],
                'email'=>$param['email'],
                'nickName'=>$param['nickname'],
            ];

            $YiMi = new YiMi();

            $res = $YiMi->updateSubAccount($data);
            if ($res['resp']['respCode'] === 0){

                $param['token'] = $res['resp']['updateSubAccount']['subAccountToken'];
                $res = $this->update($param);
                if ($res){
                    $call = [ 'code'=>1,'msg'=>'修改成功'];
                    return $call;
                }
            }

            $msg = $YiMi->getMsgError($res['resp']['respCode']);
            $call = [ 'code'=>0,'msg'=>$msg];
            return $call;

        }catch (\Exception $e){
            Log::write('更新子账户异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }

    /**
     * 更新呼叫限制
     * @param $param
     * @return array|mixed
     */
    public function editCallLimit($param)
    {
        try {
            $validate = validate('Call');
            if(!$validate->scene('editCallLimit')->check($param)){
                $res = ['code'=>0,'msg'=>$validate->getError()];
                return $res;
            }

            $data = [
                'limitType'=>$param['limit_type'],
                'dayLimit'=>$param['day_limit'],
                'weekLimit'=>$param['week_limit'],
                'monthLimit'=>$param['month_limit'],
            ];

            $YiMi = new YiMi();

            $res = $YiMi->setCallLimit($data);
            if ($res['resp']['respCode'] === 0){
                $call = $this->update($param);
                if ($call){
                    $res = [ 'code'=>1,'msg'=>'修改成功'];
                }
                return $res;
            }

            $msg = $YiMi->getMsgError($res['resp']['respCode']);
            $res = [ 'code'=>0,'msg'=>$msg];
            return $res;
        }catch (\Exception $e){
            Log::write('更新呼叫限制异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }

    /**
     * @param int $id
     * 获取子账号和token
     * @return array|false|\PDOStatement|string|Model
     */
    static  public function getSid($id = 1)
    {
        try {
            $data = self::field('sid,token')
                ->where(['id'=>$id])->find();

            return $data;
        }catch (\Exception $e){
            Log::write('获取子账号和token异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }

    /**
     * 工号+1
     * @param int $id
     * @return array
     */
    static public function saveNumber($id = 1)
    {
        try {
            self::where(['id'=>$id])->setInc('big_number', 1);
        }catch (\Exception $e){
            Log::write('工号自增异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }



}