<?php

namespace kvmanager;

use Yii;
use yii\base\BootstrapInterface;
use yii\i18n\PhpMessageSource;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $tableName = '{{%key_value}}';

    public $controllerNamespace = __NAMESPACE__ . '\controllers';

    public $defaultRoute = 'key-value';

    /**
     * @var null|string|array
     */
    public $apollo;

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
