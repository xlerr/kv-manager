<?php

use kartik\widgets\ActiveForm;
use kvmanager\models\KeyValue;
use yii\helpers\Html;

/** @var $model KeyValue */
?>

<div class="box box-default search">
    <div class="box-header with-border">
        <i class="glyphicon glyphicon-search"></i>
        <h3 class="box-title"><?= Yii::t('kvmanager', 'Search') ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <?php $form = ActiveForm::begin([
            'action' => [
                'index',
                'key_value_namespace' => $model->key_value_namespace,
                'key_value_group'     => $model->key_value_group,
            ],
            'method' => 'get',
            'type'   => ActiveForm::TYPE_INLINE,
        ]); ?>

        <?= $form->field($model, 'key_value_key') ?>

        <?= $form->field($model, 'key_value_value') ?>

        <?= $form->field($model, 'key_value_memo') ?>

        <?= Html::submitButton(Yii::t('kvmanager', 'Search'), ['class' => 'btn btn-primary']) ?>

        <?= Html::a(Yii::t('kvmanager', 'Reset'), [
            'index',
            'key_value_namespace' => $model->key_value_namespace,
            'key_value_group'     => $model->key_value_group,
        ], ['class' => 'btn btn-default']); ?>

        <?= Html::a(Yii::t('kvmanager', 'Create'), [
            'create',
            'key_value_namespace' => $model->key_value_namespace,
            'key_value_group'     => $model->key_value_group,
        ], ['class' => 'btn btn-success']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>