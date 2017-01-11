<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
?>

<?php $this->beginPage() ?>
<!DOCTYPE HTML>
<html>
<head>
	<title>评分系统</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="frontend/web/css/bootstrap.min.css">
	<script type="text/JavaScript" src="frontend/web/js/jquery-1.9.1.min.js"></script>
	<style>
		body{background: url("frontend/web/images/bg.jpg") no-repeat center fixed; background-size: 100% 100%;}
		.login{background: #fff;padding: 3em;margin-top: 10em;border-radius: 0.5em;}
		
		.mr20{margin-right:20px;}
		#title_h{    
			font-family: "microsoft yahei", "黑体";
			vertical-align: middle;
			margin-left: -42px;
			margin-top: -42px;
			width: 500px;
			line-height: 60px;
			font-size: 15px;
			font-weight: bold;
			color: #555;
			text-align: center;
			text-shadow: 0 1px white;
			background: #f3f3f3;
			/* border-bottom: 1px solid #cfcfcf; */
			border-radius: 10px 10px 0 0;}
	</style>
</head>
<body>
<?php $this->beginBody() ?>
	<div class="container">
		<div class="row">
			<div class="col-md-4 sm col-sm-1"></div>
			<div class="col-md-4 sm col-sm-1 login" style="width: 500px;margin:0 auto;margin-top: 200px;">
				<h1 id="title_h"><p style="font-size:20px;">&nbsp;欢迎使用评分系统</p></h1>
				<div style="width: 240px;margin:0 auto;">
				<?php $form = ActiveForm::begin(['id' => 'login', 'enableAjaxValidation' => false,'action' => ['mlogin'],'options' => ['enctype' => 'multipart/form-data']]); ?>	

				<?= $form->field($model, 'user')->textInput(); ?>
				<?= $form->field($model, 'pwd')->passwordInput(); ?>
				<?=$form->field($model,'verifyCode')->widget(Captcha::className(),['captchaAction'=>'site/captcha',
					'imageOptions'=>['alt'=>'点击换图','title'=>'点击换图', 'style'=>'cursor:pointer']
				    ])?>
				<?= Html::submitButton('注册', ['class' => 'btn btn-primary btn-lg', 'name' => 'submit-button']) ?>
				<?php ActiveForm::end(); ?>
				</div>
			</div>
			<div class="col-md-4 sm col-sm-1"></div>
		</div>
	</div>
<?php $this->endBody() ?>
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
<?php $this->endPage() ?>

