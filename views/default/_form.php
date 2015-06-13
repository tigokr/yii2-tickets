<?php

use kartik\widgets\DateTimePicker;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \tigokr\tickets\models\Message;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \tigokr\tickets\models\Message; */
/* @var $form yii\bootstrap\ActiveForm */

$userClass = $this->context->module->getUser();

?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<div class="row message-form">
    <div class="col-md-12">

        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Общие сведения</h3>
                <div class="box-tools pull-right">
                </div>
            </div>
            <div class="box-body">


              <?= $form->field($model, 'author_id')->dropDownList(\yii\helpers\ArrayHelper::map($userClass::find()->all(), 'id', 'name')) ?>

              <?= $form->field($model, 'type')->dropDownList(Message::type(null, 'ru')) ?>

              <?= $form->field($model, 'anon')->checkbox() ?>

              <?= $form->field($model, 'text')->widget(\vova07\imperavi\Widget::className(), [
                  'settings' => [
                      'lang' => 'ru',
                      'minHeight' => 200,
                      'plugins' => [
                          'clips',
                          'fullscreen',
                          'imagemanager'
                      ],
                      'imageManagerJson' => Url::to(['images-get']),
                      'imageUpload' => Url::to(['image-upload']),
                  ]
              ]); ?>

              <?= $form->field($model, 'created_at')->widget(DateTimePicker::className(), [
                  'model'=>$model,
                  'attribute'=>'created_at',
                  'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
              ]) ?>

              <?= $form->field($model, 'status')->dropDownList(Message::status(null, 'ru')) ?>


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