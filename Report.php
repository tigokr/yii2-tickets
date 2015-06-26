<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 26.06.2015
 * Time: 18:50
 */

namespace tigokr\tickets;


class Report extends \yii\bootstrap\Widget {
    public function run(){
        return $this->render('widgets/report');
    }

}