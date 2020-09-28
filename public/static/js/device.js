/**
 * 设备管理js
 * */
document.write("<script language='javascript' src='/public/static/js/bootstrap-paginator.js'></script>");
$(function(){
    /*↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓ 设备通道管理 ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓*/
    var $passageModal = '<div class="modal inmodal fade" id="passage-modal" tabindex="-1" role="dialog"  aria-hidden="true" data-backdrop="static"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title"></h1></div><form class="form-horizontal"><div class="modal-body"><div class="form-group"><label class="col-sm-2 control-label">* 通道名称</label><div class="col-sm-10"><input type="text" name="name" value="" class="form-control" required aria-required="true"></div></div><div class="form-group"><label class="col-sm-2 control-label">通道参数</label><div class="col-sm-10"><input type="text" name="param" value="" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">通道状态</label><div class="col-sm-10"><input type="checkbox" name="status" class="js-switch" checked/>&nbsp;&nbsp;状态：<span class="m-b-none switch-tip">启用</span></div></div><div class="modal-footer"><button type="reset" class="btn btn-white">重置</button><button type="submit" class="btn btn-primary">提交</button></div></form></div></div></div>',
        $editPassageId;

    /**
     * 添加通道信息
     * */
    $('.a-passage').on('click',function(){
        $editPassageId = 0;
        $('body').find('#passage-modal').remove();
        $('body').append($passageModal);
        var $modal = $('#passage-modal');
        switchJs();  // 状态
        $modal.find('.modal-title').text('添加腕表采购通道');
        $modal.modal('show');
    })

    /**
     * 编辑通道信息
     * */
    $('.e-passage').on('click',function(){
        $('body').find('#passage-modal').remove();
        $('body').append($passageModal);
        var $modal = $('#passage-modal'),
            $index = $('.e-passage').index(this),
            $passageJson = JSON.parse($devicePassage),
            $passageInfo;
        for(var i in $passageJson){
            if(i == $index){
                $passageInfo = $passageJson[i];
            }
        }
        $editPassageId = $passageInfo.id
        $modal.find('.modal-title').text('编辑腕表采购通道');
        $modal.find('[name=name]').val($passageInfo.name);
        $modal.find('[name=param]').val($passageInfo.param);
        if($passageInfo.p_status == 2){
            $modal.find('.js-switch').removeAttr('checked');
        }
        switchJs();
        $modal.modal('show');
    })

    /**
     * 提交腕表通道信息
     * */
    $('body').delegate('#passage-modal','show.bs.modal',function(){
        $(this).find('form').validate({
            rules: {
                name: {
                    required: !0
                }
            },
            messages: {
                name: {
                    required: "请填写通道名称",
                }
            },
            //验证通过后的执行方法
            submitHandler: function(form)
            {
                var $subBtn = $(form).find('[type=submit]'),
                    $fData = $(form).serializeArray();
                $fData.push({'name':'id','value':$editPassageId});
                $subBtn.attr('disabled',true);
                $.post('/index/Device/submitPassage',$fData,function(data){
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

    /*↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓ 设备列表管理 ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓*/
    var $deviceModal = '<div class="modal inmodal fade" id="device-modal" tabindex="-1" role="dialog"  aria-hidden="true" data-backdrop="static"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title">腕表信息录入</h1></div><form class="form-horizontal"><div class="modal-body"><div class="form-group"><label class="col-sm-3 control-label">* 采购通道</label><div class="col-sm-9"><select name="passage" class="form-control"></select></div></div><div class="form-group"><label class="col-sm-3 control-label">* 上传excel</label><div class="col-sm-9"><div class="input-group"><input type="text" name="device_excel" readonly class="form-control"><span class="input-group-btn" style="vertical-align: top;"><button type="button" class="btn btn-primary" id="device_excel"><i class="fa fa-cloud-upload"></i> &nbsp;上传文件</button><input class="layui-upload-file" type="file" accept="undefined" name="file"></span></div></div></div><div class="modal-footer"><button type="reset" class="btn btn-white">重置</button><button type="submit" class="btn btn-primary">提交</button></div></div></form></div></div></div>';
    /**
     * 设备数据录入
     * */
    $('.a-device').on('click',function(){
        $editPassageId = 0;
        $('body').find('#device-modal').remove();
        $('body').append($deviceModal);
        var $modal = $('#device-modal'),
            $select = $modal.find('select'),
            $passageJson = JSON.parse($passageList);
        $select.empty().append('<option value="">请选择采购通道</option>');
        for(var i in $passageJson){
            var $option = '<option value="'+$passageJson[i]['id']+'">'+$passageJson[i]['name']+'</option>';
            $modal.find('select').append($option);
        }
        iotUploadExcel({'elem':'device_excel','url':'/index/device/uploadExcel'});
        $modal.modal('show');
    })

    /**
     * 提交录入的腕表信息
     * */
    $('body').delegate('#device-modal','show.bs.modal',function(){
        $(this).find('form').validate({
            rules: {
                passage: {
                    required: !0
                },
                device_excel: {
                    required : !0
                }
            },
            messages: {
                passage: {
                    required: "请选择通道名称",
                },
                device_excel: {
                    required: "请上传excel文件"
                }
            },
            //验证通过后的执行方法
            submitHandler: function(form)
            {
                var $subBtn = $(form).find('[type=submit]'),
                    $fData = $(form).serializeArray();
                $subBtn.attr('disabled',true);
                $.post('/index/Device/submitDevice',$fData,function(data){
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
        if($.inArray($searchInfo['name'],['bind_time']) != -1){
            $form.find('[name$="'+$searchInfo['name']+'"]').val('');
        } else {
            $form.find("[name='"+$searchInfo['name']+"']").val('');
        }
        $form.submit();
    })

    /**
     * 绑定用户的modal
     * */
    var $bindUserModal = '<div class="modal inmodal fade" id="bind-user-modal" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title">绑定用户</h1></div><div class="modal-body"><form class="form-inline m-b" id="client-search"><div class="form-group"><input type="text" name="name" placeholder="姓名" class="form-control"></div> <div class="form-group"><input type="text" name="id_number" placeholder="身份证号" class="form-control"></div> <button class="btn btn-white" type="button">搜索</button></form><div><table class="table"><thead><tr><th></th><th>姓名</th><th>性别</th><th>手机</th><th>身份证</th><th>居住地址</th></tr></thead><tbody></tbody></table></div><div class="text-center"><ul class="pagination page"></ul></div></div><div class="modal-footer"><button type="button" class="btn btn-white" data-dismiss="modal">取消</button><button type="submit" class="btn btn-white">绑定</button></div></div></div></div>';

    /**
     * 绑定用户
     * */
    var $did;   // 设备id
    $('.bind-user').on('click',function(){
        // 列表页
        if($action == 'details'){
            $did = JSON.parse($deviceDetails).id;
        } else {
            var $parents = $(this).parents('tbody'),
                $index = $parents.find('tr').index($(this).parents('tr')),
                $deviceJson = JSON.parse($deviceList);
            for(var i in $deviceJson){
                if($index == i){
                    $did = $deviceJson[i]['id'];
                }
            }
        }
        $('body').find('#bind-user-modal').remove();
        $('body').append($bindUserModal);
        var $modal = $('#bind-user-modal');
        clientList();
        $modal.modal('show');
    })

    /**
     * 搜索用户
     * */
    $('body').delegate('#bind-user-modal #client-search button','click',function(){
        var $form = $(this).parents('form'),
            $name = $form.find('[name=name]').val(),
            $id_number = $form.find('[name=id_number]').val();
        var $fData = {'name':$name,'id_number':$id_number};
        clientList($fData);
    })

    /**
     * 绑定腕表
     * */
    $('body').delegate('#bind-user-modal button[type=submit]','click',function(){
        var $bindUser = $('#bind-user-modal').find('input[type=radio]:checked').val(),
            $subBtn = $(this);
        if(!$bindUser){
            layer.msg('请选择要绑定的服务对象',{icon:5,time:2000});
            return false;
        }
        $subBtn.attr('disabled','true');
        $.post('/index/Device/submitBindInfo',{'did':$did,'uid':$bindUser},function(data){
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
     * 取消绑定
     * */
    $('.cancel-bind').on('click',function(){
        var $did = JSON.parse($deviceDetails).id;
        layer.confirm(
            '确定取消用户绑定？',
            {
                btn : ['确定', '取消'],
                offset: '20%',
                shadeClose: true,
                btn1:function(obj){
                    $(".layui-layer-btn0").attr('disabled',true);
                    $.post('/index/Device/submitCancelBind',{'id':$did},function(data){
                        if(data.code == 0){
                            layer.msg(data.msg,{icon:1,time:2000},function(){
                                location.reload();
                            })
                            return false;
                        }
                        layer.msg(data.msg,{icon:5,time:2000},function(){
                            $(".layui-layer-btn0").attr('disabled',false);
                        })
                    })
                },
            })
    })

    /**
     * 设备发放
     * */
    var $sendHtml,
        $editDeviceSendNode = '<div class="form-group"><label class="col-sm-2 control-label">ICCID</label><div class="col-sm-4"><input type="text" name="iccid" class="form-control" /></div></div><div class="form-group"><label class="col-sm-2 control-label">电话号码</label><div class="col-sm-4"><input type="text" name="mobile" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">是否发放</label><div class="col-sm-4"><div class="radio radio-success radio-inline"><input type="radio" id="send1" value="1" name="is_send"><label for="send1"> 是 </label></div><div class="radio radio-success radio-inline"><input type="radio" id="send2" value="2" name="is_send"><label for="send2"> 否 </label></div></div></div><div class="form-group"><label class="col-sm-2 control-label">发放时间</label><div class="col-sm-4"><div class="input-group date"><input type="text" name="send_time" id="send_time" class="form-control layer-date"><span class="input-group-addon"><i class="fa fa-calendar"></i></span></div></div></div>',
        $editSendBut = '<button type="button" class="btn btn-white e-device-send">编辑</button>',
        $saveSendBtn = '<button type="button" class="btn btn-primary s-device-send">保存</button>&nbsp;<button type="button" class="btn btn-white c-device-send">取消</button>';
    $('body').delegate('.e-device-send','click',function(){
        var $parent = $(this).parent(),
            $parentNext = $parent.next('div'),
            $deviceDetailsInfo = JSON.parse($deviceDetails);
        $sendHtml = $parentNext.html();
        $(this).after($saveSendBtn).remove();
        $parentNext.empty().append($editDeviceSendNode);
        initDate(['send_time']);
        $parentNext.find('[name=iccid]').val($deviceDetailsInfo.iccid);
        $parentNext.find('[name=mobile]').val($deviceDetailsInfo.msisdn);
        $parentNext.find('[name=send_time]').val($deviceDetailsInfo.send_time);
        if($deviceDetailsInfo.is_send == '是'){
            $parentNext.find('#send1').attr('checked',true);
        } else {
            $parentNext.find('#send2').attr('checked',true);
        }
    })

    /**
     * 取消设备发放编辑
     * */
    $('body').delegate('.c-device-send','click',function(){
        var $parent = $(this).parent(),
            $parentNext = $parent.next('div');
        $parent.find('button').remove();
        $parent.append($editSendBut);
        $parentNext.empty().append($sendHtml);
    })

    /**
     * 提交设备发放信息
     * */
    $('body').delegate('.s-device-send','click',function(){
        var $parentNext = $(this).parent().next('div'),
            $suBtn = $(this),
            $deviceDetailsJson = JSON.parse($deviceDetails),
            $fData = {
                'id':$deviceDetailsJson.id,
                'iccid':$parentNext.find('[name=iccid]').val(),
                'mobile':$parentNext.find('[name=mobile]').val(),
                'is_send':$parentNext.find('[name=is_send]:checked').val(),
                'send_time':$parentNext.find('[name=send_time]').val()
            };
        $suBtn.attr('disabled',true);
        $.post('/index/Device/submitDeviceSend', $fData, function(data){
            if(data.code == 0){
                layer.msg(data.msg,{icon:1,time:2000},function(){
                    location.reload();
                })
                return false;
            }
            layer.msg(data.msg,{icon:5,time:2000},function(){
                $suBtn.attr('disabled',false);
            })
        })
    })

    /**
     * 设置SOS报警
     * */
    var $sosHtml,
        $sosNode = '<div class="form-group"><label class="col-sm-2 control-label">紧急联系人</label><div class="col-sm-4"><div class="row"><div class="d-sos"><div class="col-md-5 m-b"><input type="text" name="name0" placeholder="称呼" class="form-control"></div><div class="col-md-5 m-b"><input type="text" name="mobile0" placeholder="电话" class="form-control"></div></div><div class="d-sos"><div class="col-md-5 m-b"><input type="text" name="name1" placeholder="称呼" class="form-control"></div><div class="col-md-5 m-b"><input type="text" name="mobile1" placeholder="电话" class="form-control"></div></div><div class="d-sos"><div class="col-md-5 m-b"><input type="text" name="name2" placeholder="称呼" class="form-control"></div><div class="col-md-5 m-b"><input type="text" name="mobile2" placeholder="电话" class="form-control"></div></div></div></div></div>',
        $editSosBut = '<button type="button" class="btn btn-white e-device-sos">编辑</button>',
        $saveSosBtn = '<button type="submit" class="btn btn-primary s-device-sos">保存</button>&nbsp;<button type="button" class="btn btn-white c-device-sos">取消</button>'
    $('body').delegate('.e-device-sos','click',function(){
        var $parent = $(this).parent(),
            $parentNext = $parent.next('div'),
            $deviceSosJson = JSON.parse($deviceSos);
        $sosHtml = $parentNext.html();
        $(this).after($saveSosBtn).remove();
        $parentNext.find('.form-group:not(:first-child)').remove();
        $parentNext.append($sosNode);
        for(var i in $deviceSosJson){
            $parentNext.find('[name=name'+i+']').val($deviceSosJson[i]['name']).attr('f-required',true);
            $parentNext.find('[name=mobile'+i+']').val($deviceSosJson[i]['mobile']).attr('f-required',true);
        }
    })

    /**
     * 取消sos编辑
     * */
    $('body').delegate('.c-device-sos','click',function(){
        var $parent = $(this).parent(),
            $parentNext = $parent.next('div');
        $parent.find('button').remove();
        $parent.append($editSosBut);
        $parentNext.empty().append($sosHtml);
    })

    /**
     * sos输入框
     * */
    $('body').delegate('.emergency-info input','blur',function(){
        var $input = $(this).parents('.d-sos').find('input'),
            $thisVal = $(this).val(),
            $nextVal = $input.not(this).val();
        if($thisVal || $nextVal){
            $input.attr('f-required',true);
        } else {
            $input.removeAttr('f-required');
        }
    })

    /**
     * validate自定义验证联系人
     * */
    $.validator.addMethod('checkRequired', function(value, element, param) {
        if(!$(element).attr('f-required')){
            return true;
        }
        if(!$.trim(value)){
            return false;
        }
        return true;
    });

    /**
     * validate自定义验证手机号/座机号
     * */
    $.validator.addMethod('checkMobile', function(value, element, param) {
        if(!$(element).attr('f-required')){
            return true;
        }
        var $mobile = /^1[3|5|8]\d{9}$/,
            $phone = /^0\d{2,3}-?\d{7,8}$/;
        if(!$mobile.test(value) && !$phone.test(value)){
            return false;
        }
        return true;
    });

    /**
     * 提交sos信息
     * */
    $('body').delegate('.emergency-info .s-device-sos','click',function(){
        $(this).parents('form').validate({
            rules: {
                name0: {
                    checkRequired: true
                },
                mobile0: {
                    checkMobile: true
                },
                name1: {
                    checkRequired: true
                },
                mobile1: {
                    checkMobile: true
                },
                name2: {
                    checkRequired: true
                },
                mobile2: {
                    checkMobile: true
                }
            },
            messages: {
                name0: {
                    checkRequired: "请填写联系人称呼"
                },
                mobile0: {
                    checkMobile: '请填写正确的联系人电话'
                },
                name1: {
                    checkRequired: "请填写联系人称呼"
                },
                mobile1: {
                    checkMobile: '请填写正确的联系人电话'
                },
                name2: {
                    checkRequired: "请填写联系人称呼"
                },
                mobile2: {
                    checkMobile: '请填写正确的联系人电话'
                }
            },
            //验证通过后的执行方法
            submitHandler: function(form)
            {
                var $subBtn = $(form).find('[type=submit]'),
                    $fData = $(form).serializeArray();
                    $deviceDetailsJson = JSON.parse($deviceDetails);
                $fData.push({'name':'did','value':$deviceDetailsJson.id});
                $subBtn.attr('disabled',true);
                $.post('/index/Device/submitDeviceSos',$fData,function(data){
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
        });
    })

})

/**
 * 获取服务对象列表
 * */
var clientList = function(data) {
    var $data = data ? data : {},
        $tbody = $('#bind-user-modal').find('tbody');
    $tbody.empty();
    $.post('/index/device/clientList',$data,function(res){
        for(var i in res.data){
            var $tr = '<tr><td><input type="radio" name="radio" value="'+res.data[i]['id']+'"></td><td>'+res.data[i]['userName']+'</td><td>'+res.data[i]['sex']+'</td><td>'+res.data[i]['mobile']+'</td><td>'+res.data[i]['id_number']+'</td><td>'+res.data[i]['address']+'</td></tr>';
            $tbody.append($tr);
        }
        if(res.last_page == 1){
            return false;
        }
        var element = $('.pagination');
        var options = {
            bootstrapMajorVersion: 3,
            currentPage: res.current_page,
            numberOfPages: 5,
            totalPages: res.last_page,
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
                clientList($data)
            }
        }
        element.bootstrapPaginator(options);
    })
}
///**
// * 获取通道列表
// * */
//var $passageList;
//var passageList = function() {
//    $.post('/index/Device/passageList',{},function(data){
//        $passageList = data;
//        for(var i in data){
//            var $option = '<option value="'+data[i]['id']+'">'+data[i]['name']+'</option>';
//            $('.search [name=passage]').append($option);
//        }
//    })
//}