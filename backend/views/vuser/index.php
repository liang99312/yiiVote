<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\widgets\linkPager;
use yii\grid\GridView;
use common\tools\UtilTools;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>被评人管理</title>
    <?= Html::cssFile('backend/web/content/bootstrap/css/bootstrap.min.css') ?>
    <?= Html::cssFile('backend/web/content/site_m.css') ?>
    <script type="text/JavaScript" src="backend/web/content/jquery-1.9.1.min.js"></script>
    <script type="text/JavaScript" src="backend/web/content/bootstrap/js/bootstrap.min.js"></script>
    <style>
	    #tblVuser{
		table-layout: fixed;
	}

	#tblVuser td{	
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;	
	}
	#tblVuser th{
		text-align: left;
	}
    </style>
    <script type="text/javascript">
        function importExecl(){
		$('#myModal').modal('show');
	}
    </script>
</head>
<body >
    <div class="contianer" style="min-height:500px;">
	<div style="margin:0 auto;width: 1000px;height: 40px;">
		<form id="selectdept" action="<?=Yii::$app->urlManager->createUrl('vuser/index')?>" method="post" enctype="multipart/form-data">
			<input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">				
			<div style="width:280px;float:left;">
				<div>
					<label>部门选择：</label>
					<select id="queryform-dept" name="QueryForm[dept]" value="<?=$model->dept?>" style="width:150px;height: 26px;">
						<?php 
						foreach ($depts as $k=>$v){
							if($model->dept == $k){
							    echo '<option selected="selected" value="'.$k.'">'.$v.'</option>';
							}else{
							    echo '<option value="'.$k.'">'.$v.'</option>';
							}
						}
						?>
					</select>
				</div>		</div>
			<div style="width:280px;float:left;">
				<div class="form-group field-queryform-name">
					<label class="control-label" for="queryform-name">被评人：</label>
					<input type="text" id="queryform-name" value="<?=$model->name?>" name="QueryForm[name]" style="width:150px">
				</div>		
			</div>
			<div style="float:left;">
				<button type="submit">查找</button> <input type="button" value="导入excel" onclick="importExecl();" style="margin-left:20px;"/></div>
		</form>		
	</div>
	<div class="main" >
            <div class="tool" style="width:100%;float: left;">
                    <a class="btn btn-primary btn-sm" href="<?=Yii::$app->urlManager->createUrl('vuser/addvuser')?>">添加被评人</a>
                </div>
                <table id="tblVuser" class="table table-hover">
                    <tr>
                        <th>部门</th>
                        <th>姓名</th>
                        <th>编号</th>
                        <th>职务</th>
                        <th>职级</th>
                        <th>操作</th>
                    </tr>
                    <?php if(count($msgs)>0):?>
                        <?php foreach($msgs as $v):?>
                            <tr>
                                <td><?=$v['u_dept']?></td>
				<td><?=$v['u_name']?></td>
                                <td><?=$v['u_code']?></td>
                                <td><?=$v['u_zhiwu']?></td>
                                <td><?=$v['u_zhiji']?></td>
				<td>
					<a class="btn btn-primary" href="<?=Yii::$app->urlManager->createUrl(['vuser/editvuser','id'=>$v['id']]) ?>" >编辑</a>
					<a class="btn btn-danger" href="<?=Yii::$app->urlManager->createUrl(['vuser/delvuser','id'=>$v['id']]) ?>" onclick="if(confirm('确定删除:<?=$v['u_name']?>?')==false)return false;">删除</a>
				</td>
                            </tr>
                        <?php endforeach?>
                    <?php else:?>
                        <tr><td colspan="6">暂无被评人信息！</td></tr>
                    <?php endif?>
                </table>
                <div class="page">
                    <?= LinkPager::widget(['pagination' => $page]) ?>
                </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" url='' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="myModalLabel">导入Excel(<a href=<?= Yii::$app->urlManager->createUrl('vuser/exceloutput') ?> title="下载导入模板" target="_blank">下载导入模板</a>)</h4>
		</div>
		<div class="modal-body">

			<form id="importExcel" action="<?= Yii::$app->urlManager->createUrl('vuser/importexcel') ?>" method="post" enctype="multipart/form-data">
				<p class='text-info'>
				<div class="content">
					<input class="import_csrf" name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">		
					<label for="importform-c_file">Excel文件：</label>
					<input type="hidden" name="ImportForm[c_file]" value=""><input type="file" id="importform-c_file" name="ImportForm[c_file]">
					<p class="help-block help-block-error"></p>
				</div>
				<div >
					<button type="submit" class="btn btn-primary">确定导入</button>		
				</div>
			</form>
		</div>
		<div class="modal-footer"></div>
        </div>
    </div>
</div>
<?php if (Yii::$app->session->hasFlash('success')): 
	echo "<script type=\"text/javascript\">alert('".Yii::$app->session->getFlash('success')."')</script>";
	Yii::$app->session->remove('success');
endif;
	if (Yii::$app->session->hasFlash('error')): 
	echo "<script type=\"text/javascript\">alert('".Yii::$app->session->getFlash('error')."')</script>";
	Yii::$app->session->remove('error');
endif;
?>
</body>
</html>