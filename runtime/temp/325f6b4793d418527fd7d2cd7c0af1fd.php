<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:101:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/client/healthy_base.html";i:1547789743;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
                <small>服务对象管理&nbsp;>&nbsp;健康档案&nbsp;>&nbsp;基础档案</small>&nbsp;
                <a href="javascript:history.back(-1);" title="返回上一页"><i class="fa fa-reply"></i></a>
            </h5>
        </div>
        <div class="ibox-content c-healthy-base">
            <div class="col-sm-12 nav-tabs-client">
                <div class="col-sm-4">
                    <img src="<?php echo (isset($user['head']) && ($user['head'] !== '')?$user['head']:'/public/static/img/head.jpg'); ?>" alt="">&nbsp;&nbsp;
                    <span><?php echo $user['name']; ?></span>
                </div>
                <div class="col-sm-8">
                    <ul class="nav nav-tabs"></ul>
                </div>
            </div>
            <div class="col-sm-12">
                <ul class="nav nav-tabs nav-tabs-healthy"></ul>
            </div>
            <div class="col-sm-12 m-t">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">身高</label>
                        <div class="col-sm-6">
                            <span class="help-block"><?php echo (isset($base['height']) && ($base['height'] !== '')?$base['height']:0); ?>厘米</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">体重</label>
                        <div class="col-sm-6">
                            <span class="help-block"><?php echo (isset($base['weight']) && ($base['weight'] !== '')?$base['weight']:0); ?>公斤</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">视力情况</label>
                        <div class="col-sm-6">
                            <span class="help-block"><?php echo (isset($base['vision']) && ($base['vision'] !== '')?$base['vision']:'--'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">听力情况</label>
                        <div class="col-sm-6">
                            <span class="help-block"><?php echo (isset($base['hearing']) && ($base['hearing'] !== '')?$base['hearing']:'--'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">睡眠</label>
                        <div class="col-sm-6">
                            <span class="help-block"><?php echo (isset($base['sleep_text']) && ($base['sleep_text'] !== '')?$base['sleep_text']:'--'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">是否吸烟</label>
                        <div class="col-sm-6">
                            <span class="help-block"><?php echo (isset($base['smoke_text']) && ($base['smoke_text'] !== '')?$base['smoke_text']:'--'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">平均每天吸烟量</label>
                        <div class="col-sm-6">
                            <span class="help-block"><?php echo (isset($base['smoke_frequency']) && ($base['smoke_frequency'] !== '')?$base['smoke_frequency']:'--'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">是否饮酒</label>
                        <div class="col-sm-6">
                            <span class="help-block"><?php echo (isset($base['drink_text']) && ($base['drink_text'] !== '')?$base['drink_text']:'--'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">锻炼次数</label>
                        <div class="col-sm-6">
                            <span class="help-block"><?php echo (isset($base['exercise_frequency']) && ($base['exercise_frequency'] !== '')?$base['exercise_frequency']:'--'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">锻炼时间</label>
                        <div class="col-sm-6">
                            <span class="help-block"><?php echo (isset($base['exercise_duration_text']) && ($base['exercise_duration_text'] !== '')?$base['exercise_duration_text']:'--'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">锻炼方式</label>
                        <div class="col-sm-6">
                            <span class="help-block"><?php echo (isset($base['exercise_type_text']) && ($base['exercise_type_text'] !== '')?$base['exercise_type_text']:'--'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">服用保健品说明</label>
                        <div class="col-sm-6">
                            <span class="help-block"><?php echo (isset($base['healthy_products']) && ($base['healthy_products'] !== '')?$base['healthy_products']:'--'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">医疗支付方式</label>
                        <div class="col-sm-6">
                            <span class="help-block"><?php echo (isset($base['medical_payment_text']) && ($base['medical_payment_text'] !== '')?$base['medical_payment_text']:'--'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">社交活动</label>
                        <div class="col-sm-6">
                            <span class="help-block"><?php echo (isset($base['social_activity_text']) && ($base['social_activity_text'] !== '')?$base['social_activity_text']:'--'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">备注</label>
                        <div class="col-sm-6">
                            <span class="help-block"><?php echo (isset($base['remarks']) && ($base['remarks'] !== '')?$base['remarks']:'--'); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-primary active">编辑</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="clearfix"></div>
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
    var $userId = '<?php echo $userId; ?>',
        $healthyBase = '<?php echo json_encode($base); ?>';
</script>
</html>