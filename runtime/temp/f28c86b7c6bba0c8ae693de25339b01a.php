<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:96:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/device/details.html";i:1556431371;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
                <small>设备管理&nbsp;>&nbsp;设备详情</small>
                <a href="javascript:history.back(-1);" title="返回上一页"><i class="fa fa-reply"></i></a>
            </h5>
        </div>
        <div class="ibox-content c-b-info">
            <div class="col-sm-12 m-t">
                <div>
                    <h3 class="inline">设备基础信息</h3>
                </div>
                <div class="base-info">
                    <form class="form-horizontal">
                        <div class="row m-t col-sm-12">
                            <hr/>
                            <div class="col-sm-2">
                                设备基础信息
                            </div>
                            <div class="col-sm-10">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">IMEI号</label>
                                    <div class="col-sm-10">
                                        <span class="help-block m-b-none"><?php echo $details['imei']; ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">固件版本号</label>
                                    <div class="col-sm-10">
                                        <span class="help-block m-b-none"><?php echo $details['version']; ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">设备电量</label>
                                    <div class="col-sm-10">
                                        <span class="help-block m-b-none"><?php echo $details['electric']; ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">绑定时间</label>
                                    <div class="col-sm-10">
                                        <span class="help-block m-b-none"><?php echo (isset($details['bind_time']) && ($details['bind_time'] !== '')?$details['bind_time']:'--'); ?></span>
                                    </div>
                                </div>
                                <?php if($details['pid'] != '3'): ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">在线状态</label>
                                    <div class="col-sm-10">
                                        <span class="help-block m-b-none d_status"><?php echo $details['d_status']; ?></span>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">上次连接</label>
                                    <div class="col-sm-10">
                                        <span class="help-block m-b-none"><?php echo (isset($details['last_connection']) && ($details['last_connection'] !== '')?$details['last_connection']:'--'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="bind-info">
                    <form class="form-horizontal">
                        <div class="row m-t col-sm-12">
                            <hr/>
                            <div class="col-sm-2">
                                <span class="btn">绑定用户</span><br/>
                                <?php if($details['is_binding'] == 1): ?>
                                <button type="button" class="btn btn-white cancel-bind">取消绑定</button>
                                <?php else: ?>
                                <button type="button" class="btn btn-white bind-user">用户绑定</button>
                                <?php endif; ?>
                            </div>
                            <div class="col-sm-10">
                                <?php if($details['is_binding'] == 1): ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">姓名</label>
                                    <div class="col-sm-10">
                                        <span class="help-block m-b-none"><?php echo $bind_user['name']; ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">年龄</label>
                                    <div class="col-sm-10">
                                        <span class="help-block m-b-none"><?php echo $bind_user['age']; ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">性别</label>
                                    <div class="col-sm-10">
                                        <span class="help-block m-b-none"><?php echo $bind_user['sex']; ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">身份证</label>
                                    <div class="col-sm-10">
                                        <span class="help-block m-b-none"><?php echo $bind_user['id_number']; ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">手机号码</label>
                                    <div class="col-sm-10">
                                        <span class="help-block m-b-none"><?php echo $bind_user['mobile']; ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">居住地址</label>
                                    <div class="col-sm-10">
                                        <span class="help-block m-b-none"><?php echo $bind_user['address']; ?></span>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">未绑定用户</label>
                                    <div class="col-sm-10"></div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
                <?php if($details['is_binding'] == 1): ?>
                <div class="send-info">
                    <form class="form-horizontal">
                        <div class="row m-t col-sm-12">
                            <hr/>
                            <div class="col-sm-2">
                                <span class="btn">设备发放</span><br/>
                                <button type="button" class="btn btn-white e-device-send">编辑</button>
                            </div>
                            <div class="col-sm-10">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">ICCID号码</label>
                                    <div class="col-sm-10">
                                        <span class="help-block m-b-none"><?php echo $details['iccid']; ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">电话号码</label>
                                    <div class="col-sm-10">
                                        <span class="help-block m-b-none"><?php echo $details['msisdn']; ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">是否发放</label>
                                    <div class="col-sm-10">
                                        <span class="help-block m-b-none"><?php echo $details['is_send']; ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">发放时间</label>
                                    <div class="col-sm-10">
                                        <span class="help-block m-b-none"><?php echo $details['send_time']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <?php endif; if(($details['pid'] != '3') AND ($details['pid'] != '4')): ?>
                <div class="emergency-info">
                    <form class="form-horizontal">
                        <div class="row m-t col-sm-12">
                            <hr/>
                            <div class="col-sm-2">
                                <span class="btn">紧急联系人</span><br/>
                                <button type="button" class="btn btn-white e-device-sos">编辑</button>
                            </div>
                            <div class="col-sm-10">
                                <div class="form-group">
                                    <label class="col-sm-12">紧急呼叫号码最多可以预留3个，当触发腕表上的紧急呼叫按钮时将按顺序轮流拨打以下预留的电话号码</label>
                                </div>
                                <?php if(!(empty($emergency) || (($emergency instanceof \think\Collection || $emergency instanceof \think\Paginator ) && $emergency->isEmpty()))): if(is_array($emergency) || $emergency instanceof \think\Collection || $emergency instanceof \think\Paginator): $i = 0; $__LIST__ = $emergency;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$e): $mod = ($i % 2 );++$i;?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><?php echo $e['name']; ?></label>
                                    <div class="col-sm-10">
                                        <span class="help-block m-b-none"><?php echo $e['mobile']; ?></span>
                                    </div>
                                </div>
                                <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="interval-info">
                    <form class="form-horizontal">
                        <div class="row m-t col-sm-12">
                            <hr/>
                            <div class="col-sm-2">
                                <span class="btn">GPS工作时间设置</span><br/>
                                <button type="button" class="btn btn-white e-device-times-timing">编辑</button>
                            </div>
                            <div class="col-sm-10">
                                <div class="form-group">
                                    <label class="col-sm-12">设置GPS工作时间后,GPS定位数据将按照您设置的时间范围内上传定位数据</label>
                                </div>
                               <div class="form-group">
                                    <label class="col-sm-2 control-label">工作设置</label>
                                    <div class="col-sm-10">
                                        <span class="help-block m-b-none">（<?php echo (isset($details['start_time']) && ($details['start_time'] !== '')?$details['start_time']:'0'); ?>）起始时间</span>
                                        <span class="help-block m-b-none">（<?php echo (isset($details['end_time']) && ($details['end_time'] !== '')?$details['end_time']:'0'); ?>）结束时间</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="timing-info-gps">
                    <form class="form-horizontal">
                        <div class="row m-t col-sm-12">
                            <hr/>
                            <div class="col-sm-2">
                                <span class="btn">GPS时间间隔</span><br/>
                                <button type="button" class="btn btn-white e-device-times">编辑</button>
                            </div>
                            <div class="col-sm-10">
                                <div class="form-group">
                                    <label class="col-sm-12">设置GPS间隔后,GPS定位数据将按照您设置的时间间隔上传定位数据</label>
                                </div>
                               <div class="form-group">
                                    <label class="col-sm-2 control-label">时间间隔</label>
                                    <div class="col-sm-10">
                                        <span class="help-block m-b-none">（<?php echo (isset($details['gps']) && ($details['gps'] !== '')?$details['gps']:'0'); ?>）分钟</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <?php endif; ?>
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

<script type="text/javascript" src="/public/static/js/bootstrap-paginator.js"></script>
<script type="text/javascript" src="/public/static/js/device.js"></script>
<script type="text/javascript" src="/public/static/js/device/details.js"></script>
<script type="text/javascript">
    var $deviceDetails = '<?php echo json_encode($details); ?>',
        $deviceSos = '<?php echo json_encode($emergency); ?>';
</script>
</html>