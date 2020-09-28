//维修记录提交
function SubMit(){
    var imei = $("input[name='imei']").val();
    if (!imei) {
        layer.msg('请输入设备唯一标示号码');
        return false;
    }
    var content = $("textarea[name='content']").val();
    if (!content) {
        layer.msg('请输入设备故障原因');
        return false;
    }
    $.post('/index/Device/maintain',$('#sleep_form').serializeArray(),function(res){
        if (res.code==0) {
            //刷新当前页
            layer.msg(res.msg,{icon:1,time:1000},function(){
                location.reload();
            });
            return false;
        }
        layer.msg(res.msg,{icon:5,time:1000});
    });
}

//模态框点击触发
$('#save').on('shown.bs.modal',function(event){
    var btn_ch = $(event.relatedTarget);
    var id = btn_ch.data("id");
    var imei =  btn_ch.data("imei");
    var content =  btn_ch.data("content");
    $("input[name='hid']").attr('value',id);
    $(".imei").attr('value',imei);
    $(".content").val(content);
});

//修改维修记录
function SubMitSave(){
    var imei = $(".imei").val();
    if (!imei) {
        layer.msg('请输入设备唯一标示号码');
        return false;
    }
    var content = $(".content").val();
    if (!content) {
        layer.msg('请输入设备故障原因');
        return false;
    }
    var id = $("input[name='hid']").val();
    $.post('/index/device/maintainsave',$('#save_form').serializeArray(),function(res){
        if (res.code==0) {
            //刷新当前页
            layer.msg(res.msg,{icon:1,time:1000},function(){
                location.reload();
            });
            return false;
        }
        layer.msg(res.msg,{icon:5,time:1000});
    });
}

//删除记录
function del(id){
    layer.confirm('确认删除该维修记录?',{
        btn : ['确定', '取消'],
        btn1:function(obj){
            $(".layui-layer-btn0").attr('disabled',true);
            
            $.post("/index/Device/maintaindel",{'id':id},function(res){
                console.log(res);
                if (res.code==0) {
                    //关闭确框
                    $('.layui-layer-btn1').click();
                    layer.msg(res.msg,{icon:1,time:1000},function(){
                        location.reload();
                    });
                    return false;
                }
                layer.msg(res.msg,{icon:5,time:2000},function(){
                    $(".layui-layer-btn0").attr('disabled',false);
                })
            });
        }
    })
}
    
//选择通道导出
$('.down_device').change(function(){
    var id = $('.down_device option:selected').val();
    if (id) {
        var name = $('.down_device option:selected').text();
        layer.confirm('请确认'+name+'维修记录是否存在?',{
            btn : ['打印', '取消'],
            btn1:function(obj){
                $(".layui-layer-btn0").attr('disabled',true);
                layer.msg('2秒后开始下载',{time:2000},function(){
                    $('.layui-layer-btn1').click();
                    location.href="/index/Device/down?state=1&id="+id;
                });
            }
        })
    }
});

/**
* 时间插件
* */
layui.use('laydate', function(){
    var laydate = layui.laydate;
    //执行一个laydate实例
    lay('.times').each(function(){
        laydate.render({
            elem: this,
            trigger: 'click',
            type: 'datetime',
        });
    });
});

//高级搜索
function search_submit(){
    console.log($('.query').serializeArray());
    var s = $("input[name='start_create']").val();
    var e = $("input[name='end_create']").val();
    if (s && e) {
        if (e<s) {
            layer.msg('结束时间不能小于开始时间');
            return false;
        }
    }
    var query = $('.query').serializeArray();
    location.href='maintain?'+query;
}

/**
 * 清除搜索条件
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
    if($.inArray($searchInfo['name'],['create']) != -1){
        $form.find('[name$="'+$searchInfo['name']+'"]').val('');
    } else {
        $form.find("[name='"+$searchInfo['name']+"']").val('');
    }
    $form.submit();
})

/*
*选中数据
*/
function checkVal(flag){
    var ids = new Array();
    $(".che_val").each(function(e){
        if ($(this).is(":checked")) {
            ids.push($(this).val());
        }
    });
    if(ids.length==0){
        layer.msg('貌似没有选择需要处理的数据', {icon: 3, time: 3000});
        return false;
    }
    return ids;
}

/*
*全选/反选
*/
$('#checkAll').click(function(){
    if(this.checked) {
        $("input[class='che_val']").prop("checked", true);
    } else {
        $("input[class='che_val']").prop("checked", false);
    }
});
/*
*维修信息办结
*/
function checkmessage(){
    //选中数据
    var che_val = checkVal(true);
    console.log(che_val);
    if(che_val){
        $("input[name='c_ids']").attr('value',che_val);
        //请求后赋值
        $.get('/index/Device/check_maintaininfo',{'ids':che_val},function(res){
            var html = '';
            $.each(res,function(i,t){
                html+='<tr>'
                    +'<td>'
                        +'<div>'
                            +'<strong>'+t.name+'</strong>'
                        +'</div>'
                    +'</td>'
                    +'<td>'+t.imei+'</td>'
                    +'<td>'+t.cname+'</td>'
                    +'<td>'+t.uname+'</td>'
                +'</tr>'; 
            });
            console.log(html);
            $('.a').html(html);
        });
        //弹出模态框
        $('#check_dispose').modal('show');
    }
}

/*
*维修记录办结
*/
function SubMitMationSave(){
    var ids = $("input[name='c_ids']").val();
    var conclude = $("textarea[name='conclude']").val();
    if (!conclude) {
        layer.msg('办结原因不能为空',{icon:4,time:2000});
        return false;
    }
    $.post('/index/Device/check_maintaininfo',{'ids':ids,'conclude':conclude},function(res){
        if (res.code==0) {
            layer.msg(res.msg,{icon:1,time:1000},function(){
                location.reload();
            });
            return false;
        }
        layer.msg(res.msg,{icon:5,time:2000});
    });
}

/*
*上传维修记录表格
*/
function Upload(){
    layer.confirm('导入前是否以确认表格中的数据',{
        btn : ['是的', '我在看看'],
        btn1:function(obj){
            $(".layui-layer-btn0").attr('disabled',true);
            var w_pid = $("select[name='w_pid']").val();
            if (!w_pid) {
                layer.msg('请选择设备厂商');
                return false;
            }
    
            var device_excel = $("input[name='device_excel']").val();
            if (!device_excel) {
                layer.msg('请先上传文件');
                return false;
            }
            $.post('/index/Device/mationupload_excel',{'w_pid':w_pid,'device_excel':device_excel,'state':1},function(res){
                // 上传成功
                if(res.code == 0){
                    //关闭确框
                    $('.layui-layer-btn1').click();
                    layer.msg(res.msg,{icon:1,time:1000},function(){
                        location.reload();
                    });
                    return false;
                }
                layer.msg(res.msg,{icon:5,time:4000});
            });     
        }
    })
}