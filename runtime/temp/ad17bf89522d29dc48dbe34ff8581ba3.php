<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:104:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/callcenter/call_center.html";i:1548320618;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1548228499;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1548228499;}*/ ?>
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
                    <div class="ibox-title" style="height: 130px;background-color:#e7eaec;font-size: 16px " >
                        <!--<button class="btn btn-white btn-sm"  data-target="#myModal4" data-toggle="modal" ><i class="fa fa-plus"></i> 添加子账户</button>-->
                        请求地址: <?php echo $config['url']; ?> <br>
                        主账号id: <?php echo $config['accountSid']; ?><br>
                        主账号token: <?php echo $config['accountToken']; ?><br>
                        应用id: <?php echo $config['appId']; ?><br>
                        应用版本号: <?php echo $config['softwareVersion']; ?>

                    </div>


                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>昵称</th>
                            <th>手机号码</th>
                            <th>邮箱</th>
                            <th>子账号id</th>
                            <th>token</th>
                            <th>企业总机号码</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td><?php echo $vo['id']; ?></td>
                            <td><?php echo $vo['nickname']; ?></td>
                            <td><?php echo $vo['mobile']; ?></td>
                            <td><?php echo $vo['email']; ?></td>
                            <td><?php echo $vo['sid']; ?></td>
                            <td><?php echo $vo['token']; ?></td>
                            <td><?php echo $vo['number']; ?></td>

                            <td>
                                <button type="button" onclick="editCall('<?php echo $vo['id']; ?>')" data-target="#myModal4" data-toggle="modal" class="btn btn-success">编辑</button>
                                <button type="button" onclick="editLimit('<?php echo $vo['id']; ?>')" data-target="#myModal5" data-toggle="modal" class="btn btn-success">设置呼叫限制</button>
                            </td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>

                    </table>


                </div>


            </div>
        </div>
    </div>
</div>

<div class="modal inmodal" id="myModal4" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4 >更新子账号</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t" id="forms">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">子账户昵称：</label>
                        <div class="col-sm-8">
                            <input  id="nickname" name="nickname" class="form-control" type="text"  maxlength="20" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">子账户手机号码：</label>
                        <div class="col-sm-8">
                            <input id="mobile"  name="mobile" class="form-control" type="text"  maxlength="11" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">子账户邮箱：</label>
                        <div class="col-sm-8">
                            <input id="email" name="email" class="form-control" type="email"  >
                        </div>
                    </div>

                    <input type="hidden"   name="id" id="ids" value="" >
                    <input type="hidden" id="sid"  name="sid" value="" >
                    <input type="hidden" id="token"  name="token" value="" >

                </form>
            </div>
            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="onSublimt()">保存</button>
                </center>
            </div>
        </div>
    </div>
</div>

<div class="modal inmodal" id="myModal5" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4 >设置呼叫限制</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t" id="forms2">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">限制类型：</label>
                        <div class="col-sm-8">
                            <select class="form-control m-b" name="limit_type" id="limit_type">
                                <option value="0">不限制</option>
                                <option value="1">自然月+周一开 始计周</option>
                                <option value="2">自然月+周日开始计周</option>
                                <option value="3">非自然周 /月:当日前一周或月统计</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">每天呼叫次数：</label>
                        <div class="col-sm-8">
                            <input id="day_limit"  name="day_limit" class="form-control" type="number" >
                            <span class="help-block m-b-none">范围(1-10)，0 表示不限制。</span>

                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">每周呼叫次数：</label>
                        <div class="col-sm-8">
                            <input id="week_limit"  name="week_limit" class="form-control" type="number" >
                            <span class="help-block m-b-none">范围(1-25)，0 表示不限制。</span>
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">每月呼叫次数：</label>
                        <div class="col-sm-8">
                            <input id="month_limit"  name="month_limit" class="form-control" type="number" >
                            <span class="help-block m-b-none">范围(1-50)，0 表示不限制。</span>

                        </div>
                        <input type="hidden"   name="id" id="cid" value="" >
                        <input type="hidden" id="lsid"  name="sid" value="" >
                        <input type="hidden" id="ltoken"  name="token" value="" >

                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="onLimit()">保存</button>
                </center>
            </div>
        </div>
    </div>
</div>




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

<script src="/public/static/js/call/call.js"></script>
