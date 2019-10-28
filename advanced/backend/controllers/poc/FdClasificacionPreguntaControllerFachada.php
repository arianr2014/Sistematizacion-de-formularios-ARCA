<?php

namespace backend\controllers\poc;

use Yii;
use common\models\poc\FdClasificacionPregunta;
use common\models\poc\FdClasificacionPreguntaSearch;
use common\controllers\controllerspry\FachadaPry;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;	//Para presentar la barra de espera
use yii\helpers\Url;	//Para presentar la barra de espera
use yii\jui\ProgressBar;

/**
 * FdClasificacionPreguntaControllerFachada implementa la verificaicon de los valores, consulta información para aplicar reglas de negocio, y transacciones conforme las acciones para el modelo FdClasificacionPregunta.
 */
class FdClasificacionPreguntaControllerFachada extends FachadaPry
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
	
	
	/**Accion para la barra de progreso y render de nuevo a la vista
	Ubicación: @web = frontend\web....*/
	
	public function actionProgress($urlroute=null,$id=null){
		
	
            $progressbar = "<div style='margin-top:20%;text-align:center'>".Html::img('@web/images/lazul.gif')."</div>"; 
            $progressbar = $progressbar . "<div style='text-align:center'>Guardando</div>";
            $progressbar = $progressbar .  "<meta http-equiv='refresh' content='3;".Url::toRoute([$urlroute, 'id' => $id])."'>";
            return $progressbar;
	}

	
	
    /**
     * Listado todos los datos del modelo FdClasificacionPregunta que se encuentran en el tablename.
     * @return mixed
     */
    public function actionIndex($queryParams)
    {
                $searchModel = new FdClasificacionPreguntaSearch();
                $dataProvider = $searchModel->search($queryParams);

                return [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
                ];
            }

    /**
     * Presenta un dato unico en el modelo FdClasificacionPregunta.
     * @param integer $id_clasificacion
     * @param integer $id_pregunta
     * @return mixed
     */
    public function actionView($id_clasificacion, $id_pregunta)
    {       
            return $this->findModel($id_clasificacion, $id_pregunta);
 
    }

    /**
     * Crea un nuevo dato sobre el modelo FdClasificacionPregunta .
     * Si se crea correctamente guarda setFlash, presenta la barra de progreso y envia a view con la confirmación de guardado.
     * @return mixed
     */
    public function actionCreate($request,$isAjax)
    {
        $model = new FdClasificacionPregunta();

        if ($model->load($request) && $model->save()) {
			
                return [
                    'model' => $model,
                    'create' => 'True'
                ];	

        }
        elseif ($isAjax) {
        
                return [
                    'model' => $model,
                    'create' => 'Ajax'
                ];	
           
        }  
        
        else {
        
                 return [
                    'model' => $model,
                    'create' => 'False'
                ];

        }
    }

    /**
     * Modifica un dato existente en el modelo FdClasificacionPregunta.
     * Si se modifica correctamente guarda setFlash, presenta la barra de progreso y envia a view con la confirmación de guardado..
     * @param integer $id_clasificacion
     * @param integer $id_pregunta
     * @return mixed
     */
    public function actionUpdate($id_clasificacion, $id_pregunta,$request,$isAjax)
    {
        $model = $this->findModel($id_clasificacion, $id_pregunta);

        if ($model->load($request) && $model->save()) {
			
			
			return [
                            'model' => $model,
                            'update' => 'True'
                        ];
        } 
         elseif ($isAjax) {
        
                return [
                    'model' => $model,
                    'update' => 'Ajax'
                ];	
           
        }  
        else {
                         return [
                            'model' => $model,
                            'update' => 'False'
                        ];
        }
    }

    /**
     * Deletes an existing FdClasificacionPregunta model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id_clasificacion
     * @param integer $id_pregunta
     * @return mixed
     */
    public function actionDeletep($id_clasificacion, $id_pregunta)
    {
        $this->findModel($id_clasificacion, $id_pregunta)->delete();

    }

    /**
     * Finds the FdClasificacionPregunta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id_clasificacion
     * @param integer $id_pregunta
     * @return FdClasificacionPregunta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_clasificacion, $id_pregunta)
    {
                    if (($model = FdClasificacionPregunta::findOne(['id_clasificacion' => $id_clasificacion, 'id_pregunta' => $id_pregunta])) !== null) {
                        return $model;
                    } else {
                        throw new NotFoundHttpException('The requested page does not exist.');
                    }
    }
}