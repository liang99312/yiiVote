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
            #divVote th{    margin: 0;
                   padding: 0;
                   width: 100px;
                   border: 1px solid #000000;
                   border-spacing: 0px;
                   border-collapse: collapse;
                   empty-cells: show;
                   line-height: 30px;
		   text-align: center;
            }
        </style>
	<script>
            var vplanid = -1;
            $(document).ready(function (){
                //查看信息
                $(".msgshow").click(function(){
                    var u_name=$(this).attr('u_name');
                    var yijian=$(this).attr('yijian');
                    $(".modal-title").html("意见查看  <p>被评人："+u_name+"</p>");
                    $(".content").html("<p>"+yijian+"</p>");
                    $('#myModal').modal('show');
                });
                //确定按钮
                $(".sure").click(function(){
                    $('#myModal').modal('hide');
                });
                
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
    </script>
    </head>
    <body>
	<div style="margin:0 auto;width: 1000px;height: 40px;">
		<form id="selectdept" action="<?=Yii::$app->urlManager->createUrl('vrecord/index')?>" method="post" enctype="multipart/form-data">
			<input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">				
			<div style="width:240px;float:left;">
				<div>
                                        <label>评测计划：</label>
					<select id="queryform-plan" name="QueryForm[plan]" value="<?=$model->plan?>" style="width:150px;height: 26px;">
						<?php 
                                                echo '<option style="font-style:oblique;">请选择</option>';
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
				<button type="submit">查找</button></div>
		</form>		
	</div>
	<div id="divVote" style="width: 100%">
		<table style="border: 1px solid #000000;border-collapse: collapse;width: 100%;">
                    <thead>
                        <tr>
                            <th colspan="2" rowspan="2">考核内容</th>
                            <th colspan="2">德（20分）</th>
                            <th colspan="2">能（20分）</th>
                            <th>勤（10分）</th>
                            <th colspan="2">绩（30分）</th>
                            <th>廉（20分）</th>
			    <th rowspan="5">意见</th>
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
				<td class="yjdata">
                                    <?php if(strlen($v->yijian) > 0){ ?>
                                        <a class="msgshow" href="javascript:void(0)" u_name="<?=$v->u_name?>" yijian="<?=$v->yijian?>"><详细></a>
                                        <?=mb_substr($v->yijian, 0, 6, 'utf-8').'…'?>
                                    <?php }  ?>
                                </td>
                            </tr>
                        <?php endforeach?>
                    <?php else:?>
                        <tr><td colspan="11">暂无评分信息，请重新查询！</td></tr>
                    <?php endif?>
                </tbody>
            </table>
            <div class="page">
                    <?= LinkPager::widget(['pagination' => $page]) ?>
            </div>
	</div>
	    
	    <!-- Modal -->
<div class="modal fade" id="myModal" url='' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">意见查看</h4>
            </div>
            <div class="modal-body">
                <p class='text-info'>
                <div class="content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary sure">确定</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
	    
    </body>
</html>

