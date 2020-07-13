<?php

use kvmanager\models\KeyValue;
use yii\web\View;

/* @var $this View */
/* @var $model KeyValue */

$this->title = Yii::t('kvmanager', 'Create');

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('kvmanager', 'Key Value'),
    'url'   => [
        'index',
        'namespace' => $model->namespace,
        'group'     => $model->group,
    ],
];
$this->params['breadcrumbs'][] = $model->namespace;
$this->params['breadcrumbs'][] = $model->group;
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
