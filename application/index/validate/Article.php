<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/10
 * Time: 11:58
 * 菜单栏目验证
 */
namespace app\index\validate;

use think\Validate;

class Article extends Validate
{
    protected $rule = [
        'title'  =>  'require|max:31',
        'content'=> 'require',
        'gid'=> 'require',
    ];

    protected $message  =   [
        'title.require' => '标题不能为空',
        'gid.require' => '请选择类型',
        'content.require' => '内容不能为空',
        'title.max'     => '类型名最多不能超过31个字符',

    ];

}