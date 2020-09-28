/**
 * 服务对象js
 * */
$(function(){
    /* ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓服务对象↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓ */
    /**
     * 添加服务对象modal
     * */
    var $clientModal = '<div class="modal inmodal fade" id="client-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title">添加服务对象</h1></div><form class="form-horizontal"><div class="modal-body"><div class="form-group"><label class="col-sm-2 control-label">头像</label><div class="col-sm-10"><div class="upload-file style-h" title="点击上传头像"><img src="/public/static/img/head.jpg"><div class="layui-btn" id="head"><i class="fa fa-upload"></i></div><input class="layui-upload-file" type="file" name="file"></div></div></div><div class="form-group"><label class="col-sm-2 control-label">* 姓名</label><div class="col-sm-10"><input type="text" name="name" value="" class="form-control" required placeholder="姓名"></div></div><div class="form-group"><label class="col-sm-2 control-label">* 身份证</label><div class="col-sm-10"><input type="text" name="id_number" value="" class="form-control" required placeholder="15位或18位身份证号"></div></div><div class="form-group"><label class="col-sm-2 control-label">性别</label><div class="col-sm-10"><div class="radio radio-success radio-inline"><input type="radio" id="sex1" value="1" name="sex"><label for="sex1"> 男 </label></div><div class="radio radio-success radio-inline"><input type="radio" id="sex2" value="2" name="sex"><label for="sex2"> 女 </label></div></div></div><div class="form-group"><label class="col-sm-2 control-label">出生日期</label><div class="col-sm-10"><div class="input-group date"><input type="text" name="birthday" id="birthday" class="form-control layer-date"><span class="input-group-addon"><i class="fa fa-calendar"></i></span></div></div></div><div class="form-group"><label class="col-sm-2 control-label">电话</label><div class="col-sm-10"><input type="text" name="mobile" value="" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">现住地址</label><div class="col-sm-10"><input type="text" name="address" value="" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">户籍地址</label><div class="col-sm-10"><input type="text" name="permanent_address" value="" class="form-control"></div></div></div><div class="modal-footer"><button type="reset" class="btn btn-white">重置</button><button type="submit" class="btn btn-primary">确定</button></div></form></div></div></div>';
    $('.a-client').on('click',function(){
        $('body').find('#client-modal').remove();
        $('body').append($clientModal);
        var $modal = $('#client-modal');
        //初始化时间
        initDate(['birthday']);
        //初始化头像上传
        iotUpload({'elem':'head','url':'/index/Client/uploadHead','multiple':false});
        $modal.modal('show');
    })
    /**
     * 验证身份证有效性并获取出生日期+性别
     * */
    $('body').delegate('#client-modal [name=id_number],.user-info [name=id_number]','blur',function(){
        var $idCard = $(this).val(),
            $birthday = $(this).parents('form').find('[name=birthday]'),
            $sex = $(this).parents('form').find('[name=sex]:checked');
        if(!$idCard){
            return false;
        }
        // 验证身份证有效性
        if(!checkIdCard($idCard)){
            //layer.msg('身份证格式错误',{icon:5,time:2000});
            return false;
        }
        if($birthday.val() && $sex.val()){
            return false;
        }
        // 获取出生日期+年龄
        if(!$birthday.val()){
            var $birthdayVal = '';
            if($idCard.length == 15) {
                $birthdayVal =  '19'+$idCard.substr(6,2)+'-'+$idCard.substr(8,2)+'-'+$idCard.substr(10,2);
            } else if ($idCard.length == 18) {
                $birthdayVal =  $idCard.substr(6,4)+'-'+$idCard.substr(10,2)+'-'+$idCard.substr(12,2);
            }
            $birthday.val($birthdayVal);
        }
        if(!$sex.val()){
            var $sexVal = 1
            if (parseInt($idCard.substr(16, 1)) % 2 != 1) {
                $sexVal = 2
            }
            $('#sex'+$sexVal).click();
        }
    });

    /**
     * validate自定义验证身份证
     * */
    $.validator.addMethod('checkIdCard', function(value, element, param) {
        return checkIdCard(value);
    });

    /**
     * 提交服务对象信息validate
     * */
    $('body').delegate('.user-info [type=submit],#client-modal [type=submit]','click',function(){
        $(this).parents('form').validate({
            rules: {
                name: {
                    required: !0
                },
                id_number: {
                    required: !0,
                    checkIdCard: true
                }
            },
            messages: {
                name: {
                    required:  "请填写姓名",
                },
                id_number: {
                    required:  '请填写身份证号码',
                    checkIdCard:  '身份证格式有误'
                }
            },
            //验证通过后的执行方法
            submitHandler: function(form) {
                var $subBtn = $(form).find('[type=submit]'),
                    $fData = $(form).serializeArray();
                $subBtn.attr('disabled',true);
                $.post('/index/Client/submitBasicInfo',$fData,function(data){
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

    /* ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓服务对象紧急联系人↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓ */
    /**
     * 添加新的联系人
     * */
    $('body').delegate('.contacts-info .addContacts','click',function(){
        var $parent = $(this).parent().parent();
        $parent.before($concatsSelectNode);
        if($parent.prevAll('div').length == 3){
            $parent.remove();
        }
        contactsIndex(this);
    })

    /**
     * 新增加的节点删除
     * */
    $('body').delegate('.contacts-info .fa-close','click',function(){
        var $parent = $(this).parent().parent(),
            $addBtn = $parent.parent().find('.addContacts').parent;
        $parent.parent().find('.addContacts').parent().parent().remove();
        $parent.parent().append($contactsButtonNode);
        $parent.remove();
        contactsIndex(this);
    })

    /**
     * 提交紧急联系人信息
     * */
    $('body').delegate('.contacts-info [type=submit]','click',function(){
        $(this).parents('form').validate({
            rules: {
                type0: {
                    required: !0
                },
                name0: {
                    required: !0
                },
                mobile0: {
                    required: !0
                },
                type1: {
                    required: !0
                },
                name1: {
                    required: !0
                },
                mobile1: {
                    required: !0
                },
                type2: {
                    required: !0
                },
                name2: {
                    required: !0
                },
                mobile2: {
                    required: !0
                }
            },
            messages: {
                type0: {
                    required: '请选择联系人类型'
                },
                name0: {
                    required: '请选择联系人姓名'
                },
                mobile0: {
                    required: '请填写联系人电话'
                },
                type1: {
                    required: '请选择联系人类型'
                },
                name1: {
                    required: '请选择联系人姓名'
                },
                mobile1: {
                    required: '请填写联系人电话'
                },
                type2: {
                    required: '请选择联系人类型'
                },
                name2: {
                    required: '请选择联系人姓名'
                },
                mobile2: {
                    required: '请填写联系人电话'
                }
            },
            //验证通过后的执行方法
            submitHandler: function(form)
            {
                var $subBtn = $(form).find('[type=submit]');
                    $fData = $(form).serializeArray();
                $fData.push({'name':'id','value':JSON.parse($userInfo).id});
                $subBtn.attr('disabled',true);
                $.post('/index/Client/submitContactsInfo',$fData,function(data){
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

    /* ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓提交用户的分组信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓ */
    $('body').delegate('.group-info form','submit',function(e){
        e.preventDefault();
        var $subBtn = $(this).find('[type=submit]'),
            $fData = $(this).serializeArray();
        $fData.push({'name':'id','value':JSON.parse($userInfo).id});
        $subBtn.attr('disabled',true);
        $.post('/index/Client/submitClientGroup',$fData,function(data){
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

    /* ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓提交用户的标签信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓ */
    $('body').delegate('.tag-info form','submit',function(e){
        e.preventDefault();
        var $subBtn = $(this).find('[type=submit]'),
            $fData = $(this).serializeArray();
        $fData.push({'name':'id','value':JSON.parse($userInfo).id});
        $subBtn.attr('disabled',true);
        $.post('/index/Client/submitClientTag',$fData,function(data){
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

    /* ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓提交服务对象的其他信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓ */
    $('body').delegate('.other-info form','submit',function(e){
        e.preventDefault();
        var $subBtn = $(this).find('[type=submit]'),
            $fData = $(this).serializeArray();
        $fData.push({'name':'id','value':JSON.parse($userInfo).id});
        $subBtn.attr('disabled',true);
        $.post('/index/Client/submitClientOther',$fData,function(data){
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

    /* ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓服务对象分组↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓ */
    var $groupModal = '<div class="modal inmodal fade" id="group-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title"></h1></div><form class="form-horizontal"><div class="modal-body"><div class="form-group"><div class="col-sm-12"><input type="text" name="name" value="" class="form-control" maxlength="10" required placeholder="请输入分组名称"></div></div><div class="form-group"><div class="col-sm-12"><textarea name="remarks" cols="30" rows="3" class="form-control" placeholder="分组描述"></textarea></div></div></div><div class="modal-footer"><button type="reset" class="btn btn-white">重置</button><button type="submit" class="btn btn-primary">确定</button></div></form></div></div></div>';

    /**
     * 添加分组
     * */
    var $editGroupId;   // 编辑的分组id
    $('.a-group').on('click',function(){
        $('body').find('#group-modal').remove();
        $('body').append($groupModal);
        var $modal = $('#group-modal');
        $editGroupId = 0;
        $modal.find('.modal-title').text('添加分组');
        $modal.modal('show');
    })

    /**
     * 编辑分组
     * */
    $('.e-group').on('click',function(){
        $('body').find('#group-modal').remove();
        $('body').append($groupModal);
        var $modal = $('#group-modal'),
            $index = $('.e-group').index(this),
            $groupJson = JSON.parse($groupList),
            $groupInfo;
        for(var i in $groupJson){
            if(i == $index){
                $groupInfo = $groupJson[i];
            }
        }
        $editGroupId = $groupInfo.id;
        $modal.find('.modal-title').text('编辑分组');
        $modal.find('[name=name]').val($groupInfo.name);
        $modal.find('[name=remarks]').val($groupInfo.remarks);
        $modal.modal('show');
    })

    /**
     * 提交分组信息
     * */
    $('body').delegate('#group-modal','show.bs.modal',function(){
        $(this).find('form').validate({
            rules: {
                name: {
                    required: !0
                }
            },
            messages: {
                name: {
                    required: "请填写分组名称",
                }
            },
            //验证通过后的执行方法
            submitHandler: function(form)
            {
                var $subBtn = $(form).find('[type=submit]'),
                    $fData = {
                        'name':$(form).find('[name=name]').val(),
                        'remarks':$(form).find('[name=remarks]').val(),
                        'id':$editGroupId
                    };
                $subBtn.attr('disabled',true);
                $.post('/index/Client/submitGroup',$fData,function(data){
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
     * 删除分组
     * */
    $('.d-group').on('click',function(){
        var $index = $('.d-group').index(this),
            $groupJson = JSON.parse($groupList),
            $groupInfo;
        for(var i in $groupJson){
            if(i == $index){
                $groupInfo = $groupJson[i];
            }
        }
        layer.confirm(
            '确定删除分组：'+$groupInfo.name+'？',
            {
                btn : ['确定', '取消'],
                offset: '20%',
                shadeClose: true,
                btn1:function(obj){
                    $(".layui-layer-btn0").attr('disabled',true);
                    $.post('/index/Client/delGroup',{'id':$groupInfo.id},function(data){
                        if(data.code == '0'){
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

    /* ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓服务对象标签↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓ */
    var $tagModal = '<div class="modal inmodal fade" id="tag-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title"></h1></div><form class="form-horizontal"><div class="modal-body"><div class="form-group"><div class="col-sm-12"><input type="text" name="name" value="" class="form-control" maxlength="10" required placeholder="请输入标签名称"></div></div></div><div class="modal-footer"><button type="reset" class="btn btn-white">重置</button><button type="submit" class="btn btn-primary">确定</button></div></form></div></div></div>';

    var $editTagId;     // 编辑的标签id
    /**
     * 添加标签modal
     * */
    $('.a-tag').on('click',function(){
        $('body').find('#tag-modal').remove();
        $('body').append($tagModal);
        var $modal = $('#tag-modal');
        $modal.find('.modal-title').text('添加标签');
        $editTagId = 0;
        $modal.modal('show');
    })

    /**
     * 编辑标签modal
     * */
    $('.e-tag').on('click',function(){
        $('body').find('#tag-modal').remove();
        $('body').append($tagModal);
        var $modal = $('#tag-modal'),
            $index = $('.e-tag').index(this),
            $tagJson = JSON.parse($tagList),
            $tagInfo;
        for(var i in $tagJson){
            if(i == $index){
                $tagInfo = $tagJson[i];
            }
        }
        $editTagId = $tagInfo.id;
        $modal.find('.modal-title').text('编辑标签');
        $modal.find('[name=name]').val($tagInfo.name);
        $modal.modal('show');
    })

    /**
     * 提交标签信息
     * */
    $('body').delegate('#tag-modal','show.bs.modal',function(){
        $(this).find('form').validate({
            rules: {
                name: {
                    required: !0
                }
            },
            messages: {
                name: {
                    required: "请填写标签名称",
                }
            },
            //验证通过后的执行方法
            submitHandler: function(form)
            {
                var $subBtn = $(form).find('[name=submit]'),
                    $fData = {
                        'name':$(form).find('[name=name]').val(),
                        'id':$editTagId,
                    };
                $subBtn.attr('disabled',true);
                $.post('/index/Client/submitTag',$fData,function(data){
                    if(data.code == 0){
                        layer.msg(data.msg,{icon:1,time:2000},function(){
                            location.reload();
                        });
                        return false;
                    }
                    layer.msg(data.msg,{icon:5,time:2000},function(){
                        $subBtn.attr('disabled',false);
                    });
                })
            }
        })
    })

    /**
     * 删除标签
     * */
    $('.d-tag').on('click',function(){
        var $index = $('.d-tag').index(this),
            $tagJson = JSON.parse($tagList),
            $tagInfo;
        for(var i in $tagJson){
            if(i == $index){
                $tagInfo = $tagJson[i];
            }
        }
        layer.confirm(
            '确定删除标签：'+$tagInfo.name+'？',
            {
                btn : ['确定', '取消'],
                offset: '20%',
                shadeClose: true,
                btn1:function(obj){
                    $(".layui-layer-btn0").attr('disabled',true);
                    $.post('/index/Client/delTag',{'id':$tagInfo.id},function(data){
                        if(data.code == '0'){
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

    /* ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓服务对象健康档案基础信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓ */
    /**
     * 编辑健康档案基础信息
     * */
    var $healthyBaseHtml = '',
        $exerciseType = ['跑步','健身操','球类','游泳','其它'],
        $socialActivity = ['公园','老年活动站','老年大学','其它'],
        $healthyBaseNode1 = '<div class="form-group"><label class="col-sm-2 control-label">身高</label><div class="col-sm-5"><div class="input-group m-b"><input type="text" name="height" class="form-control"> <span class="input-group-addon">厘米</span></div></div></div><div class="form-group"><label class="col-sm-2 control-label">体重</label><div class="col-sm-5"><div class="input-group m-b"><input type="text" name="weight" class="form-control"> <span class="input-group-addon">公斤</span></div></div></div><div class="form-group"><label class="col-sm-2 control-label">视力情况</label><div class="col-sm-5"><input type="text" name="vision" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">听力情况</label><div class="col-sm-5"><input type="text" name="hearing" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">睡眠</label><div class="col-sm-5"><div class="radio radio-success radio-inline"><input type="radio" id="sleep1" value="1" name="sleep"><label for="sleep1"> 睡眠充足（5-8小时） </label></div><div class="radio radio-success radio-inline"><input type="radio" id="sleep2" value="2" name="sleep"><label for="sleep2"> 睡眠不足（不足4小时） </label></div></div></div><div class="form-group"><label class="col-sm-2 control-label">是否吸烟</label><div class="col-sm-5"><div class="radio radio-success radio-inline"><input type="radio" id="smoke1" value="1" name="smoke"><label for="smoke1"> 是 </label></div><div class="radio radio-success radio-inline"><input type="radio" id="smoke2" value="2" name="smoke"><label for="smoke2"> 否 </label></div></div></div><div class="form-group"><label class="col-sm-2 control-label">平均每天吸烟量</label><div class="col-sm-5"><div class="input-group m-b"><input type="text" name="smoke_frequency" class="form-control" disabled> <span class="input-group-addon">支/日</span></div></div></div><div class="form-group"><label class="col-sm-2 control-label">是否饮酒</label><div class="col-sm-5"><div class="radio radio-success radio-inline"><input type="radio" id="drink1" value="1" name="drink"><label for="drink1"> 是 </label></div><div class="radio radio-success radio-inline"><input type="radio" id="drink2" value="2" name="drink"><label for="drink2"> 否 </label></div></div></div>',
        $healthyBaseNode2 = '<div class="form-group"><label class="col-sm-2 control-label">锻炼次数</label><div class="col-sm-5"><div class="input-group m-b"><input type="text" name="exercise_frequency" class="form-control"> <span class="input-group-addon">次/周</span></div></div></div><div class="form-group"><label class="col-sm-2 control-label">锻炼时间</label><div class="col-sm-5"><div class="radio radio-success radio-inline"><input type="radio" id="exercise_duration1" value="1" name="exercise_duration"><label for="exercise_duration1"> 30分钟以内/次 </label></div><div class="radio radio-success radio-inline"><input type="radio" id="exercise_duration2" value="2" name="exercise_duration"><label for="exercise_duration2"> 30-60分钟/次 </label></div><div class="radio radio-success radio-inline"><input type="radio" id="exercise_duration3" value="3" name="exercise_duration"><label for="exercise_duration3"> 60分钟以上/次 </label></div></div></div><div class="form-group"><label class="col-sm-2 control-label">锻炼方式</label><div class="col-sm-6" id="exercise_type"></div></div><div class="form-group"><label class="col-sm-2 control-label">服用保健品说明</label><div class="col-sm-6"><textarea name="healthy_products" class="form-control" cols="30" rows="3"></textarea></div></div><div class="form-group"><label class="col-sm-2 control-label">医疗费支付方式</label><div class="col-sm-5"><div class="radio radio-success radio-inline"><input type="radio" id="medical_payment1" value="1" name="medical_payment"><label for="medical_payment1"> 自费 </label></div><div class="radio radio-success radio-inline"><input type="radio" id="medical_payment2" value="2" name="medical_payment"><label for="medical_payment2"> 半自费 </label></div><div class="radio radio-success radio-inline"><input type="radio" id="medical_payment3" value="3" name="medical_payment"><label for="medical_payment3"> 劳保 </label></div><div class="radio radio-success radio-inline"><input type="radio" id="medical_payment4" value="4" name="medical_payment"><label for="medical_payment4"> 公费 </label></div><div class="radio radio-success radio-inline"><input type="radio" id="medical_payment5" value="5" name="medical_payment"><label for="medical_payment5"> 社保 </label></div></div></div><div class="form-group"><label class="col-sm-2 control-label">社交活动</label><div class="col-sm-6" id="social_activity"></div></div><div class="form-group"><label class="col-sm-2 control-label">备注</label><div class="col-sm-6"><textarea name="remarks" class="form-control" cols="30" rows="3"></textarea></div></div><div class="form-group"><label class="col-sm-2 control-label"></label><div class="col-sm-6"><button type="button" class="btn btn-white">取消</button>&nbsp;&nbsp;<button type="submit" class="btn btn-primary active">保存</button></div></div>',
        $healthyBaseNode = $healthyBaseNode1+$healthyBaseNode2;
    $('body').delegate('.c-healthy-base button.active[type=button]','click',function(){
        var $form = $('.c-healthy-base form'),
            $healthyBaseJson = JSON.parse($healthyBase);
        console.log($healthyBaseJson);
        $healthyBaseHtml = $form.html();
        $form.empty().append($healthyBaseNode);
        // 锻炼方式
        for(var i in $exerciseType){
            var $i = parseInt(i)+ 1,
                $checked = $.inArray($i.toString(),$healthyBaseJson['exercise_type']) != -1 ? 'checked' : '',
                $checkbox = '<div class="checkbox checkbox-success checkbox-inline"><input type="checkbox" name="exercise_type[]" id="exercise_type'+i+'" value="'+$i+'" '+$checked+'><label for="exercise_type'+i+'"> '+$exerciseType[i]+' </label></div>';
            $form.find('#exercise_type').append($checkbox);
        }
        // 社交活动
        for(var i in $socialActivity){
            var $i = parseInt(i)+ 1,
                $checked = $.inArray($i.toString(),$healthyBaseJson['social_activity']) != -1 ? 'checked' : '',
                $checkbox = '<div class="checkbox checkbox-success checkbox-inline"><input type="checkbox" name="social_activity[]" id="social_activity'+i+'" value="'+$i+'" '+$checked+'><label for="social_activity'+i+'"> '+$socialActivity[i]+' </label></div>';
            $form.find('#social_activity').append($checkbox);
        }
        if(!$healthyBaseJson){
            return false;
        }
        $form.find('[name=height]').val($healthyBaseJson.height);
        $form.find('[name=weight]').val($healthyBaseJson.weight);
        $form.find('[name=vision]').val($healthyBaseJson.vision);
        $form.find('[name=hearing]').val($healthyBaseJson.hearing);
        $form.find('#sleep'+$healthyBaseJson.sleep).attr('checked',true);
        $form.find('#smoke'+$healthyBaseJson.smoke).attr('checked',true);
        if($healthyBaseJson.smoke == 1){
            $form.find('[name=smoke_frequency]').removeAttr('disabled').val($healthyBaseJson.smoke_frequency);
        }
        $form.find('#drink'+$healthyBaseJson.drink).attr('checked',true);
        $form.find('[name=exercise_frequency]').val($healthyBaseJson.exercise_frequency);
        $form.find('#exercise_duration'+$healthyBaseJson.exercise_duration).attr('checked',true);
        $form.find('[name=healthy_products]').val($healthyBaseJson.healthy_products);
        $form.find('#medical_payment'+$healthyBaseJson.medical_payment).attr('checked',true);
        $form.find('[name=remarks]').val($healthyBaseJson.remarks);
    })

    /**
     * 取消健康档案基础信息编辑
     * */
    $('body').delegate('.c-healthy-base button:not(".active")','click',function(){
        $('.c-healthy-base form').empty().append($healthyBaseHtml);
    })

    /**
     * 是否吸烟
     * */
    $('body').delegate('.c-healthy-base [name=smoke]','click',function(){
        var $val = $(this).val(),
            $smokeFrequency = $('.c-healthy-base [name=smoke_frequency]');
        if($val == 2){
            $smokeFrequency.attr('disabled',true);
        } else {
            $smokeFrequency.attr('disabled',false)
        }
    })

    /**
     * 提交健康档案的基础信息
     * */
    $('body').delegate('.c-healthy-base form','submit',function(e){
        e.preventDefault();
        var $subBtn = $(this).find('[type=submit]'),
            $fData = $(this).serializeArray();
        $fData.push({'name':'id','value':$userId});
        $subBtn.attr('disabled',true);
        $.post('/index/Client/submitHealthyBase',$fData,function(data){
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

    /* ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓服务对象既往病史↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓ */
    /**
     * 添加/编辑既往病史的modal
     * */
    var $medicalHistoryModal = '<div class="modal inmodal fade" id="medical-h-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title"></h1></div><form class="form-horizontal"><div class="modal-body"><div class="form-group"><label class="col-sm-2 control-label">* 疾病描述</label><div class="col-sm-10"><input type="text" name="descript" value="" class="form-control" placeholder="请输入疾病描述"></div></div><div class="form-group"><label class="col-sm-2 control-label">* 确诊日期</label><div class="col-sm-10"><div class="input-group date"><input type="text" name="diagnostic_time" id="diagnostic_time" class="form-control"><span class="input-group-addon"><i class="fa fa-calendar"></i></span></div></div></div><div class="form-group"><label class="col-sm-2 control-label">备注</label><div class="col-sm-10"><textarea name="remarks" cols="30" rows="3" class="form-control" placeholder="备注"></textarea></div></div></div><div class="modal-footer"><button type="reset" class="btn btn-white">重置</button><button type="submit" class="btn btn-primary">确定</button></div></form></div></div></div>',
        $editMedicalHistoryId;

    /**
     * 添加既往病史
     * */
    $('.a-medical-history').on('click',function(){
        $editMedicalHistoryId = 0;
        $('body').find('#medical-h-modal').remove();
        $('body').append($medicalHistoryModal);
        var $modal = $('#medical-h-modal');
        $modal.find('.modal-title').text('添加既往病史');
        // 时间插件
        initDate(['diagnostic_time']);
        $modal.modal('show');
    })

    /**
     * 编辑既往病史
     * */
    $('.e-medical-history').on('click',function(){
        $('body').find('#medical-h-modal').remove();
        $('body').append($medicalHistoryModal);
        var $modal = $('#medical-h-modal'),
            $index = $('.e-medical-history').index(this),
            $medicalHistoryJson = JSON.parse($medicalHistoryList),
            $medicalHistoryInfo;
        for(var i in $medicalHistoryJson){
            if(i == $index){
                $medicalHistoryInfo = $medicalHistoryJson[i];
            }
        }
        $editMedicalHistoryId = $medicalHistoryInfo.id;
        $modal.find('.modal-title').text('编辑既往病史');
        $modal.find('[name=descript]').val($medicalHistoryInfo.descript);
        $modal.find('[name=diagnostic_time]').val($medicalHistoryInfo.diagnostic_time);
        $modal.find('[name=remarks]').val($medicalHistoryInfo.remarks);
        // 时间插件
        initDate(['diagnostic_time']);
        $modal.modal('show');
    })

    /**
     * 提交既往病史信息
     * */
    $('body').delegate('#medical-h-modal','show.bs.modal',function(){
        $(this).find('form').validate({
            rules: {
                descript: {
                    required: !0
                },
                diagnostic_time: {
                    required: !0
                }
            },
            messages: {
                descript: {
                    required: "请填写疾病描述"
                },
                diagnostic_time: {
                    required: "请填写确诊时间"
                }
            },
            //验证通过后的执行方法
            submitHandler: function(form)
            {
                var $subBtn = $(form).find('[name=submit]'),
                    $fData = $(form).serializeArray();
                $fData.push({'name':'uid','value':$userId});
                $fData.push({'name':'id','value':$editMedicalHistoryId});
                $subBtn.attr('disabled',true);
                $.post('/index/Client/submitMedicalHistory',$fData,function(data){
                    if(data.code == 0){
                        layer.msg(data.msg,{icon:1,time:2000},function(){
                            location.reload();
                        });
                        return false;
                    }
                    layer.msg(data.msg,{icon:5,time:2000},function(){
                        $subBtn.attr('disabled',false);
                    });
                })
            }
        })
    })

    /**
     * 删除既往病史
     * */
    $('.d-medical-history').on('click',function(){
        var $index = $('.d-medical-history').index(this),
            $medicalHistoryJson = JSON.parse($medicalHistoryList),
            $medicalHistoryInfo;
        for(var i in $medicalHistoryJson){
            if(i == $index){
                $medicalHistoryInfo = $medicalHistoryJson[i];
            }
        }
        layer.confirm(
            '确定删除既往病史：'+$medicalHistoryInfo.descript+'？',
            {
                btn : ['确定', '取消'],
                offset: '20%',
                shadeClose: true,
                btn1:function(obj){
                    $(".layui-layer-btn0").attr('disabled',true);
                    $.post('/index/Client/delMedicalHistory',{'id':$medicalHistoryInfo.id,'uid':$userId},function(data){
                        if(data.code == '0'){
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

    /* ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓服务对象体检记录↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓ */
    /**
     * 添加体检记录的modal
     * */
    var $physicalModal = '<div class="modal inmodal fade" id="physical-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title"></h1></div><form class="form-horizontal"><div class="modal-body"><div class="form-group"><label class="col-sm-2 control-label">* 体检机构</label><div class="col-sm-10"><input type="text" name="institution" value="" class="form-control" required placeholder="体检机构名称"></div></div><div class="form-group"><label class="col-sm-2 control-label">* 体检日期</label><div class="col-sm-10"><div class="input-group date"><input type="text" name="physical_time" id="physical_time" class="form-control"><span class="input-group-addon"><i class="fa fa-calendar"></i></span></div></div></div><div class="form-group"><label class="col-sm-2 control-label">拍照上传</label><div class="col-sm-10"><div class="upload-file style-h" title="点击上传照片"><img src="/public/static/img/sample-img.png" style="width: 200px;height: 100px;border-radius: unset;"><div class="layui-btn" id="physical-img"><i class="fa fa-upload"></i></div><input class="layui-upload-file" type="file" name="file"></div></div></div><div class="form-group"><label class="col-sm-2 control-label">备注</label><div class="col-sm-10"><textarea name="remarks" cols="30" rows="3" class="form-control"></textarea></div></div></div><div class="modal-footer"><button type="reset" class="btn btn-white">重置</button><button type="submit" class="btn btn-primary">确定</button></div></form></div></div></div>',
        $editPhysicalId;

    /**
     * 体检添加按钮
     * */
    $('.a-physical').on('click',function(){
        $editPhysicalId = 0,
        $('body').find('#physical-modal').remove();
        $('body').append($physicalModal);
        var $modal = $('#physical-modal');
        $modal.find('.modal-title').text('添加体检记录');
        iotUpload({'elem':'physical-img','url':'/index/Client/uploadHead','multiple':true});
        initDate(['physical_time']);
        $modal.modal('show');
    })

    /**
     * 体检编辑按钮
     * */
    $('.e-physical').on('click',function(){
        $('body').find('#physical-modal').remove();
        $('body').append($physicalModal);
        var $modal = $('#physical-modal'),
            $index = $('.e-physical').index(this),
            $physicalJson = JSON.parse($physicalList),
            $physicalInfo;
        for(var i in $physicalJson){
            if(i == $index){
                $physicalInfo = $physicalJson[i];
            }
        }
        $editPhysicalId = $physicalInfo.id;
        $modal.find('.modal-title').text('编辑体检记录');
        $modal.find('[name=institution]').val($physicalInfo.institution);
        $modal.find('[name=physical_time]').val($physicalInfo.physical_time);
        $modal.find('[name=remarks]').val($physicalInfo.remarks);
        // 遍历图片
        if($physicalInfo.image_count){
            for(var i in $physicalInfo.image){
                var $img = '<div class="inline m-b m-r multiple-upload">' +
                    '<a javascript:;>X</a>' +
                    '<img src="'+$physicalInfo.image[i]+'">' +
                    '<input type="hidden" name="image[]" value="'+$physicalInfo.image[i]+'">' +
                    '</div>';
                $('.upload-file').before($img);
            }
        }
        iotUpload({'elem':'physical-img','url':'/index/Client/uploadHead','multiple':true});
        initDate(['physical_time']);
        $modal.modal('show');
    })

    /**
     * 提交体检信息
     * */
    $('body').delegate('#physical-modal','show.bs.modal',function(){
        $(this).find('form').validate({
            rules: {
                institution: {
                    required: !0
                },
                physical_time: {
                    required: !0
                }
            },
            messages: {
                institution: {
                    required: "请填写体检机构"
                },
                diagnostic_time: {
                    required: "请填写体检时间"
                }
            },
            //验证通过后的执行方法
            submitHandler: function(form)
            {
                var $subBtn = $(form).find('[name=submit]'),
                    $fData = $(form).serializeArray();
                $fData.push({'name':'uid','value':$userId});
                $fData.push({'name':'id','value':$editPhysicalId});
                $subBtn.attr('disabled',true);
                $.post('/index/Client/submitPhysical',$fData,function(data){
                    if(data.code == 0){
                        layer.msg(data.msg,{icon:1,time:2000},function(){
                            location.reload();
                        });
                        return false;
                    }
                    layer.msg(data.msg,{icon:5,time:2000},function(){
                        $subBtn.attr('disabled',false);
                    });
                })
            }
        })
    })

    /**
     * 删除体检记录
     * */
    $('.d-physical').on('click',function(){
        var $index = $('.d-physical').index(this),
            $physicalJson = JSON.parse($physicalList),
            $physicalInfo;
        for(var i in $physicalJson){
            if(i == $index){
                $physicalInfo = $physicalJson[i];
            }
        }
        layer.confirm(
            '确定删除体检记录：'+$physicalInfo.institution+'？',
            {
                btn : ['确定', '取消'],
                offset: '20%',
                shadeClose: true,
                btn1:function(obj){
                    $(".layui-layer-btn0").attr('disabled',true);
                    $.post('/index/Client/delPhysical',{'id':$physicalInfo.id,'uid':$userId},function(data){
                        if(data.code == '0'){
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

    /* ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓服务对象遗传病史↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓ */
    /**
     * 家族遗传病史modal
     * */
    var $hereditaryModal = '<div class="modal inmodal fade" id="hereditary-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title"></h1></div><form class="form-horizontal"><div class="modal-body"><div class="form-group"><label class="col-sm-2 control-label">* 关系</label><div class="col-sm-10"><input type="text" name="relationship" value="" class="form-control" required></div></div><div class="form-group"><label class="col-sm-2 control-label">* 疾病名称</label><div class="col-sm-10"><input type="text" name="name" value="" class="form-control" required></div></div><div class="form-group"><label class="col-sm-2 control-label">备注</label><div class="col-sm-10"><textarea name="remarks" cols="30" rows="3" class="form-control"></textarea></div></div></div><div class="modal-footer"><button type="reset" class="btn btn-white">重置</button><button type="submit" class="btn btn-primary">确定</button></div></form></div></div></div>',
        $editHereditaryId;

    /**
     * 添加遗传史
     * */
    $('.a-hereditary').on('click',function(){
        $editHereditaryId = 0;
        $('body').find('#hereditary-modal').remove();
        $('body').append($hereditaryModal);
        var $modal = $('#hereditary-modal');
        $modal.find('.modal-title').text('添加遗传史');
        $modal.modal('show');
    })

    /**
     * 编辑遗传病史
     * */
    $('.e-hereditary').on('click',function(){
        $('body').find('#hereditary-modal').remove();
        $('body').append($hereditaryModal);
        var $modal = $('#hereditary-modal'),
            $index = $('.e-hereditary').index(this),
            $hereditaryJson = JSON.parse($hereditaryList),
            $hereditaryInfo;
        for(var i in $hereditaryJson){
            if(i == $index){
                $hereditaryInfo = $hereditaryJson[i];
            }
        }
        $editHereditaryId = $hereditaryInfo.id;
        $modal.find('.modal-title').text('编辑遗传史');
        $modal.find('[name=relationship]').val($hereditaryInfo.relationship);
        $modal.find('[name=name]').val($hereditaryInfo.name);
        $modal.find('[name=remarks]').val($hereditaryInfo.remarks);
        $modal.modal('show');
    })

    /**
     * 提交遗传病史信息
     * */
    $('body').delegate('#hereditary-modal','show.bs.modal',function(){
        $(this).find('form').validate({
            rules: {
                relationship: {
                    required: !0
                },
                name: {
                    required: !0
                }
            },
            messages: {
                relationship: {
                    required: "请填写关系"
                },
                name: {
                    required: "请填写疾病名称"
                }
            },
            //验证通过后的执行方法
            submitHandler: function(form)
            {
                var $subBtn = $(form).find('[name=submit]'),
                    $fData = $(form).serializeArray();
                $fData.push({'name':'uid','value':$userId});
                $fData.push({'name':'id','value':$editHereditaryId});
                $subBtn.attr('disabled',true);
                $.post('/index/Client/submitHereditary',$fData,function(data){
                    if(data.code == 0){
                        layer.msg(data.msg,{icon:1,time:2000},function(){
                            location.reload();
                        });
                        return false;
                    }
                    layer.msg(data.msg,{icon:5,time:2000},function(){
                        $subBtn.attr('disabled',false);
                    });
                })
            }
        })
    })

    /**
     * 删除遗传病史
     * */
    $('.d-hereditary').on('click',function(){
        var $index = $('.d-hereditary').index(this),
            $hereditaryJson = JSON.parse($hereditaryList),
            $hereditaryInfo;
        for(var i in $hereditaryJson){
            if(i == $index){
                $hereditaryInfo = $hereditaryJson[i];
            }
        }
        layer.confirm(
            '确定删除遗传病史：'+$hereditaryInfo.name+'？',
            {
                btn : ['确定', '取消'],
                offset: '20%',
                shadeClose: true,
                btn1:function(obj){
                    $(".layui-layer-btn0").attr('disabled',true);
                    $.post('/index/Client/delHereditary',{'id':$hereditaryInfo.id,'uid':$userId},function(data){
                        if(data.code == '0'){
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

    /* ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓服务对象病历存档↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓ */
    /**
     * 病历添加/编辑的modal
     * */
    var $caseRecordModal = '<div class="modal inmodal fade" id="case-record-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title"></h1></div><form class="form-horizontal"><div class="modal-body"><div class="form-group"><label class="col-sm-2 control-label">* 医院</label><div class="col-sm-10"><input type="text" name="institution" value="" class="form-control" required placeholder="医院名称"></div></div><div class="form-group"><label class="col-sm-2 control-label">* 病历日期</label><div class="col-sm-10"><div class="input-group date"><input type="text" name="case_time" id="case_time" class="form-control"><span class="input-group-addon"><i class="fa fa-calendar"></i></span></div></div></div><div class="form-group"><label class="col-sm-2 control-label">拍照上传</label><div class="col-sm-10"><div class="upload-file style-h" title="点击上传照片"><img src="/public/static/img/sample-img.png" style="width: 200px;height: 100px;border-radius: unset;"><div class="layui-btn" id="case-img"><i class="fa fa-upload"></i></div><input class="layui-upload-file" type="file" name="file"></div></div></div><div class="form-group"><label class="col-sm-2 control-label">备注</label><div class="col-sm-10"><textarea name="remarks" cols="30" rows="3" class="form-control"></textarea></div></div></div><div class="modal-footer"><button type="reset" class="btn btn-white">重置</button><button type="submit" class="btn btn-primary">确定</button></div></form></div></div></div>',
        $editCaseId;

    /**
     * 病历添加
     * */
    $('.a-case-record').on('click',function(){
        $editCaseId = 0;
        $('body').find('#case-record-modal').remove();
        $('body').append($caseRecordModal);
        var $modal = $('#case-record-modal');
        // 加载上传图片方法
        iotUpload({'elem':'case-img','url':'/index/Client/uploadHead','multiple':true});
        // 时间插件
        initDate(['case_time']);
        $modal.modal('show');
    })

    /**
     * 病历编辑
     * */
    $('.e-case-record').on('click',function(){
        $('body').find('#case-record-modal').remove();
        $('body').append($caseRecordModal);
        var $modal = $('#case-record-modal'),
            $index = $('.e-case-record').index(this),
            $caseRecordJson = JSON.parse($caseRecordList),
            $caseRecordInfo;
        for(var i in $caseRecordJson){
            if(i == $index){
                $caseRecordInfo = $caseRecordJson[i];
            }
        }
        $editCaseId = $caseRecordInfo.id;
        $modal.find('.modal-title').text('编辑遗传史');
        $modal.find('[name=institution]').val($caseRecordInfo.institution);
        $modal.find('[name=case_time]').val($caseRecordInfo.case_time);
        $modal.find('[name=remarks]').val($caseRecordInfo.remarks);
        // 图片
        if($caseRecordInfo.count_img){
            for(var i in $caseRecordInfo.image){
                var $img = '<div class="inline m-b m-r multiple-upload">' +
                    '<a javascript:;>X</a>' +
                    '<img src="'+$caseRecordInfo.image[i]+'">' +
                    '<input type="hidden" name="image[]" value="'+$caseRecordInfo.image[i]+'">' +
                    '</div>';
                $('.upload-file').before($img);
            }
        }
        // 加载上传图片方法
        iotUpload({'elem':'case-img','url':'/index/Client/uploadHead','multiple':true});
        // 时间插件
        initDate(['case_time']);
        $modal.modal('show');
    })

    /**
     * 提交服务对象的病历信息
     * */
    $('body').delegate('#case-record-modal','show.bs.modal',function(){
        $(this).find('form').validate({
            rules: {
                institution: {
                    required: !0
                },
                case_time: {
                    required: !0
                }
            },
            messages: {
                institution: {
                    required: "请填写医院"
                },
                case_time: {
                    required: "请填写病例日期"
                }
            },
            //验证通过后的执行方法
            submitHandler: function(form)
            {
                var $subBtn = $(form).find('[name=submit]'),
                    $fData = $(form).serializeArray();
                $fData.push({'name':'uid','value':$userId});
                $fData.push({'name':'id','value':$editCaseId});
                $subBtn.attr('disabled',true);
                $.post('/index/Client/submitCaesRecord',$fData,function(data){
                    if(data.code == 0){
                        layer.msg(data.msg,{icon:1,time:2000},function(){
                            location.reload();
                        });
                        return false;
                    }
                    layer.msg(data.msg,{icon:5,time:2000},function(){
                        $subBtn.attr('disabled',false);
                    });
                })
            }
        })
    })

    /**
     * 删除病历信息
     * */
    $('.d-case-record').on('click',function(){
        var $index = $('.d-case-record').index(this),
            $caseRecordJson = JSON.parse($caseRecordList),
            $caseRecordInfo;
        for(var i in $caseRecordJson){
            if(i == $index){
                $caseRecordInfo = $caseRecordJson[i];
            }
        }
        layer.confirm(
            '确定删除病历：'+$caseRecordInfo.institution+'？',
            {
                btn : ['确定', '取消'],
                offset: '20%',
                shadeClose: true,
                btn1:function(obj){
                    $(".layui-layer-btn0").attr('disabled',true);
                    $.post('/index/Client/delCaseRecord',{'id':$caseRecordInfo.id,'uid':$userId},function(data){
                        if(data.code == '0'){
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

    /* ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓服务对象过敏药物↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓ */
    /**
     * 定义过敏药物
     * */
    var $medicine = new Array;
    if($action == 'healthy' && getUrlParam('view') == 'allergy'){
        var $allergyDrugs = {"丸药":["羚翘解毒丸","牛黄上清丸","火胆丸","六神丸"],"血清制剂":["丙种胎盘球蛋白","动物血清"],"麻醉用药":["普鲁卡因"],"解热镇痛药":["阿司匹林","去痛片"],"镇定安眠药":["鲁米那","安定"],"磺胺类药物":["磺胺噻唑","磺胺嘧啶","长效磺胺","复方新诺明"],"抗生类药物":["青霉素","氨基苄青霉素","链霉素","卡那霉素"]},
            $allergyJSON = JSON.parse($allergyInfo),
            $i = 6;
        for(var m in $allergyJSON){
            $medicine.push($allergyJSON[m]['medicine']);
        }
        for(var i in $allergyDrugs){
            var $allergyNode = '<div class="form-group"><label class="col-sm-1 control-label">'+i+'</label><div class="col-sm-6">';
            for(var j in $allergyDrugs[i]){
                var $checked = ($.inArray($allergyDrugs[i][j],$medicine) != -1) ? 'checked' : '';
                $allergyNode += '<div class="checkbox checkbox-success checkbox-inline"><input type="checkbox" id="allergy'+$i+j+'" value="'+$allergyDrugs[i][j]+'" '+$checked+'><label for="allergy'+$i+j+'">'+$allergyDrugs[i][j]+'</label></div>';
            }
            $allergyNode += '</div></div>';
            $('.c-allergy form').find('.form-group:first-child').after($allergyNode);
            $i -= 1;
        }
    }

    /**
     * 选择过敏药物
     * */
    $('body').delegate('.c-allergy [type=checkbox]','click',function(){
        var $isChecked = $(this).prop('checked'),
            $val = $(this).val(),
            $div = $('.c-allergy .form-group:first-child .col-sm-6');
        if($isChecked){     // 添加
            $medicine.push($val);
            var $span = '<span><strong>'+$val+'</strong><a href="javascript:;">X</a></span>';
            $div.append($span);
        } else {            // 删除
            var $index = jQuery.inArray($val,$medicine);
            $medicine.splice($index,1);
            $div.find('span').eq($index).remove();
        }
    })

    /**
     * 删除已添加的过敏药物
     * */
    $('.c-allergy').on('click','.form-group:first-child a',function(){
        var $thisText = $(this).prev().text();
        $(this).parent().remove();
        $('.c-allergy').find('input[value="'+$thisText+'"]').attr('checked',false);
        $medicine.splice(jQuery.inArray($thisText,$medicine),1);
    })

    /**
     * 手动添加过敏药物
     * */
    $('.c-allergy .input-group-addon').on('click',function(){
        var $otherDrug = $(this).prev();
        if($otherDrug.val()){
            $medicine.push($otherDrug.val());
            var $span = '<span><strong>'+$otherDrug.val()+'</strong><a href="javascript:;">X</a></span>';
            $('.c-allergy .form-group:first-child .col-sm-6').append($span);
            $otherDrug.val('');
        }
    })

    /**
     * 提交过敏药物信息
     * */
    $('.c-allergy button.active').on('click',function(){
        var $subBtn = $(this);
        $subBtn.attr('disabled',true);
        $.post('/index/Client/submitAllergy',{'uid':$userId,'medicine':$medicine},function(data){
            $icon = (data.code == 0) ? 1 : 5 ;
            layer.msg(data.msg,{icon:$icon,time:2000},function(){
                $subBtn.attr('disabled',false);
            });
        })
    })

    /* ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓服务对象公共↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓ */
    /**
     * 服务对象详情页的菜单
     * */
    var $menu = [{"name":"基础信息","url":"/index/Client/clientBase?"},{"name":"实时健康","url":"/index/Client/currentHealthy?"},{"name":"健康档案","url":"/index/Client/healthy?view=healthyBase&"},{"name":"服务工单","url":"/index/Client/serviceOrder?"},{"name":"积分","url":"/index/Client/integral?"},{"name":"实时定位","url":"/index/Client/currentPosition?"},{"name":"轨迹动画","url":"/index/Client/track?"}];
    $userId = typeof($userId) != "undefined" ? $userId : 0;
    for(var i in $menu){
        var $checked = ($menu[i].url.indexOf($action+'?') != -1) ? 'active' : '',
            $url = $checked ? 'javascript:;' :$menu[i].url+'id='+$userId,
            $li = '<li class="'+$checked+'"><a href="'+$url+'">'+$menu[i].name+'</a></li>';
        $('.nav-tabs-client .nav-tabs').append($li);
    }

    /**
     * 服务对象健康档案的子菜单
     * */
    if($action == 'healthy'){
        var $healthyMenu = [{"name":"基础档案","url":"/index/Client/healthy?view=healthyBase&"},{"name":"既往病史","url":"/index/Client/healthy?view=medicalHistory&"},{"name":"体检记录","url":"/index/Client/healthy?view=physical&"},{"name":"药物过敏史","url":"/index/Client/healthy?view=allergy&"},{"name":"家族遗传史","url":"/index/Client/healthy?view=hereditary&"},{"name":"病历存档","url":"/index/Client/healthy?view=caseRecord&"}],
            $view = getUrlParam('view');
        for(var i in $healthyMenu){
            var $checked = ($healthyMenu[i].url.indexOf('view='+$view) != -1) ? 'active' : '',
                $url = $checked ? 'javascript:;' :$healthyMenu[i].url+'id='+$userId,
                $li = '<li class="'+$checked+'"><a href="'+$url+'">'+$healthyMenu[i].name+'</a></li>';
            $('.nav-tabs-healthy').append($li);
        }
    }

    /**
     * 服务对象积分的子菜单
     * */
    if($action == 'integral'){
        var $integralMenu = [{"name":"积分管理","url":"/index/Client/integral?id="+$userId+"&type=0"},{"name":"累积积分","url":"/index/Client/integral?id="+$userId+"&type=1"},{"name":"核销积分","url":"/index/Client/integral?id="+$userId+"&type=2"}],
        $getType = getUrlParam('type');
        $type = $getType ? $getType : 0 ;
        for(var i in $integralMenu){
            var $checked = ($integralMenu[i].url.indexOf('type='+$type) != -1) ? 'active' : '',
                $li = '<li class="'+$checked+'"><a href="'+$integralMenu[i]['url']+'">'+$integralMenu[i].name+'</a></li>';
            $('.nav-tabs-integral').append($li);
        }
    }

    /**
     * 多图上传的删除
     * */
    $('body').delegate('.multiple-upload a','click',function(){
        $(this).parent().remove();
    })

    /**
     * 服务对象基础信息的编辑
     * */
    $infoType = '',                 // 编辑类型
        $infoHtml = new Array;          // 编辑前的HTML
    $('.c-b-info').on('click',"button.editBtn",function(){
        $infoType = $(this).parents('[class*="-info"]').attr('class');
        var $parentInfo = $('.'+$infoType),
            $html = $parentInfo.find('div.m-t').html(),
            $button = '<button type="submit" class="btn btn-sm btn-primary active">保存</button>'+
                '<button type="button" class="btn btn-sm btn-white" onclick="cancelEdit(this)">取消</button>';
        $infoHtml[$infoType] = $html;
        $parentInfo.find('.text-right').empty().append($button);
        if($infoType == 'user-info'){
            editUserInfo();
            return false;
        }
        if($infoType == 'contacts-info'){
            editContactsInfo();
            return false;
        }
        if($infoType == 'group-info'){
            editGroupInfo();
            return false;
        }
        if($infoType == 'tag-info'){
            editTagInfo();
            return false;
        }
        if($infoType == 'other-info'){
            editOtherInfo();
            return false;
        }
    });

    /**
     * 服务对象首页和健康管理首页
     * */
    if($.inArray($controller,['Client','Healthy']) != -1 && $action == 'index'){
        initDate(['start_create','end_create','start_birthday','end_birthday']);
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
            if($.inArray($searchInfo['name'],['age','create','birthday']) != -1){
                $form.find('[name$="'+$searchInfo['name']+'"]').val('');
            } else {
                $form.find("[name='"+$searchInfo['name']+"']").val('');
            }
            $form.submit();
        })
        /**
         * 获取所有分组
         * */
        $.post('/index/Client/group',{},function(data){
            for(var i in data){
                var $selected = $.inArray(data[i]['id'].toString(),JSON.parse($selectedGroup)) != -1 ? 'selected' : '';
                var $option = '<option value="'+data[i]['id']+'" '+$selected+'>'+data[i]['name']+'</option>';
                $('.chosen-group').append($option);
            }
            $('.chosen-group').chosen();
        })
        /**
         * 获取所有标签
         * */
        $.post('/index/Client/tag',{},function(data){
            for(var i in data){
                var $selected = $.inArray(data[i]['id'].toString(),JSON.parse($selectedTag)) != -1 ? 'selected' : '';
                var $option = '<option value="'+data[i]['id']+'" '+$selected+'>'+data[i]['name']+'</option>';
                $('.chosen-tag').append($option);
            }
            $('.chosen-tag').chosen();
        })
    }

})

/**
 * 编辑用户信息节点
 * */
var editUserInfo = function() {
    var $parent = $('.user-info').find('div.m-t'),
        $userJson = JSON.parse($userInfo),
        $head = $userJson.head ? $userJson.head : '/public/static/img/head.jpg',
        $sex = 1;
    $parent.empty();
    var $userNode = '<div class="form-group"><input type="hidden" name="id" value="'+$userJson.id+'"><label class="col-sm-2 control-label">头像</label><div class="col-sm-5"><div class="upload-file style-h" title="点击上传头像"><img src="'+$head+'"><input type="hidden" name="head" value="'+$userJson.head+'"><div class="layui-btn" id="head"><i class="fa fa-upload"></i></div><input class="layui-upload-file" type="file" name="file"></div></div></div><div class="form-group"><label class="col-sm-2 control-label">* 姓名</label><div class="col-sm-5"><input type="text" name="name" value="'+$userJson.name+'" class="form-control" placeholder="姓名"></div></div><div class="form-group"><label class="col-sm-2 control-label">* 身份证</label><div class="col-sm-5"><input type="text" name="id_number" value="'+$userJson.id_number+'" class="form-control" placeholder="15位或18位身份证号"></div></div><div class="form-group"><label class="col-sm-2 control-label">性别</label><div class="col-sm-5"><div class="radio radio-success radio-inline"><input type="radio" id="sex1" value="1" name="sex"><label for="sex1"> 男 </label></div><div class="radio radio-success radio-inline"><input type="radio" id="sex2" value="2" name="sex"><label for="sex2"> 女 </label></div></div></div><div class="form-group"><label class="col-sm-2 control-label">出生日期</label><div class="col-sm-5"><input type="text" name="birthday" id="birthday" value="'+$userJson.birthday+'" class="form-control layer-date"></div></div><div class="form-group"><label class="col-sm-2 control-label">电话</label><div class="col-sm-5"><input type="text" name="mobile" value="'+$userJson.mobile+'" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">现住地址</label><div class="col-sm-5"><input type="text" name="address" value="'+$userJson.address+'" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">户籍地址</label><div class="col-sm-5"><input type="text" name="permanent_address" value="'+$userJson.permanent_address+'" class="form-control"></div></div>';
    $parent.append($userNode);
    $sex = ($userJson.sex == '男') ? $sex : 2;
    $parent.find('#sex'+$sex).click();
    // 加载上传图片方法
    iotUpload({'elem':'head','url':'/index/Client/uploadHead'});
    // 时间插件
    initDate(['birthday']);
}

/**
 * 编辑联系人节点
 * */
var $concatsSelectNode = '<div class="form-group"><label class="col-sm-2 control-label">联系人</label><div class="col-sm-2"><select name="type" class="form-control"><option value="">请选择关系</option><option value="1">子女</option><option value="2">父母</option><option value="3">亲戚</option><option value="4">朋友</option><option value="5">同事</option><option value="6">同学</option><option value="7">其它</option></select></div><div class="col-sm-2"><input type="text" name="name" value="" class="form-control"></div><div class="col-sm-2"><input type="text" name="mobile" value="" class="form-control"></div><a class="btn btn-link" style="color: red;"><i class="fa fa-close fa-lg"></i></a></div>',
    $contactsButtonNode = '<div class="form-group"><label class="col-sm-2 control-label"></label><div class="col-sm-2"><a class="btn btn-white addContacts"><i class="fa fa-plus">&nbsp;添加联系人</i></a></div></div>';
var editContactsInfo = function() {
    var $parent = $('.contacts-info').find('div.m-t'),
        $contactsJson = JSON.parse($contactsInfo),
        $contactsNode = $concatsSelectNode,
        $i = 1;
    $parent.empty();
    for(var i in $contactsJson){
        $parent.append($contactsNode);
        $parent.find('[name=type]').val($contactsJson[i].type).attr('name','type'+i);
        $parent.find('[name=name]').val($contactsJson[i].name).attr('name','name'+i);
        $parent.find('[name=mobile]').val($contactsJson[i].mobile).attr('name','mobile'+i);
    }
    // 最多添加三个
    if($contactsJson.length < 3){
        $parent.append($contactsButtonNode);
    }
}

/**
 * 编辑分组信息节点
 * */
var editGroupInfo = function() {
    var $parent = $('.group-info').find('div.m-t'),
        $groupJson = JSON.parse($groupInfo),
        $groupId = $groupJson.length ? $groupJson[0]['gid'].split(',') : new Array;
    /**
     * 获取所有的分组
     * */
    $.post('/index/Client/group',{},function(data){
        $parent.empty();
        var $groupNode = '<div class="form-group"><label class="col-sm-2 control-label">分组</label><div class="col-sm-7"></div></div>';
        $parent.append($groupNode);
        for(var i in data){
            var $checked = ($.inArray(data[i].id.toString(),$groupId) != -1) ? 'checked' : '' ;
            var $checkbox = '<div class="checkbox checkbox-success checkbox-inline"><input type="checkbox" name="group[]" id="group'+i+'" value="'+data[i].id+'" '+$checked+'><label for="group'+i+'"> '+data[i].name+' </label></div>';
            $parent.find('.col-sm-7').append($checkbox);
        }
    })
}

/**
 * 编辑标签信息节点
 * */
var editTagInfo = function() {
    var $parent = $('.tag-info').find('div.m-t'),
        $tagJson = JSON.parse($tagInfo),
        $tagId = $tagJson.length ? $tagJson[0]['tid'].split(',') : new Array;
    /**
     * 获取所有的标签
     * */
    $.post('/index/Client/tag',{},function(data){
        $parent.empty();
        var $tagNode = '<div class="form-group"><label class="col-sm-2 control-label">标签</label><div class="col-sm-7"></div></div>';
        $parent.append($tagNode);
        for(var i in data){
            var $checked = ($.inArray(data[i].id.toString(),$tagId) != -1) ? 'checked' : '' ;
            var $checkbox = '<div class="checkbox checkbox-success checkbox-inline"><input type="checkbox" name="tag[]" id="tag'+i+'" value="'+data[i].id+'" '+$checked+'><label for="tag'+i+'"> '+data[i].name+' </label></div>';
            $parent.find('.col-sm-7').append($checkbox);
        }
    })
}

/**
 * 编辑其他信息节点
 * */
var $hobby = ['足球','篮球','排球','羽毛球','乒乓球','钓鱼','旅游','舞蹈','影视','跑步','游泳','登山','自行车','卡拉OK','乐器演奏','摄影','美食/烹饪','绘画','书法','读书','棋牌','花艺盆景'],
    $diet_taboo = ['糖','海鲜','蛋类','豆制品'],
    $caregiver = ['自己','老伴','儿孙子女或其他亲属','保姆/钟点工','邻里帮助'];
var editOtherInfo = function() {
    var $parent = $('.other-info').find('div.m-t'),
        $otherJson = JSON.parse($otherInfo);
    $parent.empty();
    var $otherNode = '<div class="form-group"><label class="col-sm-2 control-label">籍贯</label><div class="col-sm-5"><input type="text" name="native_place" value="" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">民族</label><div class="col-sm-5"><input type="text" name="nation" value="" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">受教育程度</label><div class="col-sm-5"><input type="text" name="education" value="" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">政治面貌</label><div class="col-sm-5"><input type="text" name="political" value="" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">宗教</label><div class="col-sm-5"><input type="text" name="religion" value="" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">兴趣爱好</label><div class="col-sm-5" id="c-o-hobby"></div></div><div class="form-group"><label class="col-sm-2 control-label">饮食禁忌</label><div class="col-sm-5" id="c-o-diet-taboo"></div></div><div class="form-group"><label class="col-sm-2 control-label">血型</label><div class="col-sm-5"><select name="blood_type" class="form-control"><option value="0">请选择</option><option value="1">A型</option><option value="2">B型</option><option value="3">AB型</option><option value="4">O型</option></select></div></div><div class="form-group"><label class="col-sm-2 control-label">RH阴性</label><div class="col-sm-5"><select name="rh_negative" class="form-control"><option value="0">请选择</option><option value="1">是</option><option value="2">否</option></select></div></div><div class="form-group"><label class="col-sm-2 control-label">经济来源</label><div class="col-sm-5"><input type="text" name="economic_source" value="" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">生活来源</label><div class="col-sm-5"><input type="text" name="livelihood" value="" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">照看人</label><div class="col-sm-5" id="c-o-caregiver"></div></div><div class="form-group"><label class="col-sm-2 control-label">身体状况</label><div class="col-sm-5"><input type="text" name="healthy" value="" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">居住类型</label><div class="col-sm-5"><input type="text" name="live" value="" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">住房类型</label><div class="col-sm-5"><input type="text" name="house" value="" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">资料提供人</label><div class="col-sm-5"><input type="text" name="provider" value="" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">备注</label><div class="col-sm-5"><input type="text" name="remarks" value="" class="form-control"></div></div>';
    $parent.append($otherNode);
    $.each($hobby,function(i){
        var $hobbyNode = '<div class="checkbox checkbox-success checkbox-inline">' +
            '<input type="checkbox" name="hobby[]" id="hobby'+i+'" value="'+$hobby[i]+'">' +
            '<label for="hobby'+i+'"> '+$hobby[i]+' </label>' +
            '</div>';
        $parent.find('#c-o-hobby').append($hobbyNode);
    })
    $.each($diet_taboo,function(i){
        var $dietNode = '<div class="checkbox checkbox-success checkbox-inline">' +
            '<input type="checkbox" name="diet_taboo[]" id="diet_taboo'+i+'" value="'+$diet_taboo[i]+'">' +
            '<label for="diet_taboo'+i+'"> '+$diet_taboo[i]+' </label>' +
            '</div>';
        $parent.find('#c-o-diet-taboo').append($dietNode);
    })
    $.each($caregiver,function(i){
        var $caregiverNode = '<div class="checkbox checkbox-success checkbox-inline">' +
            '<input type="checkbox" name="caregiver[]" id="caregiver'+i+'" value="'+$caregiver[i]+'">' +
            '<label for="caregiver'+i+'"> '+$caregiver[i]+' </label>' +
            '</div>';
        $parent.find('#c-o-caregiver').append($caregiverNode);
    })
    if($.isEmptyObject($otherJson)){
        return false;
    }
    // 血型
    $parent.find('[name=native_place]').val($otherJson.native_place);
    $parent.find('[name=nation]').val($otherJson.nation);
    $parent.find('[name=education]').val($otherJson.education);
    $parent.find('[name=political]').val($otherJson.political);
    $parent.find('[name=religion]').val($otherJson.religion);
    $parent.find('[name=economic_source]').val($otherJson.economic_source);
    $parent.find('[name=livelihood]').val($otherJson.livelihood);
    $parent.find('[name=healthy]').val($otherJson.healthy);
    $parent.find('[name=live]').val($otherJson.live);
    $parent.find('[name=house]').val($otherJson.house);
    $parent.find('[name=provider]').val($otherJson.provider);
    $parent.find('[name=remarks]').val($otherJson.remarks);
    $parent.find('[name=blood_type]').val($otherJson.blood_type);
    $parent.find('[name=rh_negative]').val($otherJson.rh_negative);
    // 兴趣爱好选中
    for(var i in $otherJson.hobby){
        $parent.find('#c-o-hobby input[value="'+$otherJson.hobby[i]+'"]').attr('checked',true);
    }
    // 饮食禁忌选中
    for(var i in $otherJson.diet_taboo){
        $parent.find('#c-o-diet-taboo input[value="'+$otherJson.diet_taboo[i]+'"]').attr('checked',true);
    }
    // 照看人选中
    for(var i in $otherJson.caregiver){
        $parent.find('#c-o-caregiver input[value="'+$otherJson.caregiver[i]+'"]').attr('checked',true);
    }
}

/**
 * 取消编辑
 * */
var cancelEdit = function(obj) {
    var $parent = $(obj).parent('.text-right'),
        $parentInfo = $parent.parent().parent().parent(),
        $button = '<button type="button" class="btn btn-sm btn-white editBtn">编辑</button>';
    $parent.empty().append($button);
    $parentInfo.find('div.m-t').empty().append($infoHtml[$parentInfo.attr('class')]);
}

/**
 * 联系人删除/添加时改变对应的name
 * */
var contactsIndex = function(obj) {
    var $contacts = $('.contacts-info').find('.m-t .form-group');
    for(var i in $contacts){
        $contacts.eq(i).find('[name^="type"]').attr('name','type'+i);
        $contacts.eq(i).find('[name^="name"]').attr('name','name'+i);
        $contacts.eq(i).find('[name^="mobile"]').attr('name','mobile'+i);
    }
}

/**
 * 查看图片
 * */
var $imageModal = '<div class="modal inmodal fade" id="view-image-modal" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title">查看图片</h1></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">确定</button></div></div></div></div>';
var viewImage = function(obj) {
    var $image = $(obj).data('image');
    if(!$image){
        return false;
    }
    $('body').find('#view-image-modal').remove();
    $('body').append($imageModal);
    var $modal = $('#view-image-modal');
    for(var i in $image){
        var $imageNode = '<img src="'+$image[i]+'" style="width: 100%;height: 100%;"/>';
        $modal.find('.modal-body').append($imageNode);
    }
    $modal.modal('show');
}


/**
 * 下载模板
 * */
var clientDown = function() {
    window.location.href = "/index/Client/clientDown";
}


/**
 * 批量添加
 * */
var $destoryModal = '<div class="modal inmodal fade" id="destory-modal" tabindex="-1" role="dialog"  aria-hidden="true" data-backdrop="static"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title">添加服务对象</h1></div><form class="form-horizontal"><div class="modal-body"><div class="form-group"><div class="col-sm-12"><div class="input-group"><input type="text" name="integra_excel" readonly class="form-control"><span class="input-group-btn" style="vertical-align: top;"><button type="button" class="btn btn-primary" id="integra_excel"><i class="fa fa-cloud-upload"></i> &nbsp;上传文件</button><input class="layui-upload-file" type="file" accept="undefined" name="file"></span></div></div></div><div class="modal-footer"><button type="button" class="btn btn-white" data-dismiss="modal">取消</button><button type="submit" class="btn btn-primary">提交</button></div></div></form></div></div></div>';

var clientSub = function() {
    $('body').find('#destory-modal').remove();
    $('body').append($destoryModal);
    $modal = $('#destory-modal');
    iotUploadExcel({'elem':'integra_excel','url':'/index/Client/clientSub'});
    $modal.modal('show');
}

/**
 * 提交批量
 * */
$('body').delegate('#destory-modal','show.bs.modal',function(){
    $(this).find('form').validate({
        rules: {
            integra_excel: {
                required: !0
            }
        },
        messages: {
            integra_excel: {
                required: '请上传excel文件'
            }
        },
        submitHandler: function(form) {
            var $subBtn = $(form).find('[type=submit]'),
                $fData = $(form).serializeArray();
            $subBtn.attr('disabled',true);
            $.post('/index/Client/destoryClient',$fData,function(data){
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