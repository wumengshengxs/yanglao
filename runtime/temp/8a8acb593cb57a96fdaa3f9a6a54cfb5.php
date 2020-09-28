<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:94:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/server/view/login/login.html";i:1552614136;s:94:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/server/view/public/head.html";i:1552612121;s:94:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/server/view/public/foot.html";i:1552612121;}*/ ?>
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
<style>
    .gray-bg{
        background: url('/public/static/img/aa.jpg') no-repeat center fixed;
    }
    .back{
        background-color: white;
        height: 220px;
        margin-top: 120px;
        margin-left: 180px;
    }

</style>

<body class="gray-bg">
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div class="col-sm-12 back">
            <div class="panel blank-panel">
                <div class="panel-heading">
                    <div class="panel-options">
                         <h3 >服务商登录</h3>

                    </div>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div  >
                            <form class="m-t form-login">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" placeholder="服务商账户" required>
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

<script type="text/javascript" src="/public/static/js/server/login.js"></script>
</body>
</html>