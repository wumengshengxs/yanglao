var opts = {
    width : 300,     // 信息窗口宽度
    height: 170,     // 信息窗口高度
    title : "" , // 信息窗口标题
    enableMessage:true//设置允许信息窗发送短息
};
function map_init() { 
    map = new BMap.Map("allmap");
    var data_info = JSON.parse($("input[name=map]").val());
    console.log(data_info.lng);
    console.log(data_info.lat);
    //经纬度为徐汇区
    var point = new BMap.Point(data_info.lng,data_info.lat);
    //创建打开区域
    map.centerAndZoom(point, 15);
    map.enableScrollWheelZoom(true);     //开启鼠标滚轮缩放
    var marker = new BMap.Marker(point);  // 创建标注
    var content = '定位时间:'+data_info.time
    +'<br/>用户名:'+data_info.name
    +'<br/><img class="img-responsive" src='+data_info.head
    +'><br/>'
    +'定位方式:'+data_info.location_type
    +'<br/>'
    +'地理位置:'+data_info.address;
    //将标注添加到地图中
    map.addOverlay(marker);               
    //添加内容
    addClickHandler(content,marker);
    //添加标注点
    var circle = new BMap.Circle(new BMap.Point(data_info.lng,data_info.lat),data_info.radius,{fillColor:"red", strokeWeight: 3 ,fillOpacity: 0.6, strokeOpacity: 0.8,enableEditing:false});
    map.addOverlay(circle);
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
//异步调用百度js  
function map_load() {  
    var load = document.createElement("script");  
    load.src = "http://api.map.baidu.com/api?v=2.0&ak=KW0y9CM8nvQWkWcQ4jIDTOBevgSapQQQ&callback=map_init";  
    document.body.appendChild(load);  
}  
window.onload = map_load; 