<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>修改密码</title>
	<?= Html::cssFile('backend/web/content/bootstrap/css/bootstrap.min.css') ?>
	<?= Html::cssFile('backend/web/content/site_m.css') ?>
	<style>
		#changepwd input{width: 200px;}
		
	</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<div class="main" style="padding-top: 20px;padding-left: 20px;">
					<?php $form = ActiveForm::begin(['id' => 'changepwd', 'enableAjaxValidation' => true]); ?>
					
					<?= $form->field($model, 'opwd')->passwordInput(); ?>
					<?= $form->field($model, 'npwd')->passwordInput(); ?>
					<?= $form->field($model, 'qrpwd')->passwordInput(); ?>
					
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