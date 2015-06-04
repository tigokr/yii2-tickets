<?php

/* @var $this yii\web\View */
/* @var $model admin\models\Message */

$this->params['partTitle'] = \Yii::t('app', Yii::t('app', 'Messages'));
$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Message',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменить');
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>