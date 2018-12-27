<?php

namespace kvmanager\behaviors;

use apollo\Apollo;
use kvmanager\models\KeyValue;
use kvmanager\Module;
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * 同步到配置中心行为类
 */
class ApolloBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => function () {
                $this->syncToApollo('create');
            },
            ActiveRecord::EVENT_AFTER_UPDATE => function () {
                $this->syncToApollo('update');
            },
            ActiveRecord::EVENT_AFTER_DELETE => function () {
                $this->syncToApollo('delete');
            },
        ];
    }

    /**
     * @param $method
     *
     * @throws \Exception
     */
    private function syncToApollo($method)
    {
        /** @var KeyValue $model */
        $model = $this->owner;

        $config = $this->getApolloConfig();
        if (null === $config) {
            return;
        }

        $apollo = new Apollo($config->baseUri);

        $apollo->token      = $config->token;
        $apollo->user       = $config->user;
        $apollo->envs       = $config->envs;
        $apollo->apps       = $config->apps;
        $apollo->clusters   = $config->clusters;
        $apollo->namespaces = $config->namespaces;

        switch ($method) {
            case 'create':
                $response = $apollo->create($model->key_value_key, $model->key_value_value, $model->key_value_memo);
                break;
            case 'update':
                $response = $apollo->update($model->key_value_key, $model->key_value_value, $model->key_value_memo);
                break;
            case 'delete':
                $response = $apollo->delete($model->key_value_key);
                break;
            default:
                throw new \Exception('未知操作');
        }

        $comment  = vsprintf('配置更新后发布:%s, 更新时间:%s', [
            $model->key_value_key,
            date('Y-m-d H:i:s'),
        ]);
        $response = $apollo->releases($comment, $comment);
    }

    private function getApolloConfig()
    {
        $config = null;

        $modules = Yii::$app->getModules(true);
        foreach ($modules as $module) {
            if ($module instanceof Module) {
                /** @var Module $module */
                /** @var null|string|array $config */
                $config = $module->apollo;
                break;
            }
        }

        if (null === $config) {
            return null;
        }

        if (is_string($config)) {
            /** @var KeyValue $model */
            $model = $this->owner;
            if ($config === $model->key_value_key) {
                // 编辑Apollo配置时不需要同步
                return null;
            }
            $config = KeyValue::getValueAsArray($config, true);
        }

        $config = array_merge([
            'baseUri'    => null,
            'token'      => null,
            'user'       => null,
            'envs'       => null,
            'apps'       => null,
            'clusters'   => 'default',
            'namespaces' => 'application',
        ], (array)$config);

        return (object)$config;
    }
}
