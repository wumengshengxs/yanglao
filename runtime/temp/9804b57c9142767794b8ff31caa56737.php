<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:109:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/devicewithdraw/devicechange.html";i:1554358663;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
                    <div class="bars pull-left">
                        <div class="btn-group hidden-xs" id="exampleTableEventsToolbar" role="group">
                            <select name="passage" class="form-control down_device">
                                <option value="">请选择设备厂商导出</option> 
                                <?php if(is_array($passage) || $passage instanceof \think\Collection || $passage instanceof \think\Paginator): $i = 0; $__LIST__ = $passage;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p): $mod = ($i % 2 );++$i;?>
                                    <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                <?php endforeach; endif; else: echo "" ;endif; ?>  
                            </select>
                        </div>
                    </div>
                    &nbsp;&nbsp;
                    <button class="btn btn-white" type="button" data-toggle="modal" data-target="#graph-a" data-type="ech_heart">
                        <i class="fa fa-plus"></i> 设备变更手动添加
                    </button>&nbsp;&nbsp;
                    <div class="btn-group search">
                        <button class="btn btn-white" type="button"><i class="fa fa-search"></i> 高级搜索</button>
                        <div class="dropdown-menu">
                            <form class="form-horizontal query">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">创建时间</label>
                                    <div class="col-sm-4">
                                        <input type="text" name="start_create"  class="form-control times" value="<?php echo $data['query']['start_create']; ?>" />
                                    </div>
                                    <div class="col-sm-1 middle-div">至</div>
                                    <div class="col-sm-4 f-right">
                                        <input type="text" name="end_create"  class="form-control times" value="<?php echo $data['query']['end_create']; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">设备厂商</label>
                                    <div class="col-sm-9">
                                        <select name="pid" class="form-control">
                                            <option value="">请选择设备厂商</option>
                                            <?php if(is_array($passage) || $passage instanceof \think\Collection || $passage instanceof \think\Paginator): $i = 0; $__LIST__ = $passage;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                                            <option value="<?php echo $v['id']; ?>" <?php if($data['query']['pid'] == $v['id']): ?> selected <?php endif; ?>><?php echo $v['name']; ?></option>
                                            <?php endforeach; endif; else: echo "" ;endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">设备号码</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="s_imei"  class="form-control" value="<?php echo $data['query']['s_imei']; ?>"/>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-3">
                                        <button class="btn btn-primary" type="submit" onclick="search_submit()">搜索</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>&nbsp;&nbsp;
                    <?php if(!(empty($data['item']) || (($data['item'] instanceof \think\Collection || $data['item'] instanceof \think\Paginator ) && $data['item']->isEmpty()))): if(is_array($data['item']) || $data['item'] instanceof \think\Collection || $data['item'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['item'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$iv): $mod = ($i % 2 );++$i;?>
                    <span style="font-weight: lighter;"><?php echo $iv['item']; ?>：<?php echo $iv['value']; ?>&nbsp;<a href="javascript:;">X</a>&nbsp;&nbsp;</span>
                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>原设备厂商</th>
                            <th>原IMEI</th>
                            <th>绑定对象</th>
                            <th>身份证号码</th>
                            <th>性别</th>
                            <th>地址</th>
                            <th>变更原因</th>
                            <th>变更创建时间</th>
                            <th>新IMEI</th>
                            <th>跟进人员</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($data['list']['data']) || $data['list']['data'] instanceof \think\Collection || $data['list']['data'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['list']['data'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $v['pname']; ?></td>
                            <td><?php echo $v['imei']; ?></td>
                            <td><?php echo $v['name']; ?></td>
                            <td><?php echo $v['id_number']; ?></td>
                            <td><?php echo $v['sex']; ?></td>
                            <td><?php echo $v['address']; ?></td>
                            <td><?php echo $v['content']; ?></td>
                            <td><?php echo date('Y-m-d H:i:s',$v['addtime']); ?></td>
                            <td><?php echo $v['ec_imei']; ?></td>
                            <td><?php echo $v['uname']; ?></td>
                            <td>
                                <button type="button" data-toggle="modal" data-target="#save" class="btn btn-outline btn-success" data-id="<?php echo $v['id']; ?>" data-imei="<?php echo $v['imei']; ?>" data-content="<?php echo $v['content']; ?>" data-ec_imei="<?php echo $v['ec_imei']; ?>" data-state=<?php echo $v['state']; ?>>
                                    编辑
                                </button>
                                <button type="button" class="btn btn-outline btn-success" onclick="del(<?php echo $v['id']; ?>)">
                                    删除
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                    <div class="page"><?php echo $data['page']; ?><div><?php echo $data['list']['last_page']; ?>页，总共<?php echo $data['list']['total']; ?>条数据</div></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 维修清单手工录入 -->
<div class="modal inmodal fade" id="graph-a" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">设备变更清单录入</h4>
            </div>
             <div class="modal-body">
                <form class="form-horizontal m-t" id="change_form">
                    <div class="form-group">
                        <label class="col-sm-3 control-label label_title_one">原始IMEI</label>
                        <div class="col-sm-8">
                            <input  name="y_imei" class="form-control" type="text" placeholder="请输入设备唯一标示号码"/>
                            <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>输入前确保原始的imei对应的设备已经绑定用户</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label label_title_one">设备变更类型</label>
                        <div class="col-sm-8">
                            <select name="passage" class="form-control add_dep">
                                <option value="">请选择设备变更类型</option>
                                <option value="1">换表换卡</option>
                                <option value="2">换表不换卡</option>
                                <option value="3">换卡不换表</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label label_title_one">新IMEI</label>
                        <div class="col-sm-8">
                            <input  name="n_imei" class="form-control" type="text" placeholder="请输入设备唯一标示号码"/>
                            <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>输入前确保新的imei对应的设备未绑定用户</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label label_title_one">变更备注</label>
                        <div class="col-sm-8">
                            <textarea  name="comment" class="form-control" placeholder="请输入变更备注"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" onclick="SubMitOne()">保存</button>
            </div>
        </div>
    </div>
</div>
<!-- 编辑更换清单 -->
<div class="modal inmodal fade" id="save" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">编辑设备更换清单</h4>
            </div>
             <div class="modal-body">
               <form class="form-horizontal m-t" id="change_save_form">
                <input type="hidden" name="h_id" />
                    <div class="form-group">
                        <label class="col-sm-3 control-label label_title_one">原始IMEI</label>
                        <div class="col-sm-8">
                            <input  name="s_y_imei" class="form-control s_y_imei" type="text" placeholder="请输入设备唯一标示号码"/>
                            <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>输入前确保原始的imei对应的设备已经绑定用户</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label label_title_one">设备变更类型</label>
                        <div class="col-sm-8">
                            <select name="passage" class="form-control s_dep">
                                <option value="">请选择设备变更类型</option>
                                <option value="1">换表换卡</option>
                                <option value="2">换表不换卡</option>
                                <option value="3">换卡不换表</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label label_title_one">新IMEI</label>
                        <div class="col-sm-8">
                            <input  name="s_n_imei" class="form-control s_n_imei" type="text" placeholder="请输入设备唯一标示号码"/>
                            <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>输入前确保新的imei对应的设备未绑定用户</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label label_title_one">变更备注</label>
                        <div class="col-sm-8">
                            <textarea  name="s_comment" class="form-control s_comment" placeholder="请输入变更备注"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" onclick="SubMitSave()">保存</button>
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
    var $search = '<?php echo json_encode($data["item"]); ?>';
</script>
<script type="text/javascript" src="/public/static/js/devicewithdraw/devicechange.js"></script>
</html>