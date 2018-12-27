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

$this->title                   = '配置列表';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="box box-default">
        <div class="box-header with-border">
            <div class="box-title">搜索条件
                <i class="fa fa-arrow-circle-down text-danger"></i>
            </div>
        </div>
        <div class="box-body">
            <?= $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>

<?php Pjax::begin([
    'timeout' => 10000,
]) ?>
<?= GridView::widget([
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
                return ArrayHelper::getValue($model->getStatus(), $model->key_value_status, '未知');
            },
        ],
        'key_value_create_at',
        'key_value_update_at',
    ],
]); ?>
<?php Pjax::end() ?>