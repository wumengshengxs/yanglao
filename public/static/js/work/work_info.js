var map = new BMap.Map("map");          // 创建地图实例
var point = new BMap.Point(121.403917,31.176385);  // 创建点坐标
map.centerAndZoom(point, 16);
map.enableScrollWheelZoom(true);     //开启鼠标滚轮缩放