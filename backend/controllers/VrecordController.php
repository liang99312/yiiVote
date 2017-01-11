<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Vrecord;
use app\models\QueryForm;

/**
 * Site controller
 */
class VrecordController extends Controller {

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
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $model = new QueryForm();
        $vrecord = new Vrecord();
        $count = 0;
        $plans = [];
        $depts = [];
        $page = $page = new Pagination(['defaultPageSize' => 10, 'totalCount' => 0]);
        $msgs = null;
        $connection = Yii::$app->db;
        $condition = "1=1";
        $tmpdept = "";
        $tmpname = "";
        $tmpplan = "";
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $tmpdept = $model->dept;
            $tmpname = $model->name;
            $tmpplan = $model->plan;
        } else {
            if (Yii::$app->session->hasFlash('dept_rc_q')) {
                $tmpdept = Yii::$app->session->getFlash('dept_rc_q');
                $model->dept = $tmpdept;
            }
            if (Yii::$app->session->hasFlash('name_rc_q')) {
                $tmpname = Yii::$app->session->getFlash('name_rc_q');
                $model->name = $tmpname;
            }
            if (Yii::$app->session->hasFlash('plan_rc_q')) {
                $tmpplan = Yii::$app->session->getFlash('plan_rc_q');
                $model->plan = $tmpplan;
            }
        }
        if ($tmpdept != "" && 0 != $tmpdept && "0" != $tmpdept && "全部" != $tmpdept) {
            $condition .= " and d_id='" . $tmpdept . "'";
        }
        Yii::$app->session->setFlash('dept_rc_q', $tmpdept);
        if ($tmpname != "") {
            $condition .= " and u_name='" . $tmpname . "'";
        }
        Yii::$app->session->setFlash('name_rc_q', $tmpname);
        if ($tmpplan != null && $tmpplan != "") {
            $condition .= " and p_id=" . $tmpplan;
            $sql = "select * from yii_vdept where id in(select d_id from yii_vplandept where p_id=$tmpplan) order by d_code";
            $command = $connection->createCommand($sql);
            $depts = $command->queryAll();
        } else {
            $condition .= " and 1=0";
        }
        Yii::$app->session->setFlash('plan_rc_q', $tmpplan);

        $count = $vrecord->find()->where($condition)->count();
        $page = new Pagination(['defaultPageSize' => 10, 'totalCount' => $count]);
        $msgs = $vrecord->find()->where($condition)->orderBy('u_id')->offset($page->offset)->limit($page->limit)->all();

        $sql = "select * from yii_vplan order by p_date desc";
        $command = $connection->createCommand($sql);
        $plans = $command->queryAll();

        return $this->render('index', ['page' => $page, 'msgs' => $msgs, 'depts' => $depts, 'plans' => $plans, 'model' => $model]);
    }

}
