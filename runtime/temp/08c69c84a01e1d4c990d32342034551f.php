<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:104:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/client/currentposition.html";i:1551145610;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
<link href="/public/static/css/map.css" rel="stylesheet">
<link rel="stylesheet" href="/public/static/css/client.css">
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>
                <small>服务对象管理&nbsp;>&nbsp;时时定位</small>
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
                        <button onclick="address(<?php echo $user['imei']; ?>)" class="btn btn-outline btn-success">创建电子围栏</button>
                        <button onclick="rmaddress(<?php echo $user['imei']; ?>)" class="btn btn-outline btn-success">移除电子围栏</button>
                        <button onclick="getgps(<?php echo $user['imei']; ?>)" class="btn btn-outline btn-success" title="获取定位">获取定位</button>
                        <form class="form-horizontal m-t" id="gpsPosition" action="javascript:;gpsAction()" style="display:none;">
                            <input type="hidden" name="lng" required="" aria-required="true"/>
                            <input type="hidden" name="lat" required="" aria-required="true"/>
                            <input type="hidden" name="radius" required="" aria-required="true"/>
                            <input type="hidden" name="imei" value="<?php echo $user['imei']; ?>"/>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">围栏名称</label>
                                <div class="col-sm-8">
                                    <input name="fencname" class="form-control" type="text"  placeholder="请输入围栏名称" required="" aria-required="true" value="<?php echo $user['fencename']; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-3">
                                    <button class="btn btn-primary" type="submit" id="button_id">提交</button>
                                </div>
                            </div>
                        </form>
                        <div class="panel-body" id='allmap' style="overflow: hidden;position: relative;left: 0;top: 0;height: 700px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="timely" value='<?php echo $loc; ?>'/>
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

<!-- 百度请求 -->
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=KW0y9CM8nvQWkWcQ4jIDTOBevgSapQQQ"></script>
<!--加载鼠标绘制工具-->
<script type="text/javascript" src="http://api.map.baidu.com/library/DrawingManager/1.4/src/DrawingManager_min.js"></script>
<link rel="stylesheet" href="http://api.map.baidu.com/library/DrawingManager/1.4/src/DrawingManager_min.css" />
<!--加载检索信息窗口-->
<script type="text/javascript" src="http://api.map.baidu.com/library/SearchInfoWindow/1.4/src/SearchInfoWindow_min.js"></script>
<link rel="stylesheet" href="http://api.map.baidu.com/library/SearchInfoWindow/1.4/src/SearchInfoWindow_min.css" />
<script type="text/javascript" src="/public/static/js/client.js"></script>
<script type="text/javascript">
    var $userId = '<?php echo $_GET["id"]; ?>';
</script>
<script type="text/javascript" src="/public/static/js/client-map.js"></script>
</html>