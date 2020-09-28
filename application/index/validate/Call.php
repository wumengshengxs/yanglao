<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/10
 * Time: 11:58
 * 服务对象分组验证
 */
namespace app\index\validate;

use think\Validate;

class Call extends Validate
{

    protected $rule = [
        'nickname'    =>  'require|max:15',
        'mobile'  =>  'require|regex:/^1[345678]{1}\d{9}$/',
        'email' => 'require',
        'day_limit'   => 'number|between:0,10',
        'week_limit'  => 'number|between:0,25',
        'month_limit' => 'number|between:0,50',
    ];

    protected $message  =   [
        'nickname.require'   => '子账户昵称不能为空',
        'nickname.max'       => '子账户昵称不得超过15个字符',
        'mobile.require'     => '手机号码不能为空',
        'mobile.regex'       => '手机号码格式错误',
        'email.require'      => '邮箱不能为空',
        'day_limit.number'   => '每天呼叫次数限制必须为数字',
        'day_limit.between'  => '每天呼叫次数限制范围0-10',
        'weekLimit.number'   => '每周呼叫次数限制必须为数字',
        'weekLimit.between'  => '每周呼叫次数限制范围0-10',
        'monthLimit.number'  => '每月呼叫次数限制必须为数字',
        'monthLimit.between' => '每月呼叫次数限制范围0-10',
    ];

    protected $scene = [
        'editCall'  =>  ['nickname','mobile','email'],
        'editCallLimit'  =>  ['day_limit','week_limit','month_limit'],
    ];
}