<?php
namespace app\models;
use Yii;
use yii\base\Model;
use app\models\YiiUser;

class LoginForm extends Model{
    public  $user;
    public  $pwd;
    public  $verifyCode;

    public function rules(){

        return [
            [['user', 'pwd','verifyCode'], 'required','message'=>'{attribute}不能为空！'],
            ['user', 'string', 'max' => 50,'tooLong'=>'{attribute}长度必需在100以内'],
            ['pwd', 'string', 'max' => 32,'tooLong'=>'{attribute}长度必需在32以内'],
	    ['verifyCode', 'captcha','captchaAction'=>'site/captcha','message'=>'验证码不正确']
        ];
    }


    /**
     * @
     */
    public function attributeLabels()
    {
        return [
            'user' => '注册账号:',
            'pwd' => '密码：',
            'verifyCode'=>'验证码：',
        ];
    }
}
?>