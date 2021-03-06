<?php

use kartik\widgets\DepDrop;
use kvmanager\models\KeyValue;
use xlerr\CodeEditor\CodeEditor;
use xlerr\common\widgets\ActiveForm;
use xlerr\common\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

/* @var $this View */
/* @var $model KeyValue */

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
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'namespace')->widget(Select2::class, [
                'disabled'   => !$model->isNewRecord,
                'data'       => array_intersect_key(KeyValue::getNamespaceList(), KeyValue::getAvailable()),
                'hideSearch' => true,
                'options'    => [
                    'id' => 'namespace-input',
                ],
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'group')->widget(DepDrop::className(), [
                'disabled'       => !$model->isNewRecord,
                'type'           => DepDrop::TYPE_SELECT2,
                'select2Options' => [
                    'theme'      => 'default',
                    'hideSearch' => true,
                ],
                'options'        => [
                    'placeholder' => '请选择...',
                ],
                'pluginOptions'  => [
                    'depends'     => ['namespace-input'],
                    'initDepends' => ['namespace-input'],
                    'initialize'  => true,
                    'params'      => [],
                    'placeholder' => '请选择...',
                    'url'         => Url::to(['group-list', 'default' => $model->group]),
                ],
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'key')->textInput([
                'maxlength' => true,
                'disabled'  => !$model->isNewRecord,
            ]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'type')->widget(Select2::class, [
                'data'         => KeyValue::typeList(),
                'hideSearch'   => true,
                'pluginEvents' => [
                    'change' => new JsExpression('typeChange'),
                ],
            ]) ?>
        </div>
    </div>

    <?= $form->field($model, 'value')->widget(CodeEditor::class, [
        'clientOptions' => [
            'mode'     => $model->getEditorMode(),
            'minLines' => 20,
            'maxLines' => 50,
        ],
    ]) ?>

    <?= $form->field($model, 'memo')->widget(CodeEditor::class, [
        'clientOptions' => [
            'mode'     => CodeEditor::MODE_Tex,
            'minLines' => 3,
            'maxLines' => 10,
        ],
    ]) ?>

    <?= $form->field($model, 'operate_info') ?>

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
        valueId = '<?= Html::getInputId($model, 'value') ?>',
        editor = aceInstance[valueId];

    $('#' + valueId).parent().append('<p><code>Ctrl+Shift+F</code>或<code>Command+Shift+F</code>可以格式化<code>JSON</code>类型值.</p>')

    editor.commands.addCommand({
        name: 'Format',
        bindKey: {win: 'Ctrl-Shift-F', mac: 'Command-Shift-F'},
        exec: function (editor) {
            if (editor.getSession().getMode().$id === 'ace/mode/json') {
                try {
                    editor.getSession().setValue(JSON.stringify(JSON.parse(editor.getSession().getValue()), null, "\t"));
                } catch (e) {
                    console.warn(e);
                }
            }
        },
        readOnly: true // false if this command should not apply in readOnly mode
    });

    function typeChange(e) {
        const type = $(e.currentTarget).val();
        editor.getSession().setMode(mapping[type]);
    }
    <?php $this->endBlock() ?>
    <?php $this->registerJs($this->blocks['typeChange']) ?>
</script>