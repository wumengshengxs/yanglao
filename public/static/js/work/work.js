/**
 * 工单js
 * */
$(function(){
    /**
     * 清除搜索条件
     * */
    $('.search').parent().find('span a').on('click',function(){
        var $parent = $('.search').parent(),
            $index = $parent.find('span a').index(this),
            $form = $parent.find('form'),
            $searchJson = JSON.parse($search),
            $searchInfo;
        for(var i in $searchJson){
            if($index == i){
                $searchInfo = $searchJson[i];
            }
        }
        // 通过info查找form表单中对应的input并清除
        if($.inArray($searchInfo['name'],['create']) != -1){
            $form.find('[name$="'+$searchInfo['name']+'"]').val('');
        } else {
            $form.find("[name='"+$searchInfo['name']+"']").val('');
        }
        $form.submit();
    })

    /**
     * 工单详情的提交备注信息
     * */
    $('.f-work-remarks').validate({
        rules: {
            'remarks': {
                required: !0
            }
        },
        messages: {
            'remarks': {
                required: '请填写备注信息'
            }
        },
        submitHandler: function(form) {
            var $subBtn = $(form).find('button[type=submit]'),
                $fData = $(form).serializeArray();
            $fData.push({'name':'id','value':$work_id});
            $subBtn.attr('disabled',true);
            $.post('/index/Work/subWorkRemarks',$fData, function(data){
                if(data.code == 0){
                    var $nodes = '<div class="row"><div class="col-sm-12 m-b-sm"><label class="col-sm-2 text-right">操作日期</label><span class="col-sm-10">'+data.data.create_time+'</span></div><div class="col-sm-12 m-b-sm"><label class="col-sm-2 text-right">操作类型</label><span class="col-sm-10">工单备注</span></div><div class="col-sm-12"><label class="col-sm-2 text-right">操作内容</label><span class="col-sm-10">'+data.data.user_name+' 备注工单，内容：'+data.data.remarks+'</span></div><div class="col-sm-12"><hr/></div></div>';
                    $('.log-records').find('div:first').after($nodes);
                    $(form).find('textarea').val('');
                    layer.msg(data.msg,{icon:1,time:2000},function(){
                        $subBtn.attr('disabled',false);
                    });
                    return false;
                }
                layer.msg(data.msg,{icon:5,time:2000},function(){
                    $subBtn.attr('disabled',false);
                })
            })
        }
    })

    /**
     * 工单处理
     * */
    $('[data-class=accept-work], [data-class=transfer-work], [data-class=close-work], [data-class=open-work], [data-class=finish-work], [data-class=quality-work], [data-class=return-work]').on('click',function(){
        var $workDetails,
            $dataClass = '';
        if(typeof($details) != 'undefined'){
            $workDetails = JSON.parse($details);
            $dataClass = $(this).data('class');
        }
        if(typeof($works) != 'undefined'){
            var $tr = $(this).parents('tr'),
                $index = $('tbody').find('tr').index($tr),
                $workDetailsJson = JSON.parse($works);
            for(var i in $workDetailsJson){
                if($index == i){
                    $workDetails = $workDetailsJson[i];
                    break;
                }
            }
            $dataClass = $(this).data('class');
        }
        switch ($dataClass) {
            case ("accept-work"):
                acceptWork($workDetails);
                break;
            case ("transfer-work"):
                transferWork($workDetails);
                break;
            case ("close-work"):
                closeWork($workDetails);
                break;
            case ("open-work"):
                openWork($workDetails);
                break;
            case ("finish-work"):
                finishWork($workDetails);
                break;
            case ("quality-work"):
                qualityWork($workDetails);
                break;
            case ("return-work"):
                returnWork($workDetails);
                break;
        }
    })

    /**
     * 提交转交工单信息
     * */
    $('body').delegate('.transfer-work-modal form','submit',function(e){
        e.preventDefault();
        var $subBtn = $(this).find('[type=submit]'),
            $fData = {
                'staff':$(this).find(':radio:checked').val(),
                'work_id':$transferWorkID,
            };
        if(!$fData.staff){
            errorTips('staff','请选择要转交的话务员');
            return false;
        }
        $subBtn.attr('disabled',true);
        $.post('/index/Work/transferWork',$fData,function(data){
            if(data.code == 0){
                layer.msg(data.msg,{icon:1,time:2000},function(){
                    location.reload();
                })
                return false;
            }
            layer.msg(data.msg,{icon:2,time:2000},function(){
                $subBtn.attr('disabled',false);
            })
        })
    })

    /**
     * 提交办结工单信息
     * */
    $('body').delegate('.finish-work-modal form','submit',function(e){
        e.preventDefault();
        var $subBtn = $(this).find('[type=submit]'),
            $fData = {
                'call_result':$(this).find(':radio:checked').val(),
                'remarks':$(this).find('[name=remarks]').val(),
                'work_id':$finishWorkID,
            };
        if(!$fData.call_result){
            errorTips('call_result','请选择通话结果');
            return false;
        }
        $subBtn.attr('disabled',true);
        $.post('/index/Work/finishWork',$fData,function(data){
            if(data.code == 0){
                layer.msg(data.msg,{icon:1,time:2000},function(){
                    location.reload();
                })
                return false;
            }
            layer.msg(data.msg,{icon:2,time:2000},function(){
                $subBtn.attr('disabled',false);
            })
        })
    })

    /**
     * 创建主动外呼
     * */
    var $outboundCallModal = '<div class="modal inmodal fade outbound-call-modal" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title">主动外呼</h1></div><form class="form-horizontal"><div class="modal-body no-padding"><div class="form-group"><label class="col-sm-3 control-label" style="padding-top: inherit;">选择服务对象</label><div class="col-sm-9"><span class="ng-binding">未选择<span id="client"></span></span>&nbsp;<a class="btn btn-white btn-xs outbound-call-client" ><i class="fa fa-plus"></i>&nbsp;&nbsp;服务对象</a></div></div><div class="form-group"><label class="col-sm-3 control-label">外呼标题</label><div class="col-sm-9 outbound-call-title"></div></div></div><div class="modal-footer"><button type="submit" class="btn btn-primary">确定</button></div></form></div></div>',
        $outboundCallTitle = [{"name":"测试"},{"name":"设备维修"},{"name":"设备使用情况"},{"name":"老人安危"},{"name":"核实信息"}];
    $('.outbound-call').on('click',function(){
        $('body').find('.outbound-call-modal').remove();
        $('body').append($outboundCallModal);
        $outboundCallClientInfo = '';
        var $modal = $('.outbound-call-modal');
        $modal.modal('show');
        for(var i in $outboundCallTitle){
            var $nodes = '<div class="radio radio-success radio-inline"><input type="radio" id="outbound_call_title'+i+'" value="'+$outboundCallTitle[i]['name']+'" name="outbound_call_title_radio"><label for="outbound_call_title'+i+'">'+$outboundCallTitle[i]['name']+'</label></div>';
            $modal.find('.outbound-call-title').append($nodes);
        }
        var $input = '<br/><input type="text" value="" name="outbound_call_title_input" class="form-control m-t-sm" style="width: 30%;" placeholder="自定义标题">';
        $modal.find('.outbound-call-title').append($input);
    })

    /**
     * 选择外呼的服务对象
     * */
    var $outboundCallClientModal = '<div class="modal inmodal fade outbound-call-client-modal" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title">主动外呼服务对象</h1></div><div class="modal-body"><div class="row"><form class="form-inline m-b-sm"><div class="form-group"><label class="sr-only">用户名</label><input type="text" name="name" placeholder="请输入服务对象" class="form-control"></div>&nbsp;<div class="form-group"><label class="sr-only">分组</label><select name="group" class="chosen-group form-control"></select></div>&nbsp;<div class="form-group"><label class="sr-only">标签</label><select name="tag" class="chosen-tag form-control"></select></div>&nbsp;<button class="btn btn-white btn-search" type="button">搜索</button></form><table class="table table-responsive table-bordered"><thead><tr><th>姓名</th><th>性别</th><th>身份证</th><th>电话</th><th>地址</th></tr></thead><tbody></tbody></table><div class="text-center"><ul class="pagination"></ul></div></div></div><div class="modal-footer"><button type="button" class="btn btn-primary btn-sure">确定</button></div></div></div>',
    $outboundCallClientInfo;    // 已选择的服务对象信息
    $('body').delegate('.outbound-call-client','click',function(){
        $('body').find('.outbound-call-client-modal').remove();
        $('body').append($outboundCallClientModal);
        var $modal = $('.outbound-call-client-modal');
        $modal.modal('show');
    })

    /**
     * modal弹出时执行
     * */
    $('body').delegate('.outbound-call-client-modal','show.bs.modal',function(){
        getClient('',$outboundCallClientInfo);
        getGroup();
        getTag();
    })

    /**
     * 搜索外呼服务对象
     * */
    $('body').delegate('.outbound-call-client-modal button.btn-search','click',function(){
        var $form = $(this).parents('form');
            $data = {
            'name':$form.find('input[name=name]').val(),
            'group':$form.find('.chosen-group').val(),
            'tag':$form.find('.chosen-tag').val()
        };
        getClient($data,$outboundCallClientInfo);
    })

    /**
     * 选中服务对象
     * */
    $('body').delegate('.outbound-call-client-modal .radio','mousedown',function(event){
        var $isChecked = $(this).find('input').prop('checked'),
            $index = $('.outbound-call-client-modal .radio').index(this);
        $(this).find('input').prop('checked',!$isChecked);
        if($isChecked){
            $outboundCallClientInfo = '';
            return false;
        }
        for(var i in $clientList){
            if($index == i){
                $outboundCallClientInfo = $clientList[i];
            }
        }
        return false;
    })

    /**
     * 阻止radio按钮的click事件
     * */
    $('body').delegate('.outbound-call-client-modal :radio','click',function(event){
        return false;
    })

    /**
     * 确定选择的服务对象
     * */
    $('body').delegate('.outbound-call-client-modal button.btn-sure','click',function(){
        $('.outbound-call-client-modal').modal('hide');
        if(!$outboundCallClientInfo){
            $('.outbound-call-modal .ng-binding').html('未选择<span id="client"></span>');
            return false;
        }
        $('.outbound-call-modal .ng-binding').html('已选择：<span id="client"></span>');
        $('.outbound-call-modal #client').text($outboundCallClientInfo['userName']);
    })

    /**
     * 选择外呼标题
     * */
    $('body').delegate('.outbound-call-modal :radio','click',function(){
        $('.outbound-call-title input[name=outbound_call_title_input]').val('');
    })

    /**
     * 自定义标题
     * */
    $('body').delegate('.outbound-call-modal .outbound-call-title input','keyup',function(){
        if($.trim($(this).val())){
            $('.outbound-call-title input:radio').prop('checked',false);
        }
    })

    /**
     * 提交主动外呼工单
     * */
    $('body').delegate('.outbound-call-modal form','submit',function(e){
        e.preventDefault();
        var $subBtn = $(this).find('button[type=submit]'),
            $titleRadio = $(this).find('.outbound-call-title input[type=radio]:checked').val(),
            $titleInput = $(this).find('.outbound-call-title input[name=outbound_call_title_input]').val();
            $clientID = (typeof($outboundCallClientInfo) != 'undefined') ?  $outboundCallClientInfo['id'] : 0 ,
            $fData = {
                'client':$clientID,
                'title':$titleRadio ? $titleRadio : $titleInput
            };
        if(!$.trim($fData.title)){
            errorTips('title','请选择或自定义外呼标题');
            return false;
        }
        $subBtn.attr('disabled',true);
        $.post('/index/Work/outboundCall',$fData,function(data){
            if(data.code == 0){
                layer.msg(data.msg,{icon:1,time:2000},function(){
                    location.reload();
                });
                return false;
            }
            layer.msg(data.msg,{icon:2,time:2000},function(){
                $subBtn.attr('disabled',false);
            })
        })
    })

    /**
     * 提交评分结果
     * */
    $('body').delegate('.quality-work-modal .modal-footer button','click',function(){
        var $subBtn = $(this),
            $fData = {
                'work_id':$qualityWorkID,
                'score':$qualityWorkScore
            };
        $subBtn.attr('disabled',true);
        $.post('/index/Work/qualityWork',$fData,function(data){
            if(data.code == 0){
                layer.msg(data.msg,{icon:1,time:2000},function(){
                    location.reload();
                })
                return false;
            }
            layer.msg(data.msg,{icon:2,time:2000},function(){
                $subBtn.attr('disabled',false);
            })
        })
    })

    /**
     * 提交退回工单信息
     * */
    $('body').delegate('.return-work-modal','show.bs.modal',function(){
        $(this).find('form').validate({
            rules: {
                'remarks': {
                    required: !0
                }
            },
            messages: {
                'remarks': {
                    required: '请填写退回原因'
                }
            },
            submitHandler: function(form) {
                var $subBtn = $(form).find('[type=submit]'),
                    $fData = {
                        'remarks':$(form).find('[name=remarks]').val(),
                        'work_id':$returnWorkID
                    }
                $subBtn.attr('disabled',true);
                $.post('/index/Work/returnWork',$fData,function(data){
                    $icon = (data.code == 0) ? 1 : 2 ;
                    layer.msg(data.msg,{icon:$icon,time:2000},function(){
                        location.reload();
                    })
                })
            }
        })
    })

})

/**
 * 受理工单
 * */
var acceptWork = function($details) {
    if($details.state != '未受理'){
        layer.msg('工单不能重复受理',{icon:2,time:2000});
        return false;
    }
    layer.confirm(
        '确定受理工单：'+$details.title+'？',
        {
            btn : ['确定', '取消'],
            offset: '20%',
            shadeClose: true,
            btn1:function(obj){
                $(".layui-layer-btn0").attr('disabled',true);
                $.post('/index/Work/acceptWork',{'id':$details.id},function(data){
                    if(data.code != '0'){
                        layer.msg(data.msg,{icon:2,time:2000},function(){
                            $(".layui-layer-btn0").attr('disabled',false);
                        })
                        return false;
                    }
                    layer.msg(data.msg,{icon:1,time:2000},function(){
                        if($action == 'works'){
                            window.location.href = '/index/Work/workDetails?id='+$details.id;
                        } else {
                            location.reload();
                        }
                    })
                })
            },
        })
}

/**
 * 转交工单
 * */
var $transferWorkModal = '<div class="modal inmodal fade transfer-work-modal" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title">转交工单</h1></div><form class="form-horizontal"><div class="modal-body no-padding"><div class="form-group"><label class="col-sm-2 control-label">话务员</label><div class="col-sm-10"></div></div></div><div class="modal-footer"><button type="submit" class="btn btn-primary">确定</button></div></form></div></div>',
    $transferWorkID;
var transferWork = function($details) {
    $('body').find('.transfer-work-modal').remove();
    $('body').append($transferWorkModal);
    var $modal = $('.transfer-work-modal');
    $transferWorkID = $details.id;
    $modal.modal('show');
    // 获取话务员
    $.post('/index/Work/staffUser',{},function(data){
        for(var i in data){
            var $nodes = '<div class="radio radio-success radio-inline"><input type="radio" id="staff'+i+'" value="'+data[i]['number']+'" name="staff"><label for="staff'+i+'"> '+data[i]['display_name']+' </label></div>';
            $modal.find('.form-group div:first').append($nodes);
        }
    })
}

/**
 * 关闭工单
 * */
var closeWork = function($details) {
    if($.inArray($details.state,['已关闭','已办结']) != -1){
        layer.msg('已'+$details.state+'的工单不能关闭',{icon:2,time:2000});
        return false;
    }
    layer.confirm(
        '确定关闭工单：'+$details.title+'？',
        {
            btn : ['确定', '取消'],
            offset: '20%',
            shadeClose: true,
            btn1:function(obj){
                $(".layui-layer-btn0").attr('disabled',true);
                $.post('/index/Work/closeWork',{'id':$details.id},function(data){
                    if(data.code == '0'){
                        layer.msg(data.msg,{icon:1,time:2000},function(){
                            location.reload();
                        })
                        return false;
                    }
                    layer.msg(data.msg,{icon:2,time:2000},function(){
                        $(".layui-layer-btn0").attr('disabled',false);
                    })
                })
            },
        })
}

/**
 * 重新打开工单
 * */
var openWork = function($details) {
    if($.inArray($details.state,['未分配','未受理','受理中']) != -1){
        layer.msg($details.state+'的工单不能重新打开',{icon:2,time:2000});
        return false;
    }
    layer.confirm(
        '确定重新打开工单：'+$details.title+'？',
        {
            btn : ['确定', '取消'],
            offset: '20%',
            shadeClose: true,
            btn1:function(obj){
                $(".layui-layer-btn0").attr('disabled',true);
                $.post('/index/Work/openWork',{'id':$details.id},function(data){
                    if(data.code == '0'){
                        layer.msg(data.msg,{icon:1,time:2000},function(){
                            location.reload();
                        })
                        return false;
                    }
                    layer.msg(data.msg,{icon:2,time:2000},function(){
                        $(".layui-layer-btn0").attr('disabled',false);
                    })
                })
            },
        })
}

/**
 * 办结工单
 * */
var $finishWorkModal = '<div class="modal inmodal fade finish-work-modal" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title">办结工单</h1></div><form class="form-horizontal"><div class="modal-body"><div class="form-group"><label class="col-sm-2 control-label">通话结果</label><div class="col-sm-10"></div></div><div class="form-group"><label class="col-sm-2 control-label">办结备注</label><div class="col-sm-10"><textarea name="remarks" cols="30" rows="3" class="form-control"></textarea></div></div></div><div class="modal-footer"><button type="submit" class="btn btn-primary">确定</button></div></form></div></div>',
    $callResult = [{"id":1,"name":"正常接听"},{"id":2,"name":"未接听"},{"id":3,"name":"挂断"},{"id":4,"name":"听不清或无声"},{"id":5,"name":"SOS报警外呼"},{"id":6,"name":"回访"},{"id":7,"name":"联系亲属"}],
    $finishWorkID;
var finishWork = function($details) {
    if($details.state != '受理中'){
        layer.msg($details.state+'的工单不能办结',{icon:2,time:2000});
        return false;
    }
    $('body').find('.finish-work-modal').remove();
    $('body').append($finishWorkModal);
    $finishWorkID = $details.id;
    $modal = $('.finish-work-modal');
    for(var i in $callResult){
        var $nodes = '<div class="radio radio-success radio-inline"><input type="radio" id="call_result'+i+'" value="'+$callResult[i]['id']+'" name="call_result"><label for="call_result'+i+'">'+$callResult[i]['name']+'</label></div>';
        $modal.find('.form-group:first div:first').append($nodes);
    }
    $modal.modal('show');
}

/**
 * 质检工单
 * */
var $qualityWorkModal = '<div class="modal inmodal fade quality-work-modal" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title">工单质检</h1></div><div class="modal-body"></div><div class="modal-footer"><span class="pull-left" style="font-size: 16px;font-weight: 900;margin-left: 20px;">质检得分：<span id="total-score">0</span></span><button type="button" class="btn btn-primary">确定</button></div></div></div>',
    $qualityWorkContent = [{'title':"一般礼仪方面",'score':20,'content':['开头语','候线处理(在线等待、转接电话)','有对客户称呼','对客户的请求有无及时反馈','结束用语']},{'title':"业务知识方面",'score':20,'content':['表达清晰/突出重点','业务信息熟练掌握','业务信息的运用能力','参老资料的灵活运用','熟练掌握业务流程']},{'title':'沟通技能方面','score':24,'content':['理解能力/归纳能力','提问能力/有效了解客户需要','倾听能力/有效沟通','有效处理异议','适时掌握沟通节奏，有效控制时间','熟悉内部政策及操作流程']},{'title':'服务态度方面','score':16,'content':['耐心','热情/亲切','主动/反馈和回应','适时使用礼貌用语']},{'title':'语言表达方面','score':8,'content':['条理清晰/语言组织简洁','方式适当/语音、语调、语速']},{'title':'电话流程方面','score':8,'content':['闭环沟通','主动引导']}],
    $qualityWorkID;
var qualityWork = function($details) {
    $('body').find('.quality-work-modal').remove();
    $('body').append($qualityWorkModal);
    var $modal = $('.quality-work-modal');
    $qualityWorkID = $details.id;
    for(var i in $qualityWorkContent){
        var $table = `<table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>${$qualityWorkContent[i]['title']}</th>
                                <th>得分</th>
                                <th>总分：<span class="table-score">0</span>/${$qualityWorkContent[i]['score']}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>`;
        $modal.find('.modal-body').append($table);
        for(var j in $qualityWorkContent[i]['content']){
            var $tbody = '<tr><td class="text-left">'+$qualityWorkContent[i]['content'][j]+'</td><td><div class="rating"></div></td><td>0</td></tr>';
            $modal.find('table').eq(i).append($tbody);
        }
    }
    $modal.find('th').attr('style','background-color:#F5F5F6 !important;color:black !important;width:35%;');
    $modal.modal('show');
}

/**
 * 评分插件
 * */
var $qualityWorkScore = 0;
$('body').delegate('.quality-work-modal','show.bs.modal',function(){
    $('.rating').raty({
        cancel: true,
        cancelHint: '0分',
        number: 4,
        hints:['1分','2分','3分','4分'],
        cancelOff: '/public/static/rating/img/cancel-custom-off.png',
        cancelOn: '/public/static/rating/img/cancel-custom-on.png',
        starOn: '/public/static/rating/img/star-on.png',
        starOff: '/public/static/rating/img/star-off.png',
        width: '100%',
        click: function(score, evt) {
            $score = (score == null) ? 0 : score;
            var $total = $('.modal-footer #total-score'),
                $thisTable = $(this).parents('table').find('.table-score'),
                $thisNext = $(this).parents('td').next(),
                $diffTable = parseInt($thisTable.text())+(parseInt($score)-parseInt($thisNext.text())),
                $diffTotal = parseInt($total.text())+($diffTable-parseInt($thisTable.text()));
            $thisNext.text($score);
            $thisTable.text($diffTable);
            $total.text($diffTotal);
            $qualityWorkScore = $diffTotal;
        }
    });
})

/**
 * 退回工单
 * */
var $returnWorkModal = '<div class="modal inmodal fade return-work-modal" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title">退回工单</h1></div><form class="form-horizontal"><div class="modal-body"><div class="form-group"><label class="col-sm-2 control-label">退回原因</label><div class="col-sm-10"><input type="text" name="remarks" class="form-control"></div></div></div><div class="modal-footer"><button type="submit" class="btn btn-primary">确定</button></div></form></div></div>',
    $returnWorkID;
var returnWork = function($details) {
    $('body').find('.return-work-modal').remove();
    $('body').append($returnWorkModal);
    var $modal = $('.return-work-modal');
    $returnWorkID = $details.id;
    $modal.modal('show');
}

/**
 * 获取服务对象
 * */
var $clientList;
var getClient = function(data,checkedClient) {
    var $data = data ? data : {},
        $checkedClient = checkedClient ? checkedClient : {},
        $tbody = $('.outbound-call-client-modal tbody');
    $tbody.empty();
    $.post('/index/Integration/clientList',$data,function(msg){
        $clientList = msg.data;
        for(var i in $clientList){
            var $checked = ($clientList[i]['id'] == $checkedClient['id']) ? 'checked' : '';
            var $tr = '<tr><td><div class="radio radio-success no-padding m-n"><input type="radio" id="client'+i+'" value="'+$clientList[i]['id']+'" name="client" '+$checked+'><label for="client'+i+'"> '+$clientList[i]['userName']+' </label></div></td><td>'+$clientList[i]['sex']+'</td><td>'+$clientList[i]['id_number']+'</td><td>'+$clientList[i]['mobile']+'</td><td>'+$clientList[i]['address']+'</td></tr>';
            $tbody.append($tr);
        }
        // 分页
        var element = $('.outbound-call-client-modal .pagination');
        var options = {
            bootstrapMajorVersion: 3,
            currentPage: msg.current_page,
            numberOfPages: 5,
            totalPages: msg.last_page,
            itemTexts: function (type, page, current) {//如下的代码是将页眉显示的中文显示我们自定义的中文。
                switch (type) {
                    case "first": return "首页";
                    case "prev": return "上一页";
                    case "next": return "下一页";
                    case "last": return "末页";
                    case "page": return page;
                }
            },
            onPageChanged:function (event, originalEvent, typePage, currentPage) {
                $data['page'] = typePage;
                getClient($data,$checkedClient);
            }
        }
        element.bootstrapPaginator(options);
    })
}

/**
 * 获取全部分组
 * */
var getGroup = function() {
    $.post('/index/Integration/groupList',{},function(data){
        $group = data.data;
        var $parent = $('.outbound-call-client-modal .chosen-group');
        $parent.append('<option value="">请选择分组</option>');
        for(var i in $group){
            var $option = '<option value="'+$group[i]['id']+'">'+$group[i]['name']+'</option>';
            $parent.append($option);
        }
        $parent.chosen();
    })
}

/**
 * 获取标签
 * */
var getTag = function() {
    $.post('/index/Integration/tagList',{},function(data){
        var $parent = $('.outbound-call-client-modal .chosen-tag');
        $parent.append('<option value="">请选择标签</option>');
        for(var i in data){
            var $option = '<option value="'+data[i]['id']+'">'+data[i]['name']+'</option>';
            $parent.append($option);
        }
        $parent.chosen();
    })
}

/**
 * 时间插件
 * */
layui.use('laydate', function(){
    var laydate = layui.laydate;
    //执行一个laydate实例
    lay('.times').each(function(){
        laydate.render({
            elem: this,
            trigger: 'click'
        });
    });
});