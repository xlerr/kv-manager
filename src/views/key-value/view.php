<?php

use kvmanager\models\KeyValue;
use xlerr\CodeEditor\CodeEditor;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model KeyValue */

$this->title = $model->key_value_key;

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('kvmanager', 'Key Value'),
    'url'   => [
        'index',
        'key_value_namespace' => $model->key_value_namespace,
        'key_value_group'     => $model->key_value_group,
    ],
];
$this->params['breadcrumbs'][] = $model->key_value_namespace;
$this->params['breadcrumbs'][] = $model->key_value_group;
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= Html::a(Yii::t('yii', 'Update'), [
        'update',
        'key_value_namespace' => $model->key_value_namespace,
        'key_value_group'     => $model->key_value_group,
        'id'                  => $model->key_value_id,
    ], ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('yii', 'Delete'), [
        'delete',
        'key_value_namespace' => $model->key_value_namespace,
        'key_value_group'     => $model->key_value_group,
        'id'                  => $model->key_value_id,
    ], [
        'class' => 'btn btn-danger',
        'data'  => [
            'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
            'method'  => 'post',
        ],
    ]) ?>
    <?= Html::a(Yii::t('kvmanager', 'Create'), [
        'create',
        'key_value_namespace' => $model->key_value_namespace,
        'key_value_group'     => $model->key_value_group,
    ], ['class' => 'btn btn-success']) ?>
    <?= Html::a(Yii::t('kvmanager', 'Go Back'), [
        'index',
        'key_value_namespace' => $model->key_value_namespace,
        'key_value_group'     => $model->key_value_group,
    ], ['class' => 'btn btn-default']) ?>
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
                'key_value_namespace',
                'key_value_group',
                'key_value_key',
                [
                    'attribute' => 'key_value_type',
                    'format'    => ['in', KeyValue::typeList()],
                ],
                [
                    'attribute'      => 'key_value_value',
                    'captionOptions' => [
                        'style' => 'width: 10%',
                    ],
                    'format'         => function ($val) use ($model) {
                        return CodeEditor::widget([
                            'name'          => 'value_show',
                            'value'         => $val,
                            'clientOptions' => [
                                'readOnly' => true,
                                'mode'     => $model->getEditorMode(),
                            ],
                        ]);
                    },
                ],
                'key_value_memo:ntext',
                'key_value_create_at',
                'key_value_update_at',
            ],
        ]) ?>
    </div>
</div>
