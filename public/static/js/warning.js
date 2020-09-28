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

function search(){
    var start_create = $("input[name='start_create']").val();
    var end_create = $("input[name='end_create']").val();
    if (start_create && end_create) {
        if (start_create>end_create) {
            layer.msg('结束时间不能小于于开始时间',{icon:3,time:1000});
            return false;
        }
    }
    location.href ='index?'+$('#search_query').serialize()+'&type='+type;
}

//定位详情
function seemap(id){
    var index = layer.open({
        type:2,
        title:'定位详情',
        shadeClose:true,
        shade:0.8,
        area:['100%','100%'],
        content:'/index/Warning/map?id='+id,
    }); 
    layer.full(index);
}

/**
* 清除搜索条件
* */
$('.search').parent().find('span a').on('click',function(){
    var $parent = $('.search').parent(),
        $index = $parent.find('span a').index(this),
        $form = $parent.find('form'),
        $searchJson = JSON.parse($search),
        $searchInfo;
    for(var i in $searchJson){
        if($index == i){
            $searchInfo = $searchJson[i];
        }
    }
    // 通过info查找form表单中对应的input并清除
    if($.inArray($searchInfo['name'],['create']) != -1){
        $form.find('[name$="'+$searchInfo['name']+'"]').val('');
    } else {
        $form.find("[name='"+$searchInfo['name']+"']").val('');
    }
    $form.submit();
});