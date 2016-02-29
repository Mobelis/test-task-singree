<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\helpers\StringHelper;
?>

<p class="text-success"><?= Yii::$app->formatter->asDate($model->created_at, 'php:H:i d.m.Y '); ?> [<?= Html::a($model->userComment->username,['profile/index','id'=>$model->userComment->id])?>]</p>
<p class="text-darker"><?= StringHelper::truncate(HtmlPurifier::process($model->text),100); ?></p>