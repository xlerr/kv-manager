<?php

namespace kvmanager\rules;

use kvmanager\components\NacosComponent;
use kvmanager\models\KeyValue;
use yii\rbac\Rule;

/**
 * RouteRule Rule for check route with extra params.
 */
class RouteRule extends Rule
{
    const RULE_NAME = 'route_rule';

    /**
     * @inheritdoc
     */
    public $name = self::RULE_NAME;

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        $config = KeyValue::getAvailable();
        if (!is_array($config)) {
            $config = KeyValue::take(NacosComponent::CONFIG_KEY);

            $config = (array)($config['namespace'] ?? []);

            foreach ($config as $namespace => &$option) {
                if (!isset($item->data[$namespace])) {
                    unset($config[$namespace]);
                    continue;
                }

                $option = (array)($option['group'] ?? []);

                if ($item->data[$namespace] === '*' || in_array('*', $item->data[$namespace])) {
                    continue;
                }

                $option = array_intersect($option, $item->data[$namespace]);

                if (empty($option)) {
                    unset($config[$namespace]);
                    continue;
                }
            }

            KeyValue::setAvailable($config);
        }

        return !empty($config);
    }
}
