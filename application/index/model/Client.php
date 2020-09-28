<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/7
 * Time: 18:32
 * 服务对象
 */
namespace app\index\model;

use app\index\model\Common;
use think\Db;
use think\Exception;
use think\Log;
use think\Request;

class Client extends Common {
    /**
     * 服务对象列表
     * @param string $where 搜索条件
     * @param array $query 分页条件
     * @param int $limit 分页条数
     * @return array []
     */
    public function clientList($where='', $query=[], $limit=20)
    {
        try {
            $data = Db::name('client')->alias('c')
                ->field('c.id,c.name as userName,c.id_number,c.age,c.sex,c.mobile,c.address,g.name as groupName,t.name as tagName')
                ->join(['v_client_group'=>'g'],'c.id=g.cid','LEFT')
                ->join(['v_client_tag'=>'t'],'c.id=t.cid','LEFT')
                ->where($where)
                ->order('c.id desc')
                ->group('c.id')
                ->paginate($limit,false,['query'=>$query]);
            $page = $data->render();
            $client = $data->toArray();
            foreach($client['data'] as &$value){
                $value['sex'] = ($value['sex'] == 1) ? '男' : '女';
            }
            return ['client'=>$client,'page'=>$page];
        } catch (\Exception $e) {
            Log::write('获取服务对象列表异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取用户的基础信息
     * @param int $id 用户id
     * @return $info 用户的基础信息
     */
    public function userInfo($id)
    {
        try {
            $sql = "SELECT c.`id`,c.`name`,c.`id_number`,c.`head`,c.`birthday`,c.`age`,c.`sex`,c.`mobile`,c.`address`,c.`permanent_address`,c.`integral`,g.`name` AS `groupName`,t.`name` AS `tagName` FROM yc_client AS c LEFT JOIN v_client_group AS g ON c.`id`=g.`cid` LEFT JOIN v_client_tag AS t ON c.`id`=t.`cid` WHERE c.`id`=".$id;
            $info = Db::query($sql);
            if(!$info){
                return false;
            }
            $info[0]['sex'] = ($info[0]['sex'] == 1) ? '男' : '女' ;
            $info[0]['birthday'] = $info[0]['birthday'] ? date('Y-m-d',$info[0]['birthday']) : '';
            return $info[0];
        } catch (\Exception $e) {
            Log::write('获取用户的基础信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 提交服务对象信息
     * @param int $id 服务对象id
     * @param string $name 服务对象姓名
     * @param string $id_number 身份证号
     * @param string $head 头像
     * @param int $sex 性别
     * @param string $birthday 出生日期时间戳
     * @param string $mobile 手机号
     * @param string $address 现住地址
     * @param string $permanent_address 户籍地址
     * @return array ['code'=>0,'msg'=>'提交结果']
     */
    public function submitBasicInfo($id, $name, $id_number, $head, $sex, $birthday, $mobile, $address, $permanent_address)
    {
        try {
            // 身份证唯一
            $where = " `id_number`='".$id_number."'";
            $where .= $id ? " AND `id`!=".$id : "" ;
            $sql_uniq = "SELECT `id` FROM yc_client WHERE ".$where;
            $uniq = Db::query($sql_uniq);
            if($uniq){
                return ['code'=>1,'msg'=>'身份证号码重复'];
            }
            $time = time();
            $age = (int)F_get_age($birthday);  // 获取年龄
            if($id){
                return $this->updateClient($id, $name, $id_number, $head, $sex, (int)$birthday, $mobile, $address, $permanent_address, $age, $time);
            }
            return $this->addClient($name, $id_number, $head, $sex, (int)$birthday, $mobile, $address, $permanent_address, $age, $time);
        } catch (\Exception $e) {
            Log::write('提交服务对象基础信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 新增服务对象
     * @param string $name 服务对象姓名
     * @param string $id_number 身份证号
     * @param string $head 头像
     * @param int $sex 性别
     * @param string $birthday 出生日期时间戳
     * @param string $mobile 手机号
     * @param string $address 现住地址
     * @param string $permanent_address 户籍地址
     * @param int $age 年龄
     * @param string $time 添加时间戳
     * @return array ['code'=>0,'msg'=>'新增结果']
     */
    protected function addClient($name, $id_number, $head, $sex, $birthday, $mobile, $address, $permanent_address, $age, $time)
    {
        try {
            $sql = "INSERT INTO yc_client (`name`,`id_number`,`head`,`mobile`,`sex`,`birthday`,`age`,`address`,`permanent_address`,`create_time`) VALUES ('".$name."','".$id_number."','".$head."','".$mobile."',".$sex.",".$birthday.",".$age.",'".$address."','".$permanent_address."',".$time.")";
            $insert = Db::execute($sql);
            if(!$insert){
                return ['code'=>2,'msg'=>'新增服务对象失败'];
            }
            return ['code'=>0,'msg'=>'新增服务对象成功'];
        } catch (\Exception $e) {
            Log::write('新增服务对象异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 更新服务对象基础信息
     * @param int $id 服务对象id
     * @param string $name 服务对象姓名
     * @param string $id_number 身份证号
     * @param string $head 头像
     * @param int $sex 性别
     * @param string $birthday 出生日期时间戳
     * @param string $mobile 手机号
     * @param string $address 现住地址
     * @param string $permanent_address 户籍地址
     * @return array ['code'=>0,'msg'=>'更新结果']
     */
    protected function updateClient($id, $name, $id_number, $head, $sex, $birthday, $mobile, $address, $permanent_address, $age, $time)
    {
        try {
            $sql = "UPDATE yc_client SET `name`='".$name."',`id_number`='".$id_number."',`head`='".$head."',`mobile`='".$mobile."',`sex`=".$sex.",`birthday`=".$birthday.",`age`=".$age.",`address`='".$address."',`permanent_address`='".$permanent_address."',`modify_time`=".$time." WHERE `id`=".$id;
            $update = Db::execute($sql);
            if($update === false){
                return ['code'=>2,'msg'=>'更新服务对象失败'];
            }
            return ['code'=>0,'msg'=>'更新服务对象成功'];
        } catch (\Exception $e) {
            Log::write('更新服务对象异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 服务对象血型
     */
    protected $bloodType = [
        '1'=>'A型',
        '2'=>'B型',
        '3'=>'AB型',
        '4'=>'O型',
    ];

    /**
     * 获取用户的其他信息
     * @param array $param ['id'=>'服务对象id']
     * @return array $other
     */
    public function userOtherInfo($uid)
    {
        try {
            $sql = "SELECT `native_place`,`nation`,`education`,`political`,`religion`,`hobby`,`diet_taboo`,`blood_type`,`rh_negative`,`economic_source`,`livelihood`,`caregiver`,`healthy`,`live`,`house`,`provider`,`remarks` FROM yc_client_other WHERE `cid`=".$uid;
            $other = Db::query($sql);
            if(!$other){
                return $other;
            }
            $other[0]['hobby'] = json_decode($other[0]['hobby'],true);                      // 兴趣爱好
            $other[0]['diet_taboo'] = json_decode($other[0]['diet_taboo'],true);            // 饮食禁忌
            $other[0]['caregiver'] = json_decode($other[0]['caregiver'],true);              // 照看人
            $other[0]['blood_type_text'] = $this->bloodType[$other[0]['blood_type']];            // 血型
            $other[0]['rh_negative_text'] = ($other[0]['rh_negative'] == 1) ? '是' : '否' ;      // RH阴性
            return $other[0];
        } catch (\Exception $e) {
            Log::write('获取服务对象的其他信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 联系人类型
     */
    protected $contactsType = [
        '1'=>'子女',
        '2'=>'父母',
        '3'=>'亲戚',
        '4'=>'朋友',
        '5'=>'同事',
        '6'=>'同学',
        '7'=>'其它'
    ];
    
    /**
     * 获取服务对象对应的联系人信息
     * @param int $uid 服务对象id
     * @return array $contacts 联系人信息数组
     */
    public function contactsInfo($uid)
    {
        try {
            $sql = "SELECT `name`,`mobile`,`type` FROM yc_client_emergency WHERE `cid`=".$uid;
            $contacts = Db::query($sql);
            foreach($contacts as &$value){
                $value['type_text'] = $this->contactsType[$value['type']];
            }
            return $contacts;
        } catch (\Exception $e) {
            Log::write('获取服务对象的联系人信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取服务对象对应的分组信息
     * @param int $uid 服务对象id
     * @param array $group 分组信息数组
     */
    public function groupInfo($uid)
    {
        try {
            $sql = "SELECT `cid`,`gid`,`name` FROM v_client_group WHERE `cid`=".$uid;
            $group = Db::query($sql);
            return $group;
        } catch (\Exception $e) {
            Log::write('获取服务对象分组信息查询异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取服务对象对应的标签信息
     * @param int $uid 服务对象id
     * @param array $group 标签信息数组
     */
    public function tagInfo($uid)
    {
        try {
            $sql = "SELECT `cid`,`tid`,`name` FROM v_client_tag WHERE `cid`=".$uid;
            $tag = Db::query($sql);
            return $tag;
        } catch (\Exception $e) {
            Log::write('获取服务对象标签信息查询异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 更新服务对象的联系人信息
     * @param int $uid 服务对象id
     * @param array $contacts 紧急联系人信息 [['type'=>'关系','name'=>'姓名','mobile'=>'手机号'],[]]
     * @return array ['code'=>0,'msg'=>'更新结果']
     */
    public function updateContactsInfo($uid, $contacts=[])
    {
        try {
            // 验证服务对象是否存在
            $is_exit = $this->userInfo($uid);
            if(!$is_exit){
                return ['code'=>1,'msg'=>'服务对象不存在'];
            }
            Db::startTrans();
            // 先删除原有的联系人信息
            $sql_del = "DELETE FROM yc_client_emergency WHERE `cid`=".$uid;
            $delete = Db::execute($sql_del);
            // 添加新的联系人信息
            $new_contacts = true;
            if(!empty($contacts)){
                $sql_contacts = "INSERT INTO yc_client_emergency (`cid`,`name`,`mobile`,`type`) VALUES ";
                foreach($contacts as $value){
                    $sql_contacts .= "(".$uid.",'".$value['name']."','".$value['mobile']."',".$value['type']."),";
                }
                $sql_contacts = rtrim($sql_contacts,',');
                $new_contacts = Db::execute($sql_contacts);
            }
            if($delete !== false && $new_contacts){
                Db::commit();
                return ['code'=>0,'msg'=>'保存联系人成功'];
            }
            Db::rollback();
            return ['code'=>2,'msg'=>'保存联系人失败'];
        } catch (\Exception $e) {
            Log::write('更新服务对象联系人信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 保存服务对象的分组记录
     * @param int $uid 服务对象id
     * @param array $group 分组id
     * @return array ['code'=>0,'msg'=>'保存结果']
     */
    public function updateClientGroup($uid, $group=[])
    {
        try {
            // 验证服务对象是否存在
            $is_exit = $this->userInfo($uid);
            if(!$is_exit){
                return ['code'=>1,'msg'=>'服务对象不存在'];
            }
            Db::startTrans();
            // 删除原有的记录
            $sql_del = "DELETE FROM yc_client_group_map WHERE `cid`=".$uid;
            $delete = Db::execute($sql_del);
            // 添加新的分组
            $new_group = true;
            if(!empty($group)){
                $sql_group = "INSERT INTO yc_client_group_map (`cid`,`gid`) VALUES ";
                foreach($group as $value){
                    $sql_group .= "(".$uid.",".(int)$value."),";
                }
                $sql_group = rtrim($sql_group,',');
                $new_group = Db::execute($sql_group);
            }
            if($delete !== false && $new_group){
                Db::commit();
                return ['code'=>0,'msg'=>'分组保存成功'];
            }
            Db::rollback();
            return ['code'=>2,'msg'=>'分组保存失败'];
        } catch (\Exception $e) {
            Log::write('保存服务对象分组异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 保存服务对象的标签记录
     * @param int $uid 服务对象id
     * @param array $tag 标签id
     */
    public function updateClientTag($uid, $tag=[])
    {
        try {
            // 验证服务对象是否存在
            $is_exit = $this->userInfo($uid);
            if(!$is_exit){
                return ['code'=>1,'msg'=>'服务对象不存在'];
            }
            Db::startTrans();
            // 先删除原有的标签记录
            $sql_del = "DELETE FROM yc_client_tag_map WHERE `cid`=".$uid;
            $delete = Db::execute($sql_del);
            // 添加新的标签
            $new_tag = true;
            if(!empty($tag)){
                $sql_tag = "INSERT INTO yc_client_tag_map (`cid`,`tid`) VALUES ";
                foreach($tag as $value){
                    $sql_tag .= "(".$uid.",".(int)$value."),";
                }
                $sql_tag = rtrim($sql_tag,',');
                $new_tag = Db::execute($sql_tag);
            }
            if($delete !== false && $new_tag){
                Db::commit();
                return ['code'=>0,'msg'=>'标签保存成功'];
            }
            Db::rollback();
            return ['code'=>2,'msg'=>'标签保存失败'];
        } catch (\Exception $e) {
            Log::write('保存服务对象标签异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 更新服务对象的其他信息
     * @param int $uid 服务对象id
     * @param string $native_place 籍贯
     * @param string $nation 民族
     * @param string $education 教育程度
     * @param string $political 政治面貌
     * @param string $religion 宗教
     * @param string $hobby 兴趣爱好json格式
     * @param string $diet_taboo 饮食禁忌json格式
     * @param int $blood_type 血型
     * @param int $rh_negative RH阴性
     * @param string $economic_source 经济来源
     * @param string $livelihood 生活来源
     * @param string $caregiver 照看人json格式
     * @param string $healthy 健康情况
     * @param string $live 居住类型
     * @param string $house 住房类型
     * @param string $provider 资料提供者
     * @param string $remarks 备注
     * @return array ['code'=>0,'msg'=>'更新结果']
     */
    public function updateOtherInfo($uid, $native_place, $nation, $education, $political, $religion, $hobby, $diet_taboo, $blood_type, $rh_negative, $economic_source, $livelihood, $caregiver, $healthy, $live, $house, $provider, $remarks)
    {
        try {
            // 验证服务对象是否存在
            $is_exit = $this->userInfo($uid);
            if(!$is_exit){
                return ['code'=>1,'msg'=>'服务对象不存在'];
            }
            // 是否存在
            $sql_exit = "SELECT `cid` FROM yc_client_other WHERE `cid`=".$uid;
            $exit = Db::query($sql_exit);
            $time = time();
            if($exit){
                $sql = "UPDATE yc_client_other SET `native_place`='".$native_place."',`nation`='".$nation."',`education`='".$education."',`political`='".$political."',`religion`='".$religion."',`hobby`='".$hobby."',`diet_taboo`='".$diet_taboo."',`blood_type`=".$blood_type.",`rh_negative`=".$rh_negative.",`economic_source`='".$economic_source."',`livelihood`='".$livelihood."',`caregiver`='".$caregiver."',`healthy`='".$healthy."',`live`='".$live."',`house`='".$house."',`provider`='".$provider."',`remarks`='".$remarks."',`modify_time`=".$time." WHERE `cid`=".$uid;
            } else {
                $sql = "INSERT INTO yc_client_other (`cid`,`native_place`,`nation`,`education`,`political`,`religion`,`hobby`,`diet_taboo`,`blood_type`,`rh_negative`,`economic_source`,`livelihood`,`caregiver`,`healthy`,`live`,`house`,`provider`,`remarks`,`create_time`) VALUES (".$uid.",'".$native_place."','".$nation."','".$education."','".$political."','".$religion."','".$hobby."','".$diet_taboo."',".$blood_type.",".$rh_negative.",'".$economic_source."','".$livelihood."','".$caregiver."','".$healthy."','".$live."','".$house."','".$provider."','".$remarks."',".$time.")";
            }
            $result = Db::execute($sql);
            if($result === false){
                return ['code'=>1,'msg'=>'保存其它信息失败'];
            }
            return ['code'=>0,'msg'=>'保存其它信息成功'];
        } catch (\Exception $e) {
            Log::write('更新服务对象其他信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 获取服务对象信息
     * @return false|array
     */
    static public function getClientList()
    {
        try {
            $sql = "SELECT `id`,`name`,`sex`,`mobile` FROM yc_client ";
            $client = Db::query($sql);
            return $client;
        } catch (\Exception $e) {
            Log::write('获取服务对象信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }

    /**
     * 获取计划工单服务对象信息
     * @param $id
     * @return array|false|\PDOStatement|string
     */
    public function getObj($id)
    {
        try {
            $sql = "SELECT `id`,`name`,`sex`,`id_number`,`address`,`label_id`,`group_id`,`age`,`mobile`,`height`,`weight`,`integral`  
                FROM yc_client AS u LEFT JOIN yc_client_healthy AS h ON u.id=h.cid WHERE u.id=".$id;
            $client = Db::query($sql)[0];
            $sql = "SELECT `msisdn` FROM yc_device WHERE uid = ".$id;

            $client['msisdn'] = Db::query($sql)[0]['msisdn'];
            $sql = "SELECT `type`,`name`,`mobile` FROM yc_client_emergency WHERE cid = ".$id;
            $emergency = Db::query($sql);
            $type = ['','子女','父母','亲戚','朋友','同事','同学','其他'];
            foreach ($emergency as $key=>$val){
                $emergency[$key]['type'] = $type[$val['type']];
            }
            $client['emergency'] = $emergency;
            //获取服务对象分组
            $sql = "SELECT g.name FROM yc_client AS u,v_client_group as g WHERE u.id = g.cid AND id=".$id;
            $client['group'] = Db::query($sql)[0]['name'];
            $sql = "SELECT t.name FROM yc_client AS u,v_client_tag as t WHERE u.id = t.cid AND id=".$id;
            $client['label'] = Db::query($sql)[0]['name'];
            return $client;
        } catch (\Exception $e) {
            Log::write('获取计划工单服务对象信息异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }

    }

    /*
    *时时定位获取信息
    *id int 用户id
    *return bool
    */
    public function userMapInfo($id){
        try {
            $where['a.id'] = ['eq',$id];
            $info = db::name('client a')
            ->field('a.id,a.head,b.imei,a.name,c.text,c.location_type,c.address,c.addtime,d.lng,d.lat,d.radius,d.fencename')
            ->join('device b','a.id=b.uid','left')
            ->join('gpslog c','b.imei=c.imei','left')
            ->join('fence d','b.imei=d.imei','left')
            ->where($where)
            ->group('c.id desc')
            ->find();
            $info['time'] = date('Y-m-d H:i:s',$info['addtime']);
            $info['loc'] = $this->location_type[$info['location_type']];
            $loc = explode(',', $info['text']);
            $info['j'] = $loc[0];
            $info['w'] = $loc[1];
            unset($info['text']);
            unset($info['location_type']);
            unset($info['addtime']);
            return $info;
        } catch (Exception $e) {
            Log::write('时时定位获取信息异常: '.$e->getMessage(),'error');
            return false;
        }
    }

    /*
    *定位方式数组
    */
    protected $location_type=[
        '1'=>'未有定位方式',
        '2'=>'GPS',
        '3'=>'基站',
        '4'=>'WIFI',
        '5'=>'混合定位'
    ];

    /*
    *创建电子围栏
    *return array
    */
    public function CreateFence(){
        try {
            $data = input();
            if (!$data['lat'] || !$data['lng']) {
                return ['code'=>1,'msg'=>'请创建电子围栏'];
            }
            if ($data['imei']=='') {
                return ['code'=>1,'msg'=>'未绑定腕表'];
            }
            $where['imei']=['eq',$data['imei']];
            $info = db::name('device')
            ->field('imei,uid')
            ->where($where)
            ->find();
            if (!$info) {
                return ['code'=>1,'msg'=>'未查询到当前用户的腕表信息'];
            }
            $id = db::name('fence')->where('imei',$data['imei'])->value('id');
            //设置电子围栏
            $arr = [
                'imei'=>$data['imei'],
                'lng' =>$data['lng'],
                'lat' =>$data['lat'],
                'addtime'=>time(),
                'radius' =>$data['radius'],
                'fencename'=>$data['fencname'],
            ];
            if($id){
                $mess = '更新';
                $result = db::name('fence')->where('id',$id)->update($arr);
            }else{
                $mess = '创建';
                $result = db::name('fence')->insert($arr);
            }
            if ($result!==false) {
                return ['code'=>0,'msg'=>$mess.'电子围栏成功'];
            }
            return ['code'=>1,'msg'=>$mess.'电子围栏失败'];
        } catch (Exception $e) {
            Log::write('创建电子围栏操作异常: '.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务异常'];
        }
    }

    /*
    *移除电子围栏
    *return array
    */
    public function RomoveFence(){
        try {
            $data = input();
            if ($data['imei']=='') {
                return ['code'=>1,'msg'=>'未绑定腕表'];
            } 
            $result = Db::name('fence')->where('imei',$data['imei'])->delete();
            if ($result) {
                return ['code'=>0,'msg'=>'电子围栏移除成功'];
            }
            return ['code'=>1,'msg'=>'电子围栏移除失败'];
        } catch (Exception $e) {
            Log::write('移除电子围栏操作异常: '.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务异常'];
        }
    }

    /*
    *点击获取时时定位
    *return array
    */
    public function GetDeviceLoc(){
        try {
            $data = input();
            if ($data['imei']!=='') {
                //查询是否入库且是否绑定
                $where['a.imei'] = ['eq',$data['imei']];
                $info = db::name('device a')
                ->field('a.uid,a.pid,b.p_status')
                ->join('device_passage b','a.pid=b.id','left')
                ->where($where)
                ->find();
                if (!$info['uid']) {
                    return ['code'=>1,'msg'=>'当前腕表未绑定用户'];
                }
                if ($info['p_status']==2) {
                    return ['code'=>1,'msg'=>'当前腕表通道未开启,请联系管理人员'];
                }
            }
            switch ($info['pid']) {
                //科强手环
                case 2:
                    $kq_loc = $this->KQH2($data['imei'],$info['uid']);
                    return $kq_loc;
                //小亿手表
                case 1:
                    $ly_loc = $this->LYH6($data['imei'],$info['uid']);
                    return $ly_loc;
                default:
                    return ['code'=>1,'msg'=>'未找到腕表通道,请联系管理人员'];
            }
        } catch (Exception $e) {
            Log::write('点击获取时时定位操作异常: '.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务异常'];
        }
    }

    /*
    *科强手环下发定位指令 下发指令成功返回后获取最新的一条记录即可
    *imei string 设备唯一标示
    *uid int 客户id
    *return array
    */
    protected function KQH2($imei,$uid){
        try {
            $key = F_CreateRandom(32);
            if (!$key) {
                return ['code'=>1,'msg'=>'下发指令失败'];
            }
            $command = '@B#@|01|CMDG|048|'.$imei.'|'.$key.'|@E#@';
            //连接服务器下发指令
            $result = F_SendCommand($command,9077);
            if (!isset($result['code']) || is_null($result) || $result['code']==1) {
                return ['code'=>1,'msg'=>'腕表不在线'];
            }
            if ($result['code']==0) {
                //查询最新的定位记录
                $info = db::name('gpslog')->field('id,text,address,addtime,location_type')->where('imei',$imei)->group('id desc')->find();
                $loc = explode(',',$info['text']);
                $info['j'] = $loc[0];
                $info['w'] = $loc[1];
                unset($info['text']);
                $info['addtime'] = date('Y-m-d H:i:s',$info['addtime']);
                $info['location_type'] = $this->location_type[$info['location_type']];
                $user = db::name('client')->field('head,name')->where('id',$uid)->find();
                $info['name'] = $user['name'];
                $info['img'] = $user['head'];
                $info['imei'] = $imei;
                return ['code'=>0,'msg'=>json_encode($info)];
            }
        } catch (Exception $e) {
            Log::write('科强手环下发定位指令操作异常: '.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务异常'];
        }
    }

    /*
    *获取小亿腕表时时定位 下发指令成功返回后获取最新的一条记录即可
    *imei string 设备唯一标示
    *uid int 客户id
    *return array
    */
    protected function LYH6($imei,$uid){
        try {
            //拼接下发指令
            $command = 'IWBP16,GTLC,'.$imei.','.rand(100000,999999).'#';
            $result = F_SendCommand($command,9088);
            //返回不在线消息
            if (!isset($result['code']) || is_null($result) || $result['code']==1) {
                return ['code'=>1,'msg'=>'腕表不在线'];
            }

            if($result['code']==0){
                //查询最新的定位记录
                $info = db::name('gpslog')->field('id,text,address,addtime,location_type')->where('imei',$imei)->group('id desc')->find();
                $loc = explode(',',$info['text']);
                $info['j'] = $loc[0];
                $info['w'] = $loc[1];
                unset($info['text']);
                $info['addtime'] = date('Y-m-d H:i:s',$info['addtime']);
                $info['location_type'] = $this->location_type[$info['location_type']];
                $user = db::name('client')->field('head,name')->where('id',$uid)->find();
                $info['name'] = $user['name'];
                $info['img'] = $user['head'];
                $info['imei'] = $imei;
                return ['code'=>0,'msg'=>json_encode($info)];
            }
        } catch (Exception $e) {
            Log::write('小亿智能终端获取时时定位异常'.$e->getMessage(),'error');
            return $this->ERROR;
        }
    }
    /*
    *用户健康数据查询
    */
    public function GetUserHealth($uid){
        try {
            $start = mktime(0,0,0,date('m'),date('d'),date('Y'));
            $end = mktime(23,59,59,date('m'),date('d'),date('Y'));
            // $where['addtime'] = ['between',[$start,$end]]; 
            $where['uid'] = ['eq',$uid];
            $info = db::name('health')->where($where)->order('id desc')->find();
            $info['addtime'] = $info['addtime'] ? date('Y-m-d H:i:s',$info['addtime']) : '未有测量时间';
            $info['state'] = $info['state'] ? $this->state[$info['state']] : '--';
            $info['heart'] = $info['heart'] ? $info['heart'] : '0';
            $info['blood'] = $info['blood'] ? $info['blood'] : '0';
            $info['sleep'] = $info['sleep'] ? $info['sleep'] : '0';
            $info['steep'] = $info['steep'] ? $info['steep'] : '0';
            return $info;
        } catch (Exception $e) {
            Log::write('健康数据查询异常: '.$e->getMessage(),'error');
            return false;
        }
    }

    //健康数据测量设备
    protected $state = [
        1=>'手环',
        2=>'手表',
        3=>'枕头', 
        4=>'一体机',
    ];

    /*
    *手工添加心率健康数据
    */
    public function AddUserHealthHeart(){
        try {
            $info = input();
            //4为一体机不需要做绑定
            if ($info['state']!=='4') {
                //查询当前用户绑定的imei
                $tmp['uid'] = ['eq',$info['uid']];
                $tmp['pid'] = ['eq',$info['state']];
                $imei = db::name('device')->field('pid,imei')->where($tmp)->value('imei');
                //查询当前用户是否绑定设备    
                if (!$imei) {
                   return ['code'=>1,'msg'=>'当前用户未绑定设备'];
                }
            }
            $arr['heart'] = $info['content'];
            $arr['addtime'] = strtotime($info['start_create']);
            $arr['uid'] = $info['uid'];
            $arr['imei'] = $imei;
            $arr['state'] = $info['state'];
            $result = db::name('health')->insert($arr);
            if ($result) {
                return ['code'=>0,'msg'=>'添加成功'];
            }
            return ['code'=>1,'msg'=>'添加失败'];
        } catch (Exception $e) {
            Log::write('心率健康数据手动入录异常: '.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务异常'];
        }
    }

    /*
    *添加血压健康记录
    */
    public  function AddUserHealthBlood(){
        try {
            $info = input();
            //4为一体机不需要做绑定
            if ($info['state']!=='4') {
                //查询当前用户绑定的imei
                $tmp['uid'] = ['eq',$info['uid']];
                $tmp['pid'] = ['eq',$info['state']];
                $imei = db::name('device')->field('pid,imei')->where($tmp)->value('imei');
                //查询当前用户是否绑定设备    
                if (!$imei) {
                   return ['code'=>1,'msg'=>'当前用户未绑定设备'];
                }
            }
            $arr['blood'] = $info['hyperpiesia_one'].';'.$info['hyperpiesia_two'];
            $arr['addtime'] = strtotime($info['hyperpiesia_create']);
            $arr['uid'] = $info['uid'];
            $arr['imei'] = $imei;
            $arr['state'] = $info['state'];
            $result = db::name('health')->insert($arr);
            if ($result) {
                return ['code'=>0,'msg'=>'添加成功'];
            }
            return ['code'=>1,'msg'=>'添加失败'];
        } catch (Exception $e) {
            Log::write('血压健康数据手动入录异常: '.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务异常'];
        }
    }

    /*
    *计步健康数据添加
    */
    public function AddUserHealthSteep(){
        try {
            $info = input();
            //查询当前用户绑定的imei
            $tmp['uid'] = ['eq',$info['uid']];
            $tmp['pid'] = ['eq',$info['state']];
            $imei = db::name('device')->field('pid,imei')->where($tmp)->value('imei');
            //查询当前用户是否绑定设备    
            if (!$imei) {
               return ['code'=>1,'msg'=>'当前用户未绑定设备'];
            }
            $arr['steep'] = $info['steep'];
            $arr['addtime'] = strtotime($info['hyperpiesia_create']);
            $arr['uid'] = $info['uid'];
            $arr['imei'] = $imei;
            $arr['state'] = $info['state'];
            $result = db::name('health')->insert($arr);
            if ($result) {
                return ['code'=>0,'msg'=>'添加成功'];
            }
            return ['code'=>1,'msg'=>'添加失败'];
        } catch (Exception $e) {
            Log::write('计步健康数据手动入录异常: '.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务异常'];
        }
    }

    /*
    *睡眠健康数据添加
    */
    public function AddUserHealthSleep(){
        try {
            $info = input();
            //查询当前用户绑定的imei
            $tmp['uid'] = ['eq',$info['uid']];
            $tmp['pid'] = ['eq',$info['p_id']];
            $imei = db::name('device')->field('pid,imei')->where($tmp)->value('imei');
            //查询当前用户是否绑定设备    
            if (!$imei) {
               return ['code'=>1,'msg'=>'当前用户未绑定设备'];
            }
            $arr['sleep'] = strtotime($info['sleep_end']) - strtotime($info['sleep_start']);
            $arr['addtime'] = strtotime($info['start_create']);
            $arr['uid'] = $info['uid'];
            $arr['imei'] = $imei;
            $arr['state'] = $info['p_id'];
            $result = db::name('health')->insert($arr);
            if ($result) {
                return ['code'=>0,'msg'=>'添加成功'];
            }
            return ['code'=>1,'msg'=>'添加失败'];
        } catch (Exception $e) {
            Log::write('睡眠健康数据手动入录异常: '.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务异常'];  
        }
    }

    /*
    *添加健康数据类型
    */
    protected $health_type = [
        'heart'=>2,
        'steep'=>4,
        'sleep'=>6,
        'blood'=>1
    ];

    /*
    *服务对象时时健康查询健康数据图表 这里只查询计步与心率
    *return bool
    */
    public function GetUserHealthEcharts(){
        try {
            $info = input();
            //数据库查询条件 
            $start_date = strtotime(date('Y-m-d',time()-(3600*24*6)));
            $end_date = strtotime(date('Y-m-d 23:59:59'));
            $where['addtime'] = ['between',[$start_date,$end_date]];
            $where['uid'] = ['eq',$info['uid']];
            switch ($info['state']) {
                case 'steep':
                    $field = "from_unixtime(addtime,'%Y-%m-%d') as name,sum(".$info['state'].") as value";
                    break;
                case 'heart':
                    $field = "from_unixtime(addtime,'%Y-%m-%d') as name,max(".$info['state'].") as value";
                    break;
                case 'sleep':
                    $field = "from_unixtime(addtime,'%Y-%m-%d') as name,max(".$info['state'].") as value";
                    break;
            }
            $data = db::name('health')
                    ->field($field)
                    ->where($where)
                    ->select();   
            for ($i=6; $i>=0;$i--) { 
                $arr[] = date('Y-m-d',time()-(3600*24*$i));
            }
            //图表时间参数数组
            $return_arr['day'] = $arr; 

            //查找时间下标
            $arr_data = array_column($data,'name');
            //数组比较返回差集
            $array_diff = array_diff($arr, $arr_data);
            foreach ($array_diff as $key=>&$value) {
                $diff[$key]['name'] = $value;
                $diff[$key]['value'] = 0;
            }
            $arr_val = array_merge_recursive($data,$diff);
            //提取列数组
            foreach ($arr_val as $k=>$val) {
                $tmp[$k] = $val['name'];
            }
            //此处对数组进行降序排列；SORT_DESC按降序排列
            array_multisort($tmp,SORT_DESC,$arr_val);
            $return_arr['v'] = $arr_val;
            return $return_arr;
        } catch (Exception $e) {
            Log::write('健康数据图表查询异常: '.$e->getMessage(),'error');
            return false;
        }
    }

    /*
    *服务对象健康数据血压图表 
    */
    public function GetUserHealthEchartsBoold(){
        try {
            $info = input();
            //数据库查询条件
            $start_date = strtotime(date('Y-m-d',time()-(3600*24*6)));
            $end_date = strtotime(date('Y-m-d 23:59:59'));
            $where['addtime'] = ['between',[$start_date,$end_date]];
            $where['uid'] = ['eq',$info['uid']];
            $data = db::name('health')->field("from_unixtime(addtime,'%Y-%m-%d') as name,blood")->where($where)->order('id desc')->select();
            for ($i=0; $i<=6;$i++) { 
                $arr[$i] = date('Y-m-d',time()-(3600*24*$i));
            }
            //图表时间参数数组
            $return_arr['day'] = $arr; 
            if (!$data) {
                foreach ($arr as &$v) {
                    $max[]=0;
                    $min[]=0;
                }
                $return_arr['min'] = $min;
                $return_arr['max'] = $max;
                return $return_arr;
            }
            //这里是数据查询出未有血压值时 默认赋值
            foreach ($data as $key =>&$value) {
                if ($value['blood']) {
                    $blood = explode(';', $value['blood']);
                    $value['one'] = $blood[0];
                    $value['two'] = $blood[1];
                }else{
                    $value['one'] = 0;
                    $value['two'] = 0;
                }
            }

            //取得列的列表按照血压值排序
            foreach ($data as $key=>&$row){
                $volume[$key]  = $row['one'];
            }
            array_multisort($volume, SORT_DESC,$data);
            
            //去重
            $tmp_arr=[];
            foreach ($data as $e=>&$v) {
                if (in_array($v['name'], $tmp_arr)) {//搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
                    unset($data[$e]);
                } else {
                    $tmp_arr[] = $v['name'];
                }
            } 
            unset($tmp_arr);
            
            /*
            *这里是7天内某一天中未有数据时默认赋值
            *数组比较返回差集
            */
            //查找时间下标
            $arr_data = array_column($data,'name');
            $array_diff = array_diff($arr,$arr_data);
            foreach ($array_diff as $key=>&$value) {
                $diff[$key]['name'] = $value;
                $diff[$key]['one'] = 0;
                $diff[$key]['two'] = 0;
            }
    
            //数组合并
            $arr_val = array_merge_recursive($data,$diff);
            //按照日期排序
            foreach ($arr_val as $al=>&$se){
                $volume_arr[$al]  = $se['name'];
            }
            array_multisort($volume_arr, SORT_DESC,$arr_val);

            //转换图表所需格式
            foreach ($arr_val as $y =>&$l) {
                $max[]=$l['one'];
                $min[]=$l['two'];
            }
            $return_arr['min'] = $min;
            $return_arr['max'] = $max;
            return $return_arr;
        } catch (Exception $e) {
            Log::write('血压与睡眠健康数据图表查询异常: '.$e->getMessage(),'error');
            return false;
        }
    }

    /*
    *轨迹动画
    */
    public function GetGpsLog(){
        try {
            //查询定位记录
            $res = db::name('gpslog')->field('text')->select();
            foreach ($res as $key =>&$value) {
                $arr = explode(',',$value['text']);
                $res[$key]['lng'] = $arr[0];
                $res[$key]['lat'] = $arr[1];
                unset($value['text']);
            }
            return $res;
        } catch (Exception $e) {
            Log::write('轨迹动画查询异常: '.$e->getMessage(),'error');
            return false;
        }
    }

    /**
     * 导出积分核销模板
     */
    public function exportTemplet()
    {
        try {
            // excel的header头
            $header = ['姓名', '手机号', '性别', '年龄','身份证号码','地址'];
            // 文件名
            $fileName = '人员添加模板';
            F_export_excel($data=[], $header, $key=[], $fileName);
        } catch (\Exception $e) {
            Log::write('核销模板导出异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /**
     * 批量添加服务对象
     * @param $file
     * @return array
     */
    public function destoryClient($file)
    {
        try {
            set_time_limit(0);  // 防止excel数据过多执行时间长
            // 处理excel文件
            vendor('PHPExcel.PHPExcel.IOFactory');
            $excel = \PHPExcel_IOFactory::load($file,$encode = 'utf-8');
            $sheet = $excel->getSheet(0); // 获取表中第一个工作表 去除列名称所属行
            $excel_rows = $sheet->getHighestRow(); //取得总行数
            $insert_arr = [];   // 带插入的数组
            $time = time();
            for($i=2; $i<=$excel_rows; $i++){
                $name = $excel->getActiveSheet()->getCell("A" . $i)->getValue();        // 姓名
                $mobile = $excel->getActiveSheet()->getCell("B" .$i)->getValue();        // 身份证
                $sex = $excel->getActiveSheet()->getCell("C" .$i)->getValue();       // 核销的积分
                $age = $excel->getActiveSheet()->getCell("D" .$i)->getValue();      // 核销备注
                $id_number = $excel->getActiveSheet()->getCell("E" .$i)->getValue();      // 核销备注
                $address = $excel->getActiveSheet()->getCell("F" .$i)->getValue();      // 核销备注

                if (trim($sex) == '男'){
                    $sex = 1;
                }else{
                    $sex = 2;
                }
                $insert_arr[$i] = [
                    'name'=> $name ? substr(trim($name) , 0 , 10) : '',
                    'mobile'=>$mobile ? trim($mobile) : '',
                    'sex'=>$sex,
                    'age'=>$age ? intval(trim($age)) : 0,
                    'id_number'=>$id_number ? trim($id_number) : 0,
                    'address'=>$address ? trim($address) : '',
                    'create_time'=>$time
                ];
                $mobile = Db::name('client')->where(['mobile'=>$insert_arr['mobile']])
                    ->whereOr(['id_number'=>$insert_arr['id_number']])->value('id');
                if (!empty($mobile)){
                    unset($insert_arr[$i]);
                }

            }
            if(empty($insert_arr)){
                return ['code'=>2,'msg'=>'excel没有数据'];
            }
            Db::startTrans();
            // 插入添加记录
            $insert = Db::name('client')->insertAll($insert_arr);

            if($insert){
                // 删除excel文件
                unlink($file);
                Db::commit();
                return ['code'=>0,'msg'=>'添加服务对象成功，共添加'.count($insert_arr).'条'];
            }
            Db::rollback();
            return ['code'=>1,'msg'=>'添加服务对象失败'];
        } catch (\Exception $e) {
            Log::write('批量服务对象异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }

    /*
    *查询服务对象绑定的终端信息
    *$id int 服务对象ID
    */
    public function GetDeviceBindUserInfo($id){
        try {
            /*
            *由于智能终端分多种,所以pid必须是手表或者是手环
            */
            $where['uid'] = ['eq',$id];
            $where['is_binding'] = ['eq',1];
            $where['pid'] = ['in','1,2'];   
            $data = db::name('device')->where($where)->select();
            return $data;
        } catch (Exception $e) {
            Log::write('获取服务对象绑定智能终端异常异常：'.$e->getMessage(),'error');
            return ['code'=>-1,'msg'=>'服务器异常'];
        }
    }
}