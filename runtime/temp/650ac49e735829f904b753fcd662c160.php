<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:96:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/client/allergy.html";i:1547789743;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1547601686;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1547707416;}*/ ?>
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
</head>
<link rel="stylesheet" href="/public/static/css/client.css">
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>
                <small>服务对象管理&nbsp;>&nbsp;健康档案&nbsp;>&nbsp;药物过敏史</small>
                <a href="javascript:history.back(-1);" title="返回上一页"><i class="fa fa-reply"></i></a>
            </h5>
        </div>
        <div class="ibox-content c-allergy">
            <div class="col-sm-12 nav-tabs-client">
                <div class="col-sm-4">
                    <img src="<?php echo (isset($user['head']) && ($user['head'] !== '')?$user['head']:'/public/static/img/head.jpg'); ?>" alt="">&nbsp;&nbsp;
                    <span><?php echo $user['name']; ?></span>
                </div>
                <div class="col-sm-8">
                    <ul class="nav nav-tabs"></ul>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="col-sm-12">
                    <ul class="nav nav-tabs nav-tabs-healthy"></ul>
                </div>
            </div>
            <div class="col-sm-12 m-t">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-1 control-label">已添加的过敏药物</label>
                        <div class="col-sm-6">
                            <?php if(is_array($allergy) || $allergy instanceof \think\Collection || $allergy instanceof \think\Paginator): $i = 0; $__LIST__ = $allergy;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$a): $mod = ($i % 2 );++$i;?>
                            <span><strong><?php echo $a['medicine']; ?></strong> <a href="javascript:;">X</a></span>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">其他过敏药</label>
                        <div class="col-sm-3">
                            <div class="input-group m-b">
                                <input type="text" class="form-control">
                                <a class="input-group-addon"><i class="fa fa-plus"></i> 添加</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label"></label>
                        <div class="col-sm-6">
                            <button class="btn btn-primary active">保存</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="clearfix"></div>
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

<script type="text/javascript" src="/public/static/js/client.js"></script>
<script type="text/javascript">
    var $userId = '<?php echo $userId; ?>',
        $allergyInfo = '<?php echo json_encode($allergy); ?>';
</script>
</html>