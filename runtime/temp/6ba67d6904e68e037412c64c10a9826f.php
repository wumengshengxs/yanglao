<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:94:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/device/index.html";i:1556431371;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
        <div class="ibox-content">
            <div class="ibox-title">
                <h5>
                    <button class="btn btn-white btn-sm a-device"><i class="fa fa-plus"></i> 批量设备录入</button>&nbsp;&nbsp;
                    <button type="button" data-toggle="modal" data-target="#myModal1" class="btn btn-white btn-sm">
                        <i class="fa fa-plus"></i>手动录入
                    </button>&nbsp;&nbsp;
                    <div class="btn-group search">
                        <button class="btn btn-white btn-sm" type="button"><i class="fa fa-search"></i> 高级搜索</button>
                        <div class="dropdown-menu">
                            <form class="form-horizontal" action="/index/Device/index" method="get">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">通道</label>
                                    <div class="col-sm-9">
                                        <select name="passage" class="form-control">
                                            <option value="">请选择采购通道</option>
                                            <?php if(is_array($passage) || $passage instanceof \think\Collection || $passage instanceof \think\Paginator): $i = 0; $__LIST__ = $passage;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p): $mod = ($i % 2 );++$i;?>
                                            <option value="<?php echo $p['id']; ?>" <?php if($p['id'] == $param['passage']): ?> selected <?php endif; ?>><?php echo $p['name']; ?></option>
                                            <?php endforeach; endif; else: echo "" ;endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">IMEI号</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="imei" value="<?php echo $param['imei']; ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">固件版本号</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="version" value="<?php echo $param['version']; ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">是否绑定</label>
                                    <div class="col-sm-9">
                                        <div class="radio radio-success radio-inline">
                                            <input type="radio" id="bind1" value="1" name="bind">
                                            <label for="bind1"> 是 </label>
                                        </div>
                                        <div class="radio radio-success radio-inline">
                                            <input type="radio" id="bind2" value="2" name="bind">
                                            <label for="bind2"> 否 </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">绑定时间</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="start_time" value="<?php echo $param['start_time']; ?>" class="form-control bind-times">
                                    </div>
                                    <div class="col-sm-1 middle-div">至</div>
                                    <div class="col-sm-4 f-right">
                                        <input type="text" name="end_time" value="<?php echo $param['end_time']; ?>" class="form-control bind-times">
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
                    </div>&nbsp;&nbsp;
                    <?php if(!(empty($item_value) || (($item_value instanceof \think\Collection || $item_value instanceof \think\Paginator ) && $item_value->isEmpty()))): if(is_array($item_value) || $item_value instanceof \think\Collection || $item_value instanceof \think\Paginator): $i = 0; $__LIST__ = $item_value;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$iv): $mod = ($i % 2 );++$i;?>
                    <span style="font-weight: lighter;"><?php echo $iv['item']; ?>：<?php echo $iv['value']; ?>&nbsp;<a href="javascript:;">X</a>&nbsp;&nbsp;</span>
                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                </h5>
            </div>
            <div class="row row-lg">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>通道</th>
                                <th>IMEI</th>
                                <th>是否绑定用户</th>
                                <th>绑定时间</th>
                                <th>上次连接时间</th>
                                <th>固件版本号</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($device['data']) || $device['data'] instanceof \think\Collection || $device['data'] instanceof \think\Paginator): $k = 0; $__LIST__ = $device['data'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p): $mod = ($k % 2 );++$k;?>
                            <tr>
                                <td><?php echo $k; ?></td>
                                <td><?php echo $p['name']; ?></td>
                                <td><?php echo $p['imei']; ?></td>
                                <td><?php echo $p['is_binding']; ?></td>
                                <td><?php echo (isset($p['bind_time']) && ($p['bind_time'] !== '')?$p['bind_time']:'--'); ?></td>
                                <td><?php echo (isset($p['last_connection']) && ($p['last_connection'] !== '')?$p['last_connection']:'--'); ?></td>
                                <td><?php echo $p['version']; ?></td>
                                <td>
                                    <a href="/index/Device/details?id=<?php echo $p['id']; ?>">查看详情</a>
                                    <?php if($p['is_binding'] == '否'): ?>
                                    &nbsp;&nbsp;
                                    <a href="javascript:;" class="bind-user">绑定用户</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="page"><?php echo $page; ?> <?php echo $device['total']; ?>条数据，共<?php echo $device['last_page']; ?>页</div>
                </div>
            </div>
        </div>
    </div>
    <!-- 心率表单 -->
<div class="modal inmodal" id="myModal1" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4>设备录入</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t" id="device_from_info">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">采购厂商</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="pid">
                                <?php if(is_array($passage) || $passage instanceof \think\Collection || $passage instanceof \think\Paginator): $i = 0; $__LIST__ = $passage;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                                <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label label_title">IMEI</label>
                        <div class="col-sm-8">
                            <input  name="imei" class="form-control" type="text" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label label_title">ICCID</label>
                        <div class="col-sm-8">
                            <input  name="iccid" class="form-control" type="text" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label label_title">MSISDN</label>
                        <div class="col-sm-8">
                            <input  name="msisdn" class="form-control" type="number" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-white model_close" data-dismiss="modal" id="close">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="device_from_info_submit()">保存</button>
                </center>
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

<script type="text/javascript" src="/public/static/js/upload.js"></script>
<script type="text/javascript" src="/public/static/js/device.js"></script>
<script type="text/javascript">
    var $search = '<?php echo json_encode($item_value); ?>',
        $deviceList = '<?php echo json_encode($device["data"]); ?>',
        $passageList = '<?php echo json_encode($passage); ?>';
    /**
    * 时间插件
    * */
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        //执行一个laydate实例
        lay('.bind-times').each(function(){
            laydate.render({
                elem: this,
                trigger: 'click',
                type: 'datetime',
            });
        });
    });
    /*
    *手工录入设备
    */
    function device_from_info_submit(){
        var f_data = $('#device_from_info').serializeArray();
        console.log(f_data);
        $.each(f_data,function(k,v){
            if (!v.value) {
                layer.msg('请将表单填写完整',{icon:5,time:2000});
                return false;
            }
        });
        $.post('/index/Device/useroperation',$('#device_from_info').serialize(),function(list){
            if (list.code==0) {
                layer.msg(list.message,{icon:1,time:2000},function() {
                    location.reload();
                });
                return false;
            }
            layer.msg(list.message,{icon:1,time:2000});
        });
    }
</script>
</html>