<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 11.06.2015
 * Time: 11:33
 */

namespace tigokr\tickets;

use yii\db\ActiveRecord;

class NotifiedBehavior extends \yii\base\Behavior {
    public $notificationsStack = [];

    public function __construct() {

    }

    public function events(){
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'restoreNotifications',
        ];
    }

    public function receive($message, $priority = 'default'){

    }

    public function saveStack(){

    }

    public function restoreNotifications(){
        d($this->owner);die;
    }
}