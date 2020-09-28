<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:99:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/work/work_details.html";i:1551665224;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <meta name="keywords" content="智慧养老">
    <meta name="description" content="智慧养老">
    <!--[if lt IE 8]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <link href="/public/static/css/bootstrap.min.css?v=3.3.5" rel="stylesheet">
    <link href="/public/static/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/public/static/css/animate.min.css" rel="stylesheet">
    <link href="/public/static/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="/public/static/css/style.min.css?v=4.0.0" rel="stylesheet">
    <link href="/public/static/css/plugins/treeview/bootstrap-treeview.css" rel="stylesheet">
    <link href="/public/static/css/plugins/switchery/switchery.css?v=1.0" rel="stylesheet">
    <link href="/public/static/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/static/css/common.css">
    <link rel="stylesheet" href="/public/static/js/plugins/layui/css/modules/laydate/default/laydate.css">
</head>
<link rel="stylesheet" href="/public/static/css/work.css">
<link type="text/css" rel="stylesheet" href="/public/static/rating/css/application.css">
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>
                        <small>工单管理&nbsp;>&nbsp;工单详情</small>
                        <a href="javascript:history.back(-1);" title="返回上一页"><i class="fa fa-reply"></i></a>
                    </h5>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>工单详情</h5>
                </div>
                <div class="ibox-content">
                    <div class="col-sm-8">
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>标题</stront></label>
                            <span class="col-sm-8"><?php echo $details['title']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>内容</stront></label>
                            <span class="col-sm-8"><?php echo $details['content']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>话务员</stront></label>
                            <span class="col-sm-8"><?php echo $details['display_name']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>生成时间</stront></label>
                            <span class="col-sm-8"><?php echo $details['create_time']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>工单类型</stront></label>
                            <span class="col-sm-8"><?php echo $details['type']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>工单状态</stront></label>
                            <span class="col-sm-8"><?php echo $details['state']; ?></span>
                        </div>
                        <?php if($details['type'] == '主动关怀'): ?>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>计划状态</stront></label>
                            <span class="col-sm-8"><?php echo $details['plan_state']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>开始时间</stront></label>
                            <span class="col-sm-8"><?php echo $details['start_time']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>截止时间</stront></label>
                            <span class="col-sm-8"><?php echo $details['end_time']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>完成时间</stront></label>
                            <span class="col-sm-8"><?php echo (isset($details['finish_time']) && ($details['finish_time'] !== '')?$details['finish_time']:'--'); ?></span>
                        </div>
                        <?php endif; if($details['state'] == '已办结'): ?>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>办结通话结果</stront></label>
                            <span class="col-sm-8"><?php echo $details['call_result']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>办结备注</stront></label>
                            <span class="col-sm-8"><?php echo $details['remarks']; ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-4 text-center">
                        <?php if($action == true && $details['state'] == '已办结'): ?>
                        <button type="button" class="btn btn-primary" data-class="quality-work">工单质检</button>
                        <button type="button" class="btn btn-primary" data-class="return-work">退回工单</button>
                        <?php else: if($details['state'] == '未受理'): ?>
                        <button type="button" class="btn btn-primary" data-class="accept-work">受理工单</button>
                        <br/>
                        <?php endif; if($details['state'] == '受理中'): ?>
                        <button type="button" class="btn btn-primary" data-class="finish-work">办结工单</button>
                        <br/>
                        <?php endif; if(in_array($details['state'],['已办结','已关闭'])): ?>
                        <button type="button" class="btn btn-primary" data-class="open-work">重新打开</button>
                        <?php endif; if(in_array($details['state'],['未受理','受理中'])): ?>
                        <button type="button" class="btn btn-white btn-xs" data-class="transfer-work">转交</button>
                        <button type="button" class="btn btn-white btn-xs" data-class="close-work">关闭</button>
                        <?php endif; endif; ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>服务对象</h5>
                </div>
                <div class="ibox-content">
                    <div class="col-sm-8">
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>姓名</stront></label>
                            <span class="col-sm-8"><?php echo $client['name']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>手机号</stront></label>
                            <span class="col-sm-8"><?php echo (isset($client['mobile']) && ($client['mobile'] !== '')?$client['mobile']:'--'); ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>身份证</stront></label>
                            <span class="col-sm-8"><?php echo $client['id_number']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>腕表电话</stront></label>
                            <span class="col-sm-8"><?php echo (isset($client['watch']) && ($client['watch'] !== '')?$client['watch']:'--'); ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>年龄</stront></label>
                            <span class="col-sm-8"><?php echo $client['age']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>性别</stront></label>
                            <span class="col-sm-8"><?php echo $client['sex']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>分组</stront></label>
                            <span class="col-sm-8"><?php echo (isset($client['groupName']) && ($client['groupName'] !== '')?$client['groupName']:'--'); ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>地址</stront></label>
                            <span class="col-sm-8"><?php echo (isset($client['address']) && ($client['address'] !== '')?$client['address']:'--'); ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>标签</stront></label>
                            <span class="col-sm-8"><?php echo (isset($client['tagName']) && ($client['tagName'] !== '')?$client['tagName']:'--'); ?></span>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>近期工单</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>标题</th>
                            <th>创建时间</th>
                            <th>服务对象</th>
                            <th>话务员</th>
                            <th>工单类型</th>
                            <th>工单状态</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!(empty($work) || (($work instanceof \think\Collection || $work instanceof \think\Paginator ) && $work->isEmpty()))): if(is_array($work) || $work instanceof \think\Collection || $work instanceof \think\Paginator): $i = 0; $__LIST__ = $work;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td><?php echo $vo['title']; ?></td>
                            <td><?php echo date('Y-m-d',$vo['create_time']); ?></td>
                            <td><?php echo $vo['userName']; ?></td>
                            <td><?php echo $vo['display_name']; ?></td>
                            <td><?php echo $vo['type']; ?></td>
                            <td><?php echo $vo['state']; ?></td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; else: ?>
                        <tr>
                            <td colspan="6">暂无数据</td>
                        </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>工单处理记录</h5>
                </div>
                <div class="ibox-content">
                    <?php if(in_array($details['state'],['未分配','未受理','受理中']) && $action == false): ?>
                    <div class="col-sm-12">
                        <form class="form-horizontal f-work-remarks">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">备注工单</label>
                                <div class="col-sm-6">
                                    <textarea name="remarks" class="form-control" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6 col-lg-offset-2">
                                   <button type="submit" class="btn btn-primary">备注</button>
                                </div>
                            </div>
                        </form>
                        <hr/>
                    </div>
                    <?php endif; if($details['state'] == '受理中' && $details['type'] == '主动外呼' && $action == false): ?>
                    <div class="col-sm-12">
                        <form class="form-horizontal f-work-outbound-call">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">外呼电话</label>
                                <div class="col-sm-6">
                                    <input type="text" name="mobile" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6 col-lg-offset-2">
                                    <button type="submit" class="btn btn-primary">外呼</button>
                                </div>
                            </div>
                        </form>
                        <hr/>
                    </div>
                    <?php endif; ?>
                    <div class="col-sm-12 log-records">
                        <div>
                            <h5>工单处理历史</h5>
                        </div>
                        <?php if(is_array($workLog) || $workLog instanceof \think\Collection || $workLog instanceof \think\Paginator): $i = 0; $__LIST__ = $workLog;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$l): $mod = ($i % 2 );++$i;?>
                        <div class="row">
                            <div class="col-sm-12 m-b-sm">
                                <label class="col-sm-2 text-right">操作日期</label>
                                <span class="col-sm-10"><?php echo $l['create_time']; ?></span>
                            </div>
                            <div class="col-sm-12 m-b-sm">
                                <label class="col-sm-2 text-right">操作类型</label>
                                <span class="col-sm-10"><?php echo $l['type']; ?></span>
                            </div>
                            <div class="col-sm-12">
                                <label class="col-sm-2 text-right">操作内容</label>
                                <span class="col-sm-10"><?php echo $l['content']; ?></span>
                            </div>
                            <?php if($l['call_log'] == true): ?>
                            <div class="col-sm-12">
                                <hr/>
                                <div class="col-sm-4 m-b-xs">
                                    <label class="col-sm-6 text-right">坐席工号</label>
                                    <span class="col-sm-6"><?php echo $l['content']; ?></span>
                                </div>
                                <div class="col-sm-4 m-b-xs">
                                    <label class="col-sm-6 text-right">坐席分机号</label>
                                    <span class="col-sm-6"><?php echo $l['content']; ?></span>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-sm-4 m-b-xs">
                                    <label class="col-sm-6 text-right">记录创建时间</label>
                                    <span class="col-sm-6"><?php echo $l['content']; ?></span>
                                </div>
                                <div class="col-sm-4 m-b-xs">
                                    <label class="col-sm-6 text-right">通话开始时间</label>
                                    <span class="col-sm-6"><?php echo $l['content']; ?></span>
                                </div>
                                <div class="col-sm-4 m-b-xs">
                                    <label class="col-sm-6 text-right">通话结束时间</label>
                                    <span class="col-sm-6"><?php echo $l['content']; ?></span>
                                </div>
                                <div class="col-sm-4 m-b-xs">
                                    <label class="col-sm-6 text-right">主叫号码</label>
                                    <span class="col-sm-6"><?php echo $l['content']; ?></span>
                                </div>
                                <div class="col-sm-4 m-b-xs">
                                    <label class="col-sm-6 text-right">被叫号码</label>
                                    <span class="col-sm-6"><?php echo $l['content']; ?></span>
                                </div>
                                <div class="col-sm-4 m-b-xs">
                                    <label class="col-sm-6 text-right">响铃时长</label>
                                    <span class="col-sm-6"><?php echo $l['content']; ?></span>
                                </div>
                                <div class="col-sm-4 m-b-xs">
                                    <label class="col-sm-6 text-right">通话时长</label>
                                    <span class="col-sm-6"><?php echo $l['content']; ?></span>
                                </div>
                                <div class="col-sm-4 m-b-xs">
                                    <label class="col-sm-6 text-right">通话状态</label>
                                    <span class="col-sm-6"><?php echo $l['content']; ?></span>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-sm-4">
                                    <label class="col-sm-6 text-right">通话录音</label>
                                    <span class="col-sm-6"><?php echo $l['content']; ?></span>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="col-sm-12">
                                <hr/>
                            </div>
                        </div>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="/public/static/js/jquery.min.js?v=2.1.4"></script>
<script src="/public/static/js/bootstrap.min.js?v=3.3.5"></script>
<script src="/public/static/js/plugins/layui/layui.all.js"></script>
<script src="/public/static/js/plugins/chosen/chosen.jquery.js?v=1.0"></script>
<script src="/public/static/js/plugins/switchery/switchery.js?v=1.0"></script>
<script src="/public/static/js/plugins/treeview/bootstrap-treeview.js"></script>
<script src="/public/static/js/content.min.js"></script>
<script src="/public/static/js/plugins/validate/jquery.validate.min.js"></script>
<script src="/public/static/js/plugins/validate/messages_zh.min.js"></script>
<script src="/public/static/js/common.js"></script>
<script type="text/javascript" src="/public/static/js/plugins/layui/lay/modules/laydate.js"></script>

<script type="text/javascript" src="/public/static/js/work/work.js"></script>
<script type="text/javascript" src="/public/static/rating/js/jquery.raty.min.js"></script>
<script type="text/javascript">
    var $work_id = '<?php echo $_GET["id"]; ?>',
        $details = '<?php echo json_encode($details); ?>';
</script>
</html>