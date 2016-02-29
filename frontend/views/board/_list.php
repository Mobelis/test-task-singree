<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\helpers\StringHelper;
?>

    <article class="">
        <a class="blog-img-wrapper" href="<?= Url::to(['view', 'id' => $model->id]) ?>">
            <img class="img-responsive" alt="" src="<?= $model->getImage() ?>">
        </a>
        <div class="caption">
            <h3><a href="<?= Url::to(['view', 'id' => $model->id]) ?>"><?= Html::encode($model->title) ?></a></h3>
            <p class="text-success"><?= Yii::$app->formatter->asDate($model->created_at, 'php:d.m.Y'); ?> [<?= Html::a($model->user->username,['profile/index','id'=>$model->user->id])?>]</p>
            <p class="text-darker"><?= StringHelper::truncate(HtmlPurifier::process($model->text),100); ?></p>
        </div>
    </article>