kv-manager
========

### Example

```shell
./yii migrate --migrationPath=@vendor/xlerr/kvmanager/src/migrations
```

```php
'bootstrap' => ['key-value'], // 重写路由，主要用于兼容以前的地址
'modules' => [
    'key-value' => [
        'class' => \kvmanager\Module::class,
        /**
         * null: 关闭同步
         * string: 配置从KV读取
         * array:
         */
        'appole' => [
            'baseUri'    => 'http://domain.com/',
            'token'      => 'security key',
            'user'       => 'apollo',
            'envs'       => 'DEV',
            'apps'       => 'test',
            'clusters'   => 'default', 
            'namespaces' => '',
        ],
    ],
],
```

```php
KeyValue::take('config_key', KeyValue::TAKE_FORMAT_ARRAY);
```
