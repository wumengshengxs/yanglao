<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/staff/group.html";i:1551665224;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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

                <div class="ibox-content">
                    <div class="ibox-title" >
                        <button class="btn btn-white btn-sm"  data-target="#myModal4" data-toggle="modal" ><i class="fa fa-plus"></i> 添加技能组</button>
                    </div>

                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>技能组名称</th>
                            <th>系统编号</th>
                            <th>是否启用</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($group) || $group instanceof \think\Collection || $group instanceof \think\Paginator): $k = 0; $__LIST__ = $group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                        <tr>
                            <td><?php echo $k; ?></td>
                            <td><?php echo $vo['name']; ?></td>
                            <td><?php echo $vo['gid']; ?></td>
                            <td>
                                <?php if($vo['state'] == 1): ?>
                                启用中
                                <?php else: ?>
                                未启用
                                <?php endif; ?></td>
                            <td><?php echo $vo['create_time']; ?></td>
                            <td>
                                <button type="button" onclick="delGroup('<?php echo $vo['gid']; ?>')" class="btn btn-danger  btn-xs">删除</button>
                            </td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>

                    </table>

                    <div class="pull-left pagination-detail">
                        <span class="pagination-info">总共<?php echo $group->total(); ?>条记录</span>
                    </div>
                    <div align="center" >

                        <?php echo $group->render(); ?>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<div class="modal inmodal" id="myModal4" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4 >添加技能分组</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t" id="forms">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">技能组名称：</label>
                        <div class="col-sm-8">
                            <input  name="name" class="form-control" type="text"  maxlength="16">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">是否启用：</label>
                        <div class="radio radio-info radio-inline">
                            <input type="radio" id="status1" value="1" name="state" >
                            <label for="status1">启用</label>
                        </div>
                        <div class="radio radio-inline">
                            <input type="radio" id="status0" value="0" name="state" checked="">
                            <label for="status0"> 禁用</label>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="onSublimt()">保存</button>
                </center>
            </div>
        </div>
    </div>
</div>


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

<script src="/public/static/js/staff/group.js"></script>
