<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:95:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/warning/index.html";i:1553561087;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
                                <form class="form-horizontal" action="javascript:;search()" id="search_query">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">创建时间</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="start_create"  class="form-control times" value="<?php echo $data['query']['start_create']; ?>"/>
                                        </div>
                                        <div class="col-sm-1 middle-div">至</div>
                                        <div class="col-sm-4 f-right">
                                            <input type="text" name="end_create"   class="form-control times" value="<?php echo $data['query']['end_create']; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">服务对象</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="name" value="<?php echo $data['query']['name']; ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">身份证</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="cardid" value="<?php echo $data['query']['cardid']; ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">腕表imei</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="imei" value="<?php echo $data['query']['imei']; ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary" type="submit">搜索</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    &nbsp;&nbsp;
                    <?php if(!(empty($data['search']) || (($data['search'] instanceof \think\Collection || $data['search'] instanceof \think\Paginator ) && $data['search']->isEmpty()))): if(is_array($data['search']) || $data['search'] instanceof \think\Collection || $data['search'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['search'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$iv): $mod = ($i % 2 );++$i;?>
                        <span style="font-weight: lighter;"><?php echo $iv['k']; ?>：<?php echo $iv['v']; ?>&nbsp;
                            <a href="javascript:;">X</a>&nbsp;&nbsp;</span>
                        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>创建时间</th>
                            <th>发起人</th>
                            <?php if($data['work_type'] != heart): ?>
                            <th>位置</th>
                            <?php else: ?>
                            <th>内容</th>
                            <?php endif; ?>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($data['show']['data']) || $data['show']['data'] instanceof \think\Collection || $data['show']['data'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['show']['data'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$res): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo date('Y-m-d H:i:s',$res['create_time']); ?></td>
                            <td><?php echo $res['name']; ?></td>
                            <?php if($data['work_type'] != heart): ?>
                            <td><?php echo $res['address']; ?></td>
                            <?php else: ?>
                            <td><?php echo (isset($res['content']) && ($res['content'] !== '')?$res['content']:'--'); ?></td>
                            <?php endif; ?>
                            <td>
                                
                                <a href="/index/Work/workDetails?id=<?php echo $res['id']; ?>" class="btn btn-success btn-xs">处理工单</a>
                                <?php if($data['work_type'] != heart): ?>
                                <a  class="btn btn-success btn-xs" onclick="seemap(<?php echo $res['id']; ?>)">查看位置</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                    <div class="page"><?php echo $data['page']; ?><div><?php echo $data['show']['last_page']; ?>页，总共<?php echo $data['show']['total']; ?>条数据</div></div>
                    </div>
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

<script type="text/javascript" src="/public/static/js/warning.js"></script>
<script type="text/javascript">
    var type = "<?php echo $data['work_type']; ?>";
    var $search = '<?php echo json_encode($data["query"]); ?>';
</script>
</html>