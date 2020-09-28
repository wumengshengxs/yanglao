//维修记录提交
    function SubMit(){
        var imei = $("input[name='imei']").val();
        if (!imei) {
            layer.msg('请输入设备唯一标示号码');
            return false;
        }
        var comment = $("textarea[name='comment']").val();
        if (!comment) {
            layer.msg('请输入备注原因');
            return false;
        }

        $.post('/index/Devicewithdraw/add',$('#sleep_form').serializeArray(),function(res){
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
        var date =  btn_ch.data("date");
        var s_pid = btn_ch.data("s_pid");
        console.log(s_pid);
        $("input[name='hid']").attr('value',id);
        $(".imei").attr('value',imei);
        $("#test18").attr('value',date);
        $(".comment").val(content);
        $(".ceck").val(s_pid);
    });

    //修改维修记录
    function SubMitSave(){
        var imei = $(".imei").val();
        if (!imei) {
            layer.msg('请输入设备唯一标示号码');
            return false;
        }
        var comment = $(".comment").val();
        if (!comment) {
            layer.msg('请输入设备退换原因');
            return false;
        }
        var id = $("input[name='hid']").val();
        $.post('/index/Devicewithdraw/save',$('#save_form').serializeArray(),function(res){
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
        layer.confirm('确认删除该退换记录?',{
            btn : ['确定', '取消'],
            btn1:function(obj){
                $(".layui-layer-btn0").attr('disabled',true);
                
                $.post("/index/Devicewithdraw/del",{'id':id},function(res){
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
                        location.href="/index/Device/down?state=2&id="+id;
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
        location.href='index?'+query;
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