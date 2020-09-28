<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:103:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/qualitys/back_quality.html";i:1550208633;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1548228499;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1548228499;}*/ ?>
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

                        <div class="ibox-title">
                            <!--搜索页面 -->
                            <h5>
                                <div class="btn-group search">
                                    <button class="btn btn-white btn-sm" type="button" ><i class="fa fa-search"></i> 高级搜索</button>
                                    <div class="dropdown-menu">
                                        <form class="form-horizontal" id="signupForm" action="javascript:search_back()">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">工单办结时间</label>
                                                    <div class="col-sm-9">
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="input-sm form-control times" name="start"   id="start" placeholder="起始时间"  readonly="true" value="<?php echo \think\Request::instance()->get('start'); ?>" />
                                                        <span class="input-group-addon">到</span>
                                                        <input type="text" class="input-sm form-control times" name="end" id="end" placeholder="结束时间"  readonly="true" value="<?php echo \think\Request::instance()->get('end'); ?>" />
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">工单类型</label>
                                                    <div class="col-sm-9">
                                                    <select class="form-control selectpicker" name="work_type">
                                                        <option value="" <?php if(\think\Request::instance()->get('work_type') === ''): ?>selected<?php endif; ?>>请选择</option>
                                                        <option value="0" <?php if(\think\Request::instance()->get('work_type') === 0): ?>selected<?php endif; ?>>其他</option>
                                                        <option value='1' <?php if(\think\Request::instance()->get('work_type') === 1): ?>selected<?php endif; ?>>SOS报警</option>
                                                        <option value='2' <?php if(\think\Request::instance()->get('work_type') == 2): ?>selected<?php endif; ?>>越界报警</option>
                                                        <option value='3' <?php if(\think\Request::instance()->get('work_type') == 3): ?>selected<?php endif; ?>>心率报警</option>
                                                        <option value='4' <?php if(\think\Request::instance()->get('work_type') == 4): ?>selected<?php endif; ?>>主动关爱</option>
                                                    </select>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">服务对象名称</label>
                                                    <div class="col-sm-9">
                                                    <input type="text" placeholder="服务对象名称" class="form-control" name="name" value="<?php echo \think\Request::instance()->get('name'); ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="hr-line-dashed"></div>
                                            <div class="form-group">
                                                <div class="col-sm-3 col-sm-offset-2">
                                                    <button class="btn btn-primary" type="submit">搜索</button>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </h5>
                        </div>

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>工单编号</th>
                                <th>工单退回时间</th>
                                <th>工单办结时间</th>
                                <th>服务对象</th>
                                <th>工单类型</th>
                                <th>受理时长</th>
                                <th>质检结果</th>
                                <th>话务员</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($quality) || $quality instanceof \think\Collection || $quality instanceof \think\Paginator): $k = 0; $__LIST__ = $quality;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                            <tr>
                                <td><?php echo $k; ?></td>
                                <td><?php echo $vo['wid']; ?></td>
                                <td><?php echo $vo['create_time']; ?></td>
                                <td><?php echo date('Y-m-d H:i:s',$vo['end_time']); ?></td>
                                <td><?php echo $vo['name']; ?></td>
                                <td><?php echo $vo['work_type']; ?></td>
                                <td><?php echo $vo['time']; ?></td>
                                <td>退回工单</td>
                                <td><?php echo $vo['display_name']; ?></td>
                                <td>
                                    <a href="/index/Qualitys/QualityInfo?id=<?php echo $vo['wid']; ?>"  class="btn btn-success btn-xs">重新质检</a>
                                </td>
                            </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                        <!-- 分页 -->
                        <div class="fixed-table-pagination" style="display: block;">
                            <div class="pull-left pagination-detail">
                                <span class="pagination-info">总共<?php echo $quality->total(); ?>条记录</span>
                            </div>
                            <div align="center" >

                                <?php echo $quality->render(); ?>
                            </div>

                        </div>
                        <!-- 分页 -->

                    </div>
            </div>
        </div>
    </div>
</div>


</body>

</html>

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

<script src="/public/static/js/work/work.js"></script>

