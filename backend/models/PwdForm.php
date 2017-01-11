<?php
namespace app\models;
use Yii;
use yii\base\Model;
use app\models\YiiUser;

class PwdForm extends Model{
    public  $opwd;
    public  $npwd;
    public  $qrpwd;

    public function rules(){

        return [
            [['npwd', 'qrpwd','opwd'], 'required','message'=>'{attribute}不能为空！']
        ];
    }


    /**
     * @
     */
    public function attributeLabels()
    {
        return [
            'opwd' => '旧密码：',
            'npwd' => '新密码：',
            'qrpwd'=>'确认密码：',
        ];
    }
}
?>