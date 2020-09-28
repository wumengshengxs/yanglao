<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/2
 * Time: 9:36
 */
namespace app\index\model;

use think\Model;
use think\Session;
use think\Exception;
use think\Log;

class Common extends Model {
    /**
     * 登录用户信息
     */
    public $user_info;

    public function __construct()
    {
        $this->user_info = Session::get('S_USER_INFO');
    }
}