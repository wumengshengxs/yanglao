<?php
/**
 * 易米云通api
 * Date: 2018/12/20
 * Time: 13:30
 */

namespace app\index\model;

class YiMi
{
    public $url = 'apiusertest.emic.com.cn';//url
    public $accountSid = 'c8ffb1a68ac9f0d179e3e1bb2b46de22';//主账号ID
    public $accountToken = '019f5c1306fd3f06637cc5d394eb1037';//主账户授权令牌
    public $appId = '1fd3711b74cd33388977b3aa0e8b4e65';//应用id
    public $softwareVersion = '20171106';//应用版本号
    protected $accounts;//账号请求方式
    protected $subAccountSid;//子账号
    protected $sigParameter;//API 验证参数
    protected $authorization;//head验证参数

    /**
     *构造所需的秘钥 参数
     * YiMi constructor.
     * @param null $accountSid 子账号操作需要传递id
     * @param null $accountToken 子账号操作需要传递Token
     */
    public function __construct($accountSid=null,$accountToken=null)
    {
        $time = date('YmdHis');//时间参数
        //判断主账号还是子账号 不传递子账号以及子账号token 默认为主账号
        $this->accountSid = !empty($accountSid) && !empty($accountToken) ? $accountSid : $this->accountSid;
        $this->accountToken = !empty($accountToken) && !empty($accountSid) ? $accountToken : $this->accountToken;
        $this->accounts = !empty($accountToken) && !empty($accountSid) ? 'SubAccounts' : 'Accounts' ;
        $this->authorization = base64_encode($this->accountSid.':'.$time);//验证信息Authorization
        $this->sigParameter = strtoupper(MD5($this->accountSid.$this->accountToken.$time));//验证
    }

    /**
     * 主账户管理功能接口 获取主账号信息
     * @return mixed|array
     * @operation AccountInfo
     */
    public function accountInfo()
    {
        $curlPost = [

        ];
        $operation = 'AccountInfo';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 应用和子账户管理功能接口 创建子账户
     * @param $param
     * @return mixed
     */
    public function createSubAccount($param)
    {
        $curlPost['createSubAccount'] = [
            'appId'=> $this->appId,//应用 Id
            'nickName'=> $param['nickname'],//子账号昵称
            'mobile'=> $param['mobile'],//子账号用户手机号码
            'email'=> $param['email'],//子账号用户邮件地址
        ];

        $operation = 'Applications/createSubAccount';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 查询子账户列表
     * @return mixed
     */
    public function subAccountList()
    {
        $curlPost['subAccountList'] = [
            'appId'=> $this->appId,//应用 Id
        ];
        $operation = 'Applications/subAccountList';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 查询子账户
     * @param $subAccountSid=>子账户Sid
     * @return mixed
     */
    public function subAccount($subAccountSid)
    {
        $curlPost['subAccount'] = [
            'appId'=> $this->appId,//应用 Id
            'subAccountSid'=> $subAccountSid,//子账户 Sid
        ];
        $operation = 'Applications/subAccount';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 删除子账户
     * @param $subAccountSid=>子账户Sid
     * @return mixed
     */
    public function dropSubAccount($subAccountSid)
    {
        $curlPost['dropSubAccount'] = [
            'appId'=> $this->appId,//应用 Id
            'subAccountSid'=> $subAccountSid,//子账户 Sid
        ];
        $operation = 'Applications/dropSubAccount';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 更新子账户
     * @param $param
     * @return mixed
     */
    public function updateSubAccount($param)
    {
        $curlPost['updateSubAccount'] = [
            'appId'=> $this->appId,//应用 Id
            'subAccountSid'=> $param['subAccountSid'],//子账户 Sid
            'nickName'=> $param['nickName'],//子账号昵称
            'mobile'=> $param['mobile'],//子账号用户手机号码
            'email'=> $param['email'],//子账号用户邮件地址
        ];
        $operation = 'Applications/updateSubAccount';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 设置呼出限制次数
     * @param $param
     * @return mixed
     */
    public function setCallLimit($param)
    {
        $curlPost['setCallLimit'] = [
            'appId'=> $this->appId,//应用 Id
            'limitType'=> $param['limitType'],//限制类型0-不限制(默认); 1-自然月+周一开 始计周; 2-自然月+周日开始计周; 3-非自然周 /月:当日前一周或月统计
            'dayLimit' => $param['dayLimit'],//每天呼叫次数限制(1-10)，0 表示不限制; 未输入表示保留上次的设置值。
            'weekLimit'=> $param['weekLimit'],//每周呼叫次数限制(1-25)，0 表示不限制; 未输入表示保留上次的设置值。
            'monthLimit'=> $param['monthLimit'],//每月呼叫次数限制(1-50)，0 表示不限制; 未输入表示保留上次的设置值。
        ];
        $operation = 'Applications/setCallLimit';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 下载应用话单
     * @param $param
     * @return mixed
     */
    public function billList($param)
    {
        $curlPost['billList'] = [
            'appId'=> $this->appId,//应用 Id
            'subAccountSid'=> $param['subAccountSid'],//子账户 ID
            'startTime' => $param['startTime'],//话单开始时间，格式:yyyymmddHHMMSS
            'endTime'=> $param['endTime'],//话单结束时间，格式:yyyymmddHHMMSS， 要求 endTime 和 startTime 的差值不超过 7 天
            'lastMaxId'=> $param['lastMaxId'],//上次返回话单中的 billId 最大值
            'maxNumber'=> $param['maxNumber'],//本次调用返回的最大话单数，范围 1-500，默 认为 100
        ];
        $operation = 'Applications/billList';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 获取指定话单详情
     * @param $callId=>呼叫Id
     * @return mixed
     */
    public function callDetail($callId)
    {
        $curlPost['callDetail'] = [
            'appId'=> $this->appId,//应用 Id
            'callId'=> $callId,//呼叫 Id
        ];
        $operation = 'Applications/callDetail';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 获取通话录音文件下载 Url
     * @param $callId=>呼叫Id
     * @return mixed
     */
    public function callRecordUrl($callId)
    {
        $curlPost['callRecordUrl'] = [
            'appId'=> $this->appId,//应用 Id
            'callId'=> $callId,//呼叫 Id
        ];
        $operation = 'Applications/callRecordUrl';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 查询用户信息
     * @param $phone=>用户绑定手机号(或其它号码) or 直线号码 二选一
     * @return mixed
     */
    public function queryUser($phone)
    {
        $curlPost['queryUser'] = [
            'appId'=> $this->appId,//应用 Id
            'callId'=> $phone,//用户绑定手机号(或其它号码)
        ];
        $operation = 'Applications/queryUser';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 云总机企业和用户管理功能接口 添加云总机企业
     * @param $param
     * @return mixed
     */
    public function addEnterprise($param)
    {
        $curlPost['addEnterprise'] = [
            'appId'=> $this->appId,//应用 Id
            'switchNumber'=> $param['switchNumber'],//云总机企业总机号码
            'number'=> $param['number'],//云总机企业管理员用户名
            'password'=> $param['password'],//云总机企业管理员密码
            'chargeMode'=> $param['chargeMode'],//计费模式
            'userData'=> $param['userData'],//用户私有数据，字母和数字的组合，最大长 读为 63。可用于通话状态推送鉴别
            'callreqUrl'=> $param['callreqUrl'],//用户呼叫请求和鉴权服务器 Url。
        ];
        $operation = 'Enterprises/addEnterprise';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 删除云总机企业
     * @return mixed
     */
    public function dropEnterprise()
    {
        $curlPost['dropEnterprise'] = [
            'appId'=> $this->appId,//应用 Id
        ];
        $operation = 'Enterprises/dropEnterprise';

        $data = $this->requestParam($operation,$curlPost);


        return json_decode($data,true);
    }

    /**
     * 获取空闲直线号码
     * @return mixed
     */
    public function freeNumbers()
    {
        $curlPost['freeNumbers'] = [
            'appId'=> $this->appId,//应用 Id
        ];
        $operation = 'Enterprises/freeNumbers';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 创建企业用户
     * @param $param
     * @return mixed
     */
    public function createUser($param)
    {
        $curlPost['createUser'] = [
            'appId'=> $this->appId,//应用 Id
            'workNumber'=> $param['workNumber'],//用户工号，与 phone 绑定，具有唯一性。如 果不使用工号，则可以为空
            'phone'=> $param['phone'],//用户绑定电话号码，要求号码长度至少为 10 位，
            'displayName'=> $param['displayName'],//用户显示名称
//            'directNumber'=> $param['directNumber'],//用户直线号码
//            'callTime'=> $param['callTime'],//用户呼叫时间限制
            'password'=> $param['password'],//用户密码
            'number'=> $param['number'],//用户分机号
        ];
        $operation = 'Enterprises/createUser';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 删除企业用户
     * @param $phone=>用户绑定电话号码
     * @return mixed
     */
    public function dropUser($phone)
    {
        $curlPost['dropUser'] = [
            'appId'=> $this->appId,//应用 Id
            'phone'=> $phone//用户绑定电话号码
        ];
        $operation = 'Enterprises/dropUser';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 更新企业用户信息
     * @param $workNumber=>用户绑定工号
     * @param $name=>用户显示名称
     * @return mixed
     */
    public function updateUser($workNumber,$name)
    {
        $curlPost['updateUser'] = [
            'appId'=> $this->appId,//应用 Id
            'workNumber'=> $workNumber,//用户绑定工号，要求号码长度至少为 10 位，
            'displayName'=> $name,//用户显示名称
//            'directNumber'=> $param['directNumber'],//用户直线号码
//            'callTime'=> $param['callTime'],//用户呼叫时间限制
        ];
        $operation = 'Enterprises/updateUser';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 更新企业用户密码
     * @param $workNumber=>用户绑定工号
     * @param $pass=>用户密码
     * @return mixed
     */
    public function updatePassword($workNumber,$pass)
    {
        $curlPost['updatePassword'] = [
            'appId'=> $this->appId,//应用 Id
            'workNumber'=> $workNumber,//用户绑定工号，要求号码长度至少为 10 位，
            'password'=> $pass,//用户密码。
        ];
        $operation = 'Enterprises/updatePassword';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 更新企业用户绑定号码
     * @param $workNumber=>用户绑定工号
     * @param $phone=>用户绑定电话号码
     * @return mixed
     */
    public function updateUserPhone($workNumber,$phone)
    {
        $curlPost['updateUserPhone'] = [
            'appId'=> $this->appId,//应用 Id
            'workNumber'=> $workNumber,//用户绑定工号
            'phone'=> $phone,//用户绑定电话号码。
        ];
        $operation = 'Enterprises/updateUserPhone';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 更新企业用户工号
     * @param $param
     * @return mixed
     */
    public function updateWorkNumber($param)
    {
        $curlPost['updateWorkNumber'] = [
            'appId'=> $this->appId,//应用 Id
            'number'=> $param['number'],//用户分机号
            'workNumber'=> $param['workNumber'],//工号。
        ];
        $operation = 'Enterprises/updateWorkNumber';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 获取用户信息
     * @param $phone=>用户绑定电话号码
     * @return mixed
     */
    public function userInfo($phone)
    {
        $curlPost['userInfo'] = [
            'appId'=> $this->appId,//应用 Id
            'phone'=> $phone,//用户绑定电话号码
        ];
        $operation = 'Enterprises/userInfo';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 获取企业最大用户数
     * @return mixed
     */
    public function getUserAmount()
    {
        $curlPost['getUserAmount'] = [
            'appId'=> $this->appId,//应用 Id
        ];
        $operation = 'Enterprises/getUserAmount';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 创建技能组
     * @param $groupName=>技能组名称
     * @return mixed
     */
    public function createGroup($groupName)
    {
        $curlPost['createGroup'] = [
            'appId'=> $this->appId,//应用 Id
            'groupName'=> $groupName,//技能组名称
        ];
        $operation = 'Enterprises/createGroup';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 删除技能组
     * @param $gid=>技能组 id
     * @return mixed
     */
    public function deleteGroup($gid)
    {
        $curlPost['deleteGroup'] = [
            'appId'=> $this->appId,//应用 Id
            'gid'=> $gid,//技能组名称
        ];
        $operation = 'Enterprises/deleteGroup';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 添加技能组用户
     * @param $param
     * @return mixed
     */
    public function addGroupUser($param)
    {
        $curlPost['addGroupUser'] = [
            'appId'=> $this->appId,//应用 Id
            'gid'=> $param['gid'],//技能组名称
            'phone'=> $param['phone'],//用户绑定号码
        ];
        $operation = 'Enterprises/addGroupUser';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 删除技能组用户
     * @param $param
     * @return mixed
     */
    public function deleteGroupUser($param)
    {
        $curlPost['deleteGroupUser'] = [
            'appId'=> $this->appId,//应用 Id
            'gid'=> $param['gid'],//技能组名称
            'phone'=> $param['phone'],//用户绑定号码
        ];
        $operation = 'Enterprises/deleteGroupUser';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 获取技能组用户
     * @param $gid=>技能组 id
     * @return mixed
     */
    public function getGroupUsers($gid)
    {
        $curlPost['getGroupUsers'] = [
            'appId'=> $this->appId,//应用 Id
            'gid'=> $gid,//技能组名称
        ];
        $operation = 'Enterprises/getGroupUsers';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 添加企业工作时间
     * @param $param
     * @return mixed
     */
    public function addWorkTime($param)
    {
        $curlPost['addWorkTime'] = [
            'appId'=> $this->appId,//应用 Id
            //(0-周日;1-周一;2-周二;3-周三; 4-周四;5-周五;6-周六)格式:用英文逗号隔开多个工作日，如 0,2,3
            'week'=> $param['week'],
            'startTime'=> $param['startTime'],//开始时间
            'endTime'=> $param['endTime'],//结束时间
        ];
        $operation = 'Enterprises/addWorkTime';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 删除企业工作时间
     * @param $param
     * @return mixed
     */
    public function deleteWorkTime($param)
    {
        $curlPost['deleteWorkTime'] = [
            'appId'=> $this->appId,//应用 Id
            'startTime'=> $param['startTime'],//开始时间
            'endTime'=> $param['endTime'],//结束时间
        ];
        $operation = 'Enterprises/deleteWorkTime';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 云总机号码保护配置接口 号码配对配置接口 设置配对号码
     * @param $param
     * @return mixed
     */
    public function createNumberPair($param)
    {
        $curlPost['createNumberPair'] = [
            'appId'=> $this->appId,//应用 Id
            'numberA'=> $param['numberA'],//用户号码 A
            'numberB'=> $param['numberB'],//用户号码 B
            'maxAge'=> $param['maxAge'],//生效时长(单位:秒，默认为 0，永久生效)
        ];
        $operation = 'Enterprises/createNumberPair';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 删除配对号码
     * @param $numberPairId=>配对号码 Id
     * @return mixed
     */
    public function dropNumberPair($numberPairId)
    {
        $curlPost['dropNumberPair'] = [
            'appId'=> $this->appId,//应用 Id
            'numberPairId'=> $numberPairId,//配对号码
        ];
        $operation = 'Enterprises/dropNumberPair';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 批量添加配对号码
     * @param $numberPairList
     * @return mixed
     * 批量导入配对号码内容:
     * 1) 每对号码包括三个参数:numberA，numberB 和 maxAge，以英文字符“,”隔开;
     * 2) 不同号码对之间以英文字符“;”隔开; 3) 号码对数量不得超过 500;
     * 例:15117946993,18251951206,3600;18551845038,1 8896550285,1800;15117942893,02557926535,50 00
     */
    public function addNumberPairList($numberPairList)
    {
        $curlPost['addNumberPairList'] = [
            'appId'=> $this->appId,//应用 Id
            'numberPairList'=> $numberPairList,//配对号码
        ];
        $operation = 'Enterprises/addNumberPairList';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 查询配对号码
     * @param $numberPairId
     * @return mixed
     */
    public function getNumberPair($numberPairId)
    {
        $curlPost['getNumberPair'] = [
            'appId'=> $this->appId,//应用 Id
            'numberPairId'=> $numberPairId,//配对号码
        ];
        $operation = 'Enterprises/getNumberPair';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 修改配对号码
     * @param $param
     * @return mixed
     */
    public function updateNumberPair($param)
    {
        $curlPost['updateNumberPair'] = [
            'appId'=> $this->appId,//应用 Id
            'numberPairId'=> $param['numberPairId'],//配对号码 Id(索引值)
            'numberA'=> $param['numberA'],//用户号码 A
            'numberB'=> $param['numberB'],//用户号码 B
            'useNumber'=> $param['useNumber'],//总机号码
            'maxAge'=> $param['maxAge'],//生效时长
        ];
        $operation = 'Enterprises/updateNumberPair';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 虚拟号码配置接口 添加虚拟号码
     * @param $param
     * @return mixed
     */
    public function addVirtualNumber($param)
    {
        $curlPost['addVirtualNumber'] = [
            'appId'=> $this->appId,//应用 Id
            'useNumber'=> $param['useNumber'],//总机号码
            'number'=> $param['number'],//分机号码
            'phone'=> $param['phone'],//客户真实号码
            'maxAge'=> $param['maxAge'],//生效时长(单位:秒，默认为 0，永久生效)
        ];
        $operation = 'Enterprises/addVirtualNumber';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 删除虚拟号码
     * @param $virtualNumberId=>虚拟号码 Id
     * @return mixed
     */
    public function dropVirtualNumber($virtualNumberId)
    {
        $curlPost['dropVirtualNumber'] = [
            'appId'=> $this->appId,//应用 Id
            'virtualNumberId'=> $virtualNumberId,//配对号码
        ];
        $operation = 'Enterprises/dropVirtualNumber';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 批量添加虚拟号码
     * @param $virtualNumberList
     * 批量导入虚拟号码内容:
     * 1) 每个虚拟号码包含四个参数，顺序为: useNumber,number,phone,maxAge(参数说 明参见添加虚拟号码接口)，参数之间以英 文字符“,”隔开;
     * 2) 最多可以输入 500 个虚拟号码;
     * 3) 每个虚拟号码之间以英文字符“;”隔开;
     * 例:02557926535,1111,18251951206,3600; 02557926535,3333,18551845038,1800;02557856 474,3432454,15117942893,5000
     * @return mixed
     */
    public function addVirtualNumberList($virtualNumberList)
    {
        $curlPost['addVirtualNumberList'] = [
            'appId'=> $this->appId,//应用 Id
            'virtualNumberList'=> $virtualNumberList,//配对号码
        ];
        $operation = 'Enterprises/addVirtualNumberList';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 查询虚拟号码
     * @param $virtualNumberId =>虚拟号码 Id
     * @return mixed
     */
    public function getVirtualNumber($virtualNumberId)
    {
        $curlPost['getVirtualNumber'] = [
            'appId'=> $this->appId,//应用 Id
            'virtualNumberId'=> $virtualNumberId,//配对号码
        ];
        $operation = 'Enterprises/getVirtualNumber';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 修改虚拟号码
     * @param $param
     * @return mixed
     */
    public function updateVirtualNumber($param)
    {
        $curlPost['updateVirtualNumber'] = [
            'appId'=> $this->appId,//应用 Id
            'virtualNumberId'=> $param['virtualNumberId'],//虚拟号码 Id(索引值)
            'useNumber'=> $param['useNumber'],//总机号码
            'number'=> $param['number'],//分机号码
            'phone'=> $param['phone'],//用户号码
            'maxAge'=> $param['maxAge'],//生效时长(单位:秒，默认为 0，永久生效)
        ];
        $operation = 'Enterprises/updateVirtualNumber';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 异号呼出模式配置接口 添加服务号码
     * @param $serviceNumber
     * $serviceNumber 服务号码，可以批量添加，号码之间以英文 字符“,”隔开
     * @return mixed
     */
    public function addServiceNumber($serviceNumber)
    {
        $curlPost['addServiceNumber'] = [
            'appId'=> $this->appId,//应用 Id
            'serviceNumber'=> $serviceNumber,//配对号码
        ];
        $operation = 'Enterprises/addServiceNumber';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 删除服务号码
     * @param $serviceNumber
     * $serviceNumber 服务号码，只能单条删除
     * @return mixed
     */
    public function delServiceNumber($serviceNumber)
    {
        $curlPost['delServiceNumber'] = [
            'appId'=> $this->appId,//应用 Id
            'serviceNumber'=> $serviceNumber,//配对号码
        ];
        $operation = 'Enterprises/delServiceNumber';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 设置呼叫总机号码
     * @param $param
     * @return mixed
     */
    public function setSwitchNumber($param)
    {
        $curlPost['setSwitchNumber'] = [
            'appId'=> $this->appId,//应用 Id
            'switchNumber'=> $param['switchNumber'],//总机号码(M/N)
            'type'=> $param['type'],//总机号码类型:0-呼入号码(M);1-呼出号 码(N)，默认为 1
        ];
        $operation = 'Enterprises/setSwitchNumber';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 删除呼叫总机号码
     * @param $switchNumber=>总机号码(M/N)
     * @return mixed
     */
    public function delSwitchNumber($switchNumber)
    {
        $curlPost['delSwitchNumber'] = [
            'appId'=> $this->appId,//应用 Id
            'switchNumber'=> $switchNumber,//总机号码(M/N)
        ];
        $operation = 'Enterprises/delSwitchNumber';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 呼叫中心接口  签入
     * @param $param
     * @return mixed
     */
    public function signIn($param)
    {
        $curlPost['signIn'] = [
            'appId'=> $this->appId,//应用 Id
            'workNumber'=> $param['workNumber'],//座席工号，
            'gid'=> $param['gid'],//坐席所属技能组 id在座席属于多个技能组 的情况下，指定当前登录技能组
            'type'=> $param['type'],//0-VOIP 模式或回拨话机(默认) 1-sip 话机
            //type=0，如果座席使用回拨模式，此处输 入回拨话机的号码，话机号码必须是真实 的手机号、物联网卡号或固话号码;
            //type=0，如果座席使用 VoIP 模式，该参 数无需输入，或为空字符串
            //type=1，座席使用 sip 话机模式时，该参 数为 sip 话机号,
            'deviceNumber'=> $param['deviceNumber'],//座席设备号码:取值范围 4-6 位数字 不能为空
        ];
        $operation = 'CallCenter/signIn';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 签出
     * @param $workNumber=>座席工号
     * @return mixed
     */
    public function signOff($workNumber)
    {
        $curlPost['signOff'] = [
            'appId'=> $this->appId,//应用 Id
            'workNumber'=> $workNumber,//座席工号，
        ];
        $operation = 'CallCenter/signOff';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 改变座席模式
     * @param $param
     * @return mixed
     */
    public function changeMode($param)
    {
        $curlPost['changeMode'] = [
            'appId'=> $this->appId,//应用 Id
            'workNumber'=> $param['workNumber'],//座席工号，
            'mode'=> $param['mode'],//0-固定座席(默认值)，1-值班座席
            //1) 座席使用回拨模式时，此处必须输入回 拨话机的号码，话机号码必须是真实的手 机号、物联网卡号或固话号码;
            //2) 座席使用 VoIP 模式时，该参数无需输入， 或为空字符串。
            'deviceNumber'=> $param['deviceNumber'],//座席设备号码
        ];
        $operation = 'CallCenter/changeMode';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 改变座席状态
     * @param $param
     * @return mixed
     */
    public function changeStatus($param)
    {
        $curlPost['changeStatus'] = [
            'appId'=> $this->appId,//应用 Id
            'workNumber'=> $param['workNumber'],//座席工号，
            'status'=> $param['status'],//0-示闲(默认);1-示忙;2-整理
        ];
        $operation = 'CallCenter/changeStatus';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 座席呼出
     * @param $param
     * @return mixed
     */
    public function callOut($param)
    {
        $curlPost['callOut'] = [
            'appId'=> $this->appId,//应用 Id
            'workNumber'=> $param['workNumber'],//座席工号，
            'outNumber'=> $param['outNumber'],//呼出用总机号码
            'to'=> $param['to'],//客户号码
            'userData'=> $param['userData'],//用户数据
        ];
        $operation = 'CallCenter/callOut';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 挂断通话
     * @param $param
     * @return mixed
     */
    public function callCancel($param)
    {
        $curlPost['callCancel'] = [
            'appId'=> $this->appId,//应用 Id
            'workNumber'=> $param['workNumber'],//座席工号，
            'callId'=> $param['callId'],//呼叫 ID
        ];
        $operation = 'CallCenter/callCancel';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 呼叫转移
     * @param $param
     * @return mixed
     */
    public function callTransfer($param)
    {
        $curlPost['callTransfer'] = [
            'appId'=> $this->appId,//应用 Id
            'workNumber'=> $param['workNumber'],//座席工号，
            'callId'=> $param['callId'],//呼叫 ID
            'toWorkNumber'=> $param['toWorkNumber'],//转移目标座席工号
            'toGid'=> $param['toGid'],//转移目标座席所属技能组 id (在座席属于多 个技能组的情况下，指定一个技能组)
        ];
        $operation = 'CallCenter/callTransfer';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 通话保持
     * @param $param
     * @return mixed
     */
    public function keepCall($param)
    {
        $curlPost['keepCall'] = [
            'appId'=> $this->appId,//应用 Id
            'workNumber'=> $param['workNumber'],//座席工号，
            'callId'=> $param['callId'],//呼叫 ID
        ];
        $operation = 'CallCenter/keepCall';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 通话继续
     * @param $param
     * @return mixed
     */
    public function resumeCall($param)
    {
        $curlPost['resumeCall'] = [
            'appId'=> $this->appId,//应用 Id
            'workNumber'=> $param['workNumber'],//座席工号，
            'callId'=> $param['callId'],//呼叫 ID
        ];
        $operation = 'CallCenter/resumeCall';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 通话监听
     * @param $param
     * @return mixed
     */
    public function callMonitor($param)
    {
        $curlPost['callMonitor'] = [
            'appId'=> $this->appId,//应用 Id
            'workNumber'=> $param['workNumber'],//班长座席工号，
            'callId'=> $param['callId'],//呼叫 ID(该呼叫必须是本组座席的呼叫)
        ];
        $operation = 'CallCenter/callMonitor';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 通话强插
     * @param $param
     * @return mixed
     */
    public function callInsert($param)
    {
        $curlPost['callInsert'] = [
            'appId'=> $this->appId,//应用 Id
            'workNumber'=> $param['workNumber'],//班长座席工号，
            'callId'=> $param['callId'],//呼叫 ID(该呼叫必须是本组座席的呼叫)
        ];
        $operation = 'CallCenter/callInsert';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 获取技能组用户列表
     * @param $gid
     * 本接口和 Enterprises/getGroupUsers 一样，
     * 都是用户获取技能组用户列表，但本接口比 Enterprises/getGroupUsers
     * 多出了一 些状态参数
     * @return mixed
     */
    public function getCallGroupUsers($gid)
    {
        $curlPost['getGroupUsers'] = [
            'appId'=> $this->appId,//应用 Id
            'gid'=> $gid,//呼叫 ID(该呼叫必须是本组座席的呼叫)
        ];
        $operation = 'CallCenter/getGroupUsers';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 获取座席状态
     * @param $workNumber=>座席工号
     * @return mixed
     */
    public function getUserState($workNumber)
    {
        $curlPost['getUserState'] = [
            'appId'=> $this->appId,//应用 Id
            'workNumber'=> $workNumber,//座席工号，
        ];
        $operation = 'CallCenter/getUserState';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     *  语音通讯功能接口 双向回拨功能
     * 传递子账号则请求子账号 传递主账号则请求主账号
     * @param $param
     * @return mixed
     */
    public function callBack($param)
    {
        $curlPost['callBack'] = [
            'appId'=> $this->appId,//应用 Id
            'from'=> $param['from'],//主叫号码
            'to'=> $param['to'],//被叫号码
            #0-显示总机固话号码(默认方式); 1-显示主叫号码(需运营商授权才可生效);
            'displayMode'=> $param['displayMode'],//被叫来电号码显示方式
            #0- 不开启按键反馈功能(默认方式) 1- 获取被叫按键反馈2- 获取主叫按键反馈3- 获取主叫、被叫按键反馈
            'getFeedBack'=> $param['getFeedBack'],//通话过程中用户的按键反馈
            #0– 一键模式(默认方式);1– 普通模式(按“#”键结束)
            'feedBackMode'=> $param['feedBackMode'],//通话过程中用户按键方式
            'keyRange'=> $param['keyRange'],//用户按键的有效范围，用逗号隔开字符。一键反馈模式下必须输入该参数
            'callLimitTime'=> $param['callLimitTime'],//通话时长限制(单位:秒)，默认为 0(不限 时)
            'userData'=> $param['userData'],//用户透传数据，回调时返回给用户，可用于 认证
        ];
        $operation = 'Calls/callBack';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 电话直拨功能
     * 传递子账号则请求子账号 传递主账号则请求主账号
     * @param $param
     * @return mixed
     */
    public function directCall($param)
    {
        $curlPost['directCall'] = [
            'appId'=> $this->appId,//应用 Id
            'from'=> $param['from'],//主叫号码
            'to'=> $param['to'],//被叫号码
            'userData'=> $param['userData'],//用户透传数据，回调时返回给用户，可用于 认证
        ];
        $operation = 'Calls/directCall';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 语音验证码功能
     * 传递子账号则请求子账号 传递主账号则请求主账号
     * @param $param
     * @return mixed
     */
    public function voiceCode($param)
    {
        $curlPost['voiceCode'] = [
            'appId'=> $this->appId,//应用 Id
            'verifyCode'=> $param['verifyCode'],//验证号码，现规定为 4~8 位数字
            'to'=> $param['to'],//验证码拨叫号码
            'userData'=> $param['userData'],//用户透传数据，回调时返回给用户，可用于 认证
        ];
        $operation = 'Calls/voiceCode';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 语音通知功能
     * 传递子账号则请求子账号 传递主账号则请求主账号
     * @param $param
     * @return mixed
     */
    public function voiceNotify($param)
    {
        $curlPost['voiceNotify'] = [
            'appId'=> $this->appId,//应用 Id
            'voiceId'=> $param['verifyCode'],//验语音文件 Id，上传文件后返回
            #1) 被叫号码最多可以有 500 个;2) 号码之间用英文字符“,”隔开;3) 号码不可重复，否则直接返回错误。
            'to'=> $param['to'],//语音通知被叫号码
            #1) 多个变量之间用英文字符“,”隔开; 2) 变量数必须与模板变量数一致;3) 如果被叫号码不止一个，则:a)
            #如果所有号码的播放内容相同，则使 用同一组变量;b) 如果多个号码播放内容不一致，则必 须使用多组变量，
            #每组变量之间用英 文字母“;”隔开，且变量组数必须与 被叫号码数相等。
            'content'=> $param['content'],//语音文本模板变量内容(使用语音文本模板 时必须输入)
            #0- 不开启按键反馈功能(默认方式) 1- 获取被叫按键反馈
            'getFeedBack'=> $param['getFeedBack'],//通话过程中用户的按键反馈:
            #0– 一键模式(默认方式);1– 普通模式(按“#”键结束)
            'feedBackMode'=> $param['feedBackMode'],//通话过程中用户按键方式:
            'keyRange'=> $param['keyRange'],//一键反馈模式下，用户按键的有效范围，用 逗号隔开字符。默认情况下按键范围为所有 数字以及*和#。
            'repeatTimes'=> $param['repeatTimes'],//按键输入错误，并􏰀示重新输入的次数，默 认为 3 次，有效范围:0-5
            'keyWaitTime'=> $param['keyWaitTime'],//等待按键输入的时间，默认为 8 秒，有效范 围为 5-20s
            'userData'=> $param['userData'],//用户透传数据，回调时返回给用户，可用于 认证
        ];
        $operation = 'Calls/voiceNotify';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 多方通话功能
     * 传递子账号则请求子账号 传递主账号则请求主账号
     * 可支持最多 16 方通话(包括主叫)。
     * @param $param
     * @return mixed
     */
    public function callBacks($param)
    {
        $curlPost['callBack'] = [
            'appId'=> $this->appId,//应用 Id
            'from'=> $param['from'],//主叫号码
            'to'=> $param['to'],//被叫号码 可以是多个号码每个号码之间用逗号隔开。
            #0-显示总机固话号码(默认方式); 1-显示主叫号码(需运营商授权才可生效);
            'displayMode'=> $param['displayMode'],//被叫来电号码显示方式
            #0- 不开启按键反馈功能(默认方式) 1- 获取被叫按键反馈2- 获取主叫按键反馈3- 获取主叫、被叫按键反馈
            'getFeedBack'=> $param['getFeedBack'],//通话过程中用户的按键反馈
            #0– 一键模式(默认方式);1– 普通模式(按“#”键结束)
            'feedBackMode'=> $param['feedBackMode'],//通话过程中用户按键方式
            'keyRange'=> $param['keyRange'],//用户按键的有效范围，用逗号隔开字符。一键反馈模式下必须输入该参数
            'callLimitTime'=> $param['callLimitTime'],//通话时长限制(单位:秒)，默认为 0(不限 时)
            'userData'=> $param['userData'],//用户透传数据，回调时返回给用户，可用于 认证
        ];
        $operation = 'Calls/callBack';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 通话取消功能
     * 传递子账号则请求子账号 传递主账号则请求主账号
     * @param $callId=>呼叫 Id
     * @return mixed
     */
    public function callsCallCancel($callId)
    {
        $curlPost['callCancel'] = [
            'appId'=> $this->appId,//应用 Id
            'callId'=> $callId,//呼叫 Id，
        ];
        $operation = 'Calls/callCancel';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 语音文件管理功能接口 上传语音文件
     * @return mixed
     */
    public function uploadVoice()
    {
        $curlPost = [

        ];
        $operation = 'Voice/uploadVoice';

        $data = $this->fileRequest($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 删除语音文件
     * @param $voiceId
     * @return mixed
     */
    public function deleteVoice($voiceId)
    {
        $curlPost['callCancel'] = [
            'appId'=> $this->appId,//应用 Id
            'voiceId'=> $voiceId,//呼叫 Id，
        ];
        $operation = 'Voice/deleteVoice';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 上传语音文本
     * @param $param
     * @return mixed
     */
    public function uploadText($param)
    {
        $curlPost['uploadText'] = [
            'appId'=> $this->appId,//应用 Id
            'text'=> $param['text'],//语音文本或模板，汉字采用 UTF-8 编码，长 度默认为 500(字节数)。最大长度可以在后 台配置(应用相关)。
            'maxAge'=> $param['maxAge'],//最大生效时间(单位为秒，默认为 1800s)， 如果为 0，则永久生效。
        ];
        $operation = 'Voice/uploadText';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 删除语音文本
     * @param $textId=>语音文本 Id
     * @return mixed
     */
    public function deleteText($textId)
    {
        $curlPost['deleteText'] = [
            'appId'=> $this->appId,//应用 Id
            'textId'=> $textId,//语音文本 Id
        ];
        $operation = 'Voice/deleteText';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 手机短信发送接口 添加短信签名
     * @param $param
     * @return mixed
     */
    public function addSmsSig($param)
    {
        $curlPost['addSmsSig'] = [
            'appId'=> $this->appId,//应用 Id
            'signature'=> $param['signature'],//短信签名，3-8 字符，utf-8 编码
            'type'=> $param['type'],//签名类型:0-普通签名;1-验证码签名
        ];
        $operation = 'Sms/addSmsSig';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 删除短信签名
     * @param $signatureId=>签名 Id
     * @return mixed
     */
    public function delSmsSig($signatureId)
    {
        $curlPost['delSmsSig'] = [
            'appId'=> $this->appId,//应用 Id
            'signatureId'=> $signatureId,//签名 Id，
        ];
        $operation = 'Sms/delSmsSig';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 添加短信模板
     * @param $param
     * @return mixed
     */
    public function addSmsTempl($param)
    {
        $curlPost['addSmsTempl'] = [
            'appId'=> $this->appId,//应用 Id
            'type'=> $param['type'],//模板类型:0-普通模板，1-验证码模板
            'textType'=> $param['textType'],//模板文字类型:0-纯文本模板(默认)1-带参数模板(验证码模板必须带参数{vc})
            'templText'=> $param['templText'],//模板内容:UTF-8 编码字符;
            'signatureId'=> $param['signatureId'],//签名 Id

        ];
        $operation = 'Sms/addSmsTempl';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 删除短信模板
     * @param $templateId=>签名id
     * @return mixed
     */
    public function delSmsTempl($templateId)
    {
        $curlPost['delSmsTempl'] = [
            'appId'=> $this->appId,//应用 Id
            '$templateId'=> $templateId,//签名 Id，
        ];
        $operation = 'Sms/delSmsTempl';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     *  模板与签名绑定
     * @param $param
     * @return mixed
     */
    public function bindSmsSig($param)
    {
        $curlPost['bindSmsSig'] = [
            'appId'=> $this->appId,//应用 Id
            'templateId'=> $param['templateId'],//模板 Id
            'signatureId'=> $param['signatureId'],//签名 Id
        ];
        $operation = 'Sms/bindSmsSig';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 模板与签名解绑
     * @param $param
     * @return mixed
     */
    public function unbindSmsSig($param)
    {
        $curlPost['unbindSmsSig'] = [
            'appId'=> $this->appId,//应用 Id
            'templateId'=> $param['templateId'],//模板 Id
            'signatureId'=> $param['signatureId'],//签名 Id
        ];
        $operation = 'Sms/unbindSmsSig';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 发送通知类短信
     * @param $param
     * @return mixed
     */
    public function smsNotify($param)
    {
        $curlPost['smsNotify'] = [
            'appId'=> $this->appId,//应用 Id
            'templateId'=> $param['templateId'],//模板 Id
            'signatureId'=> $param['signatureId'],//签名 Id
            #多个号码用英文逗号 隔开，逗号两侧不要添加空格，号码数量最 多为 10 个。
            'to'=> $param['to'],//短信通知的接收号码，
            #匹配短信模板的参数,以逗号隔开(加上模板 长度不要超出 500 字符;参数个数必须与模 板匹配)
            'content'=> $param['content'],//匹配短信模板的参数
        ];
        $operation = 'Sms/smsNotify';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 发送验证码短信
     * @param $param
     * @return mixed
     */
    public function smsVerifyCode($param)
    {
        $curlPost['smsVerifyCode'] = [
            'appId'=> $this->appId,//应用 Id
            'templateId'=> $param['templateId'],//模板 Id
            'signatureId'=> $param['signatureId'],//签名 Id
            #多个号码用英文逗号 隔开，逗号两侧不要添加空格，号码数量最 多为 10 个。
            'to'=> $param['to'],//短信通知的接收号码
            #匹配短信模板的参数,以逗号隔开(加上模板 长度不要超出 500 字符;参数个数必须与模板匹配)
            'content'=> $param['content'],//匹配短信模板的参数
            'verifyCode'=> $param['verifyCode'],//验证码参数，4-8 位数字,
        ];
        $operation = 'Sms/smsNotify';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 批量外呼接口 创建外呼任务
     * @param $param
     * @return mixed
     */
    public function createTask($param)
    {
        $curlPost['createTask'] = [
            'appId'=> $this->appId,//应用 Id
            'name'=> $param['name'],//任务名称
            #多个请使用英文逗号隔开(自动去重处理)上限为 100 个
            'workNumbers'=> $param['workNumbers'],//坐席工号
            #0.8~1.2，保留小数点后一位
            'callRate'=> $param['callRate'],//外呼速率默认 0.8
            #和外呼结束时间搭配使用，表示同一天内的 允许批量外呼的时间段，不得早于结束时间不得早于 08:00，格式例:09:30 默认:08:00
            'preStartTime'=> $param['preStartTime'],//预设外呼开始时间
            #和外呼结束时间搭配使用，表示同一天内的 允许批量外呼的时间段，不得晚于开始时间不得晚于 20:00，格式例:09:30 默认:20:00
            'preEndTime'=> $param['preEndTime'],//预设外呼结束时间
            'autoAnswer'=> $param['autoAnswer'],//是否自动接听 0-关闭(默认)，1-开启
        ];
        $operation = 'BatchCalls/createTask';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 删除外呼任务
     * @param $taskId
     * @return mixed
     */
    public function deleteTask($taskId)
    {
        $curlPost['deleteTask'] = [
            'appId'=> $this->appId,//应用 Id
            'taskId'=> $taskId,//任务 Id
        ];
        $operation = 'BatchCalls/deleteTask';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 更新外呼任务
     * @param $param
     * @return mixed
     */
    public function updateTask($param)
    {
        $curlPost['updateTask'] = [
            'appId'=> $this->appId,//应用 Id
            'name'=> $param['name'],//任务名称
            'taskId'=> $param['taskId'],//任务 Id
            #多个请使用英文逗号隔开(自动去重处理)上限为 100 个
            'workNumbers'=> $param['workNumbers'],//坐席工号
            #0.8~1.2，保留小数点后一位
            'callRate'=> $param['callRate'],//外呼速率默认 0.8
            #和外呼结束时间搭配使用，表示同一天内的 允许批量外呼的时间段，不得早于结束时间不得早于 08:00，格式例:09:30 默认:08:00
            'preStartTime'=> $param['preStartTime'],//预设外呼开始时间
            #和外呼结束时间搭配使用，表示同一天内的 允许批量外呼的时间段，不得晚于开始时间不得晚于 20:00，格式例:09:30 默认:20:00
            'preEndTime'=> $param['preEndTime'],//预设外呼结束时间
            'autoAnswer'=> $param['autoAnswer'],//是否自动接听 0-关闭(默认)，1-开启
        ];
        $operation = 'BatchCalls/updateTask';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 添加批次
     * @param $param
     * @return mixed
     */
    public function addNewBatch($param)
    {
        $curlPost['addNewBatch'] = [
            'appId'=> $this->appId,//应用 Id
            'taskId'=> $param['taskId'],//任务 Id
            #2W 条为上限 同一任务的重复任务号码自动去重
            'tels'=> $param['tels'],//任务号码 多个请使用英文逗号隔开
        ];
        $operation = 'BatchCalls/addNewBatch';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 查询批次状态
     * @param $param
     * @return mixed
     */
    public function getBatchStatus($param)
    {
        $curlPost['getBatchStatus'] = [
            'appId'=> $this->appId,//应用 Id
            'taskId'=> $param['taskId'],//任务 Id
            'batchId'=> $param['batchId'],//批次 Id
        ];
        $operation = 'BatchCalls/getBatchStatus';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 删除批次号码
     * @param $param
     * @return mixed
     */
    public function deleteBatchTels($param)
    {
        $curlPost['deleteBatchTels'] = [
            'appId'=> $this->appId,//应用 Id
            'taskId'=> $param['taskId'],//任务 Id
            'batchId'=> $param['batchId'],//批次 Id
            #多个请使用英文逗号隔开 2W 条为上限
            'tels'=> $param['tels'],//任务号码
        ];
        $operation = 'BatchCalls/deleteBatchTels';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 查询批次号码
     * @param $param
     * @return mixed
     */
    public function getBatchTels($param)
    {
        $curlPost['getBatchTels'] = [
            'appId'=> $this->appId,//应用 Id
            'taskId'=> $param['taskId'],//任务 Id
            'batchId'=> $param['batchId'],//批次 Id
            #1-1000 的正整数 默认 10
            'limit'=> $param['limit'],//调用一次接口最多可以获取的任务号码数目
            #默认 0
            'offset'=> $param['offset'],//从第几条开始(按任务号码添加的顺序查询)
        ];
        $operation = 'BatchCalls/getBatchTels';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     *启动外呼任务
     * @param $taskId=>任务 Id
     * @return mixed
     */
    public function startTask($taskId)
    {
        $curlPost['startTask'] = [
            'appId'=> $this->appId,//应用 Id
            'taskId'=> $taskId,//任务 Id
        ];
        $operation = 'BatchCalls/startTask';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 暂停外呼任务
     * @param $taskId=>任务 Id
     * @return mixed
     */
    public function pauseTask($taskId)
    {
        $curlPost['pauseTask'] = [
            'appId'=> $this->appId,//应用 Id
            'taskId'=> $taskId,//任务 Id
        ];
        $operation = 'BatchCalls/pauseTask';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 结束外呼任务
     * @param $taskId=>任务 Id
     * @return mixed
     */
    public function stopTask($taskId)
    {
        $curlPost['stopTask'] = [
            'appId'=> $this->appId,//应用 Id
            'taskId'=> $taskId,//任务 Id
        ];
        $operation = 'BatchCalls/stopTask';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 监控外呼任务
     * @param $taskId=>任务 Id
     * @return mixed
     */
    public function monitorTask($taskId)
    {
        $curlPost['monitorTask'] = [
            'appId'=> $this->appId,//应用 Id
            'taskId'=> $taskId,//任务 Id
        ];
        $operation = 'BatchCalls/monitorTask';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 获取外呼任务信息
     * @param $taskId=>任务 Id
     * @return mixed
     */
    public function getTaskInfo($taskId)
    {
        $curlPost['getTaskInfo'] = [
            'appId'=> $this->appId,//应用 Id
            'taskId'=> $taskId,//任务 Id
        ];
        $operation = 'BatchCalls/getTaskInfo';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 获取外呼任务列表
     * @param $param
     * @return mixed
     */
    public function getTaskList($param)
    {
        $curlPost['getTaskList'] = [
            'appId'=> $this->appId,//应用 Id
            #0-未开始 1-正在进行 2-暂停 4-已完成 5- 已逾期 6-企业为关闭状态
            'status'=> $param['status'],//任务状态
            #格式:yyyymmddHHMMSS必选和结束时间一起使用 开始时间不能晚于结 束时间开始时间<=批次创建时间<=结束时间
            'startTime'=> $param['startTime'],//开始时间，用于任务创建时间的筛选
            #格式:yyyymmddHHMMSS和开时时间一起使用 开始时间不能晚于结 束时间开始时间<=批次创建时间<=结束时间
            'endTime'=> $param['endTime'],//结束时间，用于任务创建时间的筛选
            #取值范围 1-1000默认 100
            'maxNumber'=> $param['maxNumber'],//调用一次接口最多可获得的列表条数
        ];
        $operation = 'BatchCalls/getTaskList';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 获取外呼任务批次信息
     * @param $param
     * @return mixed
     */
    public function getBatchInfo($param)
    {
        $curlPost['getBatchInfo'] = [
            'appId'=> $this->appId,//应用 Id
            'taskId'=> $param['taskId'],//任务 Id
            'batchId'=> $param['batchId'],//批次 Id
        ];
        $operation = 'BatchCalls/getBatchInfo';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 获取外呼任务批次列表
     * @param $param
     * @return mixed
     */
    public function getBatchList($param)
    {
        $curlPost['getBatchList'] = [
            'appId'=> $this->appId,//应用 Id
            'taskId'=> $param['taskId'],//任务 Id
            #0-未开始(导入成功) 1-正在进行 2-暂停 4-已完成 5-已逾期 6-企业为关闭状态 7- 正在导入 8-导入失败
            'status'=> $param['status'],//任务状态:
            #格式:yyyymmddHHMMSS必选和结束时间一起使用 开始时间不能晚于结 束时间开始时间<=批次创建时间<=结束时间
            'startTime'=> $param['startTime'],//开始时间，用于批次创建时间的筛选
            #格式:yyyymmddHHMMSS和开时时间一起使用 开始时间不能晚于结 束时间开始时间<=批次创建时间<=结束时间
            'endTime'=> $param['endTime'],//结束时间，用于批次创建时间的筛选
            'maxNumber'=> $param['maxNumber'],//调用一次接口最多可获得的列表条数取值范围 1-1000默认 100
        ];
        $operation = 'BatchCalls/getBatchList';

        $data = $this->requestParam($operation,$curlPost);

        return json_decode($data,true);
    }

    /**
     * 提交内容
     * @param $operation=>请求的业务操作
     * @param $curlPost=>传输数据 array
     * @return mixed
     */
    public function requestParam($operation,$curlPost)
    {
        $postUrl = 'http://'.$this->url.'/'.$this->softwareVersion.'/'.
            $this->accounts.'/'.$this->accountSid.'/'.$operation.'?sig='.$this->sigParameter;
        $curlPost = json_encode($curlPost);
        $number = strlen($curlPost);
        $headerArray = [
            'Host:'.$this->url,
            'Content-Type:application/json;charset=utf-8',//包体内容封装格式 json
            'Accept:application/json',//云总机号码保护配置接口 json
            'Content-Length:'.$number,//Http 净荷数据长度
            'Authorization:'.$this->authorization//验证信息
        ];

        return $this->postUrl($postUrl,$headerArray,$curlPost);
    }

    /**
     * 文件上传提交内容
     * @param $operation=>请求的业务操作
     * @param $curlPost=>传输数据 array
     * @param $maxAge=>传输时间 默认1800
     * @return mixed
     */
    public function fileRequest($operation,$curlPost,$maxAge=1800)
    {
        $postUrl = 'http://'.$this->url.'/'.$this->softwareVersion.'/'.$this->accounts.'/'.
            $this->accountSid.'/'.$operation.'?sig='.$this->sigParameter.'&appId='.$this->appId.'f&maxAge='.$maxAge;
        $curlPost = json_encode($curlPost);
        $number = strlen($curlPost);
        $headerArray = [
            'Host:'.$this->url,
            'Content-Type: application/octet-stream',//包体内容封装格式 json
            'Accept:application/json',//云总机号码保护配置接口 json
            'Content-Length:'.$number,//Http 净荷数据长度
            'Authorization:'.$this->authorization//验证信息
        ];

        return $this->postUrl($postUrl,$headerArray,$curlPost);
    }

    /**
     * curl post传输
     * @param $postUrl=>请求地址
     * @param $headerArray=>请求head
     * @param $curlPost=>传输数据 array
     * @return mixed
     *
     */
    public function postUrl($postUrl,$headerArray,$curlPost)
    {
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$postUrl);//指定网页
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headerArray);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);

        return $data;
    }

    /**
     * 返回错误信息
     * @param $code=>错误号码
     * @return mixed
     */
    public function getMsgError($code)
    {
        $msg = [
            100000 => '目前尚不能供的待开发功能',
            100001 => '内部数据库访问失败',
            100002 => '上传语音文件时创建目录失败',
            100003 => '上传语音文件时存储文件失败',
            100004 => '系统内存分配失败',
            100500 => '创建云总机企业分机用户失败',
            100501 => '更新云总机企业分机用户信息失败',
            100502 => '调用云总机 EP_PROFILE 接口失败',
            100503 => '获取云总机企业(用户)信息失败',
            100504 => '删除云总机企业分机失败',
            100505 => '与云总机的 HTTP 连接失败，或返回错误值',
            100506 => '向云总机获取通话录音失败',
            100507 => '向云总机申请直拨通话时，未返回总机号',
            100508 => '向云总机获取用户信息失败',
            100509 => '云总机创建技能组失败',
            100510 => '云总机删除技能组失败',
            100511 => '云总机添加技能组用户失败',
            100512 => '云总机删除技能组用户失败',
            100513 => '云总机呼叫中心接口返回错误',
            100514 => '云总机更新工作时间失败',
            100515 => '云总机删除工作时间失败',
            100516 => '云总机未获得访问录音服务器的令牌',
            100517 => '云总机访问录音授权服务器失败',
            100518 =>  '云总机企业不存在',
            100519 => '云总机没有分机号可分配',
            100520 => '云总机没有设置工作时间',
            100521 => '非法的工作时间',
            100522 => '云总机不存在该工作时间方案',
            100523 => '向呼叫中心企业签入失败',
            100524 => '未开启呼叫中心功能',
            100525 => 'VoIP 模式下,未打开坐席客户端',//VoIP 模式下，座席客户端(软电话)未注册
            100526 => '被叫号码超过呼叫次数限制(开启呼出防骚扰)',
            100527 => '被叫非法(不能是本企业总机号)',
            100528 => '移动座席模式，不能设置状态或进行转接',
            100529 => '指定通话不存在',
            100530 => '座席无监控权限',
            100531 => '座席已经被监控',
            100532 => '企业分机号已被使用',
            100533 => '企业分机号不在有效分机号段内',
            100534 => '手机号码不符合策略，禁止添加',
            100535 => '创建的用户数目已经达到上限',
            100536 => '异地号码无法绑定企业用户',
            100537 => '非本网手机号无法绑定企业用户',
            100538 => '不允许电话直拨',
            100539 => '不允许网络通话',
            100540 => '不允许总机回拨',
            100541 => '呼叫分机号不存在',
            100542 => '电子邮件地址已经存在',
            100543 => '直线号码不属于用户所属企业',
            100544 => '添加直线号码失败',
            100545 => '没有权限设置呼叫方式',
            100546 => '分机号码位数不符合设置',
            100547 => '录音已经过期',
            100548 => '录音文件尚在上传',
            100549 => '没有找到父技能组',
            100550 => '企业无外线总机号码',
            100551 => '总机号码欠费',
            100552 => '外呼类型受限(本地，长途，国际长途)',
            100553 => '不在工作工作时间内',
            100554 => '分机通话分钟数已用完',
            100555 => '单位时间内拨打中国移动号码超过限制(上海联通)',
            100556 => '语音文件不存在',
            100557 => '语音文件不可用',
            100558 => '号码不在白名单内',
            100559 => '校验白名单失败(向运维查询号码是否在白名单失败)',
            100560 => '监控不支持内线互拨场景',
            100561 => '呼叫模式不匹配(不是呼叫中心企业)',
            100562 => '未绑定 sip 话机',
            100563 => 'sip 话机不在线',
            100564 => '创建外呼任务失败',
            100565 => '删除外呼任务失败',
            100566 => '更新外呼任务失败',
            100567 => '添加外呼任务的批次失败',
            100568 => '删除外呼任务的批次的任务号码失败',
            100569 => '查询外呼任务的批次的任务号码失败',
            100570 => '查询外呼任务的批次的状态失败',
            100571 => '启动外呼任务失败',
            100572 => '结束外呼任务失败',
            100573 => '暂停外呼任务失败',
            100574 => '监控外呼任务失败',
            100575 => '获取外呼任信息失败',
            100576 => '获取外呼任务列表失败',
            100577 => '获取批次信息失败',
            100578 => '获取批次列表失败',
            100579 => '没有找到技能组',
            100580 => '没有该外呼任务',
            100581 => '企业未开启',
            100582 => '缺少任务的批次 id',
            100583 => '没有任务号码(外呼任务中的客户号码)',
            100584 => '任务启动时间不在企业的工作时间范围内',
            100585 => '非进行中的任务不允许暂停',
            100586 => '外呼任务名称不能为空',
            100587 => '外呼时间段必须要 08:00-20:00 之间',
            100588 => '外呼时间段不能超出企业工作时间范围',
            100589 => '外呼速率必须在 0.8-1.2 之间',
            100590 => '外呼任务名称已经存在',
            100591 => '未完成的任务数已经达到上限',
            100592 => '进行中或已完成的任务不允许该操作',
            100593 => '缺少任务 id',
            100594 => '进行中或正在导入号码的任务不允许删除',
            100595 => '已完成，进行中，正在导入号码的任务不允许启动',
            100596 => '未开始，已完成，正在导入号码的任务不允许结束',
            100597 => '非 SIP 分机的分机号码不能在 SIP 分机号段内',
            100598 => '当前任务不存在该批次',
            100599 => '进行中，已完成，正在导入号码的批次不允许删除任务 号码',
            100600 => '任务名称格式错误',
            100601 => '坐席已经存在于其他未完成的任务中',
            100602 => '开始时间不能晚于结束时间',
            100603 => '启动或暂停或结束任务失败(无此操作)',
            100604 => '启动或暂停或结束任务失败(操作不能为空)',
            100605 => '无坐席参与该任务',
            100606 => '更新分机信息失败(callintype 不存在)',
            100607 => 'SIP 话机分机号码不存在',
            100608 => '临时坐席，在线坐席，号码不在白名单中的坐席不能参 与批量外呼任务',
            100609 => '缺少用户 Id，分机号，手机号或者工号',
            100610 => '企业管理员用户名或密码错误',
            100611 => '不在批量外呼任务的外呼时间段内',
            100612 => '上传批次号码失败用户接口 HTTP 访问请求错误',
            101000 => 'HTTP 请求包头无 Authorization 参数',
            101001 => 'HTTP 请求包头无 Content-Length 参数',
            101002 => 'Authorization 参数 Base64 解码失败',
            101003 => 'Authorization 参数解码后的格式错误，正确格式: <AccountSid:Timestamp>，注意以“:”隔开',
            101004 => 'Authorization 参数不包含认证账户 ID',
            101005 => 'Authorization 参数不包含时间戳',
            101006 => 'Authorization 参数的账户 ID 不正确(应与 URL 中的账 户 ID 一致)',
            101007 => 'HTTP 请求使用的账号不存在',
            101008 => 'HTTP 请求使用的账号已关闭',
            101009 => 'HTTP 请求使用的账号已被锁定',
            101010 => 'HTTP 请求使用的账户尚未校验',
            101011 => 'HTTP 请求使用的子账户不存在',
            101012 => 'HTTP 请求的 sig 参数校验失败',
            101013 => 'HTTP 请求包体没有任何内容',
            101014 => 'HTTP 请求包体 XML 格式错误',
            101015 => 'HTTP 请求包体 XML 包中的功能名称错误',
            101016 => 'HTTP 请求包体 XML 包无任何有效字段',
            101017 => 'HTTP 请求包体 Json 格式错误',
            101018 => 'HTTP 请求包体 Json 包中的功能名称错误',
            101019 => 'HTTP 请求包体 Json 包无任何有效字段',
            101020 => 'HTTP 请求包体中缺少 AppId',
            101021 => 'HTTP 请求包体中缺少子账号 ID',
            101022 => 'HTTP 请求包体中的开始时间不正确',
            101023 => 'HTTP 请求包体中的结束时间不正确',
            101024 => 'HTTP 请求包体中缺少总机号码',
            101025 => 'HTTP 请求包体中的总机号码格式不正确',
            101026 => 'HTTP 请求包体中缺少企业管理员用户名',
            101027 => 'HTTP 请求包体中缺少企业管理员用户密码',
            101028 => 'HTTP 请求包体中的总机号码已被预置，无法使用',
            101029 => 'HTTP 请求包体中缺少用户绑定手机号码',
            101030 => 'HTTP 请求包体中手机号码格式错误',
            101031 => 'HTTP 请求包体中缺少直线号码',
            101032 => 'HTTP 请求包体中缺少被叫号码',
            101033 => 'HTTP 请求包体中被叫号码格式错误',
            101034 => 'HTTP 请求包体中被叫号码非法',
            101035 => 'HTTP 请求包体中主叫号码格式错误',
            101036 => 'HTTP 请求包体中主叫号码非法',
            101037 => 'HTTP 请求包体中无主叫号码',
            101038 => 'HTTP 请求包体中无验证码',
            101039 => 'HTTP 请求包体中验证码格式错误',
            101040 => 'HTTP 请求包体中缺少呼叫 ID(callId)',
            101041 => 'HTTP 请求包体的子账户 ID 非法',
            101042 => 'HTTP 请求包体中缺少语音 ID(voiceId)',
            101043 => 'HTTP 请求包体中的语音 ID 不正确',
            101044 => 'HTTP 请求包头的 Content-Length 值过大(应不大于 1024X1024)',
            101045 => 'HTTP 请求包体中缺少 numberA',
            101046 => 'HTTP 请求包体中缺少 numberB',
            101047 => 'numberA 或 numberB 格式错误',
            101048 => '呼叫来显模式数值错误',
            101049 => '请求更新的子账户不属于本应用',
            101050 => '按键反馈字段(getFeedBack)不正确',
            101051 => '按键反馈模式字段(feedBackMode)不正确',
            101052 => '按键反馈键值范围不正确(keyRange)',
            101053 => '用户分机号未输入',
            101054 => '呼叫时间限制值格式错误',
            101055 => 'HTTP 请求包中缺少语音文本内容(或为空)',
            101056 => 'HTTP 请求包中的语音文本 Id 格式错误',
            101057 => 'HTTP 请求包中无模板参数',
            101058 => 'HTTP 请求包中的用户工号(座席工号)格式错误',
            101059 => 'HTTP 请求包中的父技能组 ID 格式错误(必须是数字)',
            101060 => 'HTTP 请求包中无技能组名称',
            101061 => 'HTTP 请求包中无技能组 ID',
            101062 => 'HTTP 请求包中的技能组 ID 格式错误(必须是数字)',
            101063 => 'HTTP 请求包中无座席工号',
            101064 => 'HTTP 请求包中的座席模式值非法',
            101065 => 'HTTP 请求包中的座席状态值非法',
            101066 => 'HTTP 请求包中无转接对象',
            101067 => '座席签入时，HTTP 请求包中无设备号码',
            101068 => '查询用户时未输入手机号或直线号码',
            101069 => '按键反馈􏰀示播放次数设置数值非法',
            101070 => '按键反馈等待输入时间数值非法',
            101071 => '座席签入接口，输入的 deviceNumber 无效',
            101072 => '更新密码接口，未输入新密码',
            101073 => '密码长度非法',
            101074 => '密码中未包含数字',
            101075 => '密码中未包含字母',
            101076 => '密码中包含非法字符',
            101077 => '呼出限制接口未输入限制类型',
            101078 => '呼出限制接口限制类型错误',
            101079 => '呼出限制接口日限制值超出范围',
            101080 => '呼出限制接口周限制值超出范围',
            101081 => '呼出限制接口月限制值超出范围',
            101082 => 'HTTP 请求包中缺少短信签名',
            101083 => '短信签名不符合规范(3-8 个 UTF-8 字符)',
            101084 => 'HTTP 请求包中缺少签名类型字段',
            101085 => '签名类型值不正确',
            101086 => 'HTTP 请求包中缺少签名 ID',
            101087 => '签名 ID 无效(必须是数字)',
            101088 => 'HTTP 请求包中缺少短信模板 ID',
            101089 => '短信模板 ID 无效(必须是数字)',
            101090 => '短信模板文本类型错误',
            101091 => 'HTTP 请求包中缺少短信模板内容',
            101092 => '短信模板内容不符合规范',
            101093 => 'HTTP 请求包中缺少短信发送目标手机号',
            101094 => '短信发送目标手机号非法',
            101095 => 'HTTP 请求包中缺少短信发送模板参数',
            101096 => '短信发送模板参数不正确',
            101097 => '短信定时发送时间格式不正确',
            101098 => 'HTTP 请求包中缺少短信验证码参数',
            101099 => '短信验证码参数无效(4-8 位数字)',
            101100 => '短信模板参数非法',
            101101 => '短信模板参数过多',
            101102 => '缺少短信模板参数',
            101103 => '传入参数个数与实际短信模板参数个数不相同',
            101104 => '短信模板内容非法(长度太长或编码格式不正确)',
            101105 => '验证码短信模板中缺少 vc 变量',
            101106 => '验证码短信模板的 vc 变量过多',
            101107 => '短信模板类型错误',
            101108 => '短信发送目标号码数量过多(超过 10 个)',
            101109 => '被叫号码重复(多被叫情况下)',
            101110 => '配置虚拟号码时，未输入总机号',
            101111 => '配置虚拟号码时，输入的总机号码格式错误',
            101112 => '有效期值格式错误',
            101113 => '企业总机号不存在',
            101114 => '企业总机号码已被使用',
            101115 => '输入号码不是总机号或直线号',
            101116 => '没有星期参数',
            101117 => '没有开始时间参数',
            101118 => '没有结束时间参数',
            101119 => '星期参数值非法',
            101120 => '开始时间参数值非法',
            101121 => '结束时间参数值非法',
            101122 => '开始时间大于结束时间',
            101123 => '云总机不是呼叫中心企业',
            101124 => '语音模板参数非法',
            101125 => '语音模板组数与被叫数量不匹配',
            101126 => '语音通知模板参数变量数超过限制',
            101127 => '获取通话记录接口过于频繁',
            101128 => '获取通话记录接口最大条目数值非法(1-500)',
            101129 => '虚拟号码格式错误',
            101130 => '未输入虚拟号码对应的用户真实号码',
            101131 => '配对号码 ID 值格式错误',
            101132 => '虚拟号码 ID 值格式错误',
            101133 => '未输入配对号码列表',
            101134 => '未输入虚拟号码列表',
            101135 => '未输入有效期(maxAge)参数',
            101136 => '未输入服务号码',
            101137 => '输入服务号码非法(非数字)',
            101138 => '输入服务号码格式错误',
            101139 => '总机号码类型值错误',
            101140 => '输入分机号格式错误',
            101141 => 'HTTP 请求包中缺少话机类型参数',
            101142 => '话机类型参数格式错误',
            101143 => 'sip 话机号码格式错误',
            101144 => 'HTTP 请求包中缺少外呼任务名称参数',
            101145 => 'HTTP 请求包中缺少坐席工号参数',
            101146 => '外呼速率超过上限',
            101147 => '外呼速率低于下限',
            101148 => 'HTTP 请求包中缺少外呼任务 Id 参数',
            101149 => '外呼任务 Id 格式错误',
            101150 => 'HTTP 请求包中缺少外呼批次 Id 参数',
            101151 => 'HTTP 请求包中缺少任务号码参数',
            101152 => '任务号码数量超过上限',
            101153 => '外呼批次格式错误',
            101154 => '一次获取任务号码的数量格式错误',
            101155 => '获取任务号码的开始偏移值格式错误',
            101156 => '坐席工号的数量超过上限',
            101157 => '外呼速率格式错误',
            101158 => '任务或批次的状态参数格式错误',
            101159 => '任务或批次的状态参数超出范围',
            101160 => '查询列表限制数目参数格式错误',
            101161 => '自动接通参数格式错误',
            101162 => '自动接通参数超出范围',
            101163 => '表示一次最多获取列表条数的参数格式错误',
            101164 => '表示一次最多获取列表条数的参数超出取值范围',
            101165 => '表示一次最多获取批次号码的参数超出下限',
            101166 => '表示一次最多获取批次号码的参数格式错误',
            101167 => '智能路由不支持选择外显总机号码',
            101168 => 'HTTP 请求包中缺少 SIP 分机号码参数',
            102000 => '账户所属省份错误',
            102001 => '账户关联企业错误',
            102002 => '应用 ID(appId)不存在',
            102003 => '应用 ID 与主账户不匹配',
            102004 => '应用状态为关闭，无法再􏰀供服务',
            102005 => '子账户与应用 ID 不匹配',
            102006 => '请求包体中的子账户 ID 不存在',
            102007 => '子账户与应用 ID 不匹配',
            102008 => '子账户尚未关联或添加专用云总机企业',
            102009 => '总机号码找不到对应省份',
            102010 => '用户应用服务器连接失败',
            102011 => '用户欠费',
            102012 => '用户当天调用接口次数已经超过设定值',
            102013 => '应用未上线，号码无呼叫权限',
            102014 => '应用尚未有子账户',
            102015 => '本应用没有该用户通话错误',
            102100 => '通话被用户应用服务器拒绝',
            102101 => '通话被叫数超限',
            102102 => '无法根据 callId 获得通话记录',
            102103 => '主叫号码无呼叫权限',
            102104 => 'callId 对应的呼叫记录与所属账户不匹配',
            102105 => '呼叫状态为“失败”',
            102106 => '呼叫状态尚不是挂断状态',
            102107 => '呼叫时长太短',
            102108 => '呼叫记录因通话失败或异常而无通话录音',
            102109 => '呼叫尚在录音中，无法获取录音',
            102110 => '本次呼叫没有录音',
            102111 => '用户无主账户呼叫权限',
            102112 => '没有呼叫该被叫的权限(即被叫不是分机绑定号码)',
            102113 => '账户没有取消通话的权限',
            102114 => '被叫日呼叫次数超过限制值',
            102115 => '被叫周呼叫次数超过限制值',
            102116 => '被叫月呼叫次数超过限制值',
            102117 => '企业单位时间内呼叫次数超过限制',
            102118 => '主账户没有可用资费包',
            102119 => '子账户没有可用资费包',
            102300 => '请求必须使用子账户认证',
            102301 => '子账户所属云总机企业不存在',
            102302 => '子账户尚未绑定或添加云总机企业',
            102303 => '参数中的分机号码或密码错误，认证失败',
            102304 => '子账户已经绑定了云总机企业',
            102305 => '云总机企业已被添加到另一个子账户',
            102306 => '云总机企业是系统预置企业，无法删除',
            102307 => '用户手机号已经在云总机中注册',
            102308 => '直线号码已被其他用户使用',
            102309 => '输入的直线号码非法',
            102310 => '直线号码不属于用户所属企业',
            102311 => '用户手机号码尚未在企业中注册',
            102312 => '用户呼叫限制时间格式错误',
            102313 => '没有中间号码用于号码保护',
            102314 => '未找到输入的号码配对',
            102315 => '号码已经配对',
            102316 => '输入的云总机企业分机号不存在',
            102317 => '座席工号已被注册',
            102318 => '座席工号尚未注册',
            102319 => '座席已经加入了该技能组',
            102320 => '技能组尚不存在',
            102321 => '技能组组名已存在',
            102322 => '子账户绑定云总机属于通用企业，无法添加',
            102323 => '配对号码不存在',
            102324 => '配对号码 ID 不存在',
            102325 => '配对号码已经绑定',
            102326 => '虚拟号码不存在',
            102327 => '虚拟号码 ID 不存在',
            102328 => '虚拟号码已经存在',
            102329 => '虚拟号绑定号码已存在',
            102330 => '当前企业未找到此总机号',
            102331 => '虚拟号码不属于该企业',
            102332 => '配对号码不属于该企业',
            102333 => '关闭任务号码文件失败',
            102334 => '不是呼叫中心企业',
            102335 => '生成 json 请求文件失败',
            102400 => '语音文件与应用 ID 不匹配',
            102401 => '语音文件已被另一个企业使用',
            102402 => '没有语音文本字段或语音文本长度为0',
            102403 => '未找到语音通知要使用的语音文本',
            102404 => '语音文本长度超限',
            102405 => '语音文本总数超限',
            102406 => '语音文件个数超限',
            102407 => '语音文件尚未审核',
            102408 => '语音文件未审核通过',
            102409 => '语音文本或模板尚未审核',
            102410 => '语音文本或模板未审核通过',
            102411 => '输入的是否模板参数值非法',
            102412 => '语音模板参数数量已经超限',
            102413 => '语音文本模板参数数量与模板不符',
            102414 => '语音文本模板，参数缺少或顺序有误',
            102415 => '模板参数长度超过规定值',
            102416 => '语音文件格式不符合规定',
            102417 => '文本转语音转换失败',
            102418 => '文本正在转语音过程中',
            102500 => '座席工号不存在',
            102501 => '呼叫中心内部错误',
            102502 => '当前座席忙',
            102503 => '呼叫中心不在工作时间',
            102504 => '当前座席不是回拨模式',
            102505 => '座席尚未签入',
            102506 => '获取外线号码失败',
            102507 => '云总机外呼失败',
            102511 => '通话已经结束或不存在',
            102512 => '座席不是班长，无监控权限',
            102513 => '座席不属于该技能组',
            102600 => '手机号码已经存在',
            102601 => '邮箱已经存在',
            102602 => '省份 id 错误',
            102603 => 'HTTP 请求包中缺少主账号 id 参数',
            102604 => '主账户不是超级账户',
            102605 => '认证 token 错误',
            102606 => 'HTTP 请求包中缺少应用名称参数',
            102607 => 'HTTP 请求包中缺少邮箱地址参数',
            102608 => 'HTTP 请求包中缺少省份 id 参数',
            102700 => '短信发送失败',
            102701 => '本应用添加短信签名数已达上限(默认 16)',
            102702 => '本应用添加短信模板数已达上限(默认 16)',
            102703 => '应用 ID 不匹配',
            102704 => '模板和签名类型不匹配',
            102705 => '模板尚未通过审核',
            102706 => '模板已经绑定签名',
            102707 => '短信发送超时',
            102708 => '签名不存在',
            102709 => '模板不存在',
            102710 => '模板尚未绑定签名',
            102711 => '签名重复',
            102720 => '漫道返回:短信参数错误',
            102721 => '漫道返回:短信内容过长',
            102722 => '漫道返回:同一号码发送内容相同',

        ];


        return $msg[$code];
    }

}






