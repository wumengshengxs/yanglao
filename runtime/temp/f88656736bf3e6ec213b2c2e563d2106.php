<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:92:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/works/work.html";i:1550218609;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1548228499;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1548228499;}*/ ?>
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
                <div class="ibox-content text-center">
                    <div class="col-sm-2">
                        <p>未开始</p>
                        <h2><?php echo (isset($count['zero']) && ($count['zero'] !== '')?$count['zero']:0); ?></h2>
                    </div>
                    <div class="col-sm-2">
                        <p class="">进行中</p>
                        <h2><?php echo (isset($count['one']) && ($count['one'] !== '')?$count['one']:0); ?></h2>
                    </div>
                    <div class="col-sm-2">
                        <p>按期完成</p>
                        <h2><?php echo (isset($count['two']) && ($count['two'] !== '')?$count['two']:0); ?></h2>
                    </div>
                    <div class="col-sm-2">
                        <p>延期完成</p>
                        <h2><?php echo (isset($count['three']) && ($count['three'] !== '')?$count['three']:0); ?></h2>
                    </div>
                    <div class="col-sm-2">
                        <p>已逾期</p>
                        <h2><?php echo (isset($count['four']) && ($count['four'] !== '')?$count['four']:0); ?></h2>
                    </div>
                    <div class="col-sm-2">
                        <p>全部计划工单</p>
                        <h2><?php echo $count['count']; ?></h2>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="ibox-title">
                        <!--搜索页面 -->
                        <h5>
                            <div class="btn-group search">
                                <button class="btn btn-white btn-sm" type="button" ><i class="fa fa-search"></i> 高级搜索</button>
                                <div class="dropdown-menu">
                                    <form class="form-horizontal" id="signupForm" action="javascript:search_query()">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">范围选择</label>
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
                                                <label class="col-sm-3 control-label">工单状态</label>
                                                <div class="col-sm-9">
                                                    <select class="form-control selectpicker" name="type">
                                                        <option value="" <?php if(\think\Request::instance()->get('type') === null): ?>selected<?php endif; ?>>全部</option>
                                                        <option value='0' <?php if(\think\Request::instance()->get('type') === '0'): ?>selected<?php endif; ?>>未开始</option>
                                                        <option value='1' <?php if(\think\Request::instance()->get('type') == 1): ?>selected<?php endif; ?>>进行中</option>
                                                        <option value='2' <?php if(\think\Request::instance()->get('type') == 2): ?>selected<?php endif; ?>>按期完成</option>
                                                        <option value='3' <?php if(\think\Request::instance()->get('type') == 3): ?>selected<?php endif; ?>>延期完成</option>
                                                        <option value='4' <?php if(\think\Request::instance()->get('type') == 4): ?>selected<?php endif; ?>>已逾期</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">服务对象姓名</label>
                                                <div class="col-sm-9">
                                                    <input type="text" placeholder="请输入服务对象姓名" class="form-control" name="name" value="<?php echo \think\Request::instance()->get('name'); ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" id="state" name="status" value="0">

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
                        <div style="float: right">
                            <button class="btn btn-default btn-sm" onclick="getExcel()">
                                <i class="fa fa-sticky-note-o"></i>
                                导出报表
                            </button>
                        </div>
                    </div>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>编号</th>
                            <th>所属计划组</th>
                            <th>计划创建时间</th>
                            <th>计划开始时间</th>
                            <th>计划截止时间</th>
                            <th>计划完成时间</th>
                            <th>服务对象</th>
                            <th>计划状态</th>
                            <th>所属话务员</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($work) || $work instanceof \think\Collection || $work instanceof \think\Paginator): $k = 0; $__LIST__ = $work;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                        <tr>
                            <td><?php echo $k; ?></td>
                            <td><?php echo $vo['id']; ?></td>
                            <td><?php echo $vo['pname']; ?></td>
                            <td><?php echo $vo['create_time']; ?></td>
                            <td><?php echo date('Y-m-d H:i:s',$vo['start']); ?></td>
                            <td><?php echo date('Y-m-d H:i:s',$vo['end']); ?></td>
                            <td>
                                <?php if($vo['end_time'] != 0): ?>
                                <?php echo date('Y-m-d H:i:s',$vo['end_time']); else: ?>
                                ------
                                <?php endif; ?>
                            </td>
                            <td><?php echo $vo['uname']; ?></td>
                            <td>
                                <?php if($vo['type'] == 0): ?>
                                未开始
                                <?php elseif($vo['type'] == 1): ?>
                                进行中
                                <?php elseif($vo['type'] == 2): ?>
                                按期完成
                                <?php elseif($vo['type'] == 3): ?>
                                延期完成
                                <?php elseif($vo['type'] == 4): ?>
                                已逾期
                                <?php endif; ?>
                            </td>

                            <td><?php echo $vo['sname']; ?></td>
                            <td>
                                <?php if($vo['state'] == 3 or $vo['state'] == 4 or $vo['state'] == 5 or $vo['state'] == 6): ?>
                                <a href="/index/works/workInfo?id=<?php echo $vo['id']; ?>"  class="btn btn-success btn-xs">查看</a>

                                <?php else: ?>
                                <a href="/index/works/workInfo?id=<?php echo $vo['id']; ?>" class="btn btn-success btn-xs">查看</a>
                                <?php if($vo['state'] == 2): ?>
                                <a href="/index/works/workInfo?id=<?php echo $vo['id']; ?>" class="btn btn-success btn-xs">继续受理</a>
                                <?php else: ?>
                                <a href="/index/works/workInfo?id=<?php echo $vo['id']; ?>" class="btn btn-success btn-xs">受理</a>
                                <?php endif; ?>
                                <button type="button" onclick="changeWork('<?php echo $vo['id']; ?>')" data-toggle="modal" data-target="#myModal5" class="btn btn-success btn-xs">转交</button>
                                <button type="button" onclick="delWork('<?php echo $vo['id']; ?>')" class="btn btn-success btn-xs">关闭</button>
                                <?php endif; if($vo['state'] == 5): ?>
                                <a href="/index/works/workInfo?id=<?php echo $vo['id']; ?>" class="btn btn-success btn-xs">已退回</a>

                                <?php endif; if($vo['state'] == 6): ?>
                                <a href="/index/works/workInfo?id=<?php echo $vo['id']; ?>" class="btn btn-success btn-xs">已通过</a>

                                <?php endif; ?>


                            </td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                    <!-- 分页 -->
                    <div class="fixed-table-pagination" style="display: block;">
                        <div class="pull-left pagination-detail">
                            <span class="pagination-info">总共<?php echo $work->total(); ?>条记录</span>
                        </div>

                        <div align="center" >

                            <?php echo $work->render(); ?>
                        </div>

                    </div>
                    <!-- 分页 -->
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal inmodal" id="myModal5" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4 >转交工单到 </h4>
            </div>

            <div class="modal-body">
                <form class="form-horizontal m-t" id="forms2">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">话务员*</label>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-md-10">
                                    <?php if(is_array($staff) || $staff instanceof \think\Collection || $staff instanceof \think\Paginator): $i = 0; $__LIST__ = $staff;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vos): $mod = ($i % 2 );++$i;?>
                                    <div class="radio radio-info radio-inline">
                                        <input type="radio" id="inlineRadio<?php echo $vos['number']; ?>" value="<?php echo $vos['number']; ?>" name="sid" >
                                        <label for="inlineRadio<?php echo $vos['number']; ?>"><?php echo $vos['display_name']; ?></label>
                                    </div>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="ids"  name="id" value="" >
                </form>
            </div>
            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="saveSublimt()">确定</button>
                </center>
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



