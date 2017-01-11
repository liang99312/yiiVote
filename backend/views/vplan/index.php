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
    <title>评测计划管理</title>
    <?= Html::cssFile('backend/web/content/bootstrap/css/bootstrap.min.css') ?>
    <?= Html::cssFile('backend/web/content/site_m.css') ?>
    <script type="text/JavaScript" src="backend/web/content/jquery-1.9.1.min.js"></script>
    <script type="text/JavaScript" src="backend/web/content/bootstrap/js/bootstrap.min.js"></script>
    <style>
	#tblVplan td{	
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;	
	}
	#tblVplan th{
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
                    <a class="btn btn-primary btn-sm" href="<?=Yii::$app->urlManager->createUrl('vplan/addvplan')?>">添加评测计划</a>
                </div>
                <table id="tblVplan" class="table table-hover">
                    <tr>
                        <th>评测计划</th>
			<th>全员参与</th>
                        <th>创建时间</th>
                        <th>状态</th>
                        <th>评测对象</th>
                        <th>操作</th>
                    </tr>
                    <?php if(count($msgs)>0):?>
                        <?php foreach($msgs as $v):?>
                            <tr>
                                <td><?=$v->p_name?></td>
				<td><?=$v->p_aflag?></td>
				<td><?=$v->p_date?></td>
                                <td><?=$v->p_state?></td>
                                <td>
					<a class="btn btn-primary" href="<?=Yii::$app->urlManager->createUrl(['vplan/setvplan','id'=>$v->id]) ?>" >部门</a>
					<a class="btn btn-primary" href="<?=Yii::$app->urlManager->createUrl(['vplan/setvplanuser','id'=>$v->id]) ?>" >人员</a>
                                </td>
				<td>
					<a class="btn btn-primary" href="<?=Yii::$app->urlManager->createUrl(['vplan/editvplan','id'=>$v->id]) ?>" >编辑</a>
                                        <a class="btn btn-primary" href="<?=Yii::$app->urlManager->createUrl(['vplan/copyvplan','id'=>$v->id]) ?>" onclick="if(confirm('确定复制计划:<?=$v->p_name?>?')==false)return false;">复制</a>
                                        <a class="btn btn-primary" href="<?=Yii::$app->urlManager->createUrl(['vplan/beginvplan','id'=>$v->id]) ?>" onclick="if(confirm('确定开启计划:<?=$v->p_name?>?')==false)return false;">开启</a>
                                        <a class="btn btn-primary" href="<?=Yii::$app->urlManager->createUrl(['vplan/endvplan','id'=>$v->id]) ?>" onclick="if(confirm('确定结束计划:<?=$v->p_name?>?')==false)return false;">结束</a>
					<a class="btn btn-danger" href="<?=Yii::$app->urlManager->createUrl(['vplan/delvplan','id'=>$v->id]) ?>" onclick="if(confirm('确定删除计划:<?=$v->p_name?>?')==false)return false;">删除</a>
                                        
				</td>
                            </tr>
                        <?php endforeach?>
                    <?php else:?>
                        <tr><td colspan="5">暂无评测计划信息！</td></tr>
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