/**
 * 登录js
 * */
$(function(){
    // 登录
    $('.form-login').submit(function(e){
        e.preventDefault();
        var $subBtn = $(this).find('[type=submit]'),
            $fData = $(this).serializeArray();
        $subBtn.attr('disabled',true);
        $.post('/server/Login/checkLogin',$fData,function(data){
            if(data.code == 0){
                window.location.href = '/server/Index/index';
                return false;
            }
            layer.msg(data.msg,{icon:5,time:2000},function(){
                $subBtn.attr('disabled',false);
            })
        })
    })
})