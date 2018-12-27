<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \kvmanager\models\KeyValue */

$this->title                   = '创建';
$this->params['breadcrumbs'][] = ['label' => '配置列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <div class="box-title"><?= Html::encode($this->title) ?></div>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
