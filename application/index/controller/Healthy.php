<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/15
 * Time: 18:34
 * 健康管理
 */
namespace app\index\controller;

use app\index\controller\Common;
use think\Request;

class Healthy extends Common {
    /**
     * 健康档案列表
     */
    public function index()
    {
        $request = Request::instance();
        $param = [
            'name'=>addslashes($request->get('name')),                     // 姓名
            'mobile'=>addslashes($request->get('mobile')),                 // 手机号
            'id_number'=>addslashes($request->get('id_number')),           // 身份证号
            'start_age'=>addslashes($request->get('start_age')),           // 开始年龄
            'end_age'=>addslashes($request->get('end_age')),               // 截止年龄
            'start_create'=>addslashes($request->get('start_create')),     // 开始创建时间
            'end_create'=>addslashes($request->get('end_create')),         // 截止创建时间
            'start_birthday'=>addslashes($request->get('start_birthday')), // 开始出生日期
            'end_birthday'=>addslashes($request->get('end_birthday')),     // 截止出生日期
            'sex'=>addslashes($request->get('sex')),                       // 性别
            'group'=>$request->get()['group'],                             // 分组
            'tag'=>$request->get()['tag']                                  // 标签
        ];
        $where = [];
        $item_value = [];
        if($param['name']){
            array_push($where,"c.name like '%".$param['name']."%'");
            $item_value[] = ['name'=>'name','item'=>'姓名','value'=>$param['name']];
        }
        if($param['mobile']){
            array_push($where,"c.mobile like '%".$param['mobile']."%'");
            $item_value[] = ['name'=>'mobile','item'=>'手机号','value'=>$param['mobile']];
        }
        if($param['id_number']){
            array_push($where,"c.id_number like '".$param['ic_number']."'");
            $item_value[] = ['name'=>'id_number','item'=>'身份证','value'=>$param['id_number']];
        }
        if($param['start_age'] && $param['end_age']){
            array_push($where,"c.age between ".$param['start_age']." and ".$param['end_age']);
            $item_value[] = ['name'=>'age','item'=>'年龄','value'=>$param['name'].'至'.$param['end_age']];
        }
        if($param['start_age'] && !$param['end_age']){
            array_push($where,"c.age >= ".$param['start_age']);
            $item_value[] = ['name'=>'start_age','item'=>'年龄','value'=>$param['start_age'].'至-'];
        }
        if(!$param['start_age'] && $param['end_age']){
            array_push($where,"c.age <= ".$param['end_age']);
            $item_value[] = ['name'=>'end_age','item'=>'年龄','value'=>'-至'.$param['end_age']];
        }
        if($param['start_create'] && $param['end_create']){
            array_push($where,"c.create_time between ".strtotime($param['start_create'])." and ".strtotime($param['end_create']));
            $item_value[] = ['name'=>'create','item'=>'创建时间','value'=>$param['start_create'].'至'.$param['end_create']];
        }
        if($param['start_create'] && !$param['end_create']){
            array_push($where,"c.create_time >= ".strtotime($param['start_create']));
            $item_value[] = ['name'=>'start_create','item'=>'创建时间','value'=>$param['start_create'].'至-'];
        }
        if(!$param['start_create'] && $param['end_create']){
            array_push($where,"c.create_time <= ".strtotime($param['end_create']));
            $item_value[] = ['name'=>'end_create','item'=>'创建时间','value'=>'-至'.$param['end_create']];
        }
        if($param['start_birthday'] && $param['end_birthday']){
            array_push($where,"c.birthday between ".strtotime($param['start_birthday'])." and ".strtotime($param['end_birthday']));
            $item_value[] = ['name'=>'birthday','item'=>'出生日期','value'=>$param['start_birthday'].'至'.$param['end_birthday']];
        }
        if($param['start_birthday'] && !$param['end_birthday']){
            array_push($where,"c.birthday >= ".strtotime($param['start_birthday']));
            $item_value[] = ['name'=>'start_birthday','item'=>'出生日期','value'=>$param['start_birthday'].'至-'];
        }
        if(!$param['start_birthday'] && $param['end_birthday']){
            array_push($where,"c.birthday <= ".strtotime($param['end_birthday']));
            $item_value[] = ['name'=>'end_birthday','item'=>'出生日期','value'=>'-至'.$param['end_birthday']];
        }
        if($param['sex']){
            array_push($where,"c.sex = ".$param['sex']);
            $sex = ($param['sex'] == 1) ? '男' : '女' ;
            $item_value[] = ['name'=>'sex','item'=>'性别','value'=>$sex];
        }
        if(!empty($param['group'])){
            $whereGroup = '(';
            foreach($param['group'] as $g){
                $whereGroup .= "find_in_set(".$g.",g.gid) OR ";
            }
            array_push($where,rtrim($whereGroup,'OR ') .")");
            // 获取分组信息
            $group_id = implode(',',$param['group']);
            $whereGroup = " `id` IN (".$group_id.")";
            $group = model('Group')->groupName($whereGroup);
            $item_value[] = ['name'=>'group[]','item'=>'分组','value'=>$group];
        }
        if(!empty($param['tag'])){
            $whereTag = '(';
            foreach($param['tag'] as $t){
                $whereTag .= "find_in_set(".$t.",t.tid) OR ";
            }
            array_push($where,rtrim($whereTag,'OR ') .")");
            // 获取标签信息
            $tag_id = implode(',',$param['tag']);
            $whereTag = " `id` IN (".$tag_id.")";
            $tag = model('Tag')->tagName($whereTag);
            $item_value[] = ['name'=>'tag[]','item'=>'标签','value'=>$tag];
        }
        $whereString = join(' and ',$where);
        $arr_filter = array_filter($param);
        $healthy = model('Healthy')->healthyRecordsList($whereString, $arr_filter);
        $this->assign('healthy',$healthy['healthy']);
        $this->assign('page',$healthy['page']);
        $this->assign('item_value',$item_value);
        $this->assign('param',$arr_filter);
        $this->assign('title','养老平台 - 健康档案');
        return view();
    }


    /*
    *时时健康
    */
    public function oftenhealth(){
        $data = model('Healthy')->GetOftenHealth();
        // bug($data['search']);
        $this->assign('data',$data);
        return $this->fetch();
    }
}