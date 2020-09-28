/**
 * 计划任务js
 * */
document.write("<script language='javascript' src='/public/static/js/transfer-frame.js'></script>");
var $planSelectedClient = [];
$(function(){
    intLayDate();
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
     * 计划任务选择服务对象
     * */
    $('.b-grant-workPlan').on('click',function(){
        $('body').find('.transfer-frame').remove();
        $('body').append($transferFrameModal);
        var $modal = $('.transfer-frame');
        // 获取对应的服务对象
        getClientList();
        // 获取分组列表
        groupList();
        // 获取标签列表
        tagList();
        $modal.modal('show');
    })

    /**
     * 确定已选择的服务对象
     * */
    $('body').delegate('.transfer-frame .t-f-bottom button:first-child','click',function(){
        var $selectClientIds = [];
        // 判断是否选中服务对象
        if(!$selectedClient.length){
            errorTips('client','请选择服务对象');
            return false;
        }
        for(var i in $selectedClient){
            $selectClientIds.push($selectedClient[i]['id']);
        }
        $('input[name=client]').val($selectClientIds);
        $('#nums').text($selectClientIds.length);
        $planSelectedClient = $selectedClient;
        $('.transfer-frame').modal('hide');
    })

    /**
     * validate自定义验证话务员
     * */
    $.validator.addMethod('checkStaff', function(value, element, param) {
        var $staff = $('.form-plan').find('input[name="staff[]"]:checked').length;
        if($staff){
            return true;
        }
        return false;
    });

    /**
     * validate自定义验证截止时间应大于开始时间
     * */
    $.validator.addMethod('checkEndTime', function(value, element, param) {
        var $start_time = $('.form-plan input[name=start_time]').val();
        $start = new Date(Date.parse($start_time.replace("-", "/")));
        $end = new Date(Date.parse(value.replace("-", "/")));
        return $start <= $end;
    });

    /**
     * 提交新建计划组信息
     * */
    $('.form-plan').validate({
        rules: {
            'name': {
                required: !0
            },
            'start_time': {
                required: !0
            },
            'end_time': {
                required: !0,
                checkEndTime: true
            },
            'client': {
                required: !0
            },
            'staff[]': {
                checkStaff: true
            }
        },
        messages: {
            'name': {
                required: '请填写计划任务名称'
            },
            'start_time': {
                required: '请选择开始时间'
            },
            'end_time': {
                required: '请选择截止时间',
                checkEndTime: '开始时间应不大于截止时间'
            },
            'client': {
                required: '请选择服务对象'
            },
            'staff[]': {
                checkStaff: '请选择话务员'
            }
        },
        submitHandler: function(form){
            var $subBtn = $(form).find('[type=submit]'),
                $fData = $(form).serializeArray();
            $.post('/index/Work/subPlanGroup',$fData,function(data){
                if(data.code == 0){
                    layer.msg(data.msg,{icon:1,time:2000},function(){
                        window.location.href = '/index/Work/planGroup';
                    })
                    return false;
                }
                layer.msg(data.msg,{icon:5,time:2000},function(){
                    $subBtn.attr('disabled',false);
                })
            })
        }
    })

    /**
     * 编辑计划组的modal
     * */
    var $editPlanGroupModal = '<div class="modal inmodal fade edit-plan-group-modal" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog" style="width: 40%;"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title">编辑计划任务</h1></div><div class="modal-body"><form class="form-horizontal form-plan"><div class="modal-body"><div class="form-group"><label class="col-sm-2 control-label">计划名称</label><div class="col-sm-10"><input type="text" name="name" value="" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">开始时间</label><div class="col-sm-10"><div class="input-group date"><input type="text" name="start_time" class="form-control times"><span class="input-group-addon"><i class="fa fa-calendar"></i></span></div></div></div><div class="form-group"><label class="col-sm-2 control-label">截止时间</label><div class="col-sm-10"><div class="input-group date"><input type="text" name="end_time" class="form-control times"><span class="input-group-addon"><i class="fa fa-calendar"></i></span></div></div></div><div class="form-group"><label class="col-sm-2 control-label">计划组状态</label><div class="col-sm-10"><div class="radio radio-success radio-inline"><input type="radio" id="state1" name="state" value="1"><label for="state1"> 启用 </label></div><div class="radio radio-success radio-inline"><input type="radio" id="state2" name="state" value="2" checked><label for="state2"> 草稿 </label></div></div></div></div><div class="modal-footer"><button type="submit" class="btn btn-primary">确定</button><button type="button" class="btn btn-white" data-dismiss="modal">取消</button></div></form></div></div></div>',
        $planGroupID;

    /**
     * 编辑计划组
     * */
    $('[data-class=edit-plan-group], [data-class=enable-plan-group], [data-class=del-plan-group], [data-class=delay-plan-group]').on('click',function(){
        var $planGroupDetails,
            $dataClass = '';
        if(typeof($details) != 'undefined'){
            $planGroupDetails = JSON.parse($details);
            $dataClass = $(this).data('class');
        }
        if(typeof($planGroup) != 'undefined'){
            var $tr = $(this).parents('tr'),
                $index = $('tbody').find('tr').index($tr),
                $planGroupJson = JSON.parse($planGroup);
            for(var i in $planGroupJson){
                if($index == i){
                    $planGroupDetails = $planGroupJson[i];
                    break;
                }
            }
            $dataClass = $(this).data('class');
        }
        switch ($dataClass) {
            case ('edit-plan-group'):
                editPlanGroup($planGroupDetails);
                break;
            case ('enable-plan-group'):
                enablePlanGroup($planGroupDetails);
                break;
            case ('del-plan-group'):
                delPlanGroup($planGroupDetails);
                break;
            case ('delay-plan-group'):
                delayPlanGroup($planGroupDetails);
                break;
        }
    })

    /**
     * 编辑计划任务弹出层
     * */
    var editPlanGroup = function($details) {
        $('body').find('.edit-plan-group-modal').remove();
        $('body').append($editPlanGroupModal);
        var $modal = $('.edit-plan-group-modal');
        intLayDate();
        $planGroupID = $details.id;
        $modal.find('input[name=name]').val($details.name);
        $modal.find('input[name=start_time]').val($details.start_time);
        $modal.find('input[name=end_time]').val($details.end_time);
        if($details.state == 1){
            $modal.find('input[name=state]').parents('.form-group').remove();
        }
        $modal.modal('show');
    }

    /**
     * 提交的编辑计划任务
     * */
    $('body').delegate('.edit-plan-group-modal','show.bs.modal',function(){
        $(this).find('form').validate({
            rules: {
                'name': {
                    required: !0
                },
                'start_time': {
                    required: !0
                },
                'end_time': {
                    required: !0,
                    checkEndTime: true
                }
            },
            messages: {
                'name': {
                    required: '请填写计划任务名称'
                },
                'start_time': {
                    required: '请选择开始时间'
                },
                'end_time': {
                    required: '请选择截止时间',
                    checkEndTime: '开始时间应不大于截止时间'
                }
            },
            submitHandler: function(form){
                var $subBtn = $(form).find('[type=submit]'),
                    $fData = $(form).serializeArray();
                $fData.push({'name':'id','value':$planGroupID});
                $.post('/index/Work/subPlanGroup',$fData,function(data){
                    if(data.code == 0){
                        layer.msg(data.msg,{icon:1,time:2000},function(){
                            location.reload();
                        })
                        return false;
                    }
                    layer.msg(data.msg,{icon:5,time:2000},function(){
                        $subBtn.attr('disabled',false);
                    })
                })
            }
        })
    })

    /**
     * 启用计划任务
     * */
    var enablePlanGroup = function($details) {
        if($details.state != 2){
            layer.msg('计划任务不能重复启用',{icon:2,time:2000});
            return false;
        }
        layer.confirm(
            '<h3>确定启用计划任务：'+$details.name+'？</h3><h6>启用后将生成对应工单</h6>',
            {
                btn : ['确定', '取消'],
                offset: '20%',
                shadeClose: true,
                btn1:function(obj){
                    $(".layui-layer-btn0").attr('disabled',true);
                    $.post('/index/Work/enablePlanGroup',{'id':$details.id},function(data){
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
     * 删除计划任务
     * */
    var delPlanGroup = function($details) {
        if($details.state != 2){
            layer.msg('已启用的任务不能删除',{icon:2,time:2000});
            return false;
        }
        layer.confirm(
            '确定删除计划任务：'+$details.name+'？',
            {
                btn : ['确定', '取消'],
                offset: '20%',
                shadeClose: true,
                btn1:function(obj){
                    $(".layui-layer-btn0").attr('disabled',true);
                    $.post('/index/Work/delPlanGroup',{'id':$details.id},function(data){
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
     * 计划任务延期
     * */
    var $delayModal = '<div class="modal inmodal fade delay-plan-group-modal" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title">计划任务延期</h1></div><div class="modal-body"><form class="form-horizontal form-plan"><input type="hidden" name="end_time" value=""><div class="modal-body"><div class="form-group"><label class="col-sm-2 control-label">延期至</label><div class="col-sm-10"><div class="input-group date"><input type="text" name="delay_time" class="form-control times"><span class="input-group-addon"><i class="fa fa-calendar"></i></span></div></div></div></div><div class="modal-footer"><button type="submit" class="btn btn-primary">确定</button><button type="button" class="btn btn-white" data-dismiss="modal">取消</button></div></form></div></div></div>';

    var delayPlanGroup = function($details) {
        $('body').find('.delay-plan-group-modal').remove();
        $('body').append($delayModal);
        var $modal = $('.delay-plan-group-modal');
        intLayDate();
        $planGroupID = $details.id;
        $modal.find('input[name=end_time]').val($details.end_time);
        $modal.modal('show');
    }

    /**
     * 提交延期信息
     * */
    $('body').delegate('.delay-plan-group-modal form','submit',function(e){
        e.preventDefault();
        var $end_time = $(this).find('input[name=end_time]').val(),
            $subBtn = $(this).find('[type=submit]'),
            $fData = {
                'delay_time':$(this).find('input[name=delay_time]').val(),
                'id':$planGroupID
            };
        if(!$fData.delay_time){
            errorTips('delay_time','请选择延期日期');
            return false;
        }
        if(new Date(Date.parse($end_time.replace("-", "/"))) >= new Date(Date.parse($fData.delay_time.replace("-", "/")))){
            errorTips('delay_time','延期日期不能小于当前的截止日期');
            return false;
        }
        $subBtn.attr('disabled',true);
        $.post('/index/Work/delayPlanGroup',$fData,function(data){
            if(data.code == 0){
                layer.msg(data.msg,{icon:1,time:2000},function(){
                    location.reload();
                })
                return false;
            }
            layer.msg(data.msg,{icon:5,time:2000},function(){
                $subBtn.attr('disabled',false);
            })
        })
    })

    /**
     * modal打开时右侧展示已选中的数据
     * */
    $('body').delegate('.transfer-frame','show.bs.modal',function(){
        for(var i in $planSelectedClient){
            var $node = ' <tr><td>'+$planSelectedClient[i]['userName']+'</td><td>'+$planSelectedClient[i]['sex']+'</td><td>'+$planSelectedClient[i]['id_number']+'</td><td>'+$planSelectedClient[i]['mobile']+'</td><td>'+$planSelectedClient[i]['address']+'</td><td><a><i class="fa fa-close"></i></a></td></tr>';
            $('.transfer-frame .t-f-t-right tbody').append($node);
        }
    })

    /**
     * 根据话务员获取对应的工单
     * */
    $('.staff div').on('click',function(){
        $('.staff div').removeClass('active');
        $(this).addClass('active');
        // 获取选择的话务员工号
        var $staffNumber,
            $index = parseInt($('.staff div').index(this))-1,
            $detailsJson = JSON.parse($details);
        for(var i in $detailsJson.staff){
            if($index == i){
                $staffNumber = $detailsJson.staff[i]['number'];
                break;
            }
        }
        planGroupWork({'plan_id':$plan_id,'staff':$staffNumber});
    })

})

/**
 * 获取计划任务的工单
 * */
var planGroupWork = function(data) {
    var $data = data ? data : {};
    var $table = $('body').find('tbody');
    $table.empty();
    $.post('/index/Work/planGroupWork',$data,function(msg){
        for(var i in msg.data){
            var $tr = '<tr><td>'+msg.data[i]['create_time']+'</td><td>'+msg.data[i]['start_time']+'</td><td>'+msg.data[i]['end_time']+'</td><td>'+msg.data[i]['finish_time']+'</td><td>'+msg.data[i]['userName']+'</td><td>'+msg.data[i]['plan_state']+'</td><td><a href="/index/Work/workDetails?id='+msg.data[i]['id']+'">查看</a></td></tr>';
            $table.append($tr);
        }
        if(msg.last_page <= 1){
            return false;
        }
        // 分页
        var element = $('.pagination');
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
                planGroupWork($data);
            }
        }
        element.bootstrapPaginator(options);
    })
}

/**
 * 时间插件
 * */
var intLayDate = function() {
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
}