kv-manager
========

### Example

```php
'bootstrap' => ['key-value'], // 重写路由，主要用于兼容以前的地址
'modules' => [
    'key-value' => [
        'class' => \kvmanager\Module::class,
    ],
],
```