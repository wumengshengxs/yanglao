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

/**
 * 下载模板
 * */
var clientDown = function() {
    var start_create = $("#start_create").val();
    var end_create = $("#end_create").val();
    window.location.href = "/index/data/work?start_create="+start_create+'&end_create='+end_create+'&excel=1';
}