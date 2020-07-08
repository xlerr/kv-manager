<?php

use kvmanager\models\KeyValue;
use yii\web\View;

/* @var $this View */
/* @var $model KeyValue */

$this->title = Yii::t('kvmanager', 'Update: {0}', $model->key_value_key);

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('kvmanager', 'Key Value'),
    'url'   => [
        'index',
        'key_value_namespace' => $model->key_value_namespace,
        'key_value_group'     => $model->key_value_group,
    ],
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('kvmanager', 'Detail'),
    'url'   => [
        'view',
        'key_value_namespace' => $model->key_value_namespace,
        'key_value_group'     => $model->key_value_group,
        'id'                  => $model->key_value_id,
    ],
];
$this->params['breadcrumbs'][] = $model->key_value_namespace;
$this->params['breadcrumbs'][] = $model->key_value_group;
$this->params['breadcrumbs'][] = Yii::t('kvmanager', 'Update');
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <div class="box-title"><?= Yii::t('kvmanager', 'Update') ?></div>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
