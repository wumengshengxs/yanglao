
function onSublimt(){
    var forms = $('#forms').serializeArray();
    $.post('/index/Callcenter/editCallCenter',forms,function(data){
        if (data.code == 1){
            layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                location.reload();
            })
        }else{
            layer.msg(data.msg, {icon: 5, time: 2000});
        }

    },'json')
}


function onLimit(){
    var forms = $('#forms2').serializeArray();
    $.post('/index/Callcenter/editCallLimit',forms,function(data){
        if (data.code == 1){
            layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                location.reload();
            })
        }else{
            layer.msg(data.msg, {icon: 5, time: 2000});
        }

    },'json')
}


function editCall(id){
    $.get('/index/Callcenter/editCallCenter',{id:id},function(data) {
        $("#nickname").val(data.nickname);
        $("#mobile").val(data.mobile);
        $("#email").val(data.email);
        $("#ids").val(data.id);
        $("#sid").val(data.sid);
        $("#token").val(data.token);
    })
}

function editLimit(id){
    $.get('/index/Callcenter/editCallCenter',{id:id},function(data) {
        $("#limit_type").val(data.limit_type);
        $("#day_limit").val(data.day_limit);
        $("#week_limit").val(data.week_limit);
        $("#month_limit").val(data.month_limit);
        $("#cid").val(data.id);
        $("#lsid").val(data.sid);
        $("#ltoken").val(data.token);
    })
}


