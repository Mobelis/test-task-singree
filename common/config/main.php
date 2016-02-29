<?php
return [
    'name'=>'Тестовое задание',
  'language'       => 'ru',
  'sourceLanguage' => 'ru',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
      'i18n' => [
        'translations' => [
          'app' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'forceTranslation' => true,
          ],
        ],
      ],
      'reCaptcha' => [
        'name' => 'reCaptcha',
        'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
        'siteKey' => '6LewcBkTAAAAAN5QsvhARI6ww36euirxmxyBMsgO',
        'secret' => '6LewcBkTAAAAAPjeY2YGKzb5PjyJZzMk1BO90j-D',
      ],
      'mailer' => [
        'class' => 'yii\swiftmailer\Mailer',
        'useFileTransport' => false,
      ],
      'assetManager' => [
        'forceCopy'=>true,
        'appendTimestamp' => true,
      ],
    ],
];
