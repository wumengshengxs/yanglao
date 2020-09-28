$(function(){
    //这里是根据不同的设备通道走对应websocket
    var obj = $.parseJSON($deviceDetails);
    check_imei(obj);
    var $gpsHtml;
    //取消编辑后的按钮
    var $editGpsBut = '<button type="button" class="btn btn-white e-device-times">编辑</button>';
    //设置定位上传时间间隔表单
    var $gpsNode = '<input type="hidden" name="state" value="2"/>'
                +'<div class="form-group">'
                    +'<label class="col-sm-2 control-label">GPS时间间隔</label>'
                    +'<div class="col-sm-4">'
                        +'<div class="row">'
                            +'<div class="d-sos">'
                                +'<div class="col-md-5 m-b">'
                                    +'<input type="number" name="times" placeholder="请设置gps上传定位时间间隔" class="form-control"/>'
                                +'</div>'
                                +'<div class="col-md-5 m-b">'
                                    +'<span class="help-block m-b-none">分钟</span>'
                                +'</div>'
                            +'</div>'
                        +'</div>'
                    +'</div>'
                +'</div>';
    //间隔表单按钮
    var $saveGpsBtn = '<button type="submit" class="btn btn-primary s-device-gps">保存</button>&nbsp;'
                    +'<button type="button" class="btn btn-white c-device-gps">取消</button>';
    //点击触发添加节点
    $('body').delegate('.e-device-times','click',function(){
        var $parent = $(this).parent(),$parentNext = $parent.next('div');
        $gpsHtml = $parentNext.html();
        $(this).after($saveGpsBtn).remove();
        $parentNext.find('.form-group:not(:first-child)').remove();
        //添加节点
        $parentNext.append($gpsNode);
    })

    /**
    *取消gps编辑
    */
    $('body').delegate('.c-device-gps','click',function(){
        var $parent = $(this).parent(),
            $parentNext = $parent.next('div');
        console.log($parent);
        $parent.find('button').remove();
        $parent.append($editGpsBut);
        $parentNext.empty().append($gpsHtml);
    });

    //点击保存后触发
    $('body').delegate('.timing-info-gps .s-device-gps','click',function(){
        var gps_form = $(this).parents('form');
        var $subBtn = $(gps_form).find('[type=submit]'),
            $fData = $(gps_form).serializeArray();
            $deviceDetailsJson = JSON.parse($deviceDetails);
        $fData.push({'name':'did','value':$deviceDetailsJson.id});
        $fData.push({'name':'imei','value':$deviceDetailsJson.imei});
        $subBtn.attr('disabled',true);
        $.post('/index/Device/submitDeviceGpsOrHeart',$fData,function(s_device_gps_data){
            if(s_device_gps_data.code == 0){
                layer.msg(s_device_gps_data.msg,{icon:1,time:2000},function(){
                    location.reload();
                })
                return false;
            }
            layer.msg(s_device_gps_data.msg,{icon:5,time:2000},function(){
                $subBtn.attr('disabled',false);
            })
        })
    });
    // -------------------------------------------------------------------------------------------

    //取消设置设备工作时间段按钮
    var editGpsButTiming = '<button type="button" class="btn btn-white e-device-times-timing">编辑</button>';

    //取消编辑后
    var gpstimingHtml;

    //设备工作时间表单按钮
    var $savetimingBtn = '<button type="submit" class="btn btn-primary s-device-timing">保存</button>&nbsp;'
                    +'<button type="button" class="btn btn-white c-device-timing">取消</button>';
    //设备工作时间表单
    var $timingNode = '<input type="hidden" name="state" value="2"/>'
                        +'<div class="form-group">'
                            +'<label class="col-sm-2 control-label">工作时间</label>'
                            +'<div class="col-sm-4">'
                                +'<div class="row">'
                                    +'<div class="d-sos">'
                                        +'<div class="col-md-5 m-b">'
                                            +'<input type="text" name="start_time" placeholder="工作开始时间" class="form-control gps-timing-create" />'
                                        +'</div>'
                                        +'<div class="col-md-5 m-b">'
                                            +'<input type="text" name="end_time" placeholder="工作结束时间" class="form-control gps-timing-create" />'
                                        +'</div>'
                                    +'</div>'
                                +'</div>'
                            +'</div>'
                        +'</div>';
    //设备定位工作时间段
    $('body').delegate('.e-device-times-timing','click',function(){
        var $parent = $(this).parent(),$parentNext = $parent.next('div');
        gpstimingHtml = $parentNext.html();
        $(this).after($savetimingBtn).remove();
        $parentNext.find('.form-group:not(:first-child)').remove();
        //添加节点
        $parentNext.append($timingNode);
        /**
        * 时间插件
        * */
        layui.use('laydate', function(){
            var laydate = layui.laydate;
            //执行一个laydate实例
            lay('.gps-timing-create').each(function(){
                laydate.render({
                    elem: this,
                    trigger: 'click',
                    type: 'time',
                });
            });
        });
    })


    /**
    *保存定位工作时间段
    **/
    $('body').delegate('.interval-info .s-device-timing','click',function(){
        var start_time = $("input[name='start_time']").val();
        var end_time = $("input[name='end_time']").val();
        if (!start_time || !end_time) {
            layer.msg('请将表单填写完整');
            return false;
        }
        var gps_form = $(this).parents('form');
        var $subBtn = $(gps_form).find('[type=submit]'),
            $fData = $(gps_form).serializeArray();

            $deviceDetailsJson = JSON.parse($deviceDetails);
        $fData.push({'name':'did','value':$deviceDetailsJson.id});
        $fData.push({'name':'imei','value':$deviceDetailsJson.imei});
        $subBtn.attr('disabled',true);
        $.post('/index/Device/submitDeviceGpstiming',$fData,function(data){
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
    });

    /**
    *取消gps工作时间段编辑
    */
    $('body').delegate('.c-device-timing','click',function(){
        var $parent = $(this).parent(),
            $parentNext = $parent.next('div');
        $parent.find('button').remove();
        $parent.append(editGpsButTiming);
        $parentNext.empty().append(gpstimingHtml);
    });
})
//这里是页面加载完毕后连接websocket检测设备是否在线
function check_imei(obj){
    console.log(obj.pid);
    if (obj.pid!==1 && obj.pid!==2) {
        console.log('设备不用发送指令');
        return false;
    }
    switch(obj.pid){
        case 2:
            //科强手环
            ws = new WebSocket("ws://101.89.115.24:7273");
            ws.onopen = function() {
                var num = Math.floor(Math.random()*100+1);
                var uid = "{:session('S_USER_INFO.id')}";
                ws.send('@B#@|'+num+'.'+uid+'|WEC|'+obj.imei+'|@E#@');
            };
        break;
        case 1:
            //乐源腕表
            ws = new WebSocket("ws://101.89.115.24:7275");
            ws.onopen = function() {
                var num = Math.floor(Math.random()*100+1);
                var uid = "{:session('S_USER_INFO.id')}";
                ws.send('IWCHEK,'+obj.imei);
            };
            // return false;
        break;
    }
    
    //服务器推送消息格式为json
    ws.onmessage = function(e) {
        console.log(e.data);
        $.post('/index/Device/savedevice',{'check':e.data},function(res){
            console.log(res.d_status);
            if (res.d_status==2) {
                var d_status = '不在线';
            }
            if (res.d_status==1){
                var d_status = '在线';
            }
            if (res.d_status!==1 && res.d_status!==2) {
                var d_status = '检测中...';
            }
            $('.d_status').html(d_status);
        });
    };
    ws.onclose = function(e) {
        console.log(e.reason);
    }
}