<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>修改评测计划信息</title>
	<?= Html::cssFile('backend/web/content/bootstrap/css/bootstrap.min.css') ?>
	<?= Html::cssFile('backend/web/content/site_m.css') ?>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<div class="main" style="padding-top: 20px;padding-left: 20px;">
					<?php $form = ActiveForm::begin(['id' => 'editvplan', 'enableAjaxValidation' => true,'action' => ['editvplan','id'=>$model->id],'options' => ['enctype' => 'multipart/form-data']]); ?>
					<?= $form->field($model, 'p_name')->textInput(); ?>
					<?= $form->field($model, 'p_aflag')->dropDownList(['是' => '是', '否' => '否']); ?>
					<div>
					<?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>
					</div>
					<?php ActiveForm::end() ?>
				</div>
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