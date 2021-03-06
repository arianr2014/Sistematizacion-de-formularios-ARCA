<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\poc\FdDatosAguaPotableSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fd-datos-agua-potable-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_datos_agua_potable') ?>

    <?= $form->field($model, 'comunidad') ?>

    <?= $form->field($model, 'viviendas_existentes') ?>

    <?= $form->field($model, 'viviendas_agua_potable') ?>

    <?= $form->field($model, 'viviendas_medidores') ?>

    <?php // echo $form->field($model, 'id_conjunto_respuesta') ?>

    <?php // echo $form->field($model, 'id_pregunta') ?>

    <?php // echo $form->field($model, 'id_respuesta') ?>

    <?php // echo $form->field($model, 'id_capitulo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
