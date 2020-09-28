<?php
/**
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/3/21
 * Time: 上午10:00
 */

namespace app\api\model;


class WxChat
{
    /**
     * 小程序请求接口
     * @param $code=>小程序请求code
     * @param $signature=>数据签名校验参数
     * @param $rawData=>小程序请求签名
     * @param $encryptedData=>小程序请求用户密文
     * @param $iv=>偏移向量
     * @return mixed|\think\response\Json
     */
    public function WxLogin($code,$signature,$rawData,$encryptedData,$iv)
    {
        $APPID = 'wxfa95a0cd2b707ebe';
        $AppSecret = '06265b57a3d97a309c818e9f76170c20';
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . $APPID . "&secret=" . $AppSecret . "&js_code=" . $code . "&grant_type=authorization_code";
        $arr = curl_get($url);
        $arr = json_decode($arr, true);
        $session_key = $arr['session_key'];
        // 数据签名校验
        $signature2 = sha1($rawData . $session_key);
        if ($signature != $signature2) {
            return ['code' => 500, 'msg' => '数据签名验证失败！'];
        }
        $pc = new WXBizDataCrypt($APPID, $session_key);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);  //其中$data包含用户的所有数据
        if ($errCode != 0) {
            return ['code' => 100, 'msg' => '验证失败'];
        }
        //生成第三方3rd_session
        $data = json_decode($data, true);
        $session3rd = $this->keys();
        $data['session3rd']=$session3rd;
        $data['status']=true;
        $data['msg']='success';
        return $data;
    }

    /**
     * 生成第三方3rd_session
     * @return null|string
     */
    public function keys()
    {
        $session3rd  = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;
        for($i=0;$i<16;$i++){
            $session3rd .=$strPol[rand(0,$max)];
        }

        return $session3rd;
    }


    /**
     * 计算两点地理坐标之间的距离
     * @param  Decimal $longitude1 起点经度
     * @param  Decimal $latitude1  起点纬度
     * @param  Decimal $longitude2 终点经度
     * @param  Decimal $latitude2  终点纬度
     * @param  Int     $unit       单位 1:米 2:公里
     * @param  Int     $decimal    精度 保留小数位数
     * @return Decimal
     */
    public static function getDistance($longitude1, $latitude1, $longitude2, $latitude2, $unit=1, $decimal=2){

        $EARTH_RADIUS = 6370.996; // 地球半径系数
        $PI = 3.1415926;

        $radLat1 = $latitude1 * $PI / 180.0;
        $radLat2 = $latitude2 * $PI / 180.0;

        $radLng1 = $longitude1 * $PI / 180.0;
        $radLng2 = $longitude2 * $PI /180.0;

        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;

        $distance = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
        $distance = $distance * $EARTH_RADIUS * 1000;

        if($unit==2){
            $distance = $distance / 1000;
        }

        return round($distance, $decimal);
    }



}