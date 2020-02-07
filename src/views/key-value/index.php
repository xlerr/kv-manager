<?php

use kvmanager\models\KeyValue;
use Stringy\StaticStringy;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel KeyValue */

$this->title                   = Yii::t('kvmanager', 'Key Value');
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin([
    'timeout' => 10000,
]);

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
        ['class' => 'yii\grid\ActionColumn'],
        'key_value_key',
        [
            'attribute' => 'key_value_value',
            'format'    => 'raw',
            'value'     => function (KeyValue $model) {
                return Html::tag('div', StaticStringy::safeTruncate($model->key_value_value, 30, '...'), [
                    'title' => $model->getFormattedValue(),
                ]);
            },
        ],
        'key_value_memo:ntext',
        [
            'attribute' => 'key_value_status',
            'value'     => function (KeyValue $model) {
                return ArrayHelper::getValue(KeyValue::statusList(), $model->key_value_status);
            },
        ],
        'key_value_create_at',
        'key_value_update_at',
    ],
]);

Pjax::end();