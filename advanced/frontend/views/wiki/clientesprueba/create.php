<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Clientesprueba */

$this->title = 'Create Clientesprueba';
$this->params['breadcrumbs'][] = ['label' => 'Clientespruebas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clientesprueba-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
