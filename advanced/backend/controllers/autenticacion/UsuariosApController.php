<?php

namespace backend\controllers\autenticacion;

use Yii;
use backend\controllers\autenticacion\UsuariosApControllerFachada;
use common\controllers\controllerspry\ControllerPry;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;	//Para presentar la barra de espera
use yii\helpers\Url;	//Para presentar la barra de espera
use yii\jui\ProgressBar;

/**
 * UsuariosApController implementa las acciones a través del sistema CRUD para el modelo UsuariosAp.
 */
class UsuariosApController extends ControllerPry
{
  //private $facade =    UsuariosApControllerFachada;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $facade =  new  UsuariosApControllerFachada;
        return $facade->behaviors();
    }
	
    
	
    /**Accion para la barra de progreso y render de nuevo a la vista
    Ubicación: @web = frontend\web....*/

    public function actionProgress($urlroute=null,$id=null){
            $facade =  new  UsuariosApControllerFachada;
            echo $facade->actionProgress($urlroute,$id);
    }

	
	
    /**
     * Listado todos los datos del modelo UsuariosAp que se encuentran en el tablename.
     * @return mixed
     */
    public function actionIndex()
    {
         $facade =  new  UsuariosApControllerFachada;
        $dataAndModel= $facade->actionIndex(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $dataAndModel['searchModel'],
            'dataProvider' => $dataAndModel['dataProvider'],
        ]);
    }

    /**
     * Presenta un dato unico en el modelo UsuariosAp.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $facade =  new  UsuariosApControllerFachada;
        return $this->render('view', [
            'model' => $facade->actionView($id),
        ]);
    }

    /**
     * Crea un nuevo dato sobre el modelo UsuariosAp .
     * Si se crea correctamente guarda setFlash, presenta la barra de progreso y envia a view con la confirmación de guardado.
     * @return mixed
     */
    public function actionCreate()
    {
       $facade =  new  UsuariosApControllerFachada;
       $modelE= $facade->actionCreate(Yii::$app->request->post(),Yii::$app->request->isAjax);
       $model = $modelE['model'];
        if ($modelE['create'] == 'True') {
			
            Yii::$app->session->setFlash('FormSubmitted','2');
            return  $this->redirect(['progress', 'urlroute' => 'view', 'id' => $model->id_usuario]);		
			
        }elseif($modelE['create']=='Ajax'){
             return $this->renderAjax('create', [
                        'model' => $model
            ]);
        } 
        else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    

    /**
     * Modifica un dato existente en el modelo UsuariosAp.
     * Si se modifica correctamente guarda setFlash, presenta la barra de progreso y envia a view con la confirmación de guardado..
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $facade =  new  UsuariosApControllerFachada;
        $modelE= $facade->actionUpdate($id,Yii::$app->request->post(),Yii::$app->request->isAjax);
        $model = $modelE['model'];

        if ($modelE['update'] == 'True') {
            
            Yii::$app->session->setFlash('FormSubmitted','1');		
            return $this->redirect(['progress', 'urlroute' => 'view', 'id' => $model->id_usuario]);
            
        }elseif($modelE['update']=='Ajax'){
            return $this->renderAjax('update', [
                        'model' => $model
            ]);
        } 
        else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing UsuariosAp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDeletep($id)
    {
        $facade =  new  UsuariosApControllerFachada;
        $facade->actionDeletep($id);

        return $this->redirect(['index']);
    }

    
}
