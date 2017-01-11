<?php
namespace app\models;
use Yii;
use yii\base\Model;

class ImportForm extends Model{
	
    public $c_file;

    public function rules(){

        return [
	    [['c_file'], 'file']
        ];
    }


    /**
     * @
     */
    public function attributeLabels()
    {
        return [
            'c_file' => 'Excel文件：'
        ];
    }
}
?>