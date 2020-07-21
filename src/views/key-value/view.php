<?php

use kvmanager\models\KeyValue;
use xlerr\CodeEditor\CodeEditor;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model KeyValue */

$this->title = $model->key;

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('kvmanager', 'Key Value'),
    'url'   => [
        'index',
        'namespace' => $model->namespace,
        'group'     => $model->group,
    ],
];
$this->params['breadcrumbs'][] = $model->namespace;
$this->params['breadcrumbs'][] = $model->group;
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= Html::a(Yii::t('kvmanager', 'Clean Cache'), [
        'clean-cache',
        'namespace' => $model->namespace,
        'group'     => $model->group,
        'id'        => $model->id,
    ], ['class' => 'btn btn-warning']) ?>
    <?= Html::a(Yii::t('kvmanager', 'Sync'), [
        'sync',
        'namespace' => $model->namespace,
        'group'     => $model->group,
        'id'        => $model->id,
    ], [
        'class' => 'btn btn-facebook',
        'data'  => [
            'method' => 'post',
        ],
    ]) ?>
    <?= Html::a(Yii::t('yii', 'Update'), [
        'update',
        'namespace' => $model->namespace,
        'group'     => $model->group,
        'id'        => $model->id,
    ], ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('yii', 'Delete'), [
        'delete',
        'namespace' => $model->namespace,
        'group'     => $model->group,
        'id'        => $model->id,
    ], [
        'class' => 'btn btn-danger',
        'data'  => [
            'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
            'method'  => 'post',
        ],
    ]) ?>
    <?= Html::a(Yii::t('kvmanager', 'Create'), [
        'create',
        'namespace' => $model->namespace,
        'group'     => $model->group,
    ], ['class' => 'btn btn-success']) ?>
    <?= Html::a(Yii::t('kvmanager', 'Go Back'), [
        'index',
        'namespace' => $model->namespace,
        'group'     => $model->group,
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
                'id',
                'namespace',
                'group',
                'key',
                [
                    'attribute' => 'type',
                    'format'    => ['in', KeyValue::typeList()],
                ],
                [
                    'attribute'      => 'value',
                    'captionOptions' => [
                        'style' => 'width: 10%',
                    ],
                    'format'         => 'raw',
                    'value'          => CodeEditor::widget([
                        'name'          => 'value_show',
                        'value'         => $model->value,
                        'clientOptions' => [
                            'readOnly' => true,
                            'mode'     => $model->getEditorMode(),
                            'maxLines' => 40,
                        ],
                    ]),
                ],
                'memo:ntext',
                'created_at',
                'updated_at',
                [
                    'label'     => '创建者',
                    'attribute' => 'creator.username',
                ],
                [
                    'label'     => '修改者',
                    'attribute' => 'operator.username',
                ],
            ],
        ]) ?>
    </div>
</div>
