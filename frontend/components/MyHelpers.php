<?php
namespace frontend\components;

use Yii;
use yii\helpers\BaseFileHelper;

class MyHelpers {

    public static function SendAdminErrorMessage($text){
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,2);

        $message='Вызов:'.$trace[0]['file'].'['.$trace[0]['line'].'] '.$trace[1]['class'].' '.$trace[1]['function'].PHP_EOL;

        if(is_array($text) || is_object($text) || !is_string($text))
            $text=print_r($text,1);
        $message.=$text;

        Yii::$app->mailer->compose()
          ->setTo(Yii::$app->params['adminEmail'])
          ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['name']])
          ->setSubject('На сайте ошибка')
          ->setTextBody($message)
          ->send();
    }

    public static function createDirectory($path){
        BaseFileHelper::createDirectory(Yii::getAlias($path));
    }
}