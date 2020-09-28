<?php
/**
 * 话务员模块
 * User: Administrator
 * Date: 2019/1/4
 * Time: 16:20
 */
namespace app\index\model;

use think\Db;
use think\Session;
use think\Model;
use think\Exception;
use think\Log;

class StaffUser extends Model
{
    /**
     * 获取话务员列表
     * @param array|string $where 搜索条件
     * @return array|mixed
     */
    public function getUserList($where=[], $query=[], $limit=20)
    {
        try {
            $data = Db::name('staff_user')
                ->field('number,display_name,phone,gid,rid,work_number,last_time,create_time')
                ->where($where)
                ->paginate($limit, false, ['query'=>$query]);
            $page = $data->render();
            $staff = $data->toArray();
            return ['staff'=>$staff,'page'=>$page];
        }catch (\Exception $e){
            Log::write('获取话务员异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 话务员登陆
     * @param $param
     * @return array
     */
    public function login($param)
    {
        try {
            $password = md5(md5($param['password']).'pension');
            $number = 'YC'.$param['name'];
            $sql = "SELECT `display_name`,`phone`,`gid`,`rid`,`create_time`,`work_number`,`number` FROM yc_staff_user WHERE `work_number`=? AND `password`=?";
            $user = Db::query($sql,[$number,$password]);
            
            if(empty($user)){
                return ['code'=>1,'msg'=>'账号或密码错误'];
            }
            $user[0]['type'] = 2;
            $user[0]['r_id'] = $user[0]['rid'];
            $user[0]['name'] = $user[0]['display_name'];
            //状态为话务员推送
            $ymi = [
                'workNumber'=>$user[0]['work_number'],
                'gid'=>$user[0]['gid'],
                'type'=>'0',
                'deviceNumber'=>'',
            ];
            //请求接口
            $code = Call::getSid();
            $obj = new YiMi($code['sid'], $code['token']);
            $ym_res = $obj ->signIn($ymi);
            if ($ym_res['resp']['respCode'] !== 0) {
                return ['code'=>1,'msg'=>$obj->getMsgError($ym_res['resp']['respCode'])];
            }
            $time = time();
            $this->where(['work_number'=>$number,'password'=>$password])->update(['last_time'=>$time]);

            // 存入session
            Session::set('S_USER_INFO',$user[0]);
            return ['code'=>0,'msg'=>'登录成功'];
        } catch (\Exception $e) {
            Log::write('系统管理员登录异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 退出登陆
     * @param $number
     * @return mixed
     */
    public function logout($number)
    {
        try {
            $work_number = $this->where('number',$number)->value('work_number');
            $code = Call::getSid();
            $OBJ = new YiMi($code['sid'], $code['token']);
            $OBJ->signOff($work_number);

        }catch (\Exception $e){
            Log::write('退出登陆异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 添加话务员
     * @param $param
     * @return array
     */
    public function addStaffUser($param)
    {
        try {
            $validate = validate('StaffUser');
            if(!$validate->check($param)){
                $call = ['code'=>1,'msg'=>$validate->getError()];
                return $call;
            }

            $code = Call::getSid();

            $YiMi = new YiMi($code['sid'], $code['token']);
            $data = [
                'workNumber' => $param['work_number'],
                'phone' => $param['phone'],
                'displayName' => $param['display_name'],
                'password' => $param['password'],
                'number' => ltrim($param['work_number'], 'YC'),
            ];
            $res = $YiMi->createUser($data);//添加话务员

            if ($res['resp']['respCode'] === 0) {
                $data = [
                    'gid'=>$param['gid'],
                    'phone'=>$param['phone']
                ];

                $YiMi->addGroupUser($data);//添加技能组

                $param['create_time'] = time();
                $param['password'] = md5(md5($param['password']).'pension');
                $param['number'] = intval(ltrim($param['work_number'], 'YC'));
                $res = $this->insert($param);
                Call::saveNumber();
                if ($res){
                    $call = [ 'code'=>0,'msg'=>'添加成功'];
                    return $call;
                }
            }
            $msg = $YiMi->getMsgError($res['resp']['respCode']);
            return [ 'code'=>1,'msg'=>$msg];

        }catch (\Exception $e){
            Log::write('添加话务员异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }

    /**
     * 获取坐席信息
     * @param $number
     * @return array|false|\PDOStatement|string|Model
     */
    public function getUser($number)
    {
        try {
            $code = Call::getSid();
            $YiMi = new YiMi($code['sid'], $code['token']);
            $user = $this->where(['number' => $number])->find();
            $user['group'] = StaffGroup::where(['gid' => $user['gid']])->value('name');

            $state = $YiMi->getUserState($user['work_number']);
            $times = $state['resp']['getUserState']['statusDuration'];

            switch ($state['resp']['getUserState']['status']){
                case 0:
                    $user['status'] = '未签入';
                    break;
                case 1:
                    $user['status'] = '空闲';
                    break;
                case 2:
                    $user['status'] = '忙碌';
                    break;
                case 3:
                    $user['status'] = '振铃';
                    break;
                case 4:
                    $user['status'] = '通话中';
                    break;
                case 5:
                    $user['status'] = '话后处理';
                    break;
            }
            $str = F_callTime($times);

            $user['statusDuration'] = $str;

            return $user;
        }catch (\Exception $e){
            Log::write('获取坐席信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取编辑坐席信息
     * @param $number
     * @return array|false|\PDOStatement|string|Model
     */
    public function getEditUser($number)
    {
        try {
            $user = $this->where(['number'=>$number])->find();

            $group = StaffGroup::getUserGroup();
            $str = '';
            foreach ($group as $val){
                if ($user['gid'] == $val['gid']){
                    $str .= '<option value='.$val['id'].' selected="selected" >'.$val['name'].'</option >';
                }else{
                    $str .= '<option value='.$val['id'].' >'.$val['name'].'</option >';
                }
            }
            $user['group'] = $str;
            return $user;
        }catch (\Exception $e){
            Log::write('获取编辑坐席信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 编辑话务员
     * @param $param
     * @return array
     */
    public function editStaff($param)
    {
        try {

        $validate = validate('StaffUser');
            if(!$validate->check($param)){
                $call = ['code'=>1,'msg'=>$validate->getError()];

                return $call;
            }


            $code = Call::getSid();

            $YiMi = new YiMi($code['sid'],$code['token']);

            $YiMi->updateUser($param['work_number'],$param['display_name']);
            $YiMi->updatePassword($param['work_number'],$param['password']);
            $YiMi->updateUserPhone($param['work_number'],$param['phone']);
            unset($param['work_number']);
            $param['password'] = md5(md5($param['password']).'pension');
            $this->update($param);
            return [ 'code'=>0,'msg'=>'修改成功'];

        }catch (\Exception $e){
            Log::write('编辑话务员异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 删除话务员
     * @param $param
     * @return array
     */
    public function delStaffUser($param)
    {

        try {
            $code = Call::getSid();

            $YiMi = new YiMi($code['sid'],$code['token']);
            $data = [
                'gid'=>$param['gid'],
                'phone'=>$param['phone'],
            ];
            $res = $YiMi->deleteGroupUser($data);
            if ($res['resp']['respCode'] === 0){
                $res = $YiMi->dropUser($param['phone']);
                if ($res['resp']['respCode'] === 0){
                    $del = $this->where(['number'=>$param['number']])->delete();
                    if ($del){
                        $data = ['code'=>0, 'msg'=>'删除成功'];
                        return $data;
                    }
                }
            }
            $msg = $YiMi->getMsgError($res['resp']['respCode']);
            return [ 'code'=>1,'msg'=>$msg];

        }catch (\Exception $e){
            Log::write('删除话务员异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }

    /**
     * 获取话务员
     * @param $number
     * @return array|false|\PDOStatement|string|Model
     */
    static public function getWorkStaff($number)
    {
        $staff = self::field('number,display_name')->where(['number' => $number])->find();

        return $staff;
    }

    /**
     * 获取话务员信息
     * @return false|array
     */
    static public function getStaffUser()
    {
        try {
            $sql = "SELECT `number`,`display_name` FROM yc_staff_user ";
            $staff = Db::query($sql);
            return $staff;
        } catch (\Exception $e) {
            Log::write('获取话务员信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取技能组用户列表
     * @return mixed
     */
    static public function getCallStaffState(){
        try {
            $code  = Call::getSid();
            $YiMi  = new YiMi($code['sid'],$code['token']);
            $user = Db::name('staff')->field('work_number,number')->select();
            $staff = [];
            foreach ($user as $key=>$val){
                $res = $YiMi->getUserState($val['work_number']);

                $staff[] = [
                    'work_number'=>$val['work_number'],
                    'exNumber'=>$val['number'],
                    'status'=>$res['resp']['getUserState']['status'],
                ];
            }

            return $staff;
        } catch (\Exception $e) {
            Log::write('获取技能组用户列表信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取签入坐席
     * @return array
     */
    public function getCallStaff()
    {
        try {
            $staff = self::getCallStaffState();

            $number = [];
            foreach ($staff as $val){
                if ($val['status'] != 0){
                    $number[] = $val['exNumber'];
                }
            }
            return $number;
        } catch (\Exception $e) {
            Log::write('获取签入坐席信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }

//    /**
//     * 根据分机号获取对应的id
//     * @param $number
//     * @return mixed
//     */
//    static public function getNumber($number)
//    {
//
//        try {
//            $staff = self::where(['number'=>$number])->value('number');
//
//            return $staff;
//        } catch (\Exception $e) {
//            Log::write('根据分机号获取对应的id信息异常：'.$e->getMessage(),'error');
//            return ['code'=>-1,'msg'=>'服务器异常'];
//        }
//
//    }

}