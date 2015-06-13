<?php

namespace tigokr\tickets\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "message".
 *
 * @property string $id
 * @property string $author_id
 * @property integer $type
 * @property integer $anon
 * @property string $text
 * @property string $file
 * @property string $created_at
 * @property integer $status
 *
 * @property \common\models\User $author
 */
class MessageQuery extends \yii\db\ActiveQuery
{
    public function start(){
        $this->andWhere(['is', 'parent_id', null]);
        return $this;
    }

    public function abuse(){
        $this->andWhere(['type', Message::TYPE_ABUSE]);
        return $this;
    }

    public function notAbuse(){
        $this->andWhere(['!=', 'type', Message::TYPE_ABUSE]);
        return $this;
    }

    public function error(){
        $this->andWhere(['type', Message::TYPE_ERROR]);
        return $this;
    }

    public function offer(){
        $this->andWhere(['type', Message::TYPE_OFFER]);
        return $this;
    }

    public function newmessages(){
        $this->andWhere(['status', Message::STATUS_NEW]);
        return $this;
    }

    public function inwork(){
        $this->andWhere(['status', Message::STATUS_INWORK]);
        return $this;
    }

    public function solved(){
        $this->andWhere(['status', Message::STATUS_SOLVED]);
        return $this;
    }

    public function thread($thread){
        $this->andWhere(['thread', $thread]);
        return $this;
    }
}
