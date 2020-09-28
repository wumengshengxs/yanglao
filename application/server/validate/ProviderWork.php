<?php
/**
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/3/22
 * Time: 下午3:54
 */

namespace app\server\validate;


use think\Validate;

class ProviderWork extends Validate
{
    protected $rule = [
        'client'   =>  'require',
        'money' => 'require',
        'status' => 'require',
        'site'  =>  'require|max:63',
        'title' => 'require',
    ];

    protected $message  =   [
        'client.require' => '请选择服务对象',
        'money.require'   => '请填写工单金额',
        'title.require'   => '请填写工单标题',
        'status.require' => '请选择服务类型',
        'site.require' => '地址不能为空',
        'site.max'     => '地址最多不能超过63个字符',
    ];


}