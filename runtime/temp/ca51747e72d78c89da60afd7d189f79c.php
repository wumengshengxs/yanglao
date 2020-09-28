<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:98:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/staff/staff_info.html";i:1547619870;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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

<script type="text/javascript">
    /*关闭弹出框口*/
    function x_close(){
        var index = parent.layer.getFrameIndex(window.name);
        parent.layer.close(index);
    }
</script>
<div class="wrapper wrapper-content animated fadeInRight">
    <!-- 菜单 -->
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <form class="form-horizontal" id="signupForm" >

                        <div class="form-group">
                            <label class="col-sm-2 control-label">话务员名称：</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $user['display_name']; ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">联系电话：</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $user['phone']; ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">技能组名称：</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $user['group']; ?></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">呼叫中心工号：</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $user['work_number']; ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">分机号：</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $user['number']; ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">当前状态：</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $user['status']; ?></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">当前状态持续时间：</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $user['statusDuration']; ?></p>
                            </div>
                        </div>

                    </form>
                    <div class="hr-line-dashed"></div>

                </div>
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


