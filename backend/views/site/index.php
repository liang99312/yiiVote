<?php

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>后台管理</title>
    <!--Bootstrap-->
    <link href="backend/web/content/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link href="backend/web/content/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <!--[if IE 7]>
    <link href="/backend/web/content/font-awesome/css/font-awesome-ie7.min.css" rel="stylesheet" />
    <![endif]-->
    <link href="backend/web/content/sidebar-menu/sidebar-menu.css" rel="stylesheet" />
    <link href="backend/web/content/ace/css/ace-rtl.min.css" rel="stylesheet" />
    <link href="backend/web/content/ace/css/ace-skins.min.css" rel="stylesheet" />
    <link href="backend/web/content/toastr/toastr.min.css" rel="stylesheet" />

    <script src="backend/web/content/jquery-1.9.1.min.js"></script>
    <script src="backend/web/content/bootstrap/js/bootstrap.min.js"></script>
    <script src="backend/web/content/sidebar-menu/sidebar-menu.js"></script>
    <script src="backend/web/content/bootstrap/js/bootstrap-tab.js"></script>
    <!--[if lt IE 9]>
    <script src="/Scripts/html5shiv.js"></script>
    <script src="/Scripts/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        body {
            font-size: 12px;
        }

        .nav > li > a {
            padding: 5px 10px;
        }

        .tab-content {
            padding-top: 3px;
        }
    </style>
    <script type="text/javascript">
	function SetWinHeight(obj){ 
		var win=obj; 
		if (document.getElementById){ 
			if (win && !window.opera){ 
				if (win.contentDocument && win.contentDocument.body.offsetHeight){ 
					win.height = win.contentDocument.body.offsetHeight + 20; 
				}
				else if(win.Document && win.Document.body.scrollHeight) {
					win.height = win.Document.body.scrollHeight; 
				}
			} 
		} 
	} 

    </script>
</head>
<body>
    <div class="navbar navbar-default" id="navbar">
        <div class="navbar-container" id="navbar-container">
            <div class="navbar-header pull-left">
                <a href="#" class="navbar-brand">
                    <small>
                        <i class="icon-leaf"></i>
                        合肥供水集团民主测评系统
                    </small>
                </a>
            </div>

            <div class="navbar-header pull-right" role="navigation">
		    <div style="background-color: #579ec8 !important; color: #fff; height:45px; line-height:45px; margin-right: 50px;">
			  欢迎光临,<?=yii::$app->user->identity->user;?>
			    <a href="<?= Yii::$app->urlManager->createUrl('site/logout') ?>" style="color: #fff;">
                                    <i class="icon-off"></i>
                                    退出
                                </a>
		    </div>
            </div>
		<div class="navbar-header ace-settings-container" id="ace-settings-container" style="float:right;top:15px;">
                <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
                    <i class="icon-cog bigger-150"></i>
                </div>
                <div class="ace-settings-box" id="ace-settings-box">
                    <div>
                        <div class="pull-left">
                            <select id="skin-colorpicker" class="hide">
                                <option data-skin="default" value="#438EB9">#438EB9</option>
                                <option data-skin="skin-1" value="#222A2D">#222A2D</option>
                                <option data-skin="skin-2" value="#C6487E">#C6487E</option>
                                <option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
                            </select>
                        </div>
                        <span>&nbsp; 选择皮肤</span>
                    </div>
                    <div>
                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
                        <label class="lbl" for="ace-settings-navbar"> 固定导航条</label>
                    </div>
                    <div>
                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
                        <label class="lbl" for="ace-settings-sidebar"> 固定滑动条</label>
                    </div>
                    <div>
                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
                        <label class="lbl" for="ace-settings-breadcrumbs">固定面包屑</label>
                    </div>

                    <div>
                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
                        <label class="lbl" for="ace-settings-rtl">切换到左边</label>
                    </div>

                    <div>
                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container" />
                        <label class="lbl" for="ace-settings-add-container">
                            切换窄屏
                            <b></b>
                        </label>
                    </div>
                </div>
            </div>
        </div>
	    
    </div>
    <div class="main-container" id="main-container">
        <div class="main-container-inner">
            <a class="menu-toggler" id="menu-toggler" href="#">
                <span class="menu-text"></span>
            </a>
            <div class="sidebar" id="sidebar">
                <ul class="nav nav-list" id="menu"></ul>
                <div class="sidebar-collapse" id="sidebar-collapse">
                    <i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
                </div>
            </div>
            <div class="main-content">
                <div class="page-content">
                    <div class="row">
                        <div class="col-xs-12" style="padding-left:5px;">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="active"><a href="#Index" role="tab" data-toggle="tab">首页</a></li>
                            </ul>
                            <div class="tab-content" >
                                <div role="tabpanel" class="tab-pane active" id="Index">
                                        <h2 style="margin-left:20px;">欢迎进入后台管理系统</h2>
					<p style="margin-left:20px;">点击左侧菜单进行操作</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
            <i class="icon-double-angle-up icon-only bigger-110"></i>
        </a>
    </div>
    <script type="text/javascript">
	var vdept = "<?= Yii::$app->urlManager->createUrl('vdept/index') ?>";
	var vuser = "<?= Yii::$app->urlManager->createUrl('vuser/index') ?>";
        var vplan = "<?= Yii::$app->urlManager->createUrl('vplan/index') ?>";
	var vrecord = "<?= Yii::$app->urlManager->createUrl('vrecord/index') ?>";
	var basemsg = "<?= Yii::$app->urlManager->createUrl('site/basemsg') ?>";
	var mpwd = "<?= Yii::$app->urlManager->createUrl('site/changepwd') ?>";
	var vresult = "<?= Yii::$app->urlManager->createUrl('vresult/index') ?>";
	var yijian = "<?= Yii::$app->urlManager->createUrl('vresult/yijian') ?>";
        //toastr.options.positionClass = 'toast-bottom-right';
        $(function () {
            $('#menu').sidebarMenu({
                data: [{
                    id: '1',
                    text: '系统管理',
                    icon: 'icon-cog',
                    url: '',
                    menus: [{
                        id: '11',
                        text: '基本信息',
                        icon: 'icon-glass',
                        url: basemsg
                    },{
                        id: '12',
                        text: '修改密码',
                        icon: 'icon-glass',
                        url: mpwd
                    }]
                }, {
                    id: '2',
                    text: '评分管理',
                    icon: 'icon-leaf',
                    url: '',
                    menus: [{
                        id: '26',
                        text: '部门管理',
                        icon: 'icon-glass',
                        url: vdept
                    }, {
                        id: '21',
                        text: '被评人管理',
                        icon: 'icon-glass',
                        url: vuser
                    }, {
                        id: '25',
                        text: '评测计划',
                        icon: 'icon-glass',
                        url: vplan
                    },{
                        id: '22',
                        text: '评分明细',
                        icon: 'icon-glass',
                        url: vrecord
                    }, {
                        id: '23',
                        text: '评分结果',
                        icon: 'icon-glass',
                        url: vresult
                    }, {
                        id: '24',
                        text: '主观意见',
                        icon: 'icon-glass',
                        url: yijian
                    }]
                }]
            });
        });
    </script>
    <script src="backend/web/content/ace/js/ace-extra.min.js"></script>
    <script src="backend/web/content/ace/js/ace.min.js"></script>
</body>
</html>