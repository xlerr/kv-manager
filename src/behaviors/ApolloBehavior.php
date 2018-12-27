<?php

namespace kvmanager\behaviors;

use apollo\Apollo;
use kvmanager\models\KeyValue;
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
     * @param string $method
     *
     * @throws \yii\base\UserException
     */
    private function syncToApollo($method)
    {
        /** @var KeyValue $model */
        $model = $this->owner;

        // 这个配置不需要同步到Apollo
        if ($model->key_value_key === 'apollo_config') {
            return;
        }

        $config = KeyValue::getValue('apollo_config', true);

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
}
