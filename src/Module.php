<?php

namespace kvmanager;

use kvmanager\models\KeyValue;
use Yii;
use yii\base\BootstrapInterface;
use yii\i18n\PhpMessageSource;
use yii\web\Application;
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
        /** @var $app Application */
        $app->getUrlManager()->addRules([
            [
                'class'   => UrlRule::class,
                'route'   => $this->id . '/key-value/index',
                'pattern' => vsprintf('%s/<%s:[\w\-]+>/<%s:[\w\-]+>', [
                    $this->id,
                    KeyValue::$namespaceFieldName,
                    KeyValue::$groupFieldName,
                ]),
            ],
            [
                'class'   => UrlRule::class,
                'route'   => $this->id . '/key-value/<action>',
                'pattern' => vsprintf('%s/<%s:[\w\-]+>/<%s:[\w\-]+>/<action:(%s)>', [
                    $this->id,
                    KeyValue::$namespaceFieldName,
                    KeyValue::$groupFieldName,
                    implode('|', [
                        'create',
                        'update',
                        'delete',
                        'view',
                        'sync',
                        'clean-cache',
                    ]),
                ]),
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
