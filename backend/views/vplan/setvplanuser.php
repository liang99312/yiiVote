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
            var curusers = [];
            var yxusers = [];
            var yxuserids = [];
            var zhiwus = [];
            var zhijis = [];
            var pdids = [];
            var tempObj = {};
            var aflag = false;
            var vpalnid = "-1";
            $(document).ready(function () {
                <?php
                if ("是" == $vplan->p_aflag) {
                    echo 'aflag = true;';
                }
                echo 'vpalnid=' . $vplan->id . ';';
                foreach ($zhiwus as $v) {
                    echo 'zhiwus.push("' . $v . '");';
                }
                foreach ($zhijis as $v) {
                    echo 'zhijis.push("' . $v . '");';
                }
                ?>
                initDatas();
            });
            function initDatas() {
                var selZhiwuStr = "<option value=''>全部</option>";
                for (var i = 0; i < zhiwus.length; i++) {
                    var e = zhiwus[i];
                    selZhiwuStr += "<option value='" + e + "'>" + e + "</option>";
                }
                $("#uselZhiwu").html(selZhiwuStr);
                var selZhijiStr = "<option value=''>全部</option>";
                for (var i = 0; i < zhijis.length; i++) {
                    var e = zhijis[i];
                    selZhijiStr += "<option value='" + e + "'>" + e + "</option>";
                }
                $("#uselZhiji").html(selZhijiStr);
            }

            function selectUser() {
                var name = $("#selUserName").val();
                var dept = $("#uselDept").val();
                var zhiwu = $("#uselZhiwu").val();
                var zhiji = $("#uselZhiji").val();
                var sd = {"plan": vpalnid, "dept": dept, "name": name, "zhiwu": zhiwu, "zhiji": zhiji, "_csrf": "<?php echo Yii::$app->request->csrfToken; ?>"};
                $.ajax({
                    url: "<?= Yii::$app->urlManager->createUrl('vplan/getusers') ?>",
                    type: 'POST',
                    data: sd,
                    dataType: "json",
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert("获取数据失败:" + errorThrown);
                    },
                    success: function (data) {
                        jxSelUsers(data.sz);
                    }
                });
            }

            function jxSelUsers(users) {
                curusers = users;
                var d_str = "<tr>";
                var trs = 0;
                var tds = 0;
                for (var j = 0; j < users.length; j++) {
                    var e = users[j];
                    if (tds % 10 === 0) {
                        tds = 0;
                        if (trs !== 0) {
                            d_str = d_str + "</tr><tr>";
                        }
                        trs++;
                    }
                    tds++;
                    d_str = d_str + "<td><input type='checkbox' class ='micbd' value='" + j + "'/>" + e.u_name + "</td>";
                }
                if (tds % 10 !== 0) {
                    for (var j = 0; j < 10 - tds % 10; j++) {
                        d_str = d_str + "<td></td>";
                    }
                }
                d_str = d_str + "</tr>";
                if ("<tr></tr>" === d_str) {
                    d_str = "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                }
                $("#selUserTbl").html(d_str);
                $("#selUserTbl td").each(function () {
                    $(this).mouseover(function (event) {
                        if ($(this).children("input")) {
                            var id = parseInt($(this).children("input").val());
                            var u = curusers[id];
                            if (u !== undefined) {
                                var ev = mousePosition(event);
                                var html = u.u_dept + " " + u.u_zhiwu + " " + u.u_zhiji;
                                $("#dvTdTiShi").html(html);
                                $("#dvTdTiShi").css("top", ev.y + 2);
                                $("#dvTdTiShi").css("left", ev.x);
                                $("#dvTdTiShi").css("z-index", 9999);
                                $("#dvTdTiShi").css("background", "#FFFEEE");
                                $("#dvTdTiShi").show();
                            }
                        }

                    });
                    $(this).mouseout(function () {
                        $("#dvTdTiShi").hide();
                    });
                });

                $("#uqbxz").click(function () {
                    $("#selUserTbl input").prop("checked", true);
                    $("#selUserTbl input").prop("checked", true);
                });

                $("#uqbbx").click(function () {
                    $("#selUserTbl input").prop("checked", false);
                    $("#selUserTbl input").prop("checked", false);
                });

                $("#uqbfx").click(function () {
                    $("#selUserTbl input").each(function () {
                        if ($(this).prop("checked")) {
                            $(this).prop("checked", false);
                        } else {
                            $(this).prop("checked", true);
                        }
                    });
                });
                
                $("#yuqbxz").click(function () {
                    $("#userTbl input").prop("checked", true);
                    $("#userTbl input").prop("checked", true);
                });

                $("#yuqbbx").click(function () {
                    $("#userTbl input").prop("checked", false);
                    $("#userTbl input").prop("checked", false);
                });

                $("#yuqbfx").click(function () {
                    $("#userTbl input").each(function () {
                        if ($(this).prop("checked")) {
                            $(this).prop("checked", false);
                        } else {
                            $(this).prop("checked", true);
                        }
                    });
                });
            }

            function jxYxSelUsers() {
                yxuserids = [];
                var d_str = "<tr>";
                var trs = 0;
                var tds = 0;
                for (var j = 0; j < yxusers.length; j++) {
                    var e = yxusers[j];
                    if (tds % 10 === 0) {
                        tds = 0;
                        if (trs !== 0) {
                            d_str = d_str + "</tr><tr>";
                        }
                        trs++;
                    }
                    tds++;
                    yxuserids.push(parseInt(e.id));
                    d_str = d_str + "<td><input type='checkbox' class ='cyxuser' value='" + e.id + "'/>" + e.u_name + "</td>";
                }
                if (tds % 10 !== 0) {
                    for (var j = 0; j < 10 - tds % 10; j++) {
                        d_str = d_str + "<td></td>";
                    }
                }
                d_str = d_str + "</tr>";
                if ("<tr></tr>" === d_str) {
                    d_str = "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                }
                $("#userTbl").html(d_str);
                $("#userTbl td").each(function () {
                    $(this).mouseover(function (event) {
                        if ($(this).children("input")) {
                            var id = parseInt($(this).children("input").val());
                            var u = yxusers[yxuserids.indexOf(id)];
                            if (u !== undefined) {
                                var ev = mousePosition(event);
                                var html = u.u_dept + " " + u.u_zhiwu + " " + u.u_zhiji;
                                $("#dvTdTiShi").html(html);
                                $("#dvTdTiShi").css("top", ev.y + 2);
                                $("#dvTdTiShi").css("left", ev.x);
                                $("#dvTdTiShi").css("z-index", 9999);
                                $("#dvTdTiShi").css("background", "#FFFEEE");
                                $("#dvTdTiShi").show();
                            }
                        }
                    });
                    $(this).mouseout(function () {
                        $("#dvTdTiShi").hide();
                    });
                });
            }

            function addSelUsers() {
                $("#selUserTbl input").each(function () {
                    if ($(this).prop("checked")) {
                        var id = parseInt($(this).val());
                        var e = curusers[id];
                        if (yxuserids.indexOf(parseInt(e.id)) < 0) {
                            yxusers.push(e);
                        }
                    }
                });
                jxYxSelUsers();
            }

            function deleteUsers() {
                var indexs = [];
                $("#userTbl input").each(function () {
                    if ($(this).prop("checked")) {
                        var id = parseInt($(this).val());
                        var index = yxuserids.indexOf(id);
                        if (index > -1) {
                            indexs.push(index);
                        }
                    }
                });
                indexs.reverse();
                for (var i = 0; i < indexs.length; i++) {
                    var e = indexs[i];
                    yxusers.splice(e, 1);
                }
                jxYxSelUsers();
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


            function saveUser() {
                if(!confirm("确定保存计划评测人员？")){
                    return;
                }
                var sz = [];
                for (var j = 0; j < yxusers.length; j++) {
                    var e = yxusers[j];
                    sz.push(e.id);
                }
                var sd = {"sz": sz, "plan": vpalnid, "_csrf": "<?php echo Yii::$app->request->csrfToken; ?>"};
                $.ajax({
                    url: "<?= Yii::$app->urlManager->createUrl('vplan/saveplanusers') ?>",
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
            
            function setUserModel() {
                var sd = {"plan": vpalnid, "_csrf": "<?php echo Yii::$app->request->csrfToken; ?>"};
                $.ajax({
                    url: "<?= Yii::$app->urlManager->createUrl('vplan/getplanusers') ?>",
                    type: 'POST',
                    data: sd,
                    dataType: "json",
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert("获取数据失败:" + errorThrown);
                    },
                    success: function (data) {
                        yxusers = data.sz;
                        jxYxSelUsers();
                    }
                });
                $('#myUModal').modal('show');
            }
        </script>
    </head>
    <body>
        <div class="contianer" style="min-height: 500px;">
            <div class="main" >
                <div style="padding-top: 5px; padding-left: 5px;">
                    <div style="width:380px;float: left;"><label>计划名称：</label><input type="text" disabled="disabled" value="<?= $vplan->p_name; ?>"/><label>状态：</label><input type="text" style="width: 80px;" disabled="disabled" value="<?= $vplan->p_state; ?>"/></div>
                    <div style="margin:0 auto;float: left;height: 40px;">
                        <form id="selectdept" action="<?=Yii::$app->urlManager->createUrl(['vplan/setvplanuser','id'=>$vplan->id])?>" method="post" enctype="multipart/form-data">
                                <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">				
                                <div style="width:240px;float:left;">
                                        <div>
                                                <label>部门选择：</label>
                                                <select id="queryform-dept" name="QueryForm[dept]" value="<?=$model->dept?>" style="width:150px;height: 26px;">
                                                        <?php 
                                                        echo '<option value="0">全部</option>';
                                                        foreach ($sdepts as $d){
                                                                if($model->dept == $d['id']){
                                                                    echo '<option selected="selected" value="'.$d['id'].'">'.$d['d_name'].'</option>';
                                                                }else{
                                                                    echo '<option value="'.$d['id'].'">'.$d['d_name'].'</option>';
                                                                }
                                                        }
                                                        ?>
                                                </select>
                                        </div>		</div>
                                <div style="width:200px;float:left;">
                                        <div class="form-group field-queryform-name">
                                                <label class="control-label" for="queryform-name">被评人：</label>
                                                <input type="text" id="queryform-name" value="<?=$model->name?>" name="QueryForm[name]" style="width:120px">
                                        </div>		
                                </div>
                                <div style="float:left;">
                                        <button type="submit">查找</button></div>
                        </form>		
                </div>
                </div>
                <div class="divmain">
                    <div class="tool" style="width:100%;float: left; text-align: left;padding-bottom: 5px;">
                        <a class="btn btn-primary btn-sm" href="<?= Yii::$app->urlManager->createUrl(['vplan/setvplanuser', 'id' => $vplan->id]); ?>">刷新</a>&nbsp;&nbsp;
                        <a class="btn btn-primary btn-sm" onclick="setUserModel();">设置被评人</a>
                    </div>
                    <div style="width: 100%;background-color: rgb;">
                        <table id="tblVdept" class="table table-hover">
                            <tr>
                                <th>部门</th>
                                <th>编号</th>
                                <th>姓名</th>
                                <th>职务</th>
                                <th>职级</th>
                                <th>操作</th>
                            </tr>
                            <?php if (count($umsgs) > 0): ?>
                                <?php foreach ($umsgs as $v): ?>
                                    <tr>
                                        <td><?= $v['u_code'] ?></td>
                                        <td><?= $v['u_name'] ?></td>
                                        <td><?= $v['u_dept'] ?></td>
                                        <td><?= $v['u_zhiwu'] ?></td>
                                        <td><?= $v['u_zhiji'] ?></td>
                                        <td>
                                            <a class="btn btn-danger" href="<?= Yii::$app->urlManager->createUrl(['vplan/delvuser', 'id' => $v['id'], 'p_id' => $vplan->id]) ?>" onclick="if (confirm('确定移除人员:<?= $v['u_name'] ?>?') === false) return false;">移除</a>

                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr><td colspan="6">暂无人员信息！</td></tr>
                            <?php endif ?>
                        </table>
                        <div class="page" style="text-align:left;">
                        <?= LinkPager::widget(['pagination' => $upage]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="myUModal" url='' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
            <div class="modal-dialog" style="margin:0 auto;width: 90%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">选择人员</h4>
                    </div>
                    <div class="modal-body" style="overflow:auto;max-height: 700px;padding-top: 5px;">
                        <div id="myModalBeip">
                            <div>
                                部门名称：<select id="uselDept">
                                    <?php 
                                    echo '<option value="0">全部</option>';
                                    foreach ($sdepts as $d){
                                            echo '<option value="'.$d['id'].'">'.$d['d_name'].'</option>';
                                    }
                                    ?>
                                </select>
                                职务：<select id="uselZhiwu"></select>
                                职级：<select id="uselZhiji"></select>
                                姓名：<input type="text" id="selUserName" />
                                <input type="button" value="查询" onclick="selectUser()"/>
                            </div>
                            <div style="padding-top:5px;">
                                <a href='javascript:;' id='uqbxz'>全选</a>&nbsp;&nbsp;<a href='javascript:;' id='uqbbx'>不选</a>&nbsp;&nbsp;<a href='javascript:;' id='uqbfx'>反选</a>
                                <table id="selUserTbl">
                                    <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                                </table>
                            </div>
                            <div style="padding-top:5px;padding-bottom: 5px;">
                                <input type="button" value="添加" onclick="addSelUsers()"/>
                                <input type="button" value="删除" onclick="deleteUsers()" style="padding-left:10px;"/>
                                <a href='javascript:;' id='yuqbxz'>全选</a>&nbsp;&nbsp;<a href='javascript:;' id='yuqbbx'>不选</a>&nbsp;&nbsp;<a href='javascript:;' id='yuqbfx'>反选</a>
                            </div>
                            <div id="mb_user">
                                <table id="userTbl">
                                    <tr><td></td><td></td><td></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align: center;">
                        <input type="button" value="保存" onclick="saveUser()" />
                    </div>
                </div>
            </div>
        </div>
        <div id='dvTdTiShi' style='display: inline;position: absolute; width:auto;height:auto;border-radius: 2px;'></div>	
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