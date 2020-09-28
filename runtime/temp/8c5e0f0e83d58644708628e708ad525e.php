<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:94:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/client/track.html";i:1551769344;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
<link href="/public/static/css/map.css" rel="stylesheet">
<link rel="stylesheet" href="/public/static/css/client.css">
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>
                <small>服务对象管理&nbsp;>&nbsp;轨迹动画</small>
                <a href="javascript:history.back(-1);" title="返回上一页"><i class="fa fa-reply"></i></a>
            </h5>
        </div>
        <div class="ibox-content c-case-record">
            <div class="col-sm-12 nav-tabs-client">
                <div class="col-sm-4">
                    <img src="<?php echo (isset($user['head']) && ($user['head'] !== '')?$user['head']:'/public/static/img/head.jpg'); ?>" alt="">&nbsp;&nbsp;
                    <span><?php echo $user['name']; ?></span>
                </div>
                <div class="col-sm-8">
                    <ul class="nav nav-tabs"></ul>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 m-t">
                    <div class="float-e-margins">
                        <button  class="btn btn-outline btn-success" title="获取定位">定位时间</button>
                        <div class="panel-body" id='allmap' style="overflow: hidden;position: relative;left: 0;top: 0;height: 700px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="timely" value='<?php echo $loc; ?>'/>
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

<script type="text/javascript" src="/public/static/js/client.js"></script>
<script type="text/javascript">
    var $userId = '<?php echo $_GET["id"]; ?>';
</script>
<script type="text/javascript">
     function map_init(){
        map = new BMap.Map("allmap");
        //经纬度为徐汇区
        var point = new BMap.Point(121.43, 31.18);
        //创建打开区域
        map.centerAndZoom(point, 13);
        //开启鼠标滚轮缩放
        map.enableScrollWheelZoom(true);    
        //添加比例尺控件 
        map.addControl(new BMap.ScaleControl()); 
        var routeArr = <?php echo $res; ?>;
        var pointArr = [];
        for (var i=0;i<routeArr.length;i++){
            pointArr[i] = new BMap.Point(routeArr[i]['lng'], routeArr[i]['lat'])
        }
        map.centerAndZoom(pointArr[0], 15);
        var sy = new BMap.Symbol(BMap_Symbol_SHAPE_BACKWARD_OPEN_ARROW, {
            scale: 0.5,//图标缩放大小
            strokeColor:'#CC0033',//设置矢量图标的线填充颜色
            strokeWeight: '2',//设置线宽
        });
        var icons = new BMap.IconSequence(sy, '1', '30');
        // 创建polyline对象
        var polyline =new BMap.Polyline(pointArr, {
            enableEditing: false,//是否启用线编辑，默认为false
            enableClicking: true,//是否响应点击事件，默认为true
            icons:[icons],
            strokeWeight:'3',//折线的宽度，以像素为单位
            strokeOpacity: 1,//折线的透明度，取值范围0 - 1
            strokeColor:"#18a45b" //折线颜色
        });
        map.addOverlay(polyline);          //增加折线
    }
    //异步调用百度js
    function map_load(){
        var load = document.createElement("script");
        load.src = "http://api.map.baidu.com/api?v=2.0&ak=KW0y9CM8nvQWkWcQ4jIDTOBevgSapQQQ&callback=map_init";
        document.body.appendChild(load);
    }
    window.onload = map_load;
</script>
</html>