<?php

namespace tigokr\tickets\controllers;

class DefaultController extends \yii\web\Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }
}
