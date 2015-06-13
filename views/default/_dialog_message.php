<?php
/**
 * @var \common\models\User $model
 */


?>

<!-- Message. Default to the left -->
<div class="direct-chat-msg <?=$model->author_id == \Yii::$app->user->id?'right':'';?>">
    <div class="direct-chat-info clearfix">
        <span class="direct-chat-name pull-<?=$model->author_id == \Yii::$app->user->id?'right':'left';?>"><?=$model->author->name;?></span>
        <span class="direct-chat-timestamp pull-<?=$model->author_id == \Yii::$app->user->id?'left':'right';?>"><?=\common\helpers\String::getBackTime($model->created_at);?></span>
    </div><!-- /.direct-chat-info -->
    <img alt="message user image" src="<?=\common\models\Image::getImage($model->author->photo, 'main');?>" class="direct-chat-img"><!-- /.direct-chat-img -->
    <div class="direct-chat-text">
        <?=$model->text;?>
    </div><!-- /.direct-chat-text -->
</div><!-- /.direct-chat-msg -->

