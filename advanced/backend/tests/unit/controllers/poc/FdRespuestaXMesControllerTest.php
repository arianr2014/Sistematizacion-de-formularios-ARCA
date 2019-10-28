<?php

namespace backend\tests\unit\controllers\poc;

use Yii;
use backend\controllers\poc\FdRespuestaXMesController;


/**
 * FdRespuestaXMesControllerTest implementa las acciones a través del sistema CRUD para el modelo FdRespuestaXMes.
 */
class FdRespuestaXMesControllerTest extends \Codeception\Test\Unit
{
    public function _before()
    {
       // declaraciones y asignacion de valores que se deben tener para realizar las funciones test
    }

                                                               
                                                                                             
    protected function _after()                                                              
    {             
            // funcion que se ejecuta despues de los test                                                      
    }                
   
    
    public function testBehaviors()
    {
        //Se declara el objeto a verificar
        $tester = new  FdRespuestaXMesController('FdRespuestaXMesController','backend\controllers\poc\FdRespuestaXMesController');
        // se valida que se cree la instancia exitosamente
        $this->assertTrue($tester!=null);
        $this->assertInstanceOf('backend\controllers\poc\FdRespuestaXMesController', $tester);
        
        //Se realiza el llamado a la funcion
        $behaviors= $tester->behaviors();
        
        // Se evalua el caso exitoso
        $this->assertNotEmpty($behaviors,
            'Se devolvio vacio behaviors');  
                        
    }
    
    

    
    public function testActionProgress(){

        //Se declara el objeto a verificar
        $tester = new  FdRespuestaXMesController('FdRespuestaXMesController','backend\controllers\poc\FdRespuestaXMesController');
        // se valida que se cree la instancia exitosamente
        $this->assertTrue($tester!=null);
        $this->assertInstanceOf('backend\controllers\poc\FdRespuestaXMesController', $tester);

        // Se declaran las variables, $urlroute=null,$id=null para el reenvio de la barra de progreso, la llave tiene valor por defecto null, si se desea se puede cambiar por una llave. 
        $urlroute='/fdrespuestaxmes/index';
        $id=null;
        
        //Se ejecuta la funcion y se espera que realice todo
        expect_that($tester->actionProgress($urlroute,$id));
        
    }

	
	
    /**
     * Listado todos los datos del modelo FdRespuestaXMes que se encuentran en el tablename.
     * @return mixed
     */
    public function testActionIndex()
    {
    
        //Se declara el objeto a verificar
        $tester = new  FdRespuestaXMesController('FdRespuestaXMesController','backend\controllers\poc\FdRespuestaXMesController');
        // se valida que se cree la instancia exitosamente
        $this->assertTrue($tester!=null);
        $this->assertInstanceOf('backend\controllers\poc\FdRespuestaXMesController', $tester);
        
        
            // Se declaran los $queryParams a enviar los filtros
            $queryParams = ['FdRespuestaXMesSearch' => [
                                             'ene_decimal' => "Ingresar valor de pruebas para el campo ene_decimal de tipo numeric",       
                                              'feb_decimal' => "Ingresar valor de pruebas para el campo feb_decimal de tipo numeric",       
                                              'mar_decimal' => "Ingresar valor de pruebas para el campo mar_decimal de tipo numeric",       
                                              'abr_decimal' => "Ingresar valor de pruebas para el campo abr_decimal de tipo numeric",       
                                              'may_decimal' => "Ingresar valor de pruebas para el campo may_decimal de tipo numeric",       
                                              'jun_decimal' => "Ingresar valor de pruebas para el campo jun_decimal de tipo numeric",       
                                              'jul_decimal' => "Ingresar valor de pruebas para el campo jul_decimal de tipo numeric",       
                                              'ago_decimal' => "Ingresar valor de pruebas para el campo ago_decimal de tipo numeric",       
                                              'sep_decimal' => "Ingresar valor de pruebas para el campo sep_decimal de tipo numeric",       
                                              'oct_decimal' => "Ingresar valor de pruebas para el campo oct_decimal de tipo numeric",       
                                              'nov_decimal' => "Ingresar valor de pruebas para el campo nov_decimal de tipo numeric",       
                                              'dic_decimal' => "Ingresar valor de pruebas para el campo dic_decimal de tipo numeric",       
                                              'id_respuesta' => "Ingresar valor de pruebas para el campo id_respuesta de tipo int4",       
                                              'id_pregunta' => "Ingresar valor de pruebas para el campo id_pregunta de tipo int4",       
                                              'descripcion' => "Ingresar valor de pruebas para el campo descripcion de tipo varchar",       
                                              'id_opcion_select' => "Ingresar valor de pruebas para el campo id_opcion_select de tipo int4",       
                                              'id_respuesta_x_mes' => "Ingresar valor de pruebas para el campo id_respuesta_x_mes de tipo int4",       
                              ]];
             
       
        // Se obtiene el resultado de action index     
        Yii::$app->request->queryParams=$queryParams;
       
        $actionIndex=Yii::$app->runAction('FdRespuestaXMesController/index');
   
        $this->assertNotNull($actionIndex);
       
    }

   
    
    public function testActionView()
    {       
        //Se declara el objeto a verificar
        $tester = new  FdRespuestaXMesController('FdRespuestaXMesController','backend\controllers\poc\FdRespuestaXMesController');
        // se valida que se cree la instancia exitosamente
        $this->assertTrue($tester!=null);
        $this->assertInstanceOf('backend\controllers\poc\FdRespuestaXMesController', $tester);
        
        // se deben declarar los valores de $id para enviar la llave
        
                        $id = 'valor adecuado para el tipo de dato del paramtero $id';
                                     // se realiza el action view, intrernamente usa la funcion findModel, a su vez utiliza el findone de Yii realizando la consulta con todos los valores de los parametros $id             
            $actionView=Yii::$app->runAction('FdRespuestaXMesController/view',['id' => $id]);
             
             // se evalua el caso exitoso
             $this->assertNotNull($actionView,                  
                    'Se devolvio nullo actionView ');  
 
    }

       
    public function testActionCreate()
    {
    
        //Se declara el objeto a verificar
        $tester = new  FdRespuestaXMesController('FdRespuestaXMesController','backend\controllers\poc\FdRespuestaXMesController');
        // se valida que se cree la instancia exitosamente
        $this->assertTrue($tester!=null);
        $this->assertInstanceOf('backend\controllers\poc\FdRespuestaXMesController', $tester);
             
          
            // Se declaran los $queryParams a enviar los datos a crear
            $queryParams = ['FdRespuestaXMesController' => [
                                             'ene_decimal' => "Ingresar valor de pruebas para el campo ene_decimal de tipo numeric",       
                                              'feb_decimal' => "Ingresar valor de pruebas para el campo feb_decimal de tipo numeric",       
                                              'mar_decimal' => "Ingresar valor de pruebas para el campo mar_decimal de tipo numeric",       
                                              'abr_decimal' => "Ingresar valor de pruebas para el campo abr_decimal de tipo numeric",       
                                              'may_decimal' => "Ingresar valor de pruebas para el campo may_decimal de tipo numeric",       
                                              'jun_decimal' => "Ingresar valor de pruebas para el campo jun_decimal de tipo numeric",       
                                              'jul_decimal' => "Ingresar valor de pruebas para el campo jul_decimal de tipo numeric",       
                                              'ago_decimal' => "Ingresar valor de pruebas para el campo ago_decimal de tipo numeric",       
                                              'sep_decimal' => "Ingresar valor de pruebas para el campo sep_decimal de tipo numeric",       
                                              'oct_decimal' => "Ingresar valor de pruebas para el campo oct_decimal de tipo numeric",       
                                              'nov_decimal' => "Ingresar valor de pruebas para el campo nov_decimal de tipo numeric",       
                                              'dic_decimal' => "Ingresar valor de pruebas para el campo dic_decimal de tipo numeric",       
                                              'id_respuesta' => "Ingresar valor de pruebas para el campo id_respuesta de tipo int4",       
                                              'id_pregunta' => "Ingresar valor de pruebas para el campo id_pregunta de tipo int4",       
                                              'descripcion' => "Ingresar valor de pruebas para el campo descripcion de tipo varchar",       
                                              'id_opcion_select' => "Ingresar valor de pruebas para el campo id_opcion_select de tipo int4",       
                                              'id_respuesta_x_mes' => "Ingresar valor de pruebas para el campo id_respuesta_x_mes de tipo int4",       
                              ]];
                            
       //       Se declaran el post1
            Yii::$app->request->queryParams =  $queryParams;
                            
            // se valida que se pueda realizar la insercion del registro
            $actionCreate=Yii::$app->runAction('FdRespuestaXMesController/create');
             
            // se evalua el caso exitoso
            $this->assertNotNull($actionCreate,
                    'Se devolvio nullo actionCreate ');  
       
    }

    
  
    public function testActionUpdate($id)
    {
        //Se declara el objeto a verificar
        $tester = new  FdRespuestaXMesController('FdRespuestaXMesController','backend\controllers\poc\FdRespuestaXMesController');
        // se valida que se cree la instancia exitosamente
        $this->assertTrue($tester!=null);
        $this->assertInstanceOf('backend\controllers\poc\FdRespuestaXMesController', $tester);
        
        
        // Se declaran los $queryParams a enviar los datos a actualizar
          $queryParams = ['FdRespuestaXMesController' => [
                                         'ene_decimal' => "Ingresar valor de pruebas para el campo ene_decimal de tipo numeric",       
                                          'feb_decimal' => "Ingresar valor de pruebas para el campo feb_decimal de tipo numeric",       
                                          'mar_decimal' => "Ingresar valor de pruebas para el campo mar_decimal de tipo numeric",       
                                          'abr_decimal' => "Ingresar valor de pruebas para el campo abr_decimal de tipo numeric",       
                                          'may_decimal' => "Ingresar valor de pruebas para el campo may_decimal de tipo numeric",       
                                          'jun_decimal' => "Ingresar valor de pruebas para el campo jun_decimal de tipo numeric",       
                                          'jul_decimal' => "Ingresar valor de pruebas para el campo jul_decimal de tipo numeric",       
                                          'ago_decimal' => "Ingresar valor de pruebas para el campo ago_decimal de tipo numeric",       
                                          'sep_decimal' => "Ingresar valor de pruebas para el campo sep_decimal de tipo numeric",       
                                          'oct_decimal' => "Ingresar valor de pruebas para el campo oct_decimal de tipo numeric",       
                                          'nov_decimal' => "Ingresar valor de pruebas para el campo nov_decimal de tipo numeric",       
                                          'dic_decimal' => "Ingresar valor de pruebas para el campo dic_decimal de tipo numeric",       
                                          'id_respuesta' => "Ingresar valor de pruebas para el campo id_respuesta de tipo int4",       
                                          'id_pregunta' => "Ingresar valor de pruebas para el campo id_pregunta de tipo int4",       
                                          'descripcion' => "Ingresar valor de pruebas para el campo descripcion de tipo varchar",       
                                          'id_opcion_select' => "Ingresar valor de pruebas para el campo id_opcion_select de tipo int4",       
                                          'id_respuesta_x_mes' => "Ingresar valor de pruebas para el campo id_respuesta_x_mes de tipo int4",       
                          ]];
        
        
         // se deben declarar los valores de $id para enviar la llave
                         $id = 'valor adecuado para el tipo de dato del paramtero $id';
                                
        
         // se valida que se pueda realizar el update del registro
                                     
        $actionUpdate=Yii::$app->runAction('FdRespuestaXMesController/update',['id' => $id]);

        // se evalua el caso exitoso
        $this->assertNotNull($actionUpdate,
               'Se devolvio nullo actionUpdate ');  
 
    }


    
    
    public function testActionDeletep($id)
    {
    
        //Se declara el objeto a verificar
        $tester = new  FdRespuestaXMesController('FdRespuestaXMesController','backend\controllers\poc\FdRespuestaXMesController');
        // se valida que se cree la instancia exitosamente
        $this->assertTrue($tester!=null);
        $this->assertInstanceOf('backend\controllers\poc\FdRespuestaXMesController', $tester);
        
        
        // se deben llenar los siguientes valores para indicar el registro a borrar
                         $id = 'valor adecuado para el tipo de dato del paramtero $id';
                                
        // se valida que se pueda realizar el borrado del registro
         $actionDelete=Yii::$app->runAction('FdRespuestaXMesController/update',['id' => $id]);
             
             // se evalua el caso exitoso
             $this->assertNotNull($actionDelete,
                    'Se devolvio nullo actionDelete ');  


    }

    
}
