<?php

use xlerr\jsoneditor\JsonEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \kvmanager\models\KeyValue */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<div class="box-body">

    <?= $form->field($model, 'key_value_key')->textInput([
        'maxlength' => true,
        'disabled'  => !$model->isNewRecord,
    ]) ?>

    <?= $form->field($model, 'key_value_value')->widget(JsonEditor::class) ?>

    <?= $form->field($model, 'key_value_status')->dropDownList($model->getStatus(), [
        'options'    => [
            'placeholder' => '请选择...',
        ],
    ]) ?>

    <?= $form->field($model, 'key_value_memo')->textarea(['rows' => 4]) ?>

</div>

<div class="box-footer">
    <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', [
        'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
    ]) ?>
</div>

<?php ActiveForm::end(); ?>
