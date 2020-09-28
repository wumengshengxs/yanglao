<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:98:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/provider/details.html";i:1556157958;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
                <small>服务商管理&nbsp;>&nbsp;基础信息</small>
                <a href="javascript:history.back(-1);" title="返回上一页"><i class="fa fa-reply"></i></a>
            </h5>
        </div>
        <div class="ibox-content p-b-info">
            <div class="col-sm-12 nav-tabs-client">
                <div class="col-sm-4">
                    <img src="<?php echo (isset($user['head']) && ($user['head'] !== '')?$user['head']:'/public/static/img/head.jpg'); ?>" alt="">&nbsp;&nbsp;
                    <span><?php echo $details['company']; ?></span>
                </div>
                <div class="col-sm-8">
                    <ul class="nav nav-tabs"></ul>
                </div>
            </div>
            <div class="col-sm-12 m-t">
                <form class="form-horizontal">
                    <div class="col-sm-12">
                        <div class="col-sm-6 f-left">
                            <h3>基础信息</h3>
                        </div>
                        <div class="col-sm-6 f-right text-right">
                            <button type="button" class="btn btn-sm btn-white editBtn">编辑</button>
                        </div>
                        <div class="clearfix"></div>
                        <hr/>
                    </div>
                    <div class="row m-t col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">法人代表</label>
                            <div class="col-sm-5">
                                <span class="help-block m-b-none"><?php echo (isset($details['legal_person']) && ($details['legal_person'] !== '')?$details['legal_person']:'--'); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">法人联系方式</label>
                            <div class="col-sm-5">
                                <span class="help-block m-b-none"><?php echo (isset($details['legal_mobile']) && ($details['legal_mobile'] !== '')?$details['legal_mobile']:'--'); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">注册资金</label>
                            <div class="col-sm-5">
                                <span class="help-block m-b-none"><?php echo (isset($details['registered_capital']) && ($details['registered_capital'] !== '')?$details['registered_capital']:'--'); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">加盟时间</label>
                            <div class="col-sm-5">
                                <span class="help-block m-b-none times"><?php echo (isset($details['join_time']) && ($details['join_time'] !== '')?$details['join_time']:'--'); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">截止有效期</label>
                            <div class="col-sm-5">
                                <span class="help-block m-b-none times"><?php echo (isset($details['expiry_time']) && ($details['expiry_time'] !== '')?$details['expiry_time']:'--'); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">组织机构编号</label>
                            <div class="col-sm-5">
                                <span class="help-block m-b-none"><?php echo (isset($details['org_code']) && ($details['org_code'] !== '')?$details['org_code']:'--'); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">组织机构分类</label>
                            <div class="col-sm-5">
                                <span class="help-block m-b-none"><?php echo (isset($details['org_type']) && ($details['org_type'] !== '')?$details['org_type']:'--'); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">税务编号</label>
                            <div class="col-sm-5">
                                <span class="help-block m-b-none"><?php echo (isset($details['tax_number']) && ($details['tax_number'] !== '')?$details['tax_number']:'--'); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">卫生许可证编号</label>
                            <div class="col-sm-5">
                                <span class="help-block m-b-none"><?php echo (isset($details['health_permit']) && ($details['health_permit'] !== '')?$details['health_permit']:'--'); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">备注</label>
                            <div class="col-sm-5">
                                <span class="help-block m-b-none"><?php echo (isset($details['remarks']) && ($details['remarks'] !== '')?$details['remarks']:'--'); ?></span>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $details['id']; ?>">
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

<script type="text/javascript" src="/public/static/js/provider.js"></script>
<script type="text/javascript">
    var $details = '<?php echo json_encode($details); ?>';
    var $userId = '<?php echo json_encode($id); ?>';
</script>
</html>