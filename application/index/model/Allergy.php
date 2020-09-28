<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/15
 * Time: 14:55
 * 服务对象过敏药物
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;

class Allergy extends Common {
    /**
     * 获取服务对象的过敏药物
     * @param int $uid 服务对象id
     * @return array $allergy 过敏药物信息
     */
    public function allergyInfo($uid)
    {
        try {
            $sql = "SELECT `medicine` FROM yc_client_allergy WHERE `cid`=".$uid;
            $allergy = Db::query($sql);
            return $allergy;
        } catch (\Exception $e) {
            Log::write('获取服务对象过敏药物异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 提交过敏药物信息
     * @param int $uid 服务对象id
     * @param array $medicine 过敏药物数组信息['青霉素','动物血清',...]
     * @return array ['code'=>0,'msg'=>'提交结果']
     */
    public function submitAllergy($uid, $medicine=[])
    {
        try {
            $is_exit = model('Client')->userInfo($uid);
            if(!$is_exit){
                return ['code'=>1,'msg'=>'服务对象不存在'];
            }
            Db::startTrans();
            // 删除原有的数据
            $sql_del = "DELETE FROM yc_client_allergy WHERE `cid`=".$uid;
            $delete = Db::execute($sql_del);
            // 新增新的数据
            $new_allergy = true;
            if(!empty($medicine)){
                $time = time();
                $sql_new = "INSERT INTO yc_client_allergy (`cid`,`medicine`,`create_time`) VALUES ";
                foreach($medicine as $value){
                    $sql_new .= "(".$uid.",'".addslashes($value)."',".$time."),";
                }
                $sql_new = rtrim($sql_new,',');
                $new_allergy = Db::execute($sql_new);
            }
            if($delete !== false && $new_allergy){
                Db::commit();
                return ['code'=>0,'msg'=>'保存成功'];
            }
            Db::rollback();
            return ['code'=>2,'msg'=>'保存失败'];
        } catch (\Exception $e) {
            Log::write('保存过敏药物异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

}