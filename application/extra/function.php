<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/25
 * Time: 10:29
 */

use \think\Request;
use think\Exception;
use think\Log;

/**
 * 文件上传到本地（针对excel等文件，不包括文件）
 * @param string $name 上传的文件名（默认file）
 * @param int $size 上传文件的大小限制（默认2M）
 * @param array $type 上传文件类型
 * @return array $result 上传成功/失败信息
 */
function F_upload_to_local($name = 'file', $size = 2, $type = ['jpg','jpeg','png'])
{
    try {
        $file = Request::instance()->file($name);
        // 验证上传文件大小
        $size = $size*1024*1024;
        if(!$file->checkSize($size)){   // 验证文件大小
            return json(['code'=>1,'msg'=>'请上传'.$size.'M以内的文件！','url'=>'']); //这里是为了获取文件大小
        }
        if(!$file->checkExt($type)){   // 验证文件类型
            return json(['code'=>2,'msg'=>'请上传符合格式的文件！','url'=>'']);  // 文件格式
        }
        //上传文件
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        $str = $info->getSaveName();
        if(!$info){
            return json(['code'=>3,'msg'=>$info->getError(),'url'=>'']);
        }
        $str = str_replace('\\','/',$str);   // 处理Windows下面目录分隔符的问题
        return json(['code'=>0,'msg'=>'上传成功','url'=>'/public/uploads/'.$str]);
    } catch (\Exception $e) {
        Log::write('上传本地文件异常：'.$e->getMessage(),'error');
        return ['code'=>-1,'msg'=>'服务器异常'];
    }
}

/**
 * 文件上传到七牛（针对文件）
 * @param string $name 上传的文件名（默认file）
 * @param int $size 上传文件的大小限制（默认2M）
 * @param array $type 上传文件类型
 * @return array $result 上传成功/失败信息
 */
function F_upload_to_qiniu($name = 'file', $size = 2, $type = ['jpg','jpeg','png'])
{
    try {
        $file = Request::instance()->file($name);
        // 验证上传文件大小
        $size = $size*1024*1024;
        if(!$file->checkSize($size)){   // 验证文件大小
            return json(['code'=>1,'msg'=>'请上传'.$size.'M以内的文件！']); //这里是为了获取文件大小
        }
        if(!$file->checkExt($type)){   // 验证文件类型
            return json(['code'=>2,'msg'=>'请上传符合格式的文件！']);  // 文件格式
        }
        $qiniu = new \gmars\qiniu\Qiniu(config('qiniu')['accesskey'],config('qiniu')['secretkey'],config('qiniu')['bucket']);
        $saveName = 'Yiot'.date('YmdHis').rand(1000,9999);  // 保存的文件名
        $path = $qiniu->upload($saveName);
        if(!$path){
            return ['code'=>3,'msg'=>'上传失败'];
        }
        $path = 'http://'.config('qiniu')['domain'].'/'.$path;
        return json(['code'=>0,'msg'=>'上传成功','url'=>$path]);
    } catch (Exception $e) {
        Log::write('上传七牛文件异常：'.$e->getMessage(),'error');
        return false;
    }
}

/**
 * 根据出生日期获取年龄
 * @param string $birthday 出生日期的时间戳
 * @return int $age 年龄
 */
function F_get_age($birthday='')
{
    try {
        if($birthday === false){
            return false;
        }
        list($y1,$m1,$d1) = explode("-",date("Y-m-d",$birthday));
        $now = strtotime("now");
        list($y2,$m2,$d2) = explode("-",date("Y-m-d",$now));
        $age = $y2 - $y1;
        if((int)($m2.$d2) < (int)($m1.$d1)) {
            $age -= 1;
        }
        return $age;
    } catch (\Exception $e) {
        Log::write('获取年龄异常：'.$e->getMessage(),'error');
        return ['code'=>-1,'msg'=>'服务器异常'];
    }
}

/**
 * 时间显示
 * @param int|string $times 时间戳
 * @return string
 */
function F_callTime($times)
{
    $str = '';
    if ($times == 0) {
        $str = '没有信息';
    } elseif ($times < 60) {
        $str = $times.'秒';
    } elseif ($times < 3600) {
        $min = floor($times / 60);
        $sec = $times - ($min * 60);
        $str = $min . '分钟'.$sec.'秒';
    } elseif ($times < 3600 * 24) {
        $h = floor($times / 3600);
        $min = floor(($times - $h * 3600) / 60);
        $s = floor( $times -($h * 3600) - $min * 60 );
        $str = $h . '小时'.$min.'分钟'.$s.'秒';
    } else{
        $d = floor($times / (3600 * 24));
        $h = floor(($times - $d * (3600* 24)) / 3600);
        $m = floor( ($times - $d * (3600* 24) - $h*3600) / 60);
        $str = $d . '天'.$h.'小时'.$m.'分钟';
    }

    return $str;
}

/**
 * 获取指定后几天
 * @param $day
 * @return array
 */
function F_getDays($day)
{
    $days = [];
    for ($i=$day;$i>0;$i--){
        $days[date('Y-m-d',strtotime("-".$i.'day'))] = 0;
    }

    return $days;
}

/*
*打印调试变量
*var 变量名
*/
function bug($var, $echo = true, $label = null, $flags = ENT_SUBSTITUTE){
    $label = (null === $label) ? '' : rtrim($label) . ':';
    ob_start();
    var_dump($var);
    $output = ob_get_clean();
    $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
    if (IS_CLI) {
        $output = PHP_EOL . $label . $output . PHP_EOL;
    } else {
        if (!extension_loaded('xdebug')) {
            $output = htmlspecialchars($output, $flags);
        }
        $output = '<pre>' . $label . $output . '</pre>';
    }
    if ($echo) {
        echo($output);
        return;
    } else {
        return $output;
    }
}

/**
 * PHPExcel导出excel表格
 * @param array $data 要导出数据的数组
 * @param array $header 表头
 * @param array $key 对应导出数据的下标
 * @param string $name 文件名
 */
function F_export_excel($data=[], $header=[], $key=[], $name='')
{
    try {
        // 引入类库
        vendor('PHPExcel.PHPExcel.IOFactory');
        $excel = new \PHPExcel();
        $name = $name ? $name : 'excel_'.date('YmdHis');  // 默认文件名
        iconv('utf-8', 'gb2312', $name);    // 防止中文乱码
        $excel->setActiveSheetIndex(0);
        $excel->getActiveSheet()->setTitle($name);  //设置表名
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(18);  // 自定义行高
        $letter = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ'];    //列坐标
        // 生成表头
        for($i=0; $i<count($header); $i++)
        {
            //设置表头值
            $excel->getActiveSheet()->setCellValue("$letter[$i]1",$header[$i]);
            // 自定义列宽
            $excel->getActiveSheet()->getColumnDimension($letter[$i])->setWidth(15);
        }
        // 写入数据
        for($i=0;$i<count($data);$i++){
            //让每列的数据数小于列数
            for($j=0; $j<count($key); $j++){
                //防止科学计数法
                $value = $data[$i][$key[$j]];
                if(is_numeric($value)){
                    $value = "\t".iconv('utf-8', 'GB2312//IGNORE', $value);
                }
                //设置单元格的值

                $excel->getActiveSheet(0)->setCellValue($letter[$j].($i+2), $value);
            }
        }
        // 清理缓冲区，防止中文乱码
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$name.'.xls"');
        header('Cache-Control: max-age=0');
        $res_excel = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $res_excel->save('php://output');
    } catch (\Exception $e) {
        Log::write('导出excel异常：'.$e->getMessage(),'error');
        return ['code'=>-1,'msg'=>'服务器异常'];
    }
}

/*
*生成随机字符串
*科强手环下发指令生成指定长度的字符串
*$param int 指定字符串长度参数
*return string|bool
*/
function F_CreateRandom($param){
    try {
        $str="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $key = "";
        for($i=0;$i<$param;$i++){
            $key .= $str{mt_rand(0,32)};    //生成php随机数
        }
        return $key;
    } catch (Exception $e) {
        Log::write('生成'.$param.'长度的字符串异常'.$e->getMessage(),'error');
        return false;
    }
}

/*
*服务端->智能终端下发指令
*$command string 下发指令
*port int 类型
*return array 
*/
function F_SendCommand($command,$port){
    try {
        if (!is_string($command)) {
            return ['code'=>'0','message'=>'下发指令参数有误'];die;
        }
        //服务器地址
        $address = '101.89.115.24';
        //建立客户端的socet连接 
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!$socket) {
            return ['code'=>'0','message'=>'tcp服务异常'];die;
        } 
        /*
        *下方的两个参数请参考智能终端具体测试的上行返回时间
        *目前科强手环下发定位到设备返回目前测试下来共计耗时为20秒内
        */
        //发送超时10秒
        socket_set_option($socket,SOL_SOCKET,SO_RCVTIMEO,array("sec"=>29, "usec"=>0 ) );
        //接收超时10秒 
        socket_set_option($socket,SOL_SOCKET,SO_SNDTIMEO,array("sec"=>29, "usec"=>0 ) );
        //连接服务器端socket
        socket_connect($socket,$address,$port) or die(['code'=>'0','message'=>'未能连接服务器']);  
        /*
        *command string 预定义下发指令 WSIMEI
        */
        socket_write($socket,$command);
        //客户端去连接服务端并接受服务端返回的数据，如果返回的数据保护not connect就提示不能连接。
        $buffer = @socket_read($socket,1024,PHP_BINARY_READ);
        socket_close($socket);
        return json_decode($buffer,true);
    } catch (Exception $e) {
        Log::write('连接tcp服务器异常'.$e->getMessage(),'error');
        return ['code'=>1,'message'=>'服务器异常'];
    }
}

/*
*大屏信息时时数据间显示
*/
function F_time_trans($the_time)
{
    try {
        $now_time = time();
        $dur = $now_time - $the_time;
        if ($dur < 60) {
            return $dur . '秒前';
        } else if ($dur < 3600) {
            return floor($dur / 60) . '分钟前';
        } else if ($dur < 86400) {
            return floor($dur / 3600) . '小时前';
        } else if ($dur < 259200) {//3天内
            return floor($dur / 86400) . '天前';
        } else {
            return $the_time;
        }
    } catch (Exception $e) {
        Log::write('格式化时间异常' . $e->getMessage(), 'error');
        return false;
    }
}

/**
 * curl get
 * @param $url
 * @return mixed
 */
function curl_get($url){
    $info=curl_init();
    curl_setopt($info,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($info,CURLOPT_HEADER,0);
    curl_setopt($info,CURLOPT_NOBODY,0);
    curl_setopt($info,CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($info,CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($info,CURLOPT_URL,$url);
    $output= curl_exec($info);
    curl_close($info);
    return $output;
}

/*
*unicode 编码
*name 中文名称 UTF16-BE 解析
*return string|bool
*/
function F_unicode_encode($name){
    try {
        //utf8 转换 ASCII
        $name = iconv('UTF-8', 'UCS-2', $name);
        $len = strlen($name);
        $str = '';
        for ($i = 0; $i < $len - 1; $i = $i + 2){
            $c = $name[$i];
            $c2 = $name[$i + 1];
            if (ord($c) > 0){   
                //两个字节的文字 base_convert() 函数在任意进制之间转换数字 ord 返回ASCII对应的值
                $str .= base_convert(ord($c), 10, 16).str_pad(base_convert(ord($c2), 10, 16), 2, 0, STR_PAD_LEFT);
            }else{
                $str .= $c2;
            }
        }
        return $str;
    } catch (Exception $e) {
        Log::write('字符转换UNICODE编码异常'.$e->getMessage(),'error');
        return false;
    }
}

/**
 * @param $gg_lon
 * @param $gg_lat
 * @return mixed
 * 高德转百度经纬度
 */
function F_bd_encrypt($gg_lon,$gg_lat){
    $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
    $x = $gg_lon;
    $y = $gg_lat;
    $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
    $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
    $data['bd_lon'] = $z * cos($theta) + 0.0065;
    $data['bd_lat'] = $z * sin($theta) + 0.006;
    return $data;
}