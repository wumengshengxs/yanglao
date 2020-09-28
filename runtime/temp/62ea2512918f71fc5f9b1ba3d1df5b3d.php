<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/login/login.html";i:1553150112;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
<link rel="stylesheet" type="text/css" href="/public/static/css/login-img.css">
<body class="gray-bg back-img">
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>
            <h1 class="logo-name">YL</h1>
        </div>
        <h3>欢迎使用 智慧养老平台</h3>
        <div class="col-sm-12">
            <div class="panel blank-panel">
                <div class="panel-heading">
                    <div class="panel-options">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="tabs_panels.html#tab-1" aria-expanded="true" class="tab-a">管理员登录</a>
                            </li>
                            <li class=""><a data-toggle="tab" href="tabs_panels.html#tab-2" aria-expanded="false">话务员登录</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane active">
                            <form class="m-t form-login">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" placeholder="管理员账户" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control" placeholder="登录密码" required>
                                </div>
                                <button type="submit" class="btn btn-primary block full-width m-b">登 录</button>
                            </form>
                        </div>
                        <div id="tab-2" class="tab-pane">
                            <form class="m-t form-login">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" placeholder="话务员账户" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control" placeholder="登录密码" required>
                                </div>
                                <button type="submit" class="btn btn-primary block full-width m-b">登 录</button>
                            </form>
                        </div>
                    </div>
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

<script type="text/javascript" src="/public/static/js/login.js"></script>
</body>
</html>