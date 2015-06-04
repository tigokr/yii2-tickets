<?php

namespace tigokr\tickets;

class Module extends \yii\base\Module implements \yii\base\BootstrapInterface
{
    public $controllerNamespace = 'tigokr\tickets\controllers';
    public $defaultRoute = 'default';

    public $behaviors;

    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application) {
            $app->getUrlManager()->addRules([
                $this->id => $this->id . '/' . $this->defaultRoute,
                $this->id . '/<controller>' => $this->id . '/<controller>/index',
                $this->id . '/<controller>/<action>' => $this->id . '/<controller>/<action>',
            ], false);
        }
    }

    public function init()
    {
        parent::init();

        \Yii::configure($this, require(__DIR__ . '/config.php'));
    }
}