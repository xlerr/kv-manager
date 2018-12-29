<?php

use kvmanager\models\KeyValue;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model KeyValue */

$this->title                   = $model->key_value_key;
$this->params['breadcrumbs'][] = ['label' => 'Key Value', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= Html::a('修改', ['update', 'id' => $model->key_value_id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('删除', ['delete', 'id' => $model->key_value_id], [
        'class' => 'btn btn-danger',
        'data'  => [
            'confirm' => '确定删除该项?',
            'method'  => 'post',
        ],
    ]) ?>
    <?= Html::a('继续添加', ['/key-value/create'], ['class' => 'btn btn-info']) ?>
    <?= Html::a('返回列表', ['/key-value/index'], ['class' => 'btn btn-warning']) ?>
</p>
<div class="box box-primary">
    <div class="box-header with-border">
        <div class="box-title">详情</div>
    </div>

    <div class="box-body no-padding">
        <?= DetailView::widget([
            'model'      => $model,
            'options'    => [
                'class' => 'table table-striped detail-view',
            ],
            'attributes' => [
                'key_value_id',
                'key_value_key',
                [
                    'attribute'      => 'key_value_value',
                    'captionOptions' => [
                        'style' => 'width: 10%',
                    ],
                    'format'         => 'raw',
                    'value'          => Html::textarea(null, $model->getFormattedValue(), [
                        'style' => 'width: 100%; resize:none;',
                        'rows'  => '10',
                    ]),
                ],
                'key_value_memo:ntext',
                [
                    'attribute' => 'key_value_status',
                    'value'     => KeyValue::STATUS_LIST[$model->key_value_status],
                ],
                'key_value_create_at',
                'key_value_update_at',
            ],
        ]) ?>
    </div>
</div>
