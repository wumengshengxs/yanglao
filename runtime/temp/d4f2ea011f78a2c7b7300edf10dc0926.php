<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:96:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/server/view/work/add_work.html";i:1553564014;s:94:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/server/view/public/head.html";i:1552612121;s:94:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/server/view/public/foot.html";i:1552612121;}*/ ?>
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
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <small>服务工单&nbsp;>&nbsp;添加服务工单</small>
                    <a href="javascript:history.back(-1);" title="返回上一页"><i class="fa fa-reply"></i></a>
                </div>
            </div>
            <div class="ibox-content">
                <form class="form-horizontal form-plan" id="forms">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">*选择服务对象</label>
                        <div class="col-sm-5">
                            <input type="hidden" name="client" value="">
                            <span class="ng-binding">已选：<span id="nums">0</span>人</span>
                            <a class="btn btn-white btn-xs b-grant-work" >
                                <i class="fa fa-plus"></i>&nbsp;&nbsp;服务对象
                            </a>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">*工单标题</label>
                        <div class="col-sm-5">
                            <input  name="title" class="form-control" type="text" placeholder="请输入工单标题" maxlength="32">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">*服务类型</label>
                        <div class="col-sm-5">
                            <select class="form-control m-b" name="status" >
                                <?php if(is_array($project) || $project instanceof \think\Collection || $project instanceof \think\Paginator): $i = 0; $__LIST__ = $project;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                <option value='<?php echo $vo['id']; ?>'  ><?php echo $vo['name']; ?></option >
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">*消费金额</label>
                        <div class="col-sm-5">
                            <input  name="money" class="form-control" type="number"  >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">*服务地址</label>
                        <div class="col-sm-5">
                            <input  name="site" class="form-control" type="text" placeholder="请输入详细地址" maxlength="64">
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <div class="form-group">
                        <div class="col-sm-1 col-sm-offset-1">
                            <button class="btn btn-primary" onclick="onSublimt()" >确定</button>
                        </div>
                    </div>
                </div>

            </div>
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

<script src="/public/static/js/server/work.js"></script>
</html>