<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:97:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/works/work_info.html";i:1550218609;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/head.html";i:1548228499;s:93:"/Applications/XAMPP/xamppfiles/htdocs/pension_project/application/index/view/public/foot.html";i:1548228499;}*/ ?>
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
<style>
    .name {
         text-align: right;
        white-space: nowrap;
    }
    .ibox {
        clear: both;
        margin-bottom: 15px;
        margin-top: 0;
        padding: 0;
    }
    hr {
        margin-top: 10px;
        margin-bottom: 10px;
        border: 0;
        border-top: 1px solid #eee;
    }
    .laftpadd{
        padding-left: 50px;
    }
    .fadeInUp{
        padding-top: 0px;
    }
    .lately{
        padding-left: 0px;
        padding-right: 0px;
    }
    .anchorBL{display:none;}
</style>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=KW0y9CM8nvQWkWcQ4jIDTOBevgSapQQQ"></script>
<body class="gray-bg">
<div class="row">
    <div class="wrapper wrapper-content animated fadeInUp">

        <div class="col-sm-12 ui-sortable">
    <div class="ibox-title">
        <h5>
            <a  href="javascript:history.go(-1)">
                返回&nbsp;<i class='fa fa-angle-double-right'></i>
            </a>
            <label>&nbsp;查看工单详情</label>
        </h5>
    </div>
    <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>工单详情</h5>
            </div>
        <div class="ibox-content">
            <div class=" page-card-box with-margin">

                <div class="row card-body">
                    <div class="col-xs-8" style="padding-left: 50px;">
                        <div class="row">
                            <div class="col-xs-6 key-value-pair">
                                <label class="col-xs-4 name">标题: </label>
                                <label class="col-xs-8 value ng-binding">
                                    <?php if($work['name']): ?>
                                        <?php echo $work['name']; else: ?>
                                    <?php echo $work['work_type']; endif; ?>

                                </label>
                            </div>
                            <div class="col-xs-6 key-value-pair">
                            <label class="col-xs-4 name">内容: </label>
                            <label class="col-xs-8 value ng-binding"><?php echo $work['content']; ?></label>
                        </div>
                            <div class="col-xs-6 key-value-pair">
                                <label class="col-xs-4 name">备注: </label>
                                <label class="col-xs-8 value ng-binding"><?php echo $work['remark']; ?></label>
                            </div>
                            <div class="col-xs-6 key-value-pair">
                            <label class="col-xs-4 name">编号: </label>
                                <label class="col-xs-8 value ng-binding"><?php echo $work['id']; ?></label>
                            </div>
                            <div class="col-xs-6 key-value-pair">
                                <label class="col-xs-4 name">生成时间: </label>
                                <label class="col-xs-8 value ng-binding"><?php echo $work['create_time']; ?></label>
                            </div>
                            <div class="col-xs-6 key-value-pair">
                                <label class="col-xs-4 name">话务员: </label>
                                <label class="col-xs-8 value ng-binding"><?php echo $work['sname']; ?></label>
                            </div>
                            <div class="col-xs-6 key-value-pair">
                            <label class="col-xs-4 name">工单类型: </label>
                                <label class="col-xs-8 value ng-binding"><?php echo $work['work_type']; ?></label>
                            </div>
                            <div class="col-xs-6 key-value-pair">
                                <label class="col-xs-4 name">工单状态: </label>
                                <label class="col-xs-8 value ng-binding"><?php echo $work['status']; ?></label>
                            </div>
                            <div >
                                <div class="col-xs-6 key-value-pair">
                                    <label class="col-xs-4 name">计划状态: </label>
                                    <label class="col-xs-8 value ng-binding"><?php echo $work['type']; ?></label>
                                </div>
                                <div class="col-xs-6 key-value-pair">
                                    <label class="col-xs-4 name">开始时间: </label>
                                    <label class="col-xs-8 value ng-binding">
                                        <?php if($work['start']): ?>
                                        <?php echo date('Y-m-d H:i:s',$work['start']); else: ?>
                                        ---
                                        <?php endif; ?>


                                    </label>
                                </div>
                                <div class="col-xs-6 key-value-pair">
                                    <label class="col-xs-4 name">结束时间: </label>
                                    <label class="col-xs-8 value ng-binding">
                                        <?php if($work['end']): ?>
                                        <?php echo date('Y-m-d H:i:s',$work['end']); else: ?>
                                        ---
                                        <?php endif; ?>
                                        </label>
                                </div>
                                <div class="col-xs-6 key-value-pair">
                                <label class="col-xs-4 name">完成时间: </label>
                                <label class="col-xs-8 value ng-binding">
                                    <?php if($work['end_time'] != 0): ?>
                                        <?php echo date('Y-m-d H:i:s',$work['end_time']); else: ?>
                                        ---
                                    <?php endif; ?>
                                </label>
                                </div>
                            </div>
                            <div >
                                <div class="col-xs-6 key-value-pair"> <label class="col-xs-4 name">办结通话结果: </label>
                                    <label class="col-xs-8 value ng-binding"><?php echo $work['result']; ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4 text-align-right">
                            <center>
                                <?php if($quality != null): ?>
                                    <button type="button" class="btn btn-success btn-w-m" data-toggle="modal" data-target="#myModal1">&nbsp; 工单质检 &nbsp;</button>
                                    <button type="button" class="btn btn-success btn-w-m"  data-toggle="modal" data-target="#myModal2" >&nbsp; 工单退回 &nbsp;</button>

                                <?php else: if($work['state'] == 0 or $work['state'] == 1): ?>
                                        <button type="button" class="btn btn-success  btn-w-m" onclick="onAccept('<?php echo $work['id']; ?>',2)">受&nbsp;&nbsp;&nbsp;&nbsp;理</button>
                                    <?php elseif($work['state'] == 2 or $work['state'] == 5): ?>
                                        <button type="button" class="btn btn-success  btn-w-m" data-toggle="modal" data-target="#myModal4">办&nbsp;&nbsp;&nbsp;&nbsp;结</button>
                                        <br>
                                        <button type="button" class="btn btn-white btn-sm"  data-toggle="modal" data-target="#myModal5" >转交</button>
                                        <button type="button" class="btn btn-white btn-sm" onclick="delWork('<?php echo $work['id']; ?>',2)"  >关闭</button>
                                    <?php elseif($work['state'] == 3 or $work['state'] == 4 or $work['state'] == 6): ?>
                                        <button type="button" class="btn btn-success btn-lg" onclick="reopen('<?php echo $work['id']; ?>',2)">重新打开</button>
                                    <?php endif; endif; ?>

                            </center>
                       </div>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</div>
<div class="row">
    <div class="wrapper wrapper-content animated fadeInUp">
    <div class="col-sm-8 ui-sortable">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>服务对象</h5>
            </div>
            <div class="ibox-content">
                <div class=" page-card-box with-margin">
                    <div class="row card-body">
                        <div class="col-xs-12 laftpadd">
                            <div class="row">
                                <div class="col-xs-6 key-value-pair">
                                    <label class="col-xs-4 name">姓名: </label>
                                    <label class="col-xs-8 value ng-binding"><?php echo $object['name']; ?></label>
                                </div>
                                <div class="col-xs-6 key-value-pair">
                                    <label class="col-xs-4 name">年龄: </label>
                                    <label class="col-xs-8 value ng-binding"><?php echo $object['age']; ?></label>
                                </div>
                                <div class="col-xs-6 key-value-pair">
                                    <label class="col-xs-4 name">手机号码: </label>
                                    <label class="col-xs-8 value ng-binding">
                                        <?php if($type == 2 && $work['state'] == 2): ?>
                                            <a href="javascript:;" onclick="call('<?php echo $object['mobile']; ?>','<?php echo $work['id']; ?>')">
                                                <?php echo $object['mobile']; ?><i class="fa fa-phone-square" ></i></a>
                                        <?php else: ?>
                                            <?php echo $object['mobile']; endif; ?>
                                    </label>
                                </div>
                                <div class="col-xs-6 key-value-pair">
                                    <label class="col-xs-4 name">身份证号码: </label>
                                    <label class="col-xs-8 value ng-binding"><?php echo $object['id_number']; ?></label>
                                </div>
                                <div class="col-xs-6 key-value-pair">
                                    <label class="col-xs-4 name">腕表电话: </label>
                                    <label class="col-xs-8 value ng-binding">
                                        <?php if($type == 2 && $work['state'] == 2): ?>
                                            <a href="javascript:;" onclick="call('<?php echo $object['msisdn']; ?>','<?php echo $work['id']; ?>')">
                                                <?php echo $object['msisdn']; ?><i class="fa fa-phone-square"></i></a>
                                        <?php else: ?>
                                            <?php echo $object['msisdn']; endif; ?>
                                    </label>
                                </div>
                                <div class="col-xs-6 key-value-pair">
                                    <label class="col-xs-4 name">性别: </label>
                                    <label class="col-xs-8 value ng-binding">
                                        <?php if($object['sex'] == 1): ?>
                                            男
                                        <?php else: ?>
                                            女
                                        <?php endif; ?>
                                    </label>
                                </div>
                                <div class="col-xs-6 key-value-pair">
                                    <label class="col-xs-4 name">身高: </label>
                                    <label class="col-xs-8 value ng-binding">
                                        <?php if($object['height']): ?>
                                        <?php echo $object['height']; ?>CM
                                        <?php else: ?>
                                        ---
                                        <?php endif; ?>
                                        </label>
                                </div>
                                <div class="col-xs-6 key-value-pair">
                                    <label class="col-xs-4 name">体重: </label>
                                    <label class="col-xs-8 value ng-binding">
                                        <?php if($object['weight']): ?>
                                            <?php echo $object['weight']; ?>KG
                                        <?php else: ?>
                                            ---
                                        <?php endif; ?>
                                        </label>
                                </div>
                                <div >
                                    <div class="col-xs-6 key-value-pair">
                                        <label class="col-xs-4 name">分组: </label>
                                        <label class="col-xs-8 value ng-binding"><?php echo $object['group']; ?></label>
                                    </div>
                                    <div class="col-xs-6 key-value-pair">
                                        <label class="col-xs-4 name">地址: </label>
                                        <label class="col-xs-8 value ng-binding"><?php echo $object['address']; ?></label>
                                    </div>
                                    <div class="col-xs-6 key-value-pair">
                                        <label class="col-xs-4 name">标签: </label>
                                        <label class="col-xs-8 value ng-binding"><?php echo $object['label']; ?></label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div class="col-xs-12 laftpadd">
                                <h5>紧急联系人</h5>
                            <div class="row">
                                <?php if(is_array($object['emergency']) || $object['emergency'] instanceof \think\Collection || $object['emergency'] instanceof \think\Paginator): $i = 0; $__LIST__ = $object['emergency'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$emergency): $mod = ($i % 2 );++$i;?>
                                <div class="col-xs-12 key-value-pair ng-scope" >
                                    <label class="col-xs-2 name ng-binding"><?php echo $emergency['name']; ?></label>
                                    <label class="col-xs-1 value text-align-center white-space-nowrap ng-binding"><?php echo $emergency['type']; ?></label>
                                    <label class="col-xs-3 value white-space-nowrap"><span class="ng-binding">
                                         <?php if($type == 2 && $work['state'] == 2): ?>
                                            <a href="javascript:;" onclick="call('<?php echo $emergency['mobile']; ?>','<?php echo $work['id']; ?>')">
                                                <?php echo $emergency['mobile']; ?><i class="fa fa-phone-square"></i></a>
                                         <?php else: ?>
                                            <?php echo $emergency['mobile']; endif; ?>
                                    </span>
                                    </label>
                                </div>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </div>
                            <hr>
                        </div>
                        <div class="col-xs-12 laftpadd" >
                            <h5>当前积分: <?php echo $object['integral']; ?></h5>
                        </div>
                </div>
            </div>
        </div>
    </div>
    </div>
        <div class="col-sm-4 ui-sortable" style="padding-left: 0px;">
        <div class="ibox float-e-margins" style="margin-bottom: 0px;" >
            <div class="ibox-title">
                <h5>近期工单</h5>
            </div>
            <div class="ibox-content" style="height: 200px; overflow:auto;">
                <div class=" page-card-box with-margin">
                    <div class="row card-body">
                        <?php if(is_array($lately) || $lately instanceof \think\Collection || $lately instanceof \think\Paginator): $i = 0; $__LIST__ = $lately;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$late): $mod = ($i % 2 );++$i;?>
                        <div class="col-xs-12 " >
                            <div class="row ng-scope" >
                                <div class="col-xs-5 lately"><?php echo $late['create_time']; ?></div>
                                <div class="col-xs-3 lately"> <?php echo $late['display_name']; ?> </div>
                                <div class="col-xs-2 lately"> <?php echo $late['work_type']; ?> </div>
                                <div class="col-xs-2 lately"> <?php echo $late['type']; ?> </div>
                            </div>
                        </div>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
        </div>
    </div>
    </div>
        <div class="col-sm-4 ui-sortable" style="padding-left: 0px;">
            <div class="ibox float-e-margins" >
                <div class="ibox-content" style="height: 126px;padding: 2px;">
                    <div id="map" style="position: relative;left: 0;top: 0;height: 100%;"></div>
                </div>
            </div>
        </div>

    </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">

        <div class="wrapper wrapper-content animated fadeInUp">
            <div class="ibox">
                <div class="ibox-title" style="padding: 10px 6px 0px 6px ;" >
                    <h5>工单处理记录</h5>
                    <?php if($work['state'] == 3 or $work['state'] == 4 or $work['state'] == 5 or $work['state'] == 6): else: ?>
                        <div style="float: right;line-height: 20px;">
                            <button class="btn btn-white btn-sm"  data-toggle="modal" data-target="#myModal6" >发放积分</button>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="ibox-content">
                    <div class="row m-t-sm">
                        <div class="col-sm-12">
                            <div class="panel blank-panel">
                                <?php if($work['state'] != 3 && $work['state'] != 4 && $work['state'] != 5 && $work['state'] != 6): ?>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-10">
                                            <div class="ibox float-e-margins">
                                                <form  class="form-horizontal m-t" id="commentForm" >
                                                    <div class="form-group">
                                                        <label class="col-sm-1 control-label">备注: </label>
                                                        <div class="col-sm-8">
                                                            <textarea  name="content" class="form-control" required="" aria-required="true" id='text'></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-4 col-sm-offset-3">
                                                            <a class="btn btn-primary" onclick="onSublimt()">提交</a>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="wid" value="<?php echo $work['id']; ?>">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; if(is_array($workLog) || $workLog instanceof \think\Collection || $workLog instanceof \think\Paginator): $i = 0; $__LIST__ = $workLog;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vos): $mod = ($i % 2 );++$i;?>
                                <div class="timeline-item">
                                    <div class="row">
                                        <div class="col-xs-3 date">
                                            <i class="fa fa-file-text"></i> <?php echo $vos['create_time']; ?>
                                            <br>
                                        </div>
                                        <div class="col-xs-10 content">
                                            <p class="m-b-xs"><strong><?php echo $vos['content']; ?></strong>
                                            </p>
                                            <p><?php echo $vos['type']; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php if($vos['call'] != ''): ?>
                                <div class="timeline-item">
                                    <div class="row">
                                        <div class="col-xs-3 date">
                                            <i class="fa fa-phone"></i> <?php echo $vos['call']['create_time']; ?>
                                            <br>
                                        </div>
                                        <div class="col-xs-10 content">
                                            <p class="m-b-xs">
                                                <strong>坐席工号: </strong><?php echo $vos['call']['work_number']; ?> &nbsp;&nbsp;<strong>坐席分机号: </strong><?php echo $vos['call']['number']; ?><br>
                                                <strong>记录创建时间: </strong><?php echo $vos['call']['create_time']; ?> &nbsp;&nbsp;<strong>通话开始时间: </strong><?php echo date('Y-m-d H:i:s',$vos['call']['start_time']); ?>
                                                &nbsp;&nbsp;<strong>通话结束时间: </strong><?php echo date('Y-m-d H:i:s',$vos['call']['stop_time']); ?><br>
                                                <strong>主叫号码: </strong><?php echo $vos['call']['caller']; ?> &nbsp;&nbsp;<strong>被叫号码: </strong><?php echo $vos['call']['called']; ?>
                                                &nbsp;&nbsp;<strong>响铃时长: </strong><?php echo $vos['call']['ring_duration']; ?><br>
                                                <strong>通话时长: </strong><?php echo $vos['call']['duration']; ?> &nbsp;&nbsp;

                                                <strong>通话状态: </strong>
                                                <?php if($vos['call']['state'] == 0): ?>
                                                正常接通
                                                <?php elseif($vos['call']['state'] == 1): ?>
                                                通话失败
                                                <?php endif; ?>
                                                <br>


                                            <div class="addAudio" >
                                                <strong >通话录音: </strong>
                                                <?php if($vos['call']['state'] != 1): ?>
                                                <span class="getAudio"> <button type="button" class="btn btn-success btn-xs" onclick="getAudio(this,'<?php echo $vos['call']['id']; ?>')" >获取录音</button></span>
                                                <span class="isAudion" style="display: none;">123123</span>
                                                <?php else: ?>
                                                <span >本次通话记录无录音</span>
                                                <?php endif; ?>
                                            </div>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; endforeach; endif; else: echo "" ;endif; ?>


                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal inmodal" id="myModal4" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4 >办结工单</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t" id="forms2">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">通话结果*</label>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="radio radio-info radio-inline">
                                        <input type="radio" id="inlineRadio1" value="1" name="result" >
                                        <label for="inlineRadio1">正常接听</label>
                                    </div>
                                    <div class="radio radio-info radio-inline">
                                        <input type="radio" id="inlineRadio2" value="2" name="result" >
                                        <label for="inlineRadio2">未接听</label>
                                    </div>
                                    <div class="radio radio-info radio-inline">
                                        <input type="radio" id="inlineRadio3" value="3" name="result" >
                                        <label for="inlineRadio3">挂断</label>
                                    </div>
                                    <div class="radio radio-info radio-inline">
                                        <input type="radio" id="inlineRadio4" value="4" name="result" >
                                        <label for="inlineRadio4">听不清或无声</label>
                                    </div>
                                    <div class="radio radio-info radio-inline">
                                        <input type="radio" id="inlineRadio5" value="5" name="result" >
                                        <label for="inlineRadio5">其他</label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">备注：</label>
                        <div class="col-sm-10">
                            <textarea id="content" name="content" class="form-control" aria-required="true"></textarea>
                        </div>
                    </div>

                    <input type="hidden" id="ids"  name="id" value="<?php echo $work['id']; ?>" >
                </form>
            </div>


            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="endSublimt()">确定</button>
                </center>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal" id="myModal5" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4 >转交工单到 </h4>
            </div>

            <div class="modal-body">
                <form class="form-horizontal m-t" id="forms1">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">话务员*</label>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-md-10">
                                    <?php if(is_array($staff) || $staff instanceof \think\Collection || $staff instanceof \think\Paginator): $i = 0; $__LIST__ = $staff;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vos): $mod = ($i % 2 );++$i;?>
                                    <div class="radio radio-info radio-inline">
                                        <input type="radio" id="inlineRadio<?php echo $vos['number']; ?>" value="<?php echo $vos['number']; ?>" name="sid" >
                                        <label for="inlineRadio<?php echo $vos['number']; ?>"><?php echo $vos['display_name']; ?></label>
                                    </div>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="id"  name="id" value="<?php echo $work['id']; ?>" >
                </form>
            </div>
            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="infoChangeWork()">确定</button>
                </center>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal" id="myModal6" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4 >发放积分</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t" id="forms3">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">积分:</label>
                        <div class="col-sm-6" style="padding-right: 0px;">
                            <input id="integral" name="score"  class="form-control" type="number" value="0"  >
                        </div>
                        <div class="col-sm-1" style="padding-right: 60px;">
                            <button type="button" class="btn btn-white" onclick="getNumber(5)">5分</button>
                        </div>
                        <div class="col-sm-1" style="padding-left: 0px;">
                            <button type="button" class="btn btn-white" onclick="getNumber(10)" >10分</button>
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">积分内容:</label>
                        <div class="col-sm-9">
                            <textarea id="intContent" name="type" class="form-control" aria-required="true">主动关怀</textarea>
                        </div>
                    </div>

                    <input type="hidden" id="Cid"  name="uid" value="<?php echo $work['uid']; ?>" >
                </form>
            </div>


            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="addIntegral()">确定</button>
                </center>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal" id="myModal2" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4 >退回工单</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t" id="forms4">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">退回原因:</label>
                        <div class="col-sm-9">
                            <textarea  name="content" class="form-control" aria-required="true"></textarea>
                        </div>
                    </div>

                    <input type="hidden"   name="id" value="<?php echo $work['id']; ?>" >
                    <input type="hidden"   name="uid" value="<?php echo $object['id']; ?>" >
                    <input type="hidden"   name="sid" value="<?php echo $work['sid']; ?>" >
                </form>
            </div>


            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="onReject()">确定</button>
                </center>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal" id="myModal1" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <h4 >工单质检</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t" id="forms5">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">评分: </label>
                        <div class="col-sm-9">
                            <input type="number" class="input-sm form-control " name="score" placeholder="请输入评分范围 0-100分 "  />
                        </div>
                    </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label" >评级: </label>
                                <div class="col-md-9">
                                    <div class="radio radio-info radio-inline">
                                        <input type="radio" id="inlineRadioA" value="A" name="level" >
                                        <label for="inlineRadioA">A</label>
                                    </div>
                                    <div class="radio radio-inline">
                                        <input type="radio" id="inlineRadioB" value="B" name="level" >
                                        <label for="inlineRadioB">B</label>
                                    </div>
                                    <div class="radio radio-inline">
                                        <input type="radio" id="inlineRadioC" value="C" name="level" >
                                        <label for="inlineRadioC">C</label>
                                    </div>
                                    <div class="radio radio-inline">
                                        <input type="radio" id="inlineRadioD" value="D" name="level" >
                                        <label for="inlineRadioD">D</label>
                                    </div>
                                    <div class="radio radio-inline">
                                    <input type="radio" id="inlineRadioE" value="E" name="level" >
                                    <label for="inlineRadioE">E</label>
                                </div>
                            </div>
                    </div>

                    <input type="hidden"   name="id" value="<?php echo $work['id']; ?>" >
                    <input type="hidden"   name="uid" value="<?php echo $object['id']; ?>" >
                    <input type="hidden"   name="sid" value="<?php echo $work['sid']; ?>" >
                </form>
            </div>

            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="onQuality()">确定</button>
                </center>
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

<script src="/public/static/js/work/work.js"></script>
<script src="/public/static/js/work/work_info.js"></script>
