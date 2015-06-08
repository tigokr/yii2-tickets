<?php

namespace tigokr\tickets\controllers;

use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use tigokr\tickets\models\Message;
use tigokr\tickets\models\MessageForm;
use tigokr\tickets\models\MessageSearch;

class DefaultController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'admin\extensions\ErrorAction',
                'layout' => \Yii::$app->user->can('employee')?'main':'error',
            ],
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadAction',
                'url' => \Yii::getAlias('@hostUrl').'/uploads/messages',
                'path' => '@uploads/messages',
            ],
            'images-get' => [
                'class' => 'vova07\imperavi\actions\GetAction',
                'url' => \Yii::getAlias('@hostUrl').'/uploads/messages',
                'path' => '@uploads/messages',
                'type' => \vova07\imperavi\actions\GetAction::TYPE_IMAGES,
            ]
        ];
    }

    /**
     * Lists all Message models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MessageSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        if(!\Yii::$app->user->can('admin')) {
            $dataProvider->query->andWhere($searchModel->find()->notAbuse()->where);
        }


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Message model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if($model->type == Message::TYPE_ABUSE && !\Yii::$app->user->can('admin')) {
            $this->redirect(['index']);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Message model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Message();

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionMessage($type = null){
        $messageForm = new MessageForm;

        $messageForm->type = $type;
        $messageForm->author_id = \Yii::$app->user->id;

        if($messageForm->load(\Yii::$app->request->post()) && $messageForm->validate()) {
            $messageForm->file = UploadedFile::getInstance($messageForm, 'file');

            $messageForm->submit($type);

            \Yii::$app->session->setFlash('success', \Yii::t('app', 'Спасибо за Вашу помощь!'));
            $this->redirect(['messages/index']);
        }

        switch($type) {
            default:
                $name = 'Ошибка';
                break;
            case 20:
                $name = 'Жалоба';
                break;
            case 30:
                $name = 'Предложение';
                break;
        }

        return $this->render('message', [
            'model' => $messageForm,
            'name' => $name,
            'type' => $type,
        ]);
    }

    /**
     * Updates an existing Message model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($model->type == Message::TYPE_ABUSE && !\Yii::$app->user->can('admin')) {
            $this->redirect(['index']);
        }

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Message model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if($model->type == Message::TYPE_ABUSE && !\Yii::$app->user->can('admin')) {
            $this->redirect(['index']);
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Message model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Message the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Message::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
