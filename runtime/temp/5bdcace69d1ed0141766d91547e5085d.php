<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:94:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/server/view/index/index.html";i:1552961031;s:94:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/server/view/public/head.html";i:1552612121;s:94:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/server/view/public/foot.html";i:1552612121;}*/ ?>
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
<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
<div class="row border-bottom" style="background-color: white;">
    <nav class="navbar m-xs">
        <div class="col-sm-6">
            <img class="m-l-sm" src="http://fengxian.emailuo.com/Public/dist/static/img/LOGO_fengxianminzheng.7e8c1474c414106f1c1a473a26564c83.png" alt="">
        </div>
        <div class="col-sm-6">
            <audio controls id="audioMp3" src="/public/static/mp3/7519.wav" style="display: none;"></audio>
            <button  onclick="playMusic();" id="btn" style="display: none;">loading</button>
            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle count-info">
                        服务商 &nbsp;<span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts no-padding">
                        <li>
                            <a href="javascript:logout();">安全退出</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>
<div id="wrapper">
    <!--左侧导航开始-->
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close"><i class="fa fa-times-circle"></i>
        </div>
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <?php if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$f): $mod = ($i % 2 );++$i;?>
                <li>
                    <a href="<?php echo $f['url']; ?>"><i class="<?php echo $f['icon']; ?>"></i><span class="nav-label"><?php echo $f['name']; ?></span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <?php if(is_array($f['nodes']) || $f['nodes'] instanceof \think\Collection || $f['nodes'] instanceof \think\Paginator): $i = 0; $__LIST__ = $f['nodes'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$s): $mod = ($i % 2 );++$i;?>
                        <li>
                            <a class="J_menuItem" href="<?php echo $s['url']; ?>" data-index="0"><?php echo $s['name']; ?></a>
                        </li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    </nav>
    <!--左侧导航结束-->
    <!--右侧部分开始-->
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row content-tabs">
            <button class="roll-nav roll-left J_tabLeft navbar-minimalize" style="left: 0px;" title="菜单"><i class="fa fa-bars"></i></button>
            <button class="roll-nav roll-left J_tabLeft" style="left: 40px;"><i class="fa fa-backward"></i></button>
            <nav class="page-tabs J_menuTabs" style="margin-left: 80px;">
                <div class="page-tabs-content">
                    <a href="javascript:;" class="active J_menuTab" data-id="/server/Index/main">首页</a>
                </div>
            </nav>
            <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i></button>
            <div class="btn-group roll-nav roll-right">
                <button class="dropdown J_tabClose" data-toggle="dropdown">关闭操作<span class="caret"></span></button>
                <ul role="menu" class="dropdown-menu dropdown-menu-right">
                    <li class="J_tabShowActive"><a>定位当前选项卡</a></li>
                    <li class="divider"></li>
                    <li class="J_tabCloseAll"><a>关闭全部选项卡</a></li>
                    <li class="J_tabCloseOther"><a>关闭其他选项卡</a></li>
                </ul>
            </div>
        </div>
        <div class="row J_mainContent" id="content-main">
            <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src='/server/Index/main' frameborder="0" data-id="/server/Index/main"></iframe>
        </div>
    </div>
    <!--右侧部分结束-->
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

<script src="/public/static/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/public/static/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="/public/static/js/hplus.min.js?v=4.0.0"></script>
<script src="/public/static/js/server/index.js"></script>
<script type="text/javascript" src="/public/static/js/contabs.min.js"></script>
<script src="/public/static/js/plugins/pace/pace.min.js"></script>
</body>
<script type="text/javascript">

    $('.dropdown').mouseover(function(){
        $(this).find('.dropdown-menu').show();
    }).mouseout(function(){
        $(this).find('.dropdown-menu').hide();
    });

</script>
</html>