/**
 * layui上传图片的js方法
 * */
var iotUpload = function(data){
    layui.use('upload', function(){
        var upload = layui.upload;
        //执行实例
        var uploadInst = upload.render({
            elem: '#'+data.elem, //绑定元素
            url: data.url, //上传接口
            size: 2048, // 最大允许上传文件的大小
            method: 'post',
            done: function(res){
                // 上传成功
                if(res.code == 0){
                    // 多图
                    if(data.multiple){
                        var $div = '<div class="inline m-b m-r multiple-upload">' +
                            '<a javascript:;>X</a>' +
                            '<img src="'+res.url+'">' +
                            '<input type="hidden" name="image[]" value="'+res.url+'">' +
                            '</div>';
                        $('#'+data.elem).parent().before($div);
                        return false;
                    }
                    // 单张图
                    $('#'+data.elem).parent().find('input').remove();
                    $('#'+data.elem).parent().find('img').attr('src',res.url);
                    var _input = '<input type="hidden" name="'+data.elem+'" value="'+res.url+'">';
                    $('#'+data.elem).before(_input);
                    return false;
                }
                layer.msg(res.msg,{icon:5,time:1000});
            },
            error: function(){
                //请求异常回调
            }
        });
    });
}

/**
 * 上传excel文件
 * */
var iotUploadExcel = function(data) {
    layui.use('upload', function(){
        var upload = layui.upload;
        //执行实例
        var uploadInst = upload.render({
            elem: '#'+data.elem, //绑定元素
            url: data.url, //上传接口
            accept: 'file', //普通文件
            size: 2048, // 最大允许上传文件的大小
            method: 'post',
            done: function(res){
                // 上传成功
                if(res.code == 0){
                    $('input[name="'+data.elem+'"]').val(res.url);
                    return false;
                }
                layer.msg(res.msg,{icon:5,time:1000});
            },
            error: function(){
                //请求异常回调
            }
        });
    });
}