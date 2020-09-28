<?php
/**
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/3/21
 * Time: 上午10:06
 */

namespace app\api\model;


use think\Model;
use think\Db;

class ProviderStaff extends Model
{
    /**
     * 获取小程序请求数据
     * @param $wxData
     * @return mixed|\think\response\Json
     */
    public function Login($wxData)
    {
        $chat = new WxChat();
        $arr = $chat->WxLogin($wxData['code'],$wxData['signature'],$wxData['rawData'],$wxData['encryptedData'],$wxData['iv']);
        if($arr['status']){
            $mobile = $wxData['mobile'];
            $password = md5(md5($wxData['password']).'pension');
            $sql = "SELECT `id`,`name`,`mobile`,`nickname`,`pid`,`status` FROM yc_provider_staff
                  WHERE `state` = 1 AND `mobile`='".$mobile."' AND `password`='".$password."'";
            $user = Db::query($sql)[0];
            if(!$user){
                return ['code'=>1,'msg'=>'账号或密码错误'];
            }

            $staff = $this->field('id,open_id')->where(['open_id'=>$arr['openId'],'mobile'=>$mobile])->find();
            if($staff){
                $sign = md5($staff['id'].$staff['open_id'].time());
            }else{
                // 未授权用户 则更改信息
                $data = [
                    'id'  => $user['id'],
                    'image'=>$arr['avatarUrl'],
                    'open_id'=>$arr['openId'],
                    'nickname'=>$arr['nickName'],
                ];

                $this->update($data);
                $sign = md5($user['id'].$arr['openId'].time());
            }
            $this->update(array('sign'=>$sign,'id'=>$user['id']));

        }else{
            return ['code'=>1,'msg'=>$arr['msg']];
        }

        return ['code'=>0,'msg'=>'登陆成功','sign'=>$sign];
    }

    /**
     * 获取签名
     * @param $sign
     * @return array|false|\PDOStatement|string|Model
     */
    public function staffSign($sign)
    {
        $data = $this->field('id,name,mobile,nickname,pid,status')->where(['sign'=>$sign])->find();
        return $data;
    }
}