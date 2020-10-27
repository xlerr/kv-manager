<?php

use kvmanager\models\KeyValue;
use xlerr\CodeEditor\CodeEditor;
use xlerr\common\widgets\ActiveForm;
use xlerr\common\widgets\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;

/* @var $this View */
/* @var $model KeyValue */

$group = KeyValue::getAvailable()[$model->namespace] ?? [];
if ($group) {
    $group = array_combine($group, $group);
}

?>

<?php $form = ActiveForm::begin([
    'action' => [
        '',
        'namespace' => $model->namespace,
        'group'     => $model->group,
        'id'        => $model->id,
    ],
]); ?>

<div class="box-body">
    <?= $form->field($model, 'namespace')->textInput([
        'disabled' => true,
    ]) ?>

    <?= $form->field($model, 'group')->widget(Select2::class, [
        'disabled'   => !$model->isNewRecord,
        'data'       => $group,
        'hideSearch' => true,
    ]) ?>

    <?= $form->field($model, 'key')->textInput([
        'maxlength' => true,
        'disabled'  => !$model->isNewRecord,
    ]) ?>

    <?= $form->field($model, 'type')->widget(Select2::class, [
        'data'         => KeyValue::typeList(),
        'hideSearch'   => true,
        'pluginEvents' => [
            'change' => new JsExpression('typeChange'),
        ],
    ]) ?>

    <?= $form->field($model, 'value')->widget(CodeEditor::class, [
        'clientOptions' => [
            'mode'     => $model->getEditorMode(),
            'maxLines' => 40,
        ],
    ]) ?>

    <?= $form->field($model, 'memo')->widget(CodeEditor::class, [
        'clientOptions' => [
            'mode'     => CodeEditor::MODE_Tex,
            'maxLines' => 40,
        ],
    ]) ?>

</div>

<div class="box-footer">
    <?= Html::submitButton(Yii::t('kvmanager', 'Save'), [
        'class' => 'btn btn-primary',
    ]) ?>
    <?= Html::a('取消', Yii::$app->getRequest()->getReferrer(), [
        'class' => 'btn btn-default',
    ]) ?>
</div>

<?php ActiveForm::end(); ?>

<script>
    <?php $this->beginBlock('typeChange') ?>
    const mapping = <?= json_encode(KeyValue::getEditorModes()) ?>,
        editor = aceInstance['<?= Html::getInputId($model, 'value') ?>'];

    function typeChange(e) {
        let type = $(e.currentTarget).val();
        editor.getSession().setMode(mapping[type]);
    }
    <?php $this->endBlock() ?>
    <?php $this->registerJs($this->blocks['typeChange']) ?>
</script>