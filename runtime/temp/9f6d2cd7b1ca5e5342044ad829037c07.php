<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:91:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/data/work.html";i:1554970556;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
                        <div class="btn-group search">
                            <button class="btn btn-white" type="button"><i class="fa fa-search"></i> 高级搜索</button>
                                <div class="dropdown-menu">
                                    <form class="form-horizontal" action="/index/data/work" method="get">
                                    <div class="form-group">
                                            <label class="col-sm-3 control-label">日期</label>
                                            <div class="col-sm-4">
                                                <input type="text" name="start_create" readonly id="start_create" value="<?php echo \think\Request::instance()->get('start_create'); ?>" class="form-control times">
                                            </div>
                                            <div class="col-sm-1 middle-div">至</div>
                                            <div class="col-sm-4 f-right">
                                                <input type="text" name="end_create" readonly id="end_create" value="<?php echo \think\Request::instance()->get('end_create'); ?>" class="form-control times">
                                            </div>
                                        </div>
                                        <div class="hr-line-dashed"></div>
                                        <div class="form-group">
                                            <div class="col-sm-4 col-sm-offset-3">
                                                <button class="btn btn-primary" type="submit">搜索</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                        </div>
                        <button class="btn btn-white f-right m-r-sm" title="点击下载模板" onclick="clientDown();"><i class="fa fa-download"></i> 导出报表</button>

                    </div>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>日期</th>
                            <th>话务员名称</th>
                            <th>总数</th>
                            <th>正常接听</th>
                            <th>未接听</th>
                            <th>挂断</th>
                            <th>定不清或无声</th>
                            <th>其他</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($work) || $work instanceof \think\Collection || $work instanceof \think\Paginator): $k = 0; $__LIST__ = $work;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                        <tr>
                            <td><?php echo $k; ?></td>
                            <td><?php echo $vo['date']; ?></td>
                            <td><?php echo $vo['display_name']; ?></td>
                            <td><?php echo $vo['total']; ?></td>
                            <td><?php echo $vo['one']; ?></td>
                            <td><?php echo $vo['two']; ?></td>
                            <td><?php echo $vo['three']; ?></td>
                            <td><?php echo $vo['four']; ?></td>
                            <td><?php echo $vo['rest']; ?></td>

                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
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

<script type="text/javascript" src="/public/static/js/data.js"></script>
