<?php

use kvmanager\models\KeyValue;
use xlerr\jsoneditor\JsonViewer;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model KeyValue */

$this->title                   = $model->key_value_key;
$this->params['breadcrumbs'][] = ['label' => Yii::t('kvmanager', 'Key Value'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= Html::a(Yii::t('yii', 'Update'), ['update', 'id' => $model->key_value_id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('yii', 'Delete'), ['delete', 'id' => $model->key_value_id], [
        'class' => 'btn btn-danger',
        'data'  => [
            'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
            'method'  => 'post',
        ],
    ]) ?>
    <?= Html::a(Yii::t('kvmanager', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    <?= Html::a(Yii::t('kvmanager', 'Go Back'), ['index'], ['class' => 'btn btn-default']) ?>
</p>
<div class="box box-primary">
    <div class="box-header with-border">
        <div class="box-title"><?= Yii::t('kvmanager', 'Detail') ?></div>
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
                    'format'         => function ($val) {
                        return JsonViewer::widget([
                            'value' => $val,
                        ]);
                    },
                ],
                'key_value_memo:ntext',
                [
                    'attribute' => 'key_value_status',
                    'value'     => KeyValue::statusList()[$model->key_value_status],
                ],
                'key_value_create_at',
                'key_value_update_at',
            ],
        ]) ?>
    </div>
</div>
