<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\widgets\linkPager;
use yii\grid\GridView;
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>考核结果</title>
	<link href="backend/web/content/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <script type="text/JavaScript" src="backend/web/content/jquery-1.9.1.min.js"></script>
        <style>
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
            }
            #divVote th{    
		    margin: 0;
                   padding: 0;
                   width: 100px;
                   border: 1px solid #000000;
                   border-spacing: 0px;
                   border-collapse: collapse;
                   empty-cells: show;
		   text-align: center;
                   line-height: 30px;
            }
        </style>
        <script type="text/javascript">
            var vplanid = -1;
            $(document).ready(function (){
                                
                $("#queryform-plan").change(function(){
                    var v = $("#queryform-plan").val();
                    if(v !== undefined && v !== null && "" !== v){
                        var vi = parseInt(v);
                        if(vi !== vplanid){
                            vplanid = vi;
                            fetchPlandept(vplanid);
                        }
                    }
                });
                
                
            });
            
            function fetchPlandept(id){
                    var sd = {"plan":id,"_csrf":"<?php echo Yii::$app->request->csrfToken; ?>"};
                    $.ajax({
                            url: "<?= Yii::$app->urlManager->createUrl('vplan/getplandepts') ?>",
                            type: 'POST',
                            data: sd,
                            dataType: "json",
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                    alert("获取数据失败:"+errorThrown);
                            },
                            success: function (data) {
                                    setDeptOption(data.sz);
                            }
                       });
            }
            
            function setDeptOption(sz){
                var selDeptStr = "<option value='0'>全部</option>";
                    for(var i=0;i<sz.length;i++){
                            var e = sz[i];
                            selDeptStr += "<option value='"+e.id+"'>"+e.d_name+"</option>";
                    }
                    $("#queryform-dept").html(selDeptStr);
            }
            function daochu() {
                        var temptitle = $.trim($("#queryform-plan").find("option:selected").text()) +"-"+$.trim($("#queryform-dept").find("option:selected").text())+'评分结果.xls';
			tableToExcel('tblVote', 'Sheet1', temptitle);
		}
		var tableToExcel = (function() {
			var uri = 'data:application/vnd.ms-excel;base64,';
			var template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines></x:DisplayGridlines></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>';
			var base64 = function(s) {
					return window.btoa(unescape(encodeURIComponent(s)));
				};
			var format = function(s, c) {
					return s.replace(/{(\w+)}/g, function(m, p) {
						return c[p];
					});
				};
			return function(table, name, filename) {
				if (!table.nodeType)
					table = document.getElementById(table);
				var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}

				document.getElementById("dlink").href = uri + base64(format(template, ctx));
				document.getElementById("dlink").download = filename;
				document.getElementById("dlink").click();
			};
		})();
                
                function calcResult(){
                    var dept = $("#queryform-dept").val();
                    var plan = $("#queryform-plan").val();
                    var _csrf = $("#_csrf").val();
                    var d = {'dept':dept,'plan':plan,'_csrf':_csrf};
                    if(!confirm("确定计算‘"+$.trim($("#queryform-plan").find("option:selected").text()) +"-"+$.trim($("#queryform-dept").find("option:selected").text())+"’评分结果？")){
                        return;
                    }
                    $.ajax({
                            url: '<?= Yii::$app->urlManager->createUrl('vresult/calc') ?>',
                            type: 'POST',
                            data: d,
                            dataType: "json",
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                    alert("计算失败:"+errorThrown);
                            },
                            success: function (data) {
                                    if(data.msg === "success"){
                                            alert("计算成功，请重新查找")
                                    }else{
                                            alert("计算失败");
                                    }
                            }
                       });
                }
        </script>
    </head>
    <body>
        <a id="dlink" style="display:none;"><span id="dlinkSpan">导出</span></a>
	<div style="margin:0 auto;width: 1000px;height: 40px;">
		<form id="selectdept" action="<?=Yii::$app->urlManager->createUrl('vresult/index')?>" method="post" enctype="multipart/form-data">
			<input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">				
			<div style="width:240px;float:left;">
				<div>
                                        <label>评测计划：</label>
					<select id="queryform-plan" name="QueryForm[plan]" value="<?=$model->plan?>" style="width:150px;height: 26px;">
						<?php 
                                                echo '<option style="font-style:oblique;" value=0>请选择</option>';
						foreach ($plans as $d){
							if($d['id'] == $model->plan){
							    echo '<option selected="selected" value="'.$d['id'].'">'.$d['p_name'].'</option>';
							}else{
							    echo '<option value="'.$d['id'].'">'.$d['p_name'].'</option>';
							}
						}
						?>
					</select>
				</div>		
                        </div>
                        <div style="width:240px;float:left;">
				<div>
					<label>部门选择：</label>
					<select id="queryform-dept" name="QueryForm[dept]" value="<?=$model->dept?>" style="width:150px;height: 26px;">
						<?php 
                                                echo '<option value="0">全部</option>';
						foreach ($depts as $d){
							if($d['id'] == $model->dept){
							    echo '<option selected="selected" value="'.$d['id'].'">'.$d['d_name'].'</option>';
							}else{
							    echo '<option value="'.$d['id'].'">'.$d['d_name'].'</option>';
							}
						}
						?>
					</select>
				</div>		
                        </div>
			<div style="width:240px;float:left;">
				<div class="form-group field-queryform-name">
					<label class="control-label" for="queryform-name">被评人：</label>
					<input type="text" id="queryform-name" value="<?=$model->name?>" name="QueryForm[name]" style="width:150px">
				</div>		
			</div>
			<div style="float:left;">
                            <button type="submit">查找</button><button type="button" onclick="calcResult();" style="margin-left:20px;" >计算结果</button><button type="button" onclick="daochu();" style="margin-left:20px;" >导出excel</button></div>
		</form>		
	</div>
	<div id="divVote" style="width: 100%">
		<table id="tblVote" style="border: 1px solid #000000;border-collapse: collapse;width: 100%;">
                    <thead>
                        <tr>
                            <th colspan="2" rowspan="2">考核内容</th>
                            <th colspan="2">德（20分）</th>
                            <th colspan="2">能（20分）</th>
                            <th>勤（10分）</th>
                            <th colspan="2">绩（30分）</th>
                            <th>廉（20分）</th>
                            <th rowspan="5">总分</th>
                        </tr>
                        <tr>
                            <th>理想信念<br/>理论素养<br/>大局意识<br/>原则性等</th>
                            <th>知人善任<br/>处事公道<br/>作风民主等</th>
                            <th>组织领导协调<br/>计划决策能力<br/>开拓创新能力</th>
                            <th>业务知识掌握<br/>运用、调研<br/>综合分析等</th>
                            <th>工作作风<br/>勤奋敬业<br/>尽职尽责等</th>
                            <th>完成工作的<br/>数量、质量</th>
                            <th>工作效率和<br/>工作总体绩效</th>
                            <th>清正廉洁<br/>遵纪守法<br/>执行规章<br/>制度情况</th>
                        </tr>
                        <tr>
                            <th colspan="2">权数</th>
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
                            <th colspan="2">分值区间</th>
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
                            <th>部门</th>
                            <th>姓名</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($msgs)>0):?>
                        <?php foreach($msgs as $v):?>
                            <tr>
                                <th scope="row"><?=$v->u_dept?></th>
                                <th scope="row"><?=$v->u_name?></th>
                                <td class="tdata"><?=$v->d3?></td>
                                <td class="tdata"><?=$v->d4?></td>
                                <td class="tdata"><?=$v->d5?></td>
                                <td class="tdata"><?=$v->d6?></td>
                                <td class="tdata"><?=$v->d7?></td>
                                <td class="tdata"><?=$v->d8?></td>
                                <td class="tdata"><?=$v->d9?></td>
                                <td class="tdata"><?=$v->d10?></td>
                                <td class="tdata"><?=$v->zongf?></td>
                            </tr>
                        <?php endforeach?>
                    <?php else:?>
                        <tr><td colspan="11">暂无评分结果，请重新查询！</td></tr>
                    <?php endif?>
                </tbody>
            </table>
	</div>
    </body>
</html>

