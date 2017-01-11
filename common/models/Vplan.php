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
class Vplan extends ActiveRecord {

	/**
	 * @inheritdoc
	 */
	

	public static function tableName() {
		return '{{%vplan}}';
	}

	public function rules() {
		return [
		    [['p_name'], 'required', 'message' => '内容不能为空！'],
		    ['p_aflag', 'string', 'max' => 32,'tooLong'=>'{attribute}长度必需在32以内'],
		];
	}

	public function attributeLabels() {
		return [
		    'id' => 'ID',
		    'p_name' => '评测计划：',
		    'p_aflag' => '是否全员参与：',
		    'p_state' => '状态：'
		];
	}

	public static function getVusers() {
		return parent::find()->oneAll();
	}

}
