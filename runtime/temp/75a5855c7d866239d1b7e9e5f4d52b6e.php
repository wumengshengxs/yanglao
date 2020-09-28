<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:94:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/client/index.html";i:1554702826;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
                    <button class="btn btn-white btn-sm a-client"><i class="fa fa-plus"></i> 添加服务对象</button>&nbsp;&nbsp;
                    <div class="btn-group search">
                        <button class="btn btn-white" type="button"><i class="fa fa-search"></i> 高级搜索</button>
                        <div class="dropdown-menu">
                            <form class="form-horizontal" action="/index/Client/index" method="get">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">姓名</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" value="<?php echo $param['name']; ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">个人手机号</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="mobile" value="<?php echo $param['mobile']; ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">身份证</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="id_number" value="<?php echo $param['id_number']; ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">年龄</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="start_age" value="<?php echo $param['start_age']; ?>" class="form-control">
                                    </div>
                                    <div class="col-sm-1 middle-div">至</div>
                                    <div class="col-sm-4 f-right">
                                        <input type="text" name="end_age" value="<?php echo $param['end_age']; ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">创建时间</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="start_create" id="start_create" value="<?php echo $param['start_create']; ?>" class="form-control">
                                    </div>
                                    <div class="col-sm-1 middle-div">至</div>
                                    <div class="col-sm-4 f-right">
                                        <input type="text" name="end_create" id="end_create" value="<?php echo $param['end_create']; ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">出生日期</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="start_birthday" id="start_birthday" value="<?php echo $param['start_birthday']; ?>" class="form-control">
                                    </div>
                                    <div class="col-sm-1 middle-div">至</div>
                                    <div class="col-sm-4 f-right">
                                        <input type="text" name="end_birthday" id="end_birthday" value="<?php echo $param['end_birthday']; ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">性别</label>
                                    <div class="col-sm-9">
                                        <div class="radio radio-success radio-inline">
                                            <input type="radio" id="s-sex1" value="1" name="sex" <?php if($param['sex'] == 1): ?> checked <?php endif; ?> >
                                            <label for="s-sex1"> 男 </label>
                                        </div>
                                        <div class="radio radio-success radio-inline">
                                            <input type="radio" id="s-sex2" value="2" name="sex" <?php if($param['sex'] == 2): ?> checked <?php endif; ?> >
                                            <label for="s-sex2"> 女 </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">分组</label>
                                    <div class="col-sm-9">
                                        <select name="group[]" data-placeholder="请选择分组" class="form-control chosen-group" multiple tabindex="4"></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">标签</label>
                                    <div class="col-sm-9">
                                        <select name="tag[]" data-placeholder="请选择标签" class="form-control chosen-tag" multiple tabindex="4"></select>
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
                    </div>&nbsp;&nbsp;
                    <?php if(!(empty($item_value) || (($item_value instanceof \think\Collection || $item_value instanceof \think\Paginator ) && $item_value->isEmpty()))): if(is_array($item_value) || $item_value instanceof \think\Collection || $item_value instanceof \think\Paginator): $i = 0; $__LIST__ = $item_value;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$iv): $mod = ($i % 2 );++$i;?>
                    <span style="font-weight: lighter;"><?php echo $iv['item']; ?>：<?php echo $iv['value']; ?>&nbsp;<a href="javascript:;">X</a>&nbsp;&nbsp;</span>
                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                </h5>
            </div>
            <div class="row row-lg">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <button class="btn btn-white f-right" title="点击上传添加文件" onclick="clientSub();"><i class="fa fa-plus"></i> 批量添加</button>
                        <button class="btn btn-white f-right m-r-sm" title="点击下载模板" onclick="clientDown();"><i class="fa fa-download"></i> 下载模板</button>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>姓名</th>
                                <th>个人手机号</th>
                                <th>腕表手机号</th>
                                <th>腕表是否发放</th>
                                <th>性别</th>
                                <th>年龄</th>
                                <th>分组</th>
                                <th>标签</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($client['data']) || $client['data'] instanceof \think\Collection || $client['data'] instanceof \think\Paginator): $k = 0; $__LIST__ = $client['data'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($k % 2 );++$k;?>
                            <tr>
                                <td><?php echo $k; ?></td>
                                <td><a href="/index/Client/clientBase?id=<?php echo $c['id']; ?>" title="点击查看详情"><?php echo $c['userName']; ?></a></td>
                                <td><?php echo (isset($c['mobile']) && ($c['mobile'] !== '')?$c['mobile']:'--'); ?></td>
                                <td>--</td>
                                <td>--</td>
                                <td><?php echo $c['sex']; ?></td>
                                <td><?php echo $c['age']; ?></td>
                                <td><?php echo (isset($c['groupName']) && ($c['groupName'] !== '')?$c['groupName']:'--'); ?></td>
                                <td><?php echo (isset($c['tagName']) && ($c['tagName'] !== '')?$c['tagName']:'--'); ?></td>
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

<script type="text/javascript" src="/public/static/js/upload.js"></script>
<script type="text/javascript" src="/public/static/js/client.js"></script>
<script type="text/javascript">
    var $search = '<?php echo json_encode($item_value); ?>',
        $selectedGroup = '<?php echo json_encode($param["group"]); ?>',
        $selectedTag = '<?php echo json_encode($param["tag"]); ?>';
</script>
</html>