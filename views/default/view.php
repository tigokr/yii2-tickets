<?php

use tigokr\tickets\models\Message;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model tigokr\tickets\models\Message */

$this->params['partTitle'] = \Yii::t('app', 'Сообщения');
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Сообщения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-6">
        <div class="box message-view">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>

                <div class="box-tools pull-right">
                    <?php if(\Yii::$app->user->can('root')): ?>
                    <?= Html::a(\Yii::t('app', 'Редактировать'), ['update', 'id' => $model->id], ['class' => 'btn btn-box-tool']) ?>
                    <?php endif; ?>
                    <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
                        'class' => 'btn text-red btn-box-tool',
                        'data' => [
                            'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить этот объект?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>

            </div>
            <div class="box-body">


                <?= DetailView::widget([
                    'model' => $model,
                    'template' => "<tr><th width='50%'>{label}</th><td>{value}</td></tr>",
                    'attributes' => [
                        'id',
                        [
                            'attribute' => 'author_id',
                            'format' => 'html',
                            'value' => $model->author ? Html::a($model->author->name, $model->author->urlAdmin) : $model->author,
                        ],
                        [
                            'attribute' => 'type',
                            'value' => Message::type($model->type),
                        ],
                        [
                            'attribute' => 'status',
                            'value' => Message::status($model->status),
                        ],
                        [
                            'attribute' => 'anon',
                            'format' => 'boolean',
                            'visible' => \Yii::$app->user->can('admin'),
                        ],
                        'file:url',
                        'created_at',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="box blog-post-view box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Текст</h3>
            </div>
            <div class="box-body">
                <?= $model->text; ?>
            </div>
        </div>
    </div>
</div>
