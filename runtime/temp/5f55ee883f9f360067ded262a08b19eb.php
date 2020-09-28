<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/index/index.html";i:1565774362;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
            <!-- <img class="m-l-sm" src="/public/static/img/kangqaio-logo.png" alt=""> -->
            <!-- <img class="m-l-sm" src="/public/static/img/yunchi.png" alt=""> -->
            <!-- <img class="m-l-sm" src="/public/static/img/unicom.png" alt=""> -->
        </div>
        <div class="col-sm-6">
            <audio controls id="audioMp3" src="/public/static/mp3/7519.wav" style="display: none;"></audio>
            <button  onclick="playMusic();"  style="display: none;">loading</button>
            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle count-info warninf-info">
                        <i class="fa fa-bell"></i><span class="label label-primary">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="javascript:;save_mess('sos')">
                                <div>
                                    <i class="icon-icon fa fa-phone fa-fw"></i> SOS报警
                                    <span class="pull-right text-muted small" id='sos'>0</span>
                                    <span class="save_sos" style="display: none;"></span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="javascript:;save_mess('heart')">
                                <div>
                                    <i class="icon-icon fa fa-heartbeat fa-fw"></i> 心率报警
                                    <span class="pull-right text-muted small" id='heart'>0</span>
                                    <span class="save_heart" style="display: none;"></span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="javascript:;save_mess('gps')">
                                <div>
                                    <i class="icon-icon fa fa-map-marker fa-fw"></i> 越界报警
                                    <span class="pull-right text-muted small" id='gps'>0</span>
                                    <span class="save_gps" style="display: none;"></span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;save_mess('fuel')">
                                <div>
                                    <i class="icon-icon fa fa-warning fa-fw"></i> 燃气报警
                                    <span class="pull-right text-muted small" id='fuel'>0</span>
                                    <span class="save_fuel" style="display: none;"></span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="javascript:;save_mess('smoke')">
                                <div>
                                    <i class="icon-icon fa fa-warning fa-fw"></i>烟感报警
                                    <span class="pull-right text-muted small" id='smoke'>0</span>
                                    <span class="save_smoke" style="display: none;"></span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="javascript:;save_mess('infrared')">
                                <div>
                                    <i class="icon-icon fa fa-warning fa-fw"></i> 红外报警
                                    <span class="pull-right text-muted small" id='infrared'>0</span>
                                    <span class="save_infrared" style="display: none;"></span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info">
                    您好:
                    <?php
                        if($_SESSION['think']['S_USER_INFO']["type"]==1){
                            echo $_SESSION['think']['S_USER_INFO']["name"];
                        }else{
                            echo $_SESSION['think']['S_USER_INFO']["work_number"];
                        }
                    ?>
                    &nbsp;<span class="caret"></span>
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
                    <?php if(!(empty($f['nodes']) || (($f['nodes'] instanceof \think\Collection || $f['nodes'] instanceof \think\Paginator ) && $f['nodes']->isEmpty()))): ?>
                    <a href="<?php echo $f['url']; ?>"><i class="<?php echo $f['icon']; ?>"></i><span class="nav-label"><?php echo $f['name']; ?></span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <?php if(is_array($f['nodes']) || $f['nodes'] instanceof \think\Collection || $f['nodes'] instanceof \think\Paginator): $i = 0; $__LIST__ = $f['nodes'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$s): $mod = ($i % 2 );++$i;?>
                        <li>
                            <a class="J_menuItem" href="<?php echo $s['url']; ?>" data-index="0"><?php echo $s['name']; ?></a>
                        </li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                    <?php else: ?>
                        <a <?php if($f['name'] == '大屏信息'): ?> target="_blank" <?php else: ?> class="J_menuItem" <?php endif; ?>  href="<?php echo $f['url']; ?>">
                            <i class="<?php echo $f['icon']; ?>"></i><span class="nav-label"><?php echo $f['name']; ?></span>
                        </a>
                    <?php endif; ?>
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
                    <a href="javascript:;" class="active J_menuTab" data-id="/index/Index/main">首页</a>
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
            <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src='/index/Index/main' frameborder="0" data-id="/index/Index/main"></iframe>
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
<script type="text/javascript" src="/public/static/js/contabs.min.js"></script>
<script src="/public/static/js/plugins/pace/pace.min.js"></script>
</body>
<script type="text/javascript">
    var uid = "<?php echo session('S_USER_INFO.id'); ?>";
    //这里是定义全局的未读消息数量
    window.glo_number = 0;
    $(function(){
        //初始化查询未读消息
        messgae();
    });
    $('.dropdown').mouseover(function(){
        $(this).find('.dropdown-menu').show();
    }).mouseout(function(){
        $(this).find('.dropdown-menu').hide();
    });

    //7273的端口是科强手环专用
    ws = new WebSocket("ws://101.89.115.24:7273");
    ws.onopen = function() {
        ws.send('@B#@|'+uid+'|WES|@E#@');
    };
    //服务器推送消息格式为json
    ws.onmessage = function(e) {
        console.log(e.data);
        /*
        *Chrome的autoplay政策在2018年4月做了更改。
        *新的行为：浏览器为了提高用户体验，减少数据消耗，现在都在遵循autoplay政策，Chrome的autoplay 政策非常简单
        *1. muted autoplay始终被允许
        *2. 音乐的autoplay 只有在下面集中情况下起作用：
        *1. 有用户行为发生像（click,tap,etc）.
        *2. 对于桌面程序，用户已经提前播放了音频
        *3. 对于移动端用户将音频网址home screen.
        */
        playMusic();
        $('.warninf-info').css('color','#ec3937');
        messgae();
    };
    

    //7275的端口是科强手环专用
    WE = new WebSocket("ws://101.89.115.24:7275");
    WE.onopen = function() {
        WE.send('IWWEBC,'+uid);
    };
    //服务器推送消息格式为json
    WE.onmessage = function(e) {
        console.log(e.data);
        /*
        *Chrome的autoplay政策在2018年4月做了更改。
        *新的行为：浏览器为了提高用户体验，减少数据消耗，现在都在遵循autoplay政策，Chrome的autoplay 政策非常简单
        *1. muted autoplay始终被允许
        *2. 音乐的autoplay 只有在下面集中情况下起作用：
        *1. 有用户行为发生像（click,tap,etc）.
        *2. 对于桌面程序，用户已经提前播放了音频
        *3. 对于移动端用户将音频网址home screen.
        */
        playMusic();
        $('.warninf-info').css('color','#ec3937');
        messgae();
    };
    
    //播放提示音乐
    function playMusic() {
        console.log(111111);
        var player = $("#audioMp3")[0]; /*jquery对象转换成js对象*/
        if (player.paused){ /*如果已经暂停*/
            player.play(); /*播放*/
        }else {
            player.pause();/*暂停*/
        }
    }
    //未读消息统计查询
    function messgae(){
        //查询数据库未读消息总数
        $.post("<?php echo url('/index/Work/mess'); ?>",function(res){
            console.log(res);
            $.each(res, function(i,item){       
                switch(item.type){
                    case 2:
                        $('#gps').html(item.number);
                        $('.save_gps').html(item.id);
                    break;
                    case 3:
                        $('#heart').html(item.number);
                        $('.save_heart').html(item.id);
                    break;
                    case 1:
                        $('#sos').html(item.number);
                        $('.save_sos').html(item.id);
                    break;
                    case 7:
                        $('#smoke').html(item.number);
                        $('.save_smoke').html(item.id);
                    break;
                    case 8:
                        $('#fuel').html(item.number);
                        $('.save_fuel').html(item.id);
                    break;
                    case 9:
                        $('#infrared').html(item.number);
                        $('.save_infrared').html(item.id);
                    break;
                }  
            }); 
            $('.label-primary').html(res.count);
            window.glo_number = res.count;
        });
    }

    //点击触发修改未读消息
    function save_mess(item){
        layer.confirm('确认要将消息改成已读吗？',{
            btn : ['确定', '取消'],
            btn1:function(obj){
                $(".layui-layer-btn0").attr('disabled',true);
                var id = $('.save_'+item).html();
                var number = $('#'+item).html();
                $.post("<?php echo url('/index/Index/savemess'); ?>",{'id':id},function(res){
                    if (res.code==0) {
                        //关闭确框
                        $('.layui-layer-btn1').click();
                        layer.msg(res.msg,{icon:1,time:1000},function(){
                            //清除未读消息总数
                            var count_num = glo_number - number;
                            window.glo_number = count_num;
                            $('.label-primary').html(count_num);
                            switch(item){
                                case 'sos':
                                    $('#sos').html(0);
                                break;
                                case 'heart':
                                    $('#heart').html(0);
                                break;
                                case 'gps':
                                    $('#gps').html(0);
                                break;
                                case 'smoke':
                                    $('#smoke').html(0);
                                break;
                                case 'fuel':
                                    $('#fuel').html(0);
                                break;
                                case 'infrared':
                                    $('#infrared').html(0);
                                break;
                            }
                        });
                        return false;
                    }
                    layer.msg(res.msg,{icon:5,time:2000},function(){
                        $(".layui-layer-btn0").attr('disabled',false);
                    })
                });
            }
        })
    }
</script>
</html>