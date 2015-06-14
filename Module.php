<?php

namespace tigokr\tickets;

use yii\helpers\ArrayHelper;

class Module extends \yii\base\Module implements \yii\base\BootstrapInterface
{
    public $controllerNamespace = 'tigokr\tickets\controllers';
    public $defaultRoute = 'default/index';

    public $userModel;

    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application) {
/*            $app->getUrlManager()->addRules([
                $this->id . '/<controller>/<action>' => $this->id . '/<controller>/<action>',
                $this->id . '/<controller>' => $this->id . '/<controller>/index',
                $this->id => $this->id . '/' . $this->defaultRoute,
            ], false);*/

            if(isset($this->params['topMenu']))
                $this->addTopMenu($this->params['topMenu']);

            if(isset($this->params['mainMenu']))
                $this->addMineMenu($this->params['mainMenu']);


        }
    }

    public function init()
    {
        parent::init();
        \Yii::configure($this, require(__DIR__ . '/config.php'));
    }

    // abstract method
    public function getUser(){
        if(!empty($this->userModel))
            return new $this->userModel;
        else
            return null;
    }

    private function addTopMenu($topMenu)
    {
        $this->module->params['topMenu'] = ArrayHelper::merge($topMenu, $this->module->params['topMenu']);
    }

    private function addMineMenu($mainMenu)
    {
        $this->module->params['mainMenu'] = ArrayHelper::merge($this->module->params['mainMenu'], $mainMenu);
    }
}
