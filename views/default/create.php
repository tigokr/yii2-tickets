<?php

/* @var $this yii\web\View */
/* @var $model admin\models\Message */

$this->params['partTitle'] = \Yii::t('app', Yii::t('app', 'Messages'));
$this->title = Yii::t('app', 'Добавить Message');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>

