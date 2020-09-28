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
//添加管理人员
function onSublimt(){
    var forms = $('#forms').serializeArray();
    $.post('/server/Staff/addStaff',forms,function(data){
        if (data.code != 0){
            layer.msg(data.msg, {icon: 5, time: 2000});
        }else{
            layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                location.reload();
            })
        }

    },'json')
}


//获取编辑人员信息
function editStaff(id){
    $.get('/server/Staff/editStaff',{id:id},function(data){
        $("#name").val(data.name);
        $("#mobile").val(data.mobile);
        $("#provider").html(data.provider);
        if (data.state == 0){
            $("#status0").attr("checked",'checked');
        }else{
            $("#status1").attr("checked",'checked');
        }

        $("#ids").val(data.id);
    })
}

//编辑人员
function saveSublimt(){
    var forms = $('#forms1').serializeArray();
    $.post('/server/Staff/editStaff',forms,function(data){
        if (data.code == 0){
            layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                location.reload();
            })
        }else{
            layer.msg(data.msg, {icon: 5, time: 2000});
        }

    },'json')
}