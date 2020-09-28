/**
 * 时间插件
 * */
layui.use('laydate', function(){
    var laydate = layui.laydate;
    //执行一个laydate实例
    lay('.times').each(function(){
        laydate.render({
            elem: this,
            trigger: 'click'
        });
    });
});