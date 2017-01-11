<?php

namespace dfrontend\controllers;

use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller {

	public $enableCsrfValidation = false;

	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
		    'error' => [
			'class' => 'yii\web\ErrorAction',
		    ],
		    'captcha' => [
			'class' => 'yii\captcha\CaptchaAction',
			'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			'maxLength' => 4,
			'minLength' => 4,
		    ],
		];
	}

	public function actionIndex() {
		$depts = [];
		$sql = "select * from yii_vdept order by d_code";
		$connection = Yii::$app->db;
		$command = $connection->createCommand($sql);
		$result = $command->queryAll();
		foreach ($result as $v) {
			$depts[$v['id']] = $v['d_name'];
		}
		return $this->render('index', ['depts' => $depts]);
	}
}
