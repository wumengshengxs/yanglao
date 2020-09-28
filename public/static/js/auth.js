/**
 * 权限js
 * */
var $menu;  // 菜单数据
$(function(){
    /**
     * 添加管理员
     * */
    var $userAction,    // 用户操作类型
        $editUseriD;    // 要编辑的用户id
    $('.a-user').on('click',function(){
        $userAction = 1;
        $('body').find('#user-modal').remove();
        $('body').append($userModal);
        var $modal = $('#user-modal');
        $modal.find('.modal-title').text('添加管理员');
        switchJs();
        roleList();
        $modal.modal('show');
    })

    /**
     * 编辑管理员modal
     * */
    $('.e-user').on('click',function(){
        $userAction = 2;
        $('body').find('#user-modal').remove();
        $('body').append($userModal);
        roleList();
        var $modal = $('#user-modal'),
            $userJson = JSON.parse($userList),
            $index = $('tbody .e-user').index(this)+1,
            $userInfo;
        for(var i in $userJson){
            if(i == $index){
                $userInfo = $userJson[i];
                break;
            }
        }
        $editUseriD = $userInfo.id;
        $modal.find('.modal-title').text('编辑管理员');
        $modal.find('[name=name]').val($userInfo.u_name).attr('disabled',true);
        $modal.find('[name=password]').removeAttr('required').attr('placeholder','编辑状态密码可不填，默认原密码');
        $modal.find('[name=role]').val($userInfo.r_id);
        $modal.find('[name=mobile]').val($userInfo.mobile);
        $modal.find('[name=email]').val($userInfo.email);
        $modal.find('[name=remarks]').val($userInfo.remarks);
        if($userInfo.u_status == '停用'){
            $modal.find('[name=status]').removeAttr('checked');
            $modal.find('.switch-tip').text('停用');
        }
        switchJs();
        $modal.modal('show');
    })

    /**
     * validate自定义验证身份证
     * */
    $.validator.addMethod('checkPass', function(value, element, param) {
        if(value){
            return checkPass(value);
        }
        return true;
    });

    /**
     * 提交管理员信息
     * */
    $('body').delegate('#user-modal','show.bs.modal',function(){
        $(this).find('form').validate({
            rules: {
                name: {
                    required: !0
                },
                password: {
                    required: !0,
                    checkPass: true,
                },
                role: {
                    required: !0
                }
            },
            messages: {
                name: {
                    required: "请填写登录账号"
                },
                password: {
                    required: '请填写账户登录密码',
                    checkPass: "密码由6-18位字母、数字组成"
                },
                role: {
                    required: "请选择角色"
                }
            },
            //验证通过后的执行方法
            submitHandler: function(form)
            {
                var $subBtn = $(form).find('[type=submit]'),
                    $fData = $(form).serializeArray();
                if($userAction == 2){
                    $fData.push({'name':'id','value':$editUseriD});
                }
                $subBtn.attr('disabled',true);
                $.post('/index/Auth/submitUser',$fData,function(data){
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
     * 预加载菜单数据
     * */
    if($action == 'menu'){
        $.post('/index/Auth/menuList',{},function(data){
            $("#tree").treeview({
                data: data,
                levels:1,
                highlightSelected:false,
            });
            $menu = data;
        });
    }

    /**
     * 提交菜单信息
     * */
    $('body').delegate('#menu-modal','show.bs.modal',function(){
        $(this).find('form').validate({
            rules: {
                name: {
                    required: !0
                }
            },
            messages: {
                name: {
                    required: "请填写菜单名称",
                }
            },
            //验证通过后的执行方法
            submitHandler: function(form)
            {
                var $subBtn = $(form).find('[type=submit]'),
                    $fData = $(form).serializeArray();
                if($(form).find('[name=pid]').prop('disabled') && $menuAction == 1){
                    $fData.push({'name':'pid','value':$menuPid});
                }
                if($menuAction == 2){
                    $fData.push({'name':'id','value':$menuId});
                }
                $subBtn.attr('disabled',true);
                $.post('/index/Auth/submitMenu',$fData,function(data){
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
     * 添加角色modal
     * */
    var $editRoleId,    // 要编辑的角色id
        $roleAction;    // role操作方法
    $('.a-role').on('click',function(){
        $('body').find('#role-modal').remove();
        $('body').append($roleModal);
        var $modal = $('#role-modal');
        $modal.find('.modal-title').text('添加角色');
        switchJs();
        menuList();
        $roleAction = 1;
        $('#role-modal').modal('show');
    })

    /**
     * 编辑角色modal
     * */
    $('.e-role').on('click',function(){
        $('body').find('#role-modal').remove();
        $('body').append($roleModal);
        var $modal = $('#role-modal'),
            $index = $('tbody .e-role').index(this),
            $roleJson = JSON.parse($roleList),
            $roleInfo;          // 编辑的角色信息
        $modal.find('.modal-title').text('编辑角色');
        // 获取角色信息
        for(var i in $roleJson){
            if(i == $index){
                $roleInfo = $roleJson[i];
                break;
            }
        }
        $modal.find('[name=name]').val($roleInfo.name);
        $modal.find('[name=remarks]').val($roleInfo.remarks);
        if($roleInfo.r_status == '停用'){
            $modal.find('[name=status]').removeAttr('checked');
            $modal.find('.switch-tip').text('停用');
        }
        switchJs();
        menuList($roleInfo['m_id']);
        $editRoleId = $roleInfo.id;
        $roleAction = 2;
        $('#role-modal').modal('show');
    })

    /**
     * 提交角色信息
     * */
    $('body').delegate('#role-modal','show.bs.modal',function(){
        $(this).find('form').validate({
            rules: {
                name: {
                    required: !0
                }
            },
            messages: {
                name: {
                    required: "请填写角色名称",
                }
            },
            //验证通过后的执行方法
            submitHandler: function(form)
            {
                var $subBtn = $(form).find('[type=submit]'),
                    $tChecked = $('#tree').treeview('getChecked');
                    $menu_id = '';
                $fData = $(form).serializeArray();
                for(var i in $tChecked){
                    $menu_id += $tChecked[i]['id']+',';
                }
                $fData.push({'name':'menu_id','value':$menu_id.substring(0, $menu_id.lastIndexOf(','))});
                if($roleAction == 2){
                    $fData.push({'name':'id','value':$editRoleId});
                }
                $subBtn.attr('disabled',true);
                $.post('/index/Auth/submitRole',$fData,function(data){
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
     * 角色删除
     * */
    $('.d-role').on('click',function(){
        var $index = $('tbody .d-role').index(this),
            $roleJson = JSON.parse($roleList),
            $roleInfo;          // 编辑的角色信息
        // 获取角色信息
        for(var i in $roleJson){
            if(i == $index){
                $roleInfo = $roleJson[i];
                break;
            }
        }
        layer.confirm(
            '确定删除角色：'+$roleInfo.name+'？',
            {
                btn : ['确定', '取消'],
                offset: '20%',
                shadeClose: true,
                btn1:function(obj){
                    $(".layui-layer-btn0").attr('disabled',true);
                    $.post('/index/Auth/delRole',{'id':$roleInfo.id},function(data){
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

})

var menuList = function($id) {
    $.post('/index/Auth/menuList',{'status':1},function(data){
        $tree = $('#tree').treeview({
            data: data,
            levels: 1,
            highlightSelected:false,
            showButton:false,
            showIcon:false,
            showCheckbox: true,
            multiSelect: true,
            onNodeChecked: function(event, node) {   // 选中事件
                var childrenIds = listChildrenIds(node); // 获取所有子节点ID
                for(var i in childrenIds){
                    $('li[data-nodeid="'+childrenIds[i]+'"]').find('.glyphicon-unchecked').click();
                }
            },
            onNodeUnchecked: function(event, node) { //取消选中事件
                var childrenIds = listChildrenIds(node);    //获取所有子节点
                for(var i in childrenIds){
                    $('li[data-nodeid="'+childrenIds[i]+'"]').find('.glyphicon-check').click();
                }
            }
        });
        if(!$id){
            return false;
        }
        $idArr = $id.split(',');
        for(var j in $idArr){   // 默认选中已有的权限
            $('#tree li[id="'+$idArr[j]+'"]').find('.glyphicon-unchecked').click();
        }
    });
}

/**
 * 遍历角色列表
 * */
var roleList = function() {
    var $roleJson = JSON.parse($roleList),
        $select = $('body').find('#user-modal [name=role]');
    $select.empty().append('<option value="">请选择角色</option>');
    for(var i in $roleJson){
        var $option = '<option value="'+$roleJson[i]['id']+'">'+$roleJson[i]['name']+'</option>';
        $select.append($option);
    }
}

/**
 * 添加/编辑菜单的modal
 * */
var $menuModal = '<div class="modal inmodal fade" id="menu-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title"></h1></div><form class="form-horizontal"><div class="modal-body"><div class="form-group"><label class="col-sm-2 control-label">上级菜单</label><div class="col-sm-10"><select name="pid" class="form-control"></select></div></div><div class="form-group"><label class="col-sm-2 control-label">菜单名称</label><div class="col-sm-10"><input type="text" name="name" value="" class="form-control" maxlength="10" required></div></div><div class="form-group"><label class="col-sm-2 control-label">菜单图标</label><div class="col-sm-10"><input type="text" name="icon" value="" class="form-control"><span class="help-block m-b-none">非必填项，参考：<a href="http://fontawesome.dashgame.com/" target="_blank">图标</a></span></div></div><div class="form-group"><label class="col-sm-2 control-label">菜单URL</label><div class="col-sm-10"><input type="text" name="url" value="" class="form-control"><span class="help-block m-b-none">非必填项，/模块/控制器/方法/,例：/index/Index/index</span></div></div><div class="form-group"><label class="col-sm-2 control-label">权重</label><div class="col-sm-10"><input type="text" name="weight" value="" class="form-control"><span class="help-block m-b-none">菜单是按照权重升序进行排序，不填默认0</span></div></div><div class="form-group"><label class="col-sm-2 control-label">菜单状态</label><div class="col-sm-10"><input type="checkbox" name="status" class="js-switch" checked/>&nbsp;&nbsp;状态：<span class="m-b-none switch-tip">启用</span></div></div></div><div class="modal-footer"><button type="reset" class="btn btn-white">重置</button><button type="submit" class="btn btn-primary">确定</button></div></form></div></div></div>';

/**
 * 新增菜单分类
 * */
var $menuAction,    // 添加/编辑菜单
    $menuPid;       // 添加菜单时的父级菜单id
var addMenu = function(obj) {
    $menuAction = 1;
    $('body').find('#menu-modal').remove();
    $('body').append($menuModal);
    var $modal = $('#menu-modal');
    $menuPid = $(obj).parent().attr('id');
    $modal.find('.modal-title').text('添加菜单');
    switchJs();  // 状态
    traversalMenu();  // 遍历菜单
    if($menuPid){
        $modal.find('[name=pid]').val($menuPid).attr('disabled',true);
    }
    $modal.modal('show');
}

/**
 * 编辑菜单分类
 * */
var $menuId;    // 需要编辑的菜单id
var editMenu = function(obj) {
    $menuAction = 2
    $('body').find('#menu-modal').remove();
    $('body').append($menuModal);
    $menuId = $(obj).parent().attr('id');
    var $modal = $('#menu-modal'),
        $menuInfo = menuInfo($menu,$menuId);
    traversalMenu();   // 遍历菜单
    $modal.find('.modal-title').text('编辑菜单');
    $modal.find('[name=pid]').val($menuInfo['pid']).attr('disabled',true);
    $modal.find('[name=name]').val($menuInfo['name']);
    $modal.find('[name=icon]').val($menuInfo['icon']);
    $modal.find('[name=url]').val($menuInfo['url']);
    $modal.find('[name=weight]').val($menuInfo['weight']);
    $modal.find('[name=status]').val($menuInfo['status']);
    if($menuInfo['m_status'] == 2){
        $modal.find('[name=status]').removeAttr('checked');
        $modal.find('.switch-tip').text('停用');
    }
    switchJs();         // 状态
    $modal.modal('show');
}

/**
 * 菜单遍历
 * */
var traversalMenu = function() {
    var $select = $('body').find('#menu-modal select');
    $select.empty().append('<option value="0">顶级菜单</option>');
    for(var i in $menu){
        var $optionF = '<option value="'+$menu[i]['id']+'">'+$menu[i]['name']+'</option>';
        $select.append($optionF);
        // 一级菜单下面没有子类
        if($menu[i]['level'] == 1 && $menu[i]['nodes']){
            var $nodes = $menu[i]['nodes'];
            for(var j in $nodes){
                var $optionS = '<option value="'+$nodes[j]['id']+'">∟'+$nodes[j]['name']+'</option>';
                $select.append($optionS);
            }
        }
    }
}

/**
 * 添加/编辑管理员modal
 * */
var $userModal = '<div class="modal inmodal fade" id="user-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title"></h1></div><form class="form-horizontal"><div class="modal-body"><div class="form-group"><label class="col-sm-2 control-label">登录账号</label><div class="col-sm-10"><input type="text" name="name" value="" class="form-control" maxlength="10" required></div></div><div class="form-group"><label class="col-sm-2 control-label">登录密码</label><div class="col-sm-10"><input type="password" name="password" value="" class="form-control" autocomplete="new-password" required placeholder="密码由6-18位数字、字母组成"></div></div><div class="form-group"><label class="col-sm-2 control-label">选择角色</label><div class="col-sm-10"><select name="role" class="form-control"></select></div></div><div class="form-group"><label class="col-sm-2 control-label">手机号</label><div class="col-sm-10"><input type="text" name="mobile" value="" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">邮箱</label><div class="col-sm-10"><input type="text" name="email" value="" class="form-control"></div></div><div class="form-group"><label class="col-sm-2 control-label">备注</label><div class="col-sm-10"><textarea name="remarks" cols="30" rows="3" class="form-control"></textarea></div></div><div class="form-group"><label class="col-sm-2 control-label">菜单状态</label><div class="col-sm-10"><input type="checkbox" name="status" class="js-switch" checked/>&nbsp;&nbsp;状态：<span class="m-b-none switch-tip">启用</span></div></div></div><div class="modal-footer"><button type="reset" class="btn btn-white">重置</button><button type="submit" class="btn btn-primary">确定</button></div></form></div></div></div>';

/**
 * 添加角色modal
 * */
var $roleModal = '<div class="modal inmodal fade" id="role-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title"></h1></div><form class="form-horizontal"><div class="form-group"><label class="col-sm-2 control-label">角色名称</label><div class="col-sm-10"><input type="text" name="name" value="" maxlength="10" class="form-control" required></div></div><div class="form-group"><label class="col-sm-2 control-label">角色说明</label><div class="col-sm-10"><textarea name="remarks" cols="30" rows="5" class="form-control" maxlength="100"></textarea></div></div><div class="form-group"><label class="col-sm-2 control-label">启用/停用</label><div class="col-sm-10"><input type="checkbox" name="status" checked class="js-switch"/>&nbsp;&nbsp;状态：<span class="m-b-none switch-tip">启用</span></div></div><div class="form-group"><label class="col-sm-2 control-label">分配菜单</label><div class="col-sm-10"><span class="help-block m-b-none">* 请慎重勾选下面的菜单列表，设置用户权限</span></div></div><div class="form-group"><div class="col-sm-12"><div id="tree" class="test"></div></div></div><div class="modal-footer"><button type="reset" class="btn btn-white">重置</button><button type="submit" class="btn btn-primary">确定</button></div></form></div></div></div>';
