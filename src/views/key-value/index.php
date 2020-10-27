<?php

use kvmanager\components\NacosComponent;
use kvmanager\models\KeyValue;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */
/* @var $searchModel KeyValue */

$this->title = Yii::t('kvmanager', 'Key Value');

$this->params['breadcrumbs'][] = $this->title;

echo $this->render('_search', [
    'model' => $searchModel,
]);

echo GridView::widget([
    'tableOptions' => [
        'class' => 'table table-hover',
    ],
    'options'      => [
        'class' => 'box box-primary',
    ],
    'pager'        => [
        'options' => [
            'class' => 'pagination pagination-sm no-margin pull-right',
        ],
    ],
    'layout'       => '<div class="box-header with-border">{summary}</div><div class="box-body table-responsive no-padding">{items}</div><div class="box-footer">{pager}</div>',
    'dataProvider' => $dataProvider,
    'columns'      => [
        [
            'class'          => 'yii\grid\ActionColumn',
            'template'       => '{view} {update} {delete}',
            'visibleButtons' => [
                'sync' => function (KeyValue $model) {
                    return Yii::$app->getUser()->can('KV_SYNC');
                },
            ],
            'buttons'        => [
                'view'   => function ($url, KeyValue $model) {
                    return Html::a(Yii::t('kvmanager', 'View'), $url);
                },
                'update' => function ($url, KeyValue $model) {
                    return Html::a(Yii::t('kvmanager', 'Update'), $url);
                },
                'delete' => function ($url, KeyValue $model) {
                    return Html::a(Yii::t('kvmanager', 'Delete'), $url, [
                        'data' => [
                            'method'  => 'post',
                            'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        ],
                    ]);
                },
            ],
        ],
        'key',
        [
            'attribute' => 'type',
            'format'    => ['in', KeyValue::typeList()],
        ],
        [
            'attribute' => 'namespace',
            'value'     => function (KeyValue $model) {
                $config = KeyValue::take(NacosComponent::CONFIG_KEY);

                $config = (array)($config['namespace'] ?? []);

                return ($config[$model->namespace]['label'] ?? $model->namespace) . ' (' . $model->namespace . ')';
            },
        ],
        'group',
        [
            'attribute' => 'value',
            'format'    => 'raw',
            'value'     => function (KeyValue $model) {
                $content = preg_replace('/\s+/', '', $model->value);
                $content = htmlentities(StringHelper::truncate($content, 30));

                return Html::tag('span', $content, [
                    'title' => $model->value,
                ]);
            },
        ],
        'memo:ntext',
        [
            'label'     => '修改者',
            'attribute' => 'operator.username',
        ],
        'updated_at',
    ],
]);
