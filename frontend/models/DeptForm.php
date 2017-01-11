<?php
namespace app\models;
use Yii;
use yii\base\Model;
use app\models\YiiUser;

class DeptForm extends Model{
    public $plan = -1;
    public $dept = -1;
    public $user_id;

    public function rules(){

        return [
            [['dept','plan'], 'required','message'=>'{attribute}不能为空！']
        ];
    }


    /**
     * @
     */
    public function attributeLabels()
    {
        return [
	    'plan' => '评测计划：',
            'dept' => '部门选择：'
        ];
    }
}
?>