/**
 * 积分管理js
 * */
document.write("<script language='javascript' src='/public/static/js/transfer-frame.js'></script>");
$(function(){
    // 批量发放积分
    $('.b-grant-integral').on('click',function(){
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
     * 发放积分modal
     * */
    var $transferFTwoModal = '<div class="modal inmodal fade transfer-frame-t" tabindex="-1" role="dialog"  aria-hidden="true" data-backdrop="static"><div class="modal-dialog" style="top: 25%;box-shadow:5px 2px 6px #dddddd"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title">批量发放积分</h1></div><form class="form-horizontal"><div class="modal-body"><div class="form-group"><label class="col-sm-2 control-label">* 积分</label><div class="col-sm-10"><input type="text" name="integral" class="form-control" placeholder="大于0的整数"></div></div><div class="form-group"><label class="col-sm-2 control-label">* 积分内容</label><div class="col-sm-10"><textarea name="remarks" cols="30" rows="3" required class="form-control"></textarea></div></div><div class="modal-footer"><button type="button" class="btn btn-white" data-dismiss="modal">关闭</button><button type="submit" class="btn btn-primary">确定</button></div></form></div></div></div>';

    /**
     * 弹出积分发放modal
     * */
    $('body').delegate('.transfer-frame .t-f-bottom button:first-child','click',function(){
        // 判断是否选中服务对象
        if(!$selectedClient.length){
            errorTips('client','请选择服务对象');
            return false;
        }
        // 弹出modal
        $('body').append($transferFTwoModal);
        $('.transfer-frame-t').modal('show');
    })

    /**
     * validate自定义验证正整数
     * */
    $.validator.addMethod('positiveinteger', function(value, element, param) {
        var $grep = /^[1-9]*[1-9][0-9]*$/;
        if($grep.test(value)){
            return true;
        }
        return false;
    });

    /**
     * 提交发放积分信息
     * */
    $('body').delegate('.transfer-frame-t','show.bs.modal',function(){
        $(this).find('form').validate({
            rules: {
                'integral': {
                    positiveinteger: true
                },
                'remarks': {
                    required: !0
                }
            },
            messages: {
                'integral': {
                    positiveinteger: '请输入大于0的整数'
                },
                'remarks': {
                    required: '请填写积分内容'
                }
            },
            submitHandler: function(form)
            {
                var $subBtn = $(form).find('[type=submit]'),
                    $fData = $(form).serializeArray();
                $fData.push({'name':'client','value':JSON.stringify($selectedClient)});
                $subBtn.attr('disabled',true);
                $.post('/index/Integration/submitIntegral',$fData,function(data){
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
            }
        })
    })

    /**
     * modal关闭是清除数据
     * */
    $('body').delegate('.transfer-frame','hide.bs.modal',function(){
        $selectedClient = [],               // 选择的对象列表
        $clientList,                        // 搜索出的对象列表
        $group,                             // 分组列表
        $tag;                               // 标签列表
    })
})

/**
 * 上传文件
 * */
layui.use('upload', function() {
    var $ = layui.jquery
        , upload = layui.upload;

    //允许上传的文件后缀
    upload.render({
        elem: '#excel'
        ,url: '/index/Integration/getExcel'
        ,accept: 'file' //普通文件
        ,exts: 'xls|xlsx|xps' //只允许上传
        ,before:function () {
            layer.load();
        }
        ,done: function(res){
            if (res.code == 0) {
                layer.msg(res.msg,{'icon':1,'time':1000},function(){  // 关闭弹窗，并刷新页面
                    location.reload();
                })
            }else{
                layer.msg(res.msg, {icon: 5, time: 2000});
            }
        }
    });
})


/**
 * 下载积分核销模板
 * */
var destoryTemplet = function() {
    window.location.href = "/index/Integration/destoryTemplet";
}

/**
 * 批量核销积分
 * */
var $destoryModal = '<div class="modal inmodal fade" id="destory-modal" tabindex="-1" role="dialog"  aria-hidden="true" data-backdrop="static"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h1 class="modal-title">积分批量核销</h1></div><form class="form-horizontal"><div class="modal-body"><div class="form-group"><div class="col-sm-12"><div class="input-group"><input type="text" name="integra_excel" readonly class="form-control"><span class="input-group-btn" style="vertical-align: top;"><button type="button" class="btn btn-primary" id="integra_excel"><i class="fa fa-cloud-upload"></i> &nbsp;上传文件</button><input class="layui-upload-file" type="file" accept="undefined" name="file"></span></div></div></div><div class="modal-footer"><button type="button" class="btn btn-white" data-dismiss="modal">取消</button><button type="submit" class="btn btn-primary">提交</button></div></div></form></div></div></div>';

var destoryIntegra = function() {
    $('body').find('#destory-modal').remove();
    $('body').append($destoryModal);
    $modal = $('#destory-modal');
    iotUploadExcel({'elem':'integra_excel','url':'/index/Integration/uploadExcel'});
    $modal.modal('show');
}

/**
 * 提交积分核销
 * */
$('body').delegate('#destory-modal','show.bs.modal',function(){
    $(this).find('form').validate({
        rules: {
            integra_excel: {
                required: !0
            }
        },
        messages: {
            integra_excel: {
                required: '请上传excel文件'
            }
        },
        submitHandler: function(form) {
            var $subBtn = $(form).find('[type=submit]'),
                $fData = $(form).serializeArray();
            $subBtn.attr('disabled',true);
            $.post('/index/Integration/submitDestoryIntegra',$fData,function(data){
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
        }
    })
})