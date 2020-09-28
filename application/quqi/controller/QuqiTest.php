<?php
/**
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/8/19
 * Time: 下午3:06
 */

namespace app\quqi\controller;

use app\quqi\model\Quqi;
use think\Controller;
use think\Request;

class QuqiTest extends Controller
{
    public $quqi;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->quqi = new Quqi();

    }

    /**
     * 查看账户信息
     */
    public function self()
    {
        $data = $this->quqi->self();
        dump($data);
    }

    /**
     * 修改信息
     */
    public function editPe()
    {
        $data = [
            'email'=>'test@123.com'
        ];
        $data = $this->quqi->editPe($data);

        dump($data);
    }

    /**
     * 验证设备
     */
    public function validateQu()
    {
        $imei = '869426021197840';
        $data = $this->quqi->validateQu($imei);

        dump($data);
    }

    /**
     * 查看设备
     */
    public function showActive()
    {
        $imei = '869426021197840';
        $data = $this->quqi->showActive($imei);

        dump($data);
    }

    /**
     * 设备提醒
     */
    public function alerts(){
        $data = [
            'imei'=>'869426021197840',
            'name'=>'提醒吃药',//提醒的名称
            'alert_type'=>1,//0、周期性提醒，类似手机工作日闹钟类型 1、一次性提醒，设定日期
            'cycle'=>2,//1、以星期为周期，对应上面的0,2、以日期为周期，对应上面的1
            'time'=>date('YmdHis',time()+60),//时间格式当
        ];

        $data = $this->quqi->alerts($data);
        dump($data);
    }

    public function sos_numbers()
    {
        $data = [
            'imei'=>'869426021197840',
            'num'=>2,//提醒的名称
            'name'=>'李',//0、周期性提醒，类似手机工作日闹钟类型 1、一次性提醒，设定日期
            'mobile'=>'17686531379',//1、以星期为周期，对应上面的0,2、以日期为周期，对应上面的1
        ];

        $data = $this->quqi->sos_numbers($data);
        dump($data);
    }

    /**
     * 运动数据查看
     */
    public function pedometer()
    {
        $data = $this->quqi->pedometer('869426021197840');
        dump($data);
    }

    /**
     * 心率
     */
    public function heart()
    {
        $data = $this->quqi->heart('869426021197840');
        dump($data);
    }

    /**
     * 睡眠数据
     */
    public function sleep()
    {
        $data = $this->quqi->sleep('869426021197840');
        dump($data);
    }

    /**
     * 查看紧急呼叫
     */
    public function sosData()
    {
        $data = $this->quqi->sosData('869426021197840');
        dump($data);
    }

    /**
     * 获取设备最新位置
     */
    public function locationData()
    {
        $data = $this->quqi->locationData('869426021197840');
        dump($data);
    }


    /**
     * 登出
     */
    public function logout()
    {
        $this->quqi->logout();

    }

}