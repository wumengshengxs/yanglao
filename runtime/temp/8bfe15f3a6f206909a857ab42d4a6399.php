<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:100:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/server/view/work/work_details.html";i:1554081914;s:94:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/server/view/public/head.html";i:1552612121;s:94:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/server/view/public/foot.html";i:1552612121;}*/ ?>
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
<link rel="stylesheet" href="/public/static/css/work.css">
<link type="text/css" rel="stylesheet" href="/public/static/rating/css/application.css">
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>
                        <small>服务工单管理&nbsp;>&nbsp;工单明细</small>
                        <a href="javascript:history.back(-1);" title="返回上一页"><i class="fa fa-reply"></i></a>
                    </h5>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>工单详情</h5>
                </div>
                <div class="ibox-content">
                    <div class="col-sm-8">
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>工单编号</stront></label>
                            <span class="col-sm-8">YC<?php echo $provider['id']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>工单标题</stront></label>
                            <span class="col-sm-8"><?php echo $provider['title']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>工单地址</stront></label>
                            <span class="col-sm-8"><?php echo $provider['site']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>服务商</stront></label>
                            <span class="col-sm-8"><?php echo $provider['company']; ?></span>
                        </div>

                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>服务人员</stront></label>
                            <span class="col-sm-8"><?php echo $provider['sname']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>服务对象</stront></label>
                            <span class="col-sm-8"><?php echo $provider['cname']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>服务金额</stront></label>
                            <span class="col-sm-8"><?php echo $provider['money']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>服务类型</stront></label>
                            <span class="col-sm-8"><?php echo $provider['tname']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>创建时间</stront></label>
                            <span class="col-sm-8"><?php echo $provider['create_time']; ?></span>
                        </div>

                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>工单状态</stront></label>
                            <span class="col-sm-8"><?php echo $provider['type']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>接单时间</stront></label>
                            <span class="col-sm-8"><?php echo $provider['start_time']; ?></span>
                        </div>
                        <div class="col-sm-6 m-b-sm">
                            <label class="col-sm-4 text-right"><stront>结单时间</stront></label>
                            <span class="col-sm-8"><?php echo $provider['end_time']; ?></span>
                        </div>

                    </div>
                    <div class="col-sm-4 text-center">

                        <?php if($provider['state'] == 3 && $provider['gid'] != 0): ?>
                        <button type="button" class="btn btn-primary" data-target="#myModal1" onclick="getGrade('<?php echo $provider['gid']; ?>')" data-toggle="modal" >查看评分</button>
                        <?php endif; ?>
                        <br/>

                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>近期工单</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>工单标题</th>
                            <th>工单编号</th>
                            <th>创建时间</th>
                            <th>服务商</th>
                            <th>上门人员</th>
                            <th>工单类型</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!(empty($provider['work']) || (($provider['work'] instanceof \think\Collection || $provider['work'] instanceof \think\Paginator ) && $provider['work']->isEmpty()))): if(is_array($provider['work']) || $provider['work'] instanceof \think\Collection || $provider['work'] instanceof \think\Paginator): $i = 0; $__LIST__ = $provider['work'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td><?php echo $vo['title']; ?></td>
                            <td>YC<?php echo $vo['id']; ?></td>
                            <td><?php echo $vo['create_time']; ?></td>
                            <td><?php echo $vo['company']; ?></td>
                            <td><?php echo $vo['sname']; ?></td>
                            <td><?php echo $vo['tname']; ?></td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; else: ?>
                        <tr>
                            <td colspan="6">暂无数据</td>
                        </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="modal inmodal" id="myModal1" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated fadeIn">
                <div class="modal-header">
                    <h4 >服务工单评分  总分: <span id="total"></span></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal m-t" id="forms1">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">*上门响应速度：</label>
                            <div class="col-sm-8">
                                <input  name="speed" class="form-control" id="speed" type="number" maxlength="2" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">*上门服务仪表：</label>
                            <div class="col-sm-8">
                                <input  name="meter" class="form-control" id="meter" type="number" maxlength="2" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">*上门服务内容：</label>
                            <div class="col-sm-8">
                                <input  name="details" class="form-control" id="details" type="number" maxlength="2" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">*上门服务用语：</label>
                            <div class="col-sm-8">
                                <input  name="term" class="form-control" id="term" type="number" maxlength="2" >
                            </div>
                        </div>
                        <input  name="id" class="form-control" id="ids" type="hidden" value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <center>
                        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
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

<script type="text/javascript" src="/public/static/js/provider_work.js"></script>
<script type="text/javascript" src="/public/static/rating/js/jquery.raty.min.js"></script>

</html>