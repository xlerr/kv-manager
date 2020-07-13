<?php

use kvmanager\models\KeyValue;
use yii\web\View;

/* @var $this View */
/* @var $model KeyValue */

$this->title = Yii::t('kvmanager', 'Update: {0}', $model->key);

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('kvmanager', 'Key Value'),
    'url'   => [
        'index',
        'namespace' => $model->namespace,
        'group'     => $model->group,
    ],
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('kvmanager', 'Detail'),
    'url'   => [
        'view',
        'namespace' => $model->namespace,
        'group'     => $model->group,
        'id'        => $model->id,
    ],
];
$this->params['breadcrumbs'][] = $model->namespace;
$this->params['breadcrumbs'][] = $model->group;
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
