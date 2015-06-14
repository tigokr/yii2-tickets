<?php

namespace tigokr\tickets\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * This is the model class for table "message".
 *
 * @property string $id
 * @property string $author_id
 * @property string $parent_id
 * @property integer $type
 * @property integer $anon
 * @property string $text
 * @property string $file
 * @property string $created_at
 * @property integer $status
 *
 * @property \common\models\User $author
 */
class Message extends \yii\db\ActiveRecord
{
    const STATUS_RESPONSE = 0;
    const STATUS_NEW = 10;
    const STATUS_INWORK = 20;
    const STATUS_SOLVED = 30;
    const STATUS_DELETED = 40;

    public static function status($v = null, $ln = 'ru'){
        switch($ln){
            case 'css':
                $list = [
                    self::STATUS_NEW => 'maroon',
                    self::STATUS_INWORK => 'olive',
                    self::STATUS_SOLVED => 'green',
                    self::STATUS_DELETED => 'gray',
                ];
                break;
            case 'en':
                $list = [
                    self::STATUS_NEW => 'new',
                    self::STATUS_INWORK => 'inwork',
                    self::STATUS_SOLVED => 'solved',
                    self::STATUS_DELETED => 'deleted',
                ];
                break;
            default:
                $list = [
                    self::STATUS_NEW => 'Новое',
                    self::STATUS_INWORK => 'В работе',
                    self::STATUS_SOLVED => 'Решено',
                    self::STATUS_DELETED => 'Удалено',

                ];
                break;
        }

        if(is_null($v))
            return $list;

        return $list[$v]?$list[$v]:null;
    }

    const TYPE_ERROR = 10;
    const TYPE_ABUSE = 20;
    const TYPE_OFFER = 30;

    public static function type($v = null, $ln = 'ru'){
        switch($ln){
            case 'css':
                $list = [
                    self::TYPE_ERROR => 'red',
                    self::TYPE_ABUSE => 'yellow',
                    self::TYPE_OFFER => 'green',
                ];
                break;
            case 'en':
                $list = [
                    self::TYPE_ERROR => 'error',
                    self::TYPE_ABUSE => 'abuse',
                    self::TYPE_OFFER => 'offer',
                ];
                break;
            default:
                $list = [
                    self::TYPE_ERROR => 'Ошибка',
                    self::TYPE_ABUSE => 'Жалоба',
                    self::TYPE_OFFER => 'Предложение',
                ];
                break;
        }

        if(is_null($v))
            return $list;

        return $list[$v]?$list[$v]:null;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @return MessageQuery
     */
    public static function find() {
        return new MessageQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author_id', 'type', 'status'], 'integer'],
            [['type', 'text', 'status'], 'required'],
            [['text', 'file'], 'string'],
            [['created_at'], 'safe'],
            [['anon'], 'boolean'],
            [['file', 'parent_id'], 'default', 'value'=>null],
            [['anon'], 'default', 'value'=>false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app.models.Message', 'ID'),
            'author_id' => Yii::t('app.models.Message', 'Автор'),
            'parent_id' => Yii::t('app.models.Message', 'Ответ'),
            'type' => Yii::t('app.models.Message', 'Тип'),
            'anon' => Yii::t('app.models.Message', 'Анонимка'),
            'text' => Yii::t('app.models.Message', 'Текст'),
            'file' => Yii::t('app.models.Message', 'Приложение'),
            'created_at' => Yii::t('app.models.Message', 'Дата создания'),
            'status' => Yii::t('app.models.Message', 'Статус'),
        ];
    }

    public function afterSave($insert, $changedAttributes) {
        $this->makeThread();
        parent::afterSave($insert, $changedAttributes);
    }

    public function deleteThread(){
        $this->updateAll(['status'=>self::STATUS_DELETED], 'thread='.$this->thread);
    }

    public function makeThread(){
        if($this->parent) {
            self::updateAll(['thread'=>$this->parent->thread], 'id='.$this->id);
        } else {
            self::updateAll(['thread'=>$this->id], 'id='.$this->id);
        }
        $this->refresh();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        $userModel = \Yii::$app->controller->module->userModel;
        return $userModel?$this->hasOne($userModel, ['id' => 'author_id']):null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDialog() {
        return $this->hasMany(self::className(), ['thread'=>'thread']);
    }

    public function getDialogUrl(){
        return Url::to(['/tickets/default/dialog', 'id'=>$this->thread]);
    }

    public function doNotify($lastMessage)
    {
        $messages = self::find()
            ->select('author_id')
            ->thread($this->thread)
            ->groupBy('author_id')
            ->all();

        foreach($messages as $message) {
            $user = $message->author;

            $subject = Message::type($this->type, 'ru') . ' | ' . \Yii::$app->name;

            $email_object = \Yii::$app->mailer->compose();

            $email_object
                ->setTo([$user->email])
                ->setFrom([\Yii::$app->params['noreplyEmail'] => 'Robot'])
                ->setSubject($subject)
                ->setHtmlBody(
                    '<p>' . $lastMessage->author->name .' '. $lastMessage->author->email .' '.$lastMessage->author->phone.' </p>' .
                    '<p> <a href="'. \Yii::getAlias('@hostUrl') . $this->dialogUrl.'">Диалог '.$this->thread.'</a></p>'.
                    '<div><h3>Вопрос: </h3> ' . \yii\helpers\HtmlPurifier::process($this->text) .' </div>' .
                    '<div><h3>Ответ: </h3> ' . \yii\helpers\HtmlPurifier::process($lastMessage->text) . '</div>'
                );

            $email_object->send(); // or may user beautiful template

        }
    }
}
