<?php
/**
 * 统一返回处理类（ajax）允许跨域
 *
 */

namespace app\common\model;

use think\Config;
use think\exception\HttpResponseException;
use think\Response;

class Message
{
    /**
     * @param $statusCode
     * @param $msg
     * @param null $data
     * ajax返回信息 1成功 0失败
     */
    final public static function ajaxReturn($statusCode,$msg, $data = null)
    {
        $result['status'] = $statusCode;
        $msg ? $result['msg'] = $msg : null;
        $data ? $result['data'] = $data : null;

        $type                                   = self::getResponseType();
        $header['Access-Control-Allow-Origin']  = '*';
        $header['Access-Control-Allow-Headers'] = 'X-Requested-With,Content-Type';
        $header['Access-Control-Allow-Methods'] = 'GET,POST,PATCH,PUT,DELETE,OPTIONS';
        $response                               = Response::create($result, $type)->header($header);
        throw  new HttpResponseException($response);
    }

    /**
     * 获取当前的response 输出类型
     * @access protected
     * @return string
     */
    final private function getResponseType()
    {
        return Config::get('default_ajax_return');
    }
}
