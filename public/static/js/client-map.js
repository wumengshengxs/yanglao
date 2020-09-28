 var opts = {
        width : 300,     // 信息窗口宽度
        height: 190,     // 信息窗口高度
        title : '<h5>定位详情</h5>' , // 信息窗口标题
        enableMessage:false//设置允许信息窗发送短息
    };

    map = new BMap.Map("allmap");
    //经纬度为徐汇区
    var point = new BMap.Point(121.43,31.18);
    //创建打开区域
    map.centerAndZoom(point, 13);
    //开启鼠标滚轮缩放
    map.enableScrollWheelZoom(true);   
    var loc = $.parseJSON($("input[name='timely']").val());
    //创建标注
    var marker = new BMap.Marker(new BMap.Point(loc.j,loc.w));  
    //将标注添加到地图中
    map.addOverlay(marker);    
    var content ='<img src='+loc.head+' class="img-responsive"><br/>'
    +'用户名:'+loc.name+'<br/>'
    +'定位方式:'+loc.loc+'<br/>'
    +'设备号:'+loc.imei+'<br/>'
    +'地理位置:'+loc.address+'<br/>'
    +'定位时间:'+loc.time+'<br/>';
    //添加内容
    addClickHandler(content,marker);
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
    
    //展示安全区域
    if (loc.lng && loc.lat && loc.radius) {
       var circle = new BMap.Circle(new BMap.Point(loc.lng,loc.lat),loc.radius,{fillColor:"red", strokeWeight: 3 ,fillOpacity: 0.6, strokeOpacity: 0.8,enableEditing:false});
        map.addOverlay(circle); 
    }
    
    //创建电子围栏
    function address(imei){
        $('#gpsPosition').css('display','block');
        //实例化绘制电子围栏
        var myDrawingManagerObject = new BMapLib.DrawingManager(map, {
            isOpen: false, //是否开启绘制模式
            enableDrawingTool: true, //是否显示工具栏
            drawingToolOptions: {
                anchor: BMAP_ANCHOR_TOP_RIGHT, //位置
                offset: new BMap.Size(5, 5), //偏离值
                drawingModes : [BMAP_DRAWING_CIRCLE], //设置只显示圆的模式
            },
            //圆的样式
            circleOptions: {
                strokeColor:"blue",    //边线颜色。
                fillColor:"red",      //填充颜色。当参数为空时，圆形将没有填充效果。
                strokeWeight: 3,       //边线的宽度，以像素为单位。
                strokeOpacity: 0.8,    //边线透明度，取值范围0 - 1。
                fillOpacity: 0.6,      //填充的透明度，取值范围0 - 1。
                strokeStyle: 'solid' //边线的样式，solid或dashed。
            },
        });
        //删除指定数组元素
        var overlays = new Array();
        //监听圆形绘制完毕后触发下列方法
        myDrawingManagerObject.addEventListener("circlecomplete",function(e,overlay){
            //绘制完成后 移除原有安全区域
            map.removeOverlay(circle);
            overlays.push(overlay);
            //最新的测距
            var xa = overlay.xa;
            for(var i = 0; i < overlays.length; i++){
                if (overlays[i].xa!==xa) {
                    //移除指定元素
                    map.removeOverlay(overlays[i]);
                }
            }
            //获取中心点的圆心半径
            var center = overlay.getRadius();
            //圆心半径大于一定值后弹出提示框
            if (center>5000) {
                $("#button_id").attr('disabled',true);
                layer.msg('围栏的半径不能大于5000M',{icon:4,time:3000});
                return false;
            }else{
                $("#button_id").attr('disabled',false);
            }
            //获取电子围栏的经纬度
            var lng = e.point.lng;
            var lat = e.point.lat;
            $("input[name='lng']").attr('value',lng);
            $("input[name='lat']").attr('value',lat);
            $("input[name='radius']").attr('value',center);
        });
    }
    //提交电子围栏
    function gpsAction(){
        $.post("create_fence",$('#gpsPosition').serialize(),function(fence){
            if (fence.code==0) {
                layer.msg(fence.msg,{icon:1,time:1000});
                //刷新当前页面
                location.reload();
                return false;
            }
            layer.msg(fence.msg,{icon:4,time:1000});
        });
    }
    //删除电子围栏
    function rmaddress(imei){
        layer.confirm('确认要移除电子围栏吗？',{
            btn : ['确定', '取消'],
            btn1:function(obj){
                $(".layui-layer-btn0").attr('disabled',true);
                $.post("rmfence",{'imei':imei},function(data){
                    if(data.code == 0){
                        //刷新当前页面
                        location.reload();
                        return false;
                    }
                    layer.msg(data.msg,{icon:5,time:2000},function(){
                        $(".layui-layer-btn0").attr('disabled',false);
                    })
                })
            }
        })
    }
    //获取时时定位
    function getgps(imei){
        layer.msg('正在发送指令,请稍后',{icon:1,time:1000},function(){
            $.post("getgps",{'imei':imei},function(info){
                if (info.code!==0) {
                    layer.msg(info.msg,{icon: 5, time: 2000});
                    return false;
                }
                //清除原有覆盖物
                map.clearOverlays();
                var newobj =  JSON.parse(info.msg);
                var point = new BMap.Point(newobj.j,newobj.w);
                // 创建打开区域
                var marker = new BMap.Marker(new BMap.Point(newobj.j,newobj.w));  // 创建标注
                var content_mess ='<img src='+newobj.img+' class="img-responsive"><br/>'
                            +'用户名:'+newobj.name+'<br/>'
                            +'定位方式:'+newobj.location_type+'<br/>'
                            +'设备号:'+newobj.imei+'<br/>'
                            +'地理位置:'+newobj.address+'<br/>'
                            +'定位时间:'+newobj.addtime+'<br/>';
                //将标注添加到地图中
                map.addOverlay(marker);
                //添加内容
                addClickHandler(content_mess,marker);
                marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
            })
        });
    }