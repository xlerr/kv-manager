<?php

use kartik\widgets\ActiveForm;
use kvmanager\models\KeyValue;
use xlerr\common\widgets\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;

/** @var $this View */
/** @var $model KeyValue */

$groupList = (array)KeyValue::find()
    ->where([
        KeyValue::$namespaceFieldName => $model->{KeyValue::$namespaceFieldName},
    ])
    ->select(KeyValue::$groupFieldName)
    ->distinct()
    ->column();

if (!array_search($model->group, $groupList)) {
    $groupList[] = $model->group;
}
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
                'namespace' => $model->namespace,
                'group'     => $model->group,
            ],
            'method' => 'get',
            'type'   => ActiveForm::TYPE_INLINE,
        ]); ?>

        <?= $form->field($model, 'group')->widget(Select2::class, [
            'data'         => array_combine($groupList, $groupList),
            'hideSearch'   => true,
            'pluginEvents' => [
                'change' => new JsExpression('switchGroup'),
            ],
            'options'      => [
                'name' => '_',
            ],
        ]) ?>

        <?= $form->field($model, 'key') ?>

        <?= $form->field($model, 'value') ?>

        <?= $form->field($model, 'memo') ?>

        <?= Html::submitButton(Yii::t('kvmanager', 'Search'), ['class' => 'btn btn-primary']) ?>

        <?= Html::a(Yii::t('kvmanager', 'Reset'), [
            'index',
            'namespace' => $model->namespace,
            'group'     => $model->group,
        ], ['class' => 'btn btn-default']); ?>

        <?= Html::a(Yii::t('kvmanager', 'Create'), [
            'create',
            'namespace' => $model->namespace,
            'group'     => $model->group,
        ], ['class' => 'btn btn-success']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
<script>
    <?php $this->beginBlock('switchGroup') ?>
    function switchGroup(e) {
        window.location.pathname = window.location.pathname.toString().replace(/\/[\w\-]+$/, '/' + $(e.currentTarget).val());
    }
    <?php $this->endBlock() ?>
    <?php $this->registerJs($this->blocks['switchGroup']) ?>
</script>