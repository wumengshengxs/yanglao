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

$(function() {
    $.post('/index/Articles/group',{},function(data){
        $("#tree").treeview({
            data: data.group,
            highlightSelected:false,
        });
        $("#list").html(data.list);
    },'json');
});


function onSublimt(){
    var forms = $('#forms').serializeArray();
    $.post('/index/Articles/addGroup',forms,function(data){
        if (data.code == 0){
            layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                location.reload();
            })
        }else{
            layer.msg(data.msg, {icon: 5, time: 2000});
        }

    },'json')
}

function delArticle(id){
    layer.confirm('确认删除吗？',{
        btn : ['确定', '取消'],
        btn1:function(obj){
            $(".layui-layer-btn0").attr('disabled',true);
            $.post('/index/Articles/delArticle',{id:id},function(data){
                if (data.code == 0){
                    layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                        location.reload();
                    })
                }else{
                    layer.msg(data.msg, {icon: 5, time: 2000});
                    $(".layui-layer-btn0").attr('disabled',false);
                }
            },'json');
        }
    })
   
}

/**
 * 新增菜单分类
 * */

var addMenu = function(obj) {
    $menuPid = $(obj).parent().attr('id');
    $.post('/index/Articles/getTopGroup',{id:$menuPid},function(data){
        if (data.list.code == 2){
            layer.msg(data.list.msg, {icon: 5, time: 2000});
            return
        }
        $('body').find('#menu-modal').remove();
        $('body').append($menuModal);
        var $modal = $('#menu-modal');
        $modal.find('.modal-title').text('添加文章类型');
        $modal.modal('show');
        $("#lists").html(data.list);
    })

};

//搜索
function search_query(){
    if ($("#start").val() > $("#end").val()){
        layer.msg('结束时间不得小于起始时间',{icon:2,time:2000});
        return
    }
    var query = $('#signupForm').serialize();
    location.href="/index/Articles/index?"+query;
}

/**
 * 修改菜单分类
 * */

var editMenu = function(obj) {
    $menuPid = $(obj).parent().attr('id');
    $.post('/index/Articles/getTopGroup',{id:$menuPid},function(data){
        $('body').find('#menu-modal1').remove();
        $('body').append($editModal);
        var $modal = $('#menu-modal1');
        $("#name").val(data.group.name);
        $("#weight").val(data.group.weight);
        $("#ids").val(data.group.id);
        $modal.find('.modal-title').text('修改文章类型');
        $modal.modal('show');

    })

};

var addSublimt = function(){
    var forms = $('#from1').serializeArray();
    $.post('/index/Articles/addGroup',forms,function(data){
        if (data.code == 0){
            layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                location.reload();
            })
        }else{
            layer.msg(data.msg, {icon: 5, time: 2000});
        }

    },'json')
};

function delMenu(is){
    layer.confirm(
        '确认要删除该文章类型？',
        {
            btn : ['确定', '取消'],
            btn1:function(){
                var mid = $(is).parent().attr('id');
                $(".layui-layer-btn0").attr('disabled',true);
                $.post('/index/Articles/delGroup',{'id':mid},function(data){
                    if(data.code == 0){
                        layer.msg(data.msg,{icon:1,time:2000},function(){
                            location.reload();
                        })
                    }else{
                        layer.msg(data.msg,{icon:2,time:2000},function(){
                            $(".layui-layer-btn0").attr('disabled',false);
                        })
                    }

                })
            },
        })
}

function onArticle(){
    var forms = $('#signupForm').serializeArray();
    $.post('/index/Articles/addArticle',forms,function(data){
        if(data.code == 0){
            layer.msg(data.msg,{icon:1,time:2000},function(){
                location.href="/index/Articles/index";
            })
        }else{
            layer.msg(data.msg,{icon:2,time:2000},function(){
                $(".layui-layer-btn0").attr('disabled',false);
            })
        }

    })
}




/**
 * 添加/编辑菜单的modal
 * */
var $menuModal = `<div class="modal inmodal fade" id="menu-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h1 class="modal-title"></h1>
                </div>
                <form class="form-horizontal" id="from1">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">选择上级栏目</label>
                            <div class="col-sm-9">
                                <select name="pid" class="form-control" id="lists"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">类型名称</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" value=""  class="form-control" maxlength="15" placeholder="类型名称">
                            </div>
                        </div>
                       
                       
                        <div class="form-group">
                            <label class="col-sm-3 control-label">权重</label>
                            <div class="col-sm-9">
                                <input type="number" name="weight"  value="0" class="form-control">
                            </div>
                        </div>
                       
                    </div>
                    <input type="hidden" name="id"  value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                        <a  class="btn btn-primary" onclick="addSublimt()">确定</a>
                    </div>
                </form>
            </div>
        </div>
    </div>`;

var $editModal = `<div class="modal inmodal fade" id="menu-modal1" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h1 class="modal-title"></h1>
                </div>
                <form class="form-horizontal" id="from1">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">类型名称</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" value="" id="name" class="form-control" maxlength="15" placeholder="类型名称">
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <label class="col-sm-3 control-label">权重</label>
                            <div class="col-sm-9">
                                <input type="number" name="weight" id="weight" value="0" class="form-control">
                            </div>
                        </div>
                       
                    </div>
                    <input type="hidden" name="id" id="ids" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                        <a  class="btn btn-primary" onclick="addSublimt()">确定</a>
                    </div>
                </form>
            </div>
        </div>
    </div>`;

