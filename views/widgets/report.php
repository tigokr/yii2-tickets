<?php
use yii\helpers\Url;

$dialogs = \tigokr\tickets\models\Message::find()->start()->error()->notSolved()->own()->all();
if($dialogs) :
?>


<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Открытые тикеты</h3>
    </div><!-- /.box-header -->
    <div class="box-body no-padding">
        <div class="table-responsive">
            <table class="table no-margin">
                <thead>
                <tr>
                    <th>Тема</th>
                    <th>Последнее сообщение</th>
                    <th>Дата</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($dialogs as $model): ?>
                    <tr>
                        <td><a href="<?=Url::to($model->dialogUrl);?>"><?=$model->text;?></a></td>
                        <td><?php
                            $lastMessage = $model->getDialog()->last()->one();
                            echo $lastMessage->text;
                        ?></td>
                        <td><?=$lastMessage->created_at;?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div><!-- /.box-body -->
    <div class="box-footer clearfix">
        <a class="btn btn-sm btn-default btn-flat pull-right" href="<?=Url::to(['/tickets/default/index']);?>">Все сообщения</a>
    </div><!-- /.box-footer -->
</div><!-- /.box -->

<?php endif; ?>