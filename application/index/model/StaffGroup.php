<?php
/**
 * 服务对象分组
 * User: Administrator
 * Date: 2019/1/4
 * Time: 16:20
 */
namespace app\index\model;

use think\Db;
use think\Log;
use think\Model;

class StaffGroup extends Model
{
    /**
     * 获取展示技能组
     * @return \think\Paginator
     */
    public function groupList()
    {
        try {
            $data = $this
                ->order('create_time desc')
                ->paginate(30);



            return $data;
        }catch (\Exception $e){
            Log::write('获取展示技能组异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }

    /**
     * 获取全部技能组
     * @return array|\think\Paginator
     */
    static function getUserGroup()
    {
        try {
            $sql = "SELECT `name`,`gid` FROM yc_staff_group where state = 1";

            $group = Db::query($sql);

            return $group;

        }catch (\Exception $e){
            Log::write('获取全部技能组：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 添加技能组
     * @param $param
     * @return array
     */
    public function addGroup($param)
    {
        try {
            $validate = validate('StaffGroup');
            if(!$validate->check($param)){
                $call = ['code'=>1,'msg'=>$validate->getError()];
                return $call;
            }

            $gid = $this->getGroupId();

            if (!empty($gid)){
                return  ['code'=>1,'msg'=>'已有启用中的技能组'];

            }

            $code = Call::getSid();
            $YiMi = new YiMi($code['sid'],$code['token']);
            $groupName = $param['name'];
            $res = $YiMi->createGroup($groupName);
            if ($res['resp']['respCode'] === 0){
                $param['create_time'] = time();
                $param['gid'] = $res['resp']['createGroup']['gid'];
                $res = $this->insert($param);
                if ($res){
                    $call = [ 'code'=>0,'msg'=>'添加成功'];
                    return $call;
                }
            }
            $msg = $YiMi->getMsgError($res['resp']['respCode']);
            $call = [ 'code'=>1,'msg'=>$msg];

            return $call;
        }catch (\Exception $e){
            Log::write('添加技能组异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }

    /**
     * 获取启用中的技能组id
     * @return mixed
     */
    static public function getGroupId()
    {
        $gid = self::where(['state'=>1])->value('gid');

        return $gid;
    }

    /**
     * 删除技能分组
     * @param $id
     * @return array
     */
    public function delGroup($id)
    {
        try {
            $code = Call::getSid();

            $YiMi = new YiMi($code['sid'],$code['token']);

            $res = $YiMi->deleteGroup($id);
            if ($res['resp']['respCode'] === 0){
                $del = $this->where(['gid'=>$id])->delete();
                if ($del){
                    $data = ['code'=>0, 'msg'=>'删除成功'];
                    return $data;
                }
            }
            $msg = $YiMi->getMsgError($res['resp']['respCode']);
            $data = [ 'code'=>1,'msg'=>$msg];

            return $data;

        }catch (\Exception $e){
            Log::write('删除技能组异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }
}