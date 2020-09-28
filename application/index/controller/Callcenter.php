<?php
/**
 * 易米云接口管理
 */
namespace app\index\controller;

use think\Request;
use app\index\model\Call;
use app\index\model\YiMi;

class Callcenter extends Common{

    /**
     * 子账户与接口信息展示
     * @return mixed
     */
    public function callCenter()
    {
        $model = new YiMi();
        $call = new Call();
        $list = $call->getCallList();
        $config = [
            'url'=>$model->url,
            'accountSid'=>$model->accountSid,
            'accountToken'=>$model->accountToken,
            'appId'=>$model->appId,
            'softwareVersion'=>$model->softwareVersion,
        ];
        $this->assign('config',$config);
        $this->assign('list',$list);
        return view();
    }

    /**
     * 编辑子账户
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function editCallCenter($id)
    {
        $model = new Call();
        if (request()->isPost()){
            $post = Request::instance()->post();
            $call = $model->editCall($post);
            return  $call;

        }else{
            $data = $model->getCall($id);
            return  $data;
        }
    }

    /**
     * 更新呼叫限制
     */
    public function editCallLimit()
    {
        $model = new Call();
        $post = Request::instance()->post();
        $call = $model->editCallLimit($post);
        return  $call;
    }
}


