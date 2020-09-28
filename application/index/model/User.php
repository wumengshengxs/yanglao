<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/2
 * Time: 9:35
 * 系统管理员
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;
use think\Session;

class User extends Common {
    /**
     * 获取管理员列表
     */
    public function userList()
    {
        try {
            $sql = "SELECT u.`id`,u.`name` AS `u_name`,u.`mobile`,u.`email`,u.`u_status`,u.`r_id`,u.`remarks`,u.`create_time`,r.`name` AS r_name FROM yc_user AS u LEFT JOIN yc_role AS r ON u.`r_id`=r.`id` ORDER BY u.`id` ASC";
            $user = Db::query($sql);
            foreach($user as &$value){
                $value['u_status'] = ($value['u_status'] == 1) ? '启用' : '停用' ;
                $value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
            }
            return $user;
        } catch (\Exception $e) {
            Log::write('管理员列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 提交管理员信息
     * @param int $id 管理员id
     * @param string $name 管理员账号
     * @param string $password 管理员登录密码
     * @param int $role 管理员角色
     * @param int $mobile 管理员手机号
     * @param string $email 管理员邮箱
     * @param string $remarks 备注说明
     * @param int $status 1：启用:；2：禁用
     * @return array ['code'=>0,'msg'=>'提交结果']
     */
    public function submitUser($id, $name, $password, $role, $mobile, $email, $remarks, $status)
    {
        try {
            // 管理员账号唯一
            $where = " `name`='".$name."'";
            $where .= $id ? " AND `id`!=".$id : '' ;
            $sql_uniq = "SELECT `id` FROM yc_user WHERE ".$where;
            $uniq = Db::query($sql_uniq);
            if($uniq){
                return ['code'=>1,'msg'=>'管理员账号重复'];
            }
            $password = $password ? md5(md5($password).'pension') : '';
            if($id){
                return $this->updateUser($id, $password, $role, $mobile, $email, $remarks, $status);
            }
            return $this->addUser($name, $password, $role, $mobile, $email, $remarks, $status);
        } catch (\Exception $e) {
            Log::write('提交管理员信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 新增管理员
     * @param string $name 管理员账号
     * @param string $password 管理员登录密码
     * @param int $role 管理员角色
     * @param int $mobile 管理员手机号
     * @param string $email 管理员邮箱
     * @param string $remarks 备注说明
     * @param int $status 1：启用:；2：禁用
     * @return array ['code'=>0,'msg'=>'新增结果']
     */
    protected function addUser($name, $password, $role, $mobile, $email, $remarks, $status)
    {
        try {
            $time = time();
            $sql = "INSERT INTO yc_user (`name`,`password`,`mobile`,`email`,`r_id`,`u_status`,`remarks`,`create_time`) VALUES ('".$name."','".$password."','".$mobile."','".$email."',".$role.",".$status.",'".$remarks."',".$time.")";
            $insert = Db::execute($sql);
            if(!$insert){
                return ['code'=>2,'msg'=>'新增管理员失败'];
            }
            return ['code'=>0,'msg'=>'新增管理员成功'];
        } catch (\Exception $e) {
            Log::write('新增管理员异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 更新系统管理员
     * @param int $id 管理员id
     * @param string $password 管理员登录密码
     * @param int $role 管理员角色
     * @param int $mobile 管理员手机号
     * @param string $email 管理员邮箱
     * @param string $remarks 备注说明
     * @param int $status 1：启用:；2：禁用
     * @return array ['code'=>0,'msg'=>'更新结果']
     */
    protected function updateUser($id, $password, $role, $mobile, $email, $remarks, $status)
    {
        try {
            $set = $password ? ",`password`='".$password."'" : "" ;
            $sql = "UPDATE yc_user SET `r_id`=".$role.",`mobile`='".$mobile."',`email`='".$email."',`remarks`='".$remarks."',`u_status`=".$status.$set." WHERE `id`=".$id;
            $update = Db::execute($sql);
            if($update === false){
                return ['code'=>2,'msg'=>'更新管理员失败'];
            }
            return ['code'=>0,'msg'=>'更新管理员成功'];
        } catch (\Exception $e) {
            Log::write('更新管理员异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

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
            $sql = "SELECT `id`,`name`,`mobile`,`email`,`r_id`,`is_super`,`remarks`,`create_time` FROM yc_user WHERE `name`='".$name."' AND `password`='".$password."'";
            $user = Db::query($sql);
            if(!$user){
                return ['code'=>1,'msg'=>'账号或密码错误'];
            }
            // 存入session
            $user[0]['type'] = 1;
            Session::set('S_USER_INFO',$user[0]);
            return ['code'=>0,'msg'=>'登录成功'];
        } catch (\Exception $e) {
            Log::write('系统管理员登录异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

}
