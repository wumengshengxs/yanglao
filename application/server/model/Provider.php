<?php

/**
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/3/15
 * Time: 上午9:03
 */
namespace app\server\model;

use think\Model;
use think\Db;
use think\Session;
use think\Log;

class Provider extends Model
{

    /**
     * 系统管理员登录
     * @param string $name 登录账号
     * @param string $password 登录密码
     * @return array ['code'=>0,'msg'=>'登陆结果']
     */
    public function login($name, $password)
    {
        try {
            $password = md5(md5($password).'pension');
            $sql = "SELECT `id`,`name`,`company`,`mobile` FROM yc_provider WHERE `name`='".$name."' AND `password`='".$password."'";
            $user = Db::query($sql);
            if(!$user){
                return ['code'=>1,'msg'=>'账号或密码错误'];
            }

            Session::set('provider_info',$user[0]);
            return ['code'=>0,'msg'=>'登录成功'];
        } catch (\Exception $e) {
            Log::write('服务商登录异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取人员选择服务类目列表
     */
    public function projectList()
    {
        try {
            $sql = "SELECT `id`,`name`,`pid` FROM yc_provider_project WHERE `pid`!= 0";
            $project = Db::query($sql);

            return $project;
        } catch (\Exception $e) {
            Log::write('人员选择服务类目列表：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }


}








