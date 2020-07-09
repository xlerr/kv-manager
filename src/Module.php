<?php

namespace kvmanager;

use Yii;
use yii\base\BootstrapInterface;
use yii\i18n\PhpMessageSource;
use yii\web\UrlRule;

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
                'class'   => UrlRule::class,
                'route'   => $this->id . '/key-value/index',
                'pattern' => $this->id . '/<key_value_namespace:[\w\-]+>/<key_value_group:[\w\-]+>',
            ],
            [
                'class'   => UrlRule::class,
                'route'   => $this->id . '/key-value/<action>',
                'pattern' => $this->id . '/<key_value_namespace:[\w\-]+>/<key_value_group:[\w\-]+>/<action:(create|update|delete|view|sync|clean-cache)>',
            ],
        ], false);
    }

    public function init()
    {
        if (!isset(Yii::$app->i18n->translations['kvmanager'])) {
            Yii::$app->i18n->translations['kvmanager'] = [
                'class'            => PhpMessageSource::class,
                'forceTranslation' => true,
                'basePath'         => '@vendor/xlerr/kvmanager/src/messages',
            ];
        }
    }
}
