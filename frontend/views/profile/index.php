<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $comment app\models\Comment */
/* @var $dataProviderComment yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'TITLE_PROFILE');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <? if(Yii::$app->user->getId()==$model->id): ?>
    <p>
        <?= Html::a(Yii::t('app', 'BUTTON_UPDATE'), ['update'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'BUTTON_CHANGE_PASSWORD'), ['password-change'], ['class' => 'btn btn-primary']) ?>
    </p>
    <? endif ?>

    <div class="row">
        <div class="col-sm-3">
            <img class="img-responsive" alt="" src="<?= $model->getImage() ?>">
        </div>
        <div class="col-sm-6">
            <?= DetailView::widget([
              'model' => $model,
              'attributes' => [
                'username',
                'email:email',
                'created_at',
              ],
            ]) ?>
        </div>
        <div class="col-sm-12">
            <h3><?= Yii::t('app', 'TITLE_COMMENT')?></h3>
            <?= ListView::widget([
              'dataProvider' => $dataProviderComment,
              'itemView' => '_comment',
              'layout' => '{items}<div class="col-sm-12">{pager}</div>',
              'itemOptions' => [
                'class' => 'col-sm-12'
              ],
            ]);?>
        </div>
        <div class="col-sm-12">
        <?  if($model->id!=Yii::$app->user->getId() && !Yii::$app->user->isGuest): ?>

            <?= $this->render('_comment_form', [
              'model' => $comment,
            ]) ?>

        <?  endif ?>
        <? if(Yii::$app->user->isGuest): ?> <h4><?= Yii::t('app', 'NO_AUTH_COMMENT_INFO')?></h4><? endif ?>
        </div>
    </div>

</div>
