/**
 * 公共的js方法
 * */
$(function(){
    $url = window.location.pathname.split('/'),
    $module = $url[1],
    $controller = $url[2];
    $action = $url[3];

    /**
     * validate的默认设置
     * */
    $.validator.setDefaults({
        // 提交时拦截
        submitHandler:function(form){
            form.submit();
        },
        // 错误提示位置
        errorPlacement: function(error, element) {
            errorTips($(element).attr('name'),error.text());
        }
    });

    /**
     * 多条件搜索框显示/隐藏
     * */
    $('body').delegate('.search button[type=button]','click',function(){
        $('.dropdown-menu').slideToggle();
    })
})

/**
 * 错误的提示位置
 * */
var errorTips = function(element,msg) {
    if(!$('body').find('.error-div').length){
        $('body').append('<div class="error-div"></div>');
    }
    var $errorSpan = $('.error-div').find("[data-id='"+element+"']");
    if($errorSpan.length){
        return false;
    }
    var $errorHtml = '<span data-id="'+element+'">'+msg+'</span>';
    $('body .error-div').append($errorHtml).fadeIn(1000,function(){
        var $span = $(this).find('[data-id="'+element+'"]');
        $span.fadeOut(3000,function(){
            $(this).remove();
        });
    });
}

/**
 * 获取url地址指定的参数值
 * */
var getUrlParam = function(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]);
    return null;
}

/**
 * 状态：启用/停用
 * */
var switchJs = function(obj, str1, str2){
    var t1 = str1 ? str1 : '启用';
    var t2 = str2 ? str2 : '停用';
    var _class = obj ? obj : '.js-switch';
    var elems = Array.prototype.slice.call(document.querySelectorAll(_class));
    elems.forEach(function(html) {   // 开关
        var switchery = new Switchery(html);
    });
    var changeCheckbox = document.querySelector(_class),
        changeField = document.querySelector('.switch-tip');
    changeCheckbox.onchange = function() {
        if(changeField != null){
            changeField.innerHTML = changeCheckbox.checked ? t1 : t2;
        }
    };
}

/**
 * 验证必填字段
 * @param string text 填写的值
 * @param string tips 提示文本
 * */
var checkRequired = function(text, tips) {
    tips = $.trim(tips) ? tips : '请把表单填写完整';
    if(!$.trim(text)){
        layer.msg(tips,{icon:5,time:2000});
        return false;
    }
    return true;
}

/**
 * 验证手机号
 * @param string phone 手机号
 * */
var checkPhone = function(phone) {
    var rPhone = /^1[3|4|5|7|8][0-9]{9}$/;
    if(!rPhone.test(phone)){
        layer.msg('请输入正确的手机号',{icon:5,time:2000});
        return false;
    }
    return true;
}

/**
 * 验证邮箱
 * @param string email 邮箱
 * */
var checkEmail = function(email) {
    var rEmail = /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+[\.][a-zA-Z0-9_-]+$/;
    if(!rEmail.test(email)){
        layer.msg('请输入正确的邮箱',{icon:5,time:2000});
        return false;
    }
    return true;
}

/**
 * 验证密码，密码由6-18位的字母、数字组成
 * @param string password 密码
 * */
var checkPass = function(password) {
    var rPassword = /^[a-zA-Z0-9]{6,18}$/;
    return rPassword.test(password);
}

/**
 *  验证身份证号码
 * */
var checkIdCard = function(card) {
    var $idCard = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
    return $idCard.test(card);
}

/**
 * 退出登录
 * */
var logout = function(){
    layer.confirm('确认要退出登录？',{
        btn : ['确定', '取消'],
        btn1:function(obj){
            $.post("/index/Login/logout",{},function(data){
                window.location.reload();
            })
        }
    })
}

/**
 * 时间插件
 * */
var initDate = function(obj) {
    layui.use('laydate', function()
    {
        var laydate = layui.laydate;
        for(var i in obj){
            laydate.render({
                elem: '#'+obj[i],
                max: 'new Date()',
                theme: 'grid'
            });
        }
    });
}

/**
 * 获取编辑菜单对应的菜单信息
 * */
var menuInfo = function($menu,$id) {
    for(var a in $menu){
        if($menu[a]['id'] == $id){
            return $menu[a];
        }
        if($menu[a]['nodes']){
            for(var b in $menu[a]['nodes']){
                if($menu[a]['nodes'][b]['id'] == $id){
                    return $menu[a]['nodes'][b];
                }
                if($menu[a]['nodes'][b]['nodes']){
                    for(var c in $menu[a]['nodes'][b]['nodes']){
                        if($menu[a]['nodes'][b]['nodes'][c]['id'] == $id){
                            return $menu[a]['nodes'][b]['nodes'][c];
                        }
                    }
                }
            }
        }
    }
}

