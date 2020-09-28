$(function(){
    var opts = {
        width : 300,     // 信息窗口宽度
        height: 190,     // 信息窗口高度
        title : "" , // 信息窗口标题
        enableMessage:true//设置允许信息窗发送短息
    };
    map = new BMap.Map("allmap");
    //经纬度为徐汇区
    var point = new BMap.Point(121.43, 31.18);
    //创建打开区域
    map.centerAndZoom(point, 13);
    //开启鼠标滚轮缩放
    map.enableScrollWheelZoom(true);     
    var data_info = JSON.parse($("input[name=map]").val());

    /*
    *编写自定义函数,创建标注
    *这个point为后端抛出后处理的数据，将由这个方法渲染定位点
    */
    for(var i=0;i<data_info.length;i++){
        //创建标注
        var marker_data = new BMap.Point(data_info[i].j,data_info[i].w);  
        //创建内容
        var content = '定位时间:'
                    +data_info[i].addtime
                    +'<br/>用户名:'
                    +data_info[i].name
                    +'<br/><img src='
                    +data_info[i].img
                    +' class="img-responsive"><br/>'
                    +'定位方式:'
                    +data_info[i].location_type
                    +'<br/>设备号:'
                    +data_info[i].imei
                    +'<br/>地理位置:'
                    +data_info[i].address;             
        
        //添加标注
        addMarker(marker_data,content);
    }

    //渲染定位点
    function addMarker(point_data,content){
        var marker = new BMap.Marker(point_data);
        map.addOverlay(marker);
        //添加内容
        addClickHandler(content,marker);
    }
    
    function addClickHandler(content,marker){
        //点击定位点出现弹框
        marker.addEventListener("click",function(e){
            openInfo(content,e)}
        );
    }
    //展示弹框信息
    function openInfo(content,e){
        var p = e.target;
        var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
        var infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象
        map.openInfoWindow(infoWindow,point); //开启信息窗口
    }
    //日期搜索
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        //执行一个laydate实例
        lay('.times').each(function(){
            laydate.render({
                elem: this,trigger: 'click'
            });
        });
    });
})     
//搜索
function search_query(){
    var start = $('#start').val();
    var end = $('#end').val();
    if (start && end) {
        if (end<start) {
            layer.msg('结束时间不能小于开始时间',{icon:4,time:2000});
            return false;
        }
    }
    var query = $('#signupForm').serialize();
    location.href="/index/Loclog/map?"+query;
}