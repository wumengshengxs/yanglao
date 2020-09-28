<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:105:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/work/plan_group_details.html";i:1551665224;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
<link rel="stylesheet" href="/public/static/css/work.css">
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>
                        <small>计划任务&nbsp;>&nbsp;任务详情</small>
                        <a href="javascript:history.back(-1);" title="返回上一页"><i class="fa fa-reply"></i></a>
                    </h5>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>任务详情</h5>
                    <div class="ibox-tools">
                        <?php if($details['state'] == 2): ?>
                        <button class="btn btn-white btn-xs" data-class="enable-plan-group">启用</button>
                        <?php endif; ?>
                        <button class="btn btn-white btn-xs" data-class="edit-plan-group">编辑</button>
                        <?php if($details['state'] == 2): ?>
                        <button class="btn btn-white btn-xs" data-class="del-plan-group">删除</button>
                        <?php endif; ?>
                        <!--<button class="btn btn-white btn-xs">复制计划任务</button>-->
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="ibox-content">
                    <div class="col-sm-8">
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>计划组名称</stront></label>
                            <span class="col-sm-8"><?php echo $details['name']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>开始时间</stront></label>
                            <span class="col-sm-8"><?php echo $details['start_time']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>覆盖服务对象</stront></label>
                            <span class="col-sm-8"><?php echo $details['quantity']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>截止时间</stront></label>
                            <span class="col-sm-8"><?php echo $details['end_time']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>计划状态</stront></label>
                            <span class="col-sm-8"><?php if($details['state'] == 1): ?> 启用 <?php else: ?> 草稿 <?php endif; ?></span>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>任务分配情况</h5>
                </div>
                <div class="ibox-content">
                    <div class="col-sm-12 staff">
                        <div class="col-sm-1 active">
                            <p class="m-b-sm m-t-sm">全部工单</p>
                            <p class="m-b-sm">--</p>
                            <hr/>
                            <p class="m-b-sm m-t-sm"><?php echo $details['quantity']; ?>人</p>
                            <p class="m-b-sm">100%</p>
                        </div>
                        <?php if(is_array($details['staff']) || $details['staff'] instanceof \think\Collection || $details['staff'] instanceof \think\Paginator): $i = 0; $__LIST__ = $details['staff'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$s): $mod = ($i % 2 );++$i;?>
                        <div class="col-sm-1 staff">
                            <p class="m-b-sm m-t-sm"><?php echo $s['display_name']; ?></p>
                            <p class="m-b-sm"><?php echo $s['work_number']; ?></p>
                            <hr/>
                            <p class="m-b-sm m-t-sm"><?php echo $s['quantity']; ?>人</p>
                            <p class="m-b-sm"><?php echo $s['percent']; ?>%</p>
                        </div>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                    <div class="col-sm-12 m-t-lg">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>创建时间</th>
                                    <th>开始时间</th>
                                    <th>截止时间</th>
                                    <th>完成时间</th>
                                    <th>服务对象</th>
                                    <th>计划状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <div class="text-center">
                                <ul class="pagination"></ul>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
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

<script type="text/javascript" src="/public/static/js/bootstrap-paginator.js"></script>
<script type="text/javascript" src="/public/static/js/work/plan.js"></script>
<script type="text/javascript">
    var $plan_id = '<?php echo $_GET["id"]; ?>',
        $details = '<?php echo json_encode($details); ?>';
    planGroupWork({'plan_id':$plan_id});
</script>
</html>