<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use enigmatix\select2\Relate;
use enigmatix\select2\ManyToMany;
use enigmatix\widgets\DatePicker;
use marqu3s\summernote\Summernote;

/* @var $this yii\web\View */
/* @var $model common\models\DynamicForm */
/* @var $form yii\widgets\ActiveForm */

?>


<?= \enigmatix\formbuilder\FormBuilder::widget(['id' => $formid, 'model' => $model]) ?>
