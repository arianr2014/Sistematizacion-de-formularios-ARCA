<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\cda\CdaAnalisisHidrologicoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'CAnalisis Hidrológico';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cda-analisis-hidrologico-index">
<!----------------------------------Boton de Regresar---------------------------->
        <?php echo Html::button("Regresar",
                ['class'=>'btn btn-default btn-xs',
                    'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['cda/cda/pantallaprincipal']) . "';",
                    'data-toggle'=>'Regresar'
                ]
            ); ?>

        

    <h1 class="titSection"><?= Html::encode($this->title) ?>
        <p style="display: inline-block;">
         <?php if($validaciones['editar'] == TRUE){ echo
             Html::button('Agregar', 
        ['value' =>Url::to(['cda/cda-analisis-hidrologico/create','id_cda'=>$id_cda,'id_cactividad_proceso'=>$id_cactividad_proceso]), 'title' => 'Nuevo Analisis Hidrologico',
         'class' => 'showModalButton btn btn-success']);} ?>
        </p>
    </h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <table class="table table-bordered">
            <tr>
                <td class="datosbasicos1"> Número CDA </td>
                <td class="datosbasicos2">
                    <table width="100%">
                        <tr>
                            <td width="50%"><?= $encabezado[0]['numero']; ?></td>
                        </tr>
                    </table>
                </td>
                <td class="datosbasicos1"> Fecha Ingreso </td>
                <td class="datosbasicos2"><?= $encabezado[0]['fecha_ingreso'];  ?></td>
            </tr>
            <tr>
                <td class="datosbasicos1"> Número de Quipux Arca </td>
                <td class="datosbasicos2"><?= $encabezado[0]['arca']; ?></td>
                <td class="datosbasicos1"> Enviado por: </td>
                <td class="datosbasicos2"><?= $encabezado[0]['enviadopor']; ?></td>
            </tr>
            <tr>
                <td class="datosbasicos1"> Número de Quipux Senagua </td>
                <td class="datosbasicos2"><?= $encabezado[0]['senagua']; ?></td>
                <td class="datosbasicos1"> En calidad de: </td>
                <td class="datosbasicos2"><?= $encabezado[0]['encalidade']; ?></td>
            </tr>
            
            <tr>
                <td class="datosbasicos1"> Responsable </td>
                <td class="datosbasicos2"><?= $encabezado[0]['usuario_accion']; ?></td>
                <td class="datosbasicos1"> Fecha de Solicitud </td>
                <td class="datosbasicos2"><?= $encabezado[0]['fecha_solicitud']; ?></td>
            </tr>
            <tr>
                <td class="datosbasicos1"> Fecha Última Actividad </td>
                <td class="datosbasicos2"><?= $encabezado[0]['ult_fecha_actividad']; ?></td>
                <td class="datosbasicos1"> Fecha Último Estado </td>
                <td class="datosbasicos2"><?= $encabezado[0]['ult_fecha_estado']; ?></td>
            </tr>
        </table>
    <div class="aplicativo">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id_analisis_hidrologico',
            [
                'label'=>'Cartografía',
                'attribute' =>'id_cartografia',
                'filter'=> yii\helpers\ArrayHelper::map(\common\models\cda\CdaCartografia::find()->asArray()->all(), 'id_cartografia', 'nom_cartografia'),
            ],
            [
                'label'=>'Estación hidrológica base',
                'attribute' =>'id_ehidrografica',
                'filter'=> yii\helpers\ArrayHelper::map(\common\models\cda\CdaEstacionHidrologica::find()->asArray()->all(), 'id_cartografia', 'nom_cartografia'),
            ],
            [
                'label'=>'Estación Meteorológica base',
                'attribute' =>'id_emeteorologica',
                'filter'=> yii\helpers\ArrayHelper::map(\common\models\cda\CdaEstacionMeteorologica::find()->asArray()->all(), 'id_emeteorologica', 'nom_emeteorologica'),
            ],
            [
                'label'=>'Metodologia',
                'attribute' =>'id_metodologia',
                'filter'=> yii\helpers\ArrayHelper::map(\common\models\cda\CdaMetodologia::find()->asArray()->all(), 'id_metodologia', 'nom_metodologia'),
            ],
            [
                'label'=>'Probabilidad de excedencia obtenida',
                'attribute' =>'probabilidad',
               
            ],

             'observacion',

            [
			
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Acción',
                'template' => ' {update}',
                'visibleButtons' => [
                    'update' => $validaciones['editar'], // or whatever condition
                    
                ],
                'buttons' => [
                    
                    'update' => function ($url, $model) {
                            return Html::button('<span class="glyphicon glyphicon-pencil">Editar</span>',  ['value'=>$url,
                                         'class' => 'btn btn-default btn-xs showModalButton',
                            ]);
                    }, //Primera columna encontrada id_analisis_hidrologico                    
                   
                                        
                                        
            ],
			
			
	],
        ],
    ]); ?>
    </div>
</div>
