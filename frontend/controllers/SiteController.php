<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Vuser;
use app\models\LoginForm;

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
		$model = new LoginForm();
		return $this->render('index', ['model' => $model]);
	}
	
	public function actionLogin() {
		$depts = [];
		$sql = "select * from yii_vdept order by d_code";
		$connection = Yii::$app->db;
		$command = $connection->createCommand($sql);
		$result = $command->queryAll();
		foreach ($result as $v) {
			$depts[$v['id']] = $v['d_name'];
		}
		return $this->render('login', ['depts' => $depts]);
	}

	public function actionMlogin() {
		$model = new \app\models\LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$sql = "select * from yii_user where user='" . $model->user . "'";
			$connection = Yii::$app->db;
			$command = $connection->createCommand($sql);
			$result = $command->queryOne();
			if ($result) {
				if ($model->pwd != $result['pwd']) {
					Yii::$app->session->setFlash('error', '密码错误！');
					return $this->render('index', ['model' => $model]);
				}
				$session = Yii::$app->session;
				$session->set('user_id', $result['id']);
			} else {
				$sql = "insert yii_user(user,pwd) values('" . $model->user . "','" . $model->pwd . "')";
				$command = $connection->createCommand($sql);
				$result = $command->execute();
				if ($result) {
					$sql = "select * from yii_user where user='" . $model->user . "'";
					$command = $connection->createCommand($sql);
					$result = $command->queryOne();
					$session = Yii::$app->session;
					$session->set('user_id', $result['id']);
				} else {
					Yii::$app->session->setFlash('error', '注册失败！');
					return $this->render('index', ['model' => $model]);
				}
			}
		}
		return $this->redirect(['site/vote']);
	}
	
	public function actionNmlogin() {
                $ip = Yii::$app->request->userIP;
                $stime = strtotime("-60 seconds");
		$model = new \app\models\LoginForm();
                $sql = "select id from yii_user where thumb='$ip' and updated_at >= $stime";
		$connection = Yii::$app->db;
		$command = $connection->createCommand($sql);
		$result = $command->queryOne();
                if ($result) {
                    echo '{"msg":"error_relogin"}';
                }else{
                    $sql = "select max(id)+1 as id from yii_user";
                    $command = $connection->createCommand($sql);
                    $result = $command->queryOne();
                    if ($result) {
                            $id = $result['id'];
                            $sql = "insert yii_user(user,pwd,thumb) values('" . $id . "','" . $id . "','" . $ip . "')";
                            $command = $connection->createCommand($sql);
                            $result = $command->execute();
                            $session = Yii::$app->session;
                            $session->set('user_id', $id);
                            echo '{"msg":"success"}';
                    } else {
                            echo '{"msg":"error"}';
                    }
                }
	}
	
	public function actionDlogin() {
		$data = Yii::$app->request->post();
		$dept = $data["dept"];
                $ip = Yii::$app->request->userIP;
                $stime = strtotime("-60 seconds");
		$model = new \app\models\LoginForm();
                $sql = "select id from yii_user where thumb='$ip' and updated_at >= $stime";
		$connection = Yii::$app->db;
		$command = $connection->createCommand($sql);
		$result = $command->queryOne();
                if ($result) {
                    echo '{"msg":"error_relogin"}';
                }else{
                    $sql = "select max(id)+1 as id from yii_user";
                    $command = $connection->createCommand($sql);
                    $result = $command->queryOne();
                    if ($result) {
                            $id = $result['id'];
                            $sql = "insert yii_user(user,pwd,thumb) values('" . $id . "','" . $id . "','" . $ip . "')";
                            $command = $connection->createCommand($sql);
                            $result = $command->execute();
                            $session = Yii::$app->session;
                            $session->set('user_id', $id);
			    $session->set('dept_id', $dept);
                            echo '{"msg":"success"}';
                    } else {
                            echo '{"msg":"error"}';
                    }
                }
	}
	
	public function actionGetplandepts(){
		$session = Yii::$app->session;
		$dept_id = $session->get('dept_id');
		$connection = Yii::$app->db;
		$data = Yii::$app->request->post();
                $plan = $data["plan"];
		$vplan = new \common\models\Vplan();
		$vplan = $vplan->find()->where(["id" => $plan])->one();
		$result = [];
		if("是" == $vplan->p_aflag){
			$sql = "select d.* from yii_vdept d,yii_vplandept a where a.d_id=d.id and a.p_id=$plan order by d.d_code";
			$command = $connection->createCommand($sql);
			$result = $command->queryAll();
		}else{
			$sql = "select d.* from yii_vdept d,yii_vplancdept a where a.cd_id=$dept_id and a.d_id=d.id and a.p_id=$plan order by d.d_code";
			$command = $connection->createCommand($sql);
			$result = $command->queryAll();
		}
		echo '{"msg":"success","p_aflag":"'.$vplan->p_aflag.'","sz":'.  json_encode($result).'}';
	}
        
        public function actionGetplanusers(){
		$session = Yii::$app->session;
                $user_id = $session->get('user_id');
		$dept_id = $session->get('dept_id');
		$connection = Yii::$app->db;
		$data = Yii::$app->request->post();
                $plan = $data["plan"];
                $dept = $data["dept"];
		$vplan = new \common\models\Vplan();
                $vrecord = new \common\models\Vrecord;
                $vplan = $vplan->find()->where(["id" => $plan])->one();
                $vusers = [];
                $tvrecords = [];
                $vrecords=[];
                $usql = "";
                if("是" == $vplan->p_aflag){
                        $usql = "select a.*,d.d_name as u_dept from yii_vuser a,yii_vplanuser b,yii_vdept d where a.d_id=d.id and a.id = b.u_id and b.p_id=$plan";
                        if($dept != "0" && $dept != 0){
                                $usql = $usql.' and a.d_id='.$dept;
                        }
                }else{
                        if($dept != "0" && $dept != 0){
                                $usql = "select a.*,d.d_name as u_dept from yii_vuser a,yii_vplanuser b,yii_vdept d where a.d_id=$dept and a.d_id=d.id and a.id = b.u_id and b.p_id=$plan";
                        }else{
                                $usql = "select a.*,d.d_name as u_dept from yii_vuser a,yii_vplanuser b,yii_vdept d where a.d_id in(select d_id from yii_vplancdept where p_id=$plan and cd_id=$dept_id) and a.d_id=d.id and a.id = b.u_id and b.p_id=$plan";
                        }
                }
                $usql = $usql." order by a.u_code";
                $command = $connection->createCommand($usql);
                $vusers = $command->queryAll();
                if($dept != "0" && $dept != 0){
                        $sql = "select * from yii_vrecord where p_id=$plan and d_id=$dept and user_id=$user_id";
                        $command = $connection->createCommand($sql);
                        $tvrecords = $command->queryAll();
                }else{
                        $sql = "select * from yii_vrecord where p_id=$plan and user_id=$user_id";
                        $command = $connection->createCommand($sql);
                        $tvrecords = $command->queryAll();
                }
                foreach ($vusers as $v){
                        $temp_r = [];
                        $temp_r['u_id']=$v["id"];
                        $temp_r['u_code']=$v["u_code"];
                        $temp_r['u_name']=$v["u_name"];
                        $temp_r['u_dept']=$v["u_dept"];
                        $temp_r['d_id']=$v["d_id"];
                        for($i =3;$i < 11;$i++){
                                $temp_r['d'.$i]="";
                        }
                        $temp_r['yijian']="";
                        foreach ($tvrecords as $r){
                                if($r['u_id']==$v['id']){
                                        for($i =3;$i < 11;$i++){
                                                $temp_r['d'.$i]=$r['d'.$i];
                                        }
                                        $temp_r['yijian']=$r['yijian'];
                                        break;
                                }
                        }
                        array_push($vrecords, $temp_r);
                }
		echo '{"msg":"success","p_aflag":"'.$vplan->p_aflag.'","sz":'.  json_encode($vrecords).'}';
	}

	public function actionVote() {
                $session = Yii::$app->session;
		$user_id = $session->get('user_id');
		$dept_id = $session->get('dept_id');
                if($user_id == null || "" == $user_id){
                    return $this->redirect(['site/logout']);
                }
		$plans = [];
		$sql = "select id,p_name from yii_vplan where p_state='进行中' and p_aflag='是' order by p_date desc";
		$connection = Yii::$app->db;
		$command = $connection->createCommand($sql);
		$result = $command->queryAll();
		foreach ($result as $v) {
			$plans[$v['id']] = $v['p_name'];
		}
		if($dept_id != null && ""!=$dept_id){
			$sql = "select id,p_name from yii_vplan where p_state='进行中' and p_aflag='否' and id in(select p_id from yii_vplancdept where cd_id =$dept_id) order by p_date desc";
			$command = $connection->createCommand($sql);
			$result = $command->queryAll();
			foreach ($result as $v) {
				$plans[$v['id']] = $v['p_name'];
			}
		}
		return $this->render('vote', ['plans' => $plans]);
	}

	public function actionLogout() {
		$session = Yii::$app->session;
		if($session->get('dept_id')==null || $session->get('dept_id')==""){
			$session->destroy();
			return $this->goHome();
		}else{
			$session->destroy();
			return $this->redirect('/yiiVote/dindex.php');
		}
	}

	public function actionSample() {
		$connection = Yii::$app->db;
		$session = Yii::$app->session;
		$user_id = $session->get('user_id');
                if($user_id == null || "" == $user_id){
                    return $this->redirect(['site/logout']);
                }
		$data = Yii::$app->request->post();
		$sz = $data["sz"];
		$dept = $data["dept"];
		$plan = $data["plan"];
		$records = [];
		foreach ($sz as $v) {
			$flag = $v['flag'];
			if ($flag == 1) {
				$u_id = $v['u_id'];
				$u_name = $v['u_name'];
				$u_dept = $v['u_dept'];
                                $d_id = $v['d_id'];
				$u_code = $v['u_code'];
				$p_id = $plan;
				$d3 = $v['d3'];
				$d4 = $v['d4'];
				$d5 = $v['d5'];
				$d6 = $v['d6'];
				$d7 = $v['d7'];
				$d8 = $v['d8'];
				$d9 = $v['d9'];
				$d10 = $v['d10'];
				$yijian = $v['yijian'];
				$temp_obj = [ $user_id, $u_id, $u_name, $u_dept, $d_id, $u_code, $p_id, $d3, $d4, $d5, $d6, $d7, $d8, $d9, $d10,$yijian];
				array_push($records, $temp_obj);
			}
		}
		
		$transaction=$connection->beginTransaction();
		try {
                        if($dept != "0" && $dept != 0 && $dept != ""){
                            $sql = "delete from yii_vrecord where d_id='$dept' and p_id=$plan and user_id=$user_id";
                        }else{
                            $sql = "delete from yii_vrecord where p_id=$plan and user_id=$user_id";
                        }
			$command = $connection->createCommand($sql);
			$result = $command->execute();
			if(count($records) > 0){
				$sql = $connection->getQueryBuilder()->batchInsert('yii_vrecord', ['user_id', 'u_id', 'u_name', 'u_dept', 'd_id', 'u_code', 'p_id', 'd3', 'd4', 'd5', 'd6', 'd7', 'd8', 'd9', 'd10','yijian'], $records);
				$command = $connection->createCommand($sql);
				$result = $command->execute();
			}
			$time = strtotime('now');
			$sql = "update yii_user set updated_at=$time where id=$user_id";
			$command = $connection->createCommand($sql);
			$result = $command->execute();
			$transaction->commit();
		} catch (Exception $e) {
		    $transaction->rollBack();
		    echo '{"msg":"failed"}';
		}
		
		echo '{"msg":"success"}';
	}

}
