<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\widgets\linkPager;
use yii\grid\GridView;
use common\tools\UtilTools;
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>设置评测计划对象</title>
        <?= Html::cssFile('backend/web/content/bootstrap/css/bootstrap.min.css') ?>
        <?= Html::cssFile('backend/web/content/site_m.css') ?>
        <?= Html::cssFile('backend/web/content/setvplan.css') ?>
        <script type="text/JavaScript" src="backend/web/content/jquery-1.9.1.min.js"></script>
        <script type="text/JavaScript" src="backend/web/content/bootstrap/js/bootstrap.min.js"></script>

        <script>
            var depts = [];
            var sdepts = [];
            var pdids = [];
            var tempObj = {};
            var curi = 0;
            var aflag = false;
            var vplanid = "-1";
            var vplanstate = "";
            $(document).ready(function () {
                <?php
                if ("是" == $vplan->p_aflag) {
                    echo 'aflag = true;';
                }
                echo 'vplanid=' . $vplan->id . ';';
                echo 'vplanstate="'.$vplan->p_state . '";';
                foreach ($pdids as $v) {
                    echo 'pdids.push(' . $v . ');';
                }
                foreach ($adepts as $v) {
                    echo 'tempObj = {};';
                    foreach ($v as $key => $value) {
                        echo 'tempObj.' . $key . '="' . $value . '";';
                    }
                    if (in_array($v['id'], $pdids)) {
                        echo 'sdepts.push(tempObj);';
                    }
                    echo 'depts.push(tempObj);';
                }
                ?>
                initDatas();
            });
            function initDatas() {
                if(vplanstate === "已结束"){ 
                    $("#btnSaveDeptData").attr("disabled","disabled");
                    $("#btnSaveDeptData").css("background","#ebebe4");
                }else{
                    $("#btnSaveDeptData").removeAttr("disabled");
                }
                var selDeptStr = "<option value='全部'>全部</option>";
                var dtypes = [];
                for (var i = 0; i < depts.length; i++) {
                    var e = depts[i];
                    if (dtypes.indexOf(e.d_type) < 0) {
                        dtypes.push(e.d_type);
                        selDeptStr += "<option value='" + e.d_type + "'>" + e.d_type + "</option>";
                    }
                }
                $("#selDept").html(selDeptStr);
                $("#mb_dept").html("<a href='javascript:;' id='qbxz'>全选</a>&nbsp;&nbsp;<a href='javascript:;' id ='qbbx'>不选</a>&nbsp;&nbsp;<a href='javascript:;' id='qbfx'>反选</a><br/>");
                for (var i = 0; i < dtypes.length; i++) {
                    var d_type = dtypes[i];
                    var style_str = i!==0? "style='padding-top:10px;'":"";
                    var d_str = "<div "+style_str+"><input type='checkbox' class ='mcb' id='mcb" + i + "' value='" + i + "' />" + d_type + "<table id='mcb" + i + "tbl'><tr>";
                    var trs = 0;
                    var tds = 0;
                    for (var j = 0; j < depts.length; j++) {
                        var e = depts[j];
                        if (e.d_type === d_type) {
                            if (tds % 6 === 0) {
                                tds = 0;
                                if (trs !== 0) {
                                    d_str = d_str + "</tr><tr>";
                                }
                                trs++;
                            }
                            tds++;
                            d_str = d_str + "<td><input type='checkbox' class ='micb' id='mcb" + j + "' value='" + e.id + "' />" + e.d_name + "</td>";
                        }
                    }
                    if (tds % 6 !== 0) {
                        for (var j = 0; j < 6 - tds % 6; j++) {
                            d_str = d_str + "<td></td>";
                        }
                    }
                    d_str = d_str + "</tr></table></div>";
                    $("#mb_dept").append(d_str);
                }

                $("#qbxz").click(function () {
                    $(".micb").prop("checked", true);
                    $(".mcb").prop("checked", true);
                });

                $("#qbbx").click(function () {
                    $(".micb").prop("checked", false);
                    $(".mcb").prop("checked", false);
                });

                $("#qbfx").click(function () {
                    $(".micb").each(function () {
                        if ($(this).prop("checked")) {
                            $(this).prop("checked", false);
                        } else {
                            $(this).prop("checked", true);
                        }
                    });
                });
                $(".mcb").each(function () {
                    $(this).click(function () {
                        var id = $(this).attr("id");
                        if ($(this).prop("checked")) {
                            $("#" + id + "tbl .micb").prop("checked", true);
                        } else {
                            $("#" + id + "tbl .micb").prop("checked", false);
                        }
                    });

                });
                $("#dqbxz").click(function () {
                    $(".micbd").prop("checked", true);
                });

                $("#dqbbx").click(function () {
                    $(".micbd").prop("checked", false);
                });

                $("#dqbfx").click(function () {
                    $(".micbd").each(function () {
                        if ($(this).prop("checked")) {
                            $(this).prop("checked", false);
                        } else {
                            $(this).prop("checked", true);
                        }
                    });
                });
            }

            function fetchCdept(did) {
                var sd = {"dept": did, "plan": vplanid, "_csrf": "<?php echo Yii::$app->request->csrfToken; ?>"};
                $.ajax({
                    url: "<?= Yii::$app->urlManager->createUrl('vplan/getcids') ?>",
                    type: 'POST',
                    data: sd,
                    dataType: "json",
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert("获取数据失败:" + errorThrown);
                    },
                    success: function (data) {
                        setSelDeptFlag(data.sz);
                    }
                });
            }

            function mousePosition(ev) {
                if (!ev)
                    ev = window.event;
                if (ev.pageX || ev.pageY) {
                    return {x: ev.pageX, y: ev.pageY};
                }
                return {
                    x: ev.clientX + document.documentElement.scrollLeft - document.body.clientLeft,
                    y: ev.clientY + document.documentElement.scrollTop - document.body.clientTop
                };
            }

            function selectDept() {
                var d_type = $("#selDept").val();
                var d_name = $("#selDeptName").val();
                var d_str = "<tr>";
                var trs = 0;
                var tds = 0;
                for (var j = 0; j < sdepts.length; j++) {
                    var e = sdepts[j];
                    if (d_name !== "") {
                        if (e.d_name.substring(0, d_name.length) === d_name) {
                            if (tds % 6 === 0) {
                                tds = 0;
                                if (trs !== 0) {
                                    d_str = d_str + "</tr><tr>";
                                }
                                trs++;
                            }
                            tds++;
                            d_str = d_str + "<td><input type='checkbox' value='" + e.id + "' class ='micbd'/>" + e.d_name + "</td>";
                        }
                    } else {
                        if ("全部" === d_type) {
                            if (tds % 6 === 0) {
                                tds = 0;
                                if (trs !== 0) {
                                    d_str = d_str + "</tr><tr>";
                                }
                                trs++;
                            }
                            tds++;
                            d_str = d_str + "<td><input type='checkbox' value='" + e.id + "' class ='micbd'/>" + e.d_name + "</td>";
                        } else {
                            if (e.d_type === d_type) {
                                if (tds % 6 === 0) {
                                    tds = 0;
                                    if (trs !== 0) {
                                        d_str = d_str + "</tr><tr>";
                                    }
                                    trs++;
                                }
                                tds++;
                                d_str = d_str + "<td><input type='checkbox' value='" + e.id + "' class ='micbd'/>" + e.d_name + "</td>";
                            }
                        }
                    }
                }
                if (tds % 6 !== 0) {
                    for (var j = 0; j < 6 - tds % 6; j++) {
                        d_str = d_str + "<td></td>";
                    }
                }
                d_str = d_str + "</tr>";
                if ("<tr></tr>" === d_str) {
                    d_str = "<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                }
                $("#selDeptTbl").html(d_str);

                $("#selDeptTbl td").each(function () {
                    $(this).dblclick(function () {
                        $("#selDeptTbl td").css("background", "#f2f2f2");
                        $(this).css("background", "#ffffff");
                        if ($(this).html().length > 0) {
                            var id = parseInt($(this).children("input").val());
                            fetchCdept(id);
                        }
                    });
                });
            }

            function saveDept() {
                if (curi === 0) {
                    if(!confirm("确定保存计划评测部门？")){
                        return;
                    }
                    var sz = [];
                    $(".micb").each(function () {
                        if ($(this).prop("checked")) {
                            sz.push(parseInt($(this).val()));
                        }
                    });
                    var sd = {"sz": sz, "plan": vplanid, "_csrf": "<?php echo Yii::$app->request->csrfToken; ?>"};
                    $.ajax({
                        url: "<?= Yii::$app->urlManager->createUrl('vplan/saveplandepts') ?>",
                        type: 'POST',
                        data: sd,
                        dataType: "json",
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert("保存失败:" + errorThrown);
                        },
                        success: function (data) {
                            if (data.msg === "success") {
                                alert("保存成功");
                                pdids = [];
                                pdids = sz;
                                sdepts = [];
                                for (var j = 0; j < depts.length; j++) {
                                    var e = depts[j];
                                    if (pdids.indexOf(parseInt(e.id)) > -1) {
                                        sdepts.push(e);
                                    }
                                }
                            } else {
                                alert("保存失败");
                            }
                        }
                    });
                } else {
                    var sz = [];
                    var dsz = [];
                    $(".micb").each(function () {
                        if ($(this).prop("checked")) {
                            sz.push(parseInt($(this).val()));
                        }
                    });
                    $("#selDeptTbl input").each(function () {
                        if ($(this).prop("checked")) {
                            dsz.push(parseInt($(this).val()));
                        }
                    });
                    if(dsz.length < 1){
                        return alert("无数据需要保存");
                    }
                    if(!confirm("确定保存计划相关参评部门？")){
                        return;
                    }
                    var sd = {"sz": sz, "dsz": dsz, "plan": vplanid, "_csrf": "<?php echo Yii::$app->request->csrfToken; ?>"};
                    $.ajax({
                        url: "<?= Yii::$app->urlManager->createUrl('vplan/saveplancdepts') ?>",
                        type: 'POST',
                        data: sd,
                        dataType: "json",
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert("保存失败:" + errorThrown);
                        },
                        success: function (data) {
                            if (data.msg === "success") {
                                alert("保存成功");
                            } else {
                                alert("保存失败");
                            }
                        }
                    });
                }
            }

            function deleteDept() {
                $(".micbd:checked").parent().html("");
            }

            function setSelDeptFlag(ids) {
                if (ids.length < 1) {
                    $(".micb").prop("checked", false);
                    return;
                }
                $(".micb").each(function () {
                    var id = $(this).val();
                    if (ids.indexOf(id) > -1 || ids.indexOf(parseInt(id)) > -1) {
                        $(this).prop("checked", true);
                    } else {
                        $(this).prop("checked", false);
                    }
                });
            }

            function setDeptModel(i) {
                curi = i;
                if (i === 0) {
                    setSelDeptFlag(pdids);
                    $("#myModalLabel").html("设置被评部门");
                    $("#myModalBeip").hide();
                    $("#myModalH4").hide();
                } else {
                    if (aflag) {
                        return alert("该计划是全员参与计划，不需要设置范围");
                    }
                    $("#myModalLabel").html("批量设置被评部门的参评范围");
                    $("#myModalBeip").show();
                    $("#myModalH4").show();
                    $(".micb").prop("checked", false);
                }
                $('#myModal').modal('show');
            }
        </script>
    </head>
    <body>
        <div class="contianer" style="min-height: 500px;">
            <div class="main" >
                <div style="padding-top: 5px; padding-left: 5px;">
                    <label>计划名称：</label><input type="text" disabled="disabled" value="<?= $vplan->p_name; ?>"/><label>状态：</label><input type="text" style="width: 80px;" disabled="disabled" value="<?= $vplan->p_state; ?>"/>
                </div>
                <div class="divmain">
                    <div class="tool" style="width:100%;float: left; text-align: left;padding-top: 5px;padding-bottom: 5px;">
                        <a class="btn btn-primary btn-sm" href="<?=Yii::$app->urlManager->createUrl(['vplan/setvplan','id'=>$vplan->id]);?>">刷新</a>&nbsp;&nbsp;
                        <a class="btn btn-primary btn-sm" onclick="setDeptModel(0)">设置被评部门</a>&nbsp;&nbsp;
                        <a class="btn btn-primary btn-sm" onclick="setDeptModel(1)">设置参评范围</a>
                    </div>
                    <div style="width: 100%;background-color: rgb;">
                        <table id="tblVdept" class="table table-hover">
                            <tr>
                                <th>编号</th>
                                <th>名称</th>
                                <th>操作</th>
                            </tr>
                            <?php if (count($msgs) > 0): ?>
                                <?php foreach ($msgs as $v): ?>
                                    <tr>
                                        <td><?= $v['d_code'] ?></td>
                                        <td><?= $v['d_name'] ?></td>
                                        <td>
                                            <a class="btn btn-danger" href="<?= Yii::$app->urlManager->createUrl(['vplan/delvdept', 'id' => $v['id'], 'p_id' => $vplan->id]) ?>" onclick="if (confirm('确定移除部门:<?= $v['d_name'] ?>?') === false) return false;">移除</a>

                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr><td colspan="3">暂无部门信息！</td></tr>
                            <?php endif ?>
                        </table>
                        <div class="page" style="text-align:left;">
                        <?= LinkPager::widget(['pagination' => $page]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="myModal" url='' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
            <div class="modal-dialog" style="margin:0 auto;width: 90%;">
                <div class="modal-content">
                    <div class="modal-header" style="background:#f2f2f2;">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">设置被评部门</h4>
                    </div>
                    <div class="modal-body" style="overflow:auto;max-height: 700px;padding-top: 0px;">
                        <div id="myModalBeip">
                            <div>
                                <h4 style="margin-top:5px;margin-bottom: 5px;">被评部门：</h4>
                                部门分类：<select id="selDept"></select>
                                部门名称：<input type="text" id="selDeptName" />
                                <input type="button" value="查询" onclick="selectDept()"/>
                                <input type="button" value="删除勾选" onclick="deleteDept()" style="padding-left:10px;"/>
                            </div>
                            <div style="padding-top:5px;">
                                <a href='javascript:;' id='dqbxz'>全选</a>&nbsp;&nbsp;<a href='javascript:;' id='dqbbx'>不选</a>&nbsp;&nbsp;<a href='javascript:;' id='dqbfx'>反选</a>
                                <table id="selDeptTbl">
                                    <tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                                </table>
                            </div>
                        </div>
                        <div>
                            <h4 id='myModalH4' style="margin-top:10px;margin-bottom: 5px;">参选范围部门：</h4>
                            <div id="mb_dept"></div>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align: center;">
                        <input type="button" id="btnSaveDeptData" value="保存" onclick="saveDept()"/>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <?php
    if (Yii::$app->session->hasFlash('success')):
        echo "<script type=\"text/javascript\">alert('" . Yii::$app->session->getFlash('success') . "')</script>";
        Yii::$app->session->remove('success');
    endif;
    if (Yii::$app->session->hasFlash('error')):
        echo "<script type=\"text/javascript\">alert('" . Yii::$app->session->getFlash('error') . "')</script>";
        Yii::$app->session->remove('error');
    endif;
    ?>
</html>