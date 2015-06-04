<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model admin\models\Message */
/* @var $form yii\bootstrap\ActiveForm */
?>

<?php $form = ActiveForm::begin(['fieldClass' => 'admin\themes\adminlte\widgets\ExtendedActiveField', 'options' => ['enctype' => 'multipart/form-data']]); ?>

<div class="row message-form">
    <div class="col-md-12">

        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Общие сведения</h3>
                <div class="box-tools pull-right">
                </div>
            </div>
            <div class="box-body">


              <?= $form->field($model, 'author_id')->textInput(['maxlength' => true]) ?>

              <?= $form->field($model, 'type')->textInput() ?>

              <?= $form->field($model, 'anon')->textInput() ?>

              <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

              <?= $form->field($model, 'file')->textarea(['rows' => 6]) ?>

              <?= $form->field($model, 'created_at')->textInput() ?>

              <?= $form->field($model, 'status')->textInput() ?>


            </div>
            <div class="box-footer">
                <div class="form-group text-right">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Добавить') : Yii::t('app', 'Изменить'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>