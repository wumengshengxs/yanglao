<?php
/**
 * 积分管理
 * User: Administrator
 * Date: 2019/1/18
 * Time: 15:29
 */
namespace app\index\controller;

use app\index\model\Integral;
use think\Request;

class Integration extends Common {

    /**
     * 获取积分查看
     * @return \think\response\View
     */
    public function integral()
    {
        $model = new Integral();
        $request = Request::instance();
        $param = [
            'name'=>addslashes($request->get('name')),
            'start_age'=>addslashes($request->get('start')),
            'end_age'=>addslashes($request->get('end')),
            'sex'=>addslashes($request->get('sex'))
        ];
        $where = $this->searchWhere($param);
        $query = array_filter($param);
        $client = $model->integralList($where, $query);
        $this->assign('client',$client);
        // 获取用户的总积分数、平均积分、最高和最低积分
        $clientIntegral = $model->clientIntegral();
        $this->assign('clientIntegral',$clientIntegral);
        // 获取发放和核销的总计分数
        $sumIntegralRecords = $model->sumIntegralRecords();
        $this->assign('sumIntegralRecords',$sumIntegralRecords);
        $this->assign('param',$param);
        $this->assign('title','养老平台 - 积分管理');
        return view();
    }

    /**
     * 核销积分查看
     */
    public function destroy()
    {
        $model = new Integral();
        $request = Request::instance();
        $param = [
            'name'=>addslashes($request->get('name')),
            'start_age'=>addslashes($request->get('start')),
            'end_age'=>addslashes($request->get('end')),
            'sex'=>addslashes($request->get('sex'))
        ];
        $where = $this->searchWhere($param);
        $where['i.type'] = ['eq',2];
        $query = array_filter($param);
        $client = $model->integralRecords($where, $query);
        $this->assign('client',$client);
        $this->assign('param',$param);
        $this->assign('title','养老平台 - 核销积分管理');
        return view();
    }

    /**
     * 累积积分查看
     */
    public function accumulate()
    {
        $model = new Integral();
        $request = Request::instance();
        $param = [
            'name'=>addslashes($request->get('name')),
            'start_age'=>addslashes($request->get('start')),
            'end_age'=>addslashes($request->get('end')),
            'sex'=>addslashes($request->get('sex'))
        ];
        $where = $this->searchWhere($param);
        $where['i.type'] = ['eq',1];
        $query = array_filter($param);
        $client = $model->integralRecords($where, $query);
        $this->assign('client',$client);
        $this->assign('param',$param);
        $this->assign('title','养老平台 - 累积积分管理');
        return view();
    }

    /**
     * 积分搜索条件
     */
    protected function searchWhere($search=[])
    {
        $where = [];
        if($search['name']){
            $where['u.name'] = ['like','%'.$search['name'].'%'];
        }
        if($search['start_age'] && $search['end_age']){
            $where['u.age'] = ['between',$search['start_age'].','.$search['end_age']];
        }
        if($search['start_age'] && !$search['end_age']){
            $where['u.age'] = ['egt',$search['start_age']];
        }
        if(!$search['start_age'] && $search['end_age']){
            $where['u.age'] = ['elt',$search['end_age']];
        }
        if($search['sex']){
            $where['u.sex'] = ['eq',$search['sex']];
        }
        return $where;
    }

    /**
     * 添加积分
     */
    public function addInt()
    {
        $model = new Integral();
        $post = Request::instance()->post();
        $score = $model->addInt($post,1);
        return $score;
    }

    /**
     * 获取excel模板
     */
    public function destoryTemplet()
    {
        $model = new Integral();
        $model->exportTemplet();
    }
    
    /**
     * 上传积分核销的excel
     */
    public function uploadExcel()
    {
        $result = F_upload_to_local($name='file', $size=2, $type=['xls','xlsx']);
        return $result;
    }

    /**
     * 提交批量核销积分
     */
    public function submitDestoryIntegra()
    {
        $excel = $_SERVER['DOCUMENT_ROOT'].addslashes(Request::instance()->post('integra_excel'));
        $result = model('Integral')->batchDestoryIntegra($excel);
        return $result;
    }



    /**
     * 获取服务对象列表
     */
    public function clientList()
    {
        $request = Request::instance();
        $name = addslashes($request->post('name'));
        $group = addslashes($request->post('group'));
        $tag = addslashes($request->post('tag'));
        $where = [];
        if($name){
            array_push($where,"c.name like '%".$name."%'");
        }
        if($group){
            array_push($where,"find_in_set(".$group.",g.gid)");
        }
        if($tag){
            array_push($where,"find_in_set(".$tag.",t.tid)");
        }
        $where = join(' AND ',$where);
        $result = model('Client')->clientList($where);
        return $result['client'];
    }

    /**
     * 获取分组列表
     */
    public function groupList()
    {
        $group = model('Group')->groupList($where=[], $limit=100000);
        return $group['group'];
    }

    /**
     * 获取标签列表
     */
    public function tagList()
    {
        $tag = model('Tag')->tagList();
        return $tag;
    }

    /**
     * 提交积分批量发放的信息
     */
    public function submitIntegral()
    {
        $request = Request::instance();
        $client = json_decode($request->post()['client'],true);
        $integral = addslashes($request->post('integral'));
        $remarks = addslashes($request->post('remarks'));
        $result = model('Integral')->batchGrantIntegral($client, $integral, $remarks);
        return $result;
    }

}