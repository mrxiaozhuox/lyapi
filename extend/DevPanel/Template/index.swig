{% extends "block/main.swig" %}

{% block title %} Extend Index {% endblock %}

{% block main %}
<div class="alert alert-danger" role="alert">
 [ ! ] This extend should be only enable on local or development environment
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Loaded Extends</h5>
        <div class="list-group">
            {% for ext in extList %}
            <a href="javascript:panel('{{ext.EXTEND_NAME}}');" class="list-group-item list-group-item-action"
                style="background-color: #d2d6d9;">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ext.EXTEND_NAME}}
                        &nbsp;<i>[ {{ext.EXTEND_VERSION}} ]</i>
                    </h5>
                    <small>{{ext.EXTEND_AUTHOR}}</small>
                </div>
                <p class="mb-1">{{ext.EXTEND_DESCRIBLE}}</p>
                <small>
                    ExtraType：{{ext.EXTEND_TYPE}}&nbsp;
                    {% if ext.EXTDEV_STD %}
                    <span class="badge badge-success">standard</span>
                    {% else %}
                    <span class="badge badge-info">original</span>
                    {% endif %}
                </small>
            </a>
            {% endfor %}
        </div>

    </div>
</div><br />

<div class="card">
    <div class="card-body">
        <h5 class="card-title">路由注册列表</h5>
        <ul class="list-group">
            {% for key, val in routeli %}
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <strong>路径绑定：
                    "{{ key }}" &nbsp;=>&nbsp; {{ val.controller }}
                </strong>
                <span class="badge badge-primary badge-pill">{{val.method}}</span>
            </li>
            {% endfor %}
        </ul>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="panelModal" tabindex="-1" aria-labelledby="panelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="panelModalLabel">%EXTEND% Panel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab"
                            aria-controls="nav-home" aria-selected="true">信息</a>
                        <a class="nav-link" id="nav-setting-tab" data-toggle="tab" href="#nav-setting" role="tab"
                            aria-controls="nav-setting" aria-selected="false">配置</a>
                        <a class="nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab"
                            aria-controls="nav-contact" aria-selected="false">验证</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">拓展名称：<span id="i-name"></span></li>
                            <li class="list-group-item">拓展版本：<span id="i-version"></span></li>
                            <li class="list-group-item">
                                作者信息：<span id="i-author"></span>
                                &nbsp;<a id="i-website" href="javascript:;" target="_bank">官方网站</a>
                            </li>
                            <li class="list-group-item">版本验证：<span id="i-standard"></span></li>
                            <li class="list-group-item">拓展介绍：<span id="i-describle"></span></li>
                        </ul>
                    </div>

                    <div class="tab-pane fade" id="nav-setting" role="tabpanel" aria-labelledby="nav-setting-tab">
                        <iframe id="panelModalFrame" src="/@dev/" frameborder="0" width="100%" height="460px"></iframe>
                    </div>

                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                        <br />
                        <span style="color: darkslategrey;">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    主程验证：<span id="i-md5"></span>
                                    <button style="float: right;" type="button" class="btn btn-info btn-sm"
                                        id="check-online">云端校验</button>
                                </li>
                                <li class="list-group-item">
                                    版本验证：

                                </li>
                            </ul>
                        </span>
                        <br />
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" ext-name="" id="delext" class="btn btn-warning">卸载拓展</button>
            </div>
        </div>
    </div>
</div>

{% endblock %}

{% block script %}
<script>
    function panel(ext) {

        $("#panelModalLabel").text(ext + " Panel");
        $("#panelModalFrame").attr("src", "/@dev/panel/@" + ext);

        $("#delext").attr("ext-name", ext);

        var item = "&item=NAME|VERSION|AUTHOR|WEBSITE|.STD|.MD5|DESCRIBLE";

        $.get("/@dev/api/info?ext=" + ext + item, function (res) {
            if (res.code == 200) {

                $("#i-name").text(res.data.NAME);
                $("#i-version").text(res.data.VERSION);
                $("#i-author").text(res.data.AUTHOR);
                $("#i-describle").text(res.data.DESCRIBLE);
                $("#i-website").attr("href", res.data.WEBSITE);
                $("#i-md5").text(res.data['.MD5']);

                var std = res.data['.STD'];
                if (res.data['.STD'] == "standard") {
                    std = std + " &nbsp;[ 符合 LyDev 标准 ]";
                } else {
                    std = std + " &nbsp;[ 原版 LyApi 拓展 ]";
                }
                $("#i-standard").html(std);

                $('#panelModal').modal({
                    keyboard: false
                })

            } else {
                layer.msg(res.msg);
            }
        });
    }

    $("#delext").click(function () {
        var ext = $("#delext").attr("ext-name");
        if (ext != "") {
            $.get("/@dev/api/delete?ext=" + ext, function (res) {
                if (res.code == 200 && res.data == "OK") {
                    layer.msg("删除拓展成功！");
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    layer.msg(res.msg);
                }
            }).fail(function (res) {
                res = JSON.parse(res.responseText);
                layer.msg(res.msg);
            });
        }
    });

    $("#check-online").click(function () {

        var ext = $("#i-name").text();
        var md5 = $("#i-md5").text();

        $.get("http://dev.wwsg18.com/api/check?repo=" + ext + "&md5=" + md5, function (res) {
            if (res.data == "True") {
                layer.msg("当前拓展与云端一致，请放心使用...");
            } else {
                layer.msg("MD5值存在差异，请前往在线平台查看！");
            }
        }).fail(function (res) {
            layer.msg("本插件尚未录入云端库...")
        });

    });
</script>
{% endblock %}
