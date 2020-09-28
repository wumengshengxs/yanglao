<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:95:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/provider/work.html";i:1554970556;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
                        <a class="btn btn-white " href="<?php echo url('/index/provider/addWork'); ?>"   ><i class="fa fa-plus"></i>添加工单</a>
                        <div class="btn-group search">
                            <button class="btn btn-white" type="button"><i class="fa fa-search"></i> 高级搜索</button>
                            <div class="dropdown-menu">
                                <form class="form-horizontal" action="/index/provider/work" method="get">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">服务对象名称</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="name" value="<?php echo \think\Request::instance()->get('name'); ?>"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">工单标题</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="title" value="<?php echo \think\Request::instance()->get('title'); ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">服务地址</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="site" value="<?php echo \think\Request::instance()->get('site'); ?>"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">服务商</label>
                                        <div class="col-sm-9">
                                            <select class="form-control m-b" name="pid" >
                                                <option value=''>请选择</option >
                                                <?php if(is_array($provider) || $provider instanceof \think\Collection || $provider instanceof \think\Paginator): $i = 0; $__LIST__ = $provider;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                                <option value='<?php echo $vo['id']; ?>' <?php if(\think\Request::instance()->get('pid') == $vo['id']): ?> selected<?php endif; ?> ><?php echo $vo['company']; ?></option >
                                                <?php endforeach; endif; else: echo "" ;endif; ?>
                                            </select>
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
                                        <label class="col-sm-3 control-label">服务人员</label>
                                        <div class="col-sm-9">
                                            <select class="form-control m-b" name="sid" >
                                                <option value=''>请选择</option >

                                                <?php if(is_array($staff) || $staff instanceof \think\Collection || $staff instanceof \think\Paginator): $i = 0; $__LIST__ = $staff;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                                <option value='<?php echo $vo['id']; ?>' <?php if(\think\Request::instance()->get('sid') == $vo['id']): ?> selected<?php endif; ?>  ><?php echo $vo['name']; ?></option >
                                                <?php endforeach; endif; else: echo "" ;endif; ?>
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
                            <th>工单编号</th>
                            <th>所属服务商</th>
                            <th>工单标题</th>
                            <th>服务地址</th>
                            <th>服务类型</th>
                            <th>消费金额</th>
                            <th>服务对象</th>
                            <th>上门人员</th>
                            <th>工单状态</th>
                            <th>创建时间</th>
                            <th>结束时间</th>
                            <th>工单明细</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($work) || $work instanceof \think\Collection || $work instanceof \think\Paginator): $k = 0; $__LIST__ = $work;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                        <tr>
                            <td><?php echo $k; ?></td>
                            <td>YC<?php echo $vo['id']; ?></td>
                            <td><?php echo $vo['company']; ?></td>
                            <td><?php echo $vo['title']; ?></td>
                            <td><?php echo $vo['site']; ?></td>
                            <td><?php echo $vo['status']; ?></td>
                            <td><?php echo $vo['money']; ?></td>
                            <td><?php echo $vo['cname']; ?></td>
                            <td>
                                <?php if($vo['sname']): ?>
                                <?php echo $vo['sname']; else: ?>
                                暂无
                                <?php endif; ?>

                            </td>
                            <td><?php echo $vo['state']; ?></td>
                            <td><?php echo $vo['create_time']; ?></td>
                            <td>
                                <?php if($vo['end_time'] == 0): ?>
                                暂无
                                <?php else: ?>
                                <?php echo date('Y-m-d H:i:s',$vo['end_time']); endif; ?>
                            </td>
                            <td>
                                <a href="/index/provider/workDetails?id=<?php echo $vo['id']; ?>">查看</a>
                               </td>

                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                    <div class="page"><?php echo $work->render(); ?></div>
                    <div class="text-center">总共 <?php echo $work->total(); ?> 条数据</div>
                </div>
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
