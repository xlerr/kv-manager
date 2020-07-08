kv-manager
========

### Example

```shell
./yii migrate --migrationPath=@vendor/xlerr/kvmanager/src/migrations
```

```php
'bootstrap' => ['key-value'], // 重写路由，主要用于兼容以前的地址
'modules' => [
    'key-value' => \kvmanager\Module::class,
],
'components' => [
    NacosComponent::componentName() => function () {
        $config = KeyValue::take(NacosComponent::CONFIG_KEY);

        return new NacosComponent([
            'baseUri' => $config['baseUri'] ?? null,
        ]);
    },
],
```

```php
KeyValue::take('config_key', KeyValue::TAKE_FORMAT_ARRAY);
```
