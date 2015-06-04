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
        'adminEmail' => 'admin@extweb.org',
    ],
];