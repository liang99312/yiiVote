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
        <title>jquery table</title>
	<link href="backend/web/content/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <script type="text/JavaScript" src="backend/web/content/jquery-1.9.1.min.js"></script>
	<script type="text/JavaScript" src="backend/web/content/bootstrap/js/bootstrap.min.js"></script>
        <style>
            #divVote td{
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
            #divVote th{    margin: 0;
                   padding: 0;
                   border: 1px solid #000000;
                   border-spacing: 0px;
                   border-collapse: collapse;
                   empty-cells: show;
                   line-height: 30px;
		   text-align: center;
            }
	    #divVote .tdata{
		    text-align: left;
	    }
        </style>
	<script type="text/javascript">
            var vplanid = -1;
            $(document).ready(function (){
                                
                $("#queryform-plan").change(function(){
                    var v = $("#queryform-plan").val();
                    if(v !== undefined && "" !== v){
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
                        var temptitle = $.trim($("#queryform-plan").find("option:selected").text()) +"-"+$.trim($("#queryform-dept").find("option:selected").text())+'主观意见.xls';
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
	</script>
    </head>
    <body>
	<a id="dlink" style="display:none;"><span id="dlinkSpan">导出</span></a>
	<div style="margin:0 auto;width: 1000px;height: 40px;">
		<form id="selectdept" action="<?=Yii::$app->urlManager->createUrl('vresult/yijian')?>" method="post" enctype="multipart/form-data">
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
				<button type="submit">查找</button><button type="button" onclick="daochu();" style="margin-left:20px;" >导出excel</button></div>
		</form>		
	</div>
	<div id="divVote" style="width: 100%">
		<table id="tblVote" style="border: 1px solid #000000;border-collapse: collapse;width: 100%;">
                    <thead>
                        <tr>
			    <th style="width:100px;">部门</th>
                            <th style="width:100px;">姓名</th>
			    <th>意见</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($msgs)>0):?>
                        <?php foreach($msgs as $k=>$v):?>
                            <tr>
                                <th scope="row"><?=$v['u_dept'];?></th>
                                <th scope="row"><?=$v['u_name'];?></th>
                                <td class="tdata"><?=$v['yijian'];?></td>
                            </tr>
                        <?php endforeach?>
                    <?php else:?>
                        <tr><td colspan="3">暂无信息，请重新查询！</td></tr>
                    <?php endif?>
                </tbody>
            </table>
	</div>
	    
    </body>
</html>

