<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\poc\FdTipoCondicion */

$this->title = 'Update Fd Tipo Condicion: ' . $model->id_tcondicion;
$this->params['breadcrumbs'][] = ['label' => 'Fd Tipo Condicions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_tcondicion, 'url' => ['view', 'id' => $model->id_tcondicion]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fd-tipo-condicion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
