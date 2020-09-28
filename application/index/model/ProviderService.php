<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/29
 * Time: 14:26
 * 服务商服务项目
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;

class ProviderService extends Common {
    /**
     * 服务商服务项目列表
     * @param array $where where条件
     * @param array $query 分页条件
     * @param int $limit 分页条数
     * @return array ['service'=>'服务项目列表','page'=>'分页信息']
     */
    public function serviceList($where=[], $query=[], $limit=20)
    {
        try {
            $data = Db::name('provider_service')
                ->field('id,name,f_project_id,s_project_id,remarks')
                ->where($where)
                ->order('id desc')
                ->paginate($limit, false, ['query'=>$query]);
            $page = $data->render();
            $service = $data->toArray();
            foreach($service['data'] as &$value){
                // 一级类目
                $f = Db::query('SELECT `name` FROM yc_provider_project WHERE `id`='.$value['f_project_id']);
                // 二级类目
                $s = Db::query('SELECT `name` FROM yc_provider_project WHERE `id`='.$value['s_project_id']);
                $value['f_project_name'] = $f[0]['name'];
                $value['s_project_name'] = $s[0]['name'];
            }
            return ['service'=>$service,'page'=>$page];
        } catch (\Exception $e) {
            Log::write('服务商服务项目列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 提交服务商服务项目信息
     * @param int $id 服务项目id
     * @param int $uid 服务商id
     * @param string $name 服务项目名称
     * @param int $f_project 一级类目id
     * @param int $s_project 二级类目id
     * @param int $remarks 备注
     * @return array ['code'=>0,'msg'=>'提交结果']
     */
    public function submitService($id=0, $uid, $name, $f_project, $s_project, $remarks)
    {
        try {
            // 服务项目唯一
            $where = " `pid`=".$uid." AND `name`='".$name."'";
            $where .= $id ? " AND `id`!=".$id : "" ;
            $uniq = Db::query("SELECT `id` FROM yc_provider_service WHERE ".$where);
            if($uniq){
                return ['code'=>1,'msg'=>'服务项目重复'];
            }
            $time = time();
            if($id){
                return $this->updateService($id, $uid, $name, $f_project, $s_project, $remarks, $time);
            }
            return $this->addService($uid, $name, $f_project, $s_project, $remarks, $time);
        } catch (\Exception $e) {
            Log::write('提交服务项目信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 添加服务项目
     * @param int $uid 服务商id
     * @param string $name 服务项目名称
     * @param int $f_project 一级类目id
     * @param int $s_project 二级类目id
     * @param int $remarks 备注
     * @param string $time 添加时间戳
     * @return array ['code'=>0,'msg'=>'新增结果']
     */
    protected function addService($uid, $name, $f_project, $s_project, $remarks, $time)
    {
        try {
            $sql = "INSERT INTO yc_provider_service (`pid`,`name`,`f_project_id`,`s_project_id`,`remarks`,`create_time`) VALUES (".$uid.",'".$name."',".$f_project.",".$s_project.",'".$remarks."',".$time.")";
            $add = Db::execute($sql);
            if(!$add){
                return ['code'=>2,'msg'=>'新增服务项目失败'];
            }
            return ['code'=>0,'msg'=>'新增服务项目成功'];
        } catch (\Exception $e) {
            Log::write('新增服务项目异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 更新服务项目
     * @param int $id 服务项目id
     * @param int $uid 服务商id
     * @param string $name 服务项目名称
     * @param int $f_project 一级类目id
     * @param int $s_project 二级类目id
     * @param int $remarks 备注
     * @param string $time 更新时间
     * @return array ['code'=>0,'msg'=>'更新结果']
     */
    protected function updateService($id, $uid, $name, $f_project, $s_project, $remarks, $time)
    {
        try {
            $sql = "UPDATE yc_provider_service SET `name`='".$name."',`f_project_id`=".$f_project.",`s_project_id`=".$s_project.",`remarks`='".$remarks."',`modify_time`=".$time." WHERE `id`=".$id." AND `pid`=".$uid;
            $update = Db::execute($sql);
            if($update === false){
                return ['code'=>2,'msg'=>'编辑服务项目失败'];
            }
            return ['code'=>0,'msg'=>'编辑服务项目成功'];
        } catch (\Exception $e) {
            Log::write('更新服务项目异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 删除服务项目
     * @param int $id 服务项目id
     * @param int $uid 服务商id
     * @return array ['code'=>0,'msg'=>'删除结果']
     */
    public function deleteService($id, $uid)
    {
        try {
            $sql = "DELETE FROM yc_provider_service WHERE `id`=".$id." AND `pid`=".$uid;
            $delete = Db::execute($sql);
            if(!$delete){
                return ['code'=>1,'msg'=>'删除失败'];
            }
            return ['code'=>0,'msg'=>'删除成功'];
        } catch (\Exception $e) {
            Log::write('删除服务项目异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

}