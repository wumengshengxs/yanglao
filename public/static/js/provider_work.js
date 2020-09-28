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

//关闭工单
function delWork(id,title)
{
    layer.confirm(
        '确定关闭工单：'+title+'？',
        {
            btn : ['确定', '取消'],
            offset: '20%',
            shadeClose: true,
            btn1:function(obj){
                $.post('/index/provider/closeWork',{'id':id},function(data){
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

//添加评分
function addGrade()
{
    var forms = $('#forms').serializeArray();
    $.post('/index/provider/addGrade',forms,function(data){
        if (data.code != 0){
            layer.msg(data.msg, {icon: 5, time: 2000});
        }else{
            layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                location.reload();
            })
        }

    },'json')
}

//查看评分
function saveGrade()
{
    var forms = $('#forms1').serializeArray();
    $.post('/index/provider/saveGrade',forms,function(data){
        if (data.code != 0){
            layer.msg(data.msg, {icon: 5, time: 2000});
        }else{
            layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                location.reload();
            })
        }

    },'json')
}

function getGrade(id){
    $.get('/index/provider/saveGrade',{id:id},function(data){
        $("#speed").val(data.speed);
        $("#meter").val(data.meter);
        $("#details").val(data.details);
        $("#term").val(data.term);
        $("#ids").val(data.id);
        $("#total").html(data.total);
    },'json')
}


//添加服务工单
function onSublimt(){
    var forms = $('#forms').serializeArray();
    $.post('/index/provider/addWork',forms,function(data){
        if (data.code != 0){
            layer.msg(data.msg, {icon: 5, time: 2000});
        }else{
            layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                window.location.href = '/index/provider/work';
            })
        }

    },'json')
}

var $planSelectedClient = [];


/**
 * 计划任务选择服务对象
 * */
$('.b-grant-work').on('click',function(){
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
 * modal打开时右侧展示已选中的数据
 * */
$('body').delegate('.transfer-frame','show.bs.modal',function(){
    for(var i in $planSelectedClient){
        var $node = ' <tr><td>'+$planSelectedClient[i]['userName']+'</td><td>'+$planSelectedClient[i]['sex']+'</td><td>'+$planSelectedClient[i]['id_number']+'</td><td>'+$planSelectedClient[i]['mobile']+'</td><td>'+$planSelectedClient[i]['address']+'</td><td><a><i class="fa fa-close"></i></a></td></tr>';
        $('.transfer-frame .t-f-t-right tbody').append($node);
    }
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
 * 穿梭框js
 * */

document.write("<script language='javascript' src='/public/static/js/bootstrap-paginator.js'></script>");
/**
 * 穿梭框modal
 * */
var $transferFrameModal = '<div class="modal fade transfer-frame" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"><div class="t-f-t-left"><div class="m-b">选择服务对象</div><div class="m-l m-r"><div><div class="form-group"><div class="m-b"><div class="input-group"><input type="text" name="name" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-white btn-search">搜索</button></span></div></div></div></div><div class="m-b"><div class="radio radio-inline radio-success"><input type="radio" id="group" name="search" checked><label for="group">按分组</label></div><div class="radio radio-inline radio-success"><input type="radio" id="tag" name="search"><label for="tag">按标签</label></div></div><div class="t-f-middle"><div class="t-f-m-left"></div><div class="t-f-m-right"><div class="t-f-m-right-table"><table class="table table-responsive"><tbody></tbody></table></div></div><div class="t-f-m-bottom"><ul class="pagination"></ul></div></div></div></div><div class="t-f-t-right"><div class="m-b">已选择对象</div><div class="m-l m-r"><table class="table table-responsive"><thead><tr><td>姓名</td><td>性别</td><td>身份证号</td><td>手机</td><td>住址</td><td>操作</td></tr></thead><tbody></tbody></table></div></div></div><div class="col-sm-12 text-center t-f-bottom"><button type="button" class="btn btn-primary" >确定</button>&nbsp;&nbsp;<button type="button" class="btn btn-white" data-dismiss="modal">取消</button></div><div class="clearfix"></div></div></div></div>';

var $selectedClient = [],               // 选择的对象列表
    $clientList,                        // 搜索出的对象列表
    $group,                             // 分组列表
    $tag;                               // 标签
/**
 * 搜索
 * */
$('body').delegate('.transfer-frame .btn-search','click',function(){
    var $name = $(this).parents('.input-group').find('input').val();
    // 分组/标签初始化
    $('.transfer-frame .t-f-m-left p').removeClass('active').removeClass('font-bold');
    $('.transfer-frame .t-f-m-left p:first-child').addClass('active').addClass('font-bold');
    getClientList({'name':$name});
})

/**
 * 分组/标签切换
 * */
$('body').delegate('.transfer-frame input[name=search]','change',function(){
    var $index = $('.transfer-frame input[name=search]').index(this);
    // 分组
    if(!$index){
        var $groupNode = $('.transfer-frame .t-f-m-left');
        $groupNode.empty().append('<p class="active font-bold">全部</p>');
        for(var i in $group){
            var $groupHtml = '<p>'+$group[i]['name']+'</p>';
            $groupNode.append($groupHtml);
        }
    }
    if($index){
        var $tagNode = $('.transfer-frame .t-f-m-left');
        $tagNode.empty().append('<p class="active font-bold">全部</p>');
        for(var i in $tag){
            var $tagHtml = '<p>'+$tag[i]['name']+'</p>';
            $tagNode.append($tagHtml);
        }
    }
    // 获取服务对象
    getClientList();
})

/**
 * 选择不同的分组或者标签获取对应的服务对象
 * */
$('body').delegate('.transfer-frame .t-f-m-left p','click',function(){
    var $pIndex = $('.transfer-frame input[name=search]').index($('.transfer-frame input[name=search]:checked')),
        $index = parseInt($('.transfer-frame .t-f-m-left p').index(this))-1,
        $where = {};
    $('.transfer-frame .t-f-m-left p').removeClass('active').removeClass('font-bold');
    $(this).addClass('active').addClass('font-bold');
    // 分组
    if(!$pIndex){
        // 获取选中的分组id
        for(var i in $group){
            if(i == $index){
                $where['group'] = $group[i]['id'];
            }
        }
    }
    // 标签
    if($pIndex){
        // 获取选中的标签id
        for(var i in $tag){
            if(i == $index){
                $where['tag'] = $tag[i]['id'];
            }
        }
    }
    getClientList($where);
})

/**
 * 左侧单个选中数据
 * */
$('body').delegate(".transfer-frame .t-f-m-right table a:not('.checked')",'click',function(){
    $(this).addClass('checked');
    // 获取对应的数据信息显示在右侧
    var $index = $('.transfer-frame .t-f-m-right table a').index(this),
        $checkedClient;
    for(var i in $clientList){
        if($index == i){
            $checkedClient = $clientList[i];
            break;
        }
    }
    if ($selectedClient.length == 1){
        layer.msg('一次只能选择一位服务对象', {icon: 5, time: 2000});
        return
    }

    console.log($selectedClient.length)

    $selectedClient.push($checkedClient);

    var $node = '<tr><td>'+$checkedClient['userName']+'</td><td>'+$checkedClient['sex']+'</td><td>'+$checkedClient['id_number']+'</td><td>'+$checkedClient['mobile']+'</td><td>'+$checkedClient['address']+'</td><td><a><i class="fa fa-close"></i></a></td></tr>';
    $('.transfer-frame .t-f-t-right tbody').append($node);
})


/**
 * 右侧移除数据
 * */
$('body').delegate('.transfer-frame .t-f-t-right table a','click',function(){
    var $index = $('.transfer-frame .t-f-t-right table a').index(this),
        $removeClient;
    // 获取移除的数据信息
    $removeClient = $selectedClient.splice($index,1);
    // 左侧对应的数据
    for(var i in $clientList){
        if($clientList[i]['id'] == $removeClient[0]['id']){
            $('.transfer-frame .t-f-m-right table a').eq(i).removeClass('checked');
            $('.transfer-frame #checkAll').attr('checked',false);
            break;
        }
    }
    // 删除当前节点
    $(this).parents('tr').remove();
})

/**
 * 获取服务对象列表
 * */
var getClientList = function(data) {
    var $data = data ? data : {};
    $.post('/index/Integration/clientList',$data,function(msg){
        $clientList = msg.data;
        var $clientTable = $('.transfer-frame .t-f-m-right table');
        $clientTable.empty();
        for(var i in msg.data){
            var $checked = '';
            for(var j in $selectedClient){
                if($selectedClient[j]['id'] == msg.data[i]['id']){
                    $checked = 'checked';
                    break;
                }
            }
            var $tr = '<tr><td>'+msg.data[i]['userName']+'</td><td><a class="'+$checked+'"><i class="fa fa-arrow-right"></i></a></td></tr>';
            $clientTable.append($tr);
        }
        // 分页
        var element = $('.pagination');
        var options = {
            bootstrapMajorVersion: 3,
            currentPage: msg.current_page,
            numberOfPages: 3,
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
                getClientList($data);
            }
        }
        element.bootstrapPaginator(options);
        // 全选按钮初始化
        var $checkedLenth = $clientTable.find("a:not('.checked')").length;
        if(!$checkedLenth){
            $('.transfer-frame #checkAll').prop('checked',true);
        } else {
            $('.transfer-frame #checkAll').attr('checked',false);
        }
    })
}

/**
 * 获取分组列表
 * */
var groupList = function() {
    $.post('/index/Integration/groupList',{},function(data){
        $group = data.data;
        var $groupNode = $('.transfer-frame .t-f-m-left');
        $groupNode.empty().append('<p class="active font-bold">全部</p>');
        for(var i in data.data){
            var $groupHtml = '<p>'+data.data[i]['name']+'</p>';
            $groupNode.append($groupHtml);
        }
    })
}

/**
 * 获取标签列表
 * */
var tagList = function() {
    $.post('/index/Integration/tagList',{},function(data){
        $tag = data;
    })
}