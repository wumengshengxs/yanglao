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

class StaffGroup extends Validate
{

    protected $rule = [
        'name'  =>  'require|max:15',
    ];

    protected $message  =   [
        'name.require' => '技能组名称不能为空',
        'name.max'     => '技能组名称最多不能超过15个字符',
    ];
}