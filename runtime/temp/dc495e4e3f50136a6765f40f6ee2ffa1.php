<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:96:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/articles/index.html";i:1553149895;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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

                        <h5>
                            <a class="btn btn-white btn-sm" href="<?php echo url('/index/Articles/addArticle'); ?>" ><i class="fa fa-plus"></i> 添加资讯文章</a>
                            <div class="btn-group search">
                                <button class="btn btn-white btn-sm" type="button" ><i class="fa fa-search"></i> 高级搜索</button>
                                <div class="dropdown-menu">
                                    <form class="form-horizontal" id="signupForm" action="javascript:search_query()">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">资讯标题</label>
                                                <div class="col-sm-9">
                                                <input type="text" placeholder="资讯标题名称" class="form-control" name="title" value="<?php echo \think\Request::instance()->get('title'); ?>"/>
                                                </div>
                                                </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">是否发布</label>
                                                <div class="col-sm-9">
                                                    <div class="radio radio-info radio-inline">
                                                        <input type="radio" id="inlineRadio1" value="1" name="state" <?php if(\think\Request::instance()->get('state') == 1): ?>checked<?php endif; ?>>
                                                        <label for="inlineRadio1">已发布</label>
                                                    </div>
                                                    <div class="radio radio-inline">
                                                        <input type="radio" id="inlineRadio2" value="2" name="state" <?php if(\think\Request::instance()->get('state') == 2): ?>checked<?php endif; ?>>
                                                        <label for="inlineRadio2">未发布</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">时间范围选择</label>
                                                <div class="col-sm-9">
                                                <div class="input-daterange input-group">
                                                    <input type="text" class="input-sm form-control times" name="start"   placeholder="起始时间"  readonly="true" value="<?php echo \think\Request::instance()->get('start'); ?>" />
                                                    <span class="input-group-addon">到</span>
                                                    <input type="text" class="input-sm form-control times" name="end"   placeholder="结束时间"  readonly="true" value="<?php echo \think\Request::instance()->get('end'); ?>" />
                                                </div>
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

                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>资讯标题</th>
                                <th>类型</th>
                                <th>权重</th>
                                <th>状态</th>
                                <th>添加时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($article) || $article instanceof \think\Collection || $article instanceof \think\Paginator): $k = 0; $__LIST__ = $article;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                            <tr>
                                <td><?php echo $k; ?></td>
                                <td><?php echo $vo['title']; ?></td>
                                <td><?php echo $vo['name']; ?></td>
                                <td><?php echo $vo['weight']; ?></td>
                                <td>
                                    <?php if($vo['state'] == 1): ?>
                                        已发布
                                    <?php elseif($vo['state'] == 2): ?>
                                        未发布
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $vo['create_time']; ?></td>
                                <td>
                                    <a href="/index/Articles/editArticle?id=<?php echo $vo['id']; ?>"  class="btn btn-success btn-xs">编辑</a>
                                    <a onclick="delArticle('<?php echo $vo['id']; ?>')"  class="btn btn-danger btn-xs">删除</a>
                                </td>
                            </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                        <!-- 分页 -->
                        <div class="fixed-table-pagination" style="display: block;">
                            <div class="pull-left pagination-detail">
                                <span class="pagination-info">总共<?php echo $article->total(); ?>条记录</span>
                            </div>
                            <div align="center" >

                                <?php echo $article->render(); ?>
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


<script type="text/javascript" src="/public/static//js/articles/article.js"></script>


