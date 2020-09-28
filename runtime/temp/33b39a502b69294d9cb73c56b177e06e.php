<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/staff/staff.html";i:1551665224;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
                    <div class="ibox-title" >
                        <button class="btn btn-white btn-sm"  data-target="#myModal4" data-toggle="modal" ><i class="fa fa-plus"></i> 添加话务员</button>
                    </div>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>名称</th>
                            <th>电话号码</th>
                            <th>所属角色</th>
                            <th>登陆名称</th>
                            <th>绑定工号</th>
                            <th>分机号</th>
                            <th>最后登陆时间</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($staff['data']) || $staff['data'] instanceof \think\Collection || $staff['data'] instanceof \think\Paginator): $k = 0; $__LIST__ = $staff['data'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                        <tr>
                            <td><?php echo $k; ?></td>
                            <td><?php echo $vo['display_name']; ?></td>
                            <td><?php echo $vo['phone']; ?></td>
                            <td>话务员</td>
                            <td><?php echo $vo['number']; ?></td>
                            <td><?php echo $vo['work_number']; ?></td>
                            <td><?php echo $vo['number']; ?></td>
                            <td>
                                <?php if($vo['last_time'] != false): ?>
                                <?php echo date('Y-m-d H:i:s',$vo['last_time']); else: ?>
                                --
                                <?php endif; ?>
                            </td>
                            <td><?php echo $vo['create_time']; ?></td>
                            <td>
                                <button type="button" onclick="showUser('<?php echo $vo['number']; ?>')" class="btn btn-white  btn-xs">查看</button>
                                <button type="button" data-toggle="modal" data-target="#myModal5" onclick="editUser('<?php echo $vo['number']; ?>')" class="btn btn-success   btn-xs">编辑</button>
                            </td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                    <div class="page"><?php echo $page; ?></div>
                    <div class="text-center">总共 <?php echo $staff['total']; ?> 条数据</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal" id="myModal4" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4 >添加话务员</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t" id="forms">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">名称：</label>
                        <div class="col-sm-8">
                            <input  name="display_name" class="form-control" type="text" maxlength="16">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">密码：</label>
                        <div class="col-sm-8">
                            <input name="password" class="form-control" type="password" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">联系电话：</label>
                        <div class="col-sm-8">
                            <input name="phone" class="form-control" type="text"  maxlength="16">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">角色权限：</label>
                        <div class="col-sm-8">
                            <select class="form-control m-b" name="rid" >
                                <option value="4">话务员</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">技能组名称：</label>
                        <div class="col-sm-8">
                            <select class="form-control m-b" name="gid" >
                                <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                <option value='<?php echo $vo['gid']; ?>'  ><?php echo $vo['name']; ?></option >
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">呼叫中心工号：</label>
                        <div class="col-sm-8">
                            <input  name="work_number" class="form-control" type="text" readonly="readonly" value="YC<?php echo $number; ?>">
                        </div>
                    </div>

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
                <h4 >编辑话务员</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t" id="forms2">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">名称：</label>
                        <div class="col-sm-8">
                            <input id="display_name" name="display_name" class="form-control" type="text" maxlength="16">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">密码：</label>
                        <div class="col-sm-8">
                            <input id="password" name="password" class="form-control" type="password" placeholder="******" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">联系电话：</label>
                        <div class="col-sm-8">
                            <input id="phone" name="phone" class="form-control" type="text"  maxlength="16">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">角色权限：</label>
                        <div class="col-sm-8">
                            <select class="form-control m-b" name="rid" >
                                <option value="4">话务员</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">技能组名称：</label>
                        <div class="col-sm-8">
                            <select class="form-control m-b" name="gid" id="list">
                                <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                <option value='<?php echo $vo['gid']; ?>'  ><?php echo $vo['name']; ?></option >
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">呼叫中心工号：</label>
                        <div class="col-sm-8">
                            <input id="work_number"  class="form-control" type="text" readonly="readonly" value="">
                        </div>
                    </div>
                    <input type="hidden" id="ids"  name="number" value="" >
                </form>
            </div>
            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="saveSublimt()">保存</button>
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

<script src="/public/static/js/staff/staff.js"></script>
