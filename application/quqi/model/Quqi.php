<?php

namespace app\quqi\model;


use think\Exception;
use think\Log;
use think\Model;
use think\Session;

/**
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/8/19
 * Time: 下午2:48
 */
class Quqi extends Model
{

    //用户名密码
    protected $username = '13651742952';
    protected $password = 'yunchiiot@yl2019';
    //api请求地址
    protected $url = 'http://api.aiqiangua.com:8888';

    protected $oid = '5d5370e589d446323c0beb51';
    //请求接口所需的cookie
    protected $cookie;

    /**
     * 构造函数
     * @param array $data
     */
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->cookie = Session::get('cookie');
        if (!$this->cookie || $this->cookie['time'] < time()){
            $this->getCookie();
            $this->cookie = Session::get('cookie');
        }
    }

    /**
     * 获取账户信息
     */
    public function self()
    {
        $path = '/api/person/self';
        $data = $this->getUrl($path);
        return $data;
    }

    /**
     * 修改账户信息 #
     * @param $param
     * @return array|mixed|string
     */
    public function editPe($param)
    {
        $path = '/api/person/'.$this->oid.'/edit';
        $data = [
            'email'=>$param['email']
        ];
        $data = $this->postUrl($path,$data);
        return $data;
    }

    /**
     * 验证设备
     */
    public function validateQu($imei)
    {
        $path = '/api/device/validate';
        $data = [
            'deviceid'=>$imei
        ];
        $data = $this->postUrl($path,$data);
        return $data;
    }


    /**
     * 设备解绑 未测试
     * @param $imei
     * @return mixed|string
     */
    public function unActive($imei)
    {
        $path = '/api/device/'.$imei.'/unactive';

        $data = $this->postUrl($path);
        return $data;
    }

    /**
     * 查看设备
     * @param $imei
     * @return mixed|string
     */
    public function showActive($imei)
    {
        $path = '/api/device/'.$imei;

        $data = $this->getUrl($path);
        return $data;

    }

    /**
     * 设备提醒
     * @param $param
     * @return mixed|string
     */
    public function alerts($param)
    {

        $path = '/api/device/'.$param['imei'].'/alerts/1';

        $data = [
            'enable'=>1,//
            'name'=>$param['name'],//提醒的名称
            'alert_type'=>$param['alert_type'],//0、周期性提醒，类似手机工作日闹钟类型 1、一次性提醒，设定日期
            'cycle'=>$param['cycle'],//1、以星期为周期，对应上面的0,2、以日期为周期，对应上面的1
            'time'=>$param['time'],//时间格式当alert_type=0时，格式为 1001101+21+30+ ，意义：前7位对应周日、周一……周六，需要提醒的那些天，后面是HHMM，中间用+号分割和结尾；当alert_type=1时，格式为YYYYMMDDHHMMSS
        ];
        $data = $this->postUrl($path,$data);
        return $data;

    }

    /**
     * 设置亲情号码
     * @param $param
     * @return array|mixed|string
     */
    public function sos_numbers($param)
    {
        //num 设置号码位置 1为联系人1 2为联系人2 类推
        $path = '/api/device/'.$param['imei'].'/sos_numbers/'.$param['num'];

        $data = [
            'name'=>$param['name'],//号码昵称
            'num'=>$param['mobile'],//亲情号码 固话或手机号
            'dial_flag'=>1//1设置为紧急呼叫号码(默认) 0为不设置
        ];
        $data = $this->postUrl($path,$data);
        return $data;

    }


    /**
     * 查看运动数据
     * @param $imei
     * @return mixed|string
     */
    public function pedometer($imei)
    {
        $path = '/api/pedometerdata/?device='.$imei;

        $data = $this->getUrl($path);
        return $data;
    }

    /**
     * 查看设备心率数据
     * @param $imei
     * @return mixed|string
     */
    public function heart($imei)
    {
        $path = '/api/heartratedata/?device='.$imei;

        $data = $this->getUrl($path);
        return $data;
    }

    /**
     * 查看设备睡眠数据
     * @param $imei
     * @return mixed|string
     */
    public function sleep($imei)
    {
        $path = '/api/sleepdata/?device='.$imei;

        $data = $this->getUrl($path);
        return $data;
    }

    /**
     * 查看设备紧急呼叫
     * @param $imei
     * @return mixed|string
     */
    public function sosData($imei)
    {
        $path = '/api/sosdata/?device='.$imei;

        $data = $this->getUrl($path);
        return $data;
    }

    /**
     * 获取设备最新位置数据
     * @param $imei
     * @return mixed|string
     */
    public function locationData($imei)
    {
        $path = '/api/device/'.$imei.'/data/locationdata';

        $data = $this->getUrl($path);
        return $data;
    }




    /**
     * 登出
     */
    public function logout()
    {
        $path = '/api/auth/logout';
        $data = $this->postUrl($path);
        if ($data['success']){
            Session::delete('cookie');
        }
    }


    /**
     * post提交
     * @param $path=>url路径
     * @param string $param 传输数据
     * @return mixed|string
     */
    public function postUrl($path,$param = '')
    {
        $cookie = ['Cookie:'.$this->cookie['cookie']];
        $url = $this->url.$path;
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);//指定网页
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $cookie);
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        if ($param != ''){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);

        }
        $data = curl_exec($ch);//运行curl
        curl_close($ch);

        return (array)json_decode($data);
    }

    /**
     * get提交
     * @param $path=>url路径
     * @param string $param 传输数据
     * @return mixed|string
     */
    public function getUrl($path,$param = '')
    {
        $cookie = ['Cookie:'.$this->cookie['cookie']];
        $url = $this->url.$path;
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);//指定网页
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $cookie);
        if ($param != ''){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);

        }
        $data = curl_exec($ch);//运行curl
        curl_close($ch);

        return (array)json_decode($data);
    }


    /**
     * 获取接口请求必备的cookie
     */
    public function getCookie()
    {
        try {
            $postUrl = $this->url.'/api/auth/login';
            $data = [
                'username'=>$this->username,
                'password'=>$this->password,
            ];
            $ch = curl_init();//初始化curl
            curl_setopt($ch,CURLOPT_URL,$postUrl);//指定网页
            curl_setopt($ch,CURLOPT_POST, 1);//post提交方式
            curl_setopt($ch,CURLOPT_HEADER,1); //将头文件的信息作为数据流输出
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //返回获取的输出文本流
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data); //发送POST数据
            $content = curl_exec($ch); //执行curl并赋值给$content
            preg_match('/Set-Cookie:(.*);/iU',$content,$str); //正则匹配
            $cookie_arr = [
                'cookie'=>substr($str[0],12),
                'time'=>time()+86400,
            ];
            Session::set('cookie',$cookie_arr);
        } catch (Exception $e) {
            Log::write('生成COOKIE会话信息异常: '.$e->getMessage(),'error');
        }
    }


}













