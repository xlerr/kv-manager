<?php

/* @var $this yii\web\View */

/* @var $model \kvmanager\models\KeyValue */

$this->title = Yii::t('kvmanager', 'Update: {0}', $model->key_value_key);
$this->params['breadcrumbs'][] = ['label' => Yii::t('kvmanager', 'Key Value'), 'url' => ['index']];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('kvmanager', 'Detail'),
    'url'   => ['view', 'id' => $model->key_value_id],
];
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
