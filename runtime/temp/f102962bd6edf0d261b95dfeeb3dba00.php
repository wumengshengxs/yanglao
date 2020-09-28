<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:97:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/client/integral.html";i:1551665224;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
<link rel="stylesheet" href="/public/static/css/client.css">
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>
                <small>服务对象管理&nbsp;>&nbsp;积分管理</small>
                <a href="javascript:history.back(-1);" title="返回上一页"><i class="fa fa-reply"></i></a>
            </h5>
        </div>
        <div class="ibox-content c-case-record">
            <div class="col-sm-12 nav-tabs-client">
                <div class="col-sm-4">
                    <img src="<?php echo (isset($user['head']) && ($user['head'] !== '')?$user['head']:'/public/static/img/head.jpg'); ?>" alt="">&nbsp;&nbsp;
                    <span><?php echo $user['name']; ?></span>
                </div>
                <div class="col-sm-8">
                    <ul class="nav nav-tabs"></ul>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 m-t">
                    <div class="float-e-margins">
                        <div class="ibox-content text-center no-borders">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-2">
                                <p>当前积分</p>
                                <h2><?php echo $clientIntegral['integral']; ?></h2>
                            </div>
                            <div class="col-sm-2">
                                <p>累计获取积分</p>
                                <h2><?php echo (isset($clientIntegralRecords['accumulate']['score']) && ($clientIntegralRecords['accumulate']['score'] !== '')?$clientIntegralRecords['accumulate']['score']:0); ?></h2>
                            </div>
                            <div class="col-sm-2">
                                <p>累计核销积分</p>
                                <h2><?php echo (isset($clientIntegralRecords['destory']['score']) && ($clientIntegralRecords['destory']['score'] !== '')?$clientIntegralRecords['destory']['score']:0); ?></h2>
                            </div>
                            <div class="col-sm-2">
                                <p>累计获取次数</p>
                                <h2><?php echo (isset($clientIntegralRecords['accumulate']['total']) && ($clientIntegralRecords['accumulate']['total'] !== '')?$clientIntegralRecords['accumulate']['total']:0); ?></h2>
                            </div>
                            <div class="col-sm-2">
                                <p>累计核销次数</p>
                                <h2><?php echo (isset($clientIntegralRecords['destory']['total']) && ($clientIntegralRecords['destory']['total'] !== '')?$clientIntegralRecords['destory']['total']:0); ?></h2>
                            </div>
                            <div class="col-sm-1"></div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-12">
                        <ul class="nav nav-tabs nav-tabs-integral"></ul>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>时间</th>
                                    <th>积分变化情况</th>
                                    <th>积分变化原因</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(is_array($records) || $records instanceof \think\Collection || $records instanceof \think\Paginator): $k = 0; $__LIST__ = $records;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$r): $mod = ($k % 2 );++$k;?>
                                <tr>
                                    <td><?php echo $k; ?></td>
                                    <td><?php echo date('Y-m-d H:i:s',$r['create_time']); ?></td>
                                    <td>
                                        <?php if($r['type'] == 1): ?>
                                            <span style="color: #2E8B57;">+ <?php echo $r['score']; ?></span>
                                        <?php else: ?>
                                        <span style="color: #ec6149;">- <?php echo $r['score']; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo (isset($r['remarks']) && ($r['remarks'] !== '')?$r['remarks']:'--'); ?></td>
                                </tr>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                                </tbody>
                            </table>
                            <div class="page"><?php echo $records->render(); ?></div>
                            <div class="text-center">总共 <?php echo $records->total(); ?> 条数据</div>
                        </div>
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

<script type="text/javascript" src="/public/static/js/client.js"></script>
<script type="text/javascript">
    var $userId = '<?php echo $_GET["id"]; ?>';
</script>
</html>