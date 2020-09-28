<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/24
 * Time: 9:13
 * 服务商管理
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;

class Provider extends Common {
    /**
     * 服务商列表
     * @param array $where 搜索条件
     * @param array $query 分页参数
     * @param int $limit 分页条数
     * @return array ['provider'=>'服务商数据','page'=>'分页信息']
     */
    public function providerList($where=[], $query=[], $limit=20)
    {
        try {
            $data = Db::name('provider')->alias('p')
                ->field('p.id,p.company,p.name,p.linkman,p.mobile,p.address,p.p_status as status,group_concat(s.name) as s_name')
                ->join('provider_service s','p.id=s.pid','LEFT')
                ->where($where)
                ->order('p.id desc')
                ->group('p.id')
                ->paginate($limit, false, ['query'=>$query]);
            $page = $data->render();
            $provider = $data->toArray();
            foreach($provider['data'] as &$value){
                $value['status'] = ($value['status'] == 1) ? '开启' : '关闭' ;
            }
            return ['provider'=>$provider,'page'=>$page];
        } catch (\Exception $e) {
            Log::write('服务商列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 服务商基础信息
     * @param int $id 服务商id
     * @return array 服务商详情信息
     */
    public function providerDetails($id)
    {
        try {
            $sql = "SELECT p.`id`,p.`company`,o.`legal_person`,o.`legal_mobile`,o.`registered_capital`,o.`join_time`,o.`expiry_time`,o.`org_code`,o.`org_type`,o.`tax_number`,o.`health_permit`,o.`remarks` FROM yc_provider AS p LEFT JOIN yc_provider_other AS o ON p.id=o.pid WHERE p.`id`=".$id;
            $details = Db::query($sql);
            if($details){
                $details[0]['join_time'] = $details[0]['join_time'] ? date('Y-m-d',$details['0']['join_time']) : '' ;
                $details[0]['expiry_time'] = $details[0]['expiry_time'] ? date('Y-m-d',$details['0']['expiry_time']) : '' ;
            }
            return $details[0];
        } catch (\Exception $e) {
            Log::write('服务商详情异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 提交服务商信息
     * @param int $id 服务商id
     * @param string $company 服务商名称
     * @param string $name 服务商登录账号
     * @param string $password 登录密码
     * @param string $linkman 联系人
     * @param string $mobile 联系电话
     * @param string $address 公司地址
     * @param int $status 服务商状态
     * @return array ['code'=>0,'msg'=>'提交结果']
     */
    public function subProviderInfo($id, $company, $name, $password, $linkman, $mobile, $address, $status)
    {
        try {
            // 服务商登录账号唯一
            $where = "`name`='".$name."'";
            $where .= $id ? " AND `id`!=".$id : "";
            $uniq = Db::query("SELECT `id` FROM yc_provider WHERE ".$where);
            $password = $password ? md5(md5($password).'pension') : $password ;
            if($uniq){
                return ['code'=>1,'msg'=>'登录账号重复'];
            }
            if($id){
                return $this->updateProvider($id, $company, $name, $password, $linkman, $mobile, $address, $status);
            }
            return $this->addProvider($company, $name, $password, $linkman, $mobile, $address, $status);
        } catch (\Exception $e) {
            Log::write('提交服务商信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 新增服务商信息
     * @param string $company 服务商名称
     * @param string $name 服务商登录账号
     * @param string $password 登录密码
     * @param string $linkman 联系人
     * @param string $mobile 联系电话
     * @param string $address 公司地址
     * @param int $status 服务商状态
     * @return array ['code'=>0,'msg'=>'新增结果']
     */
    protected function addProvider($company, $name, $password, $linkman, $mobile, $address, $status)
    {
        try {
            $time = time();
            $sql = "INSERT INTO yc_provider (`company`,`name`,`password`,`linkman`,`mobile`,`address`,`p_status`,`create_time`) VALUES ('".$company."','".$name."','".$password."','".$linkman."','".$mobile."','".$address."',".$status.",".$time.")";
            $add = Db::execute($sql);
            if(!$add){
                return ['code'=>2,'msg'=>'新增服务商失败'];
            }
            return ['code'=>0,'msg'=>'新增服务商成功'];
        } catch (\Exception $e) {
            Log::write('新增服务商异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 更新服务商
     * @param int $id 服务商id
     * @param string $company 服务商名称
     * @param string $name 服务商登录账号
     * @param string $password 登录密码
     * @param string $linkman 联系人
     * @param string $mobile 联系电话
     * @param string $address 公司地址
     * @param int $status 服务商状态
     * @return array ['code'=>0,'msg'=>'更新结果']
     */
    protected function updateProvider($id, $company, $name, $password, $linkman, $mobile, $address, $status)
    {
        try {
            $set = $password ? ",`password`='".$password."'" : "" ;
            $sql = "UPDATE yc_provider SET `company`='".$company."',`name`='".$name."',`linkman`='".$linkman."',`mobile`='".$mobile."',`address`='".$address."',`p_status`=".$status.$set." WHERE `id`=".$id;
            $update = Db::execute($sql);
            if($update === false){
                return ['code'=>2,'msg'=>'更新服务商失败'];
            }
            return ['code'=>0,'msg'=>'更新服务商成功'];
        } catch (\Exception $e) {
            Log::write('更新服务商异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 提交服务商附加信息
     * @param int $id 服务商id
     * @param string $legal_person 法人
     * @param string $legal_mobile 法人联系方式
     * @param string $registered_capital 注册资金
     * @param string $join_time 加盟时间
     * @param string $expiry_time 有效期
     * @param string $org_code 机构码
     * @param string $org_type 机构类型
     * @param string $tax_number 税务编号
     * @param string $health_permit 卫生许可证
     * @param string $remarks 备注
     * @return array ['code'=>0,'msg'=>'提交结果']
     */
    public function subProviderBaseInfo($id, $legal_person, $legal_mobile, $registered_capital, $join_time, $expiry_time, $org_code, $org_type, $tax_number, $health_permit, $remarks)
    {
//        try {
            $time = time();
            $exist = Db::query("SELECT `pid` FROM yc_provider_other WHERE `pid`=".$id);
            if($exist){
                $sql = "UPDATE yc_provider_other SET `legal_person`='".$legal_person."',`legal_mobile`='".$legal_mobile."',`registered_capital`='".$registered_capital."',`join_time`='".$join_time."',`expiry_time`='".$expiry_time."',`org_code`='".$org_code."',`org_type`='".$org_type."',`tax_number`='".$tax_number."',`health_permit`='".$health_permit."',`remarks`='".$remarks."',`modify_time`=".$time." WHERE `pid`=".$id;
            } else {
                $sql = "INSERT INTO yc_provider_other (`pid`,`legal_person`,`legal_mobile`,`registered_capital`,`join_time`,`expiry_time`,`org_code`,`org_type`,`tax_number`,`health_permit`,`remarks`,`create_time`) VALUES (".$id.",'".$legal_person."','".$legal_mobile."','".$registered_capital."','".$join_time."','".$expiry_time."','".$org_code."','".$org_type."','".$tax_number."','".$health_permit."','".$remarks."',".$time.")";
            }
            $result = Db::execute($sql);
            if($result === false){
                return ['code'=>1,'msg'=>'保存失败'];
            }
            return ['code'=>0,'msg'=>'保存成功'];
//        } catch (\Exception $e) {
//            Log::write('提交服务商附加信息异常：'.$e->getMessage(),'error');
//            return ['code'=>-1,'msg'=>'服务器异常'];
//        }
    }

    /**
     * 删除服务商信息（包括服务项目）
     * @param int $id 服务商id
     * @return array ['code'=>0,'msg'=>'删除结果']
     */
    public function deleteProvider($id)
    {
        try {
            Db::startTrans();
            $delete_provider = Db::execute("DELETE FROM yc_provider WHERE `id`=".$id);
            $delete_service = Db::execute("DELETE FROM yc_provider_service WHERE `pid`=".$id);
            if($delete_provider && $delete_service !== false){
                Db::commit();
                return ['code'=>0,'msg'=>'删除成功'];
            }
            Db::rollback();
            return ['code'=>1,'msg'=>'删除失败'];
        } catch (\Exception $e) {
            Log::write('删除服务商异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取服务商
     * @return array|false|\PDOStatement|string|\think\Collection
     */
    public function WorkProviderList()
    {
        try {
            $data = Db::name('provider')
                ->field('id,company')
                ->where(['p_status'=>1])
                ->order('id desc')
                ->select();

            return $data;
        } catch (\Exception $e) {
            Log::write('服务商列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }

    /**
     * 获取服务人员
     * @return array|false|\PDOStatement|string|\think\Collection
     */
    public function projectStaffList()
    {
        try {
            $data = Db::name('provider_staff')
                ->field('id,name')
                ->where(['state'=>1])
                ->order('id desc')
                ->select();

            return $data;
        } catch (\Exception $e) {
            Log::write('服务人员异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取服务商服务人员
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Collection
     */
    public function providerStaff($id)
    {
        try {
            $mStart = mktime(0,0,0,date('m'),1,date('Y'));
            $mEnd = mktime(23,59,59,date('m'),date('t'),date('Y'));
            $where['w.end_time'] = ['between',[$mStart,$mEnd]];
            $where['s.state'] = 1;
            $where['s.pid']   = $id;
            $query = [];
            $data = Db::name('provider_staff')
                ->alias('s')
                ->field('s.id,s.name,s.mobile,s.pid,s.status,s.state,s.create_time,sum(w.money) as money')
                ->join('yc_provider_work w','s.id = w.sid')
                ->where($where)
                ->group('w.sid')
                ->order('s.id DESC')
                ->paginate(20,false,$query);

            //获取服务类型
            $provider =  model('server/Provider')->projectList();
            $provider = array_column($provider, 'name', 'id');
            $page = $data->render();
            $staff = $data->toArray();
            foreach ($staff['data'] as $key=>$val){
                $staff['data'][$key]['state'] = $val['state'] == 2 ? '禁止' : '正常';
                $staff['data'][$key]['status'] = $provider[$val['status']];
            }
            return ['staff'=>$staff,'page'=>$page];
        } catch (\Exception $e) {
            Log::write('获取服务商服务人员：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取服务商工单
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Collection
     */
    public function providerWork($id)
    {
        $work = Db::name('provider_work')
            ->alias('w')
            ->field('w.id,w.site,w.status,w.money,w.title,w.client,c.name as cname,s.name as sname,w.sid,w.state,w.create_time,w.end_time')
            ->join('client c','w.client=c.id','LEFT')
            ->join('provider_staff s','w.sid=s.id','LEFT')
            ->where(['w.pid'=>$id])
            ->order('w.id DESC')
            ->paginate(20, false);

        $work_state = [
            1=>'未接单',
            2=>'已接单',
            3=>'已结单',
            4=>'已关闭'
        ];
        $page = $work->render();
        $work = $work->toArray();
        //获取服务类型
        $provider =  model('server/Provider')->projectList();
        $provider = array_column($provider, 'name', 'id');

        foreach ($work['data'] as $key=>$val){
            $work['data'][$key]['status'] = $provider[$val['status']];
            $work['data'][$key]['state'] = $work_state[$val['state']];
        }

        return ['work'=>$work,'page'=>$page];
    }

    /**
     * 获取服务商信息
     * @param $id
     * @return array
     */
    public function company($id)
    {
        try {
            $sql = "SELECT `company` FROM yc_provider  WHERE `id`=".$id;
            $details = Db::query($sql);
            return $details[0];
        } catch (\Exception $e) {
            Log::write('获取服务商信息：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }



}






