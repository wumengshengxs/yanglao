<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/15
 * Time: 11:03
 * 服务对象的病历
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;

class Caserecord extends Common {
    /**
     * 病历存档列表
     * @param int $uid 服务对象id
     * @return array ['case'=>'病例列表','page'=>'分页信息']
     */
    public function caseRecordList($uid)
    {
        try {
            $where['cid'] = ['eq',$uid];
            $data = Db::name('client_case')
                ->field('id,institution,case_time,image,remarks')
                ->where($where)
                ->order('id desc')
                ->paginate(20,false);
            $page = $data->render();
            $case = $data->toArray();
            foreach($case['data'] as &$value){
                $value['case_time'] = date('Y-m-d',$value['case_time']);
                $value['image'] = json_decode($value['image'],true);
                $value['count_img'] = count($value['image']);
            }
            return ['case'=>$case,'page'=>$page];
        } catch (\Exception $e) {
            Log::write('病历存档列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }
    
    /**
     * 提交服务对象的病历信息
     * @param int $uid 服务对象id
     * @param int $id 病历记录id
     * @param string $institution 医院
     * @param string $case_time 检查时间
     * @param string $image 病例图片，json格式
     * @param string $remarks 备注
     * @return array ['code'=>0,'msg'=>'提交结果']
     */
    public function submitCaseRecord($uid, $id, $institution, $case_time, $image, $remarks)
    {
        try {
            $is_exit = model('Client')->userInfo($uid);
            if(!$is_exit){
                return ['code'=>1,'msg'=>'服务对象不存在'];
            }
            $time = time();
            if($id){
                return $this->updateCaseRecord($uid, $id, $institution, $case_time, $image, $remarks, $time);
            }
            return $this->addCaseRecord($uid, $institution, $case_time, $image, $remarks, $time);
        } catch (\Exception $e) {
            Log::write('提交病历信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 新增服务对象的病历
     * @param int $uid 服务对象id
     * @param string $institution 医院
     * @param string $case_time 检查时间
     * @param string $image 病例图片，json格式
     * @param string $remarks 备注
     * @param string $time 添加时间
     * @return array ['code'=>0,'msg'=>'新增结果']
     */
    protected function addCaseRecord($uid, $institution, $case_time, $image, $remarks, $time)
    {
        try {
            $sql = "INSERT INTO yc_client_case (`cid`,`institution`,`case_time`,`image`,`remarks`,`create_time`) VALUES (".$uid.",'".$institution."',".$case_time.",'".$image."','".$remarks."',".$time.")";
            $add = Db::execute($sql);
            if(!$add){
                return ['code'=>2,'msg'=>'新增病历失败'];
            }
            return ['code'=>0,'msg'=>'新增病历成功'];
        } catch (\Exception $e) {
            Log::write('新增病例信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 更新病历信息
     * @param int $uid 服务对象id
     * @param int $id 病历记录id
     * @param string $institution 医院
     * @param string $case_time 检查时间
     * @param string $image 病例图片，json格式
     * @param string $remarks 备注
     * @param string $time 更新时间
     * @return array ['code'=>0,'msg'=>'更新结果']
     */
    protected function updateCaseRecord($uid, $id, $institution, $case_time, $image, $remarks, $time)
    {
        try {
            $sql = "UPDATE yc_client_case SET `institution`='".$institution."',`case_time`=".$case_time.",`image`='".$image."',`remarks`='".$remarks."',`modify_time`=".$time." WHERE `id`=".$id." AND `cid`=".$uid;
            $update = Db::execute($sql);
            if($update === false){
                return ['code'=>2,'msg'=>'更新病历失败'];
            }
            return ['code'=>0,'msg'=>'更新病历成功'];
        } catch (\Exception $e) {
            Log::write('更新病历信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 删除服务对象的病历
     * @param int $uid 服务对象id
     * @param int $id 病历记录id
     * @return array ['code'=>0,'msg'=>'删除结果']
     */
    public function deleteCaseRecord($uid, $id)
    {
        try {
            $sql = "DELETE FROM yc_client_case WHERE `id`=".$id." AND `cid`=".$uid;
            $delete = Db::execute($sql);
            if(!$delete){
                return ['code'=>1,'msg'=>'删除病历失败'];
            }
            return ['code'=>0,'msg'=>'删除病历成功'];
        } catch (\Exception $e) {
            Log::write('删除病历记录异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }
}