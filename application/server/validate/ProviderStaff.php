<?php

/**
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/3/20
 * Time: 上午9:01
 */
namespace app\server\validate;

use \think\Validate;

class ProviderStaff extends Validate
{

    protected $rule = [
        'name'  =>  'require|max:15',
        'mobile'   =>  'require|regex:/^1[345678]{1}\d{9}$/|unique:ProviderStaff',
        'password' => 'require|max:31',

    ];

    protected $message  =   [
        'name.require' => '姓名不能为空',
        'name.max'     => '姓名最多不能超过15个字符',
        'mobile.require' => '手机号码不能为空',
        'mobile.regex'   => '手机号码格式错误',
        'mobile.unique'  => '手机号已存在',
        'password.require' => '密码不能为空',
        'password.max' => '姓名最多不能超过31个字符',
    ];

    /**
     * 应用场景
     */
    protected $scene = [
        'editStaff' =>  ['name','mobile'],
    ];


}








