<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'TITLE_BOARD');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-12">
        <h1><?= Html::encode($this->title) ?></h1>

        <?=
        ListView::widget([
          'dataProvider' => $dataProvider,
          'options' => [
            'tag' => 'div',
            'class' => 'row',
            'id' => 'list-wrapper',
          ],
          'layout' => '{items}<div class="col-sm-12">{pager}</div>',
          'itemView' => function ($model, $key, $index, $widget) {
              return $this->render('_list',['model' => $model]);

              // or just do some echo
              // return $model->title . ' posted by ' . $model->author;
          },
          'itemOptions' => [
            'class' => 'col-sm-6 col-md-4'
          ],
          'summaryOptions' => [
            'tag' => 'div',
            'class' => 'col-sm-12'
          ],
          'pager' => [
            'firstPageLabel' => 'first',
            'lastPageLabel' => 'last',
            'nextPageLabel' => 'next',
            'prevPageLabel' => 'previous',
            'maxButtonCount' => 3,
          ],
        ]);
        ?>

    </div>
</div>
