<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:96:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/provider/index.html";i:1552896514;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
<link rel="stylesheet" type="text/css" href="/public/static/js/plugins/layui/css/layui.css">
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <div class="m-b">
                <h5>
                    <a href="javascript:;" class="btn btn-white btn-sm a-provider"><i class="fa fa-plus"></i> 添加服务机构</a>&nbsp;&nbsp;
                </h5>
            </div>
            <div class="row row-lg">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>登录账号</th>
                                <th>公司名称</th>
                                <th>服务项目</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($provider['data']) || $provider['data'] instanceof \think\Collection || $provider['data'] instanceof \think\Paginator): $k = 0; $__LIST__ = $provider['data'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p): $mod = ($k % 2 );++$k;?>
                            <tr>
                                <td><?php echo $k; ?></td>
                                <td><?php echo $p['name']; ?></td>
                                <td><?php echo $p['company']; ?></td>
                                <td><?php echo $p['s_name']; ?></td>
                                <td><?php echo $p['status']; ?></td>
                                <td>
                                    <a href="/index/Provider/details?id=<?php echo $p['id']; ?>">详情</a>
                                    &nbsp;&nbsp;
                                    <a href="javascript:;" class="e-provider">编辑</a>
                                </td>
                            </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="page"><?php echo $page; ?> <?php echo $provider['total']; ?>条数据，共<?php echo $provider['last_page']; ?>页</div>
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

<script type="text/javascript" src="/public/static/js/provider.js"></script>
<script type="text/javascript">
    var $search = '<?php echo json_encode($item_value); ?>',
        $provider = '<?php echo json_encode($provider["data"]); ?>';
</script>
</html>