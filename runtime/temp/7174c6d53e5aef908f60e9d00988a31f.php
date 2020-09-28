<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:94:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/server/view/staff/index.html";i:1556087583;s:94:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/server/view/public/head.html";i:1552612121;s:94:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/server/view/public/foot.html";i:1552612121;}*/ ?>
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
                        <button class="btn btn-white " type="button" data-target="#myModal1" data-toggle="modal" ><i class="fa fa-plus"></i>添加人员</button>
                        <div class="btn-group search">
                            <button class="btn btn-white" type="button"><i class="fa fa-search"></i> 高级搜索</button>
                            <div class="dropdown-menu">
                                <form class="form-horizontal" action="/server/Staff/index" method="get">


                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">账号</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="mobile" value="<?php echo \think\Request::instance()->get('mobile'); ?>"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">姓名</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="name" value="<?php echo \think\Request::instance()->get('name'); ?>"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">服务类型</label>
                                        <div class="col-sm-9">
                                            <select class="form-control m-b" name="status" >
                                                <option value=''>请选择</option >
                                                <?php if(is_array($project) || $project instanceof \think\Collection || $project instanceof \think\Paginator): $i = 0; $__LIST__ = $project;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                                <option value='<?php echo $vo['id']; ?>' <?php if(\think\Request::instance()->get('status') == $vo['id']): ?> selected<?php endif; ?> ><?php echo $vo['name']; ?></option >
                                                <?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">状态</label>
                                        <div class="col-sm-9">
                                            <select class="form-control m-b" name="state" >
                                                <option value=''>请选择</option >
                                                <option value='1' <?php if(\think\Request::instance()->get('state') == 1): ?> selected<?php endif; ?> >正常</option >
                                                <option value='2' <?php if(\think\Request::instance()->get('state') == 2): ?> selected<?php endif; ?> >关闭</option >
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">创建时间</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="start_create" value="<?php echo \think\Request::instance()->get('start_create'); ?>" class="form-control times">
                                        </div>
                                        <div class="col-sm-1 middle-div">至</div>
                                        <div class="col-sm-4 f-right">
                                            <input type="text" name="end_create" value="<?php echo \think\Request::instance()->get('end_create'); ?>" class="form-control times">
                                        </div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-3">
                                            <button class="btn btn-primary" type="submit">搜索</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>用户ID</th>
                            <th>用户名</th>
                            <th>账号</th>
                            <th>状态</th>
                            <th>人员类型</th>
                            <th>当月结算金额</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($staff) || $staff instanceof \think\Collection || $staff instanceof \think\Paginator): $k = 0; $__LIST__ = $staff;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                        <tr>
                            <td><?php echo $k; ?></td>
                            <td><?php echo $vo['id']; ?></td>
                            <td><?php echo $vo['name']; ?></td>
                            <td><?php echo $vo['mobile']; ?></td>
                            <td><?php echo $vo['state']; ?></td>
                            <td><?php echo $vo['status']; ?></td>
                            <td><?php echo $vo['money']; ?></td>
                            <td><?php echo $vo['create_time']; ?></td>
                            <td>
                                <a  data-toggle="modal" data-target="#myModal2" onclick="editStaff('<?php echo $vo['id']; ?>')" >编辑</a>

                            </td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                    <div class="page"><?php echo $staff->render(); ?></div>
                    <div class="text-center">总共 <?php echo $staff->total(); ?> 条数据</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal" id="myModal1" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4 >添加服务人员</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t" id="forms">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">*姓名：</label>
                        <div class="col-sm-8">
                            <input  name="name" class="form-control" type="text"  maxlength="16">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">*人员账号：</label>
                        <div class="col-sm-8">
                            <input  name="mobile" class="form-control" type="text"  maxlength="11">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">*登录密码：</label>
                        <div class="col-sm-8">
                            <input  name="password" class="form-control" type="password"  maxlength="32">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">人员类型：</label>
                        <div class="col-sm-8">
                            <select class="form-control m-b" name="status" >
                                <?php if(is_array($project) || $project instanceof \think\Collection || $project instanceof \think\Paginator): $i = 0; $__LIST__ = $project;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                <option value='<?php echo $vo['id']; ?>'  ><?php echo $vo['name']; ?></option >
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">状态：</label>
                        <div class="radio radio-info radio-inline">
                            <input type="radio"  id="state1" value="1" name="state" checked="" >
                            <label for="state1">正常</label>
                        </div>
                        <div class="radio radio-inline">
                            <input type="radio" id="state0"  value="2" name="state">
                            <label for="state0"> 关闭</label>
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
<div class="modal inmodal" id="myModal2" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4 >编辑服务人员</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t" id="forms1">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">*姓名：</label>
                        <div class="col-sm-8">
                            <input  name="name" class="form-control" type="text"  maxlength="16" id="name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">*人员账号：</label>
                        <div class="col-sm-8">
                            <input  name="mobile" class="form-control" type="text"  maxlength="11" id="mobile">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">*登录密码：</label>
                        <div class="col-sm-8">
                            <input  name="password" class="form-control" type="password"  maxlength="32" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">人员类型：</label>
                        <div class="col-sm-8">
                            <select class="form-control m-b" name="status" id="provider" >

                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">状态：</label>
                        <div class="radio radio-info radio-inline">
                            <input type="radio" id="status1" value="1" name="state"  >
                            <label for="status1">正常</label>
                        </div>
                        <div class="radio radio-inline">
                            <input type="radio" id="status0" value="2" name="state" >
                            <label for="status0"> 关闭</label>
                        </div>
                    </div>
                    <input type="hidden" id="ids" name="id" >
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

<script src="/public/static/js/server/staff.js"></script>
