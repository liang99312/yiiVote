<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Vdept;

/**
 * Site controller
 */
class VdeptController extends Controller {

    //public $enableCsrfValidation = false;
    /**
     * @用户授权规则
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'addvdept', 'editvdept', 'delvdept'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $model = new Vdept();
        $count = $model->find()->count();
        $page = new Pagination(['defaultPageSize' => 10, 'totalCount' => $count]);
        $msgs = $model->find()->orderBy('d_code')->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index', ['page' => $page, 'msgs' => $msgs]);
    }

    public function actionAddvdept() {
        $model = new Vdept();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '保存成功！');
            } else {
                Yii::$app->session->setFlash('error', '保存失败！');
            }
        }
        return $this->render('addvdept', ['model' => $model]);
    }

    public function actionEditvdept($id) {
        $model = new Vdept();
        $model = $model->find()->where(["id" => $id])->one();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '保存成功！');
            } else {
                Yii::$app->session->setFlash('error', '保存失败！');
            }
        }
        return $this->render('editvdept', ['model' => $model]);
    }

    public function actionDelvdept($id) {
        $model = new Vdept();
        $model = $model->find()->where(["id" => $id])->one();
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', '删除成功！');
        } else {
            Yii::$app->session->setFlash('error', '删除失败！');
        }
        return $this->redirect(['vdept/index']);
    }
}
