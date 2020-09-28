function onSublimt(){
    var forms = $('#forms').serializeArray();
    $.post('/index/Staff/addGroup',forms,function(data){
        if (data.code != 0){
            layer.msg(data.msg, {icon: 5, time: 2000});
        }else{
            layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                location.reload();
            })
        }

    },'json')
}

function delGroup(id){
    layer.confirm(
        '确认要删除技能分组？',
        {
            btn : ['确定', '取消'],
            btn1:function(){
                $.post('/index/Staff/delGroup',{id:id},function(data){
                    if(data.code != 0){
                        layer.msg(data.msg,{icon:2,time:2000},function(){
                            $(".layui-layer-btn0").attr('disabled',false);
                        })
                    }else{
                        layer.msg(data.msg,{icon:1,time:2000},function(){
                            location.reload();
                        })
                    }
                })
            },
        })
}