<?php

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
$this->params['breadcrumbs'][] = $searchModel->key_value_namespace;
$this->params['breadcrumbs'][] = $searchModel->key_value_group;

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
            'template'       => '{sync} {view} {update} {delete}',
            'visibleButtons' => [
                'sync' => function (KeyValue $model) {
                    return Yii::$app->getUser()->can('KV_SYNC');
                },
            ],
            'buttons'        => [
                'sync'   => function ($url, KeyValue $model) {
                    return Html::a(Yii::t('kvmanager', 'Sync'), [
                        'sync',
                        'key_value_namespace' => $model->key_value_namespace,
                        'key_value_group'     => $model->key_value_group,
                        'id'                  => $model->key_value_id,
                    ], [
                        'data' => [
                            'method' => 'post',
                        ],
                    ]);
                },
                'view'   => function ($url, KeyValue $model) {
                    return Html::a(Yii::t('kvmanager', 'View'), [
                        'view',
                        'key_value_namespace' => $model->key_value_namespace,
                        'key_value_group'     => $model->key_value_group,
                        'id'                  => $model->key_value_id,
                    ]);
                },
                'update' => function ($url, KeyValue $model) {
                    return Html::a(Yii::t('kvmanager', 'Update'), [
                        'update',
                        'key_value_namespace' => $model->key_value_namespace,
                        'key_value_group'     => $model->key_value_group,
                        'id'                  => $model->key_value_id,
                    ]);
                },
                'delete' => function ($url, KeyValue $model) {
                    return Html::a(Yii::t('kvmanager', 'Delete'), [
                        'delete',
                        'key_value_namespace' => $model->key_value_namespace,
                        'key_value_group'     => $model->key_value_group,
                        'id'                  => $model->key_value_id,
                    ], [
                        'data' => [
                            'method'  => 'post',
                            'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        ],
                    ]);
                },
            ],
        ],
        'key_value_key',
        [
            'attribute' => 'key_value_type',
            'format'    => ['in', KeyValue::typeList()],
        ],
        [
            'attribute' => 'key_value_value',
            'format'    => 'raw',
            'value'     => function (KeyValue $model) {
                $content = preg_replace('/\s+/', '', $model->key_value_value);
                $content = htmlentities(StringHelper::truncate($content, 30));

                return Html::tag('span', $content, [
                    'title' => $model->key_value_value,
                ]);
            },
        ],
        'key_value_memo:ntext',
        'key_value_create_at',
        'key_value_update_at',
    ],
]);
