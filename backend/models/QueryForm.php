<?php
namespace app\models;
use Yii;
use yii\base\Model;
use app\models\YiiUser;

class QueryForm extends Model{
    public $plan;
    public $dept;
    public $user_id;
    public $name;

    public function rules(){

        return [
            [['dept','plan'], 'required','message'=>'{attribute}不能为空！'],
	    ['name', 'string', 'max' => 50,'tooLong'=>'{attribute}长度必需在100以内'],
        ];
    }


    /**
     * @
     */
    public function attributeLabels()
    {
        return [
            'plan' => '评测计划：',
            'dept' => '部门选择：',
	    'name'=>'被评人：'
        ];
    }
}
?>