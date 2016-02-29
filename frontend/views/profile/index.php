<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\DetailView;
use kartik\rating\StarRating;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $comment app\models\Comment */
/* @var $ratingModel app\models\RatingVoteUser */
/* @var $dataProviderComment yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'TITLE_PROFILE');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if(Yii::$app->user->getId()==$model->id): ?>
    <p>
        <?= Html::a(Yii::t('app', 'BUTTON_UPDATE'), ['update'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'BUTTON_CHANGE_PASSWORD'), ['password-change'], ['class' => 'btn btn-primary']) ?>
    </p>
    <?php endif ?>

    <div class="row">
        <div class="col-sm-3">
            <img class="img-responsive" alt="" src="<?= $model->getImage() ?>">
        </div>
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-4"><?=Yii::$app->user->getId()==$model->getId()?Yii::t('app', 'PROFILE_USER_RATING_CURRENT'):Yii::t('app', 'PROFILE_USER_RATING')?></div>
                <div class="col-sm-8 profile_user_rating">
                    <?php
                    echo StarRating::widget([
                      'name' => 'user_rating',
                      'value' => $model->getRating(),
                      'pluginOptions' => [
                        'size' => 'sm',
                        'theme' => 'krajee-uni',
                        'filledStar' => '&#x2605;',
                        'emptyStar' => '&#x2606;',
                        'displayOnly' => true

                      ]
                    ]);
                    ?>
                    <div class="rating_user_info"><?=$model->getRating().'/'.$model->rating_votes_col?></div>
                </div>
                <?php if(!Yii::$app->user->isGuest && Yii::$app->user->getId()!=$model->getId()): ?>
                <div class="col-sm-4"><?= Yii::t('app', 'PROFILE_USER_RATING_VOTE')?></div>
                <div class="col-sm-8 profile_user_rating_vote">
                    <?php
                    echo StarRating::widget([
                      'name' => 'user_rating_vote',
                      'value' => isset($model->voteUser->num)?$model->voteUser->num:0,
                      'pluginOptions' => [
                        'size' => 'sm',
                        'step' => 1,
                        'min' => 0,
                        'max' => 5,
                        'theme' => 'krajee-uni',
                        'filledStar' => '&#x2605;',
                        'emptyStar' => '&#x2606;',
                        'displayOnly' => isset($model->voteUser->num)?true:false,
                      ],
                      'pluginEvents' => [
                        "rating.change" => "function(event, value, caption) { ProfileUserRatingVote(event, value, caption,'".$model->getId()."')}",
                      ],
                    ]);
                    ?>
                </div>
                <?php endif ?>
            </div>
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
        <?php  if($model->id!=Yii::$app->user->getId() && !Yii::$app->user->isGuest): ?>

            <?= $this->render('_comment_form', [
              'model' => $comment,
            ]) ?>

        <?php endif ?>
        <?php if(Yii::$app->user->isGuest): ?> <h4><?= Yii::t('app', 'NO_AUTH_COMMENT_INFO')?></h4><?php endif ?>
        </div>
    </div>

</div>