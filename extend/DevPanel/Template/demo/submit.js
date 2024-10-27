/**
 * 本代码使用 LyDev 自动生成！
 */


var data = " . $args .";
var checker = '". $checker ."';

for (const key in data) {
    if (data[key] == '') {
        layer.msg('请先填写相关信息！');
        return false;
    }
}

$.ajax({
    type: 'POST',
    url: '/@dev/application/@" . $ext . "/" . $tar . "',
    data: {
        data: data,
        checker: checker
    },
    dataType: 'json',
    success: function (res) {

        data = res.data;

        if (typeof data == 'object') {
            if (data[0] == 'OK') {
                if (data.length > 1) {
                    layer.msg(data[1]);
                } else {
                    layer.msg('数据保存成功！');
                }
            }
        } else if (data == 'OK') {
            layer.msg('数据保存成功！');
        } else {
            layer.msg('异常 [' + res.code + ']: ' + JSON.stringify(data));
        }

    },
    error: function (res) {
        layer.msg('拓展数据返回异常！请联系拓展作者！');
    }
});

return false;