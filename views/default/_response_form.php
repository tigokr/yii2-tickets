<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \tigokr\tickets\models\Message; */
/* @var $form yii\bootstrap\ActiveForm */

$userClass = $this->context->module->getUser();

?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>


    <div class="box-body">

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
        ])->label('Ответ'); ?>

    </div>
    <div class="box-footer">
        <div class="form-group text-right">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
        </div>
    </div>


<?php ActiveForm::end(); ?>