/**
 * webuploader插件上传文件
 * */
var webUploader = function(data){
    var $fileNumLimit = data['fileNumLimit'] ? data['fileNumLimit'] : 1 ;  // 默认文件总数量
        $fileSizeLimit = data['fileSizeLimit'] ? data['fileSizeLimit'] : 2 ;  // 默认所有文件上传大小
        $fileSingleSizeLimit = data['fileSingleSizeLimit'] ? data['fileSingleSizeLimit'] : 2 ;  // 默认单个文件上传大小
        $uploadType = data['uploadType'] ? data['uploadType'] : 1 ;  // 默认上传文件类型 1：excel 2：image
    var $extensions = 'xls,xlsx';  // 默认excel文件后缀
    var $mimeTypes = 'application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';  // 默认文件后缀
    if($uploadType == 2){
        $extensions = 'jpg,jpeg,png';  // image文件后缀
    }
    var uploader = WebUploader.create({
        auto: true, // 选择文件后自动上传
        runtimeOrder: 'html5', // 直接使用html5模式，还有flash的我就忽略了..
        pick: {
            id: '#'+data['id'], // 按钮元素
            multiple: false // 是否支持文件多选，false表示只能选一个
        },
        server: data['server'], // 上传文件的接口（替换成你们后端给的接口路径）
        formData: {  // 传递参数
            extensions : $extensions,
        },
        accept: {
            extensions: $extensions, // 允许的文件后缀，不带点，多个用逗号分割，这里支持老版的Excel和新版的
            mimeTypes: $mimeTypes
        },
        disableGlobalDnd: false, // 禁掉全局的拖拽功能。
        duplicate: true,  // 文件重复上传
        fileNumLimit: $fileNumLimit, // 验证文件总数量, 超出则不允许加入队列
        fileSizeLimit: $fileSizeLimit * 1024 * 1024, // 限制所有上传文件的大小
        fileSingleSizeLimit: $fileSingleSizeLimit * 1024 * 1024 // 限制单个上传文件的大小
    });
    // 当有文件被添加进队列的时候
    var $list = $('#show-file');
    uploader.on( 'fileQueued', function( file ) {
        if($fileNumLimit == 1){  // 只上传一个文件
            $('.item').empty();
        }
        $list.append( '<div id="' + file.id + '" class="item">' +
            '<h4 class="info">' + file.name + '</h4>' +
            '<p class="state">上传中...</p>' +
            '</div>' );
    });

    // 文件上传过程中创建进度条实时显示。
    uploader.on( 'uploadProgress', function( file, percentage ) {
        //var $li = $( '#'+file.id ),
        //    $percent = $li.find('.progress .progress-bar');
        //// 避免重复创建
        //if ( !$percent.length ) {
        //    $percent = $('<div class="progress progress-striped active">' +
        //        '<div class="progress-bar" role="progressbar" style="width: 0%">' +
        //        '</div>' +
        //        '</div>').appendTo( $li ).find('.progress-bar');
        //}
        //$percent.css( 'width', percentage * 100 + '%' );
    });

    // 上传成功
    uploader.on('uploadSuccess', function(file, response) {
        if(response.code == 0){
            var _input = "<input name='file' type='hidden' value='"+response.url+"'/>";
            $('.item').append(_input);
            $( '#'+file.id ).find('p.state').css('color','green').text(response.msg);
            return false;
        }
        $( '#'+file.id ).find('p.state').css('color','red').text(response.msg);
    });

    // 上传失败
    uploader.on('uploadError', function(file) {
        $( '#'+file.id ).find('p.state').css('color','red').text('上传失败');
    });

    // 上传完成（不论成功或失败都会执行）
    uploader.on( 'uploadComplete', function( file ) {
        uploader.reset();  // 清空队列
        $( '#'+file.id ).find('.progress').fadeOut();
    });

    // 所有文件上传成功后调用
    //uploader.on('uploadFinished', function () {
        // TODO
    //});

    // 上传错误
    uploader.on('error', function(status) {
        var errorTxt = '';
        if(status == 'Q_TYPE_DENIED') {
            errorTxt = '文件类型错误';
        } else if(status == 'Q_EXCEED_SIZE_LIMIT') {
            errorTxt = '文件大小超出限制，请控制在'+fileSingleSizeLimit+'M以内';
        } else {
            errorTxt = '其他错误';
        }
        layer.msg(errorTxt,{icon:5,time:2000});
    });
}