<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\pqrs\Pqrs */

$this->title = 'Formato de Denuncia';
$this->params['breadcrumbs'][] = ['label' => 'Pqrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pqrs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formdenuncia', [
        'model' => $model,
    ]) ?>

</div>