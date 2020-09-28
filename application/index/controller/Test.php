<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/12
 * Time: 15:42
 */
namespace app\index\controller;

use app\index\controller\Common;
use think\Request;
use think\Db;

class Test extends Common {
    /**
     * 上传excel页面
     */
    public function uploadExcel()
    {
        return view('upload');
    }

    public function getExcelData()
    {
        // 获取文件位置
        $file = F_upload_to_local($name = 'file', $size = 2, $type = ['xls']);
        $fileUrl = $_SERVER['DOCUMENT_ROOT'].$file;
        vendor('PHPExcel.PHPExcel.IOFactory');
        $excel = \PHPExcel_IOFactory::load($fileUrl,$encode = 'utf-8');
        $sheet = $excel->getSheet(0); // 获取表中第一个工作表 去除列名称所属行
        $excel_rows = $sheet->getHighestRow(); //取得总行数
        $A = '';
        for($i=2; $i<=$excel_rows; $i++){
            $A .= "'".trim($excel->getActiveSheet()->getCell("A" . $i)->getValue())."',";
        }
        return json($A);
    }

    public function a(){
        echo md5('szk_allen_test01');die; 
        $url = "http://api.cellocation.com:81/loc/?cl=460,1,5195,32331,61;460,1,4185,54302,29;460,1,5195,19819,26;460,1,4185,54301,25&wl=8c:68:c8:dd:40:1e,-74;94:d9:b3:5b:60:c5,-76;d0:c7:c0:7a:d1:cc,-74;38:97:d6:71:85:10,-88;b4:86:55:62:fe:c1,-77;c6:bb:4c:06:1e:ea,-82&output=json&coord=bd09";
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL,$url); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:1.138.2.35', 'CLIENT-IP:1.138.2.35')); //构造IP 
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11");
        curl_setopt($ch, CURLOPT_REFERER, "//www.jb51.net/ "); //构造来路 
        curl_setopt($ch, CURLOPT_HEADER, 1); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $out = curl_exec($ch); 
        curl_close($ch); 
        bug($out);
    }

    public  function b(){
        $url = "http://api.cellocation.com:81/loc/?cl=460,1,5195,32331,61;460,1,4185,54302,29;460,1,5195,19819,26;460,1,4185,54301,25&wl=8c:68:c8:dd:40:1e,-74;94:d9:b3:5b:60:c5,-76;d0:c7:c0:7a:d1:cc,-74;38:97:d6:71:85:10,-88;b4:86:55:62:fe:c1,-77;c6:bb:4c:06:1e:ea,-82&output=json&coord=bd09";
        $curl = curl_init();
        //初始一个curl会话
        $curl = curl_init();
        //设置url
        curl_setopt($curl, CURLOPT_URL,$url);  
        //TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        //设置超时等待
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        //执行cURL会话
        $result = curl_exec($curl);
        //关闭cURL资源，并且释放系统资源
        curl_close($curl);
        bug($result);
    }
    
    public function c(){
        $tmp['id'] = ['eq',3];
        $info = db::name('device_passage')->where($tmp)->find();
        if ($info['p_status']==2) {
            die(date('Y-m-d H:i:s').' 通道3 未开启,如您看到这条消息请联系开发人员'."\r\n");
        }
        $where = "pid = 3 and uid is not null";
        $data = db::name('device')->where($where)->column('imei');
        if(!$data){
           die(date('Y-m-d H:i:s').' 通道3未有数据'."\r\n");
        }
        //请求api
        $url = 'http://api.data-unify.com/heyi-api/health/b/v1/wm/dailys/list';
        $curlPost = [
            'reportDate'=>date("Y-m-d",strtotime("-1 day")),
            'devices'=>$data,
            'appId'=>$info['param'],
        ];
        $header[] = "Content-type:application/json";
        $result = $this->getCurl($url,$header,json_encode($curlPost));

        //结果返回
        if(!$result) {
            die(date('Y-m-d H:i:s').'当前未有数据');
        }
        $res = json_decode($result,true);
        bug($res);
        //请求未成功
        if ($res['code']!=='001') {
           die(date('Y-m-d H:i:s').'请求有误,错误信息是'.$res['msg']);
        }
        $return_tmp = json_decode($res['dailys'],true);
        bug($return_tmp);
        die;
        if (count($return_tmp['data'])==0) {
            die(date('Y-m-d H:i:s')."未有测量数据");
        }

        //记录测量数据
        foreach ($return_tmp['data'] as $key=>&$value) {
            //根据设备号码查询用户id
            $uid = db::name('device')->where('imei',$value["deviceID"])->value('uid');
            $arr=[
                'addtime'=>time(),               //添加时间
                'uid'=>$uid,                     //绑定用户
                'imei'=>$value["deviceID"],      //设备imei
                'state'=>3,                     //测量设备
                'heart'=>$value['hr'],         //心率
                'sleep'=>$value['fallinsleep'],//睡眠
            ];
            //插入健康数据
            db::name('health')->insert($arr);
        }
    }

    /*
    *curl调取
    *url string 接口地址
    *head array curl请求头部信息
    *curlPost string json格式的请求字符串
    *return string|bool
    */
    protected function getCurl($url,$head,$curlPost){
        try {
            $ch = curl_init();//初始化curl
            curl_setopt($ch, CURLOPT_URL,$url);//指定网页
            curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
            curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$curlPost);
            $data = curl_exec($ch);//运行curl
            curl_close($ch);
            return $data;
        } catch (Exception $e) {
            Log::write('curl获取睡眠数据信息异常:'.$e->getMessage(),'error');
            return false; 
        }
    }

    /*
    *请求腕表是否在线
    *返回值为200为在线 201为不在线
    */
    public function t9aa(){
        $command_arr = [
          'msgType'=>'S200',
          'deviceId'=>'865946021280480',            
          'msgContent'=>null,
        ];  
        $command = json_encode($command_arr);
        $status = self::F_SendHttp($command);
        $res_arr = json_decode($status,true);
        if ($res_arr['rCode']==201) {
            echo '终端不在线,无法下发指令';
        }
    }

    /*
    *下发绑定
    *返回值为200为在线 201为不在线 
    */
    public function t9sbind(){
        $command_arr = [
          'msgType'=>'S0',
          'deviceId'=>'865946021280480',            
          'msgContent'=>'|沈沐枫|',
        ];  
        $command = json_encode($command_arr);
        $res = self::F_SendHttp($command);
        $res_arr = json_decode($res,true);
        bug($res_arr);
        if ($res_arr['rCode']==201) {
            echo '下发绑定失败';die;
        }
        echo '下发绑定成功';
    }

    /*
    *下发时时定位
    *返回值为200为成功 201失败
    */
    public function t9getgps(){
        
        $command_arr = [
          'msgType'=>'S13',
          'deviceId'=>'865946021280480',            
          'msgContent'=>null,
        ]; 
        $command = json_encode($command_arr);
        $res = self::F_SendHttp($command);
        $res_arr = json_decode($res,true);
        bug($res_arr);
        if ($res_arr['rCode']==201) {
            echo '下发定位指令失败';die;
        }
        echo '下发定位指令成功';
    }

    /*
    *设置SOS号码
    *
    */
    public function t9setsos(){
        $command_arr = [
          'msgType'=>'S71',
          'deviceId'=>'865946021280480',            
          'msgContent'=>'13651742952',
        ]; 
        $command = json_encode($command_arr);
        $res = self::F_SendHttp($command);
        $res_arr = json_decode($res,true);
        bug($res_arr);
        if ($res_arr['rCode']==201) {
            echo '下发SOS指令失败';die;
        }
        echo '下发SOS指令成功';
    }

    /*
    *设置快捷拨号
    *
    *
    */
    public function t9setnumber(){
        $command_arr = [
          'msgType'=>'S70',
          'deviceId'=>'865946021280480',            
          'msgContent'=>'13651742952,13651742952,13651742952,02180181111',
        ]; 
        $command = json_encode($command_arr);
        $res = self::F_SendHttp($command);
        $res_arr = json_decode($res,true);
        bug($res_arr);
        if ($res_arr['rCode']==201) {
            echo '下发快捷拨号指令失败';die;
        }
        echo '下发快捷拨号指令成功';
    }


    /*
    *发送HTTP POST请求
    *$post string  请求参数
    */
    public static function F_SendHttp($post){
      try {
          //请求地址
          $url = 'http://39.105.81.70:8085';
          //初始一个curl会话
          $curl = curl_init();
          //设置url
          curl_setopt($curl, CURLOPT_URL,$url);  
          //post提交方式
          curl_setopt($curl, CURLOPT_POST, 1);
          //TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
          //提交参数
          curl_setopt($curl, CURLOPT_POSTFIELDS,$post);
          //执行cURL会话
          $res = curl_exec($curl);
          //关闭cURL资源，并且释放系统资源
          curl_close($curl);
          return $res;
      } catch (Exception $e) {
        Log::write('发送HTTP请求失败'.$e->getMessage(),'error');
        return ['code'=>1,'message'=>'服务器异常'];
      }
    }
}