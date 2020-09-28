<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:98:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/provider/project.html";i:1551665224;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <div class="row row-lg">
                <div class="col-sm-12">
                    <div class="m-b">
                        <span style="font-size: 18px;"><?php echo $details['name']; ?></span>&nbsp;&nbsp;
                        <button class="btn btn-white btn-sm a-p-project"><i class="fa fa-plus"></i>&nbsp;添加一级类目</button>
                    </div>
                    <hr/>
                </div>
                <?php if(is_array($project) || $project instanceof \think\Collection || $project instanceof \think\Paginator): $i = 0; $__LIST__ = $project;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p): $mod = ($i % 2 );++$i;?>
                <div class="col-sm-12 m-b-lg">
                    <div class="col-sm-12">
                        <div class="col-sm-6 font-bold"><h3><?php echo $p['name']; ?></h3></div>
                        <div class="col-sm-6 text-right">
                            <button class="btn btn-white btn-xs e-p-project" data-id="<?php echo $p['id']; ?>"><i class="fa fa-plus"></i> 编辑</button>
                            <button class="btn btn-white btn-xs d-p-project" data-id="<?php echo $p['id']; ?>"><i class="fa fa-plus"></i> 删除</button>
                            <button class="btn btn-white btn-xs a-p-project" data-id="<?php echo $p['id']; ?>"><i class="fa fa-plus"></i> 添加二级类目</button>
                        </div>
                    </div>
                    <div class="col-sm-12 table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead class="">
                            <tr>
                                <th class="col-sm-2">二级类目</th>
                                <th class="col-sm-8">备注</th>
                                <th class="col-sm-2">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($p['nodes']) || $p['nodes'] instanceof \think\Collection || $p['nodes'] instanceof \think\Paginator): $i = 0; $__LIST__ = $p['nodes'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$n): $mod = ($i % 2 );++$i;?>
                            <tr>
                                <td><?php echo $n['name']; ?></td>
                                <td><?php echo (isset($n['remarks']) && ($n['remarks'] !== '')?$n['remarks']:'--'); ?></td>
                                <td>
                                    <a href="javascript:;" data-id="<?php echo $n['id']; ?>" class="e-p-project">编辑</a>&nbsp;&nbsp;
                                    <a href="javascript:;" data-id="<?php echo $n['id']; ?>" class="d-p-project">删除</a>
                                </td>
                            </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endforeach; endif; else: echo "" ;endif; ?>
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
    var $project = '<?php echo json_encode($project); ?>';
</script>
</html>