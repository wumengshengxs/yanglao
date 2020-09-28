<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:97:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/works/plan_info.html";i:1550452645;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1548228499;}*/ ?>
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
<div class="row">
    <div class="col-sm-12">
        <div class="wrapper wrapper-content animated fadeInUp">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>
                        <a href="<?php echo url('/index/works/plan'); ?>">
                            计划任务列表&nbsp;&nbsp;<i class='fa fa-angle-double-right'></i>
                        </a>
                        <label>&nbsp;查看计划任务</label>
                    </h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="m-b-md">
                                <h2><b>计划任务详情</b></h2>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <dl class="dl-horizontal">
                                <dt>计划任务名称:</dt>
                                <dd><?php echo $plan['name']; ?></dd>
                                <dt>覆盖服务对象:</dt>
                                <dd><?php echo $plan['num']; ?>人</dd>
                                <dt>计划状态:</dt>
                                <dd>
                                    <?php if($plan['state'] == 1): ?>
                                    启用
                                    <?php else: ?>
                                    草稿
                                    <?php endif; ?>
                                </dd>
                            </dl>
                        </div>
                        <div class="col-sm-3" id="cluster_info">
                            <dl class="dl-horizontal">

                                <dt>开始时间:</dt>
                                <dd><?php echo $plan['start_time']; ?></dd>
                                <dt>结束时间:</dt>
                                <dd><?php echo $plan['end_time']; ?></dd>
                            </dl>
                        </div>
                    </div>
                    <div class="row m-t-sm">
                        <div class="col-sm-12">
                            <div class="panel blank-panel">
                                <div class="panel-heading">
                                    <div class="panel-options">
                                        <ul class="nav nav-tabs">
                                            <h5>任务配详情</h5>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="bs-glyphicons">
                                        <ul class="bs-glyphicons-list">
                                            <a href="/index/works/planinfo?id=<?php echo $plan['id']; ?>" style="color:#444;text-decoration:none;">
                                                <li>
                                                    <h3 >查看全部</h3>
                                                    <span class="glyphicon-class">-----</span>
                                                    <h4>覆盖对象:<?php echo $plan['num']; ?>人 </h4>
                                                </li>
                                            </a>
                                            <?php if(is_array($staff) || $staff instanceof \think\Collection || $staff instanceof \think\Paginator): $i = 0; $__LIST__ = $staff;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vos): $mod = ($i % 2 );++$i;?>
                                            <a href="/index/works/planinfo?id=<?php echo $plan['id']; ?>&sid=<?php echo $vos['number']; ?>" style="color:#444;text-decoration:none;">
                                                <li>
                                                    <h3 ><?php echo $vos['display_name']; ?></h3>
                                                    <span class="glyphicon-class"><?php echo $vos['work_number']; ?></span>
                                                    <h4>覆盖对象:<?php echo $vos['num']; ?>人 </h4>
                                                </li>
                                            </a>
                                            <?php endforeach; endif; else: echo "" ;endif; ?>

                                        </ul>
                                    </div>
                                </div>

                                <div class="panel-body">
                                    <div class="table-responsive">

                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>编号</th>
                                                <th>计划创建时间</th>
                                                <th>计划开始时间</th>
                                                <th>计划截止时间</th>
                                                <th>计划完成时间</th>
                                                <th>服务对象</th>
                                                <th>计划状态</th>
                                                <th>所属话务员</th>
                                                <th>操作</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(is_array($work) || $work instanceof \think\Collection || $work instanceof \think\Paginator): $i = 0; $__LIST__ = $work;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                            <tr>
                                                <td><?php echo $vo['id']; ?></td>
                                                <td><?php echo $vo['create_time']; ?></td>
                                                <td><?php echo date('Y-m-d H:i:s',$vo['start']); ?></td>
                                                <td><?php echo date('Y-m-d H:i:s',$vo['end']); ?></td>
                                                <td>
                                                    <?php if($vo['end_time'] != 0): ?>
                                                    <?php echo date('Y-m-d H:i:s',$vo['end_time']); else: ?>
                                                    ------
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $vo['uname']; ?></td>
                                                <td>
                                                    <?php if($vo['type'] == 0): ?>
                                                    未开始
                                                    <?php elseif($vo['type'] == 1): ?>
                                                    进行中
                                                    <?php elseif($vo['type'] == 2): ?>
                                                    按期完成
                                                    <?php elseif($vo['type'] == 3): ?>
                                                    延期完成
                                                    <?php elseif($vo['type'] == 4): ?>
                                                    已逾期
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $vo['sname']; ?></td>
                                                <td>
                                                    <?php if($plan['state'] == 1): ?>
                                                        <a href="/index/works/workInfo?id=<?php echo $vo['id']; ?>"  class="btn btn-success btn-xs">查看</a>
                                                    <?php else: ?>
                                                          查看
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; endif; else: echo "" ;endif; ?>
                                            </tbody>
                                        </table>
                                        <!-- 分页 -->
                                        <div class="fixed-table-pagination" style="display: block;">
                                            <div class="pull-left pagination-detail">
                                                <span class="pagination-info">总共<?php echo $work->total(); ?>条记录</span>
                                            </div>
                                            <div class="pull-right pagination">
                                                <?php echo $all['page']; ?>
                                            </div>
                                        </div>
                                        <!-- 分页 -->

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
