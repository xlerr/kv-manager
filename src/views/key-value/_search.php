<?php

use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use kvmanager\models\KeyValue;
use yii\helpers\Html;

/** @var $model \kvmanager\models\KeyValue */
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
            'action' => ['index'],
            'method' => 'get',
            'type'   => ActiveForm::TYPE_INLINE,
        ]); ?>

        <?= $form->field($model, 'key_value_key') ?>

        <?= $form->field($model, 'key_value_value') ?>

        <?= $form->field($model, 'key_value_memo') ?>

        <?= $form->field($model, 'key_value_status')->widget(Select2::class, [
            'data'          => KeyValue::statusList(),
            'theme'         => Select2::THEME_DEFAULT,
            'hideSearch'    => true,
            'pluginOptions' => [
                'allowClear' => true,
                'width'      => '100px',
            ],
            'options'       => [
                'placeholder' => $model->getAttribute('status'),
            ],
        ]) ?>

        <?= Html::submitButton(Yii::t('kvmanager', 'Search'), ['class' => 'btn btn-primary']) ?>

        <?= Html::a(Yii::t('kvmanager', 'Reset'), ['index'], ['class' => 'btn btn-default']); ?>

        <?= Html::a(Yii::t('kvmanager', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>