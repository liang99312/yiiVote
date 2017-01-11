<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Vuser;
use app\models\QueryForm;
use app\models\ImportForm;
use common\models\Vdept;
use yii\web\UploadedFile;
use yii\base\Exception;
use backend\utils\ExcelToArrary;

/**
 * Site controller
 */
class VuserController extends Controller {

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
                        'actions' => ['index', 'addvuser', 'editvuser', 'delvuser', 'vuser', 'importexcel', 'exceloutput'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $imodel = new ImportForm();
        $model = new QueryForm();
        $vuser = new Vuser();
        $count = 0;
        $depts = [];
        $page = null;
        $msgs = null;
        $condition = "1=1";
        $tmpdept = "";
        $tmpname = "";
        if ($model->load(Yii::$app->request->post())) {
            $tmpdept = $model->dept;
            $tmpname = $model->name;
        } else {
            if (Yii::$app->session->hasFlash('dept_u_q')) {
                $tmpdept = Yii::$app->session->getFlash('dept_u_q');
                $model->dept = $tmpdept;
            }
            if (Yii::$app->session->hasFlash('name_u_q')) {
                $tmpname = Yii::$app->session->getFlash('name_u_q');
                $model->name = $tmpname;
            }
        }
        if ($tmpdept != "" && "全部" != $tmpdept && 0 != $tmpdept) {
            $condition .= " and a.d_id='" . $tmpdept . "'";
        }
        Yii::$app->session->setFlash('dept_u_q', $tmpdept);
        if ($tmpname != "") {
            $condition .= " and a.u_name like '" . $tmpname . "%'";
        }
        Yii::$app->session->setFlash('name_u_q', $tmpname);

        $connection = Yii::$app->db;
        $sql = "select 1 from yii_vuser a left join yii_vdept d on a.d_id = d.id where " . $condition;
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();

        $dsql = "select a.*,d.d_name as u_dept from yii_vuser a left join yii_vdept d on a.d_id = d.id where " . $condition;
        $count = count($result);
        $page = new Pagination(['defaultPageSize' => 10, 'totalCount' => $count]);
        $msgs = $connection->createCommand($dsql . " limit " . $page->limit . " offset " . $page->offset . "")->queryAll();
        if (count($this->adepts) == 0) {
            $this->adepts = Vdept::getVdepts();
        }
        $depts['0'] = "全部";
        foreach ($this->adepts as $v) {
            $depts[$v->id] = $v->d_name;
        }

        return $this->render('index', ['page' => $page, 'msgs' => $msgs, 'depts' => $depts, 'model' => $model, 'imodel' => $imodel]);
    }

    public function actionAddvuser() {
        $model = new Vuser();
        $depts = [];
        if (count($this->adepts) == 0) {
            $this->adepts = Vdept::getVdepts();
        }
        foreach ($this->adepts as $v) {
            $depts[$v->id] = $v->d_name;
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '保存成功！');
            } else {
                Yii::$app->session->setFlash('error', '保存失败！');
            }
        }
        return $this->render('addvuser', ['model' => $model, 'depts' => $depts]);
    }

    public function actionEditvuser($id) {
        $model = new Vuser();
        $depts = [];
        if (count($this->adepts) == 0) {
            $this->adepts = Vdept::getVdepts();
        }
        foreach ($this->adepts as $v) {
            $depts[$v->id] = $v->d_name;
        }
        $model = $model->find()->where(["id" => $id])->one();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '保存成功！');
            } else {
                Yii::$app->session->setFlash('error', '保存失败！');
            }
        }
        return $this->render('editvuser', ['model' => $model, 'depts' => $depts]);
    }

    public function actionDelvuser($id) {
        $model = new Vuser();
        $model = $model->find()->where(["id" => $id])->one();
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', '删除成功！');
        } else {
            Yii::$app->session->setFlash('error', '删除失败！');
        }
        return $this->redirect(['vuser/index']);
    }

    public function actionExceloutput() {
        return $this->redirect('/yiiVote/被评人导入示例表.xls');
    }

    public function actionImportexcel() {
        $model = new ImportForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $cfile = UploadedFile::getInstance($model, "c_file");
            if ($cfile) {
                $ext = $cfile->extension;
                $cfileName = time() . rand(100, 999) . '.' . $ext;
                if (strtolower($ext) != "xls") {
                    Yii::$app->session->setFlash('error', '不是Excel文件，重新上传');
                } else {
                    if ($cfile->saveAs("backend/excelfiles/" . $cfileName)) {
                        $res = ExcelToArrary::read("backend/excelfiles/" . $cfileName);
                        /*
                          重要代码 解决Thinkphp M、D方法不能调用的问题
                          如果在thinkphp中遇到M 、D方法失效时就加入下面一句代码
                         */
                        //spl_autoload_register ( array ('Think', 'autoload' ) );
                        /* 对生成的数组进行数据库的写入 */
                        $umodel = new Vuser();
                        $vuses = $umodel->find()->all();
                        $codes = [];
                        foreach ($vuses as $v) {
                            $codes[$v->id] = $v->u_code;
                        }
                        if (count($this->adepts) == 0) {
                            $this->adepts = Vdept::getVdepts();
                        }
                        $depts = [];
                        foreach ($this->adepts as $v) {
                            $depts[$v->id] = $v->d_name;
                        }
                        $ndept = "";
                        $index = false;
                        $transaction = Yii::$app->db->beginTransaction();
                        try {
                            foreach ($res as $k => $v) {
                                if ($k != 0) {
                                    $user = new Vuser();
                                    $user->u_code = $v[0];
                                    $user->u_name = $v[1];
                                    $key = array_search($v[2], $depts);
                                    if (!$key) {
                                        $ndept = $ndept . $v[2] . ' ';
                                        continue;
                                    } else {
                                        $user->d_id = $key;
                                    }
                                    $user->u_zhiwu = $v[3]==null? '':$v[3];
                                    $user->u_zhiji = $v[4]==null? '':$v[4];
                                    $index = array_search($user->u_code, $codes);
                                    $result = false;
                                    $sql = "";
                                    if (!$index) {
                                        $sql = "insert into yii_vuser(u_code,u_name,d_id,u_zhiwu,u_zhiji) values ('$user->u_code','$user->u_name',$user->d_id,'$user->u_zhiwu','$user->u_zhiji')";
                                    } else {
                                        $user->id = $index;
                                        $sql = "update yii_vuser set u_zhiwu='$user->u_zhiwu',u_zhiji='$user->u_zhiji',u_name='$user->u_name',d_id=$user->d_id where id=" . $index;
                                        
                                    }
                                    Yii::$app->db->createCommand($sql)->execute();
                                }
                            }
                            $transaction->commit();
                            if ("" != $ndept) {
                                Yii::$app->session->setFlash('success', '部门为“' . $ndept . '”的人员没有导入，其他的导入成功！');
                            } else {
                                Yii::$app->session->setFlash('success', '导入成功！');
                            }
                        } catch (Exception $e) {
                            Yii::$app->session->setFlash('error', '导入Excel失败！');
                            $transaction->rollBack();
                        }
                    } else {
                        Yii::$app->session->setFlash('error', '上传Excel失败！');
                    }
                }
            } else {
                Yii::$app->session->setFlash('error', '请选择Excel文件！');
            }
        } else {
            Yii::$app->session->setFlash('error', '请重新导入！');
        }
        return $this->redirect(['vuser/index']);
    }

}
