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
//
//            'notifications' => ['label' => '<i class="fa fa-bell-o"></i>', 'url' => '#', 'options' => ['class' => 'dropdown tasks-menu', 'title'=>'Уведомления'], 'linkOptions' => ['aria-expanded' => 'false', 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'], 'encode' => false, 'items' => [
//                ['label' => false, 'url' => null, 'items' => [
//                    ['label' => '&nbsp;<i class="fa fa-comment"></i> &nbsp;Отправить сообщение', 'url' => ['/tickets/default/dialog']],
//                ], 'itemsOptions' => ['class' => 'menu'],],
//            ], 'itemsOptions' => ['class' => 'dropdown-menu']],

//            'messages' => ['label' => '<i class="fa fa-comment-o"></i>', 'url' => '#', 'options' => ['class' => 'dropdown tasks-menu', 'title'=>'Личное сообщение'], 'linkOptions' => ['aria-expanded' => 'false', 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'], 'encode' => false, 'items' => [
//                ['label' => false, 'url' => null, 'items' => [
//                    ['label' => '&nbsp;<i class="fa fa-comment"></i> &nbsp;Отправить сообщение', 'url' => ['/tickets/default/dialog']],
//                ], 'itemsOptions' => ['class' => 'menu'],],
//            ], 'itemsOptions' => ['class' => 'dropdown-menu']],

            'tickets' => ['label' => '<i class="fa fa-envelope-o"></i>', 'url' => '#', 'options' => ['class' => 'dropdown tasks-menu', 'title'=>'Тикеты, сообщения об ошибках'], 'linkOptions' => ['aria-expanded' => 'false', 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'], 'encode' => false, 'items' => [
                ['label' => false, 'url' => null, 'items' => [
                    ['label' => '&nbsp;<i class="fa fa-lightbulb-o"></i> &nbsp;Внести предложение', 'url' => ['/tickets/default/message', 'type' => 30]],
                    ['label' => '&nbsp;<i class="fa fa-exclamation"></i> &nbsp;Пожаловаться', 'url' => ['/tickets/default/message', 'type' => 20]],
                    ['label' => '<i class="fa fa-bug"></i> Сообщить об ошибке', 'url' => ['/tickets/default/message', 'type' => 10]],
                ], 'itemsOptions' => ['class' => 'menu'],],
            ], 'itemsOptions' => ['class' => 'dropdown-menu']],

        ],
        'mainMenu' => [
            ['label' => '<i class="fa fa-envelope"></i> Сообщения', 'url' => ['/tickets/default/index'], 'visible' => \Yii::$app->user->can('employee')],
        ]
    ],
];