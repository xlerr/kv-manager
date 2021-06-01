<?php

use kartik\widgets\DepDrop;
use kvmanager\models\KeyValue;
use xlerr\common\widgets\ActiveForm;
use xlerr\common\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/** @var $this View */
/** @var $model KeyValue */

?>

<div class="box box-default search">
    <div class="box-header with-border">
        <div class="box-title"><?= Yii::t('kvmanager', 'Search') ?></div>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <?php
        $form = ActiveForm::begin([
            'action'        => [''],
            'method'        => 'get',
            'type'          => ActiveForm::TYPE_INLINE,
            'waitingPrompt' => ActiveForm::WAITING_PROMPT_SEARCH,
        ]); ?>

        <?= $form->field($model, 'namespace', [
            'options' => [
                'class' => 'form-group',
                'style' => 'min-width: 120px',
            ],
        ])->widget(Select2::class, [
            'data'       => array_intersect_key(KeyValue::getNamespaceList(), KeyValue::getAvailable()),
            'hideSearch' => true,
            'options'    => [
                'id' => 'namespace-input',
            ],
        ]) ?>

        <?= $form->field($model, 'group', [
            'options' => [
                'class' => 'form-group',
                'style' => 'min-width: 120px',
            ],
        ])->widget(DepDrop::className(), [
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

        <?= $form->field($model, 'key') ?>

        <?= $form->field($model, 'value') ?>

        <?= Html::submitButton(Yii::t('kvmanager', 'Search'), ['class' => 'btn btn-primary']) ?>

        <?= Html::a(Yii::t('kvmanager', 'Reset'), [''], ['class' => 'btn btn-default']); ?>

        <?= Html::a(Yii::t('kvmanager', 'Create'), [
            'create',
            'namespace' => $model->namespace,
            'group'     => $model->group,
        ], ['class' => 'btn btn-success']) ?>

        <?php
        ActiveForm::end(); ?>
    </div>
</div>
