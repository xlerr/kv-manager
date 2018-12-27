<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KeyValue */

$this->title = '更新:' . $model->key_value_key;
$this->params['breadcrumbs'][] = ['label' => '配置列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '详情', 'url' => ['view', 'id' => $model->key_value_id]];
$this->params['breadcrumbs'][] = '更新';
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <div class="box-title"><?= Html::encode('更新')?></div>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
