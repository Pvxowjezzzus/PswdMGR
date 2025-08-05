<?php

namespace app\modules\admin;

use yii\filters\AccessControl;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{

    public $layout = '@app/modules/admin/views/layouts/main';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['*'],  // Ограничить доступ ко всем действиям
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['Администраторы'],  // Разрешить только роли Администраторы
                    ],
                    [
                        'allow' => false,  // Блокировка для всех остальных
                    ],
                ],
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
