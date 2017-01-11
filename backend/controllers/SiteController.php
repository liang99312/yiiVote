<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\UserForm;
use app\models\PwdForm;
use app\models\YiiUser;

/**
 * Site controller
 */
class SiteController extends Controller {

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
		    'access' => [
			'class' => AccessControl::className(),
			'rules' => [
			    [
				'actions' => ['login', 'error', 'captcha'],
				'allow' => true,
			    ],
			    [
				'actions' => ['logout', 'index', 'changepwd','basemsg'],
				'allow' => true,
				'roles' => ['@'],
			    ],
			],
		    ],
		];
	}

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
		return $this->render('index');
	}
	
	public function actionBasemsg() {
		return $this->render('basemsg');
	}

	public function actionLogin() {
		$model = new UserForm();

		if ($model->load(Yii::$app->request->post())) {

			if ($model->login()) {
				return $this->redirect(['site/index']);
			} else {
				return $this->render('login', ['model' => $model]);
			}
		}

		return $this->render('login', ['model' => $model]);
	}

	public function actionLogout() {
		Yii::$app->user->logout();
                
		return $this->goHome();
	}

	public function actionChangepwd() {
		$uid = Yii::$app->user->getId();
		$model = new PwdForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if (Yii::$app->user) {
				$sql = "select * from yii_user where id=".$uid." and pwd=md5('".$model->opwd."')";
				$connection = Yii::$app->db;
				$command = $connection->createCommand($sql);
				$result = $command->queryAll();
				if (!$result) {
					Yii::$app->session->setFlash('error', '旧密码错误！');
				} else {
					$sql = "update yii_user set pwd='" . md5($model->npwd) . "' where id=" . $uid;
					$command = $connection->createCommand($sql);
					$result = $command->execute();
					if ($result) {
						Yii::$app->session->setFlash('success', '修改成功！');
					} else {
						Yii::$app->session->setFlash('error', '修改失败！');
					}
				}
			} else {
				Yii::$app->session->setFlash('error', '用户未登陆！');
			}
		}
		return $this->render('password', ['model' => $model]);
	}

}
