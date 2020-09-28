<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/2
 * Time: 17:07
 * 菜单的validate验证
 */
namespace app\index\validate;

use think\Validate;

class Menu extends Validate {
    /**
     * 验证规则
     */
    protected $rule = [
        'name' => 'require|length:1,10'
    ];

    /**
     * 提示信息
     */
    protected $message  =   [
        'name.require'      => '请填写菜单名称',
        'mobile.length'       => '菜单限制1-10个字符',
    ];

    /**
     * 应用场景
     */
    protected $scene = [
        'add' => ['mobile','password'],
        'login' =>  ['mobile','password'],
    ];
}