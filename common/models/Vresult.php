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
class Vresult extends ActiveRecord {

	/**
	 * @inheritdoc
	 */
	

	public static function tableName() {
		return '{{%vresult}}';
	}

	public function rules() {
		return [
		    [['u_name', 'u_code', 'u_dept','d3','d4','d5','d6','d7','d8','d9','d10','zongf'], 'required', 'message' => '内容不能为空！']
		];
	}

	public function attributeLabels() {
		return [
		    'id' => 'ID',
		    'u_name' => '姓名：',
		    'u_code' => '编号：',
                    'u_dept' => '部门：'
		];
	}
}
