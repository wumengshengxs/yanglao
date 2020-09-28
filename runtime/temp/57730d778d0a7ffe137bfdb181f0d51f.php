<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:92:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/work/works.html";i:1551665224;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
                <div class="ibox-content text-center">
                    <div class="col-sm-2" style="width: 20% !important;">
                        <p>未受理</p>
                        <h2><?php echo (isset($statistics['noaccepted']) && ($statistics['noaccepted'] !== '')?$statistics['noaccepted']:0); ?></h2>
                    </div>
                    <div class="col-sm-2" style="width: 20% !important;">
                        <p class="">受理中</p>
                        <h2><?php echo (isset($statistics['accepted']) && ($statistics['accepted'] !== '')?$statistics['accepted']:0); ?></h2>
                    </div>
                    <div class="col-sm-2" style="width: 20% !important;">
                        <p>已办结</p>
                        <h2><?php echo (isset($statistics['completed']) && ($statistics['completed'] !== '')?$statistics['completed']:0); ?></h2>
                    </div>
                    <div class="col-sm-2" style="width: 20% !important;">
                        <p>已关闭</p>
                        <h2><?php echo (isset($statistics['closed']) && ($statistics['closed'] !== '')?$statistics['closed']:0); ?></h2>
                    </div>
                    <div class="col-sm-2" style="width: 20% !important;">
                        <p>计划工单</p>
                        <h2><?php echo (isset($statistics['plan']) && ($statistics['plan'] !== '')?$statistics['plan']:0); ?></h2>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <button class="btn btn-white pull-right outbound-call">创建主动外呼</button>
                    <div class="btn-group search">
                        <button class="btn btn-white" type="button"><i class="fa fa-search"></i> 高级搜索</button>
                        <div class="dropdown-menu">
                            <form class="form-horizontal" action="/index/Work/works" method="get">
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
                                    <label class="col-sm-3 control-label">工单状态</label>
                                    <div class="col-sm-9">
                                        <?php if(is_array($work_state) || $work_state instanceof \think\Collection || $work_state instanceof \think\Paginator): $i = 0; $__LIST__ = $work_state;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$s): $mod = ($i % 2 );++$i;?>
                                        <div class="radio radio-success radio-inline">
                                            <input type="radio" id="state<?php echo $key; ?>" value="<?php echo $key; ?>" name="work_state" <?php if($param['work_state'] == $key): ?> checked <?php endif; ?>>
                                            <label for="state<?php echo $key; ?>"> <?php echo $s; ?> </label>
                                        </div>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">工单类型</label>
                                    <div class="col-sm-9">
                                        <?php if(is_array($work_type) || $work_type instanceof \think\Collection || $work_type instanceof \think\Paginator): $i = 0; $__LIST__ = $work_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$t): $mod = ($i % 2 );++$i;?>
                                        <div class="radio radio-success radio-inline">
                                            <input type="radio" id="work_type<?php echo $key; ?>" value="<?php echo $key; ?>" name="work_type" <?php if($param['work_type'] && $param['work_type'] == $key): ?> checked <?php endif; ?>>
                                            <label for="work_type<?php echo $key; ?>"> <?php echo $t; ?> </label>
                                        </div>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
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
                            <th>标题</th>
                            <th>创建时间</th>
                            <th>服务对象</th>
                            <th>话务员</th>
                            <th>工单类型</th>
                            <th>工单状态</th>
                            <th>工单办结原因</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($work['data']) || $work['data'] instanceof \think\Collection || $work['data'] instanceof \think\Paginator): $k = 0; $__LIST__ = $work['data'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                        <tr>
                            <td><?php echo $k; ?></td>
                            <td><?php echo (isset($vo['title']) && ($vo['title'] !== '')?$vo['title']:'--'); ?></td>
                            <td><?php echo date('Y-m-d H:i:s',$vo['create_time']); ?></td>
                            <td><?php echo $vo['userName']; ?></td>
                            <td><?php echo (isset($vo['display_name']) && ($vo['display_name'] !== '')?$vo['display_name']:'--'); ?></td>
                            <td><?php echo $vo['type']; ?></td>
                            <td><?php echo $vo['state']; ?></td>
                            <td><?php echo $vo['call_result']; ?></td>
                            <td>
                                <a href="/index/Work/workDetails?id=<?php echo $vo['id']; ?>">查看</a>
                                <?php if($vo['state'] == '未受理'): ?>
                                &nbsp;&nbsp;
                                <a href="javascript:;" data-class="accept-work">受理</a>
                                <?php endif; if(in_array($vo['state'],['受理中'])): ?>
                                &nbsp;&nbsp;
                                <a href="/index/Work/workDetails?id=<?php echo $vo['id']; ?>">继续受理</a>
                                <?php endif; if(in_array($vo['state'],['未受理','受理中'])): ?>
                                &nbsp;&nbsp;
                                <a href="javascript:;" data-class="transfer-work">转交</a>
                                &nbsp;&nbsp;
                                <a href="javascript:;" data-class="close-work">关闭</a>
                                <?php endif; if(in_array($vo['state'],['已办结','已关闭'])): ?>
                                &nbsp;&nbsp;
                                <a href="javascript:;" data-class="open-work">重新打开</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                    <div class="page"><?php echo $page; ?></div>
                    <div class="text-center">总共 <?php echo $work['total']; ?> 条数据</div>
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

<script type="text/javascript" src="/public/static/js/bootstrap-paginator.js"></script>
<script type="text/javascript" src="/public/static/js/work/work.js"></script>
<script type="text/javascript">
    var $search = '<?php echo json_encode($item_value); ?>',
        $works = '<?php echo json_encode($work["data"]); ?>';
</script>
</html>