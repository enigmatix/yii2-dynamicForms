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

<div class="dynamic-form-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'form_object')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'form_data')->widget(Summernote::className(), [
        'clientOptions' => Yii::$app->params['summernote']['clientOptions']
    ]) ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'owned_by')->widget(Relate::className()) ?>

    <?= $form->field($model, 'created_by')->widget(Relate::className()) ?>

    <?= $form->field($model, 'updated_by')->widget(Relate::className()) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
