<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/23
 * Time: 17:42
 * 服务商管理
 */
namespace app\index\controller;

use app\index\controller\Common;
use think\Request;

class Provider extends Common {
    /**
     * 服务商列表
     */
    public function index()
    {
        $provider = model('Provider')->providerList();
        $this->assign('provider',$provider['provider']);
        $this->assign('page',$provider['page']);
        $this->assign('title','养老平台 - 服务商列表');
        return view();
    }

    /**
     * 添加服务商
     */
    public function addProvider()
    {
        $this->assign('title','养老平台 - 添加服务商');
        return view('provider');
    }

    /**
     * 编辑服务商
     */
    public function editProvider()
    {
        $id = Request::instance()->get('id');
        $details = model('Provider')->providerDetails($id);
        $this->assign('details',$details);
        $this->assign('title','养老平台 - 更新服务商');
        return view('provider');
    }

    /**
     * 提交服务商信息
     */
    public function submitProviderInfo()
    {
        $request = Request::instance();
        $id = (int)$request->post('id');
        $company = addslashes($request->post('company'));
        $name = addslashes($request->post('name'));
        $password = addslashes($request->post('password'));
        $linkman = addslashes($request->post('linkman'));
        $mobile = addslashes($request->post('mobile'));
        $address = addslashes($request->post('address'));
        $status = is_null($request->post('status')) ? 2 : 1;
        $result = model('Provider')->subProviderInfo($id, $company, $name, $password, $linkman, $mobile, $address, $status);
        return $result;
    }
//    public function submitProviderInfo()
//    {
//        $request = Request::instance();
//        $id = (int)$request->post('id');
//        $company = addslashes($request->post('company'));
//        $short_name = addslashes($request->post('short_name'));
//        $contacts = addslashes($request->post('contacts'));
//        $contacts_mobile = addslashes($request->post('contacts_mobile'));
//        $address = addslashes($request->post('address'));
//        $legal_person = addslashes($request->post('legal_person'));
//        $legal_mobile = addslashes($request->post('legal_mobile'));
//        $registered_capital = addslashes($request->post('registered_capital'));
//        $join_time = strtotime($request->post('join_time'));
//        $expiry_time = strtotime($request->post('expiry_time'));
//        $org_code = addslashes($request->post('org_code'));
//        $org_type = addslashes($request->post('org_type'));
//        $tax_number = addslashes($request->post('tax_number'));
//        $health_permit = addslashes($request->post('health_permit'));
//        $remarks = addslashes($request->post('remarks'));
//        $result = model('Provider')->subProviderInfo($id, $name, $contacts, $contacts_mobile, $address, $short_name, $legal_person, $legal_mobile, $registered_capital, $join_time, $expiry_time, $org_code, $org_type, $tax_number, $health_permit, $remarks);
//        return $result;
//    }

    /**
     * 删除服务商（暂时作废）
     */
    public function delProvider()
    {
        $id = Request::instance()->post('id');
        $result = model('Provider')->deleteProvider($id);
        return $result;
    }

    /**
     * 服务商详情信息
     */
    public function details()
    {
        $request = Request::instance();
        $action = addslashes($request->get('action'));
        $id = Request::instance()->get('id');
        $details = model('Provider')->providerDetails((int)$id);
        $this->assign('details',$details);
        $this->assign('id',$details['id']);
        $this->assign('title','养老平台 - 服务商详情');
        return view();
    }

    /**
     * 服务商人员信息
     */
    public function detailsStaff()
    {
        $id = Request::instance()->get('id');
        $staff = model('Provider')->providerStaff((int)$id);
        $company = model('Provider')->company((int)$id);
        $this->assign('staff',$staff);
        $this->assign('company',$company['company']);
        $this->assign('id',$id);

        $this->assign('title','养老平台 - 服务商详情');
        return view();
    }

    /**
     * 服务商工单信息
     */
    public function detailsWork()
    {
        $id = Request::instance()->get('id');
        $work = model('Provider')->providerWork((int)$id);
        $company = model('Provider')->company((int)$id);
        $this->assign('work',$work);
        $this->assign('company',$company['company']);
        $this->assign('id',$id);

        $this->assign('title','养老平台 - 服务商详情');
        return view();
    }

    /**
     * 提交服务商附加信息
     */
    public function subProviderBaseInfo()
    {
        $request = Request::instance();
        $id = (int)$request->post('id');
        $legal_person = addslashes($request->post('legal_person'));
        $legal_mobile = addslashes($request->post('legal_mobile'));
        $registered_capital = addslashes($request->post('registered_capital'));
        $join_time = strtotime($request->post('join_time'));
        $expiry_time = strtotime($request->post('expiry_time'));
        $org_code = addslashes($request->post('org_code'));
        $org_type = addslashes($request->post('org_type'));
        $tax_number = addslashes($request->post('tax_number'));
        $health_permit = addslashes($request->post('health_permit'));
        $remarks = addslashes($request->post('remarks'));
        $result = model('Provider')->subProviderBaseInfo($id, $legal_person, $legal_mobile, $registered_capital, $join_time, $expiry_time, $org_code, $org_type, $tax_number, $health_permit, $remarks);
        return $result;
    }
    
    /**
     * 服务商的服务项目列表
     */
    public function service()
    {
        $id = Request::instance()->get('id');
        // 获取服务商信息
        $details = model('Provider')->providerDetails((int)$id);
        $this->assign('details',$details);
        // 获取服务列表
        $whereS['pid'] = ['eq',$id];
        $service = model('ProviderService')->serviceList($whereS);
        $this->assign('service',$service['service']);
        $this->assign('page',$service['page']);
        $this->assign('title','养老平台 - 服务商服务列表');
        return view();
    }

    /**
     * 提交服务项目信息
     */
    public function submitService()
    {
        $request = Request::instance();
        $id = addslashes($request->post('id'));
        $uid = addslashes($request->post('uid'));
        $name = addslashes($request->post('name'));
        $f_project = addslashes($request->post('project1'));
        $s_project = addslashes($request->post('project2'));
        $remarks = addslashes($request->post('remarks'));
        $result = model('ProviderService')->submitService($id, $uid, $name, $f_project, $s_project, $remarks);
        return $result;
    }

    /**
     * 删除服务商的服务项目
     */
    public function delService()
    {
        $request = Request::instance();
        $id = (int)$request->post('id');
        $uid = (int)$request->post('uid');
        $result = model('ProviderService')->deleteService($id, $uid);
        return $result;
    }

    /**
     * 服务商类目
     */
    public function project()
    {
        $project = model('ProviderProject')->projectList();
        if(Request::instance()->isAjax()){
            return $project;
        }
        $this->assign('project',$project);
        $this->assign('title','养老平台 - 服务商类目');
        return view();
    }

    /**
     * 提交服务类目
     */
    public function submitProject()
    {
        $request = Request::instance();
        $pid = (int)$request->post('pid');
        $id = (int)$request->post('id');
        $name = addslashes($request->post('name'));
        $remarks = addslashes($request->post('remarks'));
        $result = model('ProviderProject')->submitProject($id, $pid, $name, $remarks);
        return $result;
    }

    /**
     * 删除服务类目
     */
    public function delProject()
    {
        $id = Request::instance()->post('id');
        $result = model('ProviderProject')->deleteProject($id);
        return $result;
    }

    /**
     * 获取服务工单
     * @return \think\response\View
     */
    public function work()
    {
        $project = model('server/Provider')->projectList();//服务类目
        $staff = model('Provider')->projectStaffList();//服务人员
        $provider = model('Provider')->WorkProviderList();//服务商
        $param = Request::instance()->get();
        $work = model('ProviderWork')->getWorkList($param);

        $this->assign('project',$project);
        $this->assign('staff',$staff);
        $this->assign('provider',$provider);
        $this->assign('work',$work);
        $this->assign('title','养老平台 - 服务商工单列表');
        return view();
    }

    /**
     * 添加工单
     */
    public function addWork()
    {
        if (request()->isPost()){
            $post = Request::instance()->post();
            $work = model('ProviderWork')->addWork($post);
            return $work;
        }else{
            $project = model('server/Provider')->projectList();
            $provider = model('Provider')->WorkProviderList();
            $this->assign('title','养老平台 - 添加服务工单');
            $this->assign('project',$project);
            $this->assign('provider',$provider);

            return view();
        }
    }

    /**
     * 工单明细
     * @param $id
     * @return \think\response\View
     */
    public function workDetails($id)
    {
        $provider = model('ProviderWork')->getWorkProvider($id);
        $this->assign('title','养老平台 - 服务工单明细');
        $this->assign('provider',$provider);

        return view();
    }

    /**
     * 关闭工单
     */
    public function closeWork()
    {
        $id = Request::instance()->post('id');
        $provider = model('ProviderWork')->closeWork($id);

        return $provider;
    }

    /**
     * 添加评分
     */
    public function addGrade()
    {
        $post = Request::instance()->post();
        $work = model('ProviderWorkGrade')->addGrade($post);
        return $work;
    }

    /**
     * 编辑评分
     * @param $id
     * @return \think\response\View
     */
    public function saveGrade($id)
    {
       if (request()->isPost()){
           $post = Request::instance()->post();
           $work = model('ProviderWorkGrade')->saveGrade($post);
           return $work;
       }else{
           $project = model('ProviderWorkGrade')->getGrade($id);

           return $project;
       }
    }





}











