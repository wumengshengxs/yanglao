<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:101:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/work/quality_result.html";i:1551665224;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
                            <form class="form-horizontal" action="/index/Work/qualityResult" method="get">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">创建时间</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="start_create" value="<?php echo $param['start_create']; ?>" class="form-control times">
                                    </div>
                                    <div class="col-sm-1 middle-div">至</div>
                                    <div class="col-sm-4 f-right">
                                        <input type="text" name="end_create" value="<?php echo $param['end_create']; ?>" class="form-control times">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">服务对象</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="client" value="<?php echo $param['client']; ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">质检结果</label>
                                    <div class="radio radio-success radio-inline">
                                        <input type="radio" id="state1" value="1" name="state" <?php if($param['state'] && $param['state'] == 1): ?> checked <?php endif; ?>>
                                        <label for="state1"> 通过 </label>
                                    </div>
                                    <div class="radio radio-success radio-inline">
                                        <input type="radio" id="state2" value="2" name="state" <?php if($param['state'] && $param['state'] == 2): ?> checked <?php endif; ?>>
                                        <label for="state2"> 退回 </label>
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
                    &nbsp;&nbsp;
                    <?php if(!(empty($item_value) || (($item_value instanceof \think\Collection || $item_value instanceof \think\Paginator ) && $item_value->isEmpty()))): if(is_array($item_value) || $item_value instanceof \think\Collection || $item_value instanceof \think\Paginator): $i = 0; $__LIST__ = $item_value;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$iv): $mod = ($i % 2 );++$i;?>
                    <span style="font-weight: lighter;"><?php echo $iv['item']; ?>：<?php echo $iv['value']; ?>&nbsp;<a href="javascript:;">X</a>&nbsp;&nbsp;</span>
                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>工单编号</th>
                            <th>创建时间</th>
                            <th>办结时间</th>
                            <th>质检时间</th>
                            <th>受理时长</th>
                            <th>服务对象</th>
                            <th>话务员</th>
                            <th>工单类型</th>
                            <th>质检得分</th>
                            <th>质检结果</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($records['data']) || $records['data'] instanceof \think\Collection || $records['data'] instanceof \think\Paginator): $k = 0; $__LIST__ = $records['data'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                        <tr>
                            <td><?php echo $k; ?></td>
                            <td><?php echo $vo['w_id']; ?></td>
                            <td><?php echo $vo['create_time']; ?></td>
                            <td><?php echo $vo['finish_time']; ?></td>
                            <td><?php echo $vo['quality_time']; ?></td>
                            <td><?php echo $vo['handle_time']; ?></td>
                            <td><?php echo $vo['clientName']; ?></td>
                            <td><?php echo $vo['staffUser']; ?></td>
                            <td><?php echo $vo['work_type']; ?></td>
                            <td><?php echo $vo['quality_score']; ?></td>
                            <td><?php echo $vo['type']; ?></td>
                            <td>
                                <a href="/index/Work/workDetails?id=<?php echo $vo['w_id']; ?>&action=quality">工单质检</a>
                            </td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                    <div class="page"><?php echo $page; ?></div>
                    <div class="text-center">总共 <?php echo $records['total']; ?> 条数据</div>
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

<script type="text/javascript" src="/public/static/js/work/work.js"></script>
<script type="text/javascript">
    var $search = '<?php echo json_encode($item_value); ?>';
</script>
</html>