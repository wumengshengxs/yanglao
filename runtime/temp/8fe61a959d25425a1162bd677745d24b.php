<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:94:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/loclog/index.html";i:1550133751;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1548228499;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1548228499;}*/ ?>
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
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <div class="ibox-title">
                <h5>
                    <div class="btn-group search">
                        <button class="btn btn-white" type="button"><i class="fa fa-search"></i> 高级搜索</button>
                        <div class="dropdown-menu">
                            <form class="form-horizontal" action="javascript:;search()">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">创建时间</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="start_create"  class="form-control times" value="<?php echo $data['query']['start_create']; ?>">
                                    </div>
                                    <div class="col-sm-1 middle-div">至</div>
                                    <div class="col-sm-4 f-right">
                                        <input type="text" name="end_create"   class="form-control times" value="<?php echo $data['query']['end_create']; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">身份证号码</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="cardid"  class="form-control" value="<?php echo $data['query']['cardid']; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">姓名</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name"  class="form-control" value="<?php echo $data['query']['name']; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">IMEI</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="imei"  class="form-control" value="<?php echo $data['query']['imei']; ?>">
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-2">
                                        <button class="btn btn-primary" type="submit">搜索</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </h5>
            </div>
            <div class="row row-lg">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>姓名</th>
                                <th>定位地址</th>
                                <th>定位时间</th>
                                <th>IMEI</th>
                                <th>定位方式</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($data['list']['data']) || $data['list']['data'] instanceof \think\Collection || $data['list']['data'] instanceof \think\Paginator): $k = 0; $__LIST__ = $data['list']['data'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($k % 2 );++$k;?>
                            <tr>
                                <td><?php echo $k; ?></td>
                                <td><a href="/index/Client/clientBase?id=<?php echo $c['id']; ?>" title="点击查看详情"><?php echo $c['name']; ?></a></td>
                                <td><?php echo (isset($c['address']) && ($c['address'] !== '')?$c['address']:'--'); ?></td>
                                <td><?php echo date('Y-m-d H:i:s',$c['addtime']); ?></td>
                                <td><?php echo $c['imei']; ?></td>
                                <td><?php echo $c['location_type']; ?></td>
                                <td>
                                    <a href="/index/Client/clientBase?id=<?php echo $c['id']; ?>">查看详情</a>
                                </td>
                            </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                        <div class="page"><?php echo $page; ?> <div><?php echo $client['last_page']; ?>页，总共<?php echo $client['total']; ?>条数据</div></div>
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

<script type="text/javascript">
    //日期搜索
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        //执行一个laydate实例
        lay('.times').each(function(){
            laydate.render({
                elem: this,trigger: 'click'
            });
        });
    });
    //搜索
    function search(){
        //获取搜素参数
        var start_create = $("input[name='start_create']").val();
        var end_create = $("input[name='end_create']").val();
        if (end_create && start_create) {
            if (start_create<end_create) {
                layer.msg('结束时间不能小于开始时间');
                return false;
            }     
        }
        location.href="/index/Loclog/index?"+$('.form-horizontal').serialize();
    }
</script>
</html>