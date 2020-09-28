<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:92:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/works/plan.html";i:1550642015;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1548228499;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1548228499;}*/ ?>
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
                <div class="ibox-title">
                    <h5>
                        <a class="btn btn-white btn-sm" href="<?php echo url('/index/works/addPlan'); ?>" ><i class="fa fa-plus"></i> 创建计划任务</a>
                        <div class="btn-group search">
                            <button class="btn btn-white btn-sm" type="button" ><i class="fa fa-search"></i> 高级搜索</button>
                            <div class="dropdown-menu">
                                <form class="form-horizontal" id="signupForm" action="javascript:search_query()">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">范围选择</label>
                                            <div class="col-sm-9">
                                                <div class="input-daterange input-group">
                                                    <input type="text" class="input-sm form-control times" name="start" id="start" placeholder="起始时间" readonly />
                                                    <span class="input-group-addon">到</span>
                                                    <input type="text" class="input-sm form-control times" name="end" id="end"  placeholder="结束时间" readonly  />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">工单类型</label>
                                            <div class="col-sm-9">
                                            <select class="form-control selectpicker" name="state">
                                                <option value='1'>启用</option>
                                                <option value='2'>草稿</option>
                                            </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">计划工单名称</label>
                                            <div class="col-sm-9">
                                            <input type="text" placeholder="计划工单名称" class="form-control" name="name"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <div class="col-sm-3 col-sm-offset-2">
                                            <button class="btn btn-primary" type="submit">搜索</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        &nbsp;&nbsp;
                        <?php if(!(empty($item_value) || (($item_value instanceof \think\Collection || $item_value instanceof \think\Paginator ) && $item_value->isEmpty()))): if(is_array($item_value) || $item_value instanceof \think\Collection || $item_value instanceof \think\Paginator): $i = 0; $__LIST__ = $item_value;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$iv): $mod = ($i % 2 );++$i;?>
                        <span style="font-weight: lighter;"><?php echo $iv['item']; ?>：<?php echo $iv['value']; ?>&nbsp;<a href="javascript:;">X</a>&nbsp;&nbsp;</span>
                        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                    </h5>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>计划组名称</th>
                                    <th>创建时间</th>
                                    <th>开始时间</th>
                                    <th>截止时间</th>
                                    <th>覆盖对象数量</th>
                                    <th>是否启用</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(is_array($plan['data']) || $plan['data'] instanceof \think\Collection || $plan['data'] instanceof \think\Paginator): $k = 0; $__LIST__ = $plan['data'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                                <tr>
                                    <td><?php echo $k; ?></td>
                                    <td><?php echo $vo['name']; ?></td>
                                    <td><?php echo date('Y-m-d H:i:s',$vo['create_time']); ?></td>
                                    <td><?php echo date('Y-m-d H:i:s',$vo['start_time']); ?></td>
                                    <td><?php echo date('Y-m-d H:i:s',$vo['end_time']); ?></td>
                                    <td><?php echo $vo['quantity']; ?></td>
                                    <td>
                                        <?php if($vo['state'] == 1): ?>
                                        启用
                                        <?php else: ?>
                                        草稿
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="/index/works/planInfo?id=<?php echo $vo['id']; ?>">查看</a>&nbsp;&nbsp;
                                        <a href="javascript:;">编辑</a>&nbsp;&nbsp;
                                        <?php if($vo['state'] == 2): ?>
                                        <a href="javascript:;">启用</a>&nbsp;&nbsp;
                                        <?php endif; ?>
                                        <a href="javascript:;">延期</a>&nbsp;&nbsp;
                                        <a href="javascript:;">删除</a>
                                    </td>
                                </tr>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                                </tbody>
                            </table>
                            <div class="page"><?php echo $page; ?></div>
                            <div class="text-center">总共 <?php echo $plan['total']; ?> 条数据</div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
<!--<div class="modal inmodal" id="myModal5" tabindex="-1" role="dialog"  aria-hidden="true">-->
    <!--<div class="modal-dialog">-->
        <!--<div class="modal-content animated fadeIn">-->
            <!--<div class="modal-header">-->
                <!--<h4 >编辑计划任务</h4>-->
            <!--</div>-->
            <!--<div class="modal-body">-->
                <!--<form class="form-horizontal m-t" id="forms2">-->
                    <!--<div class="form-group">-->
                        <!--<label class="col-sm-3 control-label">计划任务名称：</label>-->
                        <!--<div class="col-sm-8">-->
                            <!--<input id="name" name="name" class="form-control" type="text" maxlength="16">-->
                        <!--</div>-->
                    <!--</div>-->
                    <!--<div class="form-group">-->
                        <!--<label class="col-sm-3 control-label">范围选择: </label>-->
                        <!--<div class="col-md-8">-->
                            <!--<div class="input-group">-->
                                <!--<input type="text" class="form-control times" name="start_time" id="start_time" readonly   placeholder="起始时间"/>-->
                                <!--<span class="input-group-addon">到</span>-->
                                <!--<input type="text" class="form-control times" name="end_time"  id="end_time" readonly placeholder="结束时间"/>-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</div>-->
                    <!--<input type="hidden" id="ids"  name="id" value="">-->
                <!--</form>-->
            <!--</div>-->
            <!--<div class="modal-footer">-->
                <!--<center>-->
                    <!--<button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>-->
                    <!--<button type="button" class="btn btn-primary" onclick="saveSublimt()">保存</button>-->
                <!--</center>-->
            <!--</div>-->
        <!--</div>-->
    <!--</div>-->
<!--</div>-->
<!--<div class="modal inmodal" id="myModal6" tabindex="-1" role="dialog"  aria-hidden="true">-->
    <!--<div class="modal-dialog">-->
        <!--<div class="modal-content animated fadeIn">-->
            <!--<div class="modal-header">-->
                <!--<h4 >延期</h4>-->
            <!--</div>-->
            <!--<div class="modal-body">-->
                <!--<form class="form-horizontal m-t" id="forms3">-->
                    <!--<div class="form-group">-->
                        <!--<label class="col-sm-3 control-label">选择时间: </label>-->
                        <!--<div class="col-md-8">-->
                            <!--<div class="input-group">-->
                                <!--<span class="input-group-addon">延期至</span>-->
                                <!--<input type="text" class="form-control times" name="end_time"  id="y_end_time" readonly placeholder="结束时间"/>-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</div>-->
                    <!--<input type="hidden" id="y_ids"  name="id" value="">-->
                <!--</form>-->
            <!--</div>-->
            <!--<div class="modal-footer">-->
                <!--<center>-->
                    <!--<button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>-->
                    <!--<button type="button" class="btn btn-primary" onclick="postpone()">保存</button>-->
                <!--</center>-->
            <!--</div>-->
        <!--</div>-->
    <!--</div>-->
<!--</div>-->
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

<script src="/public/static/js/work/plan.js"></script>
<script type="text/javascript"></script>
</html>