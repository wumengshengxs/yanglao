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

class ArticleGroup extends Validate
{
    protected $rule = [
        'name'  =>  'require|max:15',
    ];

    protected $message  =   [
        'name.require' => '分类名不能为空',
        'name.max'     => '类型名最多不能超过15个字符',

    ];

}