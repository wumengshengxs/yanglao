/**
 * 退出登录
 * */
var logout = function(){
    layer.confirm('确认要退出登录？',{
        btn : ['确定', '取消'],
        btn1:function(obj){
            $.post("/server/Login/logout",{},function(data){
                window.location.href = '/server/login/login';
            })
        }
    })
}