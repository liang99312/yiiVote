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
	<link rel="stylesheet" type="text/css" href="dfrontend/web/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="dfrontend/web/css/chosen.min.css">
	
	<script type="text/JavaScript" src="dfrontend/web/js/jquery-1.9.1.min.js"></script>
	<script type="text/JavaScript" src="dfrontend/web/js/chosen.jquery.min.js"></script>
	<script type="text/javascript">
		var config = {
			'.chosen-select'           : {},
			'.chosen-select-deselect'  : {allow_single_deselect:true},
			'.chosen-select-no-single' : {disable_search_threshold:10},
			'.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
			'.chosen-select-width'     : {width:"95%"}
		      };
                $(document).ready(function () {
			$("#seldept").chosen(config["#seldept"]);
		});
		function dlogin(){
			var dept = $("#seldept").val();
			if(dept === ""){
				return alert("请选择部门");
			}
			var surl = '<?= Yii::$app->urlManager->createUrl('site/dlogin') ?>';
			var reg=new RegExp("dindex.php","g"); //创建正则RegExp对象   
			var newsurl=surl.replace(reg,"index.php");   
			
			var shref = '<?= Yii::$app->urlManager->createUrl('site/vote') ?>';
			var newshref=shref.replace(reg,"index.php");  
			var sd = {"type":"dlogin","dept":dept};
			 $.ajax({
				url: newsurl,
				type: 'POST',
				data: sd,
				dataType: "json",
				error: function (XMLHttpRequest, textStatus, errorThrown) {
					alert("登录失败:"+errorThrown);
				},
				success: function (data) {
					if(data.msg === "success"){
						window.location.href=newshref;
					}else if(data.msg === "error_relogin"){
						alert("登录失败：短时间内不允许重复匿名登录");
					}else{
                                                alert("登录失败，重新登录");
                                        }
				}
			   });
		}
	</script>
	<style>
		body{background: url("dfrontend/web/images/bg.jpg") no-repeat center fixed; background-size: 100% 100%;}
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
                    <!--<h1 id="title_h"><p style="font-size:20px;">&nbsp;欢迎使用评分系统</p></h1>-->
                <div style="width: 400px;margin:0 auto;height: 340px;">
                    <div><image src="frontend/web/images/logo.png" style="width: 400px;height:164px;" /></div>
                    <div style="font-size:20px;text-align: center;font-weight:bold;margin-top: 15px;">欢迎您参加<br/>“我给领导打打分”活动</div>
                    <div style="position: absolute;bottom: 100px; width: 400px;text-align: center;">
                        <label>部门：</label>
                        <select id="seldept" name="seldept" style="width:150px;height: 26px;" value='请选择'>
                            <option></option>
                            <?php
                            foreach ($depts as $k => $d) {
                                echo '<option value="' . $k . '">' . $d . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div style="position: absolute;bottom: 40px; width: 400px;text-align: center;">
                        <input class="btn btn-primary btn-lg" type="button" value="匿名登录" onclick="dlogin()" style="margin-left:20px;"/>
                    </div>
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

