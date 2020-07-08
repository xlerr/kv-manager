<?php

/* @var $this yii\web\View */

/* @var $model \kvmanager\models\KeyValue */

$this->title                   = Yii::t('kvmanager', 'Create');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('kvmanager', 'Key Value'),
    'url'   => [
        'index',
        'key_value_namespace' => $model->key_value_namespace,
        'key_value_group'     => $model->key_value_group,
    ],
];
$this->params['breadcrumbs'][] = $model->key_value_namespace;
$this->params['breadcrumbs'][] = $model->key_value_group;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <div class="box-title"><?= Yii::t('kvmanager', 'Create') ?></div>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
