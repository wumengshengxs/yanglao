<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/10
 * Time: 11:58
 * 服务对象标签验证
 */
namespace app\index\validate;

use think\Validate;

class StaffUser extends Validate
{

    protected $rule = [
        'display_name'  =>  'require|max:31',
        'password' => 'require|regex:^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$',
        'phone'  => 'require|unique:StaffUser',
    ];

    protected $message  =   [
        'display_name.require' => '话务员名称不能为空',
        'display_name.max'     => '话务员名称最多不能超过15个字符',
        'password.require'     => '密码不能为空',
        'password.regex'     => '密码必须包含字母和数字的6-16位数',
        'phone.require'=>'手机号不能为空',
        'phone.unique'=>'手机号已存在',

    ];
}