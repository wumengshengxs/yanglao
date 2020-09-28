<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:97:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/index/bigscreen.html";i:1553234686;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>智慧养老</title>
    <meta name="keywords" content="智慧养老">
    <meta name="description" content="智慧养老">
    <link href="/public/static/css/bootstrap.min.css?v=3.3.5" rel="stylesheet">
    <link href="/public/static/css/style.min.css?v=4.0.0" rel="stylesheet">
    <link href="/public/static/css/big.css" rel="stylesheet">
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight" style="background-color: #032465;height: 100%;padding: 0px;">
    <div class="col-lg-12 " style="background-color: #032465;">
        <div class="a-title">康桥镇智慧居家养老系统</div>
        <div class="right-thing" >
            <span><i class="fa fa-cloud"></i>服务器: 良好</span>
            <span id="currentTime">2019-01-01 00:00:00</span>
        </div>
    </div>

    <div class="col-sm-6" style="background-color: #010529;border:solid 1px #025084;height: 100%;padding: 0px" >
        <div class="col-sm-7 " style="padding-left: 20px;height: 25%;">
            <span style="color: #1CF002;font-size: 16px">实时数据</span>
            <div class="ibox float-e-margins" style="color:#00fff6;height: 100%;overflow:auto;">
                <table class="table">
                    <thead id="show">
                    </thead>
                </table>
            </div>
        </div>
        <!-- 话务员 -->
        <div class="br-vertical" style="float: left"></div>
        <div class="col-sm-4" style="padding-left: 10px;float: left;height: 29%;">
            <span style="color: #1CF002;font-size: 16px">呼叫中心</span>
            <div id="staff" style="width: 140%;height:80%;"></div>
        </div>
        <!--工单24小时处理情况 -->
        <div class="br-horizontal" style="float: left"></div>
        <div class="col-sm-4" style="padding:0 0 0 10px;width: 31%;height: 25%;">
            <div>
                <span style="color: #1CF002;font-size: 16px" class="count_order"></span>
                <div class="appendChild_order">
                    
                </div>
            </div>
        </div>
        <span class="br-vertical" style="float: left"></span>
        <div class="col-sm-4" style="padding:0 0 0 10px;height: 25%">
            <div><span style="color: #1CF002;font-size: 16px">工单处理情况</span></div>
            <div id="work" style="width: 120%;height:80%;"></div>
        </div>
        <!-- 近几日工单处理 -->
        <span class="br-vertical" style="float:left"></span>
        <div class="col-sm-4 " style="padding:0 0 0 10px;height: 25%">
            <span style="color: #1CF002;font-size: 15px">近七日工单数量: 1-7 </span><br>
            <div id="sparkline2" style="width: 120%;height:100%;">
                
            </div>
        </div>
        <!-- 平台服务对象  -->
        <div class="br-horizontal" style="float: left"></div>
        <div class="col-sm-4" style="padding:0 0 0 10px;width: 31%;height: 25%">
            <div><span style="color: #1CF002;font-size: 16px">服务对象（100）人</span></div>
            <div id="sex" style="width: 120%;height:80%;"></div>
        </div>
        <!-- 年龄比例 -->
        <span class="br-vertical" style="float: left"></span>
        <div class="col-sm-4" style="padding:0 0 0 10px;height: 25%">
            <div><span style="color: #1CF002;font-size: 16px">服务对象年龄比例</span></div>
            <div id="age" style="width: 120%;height:80%;"></div>
        </div>
        <!-- 分组 -->
        <span class="br-vertical" style="float: left"></span>
        <div class="col-sm-4 " style="padding:0 0 0 10px;height:25%;overflow: hidden;overflow-x: hidden;overflow-y: auto;">
            <div><span style="color: #1CF002;font-size: 16px">分组人数</span></div>
            <div  style="width: 100%;height:80%;">
                <?php if(is_array($group) || $group instanceof \think\Collection || $group instanceof \think\Paginator): $i = 0; $__LIST__ = $group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g): $mod = ($i % 2 );++$i;?>
                    <div class="skillbar html">
                    <div class="filled" data-width="<?php echo $g['value']; ?>%"></div>
                    <span class="title"><?php echo $g['name']; ?></span>
                    <span class="percent"><?php echo $g['value']; ?>个</span>
                </div>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
        <!--腕表总数 -->
        <div class="br-horizontal" style="float: left"></div>
        <div class="col-sm-4" style="padding:0 0 0 10px;width: 31%;height: 22%">
            <div class="watch-font" align="center">腕表总数</div>
            <div class='watch-font' align="center"><?php echo $watches['number']; ?></div>

        </div>
        <!-- 绑定人数 -->
        <span class="br-vertical" style="float: left"></span>
        <div class="col-sm-4" style="padding:0 0 0 10px;height: 22%">
            <div class="watch-font"  align="center">腕表绑定数</div>
            <div class='watch-font' align="center"><?php echo $watches['binding_one']; ?></div>
        </div>
        <!--发放总数 -->
        <span class="br-vertical" style="float: left"></span>
        <div class="col-sm-4 " style="padding:0 0 0 10px;height: 22%">
            <div class="watch-font"  align="center">腕表发放数</div>
            <div align="center" class='watch-font'><?php echo $watches['binding_two']; ?></div>
        </div>
    </div>
    <!--右侧电子定位地图 -->
    <div class="col-sm-6" style="background-color: #022237;border:solid 1px #025084;height: 100%;padding: 0px" >
        <div id="allmap"></div>

    </div>
</div>
</body>
<script src="/public/static/js/jquery.min.js?v=2.1.4"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=KW0y9CM8nvQWkWcQ4jIDTOBevgSapQQQ"></script>
<script src="/public/static/js/main/echarts.min.js"></script>
<script type="text/javascript" src="/public/static/js/an-skill-bar.js"></script>
<script>
    //当前时间
    setInterval(function(){
        $("#currentTime").text(new Date().toLocaleString());
    },1000);

    //websocket连接
    kq = new WebSocket("ws://101.89.115.24:7273");
    kq.onopen = function() {
        kq.send('@B#@|big|WES|@E#@');
    };

    //服务器推送消息格式为json
    kq.onmessage = function(e) {
        //时时告警数据
        showme();
        //工单数据
        showorder();
        //地图点
        map_init_add();
    };

    //初始化查询饼状图
    $(function(){
        $('.skillbar').skillbar({
            speed: 1000,
        });
        //告警数据查询
        showme();
        //工单数据查询
        showorder();
        //地图数据
        map_init_add();
    });

    function showme(){
        $.post("<?php echo url('/index/Warning/getofteninfo'); ?>",function(res){
            var mess = '';
            $.each(res, function(i, item){       
                mess+='<tr>'
                    +'<th>'
                    +'<img src='+item.head+' class="warn-img">'
                    +'</th>'
                    +'<th width="60%">'+item.content+'</th>'
                    +'<th>'+item.time+'</th>'       
                    +'</tr>';
            });  
            $('#show').html(mess);
        });
    } 

    //工单查询
    function showorder(){
        $.get('/index/Index/leftmonitorsdata',function(info){
            //用户比例
            var sex = echarts.init(document.getElementById('sex'));
            var sex_color = ['#B5C334','#FCCE10'];
            init_echarts(info.sex,sex,sex_color);
            //年龄比例
            var age = echarts.init(document.getElementById('age'));
            var age_color = ['#666699','#660033','#99CC99'];
            init_echarts(info.age,age,age_color);
            //工单处理情况
            var work = echarts.init(document.getElementById("work"));
            var work_color = ['#C1232B','#009966','#3399CC','#FF9900','#0000CC'];
            init_echarts(info.state,work,work_color);
            //7日工单趋势
            var sparkline2 = echarts.init(document.getElementById("sparkline2"));
            cycle(info.cycle,sparkline2);
            //工单总数
            orderinfo(info.order_info);
        });
    }
    //饼状图公共方法
    function init_echarts(obj,init,colr_arr){
        var listoption = {
            color: colr_arr, //环形图每块的颜色
            legend: {
                type: 'scroll',
                orient: 'vertical',
                bottom: 20,
                data: obj,
                itemWidth:15,
                left:'left',
                top:'top',
                itemWidth:15,
            },
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            series: [
                {   
                    name:'',
                    type:'pie',
                    radius:'65%',
                    center: ['50%', '50%'],
                    avoidLabelOverlap: false,
                    label: {
                        normal: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            show: true,
                            textStyle: {
                                fontSize: '20',
                                fontWeight: 'bold'
                            }
                        }
                    },
                    labelLine: {
                        normal: {
                            show: false
                        }
                    },
                    data:obj
                }
            ]
        };
        init.setOption(listoption);
    }

    //话务员
    var myChart = echarts.init(document.getElementById('staff'));    
    var option = {
        title: {
            text: '',
            subtext: '',
            left: 'center'
        },
        color: ['#e20e0a', '#333333', '#FF6600'], //环形图每块的颜色
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        legend: {
            type: 'scroll',
            orient: 'vertical',
            bottom: 20,
            data: ['忙碌', '离线','空闲'],
            itemWidth:15,
            left:'left',
            top:'top',
        },
        series: [
            {
                name:'',
                type:'pie',
                radius:'65%',
                center: ['50%', '50%'],
                avoidLabelOverlap: false,
                label: {
                    normal: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        show: true,
                        textStyle: {
                            fontSize: '15',
                            fontWeight: 'bold'
                        }
                    }
                },
                labelLine: {
                    normal: {
                        show: false
                    }
                },
                data:[
                    {value:335, name:'忙碌'},
                    {value:310, name:'离线'},
                    {value:234, name:'空闲'},
                ]
            }
        ]
    };
    myChart.setOption(option);

    //工单趋势图 
    /*
    *柱状图公用方法
    *name 图标显示名称
    *mess 初始化查询返回数据
    *init 获取属性的标签
    */
    function cycle(mess,init){
        //工单趋势
        var list ={
            title:{
                text :name,
            },
            grid:{
                x:25,
                y:20,
                borderWidth:5,
                // width:280
            },
            tooltip:{
                trigger:"axis"
            },
            calculable : !0,
            xAxis : [
                {
                    type : "category",
                    data : mess.day,
                    // max:10
                }
            ],
            yAxis : [
                {
                    type : "value",
                }
            ],
            series : [
                {
                    name : "",
                    type : "bar",
                    data :mess.v,
                    barWidth:20,
                    itemStyle: {
                        normal: {
                            color:'#0099CC',
                        },
                    },
                }
            ]
        };
        init.setOption(list);
    };
    
    //工单数据
    function orderinfo(arr){ 
        $('.count_order').html('工单总数（'+arr.count+'）个');
        var h = '';
        $.each(arr.res, function(i, item){    
            var num = (item.number/arr.count)*100;
            h+='<div class="skillbar html">'
            +'<div class="filled" data-width="'+num+'%" style="width:'+num+'%"></div>'
            +'<span class="title">'+item.type+'</span>'
            +'<span class="percent">'+item.number+'个</span>'
            +'</div>';

        });  
        $('.appendChild_order').html(h);
    }

    //右侧地图
    function map_init_add(){
        //地图控件
        map = new BMap.Map("allmap");
        //经纬度为徐汇区
        var point = new BMap.Point(121.43, 31.18);
        //创建打开区域
        map.centerAndZoom(point, 13);
        map.enableScrollWheelZoom(true);     //开启鼠标滚轮缩放
        //地图控件背景
        var mapStyle={  style : "midnight" }  
        map.setMapStyle(mapStyle);

        var opts = {
            width : 300,     // 信息窗口宽度
            height: 190,     // 信息窗口高度
            title : "" , // 信息窗口标题
            enableMessage:true//设置允许信息窗发送短息
        };
        $.post('/index/Loclog/bigmap',function(res){
            var data_info = JSON.parse(res);
            for(var i=0;i<data_info.length;i++){
                var marker = new BMap.Marker(new BMap.Point(data_info[i].j,data_info[i].w));  // 创建标注
                var content = '定位时间:'
                            +data_info[i].addtime
                            +'<br/>用户名:'+data_info[i].name
                            +'<br/><img src='+data_info[i].img+' class="img-responsive"><br/>'
                            +'定位方式:'+data_info[i].location_type
                            +'<br/>设备号:'+data_info[i].imei
                            +'<br/>地理位置:'+data_info[i].address;
                //将标注添加到地图中
                map.addOverlay(marker);               
                //添加内容
                addClickHandler(content,marker);
            }
        });
        function addClickHandler(content,marker){
            //点击定位点出现弹框
            marker.addEventListener("click",function(e){
                openInfo(content,e)}
            );
        }
        //展示弹框信息
        function openInfo(content,e){
            var p = e.target;
            var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
            var infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象
            map.openInfoWindow(infoWindow,point); //开启信息窗口
        }
    }
    
</script>
</html>