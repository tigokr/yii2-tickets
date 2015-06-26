<?php

namespace tigokr\tickets\controllers;

use common\components\RbacFilter;
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
        $searchModel->status = Message::STATUS_NEW;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        if(!\Yii::$app->user->can('admin')) {
            $dataProvider->query->andWhere($searchModel->find()->notAbuse()->where);
        }

        // only start dialog messages
        $dataProvider->query->andWhere($searchModel->find()->start()->where);

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
            $this->redirect(['/tickets/default/index']);
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
        if(!\Yii::$app->user->can('root')) {
            $this->redirect(['index']);
        }

        $model = $this->findModel($id);
        if($model->type == Message::TYPE_ABUSE && !\Yii::$app->user->can('admin')) {
            $this->redirect(['index']);
        }

        $status = $model->status;
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {

            if($model->status != $status) {
                /**
                 * TODO Отправка сообщение авторам
                 */
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDialog($id) {
        $parent = $this->findModel($id);

        $model = new Message();

        if ($model->load(\Yii::$app->request->post())) {
            $model->type = $parent->type;
            $model->parent_id = $parent->id;
            $model->author_id = \Yii::$app->user->id;
            $model->status = Message::STATUS_RESPONSE;
            if($model->save()) {
                $parent->doNotify($model);
                return $this->redirect(['dialog', 'id' => $model->thread]);
            }
        }

        return $this->render('dialog', [
            'parent' => $parent,
            'model' => $model,
        ]);
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
        if(!($model->author_id == \Yii::$app->user->id || \Yii::$app->user->can('admin'))) {
            \Yii::$app->session->setFlash('warning', 'У вас нет доступа к этим страницам!');
            return $this->redirect('/tickets/default/index');
        }

        $model->deleteThread();

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
