<?php

namespace console\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;	
use yii\helpers\Url;	
use yii\jui\ProgressBar;

use common\controllers\controllerspry\ControllerPry;
use backend\controllers\notificaciones\TarTareaProgramadaControllerFachada;
use backend\controllers\notificaciones\CorCorreoControllerFachada;
use backend\controllers\notificaciones\CorParametroControllerFachada;
use backend\controllers\notificaciones\CorDestinatarioControllerFachada;
use backend\controllers\notificaciones\CorMensajeEnviadoControllerFachada;


class ImplTareaProgramadaController extends \yii\console\Controller
{
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            
        ];
    }
    /**
     * 
     * @return type TarTareaProgramada
     * Consulta las tareas programadas que esten activas
     */
    public function actionEjecutaTareas(){
        $facadeTareas =  new  TarTareaProgramadaControllerFachada();
        $tarTareas = $facadeTareas->cargaTareas(['estado'=>'s']);
        $hoy = date("d-m-Y",time());
        $proxima= date("d-m-Y",time());
       
        foreach ($tarTareas as $tarea){
            
            $fechaEjecucion=date("d-m-Y",time());
            $fecha_termina=date("d-m-Y",time());
            $ahora=strtotime($hoy);
            
            if(is_null($tarea['fecha_proxima_eje'])){//se asigna fecha de proxima ejecucion en caso de que este null
                $fechaEjecucion=strtotime ( '+'.$tarea['intervalo_ejecucion']. ' second' , strtotime ($tarea['fecha_inicio'])); 
                $proxima=date("d-m-Y",strtotime ( '+'.$tarea['intervalo_ejecucion']. ' second' , strtotime ($tarea['fecha_inicio'])));
                
            } else{
                $fechaEjecucion=strtotime($tarea['fecha_proxima_eje']);
                $proxima=date("d-m-Y",strtotime ( '+'.$tarea['intervalo_ejecucion']. ' second' , strtotime ($tarea['fecha_proxima_eje'])));
            }
            if(is_null($tarea['fecha_termina'])){ // se asigna la fecha de terminacion si esta fue ingresada en la base de datos, de lo contratio se suma un dia a la fecha actual
                $fecha_termina=strtotime ( '+1 day' , strtotime ($hoy));
                
            }else{
                $fecha_termina=strtotime ($tarea['fecha_termina']);                 
            } 
            
//            echo "<br>termina ".$fecha_termina;
//            echo "<br>ejecuta ".$fechaEjecucion;
//            echo "<br>ahora ".$ahora;
           // echo strtotime ( '+'.$tarea['intervalo_ejecucion']. ' second' , strtotime ($tarea['fecha_inicio']));
            if($ahora <= $fecha_termina && $ahora >= $fechaEjecucion ){
                
                $this->tareas($tarea);
                
            }
        }
            
            /// SI la fecha >= proxima ejecucion, o si es nullo y fecha < fech_inicio+ intervalo y todo lo anterior < fecha_termina
            // se consultan los correos activos a enviar
            // por cada correo se cargan los paramtros, tomando la consulta sql o de lo contrario el valor por defecto, si el tipo es ciclico, se toma el nombre del parametro y se colca un consecutivo por cada columna, si es de otro tipo se toma la primera columna
            // con los parametros se procede a realizar la busqueda de los destinatarios, pasando como parametros los que se hayan consultado de ser necesario
            // se arma el array de acuerdo al tipo de destinatario
            // se adjunta el html 
            // se adjuntan los paramtros
            // se ejecuta la funcion de envio
            // se toma la fecha + intervalo y se actualiza, si esta fecha supera la fecha_termina, se cambia el estado a n de la tarea
    }
    
    
    private function tareas($tarea){
        $facadeCorreos= new CorCorreoControllerFachada();
        $corCorreos= $facadeCorreos->cargaCorreos(['id_tarea_programada' => $tarea['id_tarea_programada'], 'estado' => 's']);
        
        $parametros=array();
        
        foreach($corCorreos as $correo){
           $parametros= $this->parametros($correo);
           $destinatarios=$this->destinatarios($correo,$parametros);
         //  echo "<br>".var_dump($destinatarios);
          $this->envioCorreo($correo,$parametros,$destinatarios);
        }
        
    }
    
    private function destinatarios($correo,$parametros){
        $facadeDestinatarios = new CorDestinatarioControllerFachada();
        $corDestinatarios = $facadeDestinatarios->cargaDestinatarios(['id_correo'=>$correo['id_correo']]);
        $destinatarios=array();
        $countTipo=array();
        
        foreach($corDestinatarios as $destinatario){
            $tipoDestinatario=$facadeDestinatarios->nombreDestinatario($destinatario['id_destinatario'])->one();
                        
            //echo var_dump($tipoDestinatario);
            //if (count($countTipo[$tipoDestinatario->nom_tdestinatario])>0) {
            if (\yii\helpers\ArrayHelper::keyExists($tipoDestinatario->nom_tdestinatario, $countTipo)) {
                $countTipo[$tipoDestinatario->nom_tdestinatario]+=1;
            }else{
                $countTipo[$tipoDestinatario->nom_tdestinatario] =0;
            }
            
            if(trim($destinatario['val_defecto'])!==""){
                 $destinatarios[$tipoDestinatario->nom_tdestinatario][$countTipo[$tipoDestinatario->nom_tdestinatario]]=trim($destinatario['val_defecto']);
            }
            if(trim($destinatario['consulta_sql'])!==""){
               $patternIgual ='/=:\w*[\s]*/';// Si la consuta tiene un campo igual a un parametro ej select nombre, correo from usuarios where estado=:parametro
               $patternIn ='/[\s]*[(,][\s]*[:]\w+[\s]*/'; //si la consulta tiene un campo in una lista, dos posibilidades in(:listadodecampos) o in(:campo1,:campo2)
               $consulta=$destinatario['consulta_sql'];
               
               //Busqueda de parametros =:
               preg_match($patternIgual, $consulta,$paraBusqueda);
            //   echo $consulta;
           //    echo var_dump($paraBusqueda);
               $bindParametros=array();
               foreach($paraBusqueda as $busqueda){
                    $bindParametros[]= $this->buscaParametros($parametros, $busqueda, "=:");
                     //echo var_dump($busqueda);
               }
                
                
               preg_match($patternIn, $consulta,$paraBusqueda);
               //echo $consulta;
           //    echo var_dump($paraBusqueda);
               foreach($paraBusqueda as $busqueda){
                    $bindParametros[]= $this->buscaParametros($parametros, $busqueda, "in(:");
               }
               //echo var_dump($bindParametros);
               
               $bindValues=array();
//               $resultados=Yii::$app->db->createCommand($consulta);
//               foreach ($bindParametros as  $paraConsultab){
//                    foreach ($paraConsultab as $clave => $valConsulta){
//                        //$bindValues[$clave]=$valConsulta;
//                        //echo '<br>'.$clave.' '.$valConsulta;
//                        
//                        $parents_safe = '';
//                        $parents_sep = explode(',', $valConsulta);
//
//                        foreach ($parents_sep as $parent) {
//                            $parents_safe .= Yii::$app->db->quoteValue($parent) . ',';
//                        }
//
//                        $parents_safe = rtrim($parents_safe, ',');
//                         $resultados->bindValue($clave, $parents_safe);
//                         //$resultados->bindValue($clave, '2017-09-12',1);
//                       
//                    }
//                  // $bindValues[]=
//                    //echo var_dump($resultados->queryAll());
//               }
               
               foreach ($bindParametros as  $paraConsultab){
                    foreach ($paraConsultab as $clave => $valConsulta){
                         $bindValues[$clave]= $valConsulta;                 
                    }
               }
              // echo var_dump($bindValues);
                try{
                    $resultados=Yii::$app->db->createCommand($consulta)->bindValues($bindValues)->queryAll();
                   // echo var_dump($resultados);
                }catch(\yii\base\Exception $e){
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
                
               
                
                foreach( $resultados as $mail){
                    foreach($mail as $correo){
                        $destinatarios[$tipoDestinatario->nom_tdestinatario][]=$correo;
                       // break;
                    }
                }
               //  echo var_dump($destinatarios);
                
            }             
               
        }
          
        return $destinatarios;
        
    }
    
    private function parametros($correo){
        $facadeParametros = new CorParametroControllerFachada();
        $corParametros = $facadeParametros->cargaParametros(['id_correo'=>$correo['id_correo']]);
        $parametrosCiclo=array();
        $parametrosVariable=array();
         //echo "<br><br><hr>".var_dump($corParametros);
        foreach($corParametros as $parametro){
            $tipoParametro=$facadeParametros->nombreParametro($parametro['id_parametro'])->one();
            //foreach($tipoParametro as $tparametro){echo $tparametro['nom_tparametro'];}
            //echo $parametro['consulta_sql'].'<br>';
            if(trim($parametro['val_defecto'])!==""){
                    switch (trim($tipoParametro->nom_tparametro)) {
                    case "VARIABLE":
                            $data=array();
                            $counter=0;
                            $data[$parametro['nom_parametro']]=$parametro['val_defecto'];
                            $parametrosVariable[]=$data;
                        break;
                    case "CICLO":
                            $fila=0;
                            $columna=0;
                            $data=array();
                            $resultado =explode(':#:',$parametro['val_defecto']);
                           // echo var_dump($resultado);
                            foreach ($resultado as $valor) {
                                $data[$parametro['nom_parametro'].$columna]=$valor;
                                
                                $columna+=1;
                             }
                                $parametrosCiclo[$parametro['nom_parametro']][]=$data; 
                                
                               // echo var_dump($parametrosCiclo);
                        break;
                    }
            }
            
            if(trim($parametro['consulta_sql'])!==""){
                $resultados=Yii::$app->db->createCommand($parametro['consulta_sql'])->queryAll();
                //echo $parametro['consulta_sql'];
                switch (trim($tipoParametro->nom_tparametro)) {
                    case "VARIABLE":
                        //echo "VARIABLE";
                            $counter=0;
                            $data=array();
                            foreach ($resultados as $resultado) { //filas
                                 //echo $resulta[0];
                                foreach ($resultado as $valor) {// columnas
                                    $data[$parametro['nom_parametro']]=$valor;
                                    //echo $parametros[$parametro['nom_parametro'].$counter];
                                    $counter+=1;
                                    break;
                                 }    
                                 $parametrosVariable[]=$data;
                            }
                    break;
                    case "CICLO":
                       // echo "CICLO";
                        $fila=0;
                        
                        foreach ($resultados as $resultado) {
                             //echo $resulta[0];
                            $data=array();
                            $columna=0;
                            foreach ($resultado as $valor) {
                                $data[$parametro['nom_parametro'].$columna]=$valor;
                                //echo $parametros[$parametro['nom_parametro'].$counter];
                                $columna+=1;
                             }
                             $parametrosCiclo[$parametro['nom_parametro']][]=$data;
                             $fila+=1;
                        }
                    break;

                }
            }
            
           
        }
         //echo var_dump($parametros).'<br><hr>';
        return $parametros=["variable"=> $parametrosVariable,"ciclo"=> $parametrosCiclo];
    }
    
    private function buscaParametros($parametros,$busqueda,$tipo){
        // si la busqueda tiene numero al final busca en Ciclo y de lo contrario busca en Variable
        // cuan es tipo =: busca el primer dato, si es tipo (:, crea una lista de elementos separados por coma y con comillas 'dato1','dato2','dato3'
        
        $keybuscar=substr(trim($busqueda),2);
        $keyparam=substr(trim($busqueda),1);
        $lastChar=substr($keybuscar,-1);
        $parametro=array();
        $ciclo=false;
        if(is_numeric($lastChar)){
            $ciclo=true;
        }

        switch ($tipo){
            case "=:":
                   if($ciclo){
                   
                       
                        
                        foreach($parametros['ciclo'] as $aparam){  
                            $ids = \yii\helpers\ArrayHelper::getColumn($aparam, $keybuscar);
                            foreach($ids as $aparam2){  
                                $parametro[$keyparam]=$aparam2;
                                break;
                                //echo "<br> variable $keybuscar=".$aparam;
                            }
                        }
                       
                   }else{
                        $ids = \yii\helpers\ArrayHelper::getColumn($parametros['variable'], $keybuscar);
                        //echo '<br>'.$keybuscar;
                        //echo var_dump($ids);
                        //echo "<br>".var_dump($parametros['variable']);
                        foreach($ids as $aparam){  
                             $parametro[$keyparam]=$aparam;
                             break;
                            // echo "<br> variable $keybuscar=".$aparam;
                        }
                   }
            break;
            case "in(:":
                   if($ciclo){
            
                        foreach($parametros['ciclo'] as $aparam){  
                            $ids = \yii\helpers\ArrayHelper::getColumn($aparam, $keybuscar);
                            foreach($ids as $aparam2){
                                if(count($parametro)===0){
                                    
                                    if (is_numeric($aparam)){
                                        $parametro[$keyparam]="".$aparam2.",";
                                    }
                                    else{
                                        $parametro[$keyparam]="".$aparam2.",";
                                    }
                                }
                               else{
                                   
                                    if (is_numeric($aparam)){
                                        $parametro[$keyparam]=$parametro[$keyparam]."".$aparam2.",";
                                    }
                                    else{
                                        $parametro[$keyparam]=$parametro[$keyparam]."".$aparam2.",";
                                    }
                               }
                             //echo "<br> variable $keybuscar=". $parametro[$busqueda];
                            }
                        }
                        $parametro[$keyparam]=substr($parametro[$keyparam],0,-1);
                   }else{
                        $ids = \yii\helpers\ArrayHelper::getColumn($parametros['variable'], $keybuscar);
                        
                        foreach($ids as $aparam){  
                            if (is_numeric($aparam)){
                                $parametro[$keyparam]="".$aparam.",";
                            }
                            else{
                             $parametro[$keyparam]="'".$aparam."',";
                            }
                             //echo "<br> variable $keybuscar=".$aparam;
                        }
                       $parametro[$keyparam]=substr($parametro[$keyparam],0,-1);
                   }
            break;
        }
        //echo var_dump($parametro);
        return $parametro;
    }
    
    
    private function envioCorreo($correo,$parametros,$destinatarios){
        $patternIgual ='/=:\w*[\s]*/';  
        $busqueda=array();
        $envioMultiple=array();
        if(! \yii\helpers\ArrayHelper::keyExists("PARA", $destinatarios, false) || count($destinatarios["PARA"])<=0){ 
            //echo "<br>".var_dump($correo);
           // return null;
            throw new \yii\web\HttpException(500, 'NO se encontraron destinatarios configurados para el correo: '. $correo['nom_correo'].' Por favor verificar configuración de destinatarios, debe existir el PARA');
            
        }
        else{
            $envioUnico=$destinatarios["PARA"];
            
            foreach($destinatarios["PARA"] as $key =>$destino)
            {
                
                if( preg_match($patternIgual, $destino,$busqueda)){
                   // echo var_dump($busqueda)." ".$destino;
                   foreach($busqueda as  $dato){
                        array_push($envioMultiple,$dato);
                       // echo var_dump($envioMultiple);
                   }
                   \yii\helpers\ArrayHelper::remove($envioUnico, $key);
                    //echo "<br>llave $key<br> destino ". var_dump($envioUnico);
                   // unset($envioUnico[$key]);
                }
            }
            $destinatarioM=$destinatarios;
            $destinatarios["PARA"]=$envioUnico;
            
            $correoFinal=array();
            if(count($envioMultiple)>0){
                 $correoFinal=$this->replaceEmail($correo,$parametros,$envioMultiple);
            }else{
                 $correoFinal=$this->replaceEmail($correo,$parametros);
            }

        }
        
        foreach($correoFinal as $envios){
            
            $correoEnviado=false;
            $facadeEnviado =  new CorMensajeEnviadoControllerFachada();
            $correos="";
           
                if ( \yii\helpers\ArrayHelper::keyExists("correo", $envios, false)){
                    //echo var_dump($destinatarios);
                    // echo count($destinatarios["PARA"]);
                    $destinos=$destinatarios;
                    $destinos["PARA"][]=$envios['correo'];
                    //echo var_dump($envios['cuerpo']);
                    $existe=$facadeEnviado->exsiteParametro(var_export($envios['paraUsados'],true),var_export($destinos,true),$correo['asunto']);
                   // echo var_dump($existe);
                    if($existe == null || count($existe)<=0){
                        $correoEnviado=\common\general\notificaciones\ImplNotificacion::Enviar($destinos, $envios['cuerpo'],$correo['asunto']);
                    }
                    $correos=$destinos;
                }
                else{
                   // echo var_dump($correoFinal[0]);
                    $existe=$facadeEnviado->exsiteParametro(var_export($envios['paraUsados'],true),var_export($destinatarios,true),$correo['asunto']);
                    if(count($existe)<=0){
                        $correoEnviado=\common\general\notificaciones\ImplNotificacion::Enviar($destinatarios, $envios['cuerpo'],$correo['asunto']);
                        
                    }
                     $correos=$destinatarios;
                }
                
                if($correoEnviado){

                    $facadeEnviado->registraEnvio($correo['asunto'], var_export($envios['paraUsados'],true), $correo['id_correo'], var_export($correos,true));
                }
           

        }
    }
    
    private function replaceEmail($correo,$parametros,$envioMultiple=null){
        //$multipe=array()
        $nuevocorreo=array();
        $envCorreo=array();
        if($envioMultiple!==null){
            // echo var_dump($parametros["variable"]);
           //echo var_dump($envioMultiple);
             $busqueda=array();
             $patternParametro='/\=\:(.*)\d*/';

            foreach($envioMultiple as $cenvio){
                preg_match($patternParametro, $cenvio,$busqueda);
               // echo $cenvio;
                if(count($busqueda)>1){
                    $cuenta=0;
                    $ids = \yii\helpers\ArrayHelper::getColumn($parametros["variable"], $busqueda[1]);
                   // echo var_dump($ids);
                    foreach($ids as $paraenv){  
                       // echo 'hola';
                         if($paraenv){
                             $envCorreo[]=$this->evalCorreoMultiple([$busqueda[1]=>$paraenv],$correo["cuerpo"],$cenvio);                              
                          }
                    }

                    foreach($parametros["ciclo"] as $key => $ciclico){
                        $busca=$busqueda[1];
                        while(is_numeric(substr($busca,-1))){
                            $busca=substr($busca,0,-1);
                        }

                        if ($key===$busca){
                            foreach($ciclico as $linea){
                                  $envCorreo[]=$this->evalCorreoMultiple($linea,$correo["cuerpo"],$cenvio);
                            }
                        }
                    }
                    //echo var_dump( $envCorreo);

                }

            }
            
            //se evaluan el resto de parametros
            foreach($envCorreo as $correos){
              // echo var_dump($parametros["variable"])."correo";
              $nuevocorreo= $this->evalCorreoCiclo($parametros["ciclo"],$correos['cuerpo']);
              $correos['cuerpo']=$nuevocorreo['cuerpo'];
              $nuevocorreo= $this->evalCorreoVariable($parametros["variable"],$correos['cuerpo']);
              $correos['cuerpo']=$nuevocorreo['cuerpo'];
            }
          

        }
        else{
              $nuevocorreo= $this->evalCorreoCiclo($parametros["ciclo"],$correo['cuerpo']);
                //$envCorreo[]=$nuevocorreo;
              
              $nuevocorreo= $this->evalCorreoVariable($parametros["variable"],$nuevocorreo['cuerpo']);
              $envCorreo[0]['cuerpo']=$nuevocorreo['cuerpo'];
              $envCorreo[0]['paraUsados']=$nuevocorreo['paraUsados'];

             
            
        }
        
        //echo var_dump($envCorreo)."ciclo";
        return $envCorreo;
    }
  
    private function evalCorreoMultiple($parametros,$correo,$para){
        
            $patternIgual ='/[\s]*\$\w*[\s]*/'; 
            $patternTabla ='/[\s]*t\#\w*[\s]*/';
            $patternComa ='/[\s]*c\#\w*[\s]*/';
            $busqueda=array();
            $nuevocorreo=$correo;
            $parametrosUsados=array(); //se guardan los parametros usados en cada correo para guardar el registro
           // echo $correo;
            preg_match_all($patternIgual, $correo,$busqueda);
          //  echo var_dump($parametros)."uno";
            foreach($busqueda[0] as  $dato){
                //echo var_dump($dato)." dlkh";
               $vbDato=substr(trim($dato),1);
                        foreach($parametros as $key=> $parametro){
                           // echo $vbDato."  ".$key." <br>";
                            //echo $parametro;
                            if($key === $vbDato){
                                //echo $vbDato;
                               $parametrosUsados[$key]=$parametro;
                               $nuevocorreo= preg_replace("/\\".trim($dato)."/",$parametro,$nuevocorreo);
                               //echo $nuevocorreo;
                            }
                        }
            }

            
            $busqueda=array();
            preg_match_all($patternTabla, $correo,$busqueda);
           //echo var_dump($busqueda);
            foreach($busqueda[0] as  $dato){
                $vbDato=substr(trim($dato),1);
               //  echo var_dump($parametros);
                        $tabla="";
                        foreach($parametros as $key=> $parametro){
                            //echo var_dump($key);
                            if (strpos('\#'.$key, $vbDato) !== false){
                                $parametrosUsados[$key]=$parametro;
                                $tabla=$tabla."<td>".$parametro."</td>";
                              
                            }
                        }
                        if(trim($tabla) !== ""){
                          $nuevocorreo= preg_replace('/'.trim($dato).'/',$tabla,$nuevocorreo);
                        }
           }
           

            $busqueda=array();
            preg_match_all($patternComa, $correo,$busqueda);
             //echo var_dump($busqueda);
            foreach($busqueda[0] as  $dato){
                $vbDato=substr(trim($dato),1);
               //  echo var_dump($parametros);
                        $coma="";
                        foreach($parametros as $key=> $parametro){
                            //echo var_dump($key);
                            if (strpos('\#'.$key, $vbDato) !== false){
                                $parametrosUsados[$key]=$parametro;
                                $coma=$coma." ".$parametro.",";
                              
                            }
                        }
                        if(trim($coma) !== ""){
                          $nuevocorreo= preg_replace('/'.trim($dato).'/',$coma,$nuevocorreo);
                            
                        }
                       //   echo $nuevocorreo;
           }
            
           $email="";
           if($para){
                foreach($parametros as $key=> $parametro){
                    $vbDato=substr(trim($para),2);
                    if($key === $vbDato){
                       $parametrosUsados[$key]=$parametro;
                       $email=$parametro;
                       //echo $parametro;
                    }
                }
           }
                      
       return['cuerpo'=>$nuevocorreo,'correo'=>$email,'paraUsados'=>$parametrosUsados];
    }
    
    private function evalCorreoCiclo($parametros,$correo){
        
            $patternIgual ='/[\s]*\$\w*[\s]*/'; 
            $patternTabla ='/[\s]*t\#\w*[\s]*/';
            $patternComa ='/[\s]*c\#\w*[\s]*/';
            $busqueda=array();
            $nuevocorreo=$correo;
            $parametrosUsados=array();
           // echo $correo;
            preg_match_all($patternIgual, $correo,$busqueda);
            //echo var_dump($parametros)."uno";
            foreach($busqueda[0] as  $dato){
                //echo var_dump($dato)." dlkh";
               $vbDato=substr(trim($dato),1);
                        foreach($parametros as $parametro){
                           // echo var_dump($parametro);
                            foreach ($parametro as $valor){
                                foreach ($valor as $key => $cambio){
                                    //echo $vbDato."  ".$key." <br>";
                                    if($key === $vbDato){
                                        //echo $vbDato;
                                       $nuevocorreo= preg_replace("/\\".trim($dato)."/",$cambio,$nuevocorreo);
                                       $parametrosUsados[$key]=$cambio;
                                      // echo $nuevocorreo;
                                    }
                                }
                            }
                        }
            }

            
            $busqueda=array();
            preg_match_all($patternTabla, $correo,$busqueda);
           //echo var_dump($busqueda);
            foreach($busqueda[0] as  $dato){
                $vbDato=substr(trim($dato),1);
               //  echo var_dump($parametros);
                        $tabla="";
                        foreach($parametros as $key=> $parametro){
                            foreach ($parametro as $valor){
                                foreach ($valor as $key => $cambio){
                           // echo var_dump($key);
                                    if (strpos('\#'.$key, $vbDato) !== false){
                                        $tabla=$tabla."<td>".$cambio."</td>";
                                        $parametrosUsados[$key]=$cambio;
                                    }
                                }
                            }
                        }
                        if(trim($tabla) !== ""){
                          $nuevocorreo= preg_replace('/'.trim($dato).'/',$tabla,$nuevocorreo);
                        }
                        //echo $nuevocorreo;
           }
           
           
            $busqueda=array();
            preg_match_all($patternComa, $correo,$busqueda);
             //echo var_dump($busqueda);
            foreach($busqueda[0] as  $dato){
                $vbDato=substr(trim($dato),1);
               //  echo var_dump($parametros);
                        $coma="";
                        foreach($parametros as $key=> $parametro){
                             foreach ($parametro as $valor){
                                foreach ($valor as $key => $cambio){
                                    //echo var_dump($key);
                                    if (strpos('\#'.$key, $vbDato) !== false){
                                        $coma=$coma." ".$cambio.",";
                                        $parametrosUsados[$key]=$cambio;
                                    }
                                }
                             }
                        }
                        if(trim($coma) !== ""){
                          $nuevocorreo= preg_replace('/'.trim($dato).'/',$coma,$nuevocorreo);
                            
                        }
                         // echo $nuevocorreo;
           }
            
       return['cuerpo'=>$nuevocorreo,'paraUsados'=>$parametrosUsados];
    }

    private function evalCorreoVariable($parametros,$correo){

      $patternIgual ='/[\s]*\$\w*[\s]*/'; 
      $patternTabla ='/[\s]*t\#\w*[\s]*/';
      $patternComa ='/[\s]*c\#\w*[\s]*/';
      $busqueda=array();
      $nuevocorreo=$correo;
      $parametrosUsados=array();
     // echo $correo;
      preg_match_all($patternIgual, $correo,$busqueda);
      //echo var_dump($parametros)."uno";
      foreach($busqueda[0] as  $dato){
          //echo var_dump($dato)." dlkh";
         $vbDato=substr(trim($dato),1);
                  foreach($parametros as $parametro){
                     // echo var_dump($parametro);
                      foreach ($parametro as $key =>$valor){
                          
                              //echo $vbDato."  ".$key." <br>";
                              if($key === $vbDato){
                                  //echo $vbDato;
                                 $nuevocorreo= preg_replace("/\\".trim($dato)."/",$valor,$nuevocorreo);
                                 $parametrosUsados[$key]=$valor;
                               //  echo $nuevocorreo;
                              }
                         
                      }
                  }
      }


      $busqueda=array();
      preg_match_all($patternTabla, $correo,$busqueda);
     //echo var_dump($busqueda);
      foreach($busqueda[0] as  $dato){
          $vbDato=substr(trim($dato),1);
         //  echo var_dump($parametros);
                  $tabla="";
                  foreach($parametros as $key=> $parametro){
                      foreach ($parametro as $key => $valor){
                          
                     // echo var_dump($key);
                              if (strpos('\#'.$key, $vbDato) !== false){
                                  $tabla=$tabla."<td>".$valor."</td>";
                                  $parametrosUsados[$key]=$valor;
                              }
                         
                      }
                  }
                  if(trim($tabla) !== ""){
                    $nuevocorreo= preg_replace('/'.trim($dato).'/',$tabla,$nuevocorreo);
                  }
                  //echo $nuevocorreo;
     }


      $busqueda=array();
      preg_match_all($patternComa, $correo,$busqueda);
       //echo var_dump($busqueda);
      foreach($busqueda[0] as  $dato){
          $vbDato=substr(trim($dato),1);
         //  echo var_dump($parametros);
                  $coma="";
                  foreach($parametros as $key=> $parametro){
                       foreach ($parametro as  $key =>$valor){
                          
                              //echo var_dump($key);
                              if (strpos('\#'.$key, $vbDato) !== false){
                                  $coma=$coma." ".$valor.",";
                                  $parametrosUsados[$key]=$valor;

                              }
                          
                       }
                  }
                  if(trim($coma) !== ""){
                    $nuevocorreo= preg_replace('/'.trim($dato).'/',$coma,$nuevocorreo);

                  }
                  //  echo $nuevocorreo;
     }

        return['cuerpo'=>$nuevocorreo, 'paraUsados'=>$parametrosUsados];
   }
}