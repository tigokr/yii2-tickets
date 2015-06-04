<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 28.05.2015
 * Time: 23:25
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = $this->params['partTitle'] = 'Сообщения';
$this->params['breadcrumbs'][] = 'Сообщения';
$this->params['breadcrumbs'][] = $name;

?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row group-create">
        <div class="col-md-12">

            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title"><?=$name;?></h3>

                    <div class="box-tools pull-right">
                    </div>
                </div>
                <div class="box-body">

                    <?php if($type == 20): ?>
                        <?= $form->field($model, 'anon')->checkbox(); ?>
                    <?php endif; ?>

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

                    <?= $form->field($model, 'file')->fileInput(['accept' => 'image/*;capture=camera'])->label('Приложить файл') ?>

                </div>
                <div class="box-footer">
                    <div class="form-group text-right">
                        <?= Html::submitButton(Yii::t('app', 'Отправить'), ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>