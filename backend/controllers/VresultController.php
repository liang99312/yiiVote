<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Vresult;
use common\models\Vuser;
use common\models\Vrecord;
use app\models\QueryForm;

/**
 * Site controller
 */
class VresultController extends Controller {

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
                        'actions' => ['index', 'calc', 'yijian'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $model = new QueryForm();
        $vresult = new Vresult();
        $count = 0;
        $plans = [];
        $depts = [];
        $msgs = null;
        $condition = "1=1";
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->dept != "" && "全部" != $model->dept && "0"!=$model->dept  && 0!=$model->dept ) {
                $condition .= " and d_id='" . $model->dept . "'";
            }
            if ($model->name != "") {
                $condition .= " and u_name='" . $model->name . "'";
            }
            if ($model->plan != "") {
                $condition .= " and p_id=".$model->plan;
                $connection = Yii::$app->db;
                $sql = "select * from yii_vdept where id in(select d_id from yii_vplandept where p_id=$model->plan) order by d_code";
                $command = $connection->createCommand($sql);
                $depts = $command->queryAll();
            } else {
                $condition .= " and 1=0";
            }
            $msgs = $vresult->find()->where($condition)->all();
        }
        $sql = "select * from yii_vplan order by p_date desc";
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        $plans = $command->queryAll();

        return $this->render('index', ['msgs' => $msgs, 'plans' => $plans, 'depts' => $depts, 'model' => $model]);
    }

    public function actionCalc() {
        $connection = Yii::$app->db;
        $data = Yii::$app->request->post();
        $dept = $data["dept"];
        $plan = $data["plan"];
        $where = "1=1";
        $where2 = "";
        if ("全部" != $dept && "0" != $dept && 0 != $dept) {
            $where = $where . " and d_id=$dept";
            $where2 = $where2 . " and a.d_id=$dept";
        }
        
        $transaction = $connection->beginTransaction();
        try {
            $sql = "delete from yii_vresult where p_id=$plan and ".$where;
            $command = $connection->createCommand($sql);
            $result = $command->execute();

            $sql = "insert into yii_vresult(u_id,u_name,d_id,u_dept,u_code,p_id,d3,d4,d5,d6,d7,d8,d9,d10)
				select a.id,a.u_name,a.d_id,d.d_name,a.u_code,$plan,0,0,0,0,0,0,0,0 from yii_vuser a,yii_vplanuser b,yii_vdept d where a.d_id=d.id and a.id=b.u_id and b.p_id=$plan " . $where2;
            $command = $connection->createCommand($sql);
            $result = $command->execute();
            $sql = "update yii_vresult a,(select u_id,sum(d3)/count(1) as s3,sum(d4)/count(1) as s4,sum(d5)/count(1) as s5,sum(d6)/count(1) as s6,sum(d7)/count(1) as s7,sum(d8)/count(1) as s8,sum(d9)/count(1) as s9,sum(d10)/count(1) as s10 from yii_vrecord where p_id=$plan and " . $where . " group by u_id) b "
                    . "set a.d3=round(b.s3,2),a.d4=round(b.s4,2),a.d5=round(b.s5,2),a.d6=round(b.s6,2),a.d7=round(b.s7,2),a.d8=round(b.s8,2),a.d9=round(b.s9,2),a.d10=round(b.s10,2) where a.u_id = b.u_id and a.p_id=$plan " . $where2;
            $command = $connection->createCommand($sql);
            $result = $command->execute();
            
            $sql = "update yii_vresult set zongf = d3+d4+d5+d6+d7+d8+d9+d10+d8+d10 where p_id=$plan and " . $where;
            $command = $connection->createCommand($sql);
            $result = $command->execute();
            
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            echo '{"msg":"failed"}';
        }
        echo '{"msg":"success"}';
    }

    public function actionYijian() {
        $connection = Yii::$app->db;
        $model = new QueryForm();
        $vresult = new Vresult();
        $count = 0;
        $depts = [];
        $plans = [];
        $msgs = null;
        $condition = "1=1";
        $condition2 = "1=1";
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->dept != "" && 0 != $model->dept && "0" != $model->dept && "全部" != $model->dept) {
                $condition .= " and d_id=" . $model->dept;
                $condition2 .= " and a.d_id=" . $model->dept;
            }
            if ($model->name != "") {
                $condition .= " and u_name='" . $model->name . "'";
                $condition2 .= " and a.u_name='" . $model->name . "'";
            }

            if ($model->plan != "") {
                $condition .= " and p_id=" . $model->plan;
                $connection = Yii::$app->db;
                $sql = "select * from yii_vdept where id in(select d_id from yii_vplandept where p_id=$model->plan) order by d_code";
                $command = $connection->createCommand($sql);
                $depts = $command->queryAll();
            } else {
                $condition .= " and 1=0";
            }

            $sql = "select a.id,a.u_name,d.d_name as u_dept,b.yijian from yii_vuser a left join(SELECT u_id,group_concat(yijian separator '；') as yijian FROM yii_vrecord where yijian !='' and $condition GROUP BY u_id) b on a.id = b.u_id left join yii_vdept d on a.d_id=d.id, yii_vplanuser c where c.p_id= $model->plan and c.u_id=a.id and " . $condition2;
            $command = $connection->createCommand($sql);
            $msgs = $command->queryAll();
        }

        $sql = "select * from yii_vplan order by p_date desc";
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        $plans = $command->queryAll();

        return $this->render('yijian', ['msgs' => $msgs, 'plans' => $plans, 'depts'=>$depts, 'model' => $model]);
    }

}
