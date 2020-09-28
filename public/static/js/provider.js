/**
 * 服务商js
 * */
$(function(){
    /**
     * 搜索条件删除
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
        if($.inArray($searchInfo['name'],['bind_time']) != -1){
            $form.find('[name$="'+$searchInfo['name']+'"]').val('');
        } else {
            $form.find("[name='"+$searchInfo['name']+"']").val('');
        }
        $form.submit();
    })

    /**
     * 添加/编辑服务商modal
     * */
    var $providerModal = '<div class="modal inmodal fade add-provider-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"><div class="modal-dialog" style="width: 35%;"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title"></h1></div><form class="form-horizontal"><input type="hidden" name="id" value=""><div class="modal-body"><div class="form-group"><label class="col-sm-2 control-label">* 服务商</label><div class="col-sm-10"><input type="text" name="company" value="" class="form-control" required></div></div><div class="form-group"><label class="col-sm-2 control-label">* 登录账号</label><div class="col-sm-10"><input type="text" name="name" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">* 密码</label><div class="col-sm-10"><input type="text" name="password" class="form-control" autocomplete="new-password" onfocus="this.type=\'password\'" placeholder="编辑状态下可不填，默认原密码"></div></div><div class="form-group"><label class="col-sm-2 control-label">* 联系人</label><div class="col-sm-10"><input type="text" name="linkman" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">* 联系电话</label><div class="col-sm-10"><input type="text" name="mobile" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">* 企业地址</label><div class="col-sm-10"><input type="text" name="address" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">* 状态</label><div class="col-sm-10"><input type="checkbox" name="status" class="js-switch" checked/>&nbsp;&nbsp;状态：<span class="m-b-none switch-tip">开启</span></div></div></div><div class="modal-footer"><button type="button" class="btn btn-white" data-dismiss="modal">取消</button><button type="submit" class="btn btn-primary">确定</button></div></form></div></div></div>',
        $editProviderID;

    /**
     * 添加服务商
     * */
    $('body').find('.a-provider').on('click',function(){
        $('body').find('.add-provider-modal').remove();
        $('body').append($providerModal);
        var $modal = $('.add-provider-modal');
        $editProviderID = 0;
        $modal.find('.modal-title').text('添加服务商');
        switchJs();
        $modal.modal('show');
    })

    /**
     * 编辑服务商
     * */
    $('body').find('.e-provider').on('click',function(){
        $('body').find('.add-provider-modal').remove();
        $('body').append($providerModal);
        var $modal = $('.add-provider-modal');
        $modal.find('.modal-title').text('编辑服务商');
        // 获取服务商信息
        var $index = $('.e-provider').index(this),
            $providerJson = JSON.parse($provider),
            $providerInfo;
        for(var i in $providerJson){
            if($index == i){
                $providerInfo = $providerJson[i];
            }
        }
        $editProviderID = $providerInfo.id;
        $modal.find('[name=company]').val($providerInfo.company);
        $modal.find('[name=name]').val($providerInfo.name);
        $modal.find('[name=linkman]').val($providerInfo.linkman);
        $modal.find('[name=mobile]').val($providerInfo.mobile);
        $modal.find('[name=address]').val($providerInfo.address);
        console.log($providerInfo.status);
        if($providerInfo.status == '关闭'){
            $modal.find('[name=status]').removeAttr('checked');
            $modal.find('.switch-tip').text('开启');
        }
        switchJs('.js-switch','开启','关闭');
        $modal.modal('show');
    })

    /**
     * 自定义验证密码
     * */
    $.validator.addMethod('checkPassword', function(value, element, param) {
        if(!$editProviderID || ($editProviderID && value)){
            return checkPass(value);
        }
        return true;
    });

    /**
     * 添加/更新服务商信息
     * */
    $('body').delegate('.add-provider-modal','show.bs.modal',function(){
        $(this).find('form').validate({
            rules: {
                company: {
                    required: !0
                },
                name: {
                    required: !0
                },
                password: {
                    checkPassword: true
                },
                linkman: {
                    required: !0
                },
                mobile: {
                    required: !0
                },
                address: {
                    required: !0
                },
            },
            messages: {
                company: {
                    required: "请填写服务商名称",
                },
                name: {
                    required: "请填写服务商登录账号",
                },
                password: {
                    checkPassword: "请填写服务商登录密码",
                },
                linkman: {
                    required: "请填写联系人名称",
                },
                mobile: {
                    required: "请填写联系人电话",
                },
                address: {
                    required: "请填写企业地址",
                },
            },
            //验证通过后的执行方法
            submitHandler: function(form) {
                var $subBtn = $(form).find('[type=submit]'),
                    $fData = $(form).serializeArray();
                $fData.push({'name':'id','value':$editProviderID});
                $subBtn.attr('disabled',true);
                $.post('/index/Provider/submitProviderInfo',$fData,function(data){
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
     * 删除服务商信息
     * */
    $('.d-provider').on('click',function(){
        var $index = $('.d-provider').index(this),
            $providerJson = JSON.parse($provider),
            $providerInfo;
        for(var i in $providerJson){
            if($index == i){
                $providerInfo = $providerJson[i];
            }
        }
        layer.confirm(
            '确定删除服务商：'+$providerInfo.name+'？',
            {
                btn : ['确定', '取消'],
                offset: '20%',
                shadeClose: true,
                btn1:function(obj){
                    $(".layui-layer-btn0").attr('disabled',true);
                    $.post('/index/Provider/delProvider',{'id':$providerInfo.id},function(data){
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


    /**
     * 添加/编辑服务商服务项目modal
     * */
    var $serviceId, $serviceModal = '<div class="modal inmodal fade" id="service-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title"></h1></div><form class="form-horizontal"><div class="modal-body"><div class="form-group"><label class="col-sm-2 control-label">* 服务名称</label><div class="col-sm-10"><input type="text" name="name" value="" class="form-control" required></div></div><div class="form-group"><label class="col-sm-2 control-label">一级类目</label><div class="col-sm-10"><select name="project1" class="form-control"></select></div></div><div class="form-group"><label class="col-sm-2 control-label">二级类目</label><div class="col-sm-10"><select name="project2" class="form-control"></select></div></div><div class="form-group"><label class="col-sm-2 control-label">备注</label><div class="col-sm-10"><textarea name="remarks" cols="30" rows="3" class="form-control"></textarea></div></div></div><div class="modal-footer"><button type="button" class="btn btn-white" data-dismiss="modal">取消</button><button type="submit" class="btn btn-primary">确定</button></div></form></div></div></div>';

    /**
     * 添加服务项目
     * */
    $('.a-p-service').on('click',function(){
        $('body').find('#service-modal').remove();
        $('body').append($serviceModal);
        var $modal = $('#service-modal');
        $modal.find('.modal-title').text('添加服务项目');
        for(var f in $project){
            var $option = '<option value="'+$project[f]['id']+'">'+$project[f]['name']+'</option>';
            $modal.find('[name=project1]').append($option);
        }
        for(var s in $project[0]['nodes']){
            var $option = '<option value="'+$project[0]['nodes'][s]['id']+'">'+$project[0]['nodes'][s]['name']+'</option>';
            $modal.find('[name=project2]').append($option);
        }
        $modal.modal('show');
    })

    /**
     * 编辑服务上服务项目
     * */
    $('.e-p-service').on('click',function(){
        $('body').find('#service-modal').remove();
        $('body').append($serviceModal);
        var $modal = $('#service-modal'),
            $index = $('.e-p-service').index(this),
            $serviceJson = JSON.parse($service),
            $serviceInfo,
            $editServiceInfo;
        for(var i in $serviceJson){
            if($index == i){
                $serviceInfo = $serviceJson[i];
            }
        }
        for(var f in $project){
            var $option = '<option value="'+$project[f]['id']+'">'+$project[f]['name']+'</option>';
            $modal.find('[name=project1]').append($option);
            if($serviceInfo.f_project_id == $project[f]['id']){
                $editServiceInfo = $project[f];
            }
        }
        for(var s in $editServiceInfo['nodes']){
            var $option = '<option value="'+$editServiceInfo['nodes'][s]['id']+'">'+$editServiceInfo['nodes'][s]['name']+'</option>';
            $modal.find('[name=project2]').append($option);
        }
        $serviceId = $serviceInfo.id;
        $modal.find('.modal-title').text('编辑服务项目');
        $modal.find('[name=name]').val($serviceInfo.name);
        $modal.find('[name=project1]').val($serviceInfo.f_project_id);
        $modal.find('[name=project2]').val($serviceInfo.s_project_id);
        $modal.find('[name=remarks]').val($serviceInfo.remarks);
        $modal.modal('show');
    })

    /**
     * 服务一级类目改变
     * */
    $('body').delegate('#service-modal [name=project1]','change',function(){
        var $id = $(this).val(),
            $select = $('#service-modal').find('[name=project2]');
        $select.empty();
        for(var f in $project){
            if($id != $project[f]['id']){
                continue;
            }
            for(var s in $project[f]['nodes']){
                var $option = '<option value="'+$project[f]['nodes'][s]['id']+'">'+$project[f]['nodes'][s]['name']+'</option>';
                $select.append($option);
            }
        }
    })

    /**
     * 提交服务项目信息
     * */
    $('body').delegate('#service-modal','show.bs.modal',function(){
        $(this).find('form').validate({
            rules: {
                name: {
                    required: !0
                }
            },
            messages: {
                name: {
                    required: '请填写服务名称'
                }
            },
            submitHandler: function(form) {
                var $subBtn = $(form).find('[type=submit]'),
                    $fData = $(form).serializeArray();
                $fData.push({'name':'uid','value':$uid});
                $fData.push({'name':'id','value':$serviceId});
                $subBtn.attr('disabled',true);
                $.post('/index/Provider/submitService',$fData,function(data){
                    if(data.code == 0){
                        layer.msg(data.msg,{icon:1,time:2000},function(){
                            location.reload();
                        })
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
     * 删除服务商服务项目
     * */
    $('.d-p-service').on('click',function(){
        var $index = $('.d-p-service').index(this),
            $serviceJson = JSON.parse($service),
            $serviceInfo;
        for(var i in $serviceJson){
            if($index == i){
                $serviceInfo = $serviceJson[i];
            }
        }
        layer.confirm(
            '确定删除服务项目：'+$serviceInfo.name+'？',
            {
                btn : ['确定', '取消'],
                offset: '20%',
                shadeClose: true,
                btn1:function(obj){
                    $(".layui-layer-btn0").attr('disabled',true);
                    $.post('/index/Provider/delService',{'id':$serviceInfo.id,'uid':$uid},function(data){
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

    /**
     * 添加/编辑服务商的服务类目modal
     * */
    var $id, $pid, $projectModal = '<div class="modal inmodal fade" id="project-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title"></h1></div><form class="form-horizontal"><div class="modal-body"><div class="form-group"><label class="col-sm-2 control-label">* 服务类目</label><div class="col-sm-10"><input type="text" name="name" value="" class="form-control" maxlength="10" required></div></div><div class="form-group"><label class="col-sm-2 control-label">备注</label><div class="col-sm-10"><textarea name="remarks" cols="30" rows="3" class="form-control"></textarea></div></div></div><div class="modal-footer"><button type="reset" class="btn btn-white">重置</button><button type="submit" class="btn btn-primary">确定</button></div></form></div></div></div>';

    /**
     * 添加服务类目
     * */
    $('.a-p-project').on('click',function(){
        $('body').find('#project-modal').remove();
        $('body').append($projectModal);
        var $modal = $('#project-modal');
        $pid = parseInt($(this).data('id'));
        $id = 0;
        $modal.find('.modal-title').text('添加服务类目');
        $modal.modal('show');
    })

    /**
     * 编辑服务类目
     * */
    $('.e-p-project').on('click',function(){
        $('body').find('#project-modal').remove();
        $('body').append($projectModal);
        $pid = 0;
        $id = $(this).data('id');
        var $modal = $('#project-modal'),
            $projectInfo = menuInfo(JSON.parse($project),$id);
        $modal.find('.modal-title').text('编辑服务类目');
        $modal.find('[name=name]').val($projectInfo.name);
        $modal.find('[name=remarks]').val($projectInfo.remarks);
        $modal.modal('show');
    })

    /**
     * 提交服务类目信息
     * */
    $('body').delegate('#project-modal','show.bs.modal',function(){
        $(this).find('form').validate({
            rules: {
                name: {
                    required: !0
                }
            },
            messages: {
                name: {
                    required: '请填写服务类目'
                }
            },
            submitHandler: function(form) {
                var $subBtn = $(form).find('[type=submit]'),
                    $fData = $(form).serializeArray();
                $fData.push({'name':'pid','value':$pid});
                $fData.push({'name':'id','value':$id});
                $subBtn.attr('disabled',true);
                $.post('/index/Provider/submitProject',$fData,function(data){
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
     * 删除分类
     * */
    $('.d-p-project').on('click',function(){
        var $id = $(this).data('id'),
            $projectInfo = menuInfo(JSON.parse($project),$id);
        layer.confirm(
            '确定删除分类：'+$projectInfo.name+'？',
            {
                btn : ['确定', '取消'],
                offset: '20%',
                shadeClose: true,
                btn1:function(obj){
                    $(".layui-layer-btn0").attr('disabled',true);
                    $.post('/index/Provider/delProject',{'id':$id},function(data){
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

    /**
     * 服务对象详情页的菜单
     * */
    var $menu = [{"name":"基础信息","url":"/index/Provider/details?"},{"name":"服务人员","url":"/index/Provider/detailsStaff?"},{"name":"服务工单","url":"/index/Provider/detailsWork?"}];
    $userId = JSON.parse($userId);
    $userId = typeof($userId) != "undefined" ? $userId : 0;
    for(var i in $menu){
        var $checked = ($menu[i].url.indexOf($action+'?') != -1) ? 'active' : '',
            $url = $checked ? 'javascript:;' :$menu[i].url+'id='+$userId,
            $li = '<li class="'+$checked+'"><a href="'+$url+'">'+$menu[i].name+'</a></li>';
        $('.nav-tabs-client .nav-tabs').append($li);
    }

    /**
     * 服务商基础信息编辑
     * */
    var $providerNode = ' <div class="form-group"><label class="col-sm-2 control-label">法人代表</label><div class="col-sm-5"><input type="text" name="legal_person" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">法人联系方式</label><div class="col-sm-5"><input type="text" name="legal_mobile" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">注册资金</label><div class="col-sm-5"><input type="text" name="registered_capital" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label times">加盟时间</label><div class="col-sm-5"> <input type="text" name="join_time" id="join_time" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label times">截止有效期</label><div class="col-sm-5"><input type="text" name="expiry_time" id="expiry_time" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">组织机构编号</label><div class="col-sm-5"><input type="text" name="org_code" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">组织机构分类</label><div class="col-sm-5"><input type="text" name="org_type" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">税务编号</label><div class="col-sm-5"><input type="text" name="tax_number" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">卫生许可证编号</label><div class="col-sm-5"><input type="text" name="health_permit" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">备注</label><div class="col-sm-5"><textarea name="remarks" id="" cols="30" rows="3" class="form-control"></textarea></div></div>',
        $providerID;        // 服务商id
    $infoHtml = '';          // 编辑前的HTML
    $('.p-b-info').on('click',"button.editBtn",function(){
        var $parentInfo = $(this).parents('form'),
            $html = $parentInfo.find('div.m-t').html(),
            $button = '<button type="submit" class="btn btn-sm btn-primary active">保存</button>'+
                '<button type="button" class="btn btn-sm btn-white" onclick="cancelEdit(this)">取消</button>';
        $infoHtml = $html;
        $parentInfo.find('.text-right').empty().append($button);
        $parentInfo.find('div.m-t').empty().append($providerNode);
        // 获取详情
        $providerJson = $details;
        console.log($providerJson)
        $providerID = $providerJson.id;
        $parentInfo.find('[name=legal_person]').val($providerJson.legal_person);
        $parentInfo.find('[name=legal_mobile]').val($providerJson.legal_mobile);
        $parentInfo.find('[name=registered_capital]').val($providerJson.registered_capital);
        $parentInfo.find('[name=join_time]').val($providerJson.join_time);
        $parentInfo.find('[name=expiry_time]').val($providerJson.expiry_time);
        $parentInfo.find('[name=org_code]').val($providerJson.org_code);
        $parentInfo.find('[name=org_type]').val($providerJson.org_type);
        $parentInfo.find('[name=tax_number]').val($providerJson.tax_number);
        $parentInfo.find('[name=health_permit]').val($providerJson.health_permit);
        $parentInfo.find('[name=remarks]').val($providerJson.remarks);
        providerDate(['join_time','expiry_time']);
    });

    /**
     * 保存服务商基础信息
     * */
    $('body').delegate('.p-b-info form','submit',function(e){
        e.preventDefault();
        var $subBtn = $(this).find('[type=submit]'),
            $fData = $(this).serializeArray();
        $fData.push({'name':'id','value':$providerID});
        $subBtn.attr('disabled',true);
        $.post('/index/Provider/subProviderBaseInfo',$fData,function(data){
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
})

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

/**
 * 取消编辑
 * */
var cancelEdit = function(obj) {
    var $parent = $(obj).parent('.text-right'),
        $parentInfo = $parent.parent().parent().parent(),
        $button = '<button type="button" class="btn btn-sm btn-white editBtn">编辑</button>';
    $parent.empty().append($button);
    $parentInfo.find('div.m-t').empty().append($infoHtml);
}

/**
 * 获取服务类目
 * */
var $project;
var getProject = function() {
    $.post('/index/Provider/project',{},function(data){
        $project = data;
    })
}

// 初始化时间
var providerDate = function(obj){
    layui.use('laydate', function()
    {
        var laydate = layui.laydate;
        for(var i in obj){
            laydate.render({
                elem: '#'+obj[i],
                theme: 'grid'
            });
        }
    });
}