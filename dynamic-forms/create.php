<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\DynamicForm */

$this->title = Yii::t('app', 'Create Dynamic Form');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dynamic Forms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dynamic-form-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
