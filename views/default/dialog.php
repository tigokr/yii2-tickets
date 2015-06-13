<?php
/* @var $this yii\web\View */
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

/* @var $model tigokr\tickets\models\Message */
/* @var $parent tigokr\tickets\models\Message */

$this->params['partTitle'] = \Yii::t('app', Yii::t('app', 'Сообщения'));
$this->title = Yii::t('app', 'Диалог');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Сообщения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row message-form">
    <div class="col-md-12">

        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Диалог</h3>
            </div>

            <div class="box-body">
                <div class="direct-chat-messages" style="height: 300px;">
                    <?php
                    $dataProvider = new ActiveDataProvider([
                        'query' => $parent->getDialog()->orderBy('created_at desc'),
                        'pagination' => [
                            'pageSize' => 20,
                        ],
                    ]);
                    echo ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemView' => '_dialog_message',
                        'summary'=>false,
                    ]);
                    ?>
                </div>
            </div>

            <?= $this->render('_response_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>