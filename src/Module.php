<?php

namespace kvmanager;

use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = __NAMESPACE__ . '\controllers';

    public $defaultRoute = 'key-value';

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        /** @var $app \yii\web\Application */
        $app->getUrlManager()->addRules([
            [
                'class'   => 'yii\web\UrlRule',
                'route'   => $this->id . '/key-value/<action>',
                'pattern' => $this->id . '/<action:[\w\-]+>',
                'suffix'  => false,
            ],
        ], false);
    }
}
