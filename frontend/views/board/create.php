<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Callboard */

$this->title = Yii::t('app', 'Create Callboard');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Callboards'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="callboard-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
