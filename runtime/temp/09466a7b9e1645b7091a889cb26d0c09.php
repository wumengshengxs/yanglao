<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:100:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/client/client_base.html";i:1547789743;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1553750080;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1553750080;}*/ ?>
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
<link rel="stylesheet" href="/public/static/css/client.css">
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>
                <small>服务对象管理&nbsp;>&nbsp;基础信息</small>
                <a href="javascript:history.back(-1);" title="返回上一页"><i class="fa fa-reply"></i></a>
            </h5>
        </div>
        <div class="ibox-content c-b-info">
            <div class="col-sm-12 nav-tabs-client">
                <div class="col-sm-4">
                    <img src="<?php echo (isset($user['head']) && ($user['head'] !== '')?$user['head']:'/public/static/img/head.jpg'); ?>" alt="">&nbsp;&nbsp;
                    <span><?php echo $user['name']; ?></span>
                </div>
                <div class="col-sm-8">
                    <ul class="nav nav-tabs"></ul>
                </div>
            </div>
            <div class="col-sm-12 m-t">
                <div class="user-info">
                    <form class="form-horizontal">
                        <div class="col-sm-12">
                            <div class="col-sm-6 f-left">
                                <h3 class="inline">用户信息</h3>&nbsp;&nbsp;
                                <span>当前状态：正常</span>
                            </div>
                            <div class="col-sm-6 f-right text-right">
                                <button type="button" class="btn btn-sm btn-white editBtn">编辑</button>
                            </div>
                            <div class="clearfix"></div>
                            <hr/>
                        </div>
                        <div class="row m-t col-sm-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">姓名</label>
                                <div class="col-sm-5">
                                    <span class="help-block m-b-none"><?php echo $user['name']; ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">身份证号</label>
                                <div class="col-sm-5">
                                    <span class="help-block m-b-none"><?php echo $user['id_number']; ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">性别</label>
                                <div class="col-sm-5">
                                    <span class="help-block m-b-none"><?php echo $user['sex']; ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">出生日期</label>
                                <div class="col-sm-5">
                                    <span class="help-block m-b-none"><?php echo $user['birthday']; ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">年龄</label>
                                <div class="col-sm-5">
                                    <span class="help-block m-b-none"><?php echo $user['age']; ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">手机号</label>
                                <div class="col-sm-5">
                                    <span class="help-block m-b-none"><?php echo $user['mobile']; ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">现住地址</label>
                                <div class="col-sm-5">
                                    <span class="help-block m-b-none"><?php echo $user['address']; ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">户籍地址</label>
                                <div class="col-sm-5">
                                    <span class="help-block m-b-none"><?php echo $user['permanent_address']; ?></span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="contacts-info">
                    <form class="form-horizontal">
                        <div class="col-sm-12">
                            <div class="col-sm-6 f-left">
                                <h3 class="inline">联系人信息</h3>
                            </div>
                            <div class="col-sm-6 f-right text-right">
                                <button type="button" class="btn btn-sm btn-white editBtn">编辑</button>
                            </div>
                            <div class="clearfix"></div>
                            <hr/>
                        </div>
                        <div class="row m-t col-sm-12">
                            <?php if(!(empty($contacts) || (($contacts instanceof \think\Collection || $contacts instanceof \think\Paginator ) && $contacts->isEmpty()))): if(is_array($contacts) || $contacts instanceof \think\Collection || $contacts instanceof \think\Paginator): $k = 0; $__LIST__ = $contacts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($k % 2 );++$k;?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">联系人<?php echo $k; ?></label>
                                <div class="col-sm-10">
                                    <span class="help-block m-b-none"><?php echo $c['type_text']; ?>&nbsp;<?php echo $c['name']; ?>&nbsp;<?php echo $c['mobile']; ?></span>
                                </div>
                            </div>
                            <?php endforeach; endif; else: echo "" ;endif; else: ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">联系人</label>
                                <div class="col-sm-10">
                                    <span class="help-block m-b-none">--</span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
                <div class="group-info">
                    <form class="form-horizontal">
                        <div class="col-sm-12">
                            <div class="col-sm-6 f-left">
                                <h3 class="inline">分组信息</h3>
                            </div>
                            <div class="col-sm-6 f-right text-right">
                                <button type="button" class="btn btn-sm btn-white editBtn">编辑</button>
                            </div>
                            <div class="clearfix"></div>
                            <hr/>
                        </div>
                        <div class="row m-t col-sm-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">分组</label>
                                <div class="col-sm-10">
                                    <?php if(!(empty($group) || (($group instanceof \think\Collection || $group instanceof \think\Paginator ) && $group->isEmpty()))): ?>
                                    <span class="help-block m-b-none">
                                        <?php if(is_array($group) || $group instanceof \think\Collection || $group instanceof \think\Paginator): $i = 0; $__LIST__ = $group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g): $mod = ($i % 2 );++$i;?>
                                        <?php echo $g['name']; endforeach; endif; else: echo "" ;endif; ?>
                                    </span>
                                    <?php else: ?>
                                    <span class="help-block m-b-none">--</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tag-info">
                    <form class="form-horizontal">
                        <div class="col-sm-12">
                            <div class="col-sm-6 f-left">
                                <h3 class="inline">标签信息</h3>
                            </div>
                            <div class="col-sm-6 f-right text-right">
                                <button type="button" class="btn btn-sm btn-white editBtn">编辑</button>
                            </div>
                            <div class="clearfix"></div>
                            <hr/>
                        </div>
                        <div class="row m-t col-sm-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">标签</label>
                                <div class="col-sm-10">
                                    <?php if(!(empty($tag) || (($tag instanceof \think\Collection || $tag instanceof \think\Paginator ) && $tag->isEmpty()))): ?>
                                    <span class="help-block m-b-none">
                                        <?php if(is_array($tag) || $tag instanceof \think\Collection || $tag instanceof \think\Paginator): $i = 0; $__LIST__ = $tag;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$t): $mod = ($i % 2 );++$i;?>
                                        <?php echo $t['name']; endforeach; endif; else: echo "" ;endif; ?>
                                    </span>
                                    <?php else: ?>
                                     <span class="help-block m-b-none">--</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="other-info">
                    <form class="form-horizontal">
                        <div class="col-sm-12">
                            <div class="col-sm-6 f-left">
                                <h3>其他信息</h3>
                            </div>
                            <div class="col-sm-6 f-right text-right">
                                <button type="button" class="btn btn-sm btn-white editBtn">编辑</button>
                            </div>
                            <div class="clearfix"></div>
                            <hr/>
                        </div>
                        <div class="row m-t col-sm-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">籍贯</label>
                                <div class="col-sm-10">
                                    <span class="help-block m-b-none"><?php echo (isset($other['native_place']) && ($other['native_place'] !== '')?$other['native_place']:'--'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">民族</label>
                                <div class="col-sm-10">
                                    <span class="help-block m-b-none"><?php echo (isset($other['nation']) && ($other['nation'] !== '')?$other['nation']:'--'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">受教育程度</label>
                                <div class="col-sm-10">
                                    <span class="help-block m-b-none"><?php echo (isset($other['education']) && ($other['education'] !== '')?$other['education']:'--'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">政治面貌</label>
                                <div class="col-sm-10">
                                    <span class="help-block m-b-none"><?php echo (isset($other['political']) && ($other['political'] !== '')?$other['political']:'--'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">宗教</label>
                                <div class="col-sm-10">
                                    <span class="help-block m-b-none"><?php echo (isset($other['religion']) && ($other['religion'] !== '')?$other['religion']:'--'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">兴趣爱好</label>
                                <div class="col-sm-10">
                                    <?php if(!(empty($other['hobby']) || (($other['hobby'] instanceof \think\Collection || $other['hobby'] instanceof \think\Paginator ) && $other['hobby']->isEmpty()))): ?>
                                    <span class="help-block m-b-none">
                                        <?php if(is_array($other['hobby']) || $other['hobby'] instanceof \think\Collection || $other['hobby'] instanceof \think\Paginator): $i = 0; $__LIST__ = $other['hobby'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$h): $mod = ($i % 2 );++$i;?>
                                        <?php echo $h; endforeach; endif; else: echo "" ;endif; ?>
                                    </span>
                                    <?php else: ?>
                                    <span class="help-block m-b-none">--</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">饮食禁忌</label>
                                <div class="col-sm-10">
                                    <?php if(!(empty($other['diet_taboo']) || (($other['diet_taboo'] instanceof \think\Collection || $other['diet_taboo'] instanceof \think\Paginator ) && $other['diet_taboo']->isEmpty()))): ?>
                                    <span class="help-block m-b-none">
                                        <?php if(is_array($other['diet_taboo']) || $other['diet_taboo'] instanceof \think\Collection || $other['diet_taboo'] instanceof \think\Paginator): $i = 0; $__LIST__ = $other['diet_taboo'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$d): $mod = ($i % 2 );++$i;?>
                                        <?php echo $d; endforeach; endif; else: echo "" ;endif; ?>
                                    </span>
                                    <?php else: ?>
                                    <span class="help-block m-b-none">--</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">血型</label>
                                <div class="col-sm-10">
                                    <span class="help-block m-b-none"><?php echo (isset($other['blood_type_text']) && ($other['blood_type_text'] !== '')?$other['blood_type_text']:'--'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">RH阴性</label>
                                <div class="col-sm-10">
                                    <span class="help-block m-b-none"><?php echo (isset($other['rh_negative_text']) && ($other['rh_negative_text'] !== '')?$other['rh_negative_text']:'--'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">经济来源</label>
                                <div class="col-sm-10">
                                    <span class="help-block m-b-none"><?php echo (isset($other['economic_source']) && ($other['economic_source'] !== '')?$other['economic_source']:'--'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">生活来源</label>
                                <div class="col-sm-10">
                                    <span class="help-block m-b-none"><?php echo (isset($other['livelihood']) && ($other['livelihood'] !== '')?$other['livelihood']:'--'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">照看人</label>
                                <div class="col-sm-10">
                                    <?php if(!(empty($other['caregiver']) || (($other['caregiver'] instanceof \think\Collection || $other['caregiver'] instanceof \think\Paginator ) && $other['caregiver']->isEmpty()))): ?>
                                    <span class="help-block m-b-none">
                                        <?php if(is_array($other['caregiver']) || $other['caregiver'] instanceof \think\Collection || $other['caregiver'] instanceof \think\Paginator): $i = 0; $__LIST__ = $other['caregiver'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i;?>
                                        <?php echo $c; endforeach; endif; else: echo "" ;endif; ?>
                                    </span>
                                    <?php else: ?>
                                    <span class="help-block m-b-none">--</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">身体状况</label>
                                <div class="col-sm-10">
                                    <span class="help-block m-b-none"><?php echo (isset($other['healthy']) && ($other['healthy'] !== '')?$other['healthy']:'--'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">居住类型</label>
                                <div class="col-sm-10">
                                    <span class="help-block m-b-none"><?php echo (isset($other['live']) && ($other['live'] !== '')?$other['live']:'--'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">住房类型</label>
                                <div class="col-sm-10">
                                    <span class="help-block m-b-none"><?php echo (isset($other['house']) && ($other['house'] !== '')?$other['house']:'--'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">资料提供人</label>
                                <div class="col-sm-10">
                                    <span class="help-block m-b-none"><?php echo (isset($other['provider']) && ($other['provider'] !== '')?$other['provider']:'--'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">备注</label>
                                <div class="col-sm-10">
                                    <span class="help-block m-b-none"><?php echo (isset($other['remarks']) && ($other['remarks'] !== '')?$other['remarks']:'--'); ?></span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="clearfix"></div>
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
        var $userInfo = '<?php echo json_encode($user); ?>',
            $contactsInfo = '<?php echo json_encode($contacts); ?>',
            $groupInfo = '<?php echo json_encode($group); ?>',
            $tagInfo = '<?php echo json_encode($tag); ?>',
            $otherInfo = '<?php echo json_encode($other); ?>',
            $userId = '<?php echo $user["id"]; ?>';
</script>
</html>