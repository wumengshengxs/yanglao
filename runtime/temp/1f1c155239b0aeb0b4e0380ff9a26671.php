<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:104:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/integration/accumulate.html";i:1551665224;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
                    <div class="btn-group search">
                        <button class="btn btn-white" type="button"><i class="fa fa-search"></i> 高级搜索</button>
                        <div class="dropdown-menu">
                            <form class="form-horizontal" action="/index/Integration/accumulate" method="get">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">服务对象姓名</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" value="<?php echo $param['name']; ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">年龄</label>
                                    <div class="col-sm-9">
                                        <div class="input-daterange input-group">
                                            <input type="number" class="input-sm form-control " name="start" id="start" value="<?php echo $param['start_age']; ?>" />
                                            <span class="input-group-addon">至</span>
                                            <input type="number" class="input-sm form-control " name="end" id="end" value="<?php echo $param['end_age']; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">性别</label>
                                    <div class="col-sm-9">
                                        <div class="radio radio-success radio-inline">
                                            <input type="radio" id="sex1" value="1" name="sex" <?php if($param['sex'] == 1): ?> checked <?php endif; ?>>
                                            <label for="sex1"> 男 </label>
                                        </div>
                                        <div class="radio radio-success radio-inline">
                                            <input type="radio" id="sex2" value="2" name="sex"  <?php if($param['sex'] == 2): ?> checked <?php endif; ?>>
                                            <label for="sex2"> 女 </label>
                                        </div>
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
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>老人姓名</th>
                            <th>性别</th>
                            <th>年龄</th>
                            <th>本次累积积分</th>
                            <th>累积备注</th>
                            <th>本次累积时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($client) || $client instanceof \think\Collection || $client instanceof \think\Paginator): $k = 0; $__LIST__ = $client;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                        <tr>
                            <td><?php echo $k; ?></td>
                            <td><?php echo $vo['name']; ?></td>
                            <td>
                                <?php if($vo['sex'] == 1): ?>
                                男
                                <?php elseif($vo['sex'] == 2): ?>
                                女
                                <?php endif; ?>
                            </td>
                            <td><?php echo $vo['age']; ?></td>
                            <td><?php echo $vo['score']; ?></td>
                            <td><?php echo $vo['remarks']; ?></td>
                            <td><?php echo date('Y-m-d',$vo['create_time']); ?></td>
                            <td>
                                <a href="/index/Client/integral?id=<?php echo $vo['id']; ?>&type=1">查看</a>
                            </td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                    <div class="page"><?php echo $client->render(); ?></div>
                    <div class="text-center">总共 <?php echo $client->total(); ?> 条数据</div>
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

<script src="/public/static/js/integral/integral.js"></script>
</body>
</html>