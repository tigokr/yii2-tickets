<?php
return [
    'components'=>[
        'mailer'=>[
            'class' => 'yii\swiftmailer\Mailer',
//          'useFileTransport' => true,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'localhost',
                'port' => '25',
            ],
        ],
    ],
    'params'=>[
        'adminEmail' => [
            'admin@extweb.org',
            'vysg81@gmail.com',
        ],
        'noreplyEmail' => 'noreply@c21center.ru',

        'topMenu' => [
            'tickets' => ['label' => '<i class="fa fa-envelope-o"></i>', 'url' => '#', 'options' => ['class' => 'dropdown tasks-menu'], 'linkOptions' => ['aria-expanded' => 'false', 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'], 'encode' => false, 'items' => [
                ['label' => false, 'url' => null, 'items' => [
                    ['label' => '&nbsp;<i class="fa fa-lightbulb-o text-success"></i> &nbsp;Внести предложение', 'url' => ['/tickets/default/message', 'type' => 30]],
                    ['label' => '&nbsp;<i class="fa fa-exclamation text-warning"></i> &nbsp;Пожаловаться', 'url' => ['/tickets/default/message', 'type' => 20]],
                    ['label' => '<i class="fa fa-exclamation-triangle text-danger"></i> Сообщить об ошибке', 'url' => ['/tickets/default/message', 'type' => 10]],
                ], 'itemsOptions' => ['class' => 'menu'],],
            ], 'itemsOptions' => ['class' => 'dropdown-menu']],
        ],
        'mainMenu' => [
            ['label' => '<i class="fa fa-envelope"></i> Сообщения', 'url' => ['/tickets/default/index'], 'visible' => \Yii::$app->user->can('employee')],
        ]
    ],
];