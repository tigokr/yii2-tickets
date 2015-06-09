<?php

use tigokr\tickets\models\Message;

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel tigokr\tickets\models\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['partTitle'] = \Yii::t('app', 'Сообщения');
$this->title = Yii::t('app', 'Сообщения');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box message-index">
    <div class="box-header">
        <h3 class="box-title"><?= Html::encode($this->title); ?></h3>
    </div>
    <div class="box-body table-responsive">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-bordered table-hover'],
            'layout' => "{items}\n{pager}\n{summary}\n<div class='clearfix'></div>",
            'pager' => [
                'options' => ['class' => 'pagination pagination-sm no-margin pull-right'],
            ],
            'filterModel' => $searchModel,
            'columns' => [
                'id',
                [
                    'attribute' => 'type',
                    'format' => 'html',
                    'value' => function ($model) {
                        return Html::tag('span', Message::type($model->type), ['class' => 'label bg-' . Message::type($model->type, 'css')]);
                    },
                    'filter' => Message::type(),
                ],
                [
                    'attribute' => 'anon',
                    'format' => 'boolean',
                    'visible' => \Yii::$app->user->can('admin'),
                    'filter' => ['Нет', 'Да'],
                ],
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'value' => function ($model) {
                        return Html::tag('span', Message::status($model->status), ['class' => 'label bg-' . Message::status($model->status, 'css')]);
                    },
                    'filter' => Message::status(),
                ],
                [
                    'attribute' => 'author_id',
                    'format' => 'html',
                    'label'=>'<i class="fa fa-user  fa-2x"></i>', 'encodeLabel'=>false,
                    'value' => function ($model) {
                        return $model->author?Html::a($model->author->name, $model->author->urlAdmin):null;
                    },
                    'filter' => ($user = $this->context->module->getAuthor())?ArrayHelper::map($user::find()->all(), 'id', 'name'):false,
                    'visible' => $this->context->module->getAuthor(),
                ],
                'text:html',
                'created_at',
                [
                    'attribute'=>'file',
                    'format'=>'raw',
                    'filter'=>false,
                    'value'=>function($model){
                        if(empty($model->file))
                            return null;

                        if( strpos(\yii\helpers\FileHelper::getMimeTypeByExtension( $model->file ), 'image') !== false )
                            return Html::a(Html::img($model->file, ['class'=>'img-responsive']), $model->file, ['target'=>'_blank']);

                        return Html::a(basename($model->file), $model->file, ['target'=>'_blank']);
                    }
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'buttons' => [
                        'update'=> function ($url, $model, $key) {
                            $can = \Yii::$app->user->can('root');
                            return $can ? Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url) : '';
                        },
                        'view'=> function ($url, $model, $key) {
                            $can = true;
                            return $can ? Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url) : '';
                        },
                        'delete'=> function ($url, $model, $key) {
                            $can = $model->author_id == \Yii::$app->user->id || \Yii::$app->user->can('manager');
                            return $can ? Html::a('<span class="glyphicon glyphicon-trash"></span>', $url) : '';
                        },
                    ]

                ],
            ],
        ]); ?>

    </div>
</div>
