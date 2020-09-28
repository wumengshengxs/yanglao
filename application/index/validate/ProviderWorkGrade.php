<?php
/**
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/3/29
 * Time: 上午10:26
 */

namespace app\index\validate;


use think\Validate;

class ProviderWorkGrade extends Validate
{

    protected $rule = [
        'speed' => 'number|between:0,10',
        'meter' => 'number|between:0,10',
        'details'  =>  'number|between:0,10',
        'term' => 'number|between:0,10',
    ];

    protected $message  =   [
        'speed.between'   => '评分分数在0-10之间',
        'meter.between'   => '评分分数在0-10之间',
        'details.between' => '评分分数在0-10之间',
        'term.between' => '评分分数必须是数字',
        'speed.number'   => '评分分数必须是数字',
        'meter.number'   => '评分分数必须是数字',
        'details.number' => '评分分数必须是数字',
        'term.number' => '评分分数必须是数字',
    ];




}