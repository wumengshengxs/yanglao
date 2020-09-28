<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:103:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/client/currenthealthy.html";i:1556431371;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
                <small>服务对象管理&nbsp;>&nbsp;时时健康</small>
                <a href="javascript:history.back(-1);" title="返回上一页"><i class="fa fa-reply"></i></a>
            </h5>
        </div>
        <div class="ibox-content c-b-info">
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
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>
                            智能终端IMEI:
                            <font color="red"><?php echo (isset($device_info['0']['imei']) && ($device_info['0']['imei'] !== '')?$device_info['0']['imei']:'未绑定穿戴式智能终端'); ?></font>
                        </h5>
                    </div>
                    <div class="ibox-content">
                        <!-- 心率 -->
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="flot-chart">
                                    <div class="flot-chart-content">
                                        <h3>
                                            <strong>心率</strong>
                                            <strong>
                                                <input type="text" value="<?php echo $health['heart']; ?>" class="dial m-r-sm" data-fgColor="#1AB394" data-width="85" data-height="85" data-step="1000" data-min="0" data-max="200" data-displayPrevious=true/>
                                            </strong>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="flot-chart">
                                    <div class="flot-chart-content">
                                        <h3 class="font-bold" align="center"><i class="fa fa-clock-o"></i>
                                            <?php echo $health['addtime']; ?>
                                        </h3>
                                        <div align="center">
                                            <?php echo $health['heart']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="flot-chart">
                                    <div class="flot-chart-content">
                                        数据来源 
                                        <h3>
                                            <i class="fa fa-heartbeat"></i>
                                            <strong><?php echo $health['state']; ?></strong>
                                        </h3>   
                                    </div>
                                </div>
                            </div>
                           
                            <div class="col-sm-4">
                                <div class="flot-chart">
                                    <div class="flot-chart-content" id="qs"> 
                                        <button type="button" data-toggle="modal" data-target="#myModal1" class="btn btn-outline btn-success">手动录入</button>
                                        <button type="button" class="btn btn-outline btn-success" data-toggle="modal" data-target="#graph" data-type="ech_heart">趋势图</button>
                                      
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- 血压 -->
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="flot-chart">
                                    <div class="flot-chart-content">
                                        <h3>
                                            <strong>血压</strong>
                                            <strong>
                                                <input type="text" value="<?php echo $health['blood']; ?>" class="dial m-r-sm" data-fgColor="#1AB394" data-width="85" data-height="85" data-step="1000" data-min="0" data-max="200" data-displayPrevious=true/>
                                            </strong>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="flot-chart">
                                    <div class="flot-chart-content">
                                        <h3 class="font-bold" align="center"><i class="fa fa-clock-o"></i><?php echo $health['addtime']; ?></h3>
                                        <div align="center">
                                            <!-- 高压:<?php echo (isset($health['1']['hyperpiesia_one']) && ($health['1']['hyperpiesia_one'] !== '')?$health['1']['hyperpiesia_one']:'0'); ?> ---- 低压<?php echo (isset($health['1']['hyperpiesia_two']) && ($health['1']['hyperpiesia_two'] !== '')?$health['1']['hyperpiesia_two']:'0'); ?> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="flot-chart">
                                    <div class="flot-chart-content">
                                        数据来源 
                                        <h3>
                                            <i class="fa fa-heartbeat"></i>
                                            <strong><?php echo $health['state']; ?></strong>
                                        </h3>   
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="flot-chart">
                                    <div class="flot-chart-content" id="qs"> 
                                        <button type="button" data-toggle="modal" data-target="#myModal2" class="btn btn-outline btn-success">手动录入</button>
                                        <button type="button" class="btn btn-outline btn-success" data-toggle="modal" data-target="#graph-qs" data-type="ech_blood">趋势图</button>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- 计步 -->
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="flot-chart">
                                    <div class="flot-chart-content">
                                        <h3>
                                            <strong>计步</strong>
                                            <strong>
                                                <input type="text" value="<?php echo $health['steep']; ?>" class="dial m-r-sm" data-fgColor="#1AB394" data-width="85" data-height="85" data-step="1000" data-min="0" data-max="10000" data-displayPrevious=true/>
                                            </strong>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="flot-chart">
                                    <div class="flot-chart-content">
                                        <h3 class="font-bold" align="center"><i class="fa fa-clock-o"></i><?php echo $health['addtime']; ?></h3>
                                        <div align="center">
                                            步数<?php echo $health['steep']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="flot-chart">
                                    <div class="flot-chart-content">
                                        数据来源 
                                        <h3>
                                            <i class="fa fa-heartbeat"></i>
                                            <strong><?php echo $health['state']; ?></strong>
                                        </h3>   
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="flot-chart">
                                    <div class="flot-chart-content" id="qs"> 
                                        <button type="button" data-toggle="modal" data-target="#myModal3" data-type="steep" class="btn btn-outline btn-success">手动录入</button>
                                        <button type="button" class="btn btn-outline btn-success" data-toggle="modal" data-target="#graph" data-type="ech_steep">趋势图</button>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- 睡眠 -->
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="flot-chart">
                                    <div class="flot-chart-content">
                                        <h3>
                                            <strong>睡眠</strong>
                                            <strong>
                                                <input type="text" value="<?php echo $health['sleep']; ?>" class="dial m-r-sm" data-fgColor="#1AB394" data-width="85" data-height="85" data-step="1" data-min="0" data-max="10000" data-displayPrevious=true/>
                                            </strong>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="flot-chart">
                                    <div class="flot-chart-content">
                                        <h3 class="font-bold" align="center"><i class="fa fa-clock-o"></i><?php echo $health['addtime']; ?></h3>
                                        <div align="center">
                                           深度睡眠（<?php echo $health['sleep']; ?>分钟）
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="flot-chart">
                                    <div class="flot-chart-content">
                                        数据来源 
                                        <h3>
                                            <i class="fa fa-heartbeat"></i>
                                            <strong><?php echo $health['state']; ?></strong>
                                        </h3>   
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="flot-chart">
                                    <div class="flot-chart-content" id="qs"> 
                                        <button type="button" data-toggle="modal" data-target="#myModal4" class="btn btn-outline btn-success">手动录入</button>
                                        <button type="button" data-toggle="modal" data-target="#graph" data-type="ech_sleep" class="btn btn-outline btn-success">趋势图</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 心率表单 -->
<div class="modal inmodal" id="myModal1" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4>手工入录--心率</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t" id="heart_from">
                    <input type="hidden" name="uid" value="<?php echo $uid; ?>">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">测量设备</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="state">
                                <option value='2'>手环</option>
                                <option value='1'>手表</option>
                                <option value='3'>枕头</option>
                                <option value='4'>一体机</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label label_title">心率值</label>
                        <div class="col-sm-8">
                            <input  name="content" class="form-control" type="number" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">创建时间</label>
                        <div class="col-sm-8">
                            <input type="text" name="start_create"  class="form-control start_create" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-white model_close" data-dismiss="modal" id="close">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="heart_action()">保存</button>
                </center>
            </div>
        </div>
    </div>
</div>
<!-- 血压 -->
<div class="modal inmodal" id="myModal2" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header label_title_h">
                <h4>血压手动入录</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t" id="blood">
                    <input type="hidden" name="uid" value="<?php echo $uid; ?>">
                     <div class="form-group">
                        <label class="col-sm-3 control-label">测量设备</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="state">
                                <option value='1'>手表</option>
                                <option value='3'>枕头</option>
                                <option value='4'>一体机</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label label_title_one">高压</label>
                        <div class="col-sm-8">
                            <input  name="hyperpiesia_one" class="form-control" type="number"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label label_title_two">低压</label>
                        <div class="col-sm-8">
                            <input  name="hyperpiesia_two" class="form-control" type="number"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">创建时间</label>
                        <div class="col-sm-8">
                            <input type="text" name="hyperpiesia_create"  class="form-control hyperpiesia_create" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-white model_close" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="hyperpiesia_submit()">保存</button>
                </center>
            </div>
        </div>
    </div>
</div>
<!-- 计步表单 -->
<div class="modal inmodal" id="myModal3" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4>手工入录--计步</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t" id="steep_from">
                    <input type="hidden" name="uid" value="<?php echo $uid; ?>">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">测量设备</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="state">
                                <option value='2'>手环</option>
                                <option value='1'>手表</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label label_title">运动步数</label>
                        <div class="col-sm-8">
                            <input  name="steep" class="form-control" type="number" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">创建时间</label>
                        <div class="col-sm-8">
                            <input type="text" name="steep_create" class="form-control steep_create" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-white model_close" data-dismiss="modal" id="close">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="steep_action()">保存</button>
                </center>
            </div>
        </div>
    </div>
</div>
<!-- 睡眠 -->
<div class="modal inmodal" id="myModal4" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header label_title_h">
                <h4>睡眠</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t" id="sleep_form">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">测量设备</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="p_id">
                                <option value='2'>手环</option>
                                <option value='1'>手表</option>
                                <option value='3'>枕头</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label label_title_one">睡眠开始</label>
                        <div class="col-sm-8">
                            <input  name="sleep_start" class="form-control sleep_start" type="text" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label label_title_two">睡眠结束</label>
                        <div class="col-sm-8">
                            <input  name="sleep_end" class="form-control sleep_end" type="text"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">创建时间</label>
                        <div class="col-sm-8">
                            <input type="text" name="start_create"  class="form-control sleep_create" />
                        </div>
                    </div>
                    <input type="hidden" name="uid" value="<?php echo $uid; ?>"/>
                </form>
            </div>
            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-white model_close" data-dismiss="modal" id="close">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="sleep()">保存</button>
                </center>
            </div>
        </div>
    </div>
</div>
<!-- 心率、睡眠、计步图标 -->
<div class="modal inmodal fade" id="graph" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="ibox-content">
                    <div class="echarts" id="echarts_img"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary">保存</button>
            </div>
        </div>
    </div>
</div>
<!-- 血压 -->
<div class="modal inmodal fade" id="graph-qs" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title qs">近7日血压图表</h4>
            </div>
            <div class="modal-body">
                <div class="ibox-content">
                    <div class="echarts" id="echarts_img_qs"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary">保存</button>
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
<script src="/public/static/js/plugins/jsKnob/jquery.knob.js"></script>
<script src="/public/static/js/main/echarts.min.js"></script>
<script type="text/javascript">
    var $userId = '<?php echo $_GET["id"]; ?>';
</script>
<script type="text/javascript">
    $(function(){
        //甜甜圈初始化
        $(".dial").knob(); 
    });

    //日历插件
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        laydate.render({
            elem: '.start_create',
            type: 'datetime',

        });
        laydate.render({
            elem: '.steep_create',
            type: 'datetime',

        });
        laydate.render({
            elem: '.sleep_start',
            type: 'datetime',
        });
        laydate.render({
            elem: '.sleep_end',
            type: 'datetime', 
        });
        laydate.render({
            elem: '.sleep_create',
            type: 'datetime',
        });
        laydate.render({
            elem: '.hyperpiesia_create',
            type: 'datetime', 
        });
    });

    //人工入录心率
    function heart_action(){
        var content = $("input[name='content']").val();
        var start_create = $("input[name='start_create']").val();
        if (!content) {
            layer.msg('请填写心率值');
            return false;
        }
        if (!start_create) {
            layer.msg('请填写测量时间');
            return false;
        }
        $.post('/index/Client/addheart',$('#heart_from').serializeArray(),function(res){
            if (res.code==0) {
                layer.msg(res.msg,{icon:1,time:1000},function(){
                    location.reload();
                });
                return false;
            }
            layer.msg(res.msg,{icon:5,time:1000});
        });
    }

    //血压表单
    function hyperpiesia_submit(){
        var hyperpiesia_one = $("input[name='hyperpiesia_one']").val();
        var hyperpiesia_two = $("input[name='hyperpiesia_two']").val();
        var hyperpiesia_create = $(".hyperpiesia_create").val();

        if (!hyperpiesia_one) {
            layer.msg('请填写高压值');
            return false;
        }

        if (!hyperpiesia_two) {
            layer.msg('请填写低压值');
            return false;
        }

        if (!hyperpiesia_create) {
            layer.msg('请填写创建时间');
            return false;
        }
        $.post('/index/Client/addblood',$('#blood').serializeArray(),function(res){
            if (res.code==0) {
                layer.msg(res.msg,{icon:1,time:1000},function(){
                    location.reload();
                });
                return false;
            }
            layer.msg(res.msg,{icon:5,time:1000});
        });
    }

    //计步信息
    function steep_action(){
        var steep = $("input[name='steep']").val();
        var steep_create = $("input[name='steep_create']").val();
        if (!steep) {
            layer.msg('请填写运动步数');
            return false;
        }
        if (!steep_create) {
            layer.msg('请填写测量时间');
            return false;
        }
        $.post('/index/Client/addsteep',$('#steep_from').serializeArray(),function(res){
            if (res.code==0) {
                layer.msg(res.msg,{icon:1,time:1000},function(){
                    location.reload();
                });
                return false;
            }
            layer.msg(res.msg,{icon:5,time:1000});
        });
    }

    //睡眠数据
    function sleep(){
        var sleep_start = $(".sleep_start").val();
        var sleep_end = $(".sleep_end").val();
        var sleep_create = $(".sleep_create").val();
        if (!sleep_start) {
            layer.msg('请填写睡眠开始时间');
            return false;
        }

        if (!sleep_end) {
            layer.msg('请填写睡眠结束时间');
            return false;
        }

        if (!sleep_create) {
            layer.msg('请填写测量时间');
            return false;
        }

        if (sleep_end < sleep_start) {
            layer.msg('睡眠结束时间不能小于开始时间');
            return false;
        }
        $.post('/index/Client/addsleep',$('#sleep_form').serializeArray(),function(res){
            if (res.code==0) {
                layer.msg(res.msg,{icon:1,time:1000},function(){
                    location.reload();
                });
                return false;
            }
            layer.msg(res.msg,{icon:5,time:1000});
        });
    }

    //关闭后清空值
    $('.model_close').click(function(){
        $('.form-control').val('');
    });

    //max属性
    $("input[name='content']").bind('input propertychange', function() { //绑定内容改变执行时间
       var tvalmum;
       tvalnum = $("input[name='content']").val().length;
        if(tvalnum>3){
            layer.msg('可输入长度为5个字符',function(){
                var tval = $("input[name='content']").val();
                tval = tval.substring(0,3); //指定8位长度 超出截取
                $("input[name='content']").val(tval);
            });
        }
    });


    /*
    *心率与计步的图表
    *图表模态框传递参数取值
    */
    $('#graph').on('shown.bs.modal',function(event){
        var btn_ch = $(event.relatedTarget);
        var type = btn_ch.data("type"); 
        switch(type){
            case 'ech_heart':
                $('.label_title').html('心率');
                var html = '近7日心率图表';
                var search = 'heart';
            break;
            case 'ech_steep':
                $('.label_title').html('计步');
                var html = '近7日计步图表';
                var search = 'steep';
            break;
            case 'ech_sleep':
                $('.label_title').html('睡眠');
                var html = '近7日睡眠图表';
                var search = 'sleep';
            break;
        }
        $('.modal-title').html(html);
        var uid = $("input[name='uid']").val();
        $.post('/index/Client/healthec',{'state':search,'uid':uid},function(res){
            console.log(res.v);
            var a = echarts.init(document.getElementById('echarts_img'));
            //图表参数
            var init_option = {
                color: ['#3398DB'],
                tooltip : {
                    trigger: 'axis',
                    axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                        type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis : [
                    {
                        type : 'category',
                        data : res.day,
                        axisTick: {
                            alignWithLabel: true
                        }
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : [
                    {
                        name:'',
                        type:'bar',
                        barWidth: '60%',
                        data:res.v,
                        itemStyle: {
                            normal: {
                                color:'#39b79d',
                            },
                        },
                    }
                ]
            };
            //使用刚指定的配置项和数据显示图表。
            a.setOption(init_option);
        });
    });

    /*
    *血压与睡眠的图表
    *图表模态框传递参数取值
    */
    $('#graph-qs').on('shown.bs.modal',function(event){
        var arr = ['高压','低压'];
        var uid = $("input[name='hyperpiesia_two_uid']").val();
        $.post('/index/Client/healthboold',{'state':'blood','uid':uid},function(re){
            console.log(re.max);
            console.log(re.min);
            var a = echarts.init(document.getElementById('echarts_img_qs'));
            var option = {
                title : {
                    text: '',
                    subtext: ''
                },
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    data:arr
                },
                // calculable : true,
                xAxis : [
                    {
                        type : 'category',
                        data : re.day
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : [
                    {
                        name:arr[0],
                        type:'bar',
                        data:re.max,
                    },
                    {
                        name:arr[1],
                        type:'bar',
                        data:re.min,
                    }
                ]
            };
            //使用刚指定的配置项和数据显示图表。
            a.setOption(option);
        });
    });
</script>
</html>