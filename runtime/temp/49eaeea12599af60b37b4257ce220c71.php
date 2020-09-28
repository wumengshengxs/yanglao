<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:96:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/works/add_plan.html";i:1550218609;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1548228499;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1548228499;}*/ ?>
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
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <!-- 权限菜单 -->
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>
                        <a href="javascript:history.go(-1)">
                            计划任务列表&nbsp;&nbsp;<i class='fa fa-angle-double-right'></i>
                        </a>
                        <label>&nbsp;&nbsp;添加计划任务</label>
                    </h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal" id="signupForm" >


                        <div class="form-group">
                            <label class="col-sm-2 control-label">计划名称</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder='请输入计划名称' name='name' required="" aria-required="true" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">范围选择</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type="text" class="form-control times" name="start_time"   id="start" placeholder="起始时间"  readonly="true" />
                                            <span class="input-group-addon">到</span>
                                            <input type="text" class="form-control times" name="end_time" id="end" placeholder="结束时间"  readonly="true" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">选择服务对象</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-md-4">
                                        <span class="ng-binding">已选：<span id="nums">0</span>人</span>
                                        <a class="btn btn-primary btn-xs b-grant-workPlan" >
                                            <i class="fa fa-plus"></i>&nbsp;&nbsp;服务对象
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">话务员</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-md-4">
                                        <?php if(is_array($staff) || $staff instanceof \think\Collection || $staff instanceof \think\Paginator): $i = 0; $__LIST__ = $staff;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                        <div class="checkbox checkbox-inline">
                                            <input type="checkbox" id="ra<?php echo $vo['number']; ?>" value="<?php echo $vo['number']; ?>" name="sid[]" />
                                            <label for="ra<?php echo $vo['number']; ?>"><?php echo $vo['display_name']; ?></label>
                                        </div>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">分配策略</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio"  checked="">
                                            <label for="inlineRadio1">平均分配</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">启用</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="state" checked="">
                                            <label for="inlineRadio1">启用</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="state">
                                            <label for="inlineRadio2">草稿</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="uids" id="uids">
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-primary"  onclick="obSubmit()">保存内容</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>

</html>
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

<script type="text/javascript" src="/public/static/js/bootstrap-paginator.js"></script>
<script src="/public/static/js/work/plan.js"></script>




