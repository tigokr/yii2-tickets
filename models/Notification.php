<?php

namespace tigokr\tickets\models;

use Yii;

/**
 * This is the model class for table "notification".
 *
 * @property string $id
 * @property string $receiver
 * @property string $sender
 * @property string $message
 * @property string $created_at
 * @property integer $email
 * @property string $priority
 */
class Notification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['receiver', 'sender', 'message', 'created_at', 'email', 'priority'], 'required'],
            [['message'], 'string'],
            [['created_at'], 'safe'],
            [['email'], 'integer'],
            [['receiver', 'sender'], 'string', 'max' => 80],
            [['priority'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'receiver' => 'Получатель',
            'sender' => 'Отправитель',
            'message' => 'Сообщение',
            'created_at' => 'Дата отправки',
            'email' => 'email',
            'priority' => 'Приоритет',
        ];
    }
}
