<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Vplan;
use common\models\Vdept;
use common\models\Vuser;
use app\models\QueryForm;

/**
 * Site controller
 */
class VplanController extends Controller {

    //public $enableCsrfValidation = false;
    /**
     * @用户授权规则
     */
    public $adepts = [];

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
                        'actions' => ['index', 'addvplan', 'editvplan', 'copyvplan', 'delvplan',
                            'beginvplan', 'endvplan', 'setvplan', 'setvplanuser', 'saveplandepts', 'saveplancdepts',
                            'saveplanusers', 'getcids', 'getusers', 'delvdept', 'delvuser', 'getplanusers', 'getplandepts'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $model = new Vplan();
        $count = $model->find()->count();
        $page = new Pagination(['defaultPageSize' => 10, 'totalCount' => $count]);
        $msgs = $model->find()->orderBy('p_date desc')->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index', ['page' => $page, 'msgs' => $msgs]);
    }

    public function actionSetvplan($id) {
        $vplan = new Vplan();
        $vplan = $vplan->find()->where(["id" => $id])->one();
        $connection = Yii::$app->db;

        $dsql = "select 1 from yii_vplandept a,yii_vdept d where a.p_id=$vplan->id and a.d_id=d.id";
        $command = $connection->createCommand($dsql);
        $result = $command->queryAll();

        $dsql = "select d.* from yii_vplandept a,yii_vdept d where a.p_id=$vplan->id and a.d_id=d.id";
        $count = count($result);
        $page = new Pagination(['defaultPageSize' => 10, 'totalCount' => $count]);
        $msgs = $connection->createCommand($dsql . " limit " . $page->limit . " offset " . $page->offset . "")->queryAll();

        $connection = Yii::$app->db;
        $sql = "select d_id from yii_vplandept where p_id=" . $vplan->id;
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        $pdids = [];
        foreach ($result as $v) {
            array_push($pdids, $v['d_id']);
        }
        if (count($this->adepts) == 0) {
            $this->adepts = Vdept::getVdepts();
        }

        return $this->render('setvplan', ['page' => $page, 'msgs' => $msgs, 'adepts' => $this->adepts, 'pdids' => $pdids, 'vplan' => $vplan]);
    }

    public function actionSetvplanuser($id) {
        $vplan = new Vplan();
        $vplan = $vplan->find()->where(["id" => $id])->one();
        $connection = Yii::$app->db;
        $model = new QueryForm();
        $tmpdept = "";
        $tmpname = "";
        if ($model->load(Yii::$app->request->post())) {
            $tmpdept = $model->dept;
            $tmpname = $model->name;
        } else {
            if (Yii::$app->session->hasFlash('dept_p_q')) {
                $tmpdept = Yii::$app->session->getFlash('dept_p_q');
                $model->dept = $tmpdept;
            }
            if (Yii::$app->session->hasFlash('name_p_q')) {
                $tmpname = Yii::$app->session->getFlash('name_p_q');
                $model->name = $tmpname;
            }
        }

        $condition = "1=1";
        if ($tmpdept != "" && "全部" != $tmpdept && 0 != $tmpdept) {
            $condition .= " and u.d_id='" . $tmpdept . "'";
        }
        Yii::$app->session->setFlash('dept_p_q', $tmpdept);
        if ($tmpname != "") {
            $condition .= " and u.u_name like '" . $tmpname . "%'";
        }
        Yii::$app->session->setFlash('name_p_q', $tmpname);

        $usql = "select 1 from yii_vplanuser a,yii_vuser u,yii_vdept d where a.p_id=$vplan->id and a.u_id=u.id and u.d_id = d.id and " . $condition;
        $command = $connection->createCommand($usql);
        $uresult = $command->queryAll();

        $usql = "select u.*,d.d_name as u_dept from yii_vplanuser a,yii_vuser u,yii_vdept d where a.p_id=$vplan->id and a.u_id=u.id and u.d_id = d.id and " . $condition;
        $ucount = count($uresult);
        $upage = new Pagination(['defaultPageSize' => 10, 'totalCount' => $ucount]);
        $umsgs = $connection->createCommand($usql . " limit " . $upage->limit . " offset " . $upage->offset . "")->queryAll();

        $connection = Yii::$app->db;
        $sql = "select d.* from yii_vplandept a,yii_vdept d where a.d_id=d.id and p_id=" . $vplan->id;
        $command = $connection->createCommand($sql);
        $sdepts = $command->queryAll();

        $zhiwus = [];
        $zhijis = [];
        $sql = "select distinct u_zhiwu,u_zhiji from yii_vuser";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        foreach ($result as $v) {
            $zw = $v['u_zhiwu'];
            $zj = $v['u_zhiji'];
            if ($zw != null && !in_array($zw, $zhiwus)) {
                array_push($zhiwus, $zw);
            }
            if ($zj != null && !in_array($zj, $zhijis)) {
                array_push($zhijis, $zj);
            }
        }

        return $this->render('setvplanuser', ['model' => $model, 'sdepts' => $sdepts, 'vplan' => $vplan, 'upage' => $upage, 'umsgs' => $umsgs, 'zhiwus' => $zhiwus, 'zhijis' => $zhijis]);
    }

    public function actionDelvdept($id, $p_id) {
        $connection = Yii::$app->db;
        $sql = "delete from yii_vplandept where p_id=$p_id and d_id=$id";
        $command = $connection->createCommand($sql);
        $result = $command->execute();
        return $this->redirect(['vplan/setvplan', 'id' => $p_id]);
    }

    public function actionDelvuser($id, $p_id) {
        $connection = Yii::$app->db;
        $sql = "delete from yii_vplanuser where p_id=$p_id and u_id=$id";
        $command = $connection->createCommand($sql);
        $result = $command->execute();
        return $this->redirect(["vplan/setvplanuser", 'id' => $p_id]);
    }

    public function actionAddvplan() {
        $model = new Vplan();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->p_date = date('y-m-d h:i:s', time());
            $model->p_state = '未开启';
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '保存成功！');
            } else {
                Yii::$app->session->setFlash('error', '保存失败！');
            }
        }
        return $this->render('addvplan', ['model' => $model]);
    }

    public function actionEditvplan($id) {
        $model = new Vplan();
        $model = $model->find()->where(["id" => $id])->one();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '保存成功！');
            } else {
                Yii::$app->session->setFlash('error', '保存失败！');
            }
        }
        return $this->render('editvplan', ['model' => $model]);
    }

    public function actionCopyvplan($id) {
        $model = new Vplan();
        $model = $model->find()->where(["id" => $id])->one();
        $connection = Yii::$app->db;
        $sql = "select max(id)+1 as id from yii_vplan";
        $command = $connection->createCommand($sql);
        $result = $command->queryOne();
        $newid = -1;
        foreach ($result as $v) {
            $newid = $v;
        }
        if ($newid < 0) {
            Yii::$app->session->setFlash('error', '复制失败！');
            return $this->redirect(['vplan/index']);
        }
        $transaction = $connection->beginTransaction();
        try {
            $model->id = $newid;
            $model->p_name = "备份-" . $model->p_name;
            $d = date('y-m-d h:i:s', time());
            $sql = "insert into yii_vplan(id,p_name,p_state,p_date,p_aflag) values($model->id,'$model->p_name','未开启','$d','$model->p_aflag')";
            $command = $connection->createCommand($sql);
            $result = $command->execute();

            $sql = "insert into yii_vplandept(p_id,d_id) select $newid,a.d_id from yii_vplandept a where a.p_id=$id";
            $command = $connection->createCommand($sql);
            $result = $command->execute();

            $sql = "insert into yii_vplanuser(p_id,u_id) select $newid,a.u_id from yii_vplanuser a where a.p_id=$id";
            $command = $connection->createCommand($sql);
            $result = $command->execute();

            $sql = "insert into yii_vplancdept(p_id,d_id,cd_id) select $newid,a.d_id,a.cd_id from yii_vplancdept a where a.p_id=$id";
            $command = $connection->createCommand($sql);
            $result = $command->execute();

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', '复制失败！');
            return $this->redirect(['vplan/index']);
        }
        Yii::$app->session->setFlash('error', '复制成功！');
        return $this->redirect(['vplan/index']);
    }

    public function actionDelvplan($id) {
        $model = new Vplan();
        $model = $model->find()->where(["id" => $id])->one();
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', '删除成功！');
        } else {
            Yii::$app->session->setFlash('error', '删除失败！');
        }
        return $this->redirect(['vplan/index']);
    }

    public function actionBeginvplan($id) {
        $model = new Vplan();
        $model = $model->find()->where(["id" => $id])->one();
        $model->p_state = '进行中';
        if ($model->save()) {
            Yii::$app->session->setFlash('success', '开启成功！');
        } else {
            Yii::$app->session->setFlash('error', '开启失败！');
        }
        return $this->redirect(['vplan/index']);
    }

    public function actionEndvplan($id) {
        $model = new Vplan();
        $model = $model->find()->where(["id" => $id])->one();
        $model->p_state = '已结束';
        if ($model->save()) {
            Yii::$app->session->setFlash('success', '结束成功！');
        } else {
            Yii::$app->session->setFlash('error', '结束失败！');
        }
        return $this->redirect(['vplan/index']);
    }

    public function actionSaveplandepts() {
        $connection = Yii::$app->db;
        $data = Yii::$app->request->post();
        $sz = [];
        if(isset($data["sz"])){
            $sz = $data["sz"];
        }
        $plan = $data["plan"];
        $records = [];
        foreach ($sz as $v) {
            $temp_obj = [ $plan, $v];
            array_push($records, $temp_obj);
        }
        $transaction = $connection->beginTransaction();
        try {
            $sql = "delete from yii_vplandept where p_id=$plan";
            $command = $connection->createCommand($sql);
            $result = $command->execute();
            if (count($records) > 0) {
                $sql = $connection->getQueryBuilder()->batchInsert('yii_vplandept', ['p_id', 'd_id'], $records);
                $command = $connection->createCommand($sql);
                $result = $command->execute();
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            echo '{"msg":"failed"}';
        }
        echo '{"msg":"success"}';
    }

    public function actionSaveplancdepts() {
        $connection = Yii::$app->db;
        $data = Yii::$app->request->post();
        $sz = [];
        if(isset($data["sz"])){
            $sz = $data["sz"];
        }
        $dsz = [];
        if(isset($data["dsz"])){
            $dsz = $data["dsz"];
        }
        $plan = $data["plan"];
        $records = [];
        $ids = "-1";
        foreach ($dsz as $dv) {
            $ids = $ids . ',' . $dv;
            foreach ($sz as $v) {
                $temp_obj = [ $plan, $dv, $v];
                array_push($records, $temp_obj);
            }
        }
        $transaction = $connection->beginTransaction();
        try {
            $sql = "delete from yii_vplancdept where p_id=$plan and d_id in($ids)";
            $command = $connection->createCommand($sql);
            $result = $command->execute();
            if (count($records) > 0) {
                $sql = $connection->getQueryBuilder()->batchInsert('yii_vplancdept', ['p_id', 'd_id', 'cd_id'], $records);
                $command = $connection->createCommand($sql);
                $result = $command->execute();
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            echo '{"msg":"failed"}';
        }
        echo '{"msg":"success"}';
    }

    public function actionSaveplanusers() {
        $connection = Yii::$app->db;
        $data = Yii::$app->request->post();
        $sz = [];
        if(isset($data["sz"])){
            $sz = $data["sz"];
        }
        $plan = $data["plan"];
        $records = [];
        foreach ($sz as $v) {
            $temp_obj = [ $plan, $v];
            array_push($records, $temp_obj);
        }
        $transaction = $connection->beginTransaction();
        try {
            $sql = "delete from yii_vplanuser where p_id=$plan";
            $command = $connection->createCommand($sql);
            $result = $command->execute();
            if (count($records) > 0) {
                $sql = $connection->getQueryBuilder()->batchInsert('yii_vplanuser', ['p_id', 'u_id'], $records);
                $command = $connection->createCommand($sql);
                $result = $command->execute();
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            echo '{"msg":"failed"}';
        }
        echo '{"msg":"success"}';
    }

    public function actionGetcids() {
        $connection = Yii::$app->db;
        $data = Yii::$app->request->post();
        $did = $data["dept"];
        $plan = $data["plan"];
        $sql = "select cd_id from yii_vplancdept where p_id=$plan and d_id=$did";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        $ids = [];
        foreach ($result as $v) {
            array_push($ids, $v['cd_id']);
        }
        echo '{"msg":"success","sz":' . json_encode($ids) . '}';
    }

    public function actionGetplandepts() {
        $connection = Yii::$app->db;
        $data = Yii::$app->request->post();
        $plan = $data["plan"];
        $sql = "select d.* from yii_vdept d,yii_vplandept a where a.d_id=d.id and a.p_id=$plan";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        echo '{"msg":"success","sz":' . json_encode($result) . '}';
    }

    public function actionGetusers() {
        $connection = Yii::$app->db;
        $data = Yii::$app->request->post();
        $dept = $data["dept"];
        $zhiwu = $data["zhiwu"];
        $zhiji = $data["zhiji"];
        $name = $data["name"];
        $plan = $data["plan"];
        $sql = "select a.*,d.d_name as u_dept from yii_vuser a left join yii_vdept d on a.d_id=d.id where a.d_id in(select d_id from yii_vplandept where p_id=$plan) ";
        if ($name != "") {
            $sql = $sql . " and a.u_name like '" . $name . "%'";
        } else {
            if ($zhiwu != "") {
                $sql = $sql . " and a.u_zhiwu = '" . $zhiwu . "'";
            }
            if ($zhiji != "") {
                $sql = $sql . " and a.u_zhiji = '" . $zhiji . "'";
            }
            if ($dept != "" && $dept != 0) {
                $sql = $sql . " and a.d_id = " . $dept;
            }
        }
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        echo '{"msg":"success","sz":' . json_encode($result) . '}';
    }

    public function actionGetplanusers() {
        $connection = Yii::$app->db;
        $data = Yii::$app->request->post();
        $plan = $data["plan"];
        $sql = "select a.*,d.d_name as u_dept from yii_vuser a left join yii_vdept d on a.d_id=d.id,yii_vplanuser c where a.id=c.u_id and c.p_id=$plan ";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        echo '{"msg":"success","sz":' . json_encode($result) . '}';
    }

}
