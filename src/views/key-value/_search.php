<?php

use kartik\widgets\Select2;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

/** @var $model \kvmanager\models\KeyValue */
?>

<?php $form = ActiveForm::begin([
    'action'  => ['index'],
    'method'  => 'get',
    'type'    => ActiveForm::TYPE_INLINE,
    'options' => ['class' => 'form-inline'],
]); ?>

<?= $form->field($model, 'key_value_key')->textInput([]) ?>

<?= $form->field($model, 'key_value_value')->textInput() ?>

<?= $form->field($model, 'key_value_memo')->textInput() ?>

<?= $form->field($model, 'key_value_status')->widget(Select2::class, [
    'data'          => $model->getStatus(),
    'theme'         => Select2::THEME_DEFAULT,
    'hideSearch'    => true,
    'pluginOptions' => [
        'allowClear' => true,
        'width'      => '100px',
    ],
    'options'       => [
        'placeholder' => '状态',
    ],
]) ?>

<?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>

<?= Html::a('重置搜索条件', ['index'], ['class' => 'btn btn-default']); ?>

<?= Html::a('创建键值对', ['create'], ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
