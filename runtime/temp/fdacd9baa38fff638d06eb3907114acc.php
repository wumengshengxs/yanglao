<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:102:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/articles/add_article.html";i:1548152570;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
    <!-- 权限菜单 -->
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>
                        <a href="javascript:history.go(-1)">
                            健康资讯列表&nbsp;&nbsp;<i class='fa fa-angle-double-right'></i>
                        </a>
                        <label>资讯文章</label>
                    </h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal" id="signupForm" >


                        <div class="form-group">
                            <label class="col-sm-2 control-label">资讯标题</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder='请输入资讯标题' name='title'  value="<?php echo $article['title']; ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">资讯类型</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-md-4">
                                        <select class="form-control m-b" name="gid" >
                                            <option value="" >请选择</option>

                                            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vos): $mod = ($i % 2 );++$i;?>
                                            <option value="<?php echo $vos['id']; ?>" disabled><?php echo $vos['name']; ?></option>
                                                <?php if(is_array($vos['nodes']) || $vos['nodes'] instanceof \think\Collection || $vos['nodes'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vos['nodes'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                                    <option value="<?php echo $vo['id']; ?>" <?php if($article['gid'] == $vo['id']): ?>selected<?php endif; ?> > ∟<?php echo $vo['name']; ?></option>
                                                <?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">排序</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="number" class="form-control" placeholder='排序越大越靠前' name='weight'  value="<?php echo $article['weight']; ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">是否发布</label>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="state" <?php if($article['state'] == 1 or $article['state'] == 0): ?>checked<?php endif; ?>>
                                            <label for="inlineRadio1">立即发布</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="state" <?php if($article['state'] == 2): ?>checked<?php endif; ?>>
                                            <label for="inlineRadio2">暂不发布</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">内容</label>
                            <div class="col-sm-7">
                                <script id="editor" name="content" type="text/plain" style="width:100%;height:300px;"><?php echo $article['content']; ?></script></div>

                                </div>

                        <input type="hidden" name="id" value="<?php echo $article['id']; ?>">

                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-primary"  onclick="onArticle()">保存</a>
                            </div>
                        </div>
                    </form>

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

<script type="text/javascript" charset="utf-8" src="/public/static/js/umeditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/public/static/js/umeditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/public/static/js/umeditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" charset="utf-8" src="/public/static/js/umeditor/third-party/codemirror/codemirror.js"></script>
<script type="text/javascript" charset="utf-8" src="/public/static/js/umeditor/third-party/zeroclipboard/ZeroClipboard.js"></script>

<script type="text/javascript" src="/public/static//js/articles/article.js"></script>
<script>
    var um = UE.getEditor('editor',{
        toolbars: [
            ['fullscreen', 'source', 'undo', 'redo'],
            ['bold', 'italic','justifyjustify','justifyleft','justifyright','justifycenter', 'underline', 'fontborder','simpleupload','link','emotion','spechars','forecolor',  'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc']
        ]
    });
</script>






