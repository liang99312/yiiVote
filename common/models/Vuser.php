<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $user
 * @property string $pwd
 */
class Vuser extends ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public $u_dept;
        

	public static function tableName() {
		return '{{%vuser}}';
	}

	public function rules() {
		return [
		    [['u_name', 'u_code', 'd_id'], 'required', 'message' => '内容不能为空！'],
                    ['u_zhiwu', 'string', 'max' => 50,'tooLong'=>'{attribute}长度必需在50以内'],
                    ['u_zhiji', 'string', 'max' => 50,'tooLong'=>'{attribute}长度必需在50以内'],
		];
	}

	public function attributeLabels() {
		return [
		    'id' => 'ID',
		    'u_name' => '姓名：',
		    'u_code' => '编号：',
                    'u_zhiwu' => '职务：',
                    'u_zhiji' => '职级：',
                    'd_id' => '部门：'
		];
	}

	public static function getVusers() {
		return parent::find()->All();
	}

}
