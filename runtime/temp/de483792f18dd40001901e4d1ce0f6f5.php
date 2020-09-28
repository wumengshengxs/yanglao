<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:99:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/provider/provider.html";i:1548320618;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1548228499;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1548228499;}*/ ?>
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
<link rel="stylesheet" type="text/css" href="/public/static/js/plugins/layui/css/layui.css">
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>
                <small>服务商管理&nbsp;&gt;&nbsp;添加服务商</small>
                <a href="javascript:history.back(-1);" title="返回上一页"><i class="fa fa-reply"></i></a>
            </h5>
        </div>
        <div class="ibox-content">
            <form class="form-horizontal a-u-provider">
                <input type="hidden" name="id" value="<?php echo $details['id']; ?>">
                <div class="form-group">
                    <label class="col-sm-4 control-label">* 服务商名称</label>
                    <div class="col-sm-4">
                        <input type="text" name="name" value="<?php echo $details['name']; ?>" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">简称</label>
                    <div class="col-sm-4">
                        <input type="text" name="short_name" value="<?php echo $details['short_name']; ?>" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">* 联系人名称</label>
                    <div class="col-sm-4">
                        <input type="text" name="contacts" value="<?php echo $details['contacts']; ?>" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">* 联系人电话</label>
                    <div class="col-sm-4">
                        <input type="text" name="contacts_mobile" value="<?php echo $details['contacts_mobile']; ?>" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">* 企业地址</label>
                    <div class="col-sm-4">
                        <input type="text" name="address" value="<?php echo $details['address']; ?>" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">法人代表</label>
                    <div class="col-sm-4">
                        <input type="text" name="legal_person" value="<?php echo $details['legal_person']; ?>" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">法人代表联系方式</label>
                    <div class="col-sm-4">
                        <input type="text" name="legal_mobile" value="<?php echo $details['legal_mobile']; ?>" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">注册资金</label>
                    <div class="col-sm-4">
                        <input type="text" name="registered_capital" value="<?php echo $details['registered_capital']; ?>" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">加盟时间</label>
                    <div class="col-sm-4">
                        <input type="text" name="join_time" value="<?php echo $details['join_time']; ?>" id="join_time" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">截止有效期</label>
                    <div class="col-sm-4">
                        <input type="text" name="expiry_time" value="<?php echo $details['expiry_time']; ?>" id="expiry_time" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">组织机构编号</label>
                    <div class="col-sm-4">
                        <input type="text" name="org_code" value="<?php echo $details['org_code']; ?>" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">组织机构分类</label>
                    <div class="col-sm-4">
                        <input type="text" name="org_type" value="<?php echo $details['org_type']; ?>" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">税务编号</label>
                    <div class="col-sm-4">
                        <input type="text" name="tax_number" value="<?php echo $details['tax_number']; ?>" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">卫生许可证编号</label>
                    <div class="col-sm-4">
                        <input type="text" name="health_permit" value="<?php echo $details['health_permit']; ?>" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">备注</label>
                    <div class="col-sm-4">
                        <textarea name="remarks" cols="30" rows="3" class="form-control"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-5">
                        <button class="btn btn-primary" type="submit">保存</button>
                    </div>
                </div>
            </form>
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
</html>