layui.use('laydate', function(){
    var laydate = layui.laydate;

    //执行一个laydate实例
    lay('.times').each(function(){
        laydate.render({
            elem: this
            ,trigger: 'click'
        });
    });
});





//搜索
function search_query(){
    var query = $('#signupForm').serialize();
    location.href="/index/Works/work?"+query;
}

//质检搜索
function search_quality(){
    var query = $('#signupForm').serialize();
    location.href="/index/Qualitys/quality?"+query;
}

//驳回搜索
function search_back(){
    var query = $('#signupForm').serialize();
    location.href="/index/Qualitys/backQuality?"+query;
}

//通过搜索
function search_pass(){
    var query = $('#signupForm').serialize();
    location.href="/index/Qualitys/passQuality?"+query;
}

function getExcel(){
    $("#state").val(1);
    var query = $('#signupForm').serialize();
    location.href="/index/Works/work?"+query;

}

function changeWork(id){
    $("#ids").val(id)
}

function saveSublimt(){
    var forms = $('#forms2').serializeArray();
    $.post('/index/Works/changeWork',forms,function(data){
        if (data.code == 0){
            layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                location.reload();
            })
        }else{
            layer.msg(data.msg, {icon: 5, time: 2000});
        }

    },'json')
}

function delWork(id){
    layer.confirm(
        '确认要关闭此工单？',
        {
            btn : ['确定', '取消'],
            btn1:function(){
                $.post('/index/Works/shutWork',{id:id},function(data){
                    if (data.code == 0){
                        layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                            location.reload();
                        })
                    }else{
                        layer.msg(data.msg, {icon: 5, time: 2000});
                    }

                },'json')
            },
        })
}

function onSublimt(){
    var forms = $('#commentForm').serializeArray();
    $.post('/index/Works/addWorkLog',forms,function(data){
        if (data.code == 0){
            layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                location.reload();
            })
        }else{
            layer.msg(data.msg, {icon: 5, time: 2000});
        }

    },'json')
}

function onAccept(id,ste){
    layer.confirm(
        '确认要受理工单？',
        {
            btn : ['确定', '取消'],
            btn1:function(){
                $.post('/index/Works/WorkAccept',{id:id,state:ste},function(data){
                    if (data.code == 0){
                        layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                            location.reload();
                        })
                    }else{
                        layer.msg(data.msg, {icon: 5, time: 2000});
                    }
                },'json')
            },
        })

}

function reopen(id,ste){
    layer.confirm(
        '确认要重新打开工单？',
        {
            btn : ['确定', '取消'],
            btn1:function(){
                $.post('/index/Works/WorkReopen',{id:id,state:ste},function(data){
                    if (data.code == 0){
                        layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                            location.reload();
                        })
                    }else{
                        layer.msg(data.msg, {icon: 5, time: 2000});
                    }

                },'json')
            },
        })
}

function endSublimt()
{
    var forms = $('#forms2').serializeArray();
    $.post('/index/Works/endChangeWork',forms,function(data){
        if (data.code == 0){
            layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                location.reload();
            })
        }else{
            layer.msg(data.msg, {icon: 5, time: 2000});
        }

    },'json')
}



//拨打询问框
function call(phone,id){
    layer.confirm(
        '确定呼出电话到'+phone+'?',
        {
            btn : ['确定', '取消'],
            btn1:function(){
                $.post("/index/Works/CallUser",{'phone':phone,'id':id},function(data){
                    layer.msg(data.msg, {icon: 1, time: 2000});
                },'json');
            },
        })
}

function infoChangeWork(){
    var forms = $('#forms1').serializeArray();
    $.post('/index/Works/changeWork',forms,function(data){
        if (data.code == 0){
            layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                location.reload();
            })
        }else{
            layer.msg(data.msg, {icon: 5, time: 2000});
        }

    },'json')
}

function getAudio(is,id){
    $.post('/index/Works/getAudio',{id:id},function(data){
        $(is).parents('.getAudio').css("display","none");
        $(is).parents().next('.isAudion').css("display","block");
        if(data.code == 0){
            $(is).parents().next('.isAudion').html("<audio controls='controls' src='"+data.data+"'></audio>")
        }else{
            $(is).parents().next('.isAudion').html("<span >本次通话记录无录音</span>")
        }

    },'json')
}

function getNumber(num){
    $("#integral").val(num)
}

function addIntegral() {
    var forms = $('#forms3').serializeArray();
    $.post('/index/Integration/addInt',forms,function(data){
        if (data.code == 0){
            layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                location.reload();
            })
        }else{
            layer.msg(data.msg, {icon: 5, time: 2000});
        }

    },'json')
}


//工单质检
function onQuality()
{
    var forms = $('#forms5').serializeArray();
    $.post('/index/Qualitys/addPassQuality',forms,function(data){
        if (data.code == 0){
            layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                location.href = '/index/Qualitys/passquality';
            })
        }else{
            layer.msg(data.msg, {icon: 5, time: 2000});
        }

    },'json')
}

//工单驳回
function onReject()
{
    var forms = $('#forms4').serializeArray();
    $.post('/index/Qualitys/reject',forms,function(data){
        if (data.code == 0){
            layer.msg(data.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                location.href = '/index/Qualitys/backquality';
            })
        }else{
            layer.msg(data.msg, {icon: 5, time: 2000});
        }

    },'json')
}
