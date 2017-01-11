<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>基本信息</title>
	<?= Html::cssFile('backend/web/content/bootstrap/css/bootstrap.min.css') ?>
	<?= Html::cssFile('backend/web/content/site_m.css') ?>
</head>
<body>
	<div class="container" style="margin-left: 0px; ">
		<div class="row">
			<div class="col-md-6">
				<div class="main" style="padding-top: 20px;padding-left: 20px;font-size: 20px;width: 700px;">
					<p><br></p>
					<p>本系统用于企业内部无记名评分。</p>
					<p><br></p>
					<p>功能介绍：</p>
					<p><br></p>
					<p>1、修改密码：可以修改登录用户密码。</p>
					<p><br></p>
					<p>2、被评人管理：维护被评人信息，是评分的对象。</p>
					<p><br></p>
					<p>3、评分明细：可以查看被评人的评分明细。</p>
					<p><br></p>
					<p>4、评分结果：可以按规则统计被评人的得分结果，可以导出结果到excel。</p>
					<p><br></p>
					<p>5、主观意见：汇总被评人的意见，可以导出excel。</p>
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