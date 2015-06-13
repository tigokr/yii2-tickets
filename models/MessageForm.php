<?php

namespace tigokr\tickets\models;

use common\helpers\String;
use common\models\User;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;

/**
 * ContactForm is the model behind the contact form.
 */
class MessageForm extends Model
{
    public $author_id;
    public $anon;
    public $text;
    public $file;
    public $type;

    private $_users;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['text', 'type'], 'required'],
            ['text', 'safe'],
            ['file', 'file', 'maxSize' => 10 * 1024 * 1024],
            [['author_id'], 'integer',],
            [['anon'], 'boolean',],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'author_id' => \Yii::t('app', 'Автор'),
            'anon' => \Yii::t('app', 'Анонимка'),
            'file' => \Yii::t('app', 'Приложение'),
            'text' => \Yii::t('app', 'Текст'),
            'type' => \Yii::t('app', 'Тип'),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param  string $email the target email address
     * @return boolean whether the model passes validation
     */
    public function submit($type)
    {
        $subject = Message::type($type, 'ru') . ' | ' . \Yii::$app->name;

        $file = null;
        if ($this->file) {
            $path_parts = pathinfo($this->file->name);
            $file = String::sanitize($path_parts['filename']) . '.' . String::sanitize($path_parts['extension']);
        }

        $email_object = Yii::$app->mailer->compose();

        $email_object
            ->setTo([\Yii::$app->params['adminEmail'] => 'Admin', \Yii::$app->params['admin2Email']])
            ->setFrom([\Yii::$app->params['noreplyEmail'] => 'Robot'])
            ->setSubject($subject)
            ->setHtmlBody(
                '<p>' . $this->author->name .' '. $this->author->email .' '.$this->author->phone.' </p>' .
                '<div>' . \yii\helpers\HtmlPurifier::process($this->text) . '</div>'
            );

        if ($file)
            $email_object->attach($this->file->tempName, ['fileName' => $file, 'contentType' => $this->file->type]);

        $email_object->send(); // or may user beautiful template

        $message = new Message();
        $message->author_id = $this->author_id;
        $message->anon = (boolean)$this->anon;
        $message->text = $this->text;
        $message->type = $this->type;
        $message->created_at = date('Y-m-d H:i:s');
        $message->status = Message::STATUS_NEW;

        if ($this->file) {
            FileHelper::createDirectory(\Yii::getAlias('@uploads') . '/messages');
            $fn = '/messages/' . $file;
            $this->file->saveAs( \Yii::getAlias('@uploads') . $fn );
            $message->file = \Yii::getAlias('@hostUrl') . \Yii::getAlias('@uploadsUrl') . $fn;
        }


        if($message->save())
            return true;
    }

    public function getAuthor()
    {
        if(empty($this->_users[$this->author_id]))
            return $this->_users[$this->author_id] = User::findOne($this->author_id);
        else
            return $this->_users[$this->author_id];
    }
}
