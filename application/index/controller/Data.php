<?php
/**
 * Created by PhpStorm.
 * User: Hongfei
 * Date: 2019/4/10
 * Time: 下午1:33
 */

namespace app\index\controller;


use think\Request;

class Data extends Common
{

    /**
     * 主动关怀统计
     * @return \think\response\View
     */
    public function work()
    {
        $get = Request::instance()->get();
        $data = model('work')->dataStaff($get);
        $this->assign('work',$data);
        $this->assign('title','养老平台 - 主动关怀统计');
        return view();
    }


}