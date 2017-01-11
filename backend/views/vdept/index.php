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
    <title>部门管理</title>
    <?= Html::cssFile('backend/web/content/bootstrap/css/bootstrap.min.css') ?>
    <?= Html::cssFile('backend/web/content/site_m.css') ?>
    <script type="text/JavaScript" src="backend/web/content/jquery-1.9.1.min.js"></script>
    <script type="text/JavaScript" src="backend/web/content/bootstrap/js/bootstrap.min.js"></script>
    <style>
	#tblVdept{
		table-layout: fixed;
	}

	#tblVdept td{	
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;	
	}
	#tblVdept th{
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
<div class="contianer">
	<div class="main" >
            <div class="tool" style="width:100%;float: left;">
                    <a class="btn btn-primary btn-sm" href="<?=Yii::$app->urlManager->createUrl('vdept/addvdept')?>">添加部门</a>
                </div>
                <table id="tblVdept" class="table table-hover">
                    <tr>
                        <th>编号</th>
                        <th>名称</th>
                        <th>部门分类</th>
                        <th>备注</th>
			<th>操作</th>
                    </tr>
                    <?php if(count($msgs)>0):?>
                        <?php foreach($msgs as $v):?>
                            <tr>
                                <td><?=$v->d_code?></td>
				<td><?=$v->d_name?></td>
                                <td><?=$v->d_type?></td>
                                <td><?=$v->d_remark?></td>
				<td>
					<a class="btn btn-primary" href="<?=Yii::$app->urlManager->createUrl(['vdept/editvdept','id'=>$v->id]) ?>" >编辑</a>
					<a class="btn btn-danger" href="<?=Yii::$app->urlManager->createUrl(['vdept/delvdept','id'=>$v->id]) ?>" onclick="if(confirm('确定删除部门:<?=$v->d_name?>?')==false)return false;">删除</a>
                                        
				</td>
                            </tr>
                        <?php endforeach?>
                    <?php else:?>
                        <tr><td colspan="5">暂无部门信息！</td></tr>
                    <?php endif?>
                </table>
                <div class="page">
                    <?= LinkPager::widget(['pagination' => $page]) ?>
                </div>
    </div>
</div>

</body>
<?php if (Yii::$app->session->hasFlash('success')): 
	echo "<script type=\"text/javascript\">alert('".Yii::$app->session->getFlash('success')."')</script>";
	Yii::$app->session->remove('success');
endif;
	if (Yii::$app->session->hasFlash('error')): 
	echo "<script type=\"text/javascript\">alert('".Yii::$app->session->getFlash('error')."')</script>";
	Yii::$app->session->remove('error');
endif;
?>
</html>