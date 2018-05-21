<?php

namespace frontend\controllers\cda;

use Yii;
use common\models\cda\PsActividadQuipux;
use common\models\cda\PsActividadQuipuxSearch;
use common\controllers\controllerspry\FachadaPry;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;	//Para presentar la barra de espera
use yii\helpers\Url;	//Para presentar la barra de espera
use yii\jui\ProgressBar;

/**
 * PsactividadquipuxControllerFachada implementa la verificaicon de los valores, consulta información para aplicar reglas de negocio, y transacciones conforme las acciones para el modelo PsActividadQuipux.
 */
class PsActividadQuipuxControllerFachada extends FachadaPry
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
     * Listado todos los datos del modelo PsActividadQuipux que se encuentran en el tablename.
     * @return mixed
     */
    public function actionIndex($queryParams)
    {
        $searchModel = new PsActividadQuipuxSearch();
        $dataProvider = $searchModel->search($queryParams);
       
        return [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
            ];
    }
    
    
    /*Listado de los datos que se solicitan en la ventana oficios relacionados del modulo detalle
     * proceso, se saca consulta aparte para no modificar el codigo por defecto
     */
    
    public function actionIndexdetproceso($queryParams)
    {
        $searchModel = new PsActividadQuipuxSearch();

        if(!empty($queryParams['id_cproceso'])){
            $searchModel->id_cproceso = $queryParams['id_cproceso'];
        }

        $dataProvider = $searchModel->searchdetproceso($queryParams);
        
         return [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
            ];
    
    }    

    /**
     * Presenta un dato unico en el modelo PsActividadQuipux.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {       
            return $this->findModel($id);
 
    }

    /**
     * Crea un nuevo dato sobre el modelo PsActividadQuipux .
     * Si se crea correctamente guarda setFlash, presenta la barra de progreso y envia a view con la confirmación de guardado.
     * @return mixed
     */
    public function actionCreate($request,$isAjax)
    {
        $model = new PsActividadQuipux();

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
     * Modifica un dato existente en el modelo PsActividadQuipux.
     * Si se modifica correctamente guarda setFlash, presenta la barra de progreso y envia a view con la confirmación de guardado..
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id,$request,$isAjax)
    {
        $model = $this->findModel($id);

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
     * Deletes an existing PsActividadQuipux model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeletep($id)
    {
        $this->findModel($id)->delete();

    }

    /**
     * Finds the PsActividadQuipux model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PsActividadQuipux the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
                    if (($model = PsActividadQuipux::findOne($id)) !== null) {
                        return $model;
                    } else {
                        throw new NotFoundHttpException('The requested page does not exist.');
                    }
    }
    
    
    public function getActividadQuipux($id_cproceso){
        return PsActividadQuipux::find()
                ->where(['id_cproceso'=>$id_cproceso])
                ->orderBy(['fecha_actividad_quipux'=> SORT_DESC])
                ->one();
    }
}
