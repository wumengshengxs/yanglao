/**
 * 登录js
 * */
$(function(){
    // 登录
    $('.form-login').submit(function(e){
        e.preventDefault();
        var $subBtn = $(this).find('[type=submit]'),
            $index = $('.nav-tabs li.active').index(),
            $fData = $(this).serializeArray();
        $fData.push({'name':'type','value':$index});
        $subBtn.attr('disabled',true);
        $.post('/index/Login/checkLogin',$fData,function(data){
            if(data.code == 0){
                window.location.href = '/index/Index/index';
                return false;
            }
            layer.msg(data.msg,{icon:5,time:2000},function(){
                $subBtn.attr('disabled',false);
            })
        })
    })
})