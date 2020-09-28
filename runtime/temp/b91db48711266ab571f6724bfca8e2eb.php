<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:92:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/index/main.html";i:1551769344;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;}*/ ?>
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
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>最新紧急报警</h5>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-hover no-margins">
                            <thead>
                                <tr>
                                    <th>状态</th>
                                    <th>时间</th>
                                    <th>发起人</th>
                                    <th>内容</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(is_array($gency) || $gency instanceof \think\Collection || $gency instanceof \think\Paginator): $i = 0; $__LIST__ = $gency;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                <tr>
                                    <td>
                                        <?php if($vo['state'] == '已退回'): ?>
                                            <span class="label label-warning"><?php echo $vo['state']; ?></span>
                                        <?php elseif($vo['state'] == '已办结'): ?>
                                            <span class="label label-primary"><?php echo $vo['state']; ?></span>
                                        <?php else: ?>
                                            <small><?php echo $vo['state']; ?>...</small>
                                        <?php endif; ?>
                                    </td>
                                    <td><i class="fa fa-clock-o"></i><?php echo date('Y-m-d H:i:s',$vo['create_time']); ?></td>
                                    <td><?php echo $vo['userName']; ?></td>
                                    <td class="text-navy"><?php echo $vo['title']; ?></td>
                                    <td>
                                        <a href="/index/Work/workDetails?id=<?php echo $vo['id']; ?>"  class="btn btn-success btn-xs">查看工单</a>
                                    </td>
                                </tr>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>工单趋势</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="flot-chart">
                                    <div class="flot-chart-content" id="echarts"></div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="flot-chart">
                                    <div class="flot-chart-content" id="order"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="flot-chart">
                                    <div class="flot-chart-content" id="qs"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>服务对象</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="flot-chart">
                                    <div class="flot-chart-content" id="sex"></div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="flot-chart">
                                    <div class="flot-chart-content" id="age"></div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="flot-chart">
                                    <div class="flot-chart-content" id="group">
                                        <div class="project-completion"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 text-center">
                                <span  style="color:#333;font-size: 19px;"><b>今日寿星</b></span>
                                <div class="flot-chart" style="overflow:auto;">
                                    <?php if(is_array($birthday) || $birthday instanceof \think\Collection || $birthday instanceof \think\Paginator): $i = 0; $__LIST__ = $birthday;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                    <div class="col-xs-6 birthday-person ng-scope"> 
                                        <div class="col-xs-6 name"> 
                                            <a class="btn btn-link btn-xs ng-binding" style="color: #1c84c6;" href="/index/Client/clientBase?id=<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></a> 
                                        </div> 
                                        <div class="col-xs-6 age ng-binding"><?php echo $vo['age']; ?>岁</div> 
                                    </div>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-sm-12 m-t">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>积分情况</h5>
                </div>
                <div class="ibox-content text-center">
                    <div class="col-sm-2">
                        <p class="text-warning">累计发放</p>
                        <h2><?php echo (isset($sumIntegralRecords['accumulate']['score']) && ($sumIntegralRecords['accumulate']['score'] !== '')?$sumIntegralRecords['accumulate']['score']:0); ?></h2>
                    </div>
                    <div class="col-sm-2">
                        <p class="text-warning">累计核销</p>
                        <h2><?php echo (isset($sumIntegralRecords['destory']['score']) && ($sumIntegralRecords['destory']['score'] !== '')?$sumIntegralRecords['destory']['score']:0); ?></h2>
                    </div>
                    <div class="col-sm-2">
                        <p class="text-warning">可用积分</p>
                        <h2><?php echo (isset($clientIntegral['integral']) && ($clientIntegral['integral'] !== '')?$clientIntegral['integral']:'0'); ?></h2>
                    </div>
                    <div class="col-sm-2">
                        <p class="text-warning">人均积分</p>
                        <h2><?php echo (isset($clientIntegral['average']) && ($clientIntegral['average'] !== '')?$clientIntegral['average']:'0'); ?></h2>
                    </div>
                    <div class="col-sm-2">
                        <p class="text-warning">最大积分</p>
                        <h2><?php echo (isset($clientIntegral['max']) && ($clientIntegral['max'] !== '')?$clientIntegral['max']:'0'); ?></h2>
                    </div>
                    <div class="col-sm-2">
                        <p class="text-warning">最小积分</p>
                        <h2><?php echo (isset($clientIntegral['min']) && ($clientIntegral['min'] !== '')?$clientIntegral['min']:'0'); ?></h2>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="/public/static/js/jquery.min.js?v=2.1.4"></script>
<script src="/public/static/js/main/echarts.min.js"></script>
<script>
    $(function(){
        //初始化24小时工单查询
        $.get('/index/Index/main_echarts',function(info){
            //初始化echarts插件
            var order = echarts.init(document.getElementById('order'));
            init_echarts('工单处理状态','工单处理状态',info.order,order);
            //用户比例
            var sex = echarts.init(document.getElementById('sex'));
            init_echarts('服务对象男女比例','服务对象男女比例',info.sex,sex);
            //年龄比例
            var age = echarts.init(document.getElementById('age'));
            init_echarts('服务对象年龄比例','服务对象年龄比例',info.age,age);
            //7日工单
            var qs = echarts.init(document.getElementById("qs"));
            cycle('7日工单',info.cycle,qs);
            //服务对象分组
            var group = echarts.init(document.getElementById("group"));
            init_echarts('分组信息','分组信息',info.group,group);
        });
    });
    /*
    *饼状图公用方法
    *text title配置名称
    *name series 配置名称
    *mess 初始化查询返回数据
    *init 获取属性的标签
    */
    function init_echarts(text,name,mess,init){
        //指定图表的配置项和数据
        var init_option = {
            color: ['#6dd8da', '#b6a2de','#ca8335','#58afed','#d6000000','#a94442','#ed5565','#337ab7','#771917','#80222c'], //环形图每块的颜色
            title: {
                text: text,
                subtext: '',
                left: 'center'
            },
            legend: {
                type: 'scroll',
                orient: 'horizontal',
                bottom: 1,
                data: mess,
                x:'center',
                y : 'bottom',

            },
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            series: [
                {
                    name:name,
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
                    data:mess
                }
            ]
        };
        //使用刚指定的配置项和数据显示图表。
        init.setOption(init_option);
    }

    /*
    *柱状图公用方法
    *name 图标显示名称
    *mess 初始化查询返回数据
    *init 获取属性的标签
    */
    function cycle(name,mess,init){
        //工单趋势
        var list ={
            title:{
                text :name,
                x:'center'
            },
            tooltip:{
                trigger:"axis"
            },
            grid:{
                x:30,x2:40,y2:24
            },
            calculable : !0,
            xAxis : [
                {
                    type : "category",
                    data : mess.day,
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
                    data :mess.v ,
                    barWidth:30,
                    itemStyle: {
                        normal: {
                            color:'#1c84c6',
                        },
                    },
                }
            ]
        };
        init.setOption(list);
    }; 

    //话务员
    var myChart = echarts.init(document.getElementById('echarts'));    
    var option = {
        title: {
            text: '话务员状态',
            subtext: '',
            left: 'center'
        },
        legend: {
                type: 'scroll',
                orient: 'horizontal',
                bottom: 1,
                data: ['忙碌','离线','空闲'],
                x:'center',
                y : 'bottom',

            },
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        series: [
            {
                name:'话务员状态',
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
</script>
</html>