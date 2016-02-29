<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Callboard */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'TITLE_BOARD'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<article class="">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if(Yii::$app->user->getId()==$model->user_id): ?>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?php endif ?>
    </p>


    <a class="blog-img-wrapper" href="<?= Url::to(['view', 'id' => $model->id]) ?>">
            <img class="img-responsive" alt="" src="<?= $model->getImage() ?>">
    </a>
    <div class="caption">
        <h3><a href="<?= Url::to(['view', 'id' => $model->id]) ?>"><?= Html::encode($model->title) ?></a></h3>
        <p class="text-info"><?= Yii::$app->formatter->asDate($model->created_at, 'php:d.m.Y'); ?></p>
        <p class="text-darker"><?= $model->text ?></p>
    </div>
