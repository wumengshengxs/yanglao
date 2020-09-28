function onSublimt(){
    var forms = $('#forms').serializeArray();
    $.post('/index/Staff/addStaff',forms,function(data){
        if (data.code != 0){
            layer.msg(data.msg, {icon: 5, time: 2000});
        }else{
            layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                location.reload();
            })
        }

    },'json')
}

function delGroup(number,gid,phone){
    layer.confirm(
        '确认要删除话务员？',
        {
            btn : ['确定', '取消'],
            btn1:function(){
                $.post('/index/Staff/delStaffUser',{number:number,gid:gid,phone:phone},function(data){
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

function showUser(number){
    layer.open({
        type:2,
        title:'查看话务员',
        shadeClose:true,
        shade:0.8,
        area:['100%','100%'],
        content:"staffInfo?number="+number,
    });
}

function editUser(number){
    $.get('/index/Staff/editStaff',{number:number},function(data){
        $("#display_name").val(data.display_name);
        $("#phone").val(data.phone);
        $("#work_number").val(data.work_number);
        $("#ids").val(data.number);
    })
}

function saveSublimt(){
    var forms = $('#forms2').serializeArray();
    $.post('/index/Staff/editStaff',forms,function(data){
        if (data.code == 0){
            layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                location.reload();
            })
        }else{
            layer.msg(data.msg, {icon: 5, time: 2000});
        }

    },'json')
}
