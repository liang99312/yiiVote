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
class Vdept extends ActiveRecord {

	/**
	 * @inheritdoc
	 */
	

	public static function tableName() {
		return '{{%vdept}}';
	}

	public function rules() {
		return [
		    [['d_name','d_type'], 'required', 'message' => '内容不能为空！'],
		    ['d_remark', 'string', 'max' => 200,'tooLong'=>'{attribute}长度必需在200以内'],
		    ['d_code', 'string', 'max' => 20,'tooLong'=>'{attribute}长度必需在20以内'],
		];
	}

	public function attributeLabels() {
		return [
		    'id' => 'ID',
		    'd_name' => '部门名称：',
		    'd_remark' => '备注：',
                    'd_type' => '部门分类：',
		    'd_code' => '编号：'
		];
	}

	public static function getVdepts() {
		return parent::find()->orderBy('d_code')->All();
	}

}
