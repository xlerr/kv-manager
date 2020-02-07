<?php

use kartik\widgets\Select2;
use kvmanager\models\KeyValue;
use xlerr\jsoneditor\JsonEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \kvmanager\models\KeyValue */
/* @var $form yii\widgets\ActiveForm */

$formId = md5(__FILE__ . 'KVMANAGER');
$js     = <<<JS
$("#$formId").on("beforeSubmit", function (event) {
    $(this).find('button[type=submit]').html('...').attr('disabled', 'disabled');
});
JS;

$this->registerJs($js)
?>

<?php $form = ActiveForm::begin([
    'id' => $formId,
]); ?>

<div class="box-body">

    <?= $form->field($model, 'key_value_key')->textInput([
        'maxlength' => true,
        'disabled'  => !$model->isNewRecord,
    ]) ?>

    <?= $form->field($model, 'key_value_value')->widget(JsonEditor::class) ?>

    <?= $form->field($model, 'key_value_status')->widget(Select2::class, [
        'data'       => KeyValue::statusList(),
        'theme'      => Select2::THEME_DEFAULT,
        'hideSearch' => true,
    ]) ?>

    <?= $form->field($model, 'key_value_memo')->textarea([
        'rows' => 2,
    ]) ?>

</div>

<div class="box-footer">
    <?= Html::submitButton(Yii::t('kvmanager', $model->isNewRecord ? 'Create' : 'Update'), [
        'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
    ]) ?>
</div>

<?php ActiveForm::end(); ?>
