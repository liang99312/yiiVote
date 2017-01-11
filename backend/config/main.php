<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
	'assetManager' => [
            'basePath' => '@webroot/backend/web/assets',
            'baseUrl' => '@web/backend/web/assets'
        ],
        'user' => [
            'identityClass' => 'app\models\YiiUser',//模型自动登录
            'enableAutoLogin' => true,
            'loginUrl'=>['site/login'],//定义后台默认登录界面[权限不足跳到该页]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
