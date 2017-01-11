<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>评分中心</title>
        <link rel="stylesheet" type="text/css" href="frontend/web/css/chosen.min.css">
        <script type="text/JavaScript" src="frontend/web/js/jquery-1.9.1.min.js"></script>
        <script type="text/JavaScript" src="frontend/web/js/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="frontend/web/js/jqueryEditUseBlur.js"></script>
        <style>
            #tblVoteTitle td{
                width: 100px;
                border: 1px solid #000000;
                margin: 0;
                padding: 0;
                border-spacing: 0px;
                border-collapse: collapse;
                empty-cells: show;
                line-height: 30px;
                text-align: center;
                vertical-align: middle;
            }
            #divVote td{
                width: 100px;
                border: 1px solid #000000;
                margin: 0;
                padding: 0;
                border-spacing: 0px;
                border-collapse: collapse;
                empty-cells: show;
                line-height: 30px;
                text-align: center;
                vertical-align: middle;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .xier{
                background-color: #00CC66;
            }
            .noInput{
                background-color: rosybrown;
            }
            #divVote th,#tblVoteTitle th{    margin: 0;
                                             padding: 0;
                                             width: 100px;
                                             border: 1px solid #000000;
                                             border-spacing: 0px;
                                             border-collapse: collapse;
                                             empty-cells: show;
                                             line-height: 30px;
            }
            #divVote input{
                border-style:none;
                //outline:medium;
                height: 28px;
                width: 100px;
                margin: 0 auto;

            }
            #divVote .cbBlj{
                width: 60px;
                height: 18px;
                margin: 0 auto;
                vertical-align: middle;
            }
        </style>
        <script type="text/javascript">
            var config = {
                '.chosen-select': {},
                '.chosen-select-deselect': {allow_single_deselect: true},
                '.chosen-select-no-single': {disable_search_threshold: 10},
                '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
                '.chosen-select-width': {width: "95%"}
            };
            var vuser_sz = [];
            var vplanid = -1;
            var vdeptid = -1;
	    var oldSel = "";
	    var oldSelPlan = "";
            $(document).ready(function () {
                $("#deptform-dept").chosen(config["#deptform-dept"]);
                $("#deptform-plan").chosen().change(function () {
                    var v = $("#deptform-plan").val();
                    if (v !== undefined && "" !== v) {
                        var vi = parseInt(v);
                        if (vi !== vplanid) {
			    if(doFlag){
				if (!confirm("内容有修改，是否提交评分？")) {
				    doFlag = false;
				}else{
				    $("#deptform-plan").find("option[value='"+oldSelPlan+"']").attr("selected",true);
				    $("#deptform-plan").val(oldSelPlan);
				    $("#deptform-plan").trigger("chosen:updated");
				    submitData(1);
				    return;
				}
			    }
			    oldSelPlan = v;
                            vplanid = vi;
                            $("#table_title").html($("#deptform-plan").find("option:selected").text());
                            fetchPlandept(vplanid);
			    $("#tblVote tr").hide();
                        }
                    }
                });
                $(".cbBlj").each(function () {
                    $(this).click(function () {
                        if ($(this).is(":checked")) {
                            $(this).parent("th").nextAll("td").addClass("noInput");
                            $(this).parent("th").nextAll("td").removeClass("xier");
                            $(this).parent("th").nextAll("td").html("");
                        } else {
                            $(this).parent("th").nextAll("td").removeClass("noInput");
                        }
                    });
                });
            });

            function fetchVoteData() {
                var dept = $("#deptform-dept").val();
		oldSel = dept;
                if (vplanid < 0) {
                    return alert("请选择计划");
                }
                if (dept === null || dept === ""|| dept < 0) {
                    return alert("请选择部门");
                }
                var sd = {"plan": vplanid, "dept": dept, "_csrf": "<?php echo Yii::$app->request->csrfToken; ?>"};
                $.ajax({
                    url: "<?= Yii::$app->urlManager->createUrl('site/getplanusers') ?>",
                    type: 'POST',
                    data: sd,
                    dataType: "json",
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert("获取数据失败:" + errorThrown);
                    },
                    success: function (data) {
                        vdeptid = dept;
                        jxUserData(data.sz);
                    }
                });
            }

            function jxUserData(sz) {
                vuser_sz = [];
                vuser_sz = sz;
                $("#tblVote tr").remove();
                for (var i = 0; i < sz.length; i++) {
                    var e = sz[i];
                    var xd3 = e.d3 > 0 ? 'xier' : '';
                    var xd4 = e.d4 > 0 ? 'xier' : '';
                    var xd5 = e.d5 > 0 ? 'xier' : '';
                    var xd6 = e.d6 > 0 ? 'xier' : '';
                    var xd7 = e.d7 > 0 ? 'xier' : '';
                    var xd8 = e.d8 > 0 ? 'xier' : '';
                    var xd9 = e.d9 > 0 ? 'xier' : '';
                    var xd10 = e.d10 > 0 ? 'xier' : '';
                    var xyijian = e.yijian !== undefined && e.yijian.length > 0 ? 'xier' : '';
                    var trstr = '<tr><th style="width:32px;">' + i + '</th><th scope="row">' + e.u_name
                            + '</th><th><input class="cbBlj tdata" type="checkbox" /></th><td class="numTd ' + xd3 + '">' + e.d3
                            + '</td><td class="numTd ' + xd4 + '">' + e.d4 + '</td><td class="numTd ' + xd5 + '">' + e.d5
                            + '</td><td class="numTd ' + xd6 + '">' + e.d6 + '</td><td class="numTd ' + xd7 + '">' + e.d7
                            + '</td><td class="numTd ' + xd8 + '">' + e.d8 + '</td><td class="numTd ' + xd9 + '">' + e.d9
                            + '</td><td class="numTd ' + xd10 + '">' + e.d10 + '</td><td class="textTd ' + xyijian + '" style="width:200px;">' + e.yijian + '</td></tr>';
                    $("#tblVote tbody").append(trstr);
                }
                $(".cbBlj").each(function () {
                    $(this).click(function () {
                        if ($(this).is(":checked")) {
                            $(this).parent("th").nextAll("td").addClass("noInput");
                            $(this).parent("th").nextAll("td").removeClass("xier");
                            $(this).parent("th").nextAll("td").html("");
                        } else {
                            $(this).parent("th").nextAll("td").removeClass("noInput");
                        }
                    });
                });
                var tdNods = $("#tblVote td");
                tdNods.click(tdClick);
            }

            function fetchPlandept(id) {
                var sd = {"plan": id, "_csrf": "<?php echo Yii::$app->request->csrfToken; ?>"};
                $.ajax({
                    url: "<?= Yii::$app->urlManager->createUrl('site/getplandepts') ?>",
                    type: 'POST',
                    data: sd,
                    dataType: "json",
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert("获取数据失败:" + errorThrown);
                    },
                    success: function (data) {
                        setDeptOption(data.sz);
                    }
                });
            }

            function setDeptOption(sz) {
                var selDeptStr = "<option value='0'>全部</option>";
                for (var i = 0; i < sz.length; i++) {
                    var e = sz[i];
                    selDeptStr += "<option value='" + e.id + "'>" + e.d_name + "</option>";
                }
                $("#deptform-dept").chosen("destroy");
                $("#deptform-dept").html(selDeptStr);
                $("#deptform-dept").chosen().change(function () {
                    var v = $("#deptform-dept").val();
                    if (v !== undefined && "" !== v) {
                        if(doFlag){
				if (!confirm("内容有修改，是否提交评分？")) {
				    doFlag = false;
				}else{
				    $("#deptform-dept").find("option[value='"+oldSel+"']").attr("selected",true);
				    $("#deptform-dept").val(oldSel);
				    $("#deptform-dept").trigger("chosen:updated");
				    submitData(1);
				    return;
				}
			}
			$("#tblVote tr").hide();
                    }
                });
            }

            function submitData(f) {
                if (!checkData()) {
                    alert("未完全填写，不允许提交！");
                    return;
                }
                var d = getDataFromTable();
                if (d.length < 1) {
                    alert("未填写评分，不允许提交！");
                    return;
                }
                if(f === 0){
                    if (!confirm("确定提交评分？")) {
                        return;
                    }
                }
                var dept = vdeptid;
                if (vplanid < 0) {
                    alert("请选择计划");
                    return;
                }
                if (dept === null || dept === "" || dept < 0) {
                    alert("请选择部门,并生产评分表");
                    return;
                }
                var sd = {"dept": dept, "plan": vplanid, "sz": d};

                $.ajax({
                    url: '<?= Yii::$app->urlManager->createUrl('site/sample') ?>',
                    type: 'POST',
                    data: sd,
                    dataType: "json",
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert("提交失败:" + errorThrown);
                    },
                    success: function (data) {
                        if (data.msg === "success") {
                            alert("提交成功");
			    doFlag = false;
                        } else {
                            alert("提交失败");
                        }
                    }
                });
            }
            function checkData() {
                var length = $("#tblVote tr").length;
                for (var j = 0; j < length; j++) {
                    var tr = $("#tblVote tr").eq(j);
                    var flag = $(tr).children().eq(2).children("input").is(":checked");
                    if (!flag) {
                        for (var i = 3; i < 11; i++) {
                            if ("" === $(tr).children().eq(i).html()) {
                                return false;
                            }
                        }
                    }
                }
                return true;
            }
            function getDataFromTable() {
                var result = [];
                var length = $("#tblVote tr").length;
                for (var j = 0; j < length; j++) {
                    var tr = $("#tblVote tr").eq(j);
                    var flag = $(tr).children().eq(2).children("input").is(":checked");
                    if (!flag) {
                        var d = {"u_id": vuser_sz[j].u_id, "u_name": vuser_sz[j].u_name, "u_dept": vuser_sz[j].u_dept, "d_id": vuser_sz[j].d_id, "u_code": vuser_sz[j].u_code, "flag": 1, "d3": 0, "d4": 0, "d5": 0, "d6": 0, "d7": 0, "d8": 0, "d9": 0, "d10": 0};
                        for (var i = 3; i < 11; i++) {
                            d["d" + i] = $(tr).children().eq(i).html();
                        }
                        d['yijian'] = $(tr).children().eq(11).html();
                        result.push(d);
                    } else {
                        var d = {"u_id": vuser_sz[j].u_id, "u_name": vuser_sz[j].u_name, "u_dept": vuser_sz[j].u_dept, "d_id": vuser_sz[j].d_id, "u_code": vuser_sz[j].u_code, "flag": 0, "d3": 0, "d4": 0, "d5": 0, "d6": 0, "d7": 0, "d8": 0, "d9": 0, "d10": 0};
                        result.push(d);
                    }
                }
                return result;
            }
            function exitVote(){
                if(doFlag){
                    if (!confirm("内容有修改，是否提交评分？")) {
                        doFlag = false;
                        return true;
                    }else{
                        submitData(1);
                    }
                    return false;
                }else{
                    if (!confirm("确定退出系统？")) {
                        doFlag = false;
                        return false;
                    }
                }
                return true;
            }
        </script>
    </head>
    <body >	
        <div style="margin:0 auto;width: 600px;padding-bottom: 10px;float: left;padding-left: 30%;">
            <form id="selectdept" action="<?= Yii::$app->urlManager->createUrl('site/vote') ?>" method="post" enctype="multipart/form-data">
                <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">				
                <div style="width:240px;float:left;">
                    <div style="v">
                        <label>评测计划：</label>
                        <select id="deptform-plan" name="DeptForm[plan]" style="width:150px;height: 26px;">
                            <?php
                            echo '<option></option>';
                            foreach ($plans as $k => $d) {
                                echo '<option value="' . $k . '">' . $d . '</option>';
                            }
                            ?>
                        </select>
                    </div>		
                </div>
                <div style="width:240px;float:left;">
                    <div>
                        <label>部门选择：</label>
                        <select id="deptform-dept" name="DeptForm[dept]" style="width:150px;height: 26px;">
                            <option style="font-style:oblique;">请选择</option>
                        </select>
                    </div>		
                </div>
                <div style="float:left;">
                    <button style="height:25px;" type="button" onclick="fetchVoteData()"> 生成评分表</button></div>
            </form>	


        </div>
        <div style="float:right;padding-right: 20px;" class="tool">
            <a class="btn btn-primary btn-sm" onclick="return exitVote();" href="<?= Yii::$app->urlManager->createUrl('site/logout') ?>">退出系统</a>
        </div>
        <div id="divVote" style="float:left;width: 100%">
            <table style="border-collapse:collapse;margin: 0 auto;width:1245px; border:0;" cellSpacing=1 cellPadding=0 >
                <tr>
                    <td style="padding: 0;border-width: 0;width: 100%;">
                        <table id="tblVoteTitle" style="border: 1px solid #000000;border-collapse: collapse;width: 1245px; ">
                            <thead>
                                <tr>
                                    <th colspan="12" id="table_title">测评表</th>
                                </tr>
                                <tr>
                                    <th colspan="3" rowspan="2">考核内容</th>
                                    <th colspan="2">德（20分）</th>
                                    <th colspan="2">能（20分）</th>
                                    <th>勤（10分）</th>
                                    <th colspan="2">绩（30分）</th>
                                    <th>廉（20分）</th>
                                    <th rowspan="5" style="width:200px;">意见</th>
                                </tr>
                                <tr>
                                    <th>理想信念<br/>理论素养<br/>大局意识<br/>原则性等</th>
                                    <th>知人善任<br/>处事公道<br/>作风民主等</th>
                                    <th>组织领导<br/>协调、计划<br/>决策能力、<br/>开拓创新<br/>能力</th>
                                    <th>业务知识<br/>掌握运用、<br/>调研综合<br/>分析等</th>
                                    <th>工作作风<br/>勤奋敬业<br/>尽职尽责等</th>
                                    <th>完成工作的<br/>数量、质量</th>
                                    <th>工作效率和<br/>工作总体<br/>绩效</th>
                                    <th>清正廉洁<br/>遵纪守法<br/>执行规章<br/>制度情况</th>
                                </tr>
                                <tr>
                                    <th colspan="3">权数</th>
                                    <th>1</th>
                                    <th>1</th>
                                    <th>1</th>
                                    <th>1</th>
                                    <th>1</th>
                                    <th>2</th>
                                    <th>1</th>
                                    <th>2</th>
                                </tr>
                                <tr>
                                    <th colspan="3">分值区间</th>
                                    <th rowspan="2">10-4</th>
                                    <th rowspan="2">10-4</th>
                                    <th rowspan="2">10-4</th>
                                    <th rowspan="2">10-4</th>
                                    <th rowspan="2">10-4</th>
                                    <th rowspan="2">10-4</th>
                                    <th rowspan="2">10-4</th>
                                    <th rowspan="2">10-4</th>
                                </tr>
                                <tr>
                                    <th style="width:34px;">序号</th>
                                    <th>姓名</th>
                                    <th>不了解</th>
                                </tr>
                            </thead>

                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0;border-width: 0;width: 100%;">
                        <div style="overflow: auto;overflow-x: hidden; width: 1245px; max-height: 540px;">
                            <div style="overflow: auto; width: 1265px; max-height: 540px;">
                                <table style="border-collapse: collapse;width: 1245px;table-layout: fixed;" id="tblVote">
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div style="width: 95%;float:left;margin-left: 20px;text-align: center;margin-top: 10px;">
            <input type="button" value="提交评分" onclick="submitData(0);" style="width: 80px;height: 40px;"/>
        </div>
    </body>
</html>

