<?php

namespace frontend\helpers;
use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\poc\SopSoportes;
use common\models\poc\FdInvolucrado;
use common\models\poc\FdCoordenada;
use common\models\poc\FdUbicacion;
use common\models\poc\FdOpcionSelect;
use common\models\poc\FdRespuestaXMes;
use common\models\autenticacion\Parroquias;
use common\models\autenticacion\Cantones;
use common\models\autenticacion\Provincias;


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class helperHTML{
    
    public $_stringhtml;
    public $htmlvista;
    
    /*Para detalle capitulo*/
        public $_larray;
        public $_numcapitulo = 0;
        public $_numseccion = 0;
        public $_numpregunta = 0;    
        public $_estnumerado;
        public $last_seccion = 0;
        public $last_capitulo = 0;
        public $_stringvista;
        public $_varpass;
        private $_vrelaciones;
        private $_condicion = "<div class='condicion-block'></div>";
        public $_tiposoporte;
        public $_pcrear;             //Guarda permisos de crear
        public $_pactualizar;        //Guarda permisos de modificar "S" o "N"
        public $_pborrar;            //Guarda permisos de borrar "S" o "N"
        public $_pejecutar;          //Guardar permisos para ejecutar "S" o "N"
        public $id_pregagrupadas;
        public $agrupadas;
        public $vactual;
        public $td_agrupadas = array();
        public $_vectorcntag = array();
        public $tipo_archivo;
        public $preguntascondiciones;
        public $preguntashabilitadoras;
        public $_stringjavascriptcond;
        public $aplicadisable = 2;
        

        /*Pasando variables generales para la funcion*/
        public $id_conj_rpta,$id_fmt,$id_version,$antvista,$estado,$parroquias,$cantones,$periodos,$provincia,$capituloid;
        
//==========================================================================================================================================//
//============================================FUNCIONES GENERADORAS PARA DASHBOARD, DETCAPITULO, FORMATOHTML Y LISTCAPITULO===============//
//==========================================================================================================================================//        

    /*Funcion Generadora para la vista ListCapitulo***********************************/
     /* @_arraydata => contiene la informacion de la consulta asociada en formato vector
     * $alldata[] = ['id'=>$clave['id_capitulo'],'orden'=>$n_romano,'nomcapitulo' => $clave['nom_capitulo'], 'icono'=> $clave['icono'], 'modificar' => $clave['p_actualizar'], 'borrar' => $clave['p_borrar'], 'crear' =>$clave['p_crear'] ]; 
     * @id_conj_rpta => id conjunto respuesta 
     * @id_conj_prta => id conjunto pregunta
     * @id_fmt => id formato
     * @last => id version
     * @estado = id estado
     * @antvista = vista de retorno en los enlaces 
    */
        
    public function gen_listcapitulos($_arraydata,$id_conj_rpta,$id_conj_prta,$id_fmt,$last,$estado,$antvista,$_permisosuser,$_datosgenerales,$_datosgenubicacion){
        
        
        $var_create=$_permisosuser['p_crear'];
        $var_actualizar=$_permisosuser['p_actualizar'];
        $var_ejecutar=$_permisosuser['p_ejecutar'];
        $var_borrar=$_permisosuser['p_borrar'];
       
        $_stringhtml[]='<table class="listado">';
        
        
        
        foreach($_arraydata as $clave){
            
            
           //Pasando Numero de Orden a Romano           
           $n_romano=$this->romanic_number($clave['orden']);
           $_url5="";
           
           //Creando url se accedera a la siguiente pagina segun el id_tipo capitulo que se tenga en la tabla fd_capitulo
           
           if($clave['id_tcapitulo'] == 1 and $var_actualizar=='S'){
               
               $_url5 = Url::toRoute(['detcapitulo/index','id_conj_rpta' => $id_conj_rpta,'id_conj_prta' => $id_conj_prta,'id_fmt' => $id_fmt,'last' => $last,'estado'=>$estado,'id_capitulo' => $clave['id_capitulo'],'_lastvista'=>$antvista],true);
           
               
           }else if($clave['id_tcapitulo'] == 1 and $var_actualizar=='N'){
              
               $_url5 = Url::toRoute(['detformato/genhtml','id_conj_rpta' => $id_conj_rpta,'id_conj_prta' => $id_conj_prta,'id_fmt' => $id_fmt,'last' => $last,'estado'=>$estado,'id_capitulo' => $clave['id_capitulo'],'_lastvista'=>$antvista],true);
          
           }else if($clave['id_tcapitulo'] == 2 and $clave['url'] !== NULL and !empty($var_actualizar)){
               
             
               if($clave['url'] == 'poc/datosbasicos.php'){
                  
                   $_url5 = Url::toRoute(['detcapitulo/index','id_conj_rpta' => $id_conj_rpta,'id_conj_prta' => $id_conj_prta,'id_fmt' => $id_fmt,'last' => $last,'estado'=>$estado,'id_capitulo' => $clave['id_capitulo'],'_lastvista'=>$antvista],true); 
                  
                   if(!empty($_datosgenerales->id_datos_generales)){
                       $clave['respuestas'] = $clave['preguntas'];
                   }else{
                       $clave['respuestas'] = 0;
                       $clave['preguntas'] = 8;
                   }
                   
               }else if($clave['url'] == 'poc/basicosubicacioncoordenada.php'){
               
                   $_url5 = Url::toRoute(['detcapitulo/index','id_conj_rpta' => $id_conj_rpta,'id_conj_prta' => $id_conj_prta,'id_fmt' => $id_fmt,'last' => $last,'estado'=>$estado,'id_capitulo' => $clave['id_capitulo'],'_lastvista'=>$antvista],true); 
               
                    if(!empty($_datosgenubicacion->id_datos_generales)){
                        $clave['respuestas'] = $clave['preguntas'];
                   }else{
                       $clave['respuestas'] = 0;
                       $clave['preguntas'] = 4;
                   }
                   
               }else{
                   
                   $_url5 = Url::toRoute([$clave['url']]);
                   
               }
           
               
          }else if($clave['id_tcapitulo'] == 3){
              
               
               //FALTA PROGRAMAR EL PATRON SE REVISARA EL VIERNES================================================
               
           }
           
           //Yii::trace('En listado Capitulos'.$clave['id_capitulo'].'::'.$_url5, 'DEBUG');
           
           //Seleccionando clase segun cantidad de respuestas dadas a las preguntas=================================================
           if($clave['respuestas'] == $clave['preguntas']){
               $_clase="finish";
           }else{
               $_clase="title";
           }
           
           $_stringhtml[]='<tr>';
           $_stringhtml[]='<td class="'.$_clase.'">';
           $_stringhtml[]= Html::a("<span>".$n_romano.". ".$clave['nom_capitulo']."</span>",$_url5);
           $_stringhtml[]='</td>';
           $_stringhtml[]='<td class="porcentaje">';
           /*$_stringhtml[]='50';*/
           $_stringhtml[]='</td>';
           $_stringhtml[]='<td class="botton">';
           $_stringhtml[]= Html::a(Html::img("@web/images/iconnext.png"),$_url5);
           $_stringhtml[]='</td>';
           $_stringhtml[]='</tr>';
           
       }
       
       $_stringhtml[]='</table>';
       
      
       return [$_stringhtml,$var_actualizar,$var_create,$var_ejecutar,$var_borrar];
      
    }    
        
        
        
    //Funcion Generadora de vista Dashboard*******************************************//
    /* @_arraydata => contiene la informacion de la consulta asociada en formato vector
     * $alldata[] = ['id'=>$clave['id_capitulo'],'orden'=>$n_romano,'nomcapitulo' => $clave['nom_capitulo'], 'icono'=> $clave['icono'], 'modificar' => $clave['p_actualizar'], 'borrar' => $clave['p_borrar'], 'crear' =>$clave['p_crear'] ]; 
     * @id_conj_rpta => id conjunto respuesta 
     * @id_conj_prta => id conjunto pregunta
     * @id_fmt => id formato
     * @last => id version
     * @estado = id estado
     * @antvista = vista de retorno en los enlaces 
    */

    public function  gen_dashboardview($_arraydata,$id_conj_rpta,$id_conj_prta,$id_fmt,$last,$estado,$antvista,$_permisosuser,$_datosgenerales,$_datosgenubicacion){
        
        $var_create=$_permisosuser['p_crear'];
        $var_actualizar=$_permisosuser['p_actualizar'];
        $var_ejecutar=$_permisosuser['p_ejecutar'];
        $var_borrar=$_permisosuser['p_borrar'];
        
        
        foreach($_arraydata as $clave){
            
           //Pasando Numero de Orden a Romano           
           $n_romano=$this->romanic_number($clave['orden']);
           $_url5="";
           
           //Creando url se accedera a la siguiente pagina segun el id_tipo capitulo que se tenga en la tabla fd_capitulo
           
           if($clave['id_tcapitulo'] == 1 and $var_actualizar=='S'){
               
               $_url5 = Url::toRoute(['detcapitulo/index','id_conj_rpta' => $id_conj_rpta,'id_conj_prta' => $id_conj_prta,'id_fmt' => $id_fmt,'last' => $last,'estado'=>$estado,'id_capitulo' => $clave['id_capitulo'],'_lastvista'=>$antvista],true);
           
               
           }else if($clave['id_tcapitulo'] == 1 and $var_actualizar=='N'){
              
               $_url5 = Url::toRoute(['detformato/genhtml','id_conj_rpta' => $id_conj_rpta,'id_conj_prta' => $id_conj_prta,'id_fmt' => $id_fmt,'last' => $last,'estado'=>$estado,'id_capitulo' => $clave['id_capitulo'],'_lastvista'=>$antvista],true);
          
           }else if($clave['id_tcapitulo'] == 2 and $clave['url'] !== NULL and !empty($var_actualizar)){
               
               if($clave['url'] == 'poc/datosbasicos.php'){
                  
                  $_url5 = Url::toRoute(['detcapitulo/index','id_conj_rpta' => $id_conj_rpta,'id_conj_prta' => $id_conj_prta,'id_fmt' => $id_fmt,'last' => $last,'estado'=>$estado,'id_capitulo' => $clave['id_capitulo'],'_lastvista'=>$antvista],true); 
                  
                  if(!empty($_datosgenerales->id_datos_generales)){
                       $clave['respuestas'] = $clave['preguntas'];
                   }else{
                       $clave['respuestas'] = 0;
                       $clave['preguntas'] = 8;
                   }
                  
               }else if($clave['url'] == 'poc/basicosubicacioncoordenada.php'){
                   
                   $_url5 = Url::toRoute(['detcapitulo/index','id_conj_rpta' => $id_conj_rpta,'id_conj_prta' => $id_conj_prta,'id_fmt' => $id_fmt,'last' => $last,'estado'=>$estado,'id_capitulo' => $clave['id_capitulo'],'_lastvista'=>$antvista],true); 
                   
                   if(!empty($_datosgenubicacion->id_datos_generales)){
                        $clave['respuestas'] = $clave['preguntas'];
                   }else{
                       $clave['respuestas'] = 0;
                       $clave['preguntas'] = 4;
                   }
                   
               }else{
                   $_url5 = Url::toRoute([$clave['url']]);
               }
           
               
          }else if($clave['id_tcapitulo'] == 3){
              
               
               //FALTA PROGRAMAR EL PATRON SE REVISARA EL VIERNES================================================
               
           }
           
           
           //Seleccionando clase segun cantidad de respuestas dadas a las preguntas=================================================
           if($clave['respuestas'] == $clave['preguntas']){
               $_clase="finish";
           }else{
               $_clase="title";
           }
           
           $_stringhtml[]='<div class="caja">';
           $_stringhtml[]='<div class="linea1">';
           $_stringhtml[]='<div class="'.$_clase.'">';
	   $_stringhtml[]='<div>'.$n_romano.'</div>';
	   $_stringhtml[]='<?= yii\helpers\Html::a("<div class=\'numcap\'>'.$clave['nom_capitulo'].'</div>","'.$_url5.'"); ?>';
	   $_stringhtml[]='</div>';
           $_stringhtml[]='</div>';
           $_stringhtml[]='<div class="linea2">';
           $_stringhtml[]='<div class="icono">';
           $_stringhtml[]='<img src="'.$clave['icono'].'" width="90%" height="70%"/>';
           $_stringhtml[]='</div>';
           /*$_stringhtml[]='<div class="valores">';
           $_stringhtml[]='<p>10/20</p>';
           $_stringhtml[]='<p>50%</p>';
           $_stringhtml[]='</div>';*/
           $_stringhtml[]='</div>';
           $_stringhtml[]='</div>';
           
       }
       
       if(!empty($_stringhtml)){
        return [$_stringhtml,$var_actualizar,$var_create,$var_ejecutar,$var_borrar];
       }else{
            throw new \yii\web\HttpException(404, 'No existen capitulos asociadas al formato');
       }
        
    }
    
    
    
    /*Funcion Generadora de HTML Detalle Capitulo y Detalle Formato**************************************************************/
    public function gen_detacapituloview($_capitulos,$r_preguntans,$r_secciones,$r_pregunta,$formanumber,$_permisos,$_modelogenerales,$_modelbasicos,$_modelbasicos_coordenadas,$_modelbasicos_ubicacion){
        $this->_larray=0;
        $this->_stringvista=array();
        $this->vactual = "detcapitulo/genvistaformato";
        
        /*Asignando Permisos=====================================================*/
        $this->_pcrear = $_permisos['p_crear'];
        $this->_pactualizar = $_permisos['p_actualizar'];
        $this->_pborrar =$_permisos['p_borrar'];
        $this->_pejecutar =$_permisos['p_ejecutar'];
        
        
        //Asignando Vista para las preguntas de Datos generales si se necesitan*/
        if(!empty($_modelogenerales)){
            $this->gen_capitulogenerales($this->_pactualizar);
        }
        
        //Asignando Vista para las preguntas de Datos basicos*/
        if(!empty($_modelbasicos) or !empty($_modelbasicos_coordenadas) or !empty($_modelbasicos_ubicacion)){
            $this->gen_capitulobasico($this->_pactualizar,$_modelbasicos_ubicacion,$_modelbasicos_coordenadas);
        }
            
        /*Organizando titulo de capitulo ==========================================*/
        foreach($_capitulos as $_cpclave){
            
            $_indicecap=$_cpclave['id_capitulo'];
                     
            /*Asignando total de columnas=============================================*/
            $_tcolumnas=$_cpclave['num_columnas'];
            
            /*Habilitando numeracion==================================================*/
            if($formanumber=='S'){
                $this->_numcapitulo = $_cpclave['orden'];
                $this->_estnumerado = 'S';
            }  
            
            
            //Agregando Titulo del capitulo encontrado==============================
            $this->gen_capitulo($_cpclave,$_tcolumnas);
            
            
            /*Organizando preguntas sin seccion ====================================*/
           
            if(!empty($r_preguntans[$_indicecap])){
                $this->gen_preguntans($r_preguntans[$_indicecap], $_tcolumnas,$this->_larray);
            }
            
            /*Organiznado secciones===================================================
            *Aqui se generar tambien las preguntas con seccion gen_preguntassec
            **/
            if(!empty($r_secciones[$_indicecap]) and !empty($r_pregunta[$_indicecap])){
               $_vsecciones= $this->gen_secciones($r_secciones[$_indicecap], $_tcolumnas, $r_pregunta[$_indicecap],$this->_larray);
            }
            
        }
       

        $this->_stringvista[]='</table>';
        $this->_stringhtml = $this->_stringvista;
        
        if(!empty($this->preguntascondiciones) and !empty($this->preguntashabilitadoras)){
            
            $_disablestring= "";
            
            $_stringjavascript = "<script>"
                    . "function condiciones(larrayhab){ "
                    . " var arrayRelaciones= new Array(); ";
            
            for($q=0;$q<count($this->preguntashabilitadoras);$q++){
                
                $js_hab = $this->preguntashabilitadoras[$q];
                $js_cond = $this->preguntascondiciones[$q];
                $_larrayhabilitadora= $this->_vrelaciones[$js_hab];
                $_larraycondicionada= $this->_vrelaciones[$js_cond];
                
                
                $_stringjavascript .= ' arrayRelaciones["'.$_larrayhabilitadora.'"] = '.$_larraycondicionada .'; ';
                                     
                
                $_disablestring .= 'document.getElementById("consultaciudadana-rpta'.$_larraycondicionada.'").disabled = true;';
                
            }
            
			$_stringjavascript .=  ' return arrayRelaciones[larrayhab]; '
                                   . ' }</script>';
								   
								   
            $_stringjavascript .= "<script>"
                               . "window.onload = function (){ "
                               . $_disablestring ."} </script>";  
            
            $this->_stringjavascriptcond = $_stringjavascript;
            //Yii::trace("probando la funcion de javascript dinamica ".$_stringjavascript,"DEBUG");
        }

        return TRUE;
    
    }
    
  
    
    
    
    /*Funcion que genera string sin cajitas ni eventos HTML para el FORMATOHTML*/
    public function gen_formatoHTML($_capitulos,$r_preguntans,$r_secciones,$r_pregunta,$formanumber,$_permisos,$_modelogenerales,$modelpreguntas,$_modelbasicos,$_modelbasicos_coordenadas,$_modelbasicos_ubicacion){
       
        $this->_larray=0;
        //Asignando Vista para las preguntas de Datos generales si se necesitan*/
        if(!empty($_modelogenerales)){
            $this->gen_capitulogeneralesHTML($_modelogenerales);
        }
        
        if(!empty($_modelbasicos) or !empty($_modelbasicos_coordenadas) or !empty($_modelbasicos_ubicacion)){
            $this->gen_capitulobasicoHTML($_modelbasicos,$_modelbasicos_ubicacion, $_modelbasicos_coordenadas);
        }
        
   
         /*Organizando titulo de capitulo ==========================================*/
        
        foreach($_capitulos as $_cpclave){
            
            $_indicecap=$_cpclave['id_capitulo'];
                     
            /*Asignando total de columnas=============================================*/
            $_tcolumnas=$_cpclave['num_columnas'];
            
            /*Habilitando numeracion==================================================*/
            if($formanumber=='S'){
                $this->_numcapitulo = $_cpclave['orden'];
                $this->_estnumerado = 'S';
            }  
            
            
            //Agregando Titulo del capitulo encontrado==============================
            $this->gen_capituloHTML($_cpclave,$_tcolumnas);
            
            /*Organizando preguntas sin seccion ====================================*/
           
            if(!empty($r_preguntans[$_indicecap])){
                $this->gen_preguntansHTML($r_preguntans[$_indicecap], $_tcolumnas,$this->_larray,$modelpreguntas);
            }
            
             /*Organiznado secciones===================================================
            *Aqui se generar tambien las preguntas con seccion gen_preguntassec
            **/
            if(!empty($r_secciones[$_indicecap]) and !empty($r_pregunta[$_indicecap])){
               $this->gen_seccionesHTML($r_secciones[$_indicecap], $_tcolumnas, $r_pregunta[$_indicecap],$this->_larray,$modelpreguntas);
            }
            
            $this->htmlvista.='</table>';

        }
        
        
        
        $this->_stringhtml = $this->htmlvista;
      
    }
    

//===================================================================================================================================//    
/*=====================================================SUBFUNCIONES GENERADORAS======================================================*/
//===================================================================================================================================// 
    
    /*Funcion para generar el inicio
     * del capitulo en detalle capitulo para llenar por el usuario
     */
    protected function gen_capitulo($m_capitulo,$_tcolumnas){
        
      /*Habilitando numeracion==================================================*/
        if($this->_estnumerado == 'S'){
            $this->_numcapitulo = $m_capitulo['orden'];
        }  
        
       
       $this->_stringvista[]='<table class="tbcapitulo" >';        
        
        if(!empty($m_capitulo)){
                
                $this->_stringvista[]='<tr>
                                    <td class="nomcapitulo" colspan="'.$_tcolumnas.'">
                                               '.$this->romanic_number($m_capitulo['orden']).'.'.$m_capitulo['nom_capitulo'].'
                                    </td>
                                </tr>';
        }
        
    }
    
    /*Funcion para general el inicio del capitulo
     * en formatoHTML================== SE SACA POR APARTE POR SI SE DEBE REALIZAR ALGUN CAMBIO DE STILOS O DE FORMA
     */
     protected function gen_capituloHTML($m_capitulo,$_tcolumnas){
        
      /*Habilitando numeracion==================================================*/
        if($this->_estnumerado == 'S'){
            $this->_numcapitulo = $m_capitulo['orden'];
        }  
       
       $this->htmlvista.='<table class="tbcapitulo" >';        
        
        if(!empty($m_capitulo)){
                
                $this->htmlvista.='<tr>
                                    <td class="nomcapitulo" colspan="'.$_tcolumnas.'">
                                               '.$this->romanic_number($m_capitulo['orden']).'.'.htmlentities($m_capitulo['nom_capitulo']).'
                                    </td>
                                </tr>';
        }
    }
    
    
    
    /*Funcion para agregar las casillas para ingresar datos
     * del capitulo tipo datos basicos con ubicacion y coordenadas
     * verificar que este capitulo existe solos si en fd_capitulos se encuentra un capitulo tipo ='2' and url = poc/basicosubicacioncoordenada.php
     * model_basicos => fd_datos_generales
     * model_basicos_coordenadas => fd_coordendas  */
    
    protected function gen_capitulobasico($varactualizar,$_modelbasicos_ubicacion,$_modelbasicos_coordenadas){
       
        /*Verificando permisos para desactivar casillas*/
        if($varactualizar == 'S'){
            $_disabled = "FALSE";
        }else{
            $_disabled = "TRUE";
        }
        
          $this->_stringvista[]='<table class="tbcapitulo" ><tr>
                                 <td class="nomcapitulo" colspan="4">
                                            '.$this->romanic_number('1').'.FICHA DE AUTOEVALUACION PARA LA PRESENTACION DEL SERVICIO DE RIEGO
                                 </td>
                             </tr>';
          
          
           $this->_stringvista[]='<tr><td width="25%" class="labelpregunta">NOMBRE DEL PRESTADOR DEL SERVICIO: '
                                    .$this->fnt_tooltip("texto pregunta 1").'</td>';
          $this->_stringvista[]='<td class="inputpregunta" width="25%"><?= $form->field($_modelbasicos, "nombres")->textInput(["disabled" => '.$_disabled.'])->label(false); ?></td>';
          
          
          $this->_stringvista[]='<td  width="25%" class="labelpregunta">NOMBRE DEL SISTEMA DE RIEGO: '
                                    .$this->fnt_tooltip("texto pregunta 1").'</td>';
          $this->_stringvista[]='<td class="inputpregunta"  width="25%"><?= $form->field($_modelbasicos, "nom_sistema")->textInput(["disabled" => '.$_disabled.'])->label(false); ?></td></tr>';
          
          
          $this->_stringvista[]='<tr><td width="25%" class="labelpregunta">Provincia: '
                                    .$this->fnt_tooltip("texto pregunta 1").'</td>';
          
          
          $this->_stringvista[]='<td class="inputpregunta"><?= $form->field($_modelbasicos_ubicacion, "cod_provincia")->dropDownList('
                                . '\yii\helpers\ArrayHelper::map(\common\models\autenticacion\Provincias::find()->all(),'
                                . '"cod_provincia","nombre_provincia"),'
                                . '["prompt"=>"Seleccione una provincia","disabled"=>'.$_disabled.',"onchange"=>\'$.post("index.php?r=fdubicacion/listado&id=\'.\'"+$(this).val(),function(data){'
                                . '$("#fdubicacion_var-cod_canton").html(data);'
                                . '});\'])->label(false); ?></td>';
          
          
         $this->_stringvista[]='<td width="25%" class="labelpregunta">Cantón: '
                                    .$this->fnt_tooltip("texto pregunta 1").'</td>';
         
         
         if(!empty($_modelbasicos_ubicacion->cod_canton)){
             
             
            $this->_stringvista[]='<td class="inputpregunta"><?= $form->field($_modelbasicos_ubicacion, "cod_canton")->dropDownList('
                                . '\yii\helpers\ArrayHelper::map($cantonesPost,"cod_canton","nombre_canton"),'
                                . '["prompt"=>"Seleccione un canton","disabled"=>'.$_disabled.',"onchange"=>\'$.post("index.php?r=fdubicacion/listadopd&id_prov=\'.\'"+$("#fdubicacion_var-cod_provincia:selected").val()+"\'.\''
                                . '&id=\'.\'"+$(this).val(),function(data){'
                                . 'var res = data.split("::"); '
                                . '$("#fdubicacion_var-id_demarcacion").html(res[0]);'
                                . '});\'])->label(false); ?></td></tr>';
             
         }else{
             
              $this->_stringvista[]='<td class="inputpregunta"><?= $form->field($_modelbasicos_ubicacion, "cod_canton")->dropDownList('
                                . '[],'
                                . '["prompt"=>"Seleccione un canton","disabled"=>'.$_disabled.',"onchange"=>\'$.post("index.php?r=fdubicacion/listadopd&id_prov=\'.\'"+$("#fdubicacion_var-cod_provincia:selected").val()+"\'.\''
                                . '&id=\'.\'"+$(this).val(),function(data){'
                                . 'var res = data.split("::"); '
                                . '$("#fdubicacion_var-id_demarcacion").html(res[0]);'
                                . '});\'])->label(false); ?></td></tr>';
         }
         
         
                                            
        $this->_stringvista[]='<tr><td width="25%" class="labelpregunta">Demarcación Hidrográfica: '
                                    .$this->fnt_tooltip("texto pregunta 1").'</td>';
         
         if(!empty($_modelbasicos_ubicacion->id_demarcacion)){
            
              $this->_stringvista[]='<td class="inputpregunta"><?= $form->field($_modelbasicos_ubicacion, "id_demarcacion")->dropDownList('
                                . '\yii\helpers\ArrayHelper::map($demarcacionespost,"id_demarcacion","nombre_demarcacion"),'
                                . '["prompt"=>"Seleccione una demarcación","disabled"=>'.$_disabled.'])->label(false); ?></td>';

         }else{

              $this->_stringvista[]='<td class="inputpregunta"><?= $form->field($_modelbasicos_ubicacion, "id_demarcacion")->dropDownList('
                                . '[],'
                                . '["prompt"=>"Seleccione una demarcación","disabled"=>'.$_disabled.'])->label(false); ?></td>';
        }
        
        
        $this->_stringvista[]='<td width="25%" class="labelpregunta">Coordenadas X: '
                                    .$this->fnt_tooltip("texto pregunta 1").'</td>';
         
        $this->_stringvista[]='<td class="inputpregunta"><?= $form->field($_modelbasicos_coordenadas, "x")->widget(\yii\widgets\MaskedInput::className(),'
                  . '["mask" => "99.99999","options"=>["disabled" =>'.$_disabled.']])->label(false); ?></td></tr>';
        
        
        $this->_stringvista[]='<tr><td width="25%" class="labelpregunta">Coordenadas Y: '
                                    .$this->fnt_tooltip("texto pregunta 1").'</td>';
         
        $this->_stringvista[]='<td class="inputpregunta"><?= $form->field($_modelbasicos_coordenadas, "y")->widget(\yii\widgets\MaskedInput::className(),'
                  . '["mask" => "99.99999","options"=>["disabled" =>'.$_disabled.']])->label(false); ?></td>';
        
        $this->_stringvista[]='<td width="25%" class="labelpregunta">Altura: '
                                    .$this->fnt_tooltip("texto pregunta 1").'</td>';
        
        $this->_stringvista[]='<td class="inputpregunta"><?= $form->field($_modelbasicos_coordenadas, "altura")->widget(\yii\widgets\MaskedInput::className(),'
                  . '["mask" => "99.99999","options"=>["disabled" =>'.$_disabled.']])->label(false); ?></td>';
        
        $this->_stringvista[]='<tr><td width="25%" class="labelpregunta">Tipo de Coordenada: '
                                    .$this->fnt_tooltip("texto pregunta 1").'</td>';
        
        
       $this->_stringvista[]='<td class="inputpregunta"><?= $form->field($_modelbasicos_coordenadas, "id_tcoordenada")->dropDownList('
                                . 'yii\helpers\ArrayHelper::map(common\models\poc\TrTipoCoordenada::find()->all(),'
                                . '"id_tcoordenada","nom_tcoordenada"),'
                                . '["prompt"=>"Seleccione","disabled"=>'.$_disabled.'])->label(false); ?></td>';
        
       
        $this->_stringvista[]='<td width="25%" class="labelpregunta">Nombre del Representante Legal: '
                                    .$this->fnt_tooltip("texto pregunta 1").'</td>';
              
        $this->_stringvista[]='<td class="inputpregunta" width="25%"><?= $form->field($_modelbasicos, "nom_replegal")->textInput(["disabled" => '.$_disabled.'])->label(false); ?></td></tr>';
           
        $this->_stringvista[]='<tr><td width="25%" class="labelpregunta">Fecha: '
                                    .$this->fnt_tooltip("texto pregunta 1").'</td>';
        
         $this->_stringvista[]='<td class="inputpregunta" width="25%"><?= $form->field($_modelbasicos, "fecha")->'
                                . 'widget(yii\jui\DatePicker::className(),['
                                . '"dateFormat"=>"dd/MM/yyyy",'
                                . '"clientOptions"=>['
                                . '"changeYear" => true,'            
                                . '"changeMonth" => true]'
                                . '])->label(false); ?></td></tr></table>';
    }
    
    
    /*Funcion que genera el stringhtml valido para
     * los documentos de excel, word y pdf
     */
    
    protected function gen_capitulobasicoHTML($_modelbasicos,$_modelbasicos_ubicacion,$_modelbasicos_coordenadas){
       
        
        $this->htmlvista.='<table class="tbcapitulo"><tr>
                                 <td class="nomcapitulo" colspan="4">
                                            '.$this->romanic_number('1').'.FICHA DE AUTOEVALUACION PARA LA PRESENTACION DEL SERVICIO DE RIEGO
                                 </td>
                             </tr>';
        $this->htmlvista.='<tr><td class="labelpregunta">NOMBRE DEL PRESTADOR DEL SERVICIO: </td>';
        $this->htmlvista.='<td class="inputpregunta">'.$_modelbasicos->nombres.'</td>';
          
          
        $this->htmlvista.='<td class="labelpregunta">NOMBRE DEL SISTEMA DE RIEGO:</td>';
        $this->htmlvista.='<td class="inputpregunta">'.$_modelbasicos->nom_sistema.'</td></tr>';
          
          
        $this->htmlvista.='<tr><td class="labelpregunta">PROVINCIA: </td>';
        $this->htmlvista.='<td class="inputpregunta">'.$this->nom_provincia($_modelbasicos_ubicacion->cod_provincia).'</td>';
       
        $this->htmlvista.='<td class="labelpregunta">CANTON: </td>';
        $this->htmlvista.='<td class="inputpregunta">'.$this->nom_canton($_modelbasicos_ubicacion->cod_canton,$_modelbasicos_ubicacion->cod_provincia).'</td></tr>';
        
        $this->htmlvista.='<tr><td class="labelpregunta">Demarcacion: </td>';
        $this->htmlvista.='<td class="inputpregunta">'.$this->demarcaciones($_modelbasicos_ubicacion->id_demarcacion).'</td>';
       
        $this->htmlvista.='<td class="labelpregunta">COORDENADA X: </td>';
        $this->htmlvista.='<td class="inputpregunta">'.$_modelbasicos_coordenadas->x.'</td></tr>';
        
        $this->htmlvista.='<tr><td class="labelpregunta">COORDENADA Y: </td>';
        $this->htmlvista.='<td class="inputpregunta">'.$_modelbasicos_coordenadas->y.'</td>';
       
        $this->htmlvista.='<td class="labelpregunta">ALTURA: </td>';
        $this->htmlvista.='<td class="inputpregunta">'.$_modelbasicos_coordenadas->altura.'</td></tr>';
        
        $this->htmlvista.='<tr><td class="labelpregunta">TIPO DE COORDENADA: </td>';
        $this->htmlvista.='<td class="inputpregunta">'.$this->tipocoordenada($_modelbasicos_coordenadas->id_tcoordenada).'</td>';
       
        $this->htmlvista.='<td class="labelpregunta">NOMBRE REPRESENTANTE LEGAL: </td>';
        $this->htmlvista.='<td class="inputpregunta">'.$_modelbasicos->nom_replegal.'</td></tr>';
        
        $this->htmlvista.='<tr><td class="labelpregunta">FECHA: </td>';
        $this->htmlvista.='<td class="inputpregunta">'.$_modelbasicos->fecha.'</td></tr></table>';
   
        
    }
    
    
    
    /*Funcion que agrega las casillas para ingresar datos a la tabla
     * Fd_Datos_Generales si el formato asi lo requiere
     */
    protected function gen_capitulogenerales($varactualizar){
        
        /*Verificando permisos para desactivar casillas*/
        if($varactualizar == 'S'){
            $_disabled = "FALSE";
        }else{
            $_disabled = "TRUE";
        }
        
        $this->_stringvista[]='<table class="tbcapitulo" ><tr>
                                 <td class="nomcapitulo" colspan="4">
                                            '.$this->romanic_number('1').'.INFORMACIÓN GENERAL DEL SOLICITANTE
                                 </td>
                             </tr>';
        
        $this->_stringvista[]='<tr><td colspan="2" width="50%" class="labelpregunta">NOMBRES Y APELLIDOS DEL USUARIO AUTORIZADO/CONCESIONADO, REPRESENTANTE LEGAL O SOLICITANTES AUTORIZADO: '.$this->fnt_tooltip("texto pregunta 1").'</td>';
        $this->_stringvista[]='<td class="inputpregunta" colspan="2" width="50%"><?= $form->field($modelgenerales, "nombres")->textInput(["disabled" => '.$_disabled.'])->label(false); ?></td></tr>';
        
        $this->_stringvista[]='<tr><td class="labelpregunta">Numero de Celular:'.$this->fnt_tooltip("texto pregunta 2").'</td>';
        $this->_stringvista[]='<td class="inputpregunta"><?= $form->field($modelgenerales, "num_celular")->textInput(["disabled" => '.$_disabled.'])->label(false); ?></td>';
        $this->_stringvista[]='<td class="labelpregunta">Numero Convencional:'.$this->fnt_tooltip("texto pregunta 3").'</td>';
        $this->_stringvista[]='<td class="inputpregunta"><?= $form->field($modelgenerales, "num_convencional")->textInput(["disabled" => '.$_disabled.'])->label(false); ?></td></tr>';
        
        $this->_stringvista[]='<tr><td class="labelpregunta">Tipo de Documento:'.$this->fnt_tooltip("texto pregunta 4").'</td>';
        $this->_stringvista[]='<td class="inputpregunta"><?= $form->field($modelgenerales, "id_tdocumento")->dropDownList('
                                . 'yii\helpers\ArrayHelper::map(common\models\poc\TrTipoDocumento::find()->all(),'
                                . '"id_tdocumento","nom_tdocumento"),'
                                . '["prompt"=>"Seleccione","disabled"=>'.$_disabled.'])->label(false); ?></td>';
        
        $this->_stringvista[]='<td class="labelpregunta">Cédula y/o RUC:</td>';
        $this->_stringvista[]='<td class="inputpregunta"><?= $form->field($modelgenerales, "num_documento")->textInput(["disabled" => '.$_disabled.'])->label(false); ?></td></tr>';
        
        $this->_stringvista[]='<tr><td colspan="1" class="labelpregunta">Direccion:</td>';
        $this->_stringvista[]='<td colspan="3" class="inputpregunta"><?= $form->field($modelgenerales, "direccion")->textInput(["disabled" => '.$_disabled.'])->label(false); ?></td></tr>';
        
        $this->_stringvista[]='<tr><td colspan="1" class="labelpregunta">Descripcion Trabajo:</td>';
        $this->_stringvista[]='<td colspan="3" class="inputpregunta"><?= $form->field($modelgenerales, "descripcion_trabajo")->textInput(["disabled" => '.$_disabled.'])->label(false); ?></td></tr>';
        
        $this->_stringvista[]='<tr><td colspan="1" class="labelpregunta">Correo Electronico:</td>';
        $this->_stringvista[]='<td colspan="3" class="inputpregunta"><?= $form->field($modelgenerales, "correo_electronico")->textInput(["disabled" => '.$_disabled.'])->label(false); ?></td></tr>';
        
        $this->_stringvista[]='<tr><td class="labelpregunta">Tipo de Personal:</td>';
        $this->_stringvista[]='<td class="inputpregunta"><?= $form->field($modelgenerales, "id_natu_juridica")->dropDownList('
                                . 'yii\helpers\ArrayHelper::map(common\models\poc\TrTipoNatuJuridica::find()->all(),'
                                . '"id_natu_juridica","nom_natu_juridica"),'
                                . '["prompt"=>"Seleccione","disabled"=>'.$_disabled.'])->label(false); ?></td>';
        $this->_stringvista[]='<td class="labelpregunta">Nombres y Apellidos Rep. Legal:</td>';
        $this->_stringvista[]='<td class="inputpregunta"><?= $form->field($modelgenerales, "nombres_apellidos_replegal")->textInput(["disabled" => '.$_disabled.'])->label(false); ?></td></tr></table>';
        
    
    }
    
    
    
    
    /**Funcion que general el HTML para formatoHTML
     * puro de datosgenerales
     * De donde sale el total de columnas para esto?????? OJO CON ESE 4 DE COLSPAN PUEDE TRAER ERRORES SE DEBE BUSCAR EN BD
     */
     protected function gen_capitulogeneralesHTML($_modelogenerales){
         
        $this->htmlvista.='<table class="tbcapitulo" ><tr>
                                 <td class="nomcapitulo" colspan="4">
                                            '.$this->romanic_number('1').'.INFORMACI&Oacute;N GENERAL DEL SOLICITANTE
                                 </td>
                             </tr>';
        
        $this->htmlvista.='<tr><td colspan="2" width="50%" class="labelpregunta">NOMBRES Y APELLIDOS DEL USUARIO AUTORIZADO/CONCESIONADO, REPRESENTANTE LEGAL O SOLICITANTES AUTORIZADO: </td>';
        $this->htmlvista.='<td class="inputpregunta" colspan="2" width="50%">'.$_modelogenerales->nombres.'</td></tr>';
        
        $this->htmlvista.='<tr><td class="labelpregunta">Numero de Celular: </td>';
        $this->htmlvista.='<td class="inputpregunta">'.$_modelogenerales->num_celular.'</td>';
        
        $this->htmlvista.='<td class="labelpregunta">Numero Convencional:</td>';
        $this->htmlvista.='<td class="inputpregunta">'.$_modelogenerales->num_convencional.'</td></tr>';
        
        $this->htmlvista.='<tr><td class="labelpregunta">Tipo de Documento:</td>';
        $this->htmlvista.='<td class="inputpregunta">'.$_modelogenerales->idTdocumento["nom_tdocumento"].'</td>';
        
        $this->htmlvista.='<td class="labelpregunta">C&eacute;dula y/o RUC:</td>';
        $this->htmlvista.='<td class="inputpregunta">'.$_modelogenerales->num_documento.'</td></tr>';
        
        $this->htmlvista.='<tr><td colspan="1" class="labelpregunta">Direccion:</td>';
        $this->htmlvista.='<td colspan="3" class="inputpregunta">'.$_modelogenerales->direccion.'</td></tr>';
        
        $this->htmlvista.='<tr><td colspan="1" class="labelpregunta">Descripcion Trabajo:</td>';
        $this->htmlvista.='<td colspan="3" class="inputpregunta">'.$_modelogenerales->descripcion_trabajo.'</td></tr>';
        
        $this->htmlvista.='<tr><td colspan="1" class="labelpregunta">Correo Electronico:</td>';
        $this->htmlvista.='<td colspan="3" class="inputpregunta">'.$_modelogenerales->correo_electronico.'</td></tr>';
        
        $this->htmlvista.='<tr><td class="labelpregunta">Tipo de Personal:</td>';
        $this->htmlvista.='<td class="inputpregunta">'.$_modelogenerales->idNatuJuridica["nom_natu_juridica"].'</td>';
        
        $this->htmlvista.='<td class="labelpregunta">Nombres y Apellidos Rep. Legal:</td>';
        $this->htmlvista.='<td class="inputpregunta">'.$_modelogenerales->nombres_apellidos_replegal.'</td></tr></table>';

        // $this->htmlvista.='</table>';
     }
    
     
     
    /*======================================================================preguntas sin secciones=========================================*/
     /***************************************************************************************************************************************/
    
    /***Funcion para generar la vista
     * de las preguntas sin secciones con cajitas y eventos para detcapitulo
     */
    protected function gen_preguntans($r_preguntans,$_tcolumnas,$_larray){
        
        $this->_larray = $_larray;
        $_acces=0;                                          //Lleva el conteo de columnas para el salto de linea
        $larray_agrupados=array();                          //Vector que guarda la posicion de larray para una pregunta tipo checkbox con agrupacion
                                                            // De esa forma no se debe recorrer todo el array de preguntas para saber en que posicion queda
        
        $larray_radiobutton=array();                        //Vector que guarda el nombre del grupo de radio buttons
        $_contagrupacion=array();
        
        $_vtipo1=[1,3,4,5,6,7,8,9,11,12,13,14,15];           //Tipo de preguntas que llevan caja de label y caja de input, estas tambien pueden salir en la seccion
        $_vtipo2=[10,2];                                    //Diferentes pueden llevar o no label, o llevar o no caja
 
        
        foreach ($r_preguntans as $_clavepns){
            
            
            /**1) Columnas para Presentacion de la pregunta******************************************************/
            $cols_l=$_clavepns['num_col_label'];            //Numero de columnas para el label
            $cols_i=$_clavepns['num_col_input'];            //Numero de columnas para el input

            /**2) Asignando las relaciones necesarias para cuando se va a guardar *****************/
            $this->fnt_varpass($this->_larray,$_clavepns['id_pregunta'],$_clavepns['id_tpregunta'],$_clavepns['id_respuesta'],$_clavepns['id_capitulo']);
           
            /*Asignando Tooltip====================================================*/
            $_tooltip='';
            if(!empty($_clavepns['ayuda_pregunta'])){
                $_tooltip=$this->fnt_tooltip($_clavepns['ayuda_pregunta']);
            }
            
            /*Asignando Boton Adicionar si caracteristica_preg es Multiple => 'M' ==========================*/
            $_adicionar="";
            
            /*if($_clavepns['caracteristica_preg'] == 'M' and $this->_pactualizar == 'S'){

                $_adicionar=$this->fnt_adicionar(array("id_tpregunta"=>$_clavepns['id_tpregunta'],"id_seccion"=>"","id_agrupacion"=>$_clavepns['id_agrupacion'],"id_capitulo"=>$_clavepns['id_capitulo'],"id_conjunto_pregunta"=>$_clavepns['id_conjunto_pregunta']));
            }*/
        
            /*Declarando inicio de linea en la tabla =====================================*/
            if($_acces==0){
              $this->_stringvista[]='<tr>';  
            }
            
            
            /*Agregando a la vista para preguntas en el vector tipo 1======================*/
            if($_clavepns['visible_desc_pregunta']=='S' and in_array($_clavepns['id_tpregunta'], $_vtipo1) ){
               
                $_contenido=$_clavepns['nom_pregunta']." ";
                $_contenido2=$_tooltip." ".$_adicionar;
                $this->gen_celdas($cols_l,"","labelpregunta". $_clavepns['stylecss'], $_contenido,$_contenido2);
                $_acces=$_acces+$cols_l;
         
            }

            if($_clavepns['visible']=='S' and in_array($_clavepns['id_tpregunta'], $_vtipo1) ){
                
                $_contenido=$this->{"tipo_".$_clavepns['id_tpregunta']}($_clavepns,$this->_larray);
                $this->gen_celdas($cols_i,"","inputpregunta".$_clavepns['stylecss'], $_contenido,"");
                $_acces=$_acces+$cols_i;

            }
            
            
            /*Agregando a la vista para preguntas en el vector tipo 2======================*/
            if(in_array($_clavepns['id_tpregunta'], $_vtipo2)){
                
                if($_clavepns['id_tpregunta']==10){
                    
                    $_contenido=$this->{"tipo_".$_clavepns['id_tpregunta']}($_clavepns,$this->_larray);
                    $_contenido2=$_tooltip." ".$_adicionar;
                    $this->gen_celdas($cols_i,"", "inputpregunta". $_clavepns['stylecss'], $_contenido,$_contenido2);
                                        
                    $_acces=$_acces+$cols_i;
                }
                
                /*Checkbox sin Agrupacion********************************************************/
                else if($_clavepns['id_tpregunta']==2 and empty($_clavepns['id_agrupacion'])){
                    
                    
                    if($_clavepns['visible_desc_pregunta']=='S'){
                        
                       $_contenido=$_clavepns['nom_pregunta']." ";
                       $_contenido2=$_tooltip." ".$_adicionar;
                       $this->gen_celdas($cols_l,"","labelpregunta". $_clavepns['stylecss'], $_contenido,$_contenido2);

                       $_acces=$_acces+$cols_l;
                    }
                    
                    if($_clavepns['visible']=='S'){
                        
                        $_contenido=$this->{"tipo_".$_clavepns['id_tpregunta']}($_clavepns,$this->_larray);
                        $this->gen_celdas($cols_i,"", "inputpregunta".$_clavepns['stylecss'], $_contenido,"");
                        
                        $_acces=$_acces+$cols_i;
                    }
                    
                    
                }
                
                /*Checkbox con Agrupacion********************************************************/
                else if($_clavepns['id_tpregunta']==2 and !empty($_clavepns['id_agrupacion'])){
                    
                    /*Este pedazo del algoritmo funcion asi:
                     */
                    $_indiagrupacion=$_clavepns['id_agrupacion'];
                    
                    
                    if(empty($larray_agrupados[$_indiagrupacion])){
                        
                        /*Averiguando si es de seleccion multiple o seleccion unica
                         *Se guarda el numero de l_array para la rptaNo. dado que si es tipo 1        
                         *es de seleccion unica entonces todos los radio button deben llevar el mismo
                         *nombre "name" , el valor de cada radiobutton sera la pregunta que
                         * escogio el usuario.    */
                        if(!empty($_clavepns['tipo_agrupacion']) and $_clavepns['tipo_agrupacion']=='1'){
                          $larray_radiobutton[$_indiagrupacion] = $this->_larray;
                          $larray_rdbttn = $larray_radiobutton[$_indiagrupacion];
                        }else{
                          $larray_rdbttn = $this->_larray;  
                        }
                        
                       
                        /*Posicion del label*******/
                        $_posicionlabel = count($this->_stringvista);
                        $larray_agrupados[$_indiagrupacion][0]  = $_posicionlabel;
                        $this->_stringvista[]='';
                        
                         /*Se guarda la posición para las cajitas checkbos o radio button*/
                        $larray_agrupados[$_indiagrupacion][1] = count($this->_stringvista);
                        $this->_stringvista[]='';
                        $_posicioninput = $larray_agrupados[$_indiagrupacion][1]; 
                        
                        /*Se suman las columnas usadas tanto por el label como por el input*/
                        //$_acces=$_acces+$_clavepns['ag_num_col']+$cols_l;
                        $_acces=$_acces+$cols_i+$cols_l;
                        $_finallinea[$_indiagrupacion]="";
                        
                        if($_acces == $_tcolumnas){
                            $_finallinea[$_indiagrupacion]="</tr>";
                        }
                                           
                    
                    }else{
                        
                        /*En este caso ya debe existir una posicion previa para este grupo de preguntas*/
                        $_posicionlabel = $larray_agrupados[$_indiagrupacion][0];
                        $_posicioninput = $larray_agrupados[$_indiagrupacion][1];
                        
                         /*En este caso ya debe existir una posicion previa para este grupo de preguntas*/
                        if(!empty($_clavepns['tipo_agrupacion']) and $_clavepns['tipo_agrupacion']=='1'){
                            $larray_rdbttn = $larray_radiobutton[$_indiagrupacion];
                        }else{
                            $larray_rdbttn = $this->_larray;
                        }
                       
                    }
                    
                    /*Visualizacion en pantalla**********************************************/
                     /*Se guarda en stringvista el td para label, junto con el respectivo label*/
                        if($_clavepns['visible_desc_pregunta']=='S'){
                            
                            //Si la primera interaccion lleva final de carrera se asigna igual para el resto=========================
                            $filasagrupacion[$_indiagrupacion]=(empty($filasagrupacion[$_indiagrupacion]))? 1:$filasagrupacion[$_indiagrupacion]+1;
                            $_contenido=$_clavepns['ag_descripcion']." ";
                            $_contenido2=$_tooltip.' '.$_adicionar;
                            $this->gen_celdaspos($_posicionlabel,$cols_l,'1', "labelpregunta".$_clavepns['stylecss'], $_contenido,$_contenido2);
                            
                        }
                    
                        if($_clavepns['visible']=='S'){
                            
                            $_contagrupacion[$_indiagrupacion]=(empty($_contagrupacion[$_indiagrupacion]))? $this->{"tipo_".$_clavepns['id_tpregunta']}($_clavepns,$larray_rdbttn)." ":$_contagrupacion[$_indiagrupacion].$this->{"tipo_".$_clavepns['id_tpregunta']}($_clavepns,$larray_rdbttn)." ";
                            $this->gen_celdaspos($_posicioninput,$cols_i,'1', "inputpregunta".$_clavepns['stylecss'], $_contagrupacion[$_indiagrupacion],"");
                        }
                                        
                }
                
            }

            $this->_larray+=1;
            
            if($_acces == $_tcolumnas){
              $this->_stringvista[]='</tr>'; 
              $_acces=0;
            }
        }
       
    }
    
    
    /***Funcion para generar la vista
     * de las preguntas sin secciones solo contenido pregunta y respuesta
	 * no genera cajitas ni eventos
     */
    protected function gen_preguntansHTML($r_preguntans,$_tcolumnas,$_larray,$modelpreguntasns){
        
        $this->_larray = $_larray;
        $_acces=0;                                          //Lleva el conteo de columnas para el salto de linea
        $larray_agrupados=array();                          //Vector que guarda la posicion de larray para una pregunta tipo checkbox con agrupacion
                                                            // De esa forma no se debe recorrer todo el array de preguntas para saber en que posicion queda
        
        $larray_radiobutton=array();                        //Vector que guarda el nombre del grupo de radio buttons
        $_contagrupacion=array();
        
        $_vtipo1=[1,3,4,5,6,7,8,15];                        //Cajas con una unica respuesta
        $_vtipo2=[9,11,12,13,14];                                //Cajas de multiples respuestas tabla por aparte 14
 
        
        foreach ($r_preguntans as $_clavepns){
            
            
            /**1) Columnas para Presentacion de la pregunta******************************************************/
            $cols_l=$_clavepns['num_col_label'];            //Numero de columnas para el label
            $cols_i=$_clavepns['num_col_input'];            //Numero de columnas para el input

          
          
            /*Declarando inicio de linea en la tabla =====================================*/
            if($_acces==0){
              $this->htmlvista.='<tr>';  
            }
            
            
            /*Agregando a la vista para preguntas en el vector tipo 1======================*/
            if($_clavepns['visible_desc_pregunta']=='S' and in_array($_clavepns['id_tpregunta'], $_vtipo1) ){
               
                $_contenido=$_clavepns['nom_pregunta']." ";
                $this->gen_celdashtml($cols_l,"","labelpregunta". $_clavepns['stylecss'], $_contenido);
                $_acces=$_acces+$cols_l;
         
            }

            if($_clavepns['visible']=='S' and in_array($_clavepns['id_tpregunta'], $_vtipo1) ){
                
                $_contenido=$this->tipohtmltexto($modelpreguntasns,$this->_larray,$_clavepns);
                $this->gen_celdashtml($cols_i,"","inputpregunta".$_clavepns['stylecss'], $_contenido);
                $_acces=$_acces+$cols_i;

            }
            
            
            /*Agregando a la vista preguntas tipo checkbox sin agrupacion=================================*/
            
            if($_clavepns['id_tpregunta']==2 and empty($_clavepns['id_agrupacion'])){
                    
                    
                    if($_clavepns['visible_desc_pregunta']=='S'){
                       $_contenido=$_clavepns['nom_pregunta']." ";
                       $this->gen_celdashtml($cols_l,"","labelpregunta ".$_clavepns['stylecss'], $_contenido);
                       $_acces=$_acces+$cols_l;
                    }
                    
                    if($_clavepns['visible']=='S'){
                           $_contenido=$this->tipohtmlcheck1($modelpreguntasns,$this->_larray);
                           $this->gen_celdashtml($cols_i,"","inputpregunta ".$_clavepns['stylecss'], $_contenido);
                           $_acces=$_acces+$cols_i;
                    }
            }
            
            /*Agregando a la vista preguntas tipo checkbox con agrupacion==================================*/
            else if($_clavepns['id_tpregunta']==2 and !empty($_clavepns['id_agrupacion'])){
                
                    $_regagrupacion=$_clavepns['id_agrupacion'];
                    
                    if(empty($this->td_agrupadas[$_regagrupacion])){
                        
                        $this->htmlvista.="<td>agrupacion".$_regagrupacion."</td>";
                    }
                    
                    $_creandohtml = $this->tipohtmlcheck_grupo($modelpreguntasns,$this->_larray,$_clavepns,$_tcolumnas,$_acces);
                    $_acces = $_acces+$_creandohtml[0]+$_creandohtml[1];
            }
            
            
            
            /*Agregando tipo de pregunta 10 -BOTON =====================================================*/
            
            
            
            /*Agregando tipo pregunta 11 SOPORTE========================================================*/
            if(in_array($_clavepns['id_tpregunta'], $_vtipo2)){
                 
                if($_acces>0){
                    $this->htmlvista.='</tr><tr>'; 
                }
                
                $_acces=$_tcolumnas;
                $this->{"tipohtml_".$_clavepns['id_tpregunta']}($_clavepns,$_tcolumnas);
            }

            
            
            $this->_larray+=1;
            
            if($_acces==$_tcolumnas){
              $this->htmlvista.='</tr>'; 
              $_acces=0;
            }
            
        }
        
        
        //Agregando agrupadas a la vista html====================================================
        foreach($this->td_agrupadas as $_clagrupadas => $_vlagrupadas){
            
            $ind_clagrupado = $_clagrupadas;
            $_linearemplazo = $_vlagrupadas["ag_descripcion"];
            $_linearemplazo.= $_vlagrupadas["respuestas"]."</td>";
            
        
            $this->htmlvista = str_replace("<td>agrupacion".$ind_clagrupado."</td>",$_linearemplazo,$this->htmlvista);
            
        }
        $this->td_agrupadas=array();
       
    }
    
    
    
    
 /********************************************************************generando preguntas con secciones************************************/
 /*****************************************************************************************************************************************/
    
    /* Funcion para generar secciones*/
    public function gen_secciones($r_secciones,$_tcolumnas,$r_pregunta,$_larray){
        
        $this->_larray = $_larray;
        
        foreach ($r_secciones as $_clavesec){
               
               $_numcoltitle=$_tcolumnas;
               $_colxfila=$_tcolumnas/$_numcoltitle;
               $_porcentajefila=100/$_colxfila;
              
               
               /*Asignando inicio de tabla===================================================//
                */ 
               $this->_stringvista[]='<tr><td colspan="'.$_tcolumnas.'"><table class="tbseccion">'; 
               
               /*Asignando Numeracion=========================================================*/
                if($this->_estnumerado == 'S'){
                    
                    /*Se compara con el anteriro capitulo si cambio se reinicia la numeracion de seccion*/
                    if($this->_numcapitulo != $this->last_capitulo){
                        $this->_numseccion = 0;
                    }
                    
                    $this->_numseccion+=1;
                    $_numeracion = $this->_numcapitulo.".".$this->_numseccion;
                    $this->last_capitulo = $this->_numcapitulo;
                    
                }else{
                    $_numeracion = "";
                }
               
               /*Se realizan dos tareas diferentes la pregunta es igual 
                * a la seccion o no, si el dato comparar viene vacio no es igual*/
               if(!empty($_clavesec['comparar'])){
                   
                   $this->_stringvista[]='<tr>';
                   $this->_stringvista[]='<td colspan="'.$_numcoltitle.'" class="titleseccion" width="'.$_porcentajefila.'%">'; 
                   $this->_stringvista[]=$_numeracion." ".$_clavesec['nom_seccion'];
                   $this->_stringvista[]='</td>';
                   $this->_stringvista[]='</tr>';
                   
               }else{
                  
                   $this->_stringvista[]='<tr>';
                   $this->_stringvista[]='<td colspan="'.$_tcolumnas.'" class="titleseccion">'; 
                   $this->_stringvista[]=$_clavesec['nom_seccion'];
                   $this->_stringvista[]='</td>'; 
                   $this->_stringvista[]='</tr>';
               }  
               
               /*Organizando preguntas asociadas a la seccion*/
               $_indiseccion=$_clavesec['id_seccion'];
               
               if(!empty($r_pregunta[$_indiseccion])){
                   $_vpreguntassec=$this->gen_pregutasec($_clavesec['comparar'],$_numcoltitle,$r_pregunta[$_indiseccion], $this->_larray,$_tcolumnas);
               }
               
               $this->_stringvista[]='</table></td></tr>';
            }
    }
    
    
    /*Funcion para generar seccion
     * para el contenido del formato HTML
     */
    
    public function gen_seccionesHTML($r_secciones,$_tcolumnas,$r_pregunta,$_larray,$modelpreguntas){
        
         $this->_larray = $_larray;
        
        foreach ($r_secciones as $_clavesec){
               
               $_numcoltitle=$_tcolumnas;
               $_colxfila=$_tcolumnas/$_numcoltitle;
               //$_porcentajefila=100/$_colxfila;
               $_porcentajefila=100;
              
               
               /*Asignando inicio de tabla===================================================//
                */ 
                if($this->tipo_archivo == 'excel'){
                    $this->htmlvista.='</table><table class="tbseccion" width="100%">'; 
                }else{
                    $this->htmlvista.='<tr><td colspan="'.$_tcolumnas.'"><table class="tbseccion" width="100%">'; 
                }
                
               /*Asignando Numeracion=========================================================*/
                if($this->_estnumerado == 'S'){
                    
                    /*Se compara con el anteriro capitulo si cambio se reinicia la numeracion de seccion*/
                    if($this->_numcapitulo != $this->last_capitulo){
                        $this->_numseccion = 0;
                    }
                    
                    $this->_numseccion+=1;
                    $_numeracion = $this->_numcapitulo.".".$this->_numseccion;
                    $this->last_capitulo = $this->_numcapitulo;
                    
                }else{
                    $_numeracion = "";
                }
               
               /*Se realizan dos tareas diferentes la pregunta es igual 
                * a la seccion o no, si el dato comparar viene vacio no es igual*/
               if(!empty($_clavesec['comparar'])){
                   
                   $this->htmlvista.='<tr>';
                   $this->htmlvista.='<td colspan="'.$_numcoltitle.'" class="titleseccion" width="100%">'; 
                   $this->htmlvista.=$_numeracion." ".htmlentities($_clavesec['nom_seccion']);
                   $this->htmlvista.='</td>';
                   
               }else{
                  
                   $this->htmlvista.='<tr>';
                   $this->htmlvista.='<td colspan="'.$_tcolumnas.'" class="titleseccion" width="100%">'; 
                   $this->htmlvista.=htmlentities($_clavesec['nom_seccion']);
                   $this->htmlvista.='</td>'; 
                   $this->htmlvista.='</tr>';
               }  
               
               /*Organizando preguntas asociadas a la seccion*/
               $_indiseccion=$_clavesec['id_seccion'];
               
               if(!empty($r_pregunta[$_indiseccion])){
                   $_vpreguntassec=$this->gen_pregutasecHTML($_clavesec['comparar'],$_numcoltitle,$r_pregunta[$_indiseccion], $this->_larray,$_tcolumnas,$modelpreguntas);
               }
               
               if($this->tipo_archivo == 'excel'){
                    $this->htmlvista.='</table>'; 
                }else{
                   $this->htmlvista.='</table></td></tr>'; 
                }
               
               
            }
            
    }
    
    
    /*Funcion para las preguntas por seccion
     * esta funcion aplica para formularios de llenado
     * contiene cajitas de texto con eventos */
    public function gen_pregutasec($secpregunta,$_numcoltitle,$r_preguntasecc,$_larray,$_tcolumnas){
        
                $_accesp=1;
                $col_for=0;                                    //Columnas por entrada 
                $this->_larray = $_larray;
                $_vtipo1=[1,3,4,5,6,7,8,9,11,12,13,14,15];       //Tipo de preguntas que llevan caja de label y caja de input, estas tambien pueden salir en la seccion
                $_vtipo2=[10,2];
                $_vtipo3=[2];                                    //Preguntas tipo preg M que llevan boton de +
                
                
                foreach ($r_preguntasecc as $_claveps){
                    
                    
                    /**Organizando Vista******************************************************************/
                                      
                    $cols_l=$_claveps['num_col_label'];            //Numero de columnas para el label
                    $cols_i=$_claveps['num_col_input'];            //Numero de columnas para el input
                    
                  
                    /**2) Asignando las relaciones necesarias para cuando se va a guardar *****************/
                    $this->fnt_varpass($this->_larray,$_claveps['id_pregunta'],$_claveps['id_tpregunta'],$_claveps['id_respuesta'],$_claveps['id_capitulo']);
              
                    /*Asignando Tooltip====================================================*/
                    $_tooltip='';
                    if(!empty($_claveps['ayuda_pregunta'])){
                        $_tooltip=$this->fnt_tooltip($_claveps['ayuda_pregunta']);
                    }
            
                    /*Asignando Boton Adicionar si caracteristica_preg es Multiple => 'M' ==========================*/
                    $_adicionar="";
                    /*if($_claveps['caracteristica_preg'] == 'M' and $this->_pactualizar == 'S' and in_array($_claveps['id_tpregunta'], $_vtipo3)){
                        $_adicionar=$this->fnt_adicionar(array("id_tpregunta"=>$_claveps['id_tpregunta'],"id_seccion"=>"","id_agrupacion"=>$_claveps['id_agrupacion'],"id_capitulo"=>$_claveps['id_capitulo'],"id_conjunto_pregunta"=>$_claveps['id_conjunto_pregunta']));
                    }*/
                    
                   
                    /*Asignando Numeracion=========================================================*/
                    if($this->_estnumerado == 'S'){

                        /*Reiniciamos contador de preguntas al cambiar de seccion*/    
                        if($this->_numseccion != $this->last_seccion){
                            $this->_numpregunta = 0;
                        }
                        
                        $this->_numpregunta +=1;
                        $_numeracion = $this->_numcapitulo.".".$this->_numseccion.".".$this->_numpregunta;
                        $this->last_seccion = $this->_numseccion;
                        
                    }else{
                        
                        $_numeracion = "";
                    }
                     
                    /*Si contador es igual a 0 y la respuesta no esta asociada a la pregunta de la seccion 
                     * se inicia nueva fila
                     */
                    if($col_for==0 ){
                       $this->_stringvista[]='<tr>'; 
                    }
                    
                    /*Vista para tipos del 1 al 9****************/
                    if(in_array($_claveps['id_tpregunta'], $_vtipo1)){
                        
                        if($_accesp==1 and !empty($secpregunta) and $_claveps['visible']=='S'){

                            $_contenido=$this->{"tipo_".$_claveps['id_tpregunta']}($_claveps,$this->_larray);
                            $this->gen_celdas($_numcoltitle,"","titleseccion ", $_contenido,"");
                            $col_for=$col_for+$_numcoltitle;
                            $this->_stringvista[]="</tr>";
                            $_tcolumnas=$col_for;

                        }else{

                            if($_claveps['visible_desc_pregunta'] == 'S'){
                                
                                $_contenido=$_numeracion.' &nbsp; '.$_claveps['nom_pregunta'];
                                $this->gen_celdas($cols_l,"","labelpregunta".$_claveps['stylecss'], $_contenido,$_tooltip.' '.$_adicionar);
                                $col_for=$col_for+$cols_l;
                            }
                            
                            if($_claveps['visible'] == 'S'){
                                
                                $_contenido=$this->{"tipo_".$_claveps['id_tpregunta']}($_claveps,$this->_larray);
                                $this->gen_celdas($cols_i,"","inputpregunta".$_claveps['stylecss'], $_contenido,"");
                                $col_for=$col_for+$cols_i;
                            }

                           
                        }
                    }
                    else if(in_array($_claveps['id_tpregunta'], $_vtipo2)){
                        
                        /*Vista para el tipo 10**********************************************/
                        if($_claveps['id_tpregunta']==10 and $_claveps['visible'] == 'S'){
                            
                            $_contenido=$this->{"tipo_".$_claveps['id_tpregunta']}($_claveps,$this->_larray);
                            $this->gen_celdas($cols_i,"","inputpregunta".$_claveps['stylecss'], $_contenido,$_tooltip.' '.$_adicionar);
                            $col_for=$col_for+$cols_i;
                        }
                        
                         /*Checkbox sin Agrupacion********************************************************/
                        else if($_claveps['id_tpregunta']==2 and empty($_claveps['id_agrupacion'])){
                            
                            if($_accesp==1 and !empty($secpregunta) and $_claveps['visible']=='S'){
                                
                                $_contenido=$this->{"tipo_".$_claveps['id_tpregunta']}($_claveps,$this->_larray);
                                $this->gen_celdas($_numcoltitle,"","titleseccion ".$_claveps['stylecss'], $_contenido,"");
                                $col_for=$col_for+$_numcoltitle;
                                
                            }else{
                               
                                if($_claveps['visible_desc_pregunta'] == 'S'){
                                    
                                    $_contenido=$_numeracion.' &nbsp; '.$_claveps['nom_pregunta'].'';
                                    $this->gen_celdas($cols_l,"","labelpregunta".$_claveps['stylecss'], $_contenido,$_tooltip.' '.$_adicionar);
                                    $col_for=$col_for+$cols_l;
                                }
                                
                                if($_claveps['visible'] == 'S'){
                                    
                                    $_contenido=$this->{"tipo_".$_claveps['id_tpregunta']}($_claveps,$this->_larray);
                                    $this->gen_celdas($cols_i,"","inputpregunta".$_claveps['stylecss'], $_contenido,"");
                                    $col_for=$col_for+$cols_l;

                                }

                            }
                        }
                        
                        /*Checkbox con Agrupacion********************************************************/
                        else if($_claveps['id_tpregunta']==2 and !empty($_claveps['id_agrupacion'])){
                    
                            /*Este pedazo del algoritmo funcion asi:
                             */
                            $_indiagrupacion=$_claveps['id_agrupacion'];


                            if(empty($larray_agrupados[$_indiagrupacion])){

                                /*Averiguando si es de seleccion multiple o seleccion unica
                                 *Se guarda el numero de l_array para la rptaNo. dado que si es tipo 1        
                                 *es de seleccion unica entonces todos los radio button deben llevar el mismo
                                 *nombre "name" , el valor de cada radiobutton sera la pregunta que
                                 * escogio el usuario.    */
                                if(!empty($_claveps['tipo_agrupacion']) and $_claveps['tipo_agrupacion']=='1'){
                                  $larray_radiobutton[$_indiagrupacion] = $this->_larray;
                                  $larray_rdbttn = $larray_radiobutton[$_indiagrupacion];
                                }else{
                                  $larray_rdbttn = $this->_larray;  
                                }


                                /*Posicion del label*******/
                                $_posicionlabel = count($this->_stringvista);
                                $larray_agrupados[$_indiagrupacion][0]  = $_posicionlabel;
                                $this->_stringvista[]='';

                                 /*Se guarda la posición para las cajitas checkbos o radio button*/
                                $larray_agrupados[$_indiagrupacion][1] = count($this->_stringvista);
                                $this->_stringvista[]='';
                                $_posicioninput = $larray_agrupados[$_indiagrupacion][1]; 

                                /*Se suman las columnas usadas tanto por el label como por el input*/
                                $col_for=$col_for+$_claveps['ag_num_col']+$cols_i;


                            }else{

                                /*En este caso ya debe existir una posicion previa para este grupo de preguntas*/
                                $_posicionlabel = $larray_agrupados[$_indiagrupacion][0];
                                $_posicioninput = $larray_agrupados[$_indiagrupacion][1];

                                 /*En este caso ya debe existir una posicion previa para este grupo de preguntas*/
                                if(!empty($_claveps['tipo_agrupacion']) and $_claveps['tipo_agrupacion']=='1'){
                                    $larray_rdbttn = $larray_radiobutton[$_indiagrupacion];
                                }else{
                                    $larray_rdbttn = $this->_larray;
                                }

                            }

                            /*Visualizacion en pantalla**********************************************/
                             /*Se guarda en stringvista el td para label, junto con el respectivo label*/
                                if($_claveps['visible_desc_pregunta']=='S'){

                                    $_contenido=$_claveps['ag_descripcion']." ";
                                    $_contenido2=$_tooltip.' '.$_adicionar;
                                    $this->gen_celdaspos($_posicionlabel,$_claveps['ag_num_col'],$_claveps['ag_num_row'], "labelpregunta".$_claveps['stylecss'], $_contenido,$_contenido2);

                                }

                                if($_claveps['visible']=='S'){

                                    $_contagrupacion[$_indiagrupacion]=(empty($_contagrupacion[$_indiagrupacion]))? $this->{"tipo_".$_claveps['id_tpregunta']}($_claveps,$larray_rdbttn)." ":$_contagrupacion[$_indiagrupacion].$this->{"tipo_".$_claveps['id_tpregunta']}($_claveps,$larray_rdbttn)." ";
                                    $this->gen_celdaspos($_posicioninput,$cols_i,$_claveps['ag_num_row'], "inputpregunta".$_claveps['stylecss'], $_contagrupacion[$_indiagrupacion],"");
                                 }

                        }                        
                    }
                    
                    $_accesp+=1;
                    $this->_larray+=1;
                    
                    /*Cuando el Numero de celdas de la fila de la seccion sea igual al numero total de columnas que tiene el
                     * capitulo entonces cierra la fila, y se reinicia contador para la siguiente fila*/
                    if($_tcolumnas==$col_for){
                        $this->_stringvista[]='</tr>'; 
                        $col_for=0; 
                    }
                    
                }                
                
                
    }
    
    /*Funcion para generar espacios de respuesta de las preguntas 
     * que pertenece a una seccion solo aplica para FORMATOHTML
     */
    public function gen_pregutasecHTML($secpregunta,$_numcoltitle,$r_preguntasecc,$_larray,$_tcolumnas,$modelpreguntasns){
        $this->_larray = $_larray;
        $_acces=0;                                          //Lleva el conteo de columnas para el salto de linea
        $larray_agrupados=array();                          //Vector que guarda la posicion de larray para una pregunta tipo checkbox con agrupacion
                                                            // De esa forma no se debe recorrer todo el array de preguntas para saber en que posicion queda
        
        
        $larray_radiobutton=array();                        //Vector que guarda el nombre del grupo de radio buttons
        $_contagrupacion=array();
        
        
       
        $_vtipo1=[1,3,4,5,6,7,8,15];                            //Cajas con una unica respuesta
        $_vtipo2=[9,11,12,13,14];                                //Cajas de multiples respuestas tabla por aparte 14
        
        foreach ($r_preguntasecc as $_claveps){
            
             /**1) Columnas para Presentacion de la pregunta******************************************************/
            $cols_l=$_claveps['num_col_label'];            //Numero de columnas para el label
            $cols_i=$_claveps['num_col_input'];            //Numero de columnas para el input

          
          
            /*Declarando inicio de linea en la tabla =====================================*/
            if($_acces==0){
              $this->htmlvista.='<tr>';  
            }
            
            
            if($_acces==0 and !empty($secpregunta) and $_claveps['visible']=='S'){
                    
                $_claveps['visible_desc_pregunta'] = 'N';
                $_claveps['visible'] = 'S';
            }
            
             
            //Agregando vista para preguntas en el vector tipo 1===========================//
            if($_claveps['visible_desc_pregunta']=='S' and in_array($_claveps['id_tpregunta'], $_vtipo1) ){
               
                $_contenido=$_claveps['nom_pregunta']." ";
                $this->gen_celdashtml($cols_l,"","labelpregunta". $_claveps['stylecss'], $_contenido);
                $_acces=$_acces+$cols_l;
         
            }

            if($_claveps['visible']=='S' and in_array($_claveps['id_tpregunta'], $_vtipo1) ){
                
                if(!empty($secpregunta)){
                    
                    $_contenido=$this->tipohtmltexto($modelpreguntasns,$this->_larray,$_claveps);
                    $this->gen_celdashtml($_tcolumnas,"","inputpregunta".$_claveps['stylecss'], $_contenido);
                    $_acces=$_tcolumnas;
                    $secpregunta="";
                    
                }else{
                    
                    $_contenido=$this->tipohtmltexto($modelpreguntasns,$this->_larray,$_claveps);
                    $this->gen_celdashtml($cols_i,"","inputpregunta".$_claveps['stylecss'], $_contenido);
                    $_acces=$_acces+$cols_i;
                }

            }
            
            
            /*Agregando a la vista preguntas tipo checkbox sin agrupacion=================================*/
            if($_claveps['id_tpregunta']==2 and empty($_claveps['id_agrupacion'])){
                    
                    if($_claveps['visible_desc_pregunta']=='S'){
                       $_contenido=$_claveps['nom_pregunta']." ";
                       $this->gen_celdashtml($cols_l,"","labelpregunta". $_claveps['stylecss'], $_contenido);
                       $_acces=$_acces+$cols_l;
                    }
                    
                    if($_claveps['visible']=='S'){
                           $_contenido=$this->tipohtmlcheck1($modelpreguntasns,$this->_larray);
                           $this->gen_celdashtml($cols_i,"","inputpregunta".$_claveps['stylecss'], $_contenido);
                           $_acces=$_acces+$cols_i;
                    }
            }
            
            
            /*Agregando a la vista preguntas tipo checkbox con agrupacion==================================*/
            else if($_claveps['id_tpregunta']==2 and !empty($_claveps['id_agrupacion'])){
                
                    $_regagrupacion=$_claveps['id_agrupacion'];
                    
                    if(empty($this->td_agrupadas[$_regagrupacion])){
                        
                        $this->htmlvista.="<td>agrupacion".$_regagrupacion."</td>";
                    }
                    
                    $_creandohtml = $this->tipohtmlcheck_grupo($modelpreguntasns,$this->_larray,$_claveps,$_tcolumnas,$_acces);
                    $_acces = $_acces+$_creandohtml[0]+$_creandohtml[1];
            }
            
            
             /*Agregando tipo pregunta 11 SOPORTE========================================================*/
            if(in_array($_claveps['id_tpregunta'], $_vtipo2)){
                 
                if($_acces>0){
                    $this->htmlvista.='</tr><tr>'; 
                }
                
                $_acces=$_tcolumnas;
                $this->{"tipohtml_".$_claveps['id_tpregunta']}($_claveps,$_tcolumnas);
            }

            
            
            $this->_larray+=1;
            if($_acces==$_tcolumnas){
              $this->htmlvista.='</tr>'; 
              $_acces=0;
            }
            
            
        }
        
         //Agregando agrupadas a la vista html====================================================
        foreach($this->td_agrupadas as $_clagrupadas => $_vlagrupadas){
            
            $ind_clagrupado = $_clagrupadas;
            $_linearemplazo = $_vlagrupadas["ag_descripcion"];
            $_linearemplazo.= $_vlagrupadas["respuestas"];
            $_linearemplazo.= "</td>";
        
            $this->htmlvista = str_replace("<td>agrupacion".$ind_clagrupado."</td>",$_linearemplazo,$this->htmlvista);
            
        }
        
        $this->td_agrupadas=array();
        
    }
    
    
    
    
    
    /*Funcion que asigna valores a varpass, para relacionar
     * el id_pregunta con el id_respuesta esta variable $this->varpass se usa para gestionar el guardado en la base de datos 
     * de las respuestas que asigna el usuario
     */
    private function fnt_varpass($_larray,$_idpregunta,$_idtpregunta,$_idrespuesta,$_idcapitulo){
        
         /*Recogiendo variables para data_pass id_pregunta,id_tpregunta,id_respuesta*/
        $this->_varpass.=$_larray.":::".$_idpregunta.":::".$_idtpregunta.":::".$_idrespuesta.":::".$_idcapitulo."%%";
        $_indrelaciones =$_idpregunta;
        $this->_vrelaciones[$_indrelaciones] = $_larray;
    }
    
    
    /*Funcion para genera un boton tipo tooltip
     * 
     */
    
    private function fnt_tooltip($_textotooltip){
        
        $_botontool = '<?= yii\helpers\Html::a(yii\helpers\Html::img("@web/images/icono.jpg")
                                    ,"", 
                                    ["title" => "'.$_textotooltip.'", 
                                      "data-toggle" => "tooltip", 
                                      "class" => "not-active",
                                    ] ); ?>';
                
        return $_botontool;        
    }
    
    
    /*Funcion que genera el boton de agregar mas preguntas*/
    
    private function fnt_adicionar($_varsget){
        
        
        /*Adicionar debe seguir siendo un boton pero se debe tener en cuenta que
        debe hacer lo mismo que los multiples que apuntan a otras tablas:
         * 
         * 1) al dar clic debe ir a fd_respuesta
         * 2) el boton se debe llegar id_pregunta, id_conjunto_prta, id_capitulo, etc...
         * 3) se debe habilitar una casilla de acuerdo al tipo de pregunta y debe salir un gridview
         * con las respustas que ha seleccionado el cliente que ya se han ido guardando.
         * 4) Tener presente que al abrirse la ventana debe ser de tipo modal y al cerrar no debe afectar la ventana, o en cuyo caso
         * se debe guardar lo que se lleve antes de abrir la solucion multiple.
         * 5) Al regresar debe dejar el focus en donde va el usuario
         * 
         */
        
        
        
        $_adicionar ='<?= yii\helpers\Html::button("+", 
                            ["value"=>yii\helpers\Url::toRoute(["fdpregunta/createm",
                            "_tipopregunta"=>"'.$_varsget["id_tpregunta"].'"'
                            . ',"id_seccion"=>"'.$_varsget["id_seccion"].'",'
                            . '"id_agrupacion"=>"'.$_varsget["id_agrupacion"].'",'
                            . '"id_capitulo"=>"'.$_varsget["id_capitulo"].'",'
                            . '"id_conj_prta"=>"'.$_varsget["id_conjunto_pregunta"].'",'
                            . '"id_conj_rpta"=>'.$this->id_conj_rpta.','
                            . '"id_fmt"=>'.$this->id_fmt.','
                            . '"id_version"=>'.$this->id_version.','
                            . '"antvista"=>"'.$this->antvista.'",'
                            . '"estado"=>'.$this->estado.','
                            . '"parroquias"=>"'.$this->parroquias.'",'
                            . '"cantones"=>"'.$this->cantones.'",'
                            . '"provincia"=>"'.$this->provincia.'",'
                            . '"periodos"=>"'.$this->periodos.'",'
                            . '"capituloid"=>"'.$this->capituloid.'"],true),
                             "class" => "btn btn-default btn-xs showModalButton"
                            ]); ?>';
        
        return $_adicionar;
    }
    
    /*Funcion para agregar celdas sin posicionamiento exclusivo
     * Si es label con tooltip :
     * $this->_stringvista[]='<td colspan="'.$cols_l.'" class="labelpregunta'.$_clavepns['stylecss'].' ">';
                $this->_stringvista[]=$_clavepns['nom_pregunta'];
                $this->_stringvista[]=$_tooltip.' '.$_adicionar.'</td>'; */
    
    private  function gen_celdas($_colspan,$_rowspan,$_style,$_html_1,$_html_2){
        
        $_style=trim($_style);
        $this->_stringvista[]='<td colspan="'.$_colspan.'" rowspan="'.$_rowspan.'" class="'.$_style.' ">';
        $this->_stringvista[]=$_html_1;
        $this->_stringvista[]=$_html_2.'</td>';
        
    }
    
    
    private  function gen_celdashtml($_colspan,$_rowspan,$_style,$_html_1){
        
        $_style=trim($_style);  //Retira espacios al inicio y al final de la clas
        
        $this->htmlvista.='<td colspan="'.$_colspan.'" rowspan="'.$_rowspan.'" class="'.$_style.'">';
        $this->htmlvista.=$_html_1.'</td>';
        
    }
    
    /*Funcion para agregar celdas con posicionamiento exclusivo
     * Si es label con tooltip :
     * $this->_stringvista[]='<td colspan="'.$cols_l.'" class="labelpregunta'.$_clavepns['stylecss'].' ">';
                $this->_stringvista[]=$_clavepns['nom_pregunta'];
                $this->_stringvista[]=$_tooltip.' '.$_adicionar.'</td>'; */
    
    private  function gen_celdaspos($_posicion,$_colspan,$_rowspan,$_style,$_html_1,$_html_2){
        
    
            $this->_stringvista[$_posicion]='<td colspan="'.$_colspan.'" rowspan="'.$_rowspan.'" class="labelpregunta'.$_style.' ">';
            $this->_stringvista[$_posicion].=$_html_1;
            $this->_stringvista[$_posicion].=$_html_2.'</td>';
        
    }


    
    public function gen_htmlCDA($cabezote,$analizar_informacion,$registrar_datos_solicitante,$solicitar_informacion,$registrar_respuesta,$datos_tecnicos,$datos_senagua,$datos_epa,$error_pres,$datos_visita,$registro_cda,$puntos,$analisis_hidrologico){
        
        $_string = '';
        
        //----------------------------INICIA CABEZOTE DATOS BASICOS --------------------------------->
        if(!empty($cabezote)){
            
            $_string= '<table class="cda">';
            
            $_string .= '<tr>'
                    .'<td class="datosbasicos1"> N&uacute;mero CDA </td>'
                        .'<td class="datosbasicos2">'
                            .'<table width="100%">
                                <tr>
                                    <td width="50%">'.$cabezote[0]['numero'].'</td>
                                </tr>
                            </table>
                        </td>'
                        .'<td class="datosbasicos1"> Tr&uacute;mite Administrativo </td>
                        <td class="datosbasicos2">'.$cabezote[0]['tramite_administrativo'].'</td>'
                    .'</tr>
                    <tr>
                        <td class="datosbasicos1"> N&uacute;mero de Quipux ARCA </td>
                        <td class="datosbasicos2">'.$cabezote[0]['arca'].'</td>'
                        .'<td class="datosbasicos1"> N&uacute;mero de Quipux SENAGUA </td>
                        <td class="datosbasicos2">'.$cabezote[0]['senagua'].'</td>
                    </tr>
                    <tr>
                        <td class="datosbasicos1"> Fecha Ingreso </td>
                        <td class="datosbasicos2">'.$cabezote[0]['fecha_ingreso'].'</td>
                        <td class="datosbasicos1"> Fecha de Solicitud </td>
                        <td class="datosbasicos2">'.$cabezote[0]['fecha_solicitud'].'</td>
                    </tr>
                    <tr>
                        <td class="datosbasicos1"> Número de Trámites </td>
                        <td class="datosbasicos2">'.$cabezote[0]['numero_tramites'].'</td>
                        <td class="datosbasicos1"> Enviado Por </td>
                        <td class="datosbasicos2">'.$cabezote[0]['enviadopor'].'</td>
                    </tr>
                    <tr>
                        <td class="datosbasicos1"> En Calidad de </td>
                        <td class="datosbasicos2" colspan="3">'.$cabezote[0]['encalidade'].'</td>
                    </tr>';
                    
                
                //Responsable=============================================================================================    
                $_string .= '<tr><td colspan="4" class="cdatitulos">Responsables del CDA</td></tr>';
                $_string .= '<tr>'
                            .'<td colspan="2" class="datosbasicos1">Nombre del Responsable: <td>'
                            . '<td colspan="2" class="datosbasicos2">'.$cabezote[0]['usuario_accion'].'<td>'
                            . '</tr>';
                
         
                $_string .= '<tr>
                        <td class="datosbasicos1"> Puntos Solicitados </td>
                        <td class="datosbasicos2">'.$analizar_informacion->puntos_solicitados.'</td>
                        <td class="datosbasicos1"> C&oacute;digo Solicitud del T&eacute;cnico </td>
                        <td class="datosbasicos2">'.$analizar_informacion->codigo_solicitud_tecnico.'</td>
                    </tr>';
                
                //Registrar datos del solicitante=========================================================================
                $_string .= '<tr><td colspan="4" class="cdatitulos">Registrar datos del Solicitante</td></tr>';
                
                 $_string .= '<tr>
                        <td class="datosbasicos1"> Instituci&oacute;n o gremio solicitante: </td>
                        <td class="datosbasicos2">'.$registrar_datos_solicitante->institucion_solicitante.'</td>
                        <td class="datosbasicos1"> Demarcaci&oacute;n Hidrogr&aacute;fica </td>
                        <td class="datosbasicos2">'.$registrar_datos_solicitante->idDemarcacion['nombre_demarcacion'].'</td>
                    </tr>';
                 
                  $_string .= '<tr>
                        <td class="datosbasicos1"> Solicitante Representante: </td>
                        <td class="datosbasicos2">'.$registrar_datos_solicitante->solicitante.'</td>
                        <td class="datosbasicos1"> Centro de Atención Ciudadano: </td>
                        <td class="datosbasicos2">'.$registrar_datos_solicitante->centroatencion['nom_centro_atencion_ciudadano'].'</td>
                    </tr>';
                  
                  
                 //Solicitudes de Informacion============================================================================= 
                 $_string .= '<tr><td colspan="4" class="cdatitulos">Solicitudes de Información</td></tr>';
                 $_string .= '<tr><td colspan="4">';
                            
                 $_string .= '<table width="100%">';
                 $_string .= '<tr>
                        <td class="datosbasicos1"> Tipo de Informaci&oacute;n faltante </td>
                        <td class="datosbasicos1"> Información solicitada a</td>
                        <td class="datosbasicos1"> Tipo de Atención </td>
                        <td class="datosbasicos1"> Observaciones </td>
                        <td class="datosbasicos1"> Oficio de Atención </td>
                        <td class="datosbasicos1"> Fecha de Atención </td>
                    </tr>';
                 
                 
                 foreach($solicitar_informacion as $sol_clave){
                     
            
                     $_string .= '<tr>
                        <td class="datosbasicos2">'.$sol_clave->idTinfoFaltante['nom_tinfo_faltante'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->idTreporte['nom_treporte'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->idTatencion['nom_tatencion'].' </td>
                        <td class="datosbasicos2">'.$sol_clave->observaciones.' </td>
                        <td class="datosbasicos2">'.$sol_clave->oficio_atencion.'</td>
                        <td class="datosbasicos2">'.$sol_clave->fecha_atencion.'</td>
                    </tr>';
                     
                 }
                 
                 
                 $_string .= '</table>';
                 $_string .= '</td></tr>';    


                 //Solicitudes de Informacion============================================================================= 
                 $_string .= '<tr><td colspan="4" class="cdatitulos">Registrar Respuesta</td></tr>';
                 $_string .= '<tr><td colspan="4">';
                 
                 
                 $_string .= '<table>';
                 $_string .= '<tr>
                        <td class="datosbasicos1"> Tipo de Informaci&oacute;n faltante </td>
                        <td class="datosbasicos1"> Informaci&oacute;n solicitada a</td>
                        <td class="datosbasicos1"> Tipo de Atenci&oacute;n </td>
                        <td class="datosbasicos1"> Oficio de Atenci&oacute;n </td>
                        <td class="datosbasicos1"> Fecha de Atenci&oacute;n </td>
                        <td class="datosbasicos1"> Tipo de Respuesta </td>
                        <td class="datosbasicos1"> Oficio de Respuesta </td>
                        <td class="datosbasicos1"> Fecha de Respuesta </td>
                         <td class="datosbasicos1"> Observaciones </td>
                        
                       
                    </tr>';
                 
                 
                 foreach($registrar_respuesta as $sol_clave){
                     
            
                     $_string .= '<tr>
                        <td class="datosbasicos2">'.$sol_clave->idTinfoFaltante['nom_tinfo_faltante'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->idTreporte['nom_treporte'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->idTatencion['nom_tatencion'].' </td>
                        <td class="datosbasicos2">'.$sol_clave->oficio_atencion.'</td>  
                        <td class="datosbasicos2">'.$sol_clave->fecha_atencion.'</td>
                        <td class="datosbasicos2">'.$sol_clave->idTrespuesta['nom_tatencion'].'</td>  
                        <td class="datosbasicos2">'.$sol_clave->oficio_respuesta.'</td> 
                        <td class="datosbasicos2">'.$sol_clave->fecha_respuesta.'</td>
                        <td class="datosbasicos2">'.$sol_clave->observaciones.' </td>
                        </tr>';
                     
                 }
                 
                $_string .= '</table>';
                $_string .= '</td></tr>';   
                 
                 
                //Datos tecnicos de la solicitud=========================================================================
                $_string .= '<tr><td colspan="4" class="cdatitulos">Datos Tecnicos de la solicitud</td></tr>';
                $_string .= '<tr><td colspan="4">';
                
                $_string .= '<table>';
                $_string .= '<tr>
                        <td class="datosbasicos1"> Código CDA </td>
                        <td class="datosbasicos1"> Latitud Solicitada </td>
                        <td class="datosbasicos1"> Longitud Solicitada </td>
                        <td class="datosbasicos1"> Altura solicitada </td>
                        <td class="datosbasicos1"> Pronvicia Solicitada </td>
                        <td class="datosbasicos1"> Cantón Solicitado </td>
                        <td class="datosbasicos1"> Parroquia solicitada </td>
                        <td class="datosbasicos1"> Sector Solicitado </td>
                        <td class="datosbasicos1"> Fuente Solicitada </td>
                        <td class="datosbasicos1"> Tipo Fuente Solicitada </td>
                        <td class="datosbasicos1"> Subtipo Fuente Solicitada </td>
                        <td class="datosbasicos1"> Caracteristica </td>
                        <td class="datosbasicos1"> q Solicitado l/s </td>
                        <td class="datosbasicos1"> Uso / aprovechamiento solicitado </td>
                        <td class="datosbasicos1"> Destino Solicitado </td>
                        <td class="datosbasicos1"> Tiempo (años) </td>
                     </tr>';
                
                foreach($datos_tecnicos as $sol_clave){
                     
            
                     $_string .= '<tr>
                        <td class="datosbasicos2">'.$sol_clave->idCda['id_cda'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->fdCoordenadas['latitud'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->fdCoordenadas['longitud'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->fdCoordenadas['altura'].'</td>
                        <td class="datosbasicos2">'.$this->nom_provincia($sol_clave->idUbicacion['cod_provincia']).'</td>
                        <td class="datosbasicos2">'.$this->nom_canton($sol_clave->idUbicacion['cod_canton'],$sol_clave->idUbicacion['cod_canton']).'</td>
                        <td class="datosbasicos2">'.$this->nom_parroquia($sol_clave->idUbicacion['cod_parroquia'], $sol_clave->idUbicacion['cod_canton'], $sol_clave->idUbicacion['cod_provincia']).'</td>
                        <td class="datosbasicos2">'.$sol_clave->sector_solicitado.'</td>
                        <td class="datosbasicos2">'.$sol_clave->fuente_solicitada.'</td>
                        <td class="datosbasicos2">'.$sol_clave->idTfuente['nom_tfuente'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->idSubtfuente['nom_subtfuente'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->idCaracteristica['nom_caracteristica'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->q_solicitado.'</td>
                        <td class="datosbasicos2">'.$sol_clave->idUsoSolicitado['nom_uso_solicitado'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->idDestino['nom_destino'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->tiempo_years.'</td>
                        </tr>';
                     
                 }
                 
                $_string .= '</table>';
                $_string .= '</td></tr>'; 
                
                
                //Datos de Senagua===================================================================================
                $_string .= '<tr><td colspan="4" class="cdatitulos">Datos de Senagua</td></tr>';
                $_string .= '<tr><td colspan="4">';
                
                $_string .= '<table width="100%">';
                $_string .= '<tr>
                        <td class="datosbasicos1"> Longitud SENAGUA </td>
                        <td class="datosbasicos1"> Latitud SENAGUA </td>
                        <td class="datosbasicos1"> Altura </td>
                        <td class="datosbasicos1"> Abscisa SENAGUA </td>
                        <td class="datosbasicos1"> Q SENAGUA (l/s) </td>
                        <td class="datosbasicos1"> Fuente SENAGUA </td>
                        <td class="datosbasicos1"> Uso/Aprovechamiento  </td>
                        <td class="datosbasicos1"> Destino SENAGUA </td>
                        </tr>';
                        
                foreach($datos_senagua as $sol_clave){
                     
            
                     $_string .= '<tr>
                        <td class="datosbasicos2">'.$sol_clave->fdCoordenadas['longitud'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->fdCoordenadas['latitud'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->fdCoordenadas['altura'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->abscisa.'</td>
                        <td class="datosbasicos2">'.$sol_clave->q_solicitado.'</td>
                        <td class="datosbasicos2">'.$sol_clave->fuente_solicitada.'</td>
                        <td class="datosbasicos2">'.$sol_clave->idUsoSolicitado['nom_uso_solicitado'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->idDestino['nom_destino'].'</td>
                        </tr>';
                     
                }

                $_string .= '</table>';
                $_string .= '</td></tr>';  
                
                //Datos de EPA===================================================================================
                $_string .= '<tr><td colspan="4" class="cdatitulos">Datos EPA</td></tr>';
                $_string .= '<tr><td colspan="4">';
                
                $_string .= '<table width="100%">';
                $_string .= '<tr>
                        <td class="datosbasicos1"> Longitud EPA </td>
                        <td class="datosbasicos1"> Latitud EPA </td>
                        <td class="datosbasicos1"> Altura EPA</td>
                        <td class="datosbasicos1"> Abscisa EPA </td>
                        <td class="datosbasicos1"> Q EPA (l/s) </td>
                        <td class="datosbasicos1"> Fuente EPA </td>
                        <td class="datosbasicos1"> Uso/Aprovechamiento </td>
                        <td class="datosbasicos1"> Destino EPA </td>
                        </tr>';
                        
                foreach($datos_epa as $sol_clave){
                     
            
                     $_string .= '<tr>
                       <td class="datosbasicos2">'.$sol_clave->fdCoordenadas['longitud'].'</td>
                       <td class="datosbasicos2">'.$sol_clave->fdCoordenadas['latitud'].'</td>
                       <td class="datosbasicos2">'.$sol_clave->fdCoordenadas['altura'].'</td>
                       <td class="datosbasicos2">'.$sol_clave->abscisa.'</td>
                       <td class="datosbasicos2">'.$sol_clave->q_solicitado.'</td>
                       <td class="datosbasicos2">'.$sol_clave->fuente_solicitada.'</td>
                       <td class="datosbasicos2">'.$sol_clave->idUsoSolicitado['nom_uso_solicitado'].'</td>
                       <td class="datosbasicos2">'.$sol_clave->idDestino['nom_destino'].'</td>
                    </tr>';
                     
                }

                $_string .= '</table>';
                $_string .= '</td></tr>';  
                
                
                //Errores Presentados=============================================================================
                
                $_string .= '<tr><td colspan="4" class="cdatitulos">Errores Presentados</td></tr>';
                $_string .= '<tr><td colspan="4">';
                
                $_string .= '<table width="100%">';
                $_string .= '<tr>
                        <td class="datosbasicos1"> Tipo de Errores </td>
                        <td class="datosbasicos1"> Observaciones </td>
                        </tr>';
                
                foreach($error_pres as $sol_clave){
                    
                     $_string .= '<tr>
                        <td class="datosbasicos2">'.$sol_clave->idTerror['nom_terror'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->observaciones.'</td>
                        </tr>';
                    
                }
                
                $_string .= '</table>';
                $_string .= '</td></tr>';  
                
                //Datos Visita=============================================================================
                
                $_string .= '<tr><td colspan="4" class="cdatitulos">Datos Visita</td></tr>';
                $_string .= '<tr><td colspan="4">';
                
                $_string .= '<table width="100%">';
                $_string .= '<tr>
                        <td class="datosbasicos1"> Longitud Observados </td>
                        <td class="datosbasicos1"> Latitud Observados </td>
                        <td class="datosbasicos1"> Altitud Observados </td>
                        </tr>';
                
                foreach($datos_visita as $sol_clave){
                    
                     $_string .= '<tr>
                        <td class="datosbasicos2">'.$sol_clave->fdCoordenadasvista['longitud'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->fdCoordenadasvista['latitud'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->fdCoordenadasvista['altura'].'</td>
                        </tr>';
                    
                }
                
                $_string .= '</table>';
                $_string .= '</td></tr>';  
                
                //Analisis Hidrologico=============================================================================
                
                //Anàlisis Hidrologico=============================================================================
                
                $_string .= '<tr><td colspan="4" class="cdatitulos">Análisis Hidrológico</td></tr>';
                $_string .= '<tr><td colspan="4">';
                
                $_string .= '<table width="100%">';
                $_string .= '<tr>
                        <td class="datosbasicos1"> Cartografía</td>
                        <td class="datosbasicos1"> Estacion Hidrológica base</td>
                        <td class="datosbasicos1"> Estacion Meterológica base </td>
                        <td class="datosbasicos1"> Metodología </td>
                        <td class="datosbasicos1"> Probabilidad de </td>
                        <td class="datosbasicos1"> Observación </td>
                        </tr>';
                
                
                foreach($analisis_hidrologico as $sol_clave){
                        
                        $_string .= '<tr>
                        <td class="datosbasicos2">'.$sol_clave->idCartografia['nom_cartografia'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->idEhidrografica['nom_ehidrografica'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->idEmeteorologica['nom_ehidrografica'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->idMetodologia['nom_emeteorologica'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->probabilidad.'</td>
                        <td class="datosbasicos2">'.$sol_clave->observacion.'</td>
                            </tr>';
                }
                
                $_string .= '</table>';
                $_string .= '</td></tr>';  
                
                
                //Registro CDA=============================================================================
                
                $_string .= '<tr><td colspan="4" class="cdatitulos">Registro CDA</td></tr>';
                $_string .= '<tr><td colspan="4">';
                
                $_string .= '<table width="100%">';
                $_string .= '<tr>
                        <td class="datosbasicos1"> Codigo CDA</td>
                        <td class="datosbasicos1"> Longitud solicitado </td>
                        <td class="datosbasicos1"> Latitud solicitado </td>
                        <td class="datosbasicos1"> Altura solicitado </td>
                        <td class="datosbasicos1"> Provincia solicitada </td>
                        <td class="datosbasicos1"> Canton solicitado </td>
                        <td class="datosbasicos1"> Parroquia solicitada </td>
                        <td class="datosbasicos1"> Sector solicitado </td>
                        <td class="datosbasicos1"> Fuente solicitada </td>
                        <td class="datosbasicos1"> Tipo Fuente solicitada </td>
                        <td class="datosbasicos1"> Subtipo Fuente solicitada </td>
                        <td class="datosbasicos1"> Carateristica </td>
                        <td class="datosbasicos1"> Q Solicitado l/s </td>
                        <td class="datosbasicos1"> Uso / aprovechamiento </td>
                        <td class="datosbasicos1"> Destino solicitado </td>
                        <td class="datosbasicos1"> Tiempo (años) </td>
                        <td class="datosbasicos1"> Decisión de la solicitud </td>
                        <td class="datosbasicos1"> Observaciones </td>
                        </tr>';
                
                foreach($registro_cda as $sol_clave){
                    
                     $_string .= '<tr>
                        <td class="datosbasicos2">'.$sol_clave->idCda['id_cda'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->fdCoordenadas['latitud'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->fdCoordenadas['longitud'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->fdCoordenadas['altura'].'</td>
                        <td class="datosbasicos2">'.$this->nom_provincia($sol_clave->idUbicacion['cod_provincia']).'</td>
                        <td class="datosbasicos2">'.$this->nom_canton($sol_clave->idUbicacion['cod_canton'],$sol_clave->idUbicacion['cod_canton']).'</td>
                        <td class="datosbasicos2">'.$this->nom_parroquia($sol_clave->idUbicacion['cod_parroquia'], $sol_clave->idUbicacion['cod_canton'], $sol_clave->idUbicacion['cod_provincia']).'</td>
                        <td class="datosbasicos2">'.$sol_clave->sector_solicitado.'</td>
                        <td class="datosbasicos2">'.$sol_clave->fuente_solicitada.'</td>
                        <td class="datosbasicos2">'.$sol_clave->idTfuente['nom_tfuente'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->idSubtfuente['nom_subtfuente'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->idCaracteristica['nom_caracteristica'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->q_solicitado.'</td>
                        <td class="datosbasicos2">'.$sol_clave->idUsoSolicitado['nom_uso_solicitado'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->idDestino['nom_destino'].'</td>
                        <td class="datosbasicos2">'.$sol_clave->observaciones.'</td>
                        </tr>';
                    
                }
                
                $_string .= '</table>';
                $_string .= '</td></tr>';  
                
                
                //=========================Numero de Puntos====================================================
                
                $_string .= '<tr><td colspan="4">';
                
                $_string .= '<table width="100%">';
                $_string .= '<tr>
                            <td class="datosbasicos1"> Numero puntos solicitados tramite: </td>
                            <td class="datosbasicos2">ND</td>';
                
                $_string .= '<td class="datosbasicos1">Numero puntos visita tecnica: </td>
                            <td class="datosbasicos2">ND</td>';
                
                
                $_string .= '<td class="datosbasicos1">Numero puntos verificados SENAGUA: </td>
                            <td class="datosbasicos2">ND</td>';
                
                $_string .= '<td class="datosbasicos1">Numero puntos certificados: </td>
                            <td class="datosbasicos2">ND</td>';
                
                $_string .= '<td class="datosbasicos1">Numero devueltos: </td>
                            <td class="datosbasicos2">ND</td>';
                
                $_string .= '</table>';
                $_string .= '</td></tr>';
                
                //Cierre tabla general=========================================================================    
                $_string .= '</table>';
            
            return $_string;
        }
        
    }
    
/*===================================================================================================================================*/
/*=========================================FUNCIONES PARA CREAR LAS CAJAS DE ENTRADA SEGUN TIPO DE PREGUNTA =========================*/
/******************************======================FUNCIONES PARA LAS CAJAS DE RESPUESTAS==================================********/
    
    /*Funcion para tipo_fecha*/
    public function tipo_1($_vdata,$_larray){
        
        /*Asignando Formato*/
        if(!empty($_vdata['format'])){

          $_formato = $_vdata['format'];
          $_formatphp = $this->formatear($_formato);

        }else{

          $_formato = 'dd/MM/yyyy';
          $_formatphp = 'd/m/Y';  
        }
        
        /*Revisando fecha maxima y fecha minima */
        if($_vdata['atributos']=="NOW" and $_vdata['max_date']=="1900-01-01" and $_vdata['min_date']!="1900-01-01"){
            $_vdata['max_date']=gmdate($_formatphp);
            $b_2='1';
        }else if($_vdata['atributos']=="NOW" and $_vdata['min_date']=="1900-01-01" and $_vdata['max_date']!="1900-01-01"){
            $_vdata['min_date']=gmdate($_formatphp);
            $b_1='1';
        }
        
         /*Transformado Fecha d/m/Y*/
        if(empty($b_2)){
            $date_fmt = date_create($_vdata['max_date']);
            $_vdata['max_date']= date_format($date_fmt, $_formatphp);
        }

        if(empty($b_1)){
            $date_fmt = date_create($_vdata['min_date']);
            $_vdata['min_date']= date_format($date_fmt, $_formatphp);
        }
        
        
        
        /*Averiguando si la pregunta es multiple o es sencilla ===========================================================*/
        if($_vdata['caracteristica_preg'] == 'M'){
            
            //=====================================Averiguando si existen respuestas asociadas a la pregunta tipo M========================//
            $_stringresponse = $this->getResponse('1', $_vdata['id_pregunta'], $_vdata['id_capitulo'], $this->id_fmt, $this->id_version, $_vdata['id_conjunto_pregunta'], $this->id_conj_rpta); 
            
            $_string4 ='<?php echo $form->field($model, "rpta'.$_larray.'")->'
                    . 'widget(yii\jui\DatePicker::className(),['
                    . '"dateFormat"=>"'.$_formato.'",'
                    . '"clientOptions"=>['
                    . '"maxDate" =>"'.$_vdata['max_date'].'",'
                    . '"minDate" =>"'.$_vdata['min_date'].'",'
                    . '"changeYear" => true,'            
                    . '"changeMonth" => true,';
            $_string4.= ']';
            $_string4.= ']); ';
            $_string4.= ' echo "'.$this->_condicion.'"; ?>';
            $_string4.= ' <div>';
                $_string4.= ' <div style="float:left">';
                $_string4.= ' <?= yii\helpers\Html::button("Adicionar", '
                            . '["onclick"=> "getRptaM'
                                         . '('.$this->id_conj_rpta.','.
                                             $_vdata['id_conjunto_pregunta'].','
                                             .$_vdata['id_pregunta'].','
                                             .'0,'
                                             .$this->id_fmt.','
                                             .$_vdata['id_tpregunta'].','
                                             .$this->id_version.','
                                             .$_vdata['id_capitulo'].','
                                             .$_larray.')" ]); ?>';
                $_string4.= ' </div>';
                $_string4.= ' <div style="float:right"  id="prueba'.$_larray.'">';
                    $_string4.= $_stringresponse;
                $_string4.= '</div>';
            $_string4.= ' </div>';
                        
                        
            
        }else{
            
            
            /*Creando caja para la vista*/
            $_string4='<?= $form->field($model, "rpta'.$_larray.'")->'
                    . 'widget(yii\jui\DatePicker::className(),['
                    . '"dateFormat"=>"'.$_formato.'",'
                    . '"clientOptions"=>['
                    . '"maxDate" =>"'.$_vdata['max_date'].'",'
                    . '"minDate" =>"'.$_vdata['min_date'].'",'
                    . '"changeYear" => true,'            
                    . '"changeMonth" => true,';
            $_string4.= '],';

            if(!empty($_vdata['operacion'])){           //Aplica para caso 2 la respuesta de la pregunta condicionada no puede varia la operacion dada en fd_elementocondicion de acuerdo al valor de la habilitadora

                $_idhab = $_vdata['id_pregunta_habilitadora'];       //Recogiendo id de  la pregunta habilitadora
                $_cond = $_larray;                                 //numera de la caja de respuesta de la pregunta condicionada
                $_hab = $this->_vrelaciones[$_idhab];               //numero de la caja de la respuesta habilitadora
                $_idtcond = $_vdata['id_tcondicion']; 
                $_valor = $_vdata['valor']; 

                $_string4.= '"options" =>[';
                $_string4.=' "onchange" => new \yii\web\JsExpression("setCondicion(\''.$_hab.'\',\''.$_cond.'\',\'1\',\''.$_vdata['operacion'].'\',\''.$_vdata['format'].'\',\''.$_idtcond.'\',\''.$_valor.'\',this)")';
                $_string4.= ']';
            }

            if(!empty($_vdata['opercond'])){          //Aplica para caso 1 la pregunta habilitadora permite contestar la pregunta condicionada

                $_idcond = $_vdata['id_pregunta_condicionada'];       //Recogiendo id de  la pregunta habilitadora

                //Se guarda el id de la pregunta condicionada en un vector para enviar una funciona javascript que retorna el larray para su uso
                //en el formulario esto se hace por que si la pregunta condicionada esta mas adelante de la habilitadora no se puede saber el numero
                //de la caja hasta tene todo el formulario listo para envio========================================================================
                $this->preguntascondiciones[] = $_idcond;
                $this->preguntashabilitadoras[] = $_vdata['id_pregunta'];

                $_hab = $_larray;
                $_idtcond = $_vdata['tcond']; 
                $_valor = $_vdata['valorcond']; 

                $_string4.= '"options" =>[';
                $_string4.=' "onchange" => new \yii\web\JsExpression("setCondicion(\''.$_hab.'\',\''.$_idcond.'\',\'2\',\''.$_vdata['opercond'].'\',\''.$_vdata['format'].'\',\''.$_idtcond.'\',\''.$_valor.'\',this)")';
                $_string4.= ']';

            }

            $_string4.= ']); ';

            /*Caja para escribir si la condicion es valida o no*/
            $_string4.= 'echo "'.$this->_condicion.'"; ?>';
        }
        
        return $_string4;
       
    }
    
    
    /*Funcion para tipo check
        Este campo tiene un modelo interno
     *  SI id_agrupacion == null => entonces se debe sacar una caja de checkbox unica
     *  Si id_agrupaicon != null y fd_tipo_agrupacion.id_t_agrupacion==1 muestra un radio button
     *  Si id_agrupacion != null y fd_tipo_agrupacion.id_t_agrupacion==2 muestra checkbox
     *      */
    public function tipo_2($_vdata,$_larray){
        
         /*Se habilita boton de eleminar si el usuario tiene permisos de editar y su preg_caracteristica = m*/
        $_eliminar="";
        
        if($_vdata['caracteristica_preg'] == 'M' and $this->_pactualizar == 'S'){
            
            /*Se consulta si existe respuesta relacionada conla pregunta
             * si existe se envia mensaje en la ventana de confirmacion con "Existe una respuesta asociada desea continuar"
             * Si no se envia mensaje desea eliminar el registro
             */
            
            if(!empty($_vdata['id_respuesta'])){
                $_msjpreg = "Existe una respuesta asociada, desea continuar..";
                $_idrpta = $_vdata['id_respuesta'];
            }else{
                $_msjpreg = "Desea eliminar el registro..";
                $_idrpta="";
            }
            
           /*Llama a delete_M se deben enviar las siguiente variables
           * $_tipopregunta,$id_seccion,$id_agrupacion,$id_capitulo,$id_conj_prta,$id_conj_rpta,$id_fmt,$id_version,$antvista,$estado                 */
            $_urlm='yii\helpers\Url::toRoute(["fdpregunta/deletem","id"=>'.$_vdata["id_pregunta"].',"id_rpta"=>"'.$_idrpta.'","id_capitulo"=>'.$_vdata["id_capitulo"].',"id_conj_prta"=>'.$_vdata["id_conjunto_pregunta"].',"id_conj_rpta"=>'.$this->id_conj_rpta.',"id_fmt"=>'.$this->id_fmt.',"id_version"=>'.$this->id_version.',"antvista"=>"'.$this->antvista.'","estado"=>'.$this->estado.',"parroquias"=>"'.$this->parroquias.'","cantones"=>"'.$this->cantones.'","provincia"=>"'.$this->provincia.'","periodos"=>"'.$this->periodos.'","capituloid"=>"'.$this->capituloid.'"],true)';     
            $_eliminar=' yii\helpers\Html::a("-","",'
                        .'["class"=>"btn btn-info","title" => Yii::t("app", "Delete"),'
                        .'"data-confirm" => Yii::t("yii",\''.$_msjpreg.'::\'.'.$_urlm.'),'
                        . '"data-method" => "post"]) ';
                
        }
        
        
        if(empty($_vdata['id_agrupacion'])){
            
            $_string4='<?= $form->field($model, "rpta'.$_larray.'")->checkbox(); ';
            $_string4.= 'echo "'.$this->_condicion.'"; ?>';
        
        }else if(!empty($_vdata['id_agrupacion']) and $_vdata['tipo_agrupacion'] == '1'){
          
          $_indiceagrupadas=$_vdata['id_agrupacion'];
          $_idpregunta = $_vdata['id_pregunta'];
        
                   
          /*Se crea la cajita para raddio button*/ 
         
          $_string4='<?php echo "<table><tr><td>".$form->field($model, "rpta'.$_larray.'")->radio(["label" => "'.$_vdata['nom_pregunta'].'","value"=>"'.$_vdata['id_pregunta'].'","uncheck"=>null])."</td>"; ';
          $_string4.=(!empty($_eliminar))? ' echo "<td>".'.$_eliminar.'."</td>" ;':'';
          $_string4.=' echo "<td>'.$this->_condicion.'</td></tr></table>"; ';
          
          /*Guardando el id de la pregunta con agrupacion*/
          $this->agrupadas[$_idpregunta] =  $_indiceagrupadas;  //Grupo para guardar======================
          
          $_string4.='?>';
          
        }else if(!empty($_vdata['id_agrupacion']) and $_vdata['tipo_agrupacion'] == '2'){
           
            $_string4='<?= $form->field($model, "rpta'.$_larray.'")->checkbox(["label" => "'.$_vdata['nom_pregunta'].'"]); ';
            $_string4.= 'echo "'.$this->_condicion.'"; ?>';
            
        }
       
       
       
        return $_string4;
        
    }
    
    
    
    
    /*Funcion para tipo SELECT ONE
     * El combobox se llena con los datos obtenidos de FD_OPCION.SELECT.ID_TSELECT=FD_PREGUNTA.ID_TSELECT
     */
    public function tipo_3($_vdata,$_larray){
        
        $id_tselect=$_vdata['id_tselect'];
        
        
        
        $_string4 = '<?php echo $form->field($model, "rpta'.$_larray.'")->dropDownList('
                . 'yii\helpers\ArrayHelper::map(common\models\poc\FdOpcionSelect::find()->where("id_tselect='.$id_tselect.'")->all(),'
                . '"id_opcion_select","nom_opcion_select"),'
                . '["prompt"=>"Seleccione"';

        if(!empty($_vdata['operacion'])){
            
            $_idhab = $_vdata['id_pregunta_habilitadora'];       //Recogiendo id de  la pregunta habilitadora
            $_hab = $this->_vrelaciones[$_idhab];
            $_idcond = $_larray;
            $_idtcond = $_vdata['id_tcondicion']; 
            $_valor = $_vdata['valor']; 
            
            $_string4.=', "onchange" => new \yii\web\JsExpression("setCondicion(\''.$_hab.'\',\''.$_idcond.'\',\'1\',\''.$_vdata['operacion'].'\',\''.$_vdata['format'].'\',\''.$_idtcond.'\',\''.$_valor.'\',this)")'; 
            
        } 
        
        if(!empty($_vdata['opercond'])){          //Aplica para caso 1 la pregunta habilitadora permite contestar la pregunta condicionada
            
            $_idcond = $_vdata['id_pregunta_condicionada'];       //Recogiendo id de  la pregunta habilitadora
            
            //Se guarda el id de la pregunta condicionada en un vector para enviar una funciona javascript que retorna el larray para su uso
            //en el formulario esto se hace por que si la pregunta condicionada esta mas adelante de la habilitadora no se puede saber el numero
            //de la caja hasta tene todo el formulario listo para envio========================================================================
            $this->preguntascondiciones[] = $_idcond;
            $this->preguntashabilitadoras[] = $_vdata['id_pregunta'];
            
            $_hab = $_larray;
            $_idtcond = $_vdata['tcond']; 
            $_valor = $_vdata['valorcond']; 
            
            $_string4.=', "onchange" => new \yii\web\JsExpression("setCondicion(\''.$_hab.'\',\''.$_idcond.'\',\'2\',\''.$_vdata['opercond'].'\',\''.$_vdata['format'].'\',\''.$_idtcond.'\',\''.$_valor.'\',this)")';
            
        }
                
        $_string4 .= ']); ';
        $_string4.= 'echo "'.$this->_condicion.'"; ?>';
        
        /*Averiguando si la pregunta es multiple o es sencilla ===========================================================*/
        if($_vdata['caracteristica_preg'] == 'M'){
            
             //=====================================Averiguando si existen respuestas asociadas a la pregunta tipo M========================//
            $_stringresponse = $this->getResponse('3', $_vdata['id_pregunta'], $_vdata['id_capitulo'], $this->id_fmt, $this->id_version, $_vdata['id_conjunto_pregunta'], $this->id_conj_rpta); 
            
            
             /*Caja para escribir si la condicion es valida o no*/
            $_string4.= '<?= "'.$this->_condicion.'"; ?>';
            
             $_string4.= ' <div>';
                $_string4.= ' <div style="float:left">';
                $_string4.= ' <?= yii\helpers\Html::button("Adicionar", '
                            . '["onclick"=> "getRptaM'
                                         . '('.$this->id_conj_rpta.','.
                                             $_vdata['id_conjunto_pregunta'].','
                                             .$_vdata['id_pregunta'].','
                                             .'0,'
                                             .$this->id_fmt.','
                                             .$_vdata['id_tpregunta'].','
                                             .$this->id_version.','
                                             .$_vdata['id_capitulo'].','
                                             .$_larray.')" ]); ?>';
                $_string4.= ' </div>';
                 $_string4.= ' <div style="float:right"  id="prueba'.$_larray.'">';
                    $_string4.= $_stringresponse;
                $_string4.= '</div>';
            $_string4.= ' </div>';
        }
        
       
        return $_string4;
    }
    
    
    /*Funcion para tipo DESCRIPCION
        Ejemplo de lo que debe entregar la funcion: <?= $form->field($model, "rpta1")->input("text") ?> 
     *   
     *      */
    public function tipo_4($_vdata,$_larray){
        
        if(!empty($_vdata['reg_exp'])){
            
            $_string4='<?= $form->field($model, "rpta'.$_larray.'")->input("text"); ';
            
        }else{
            
            $_string4='<?= $form->field($model, "rpta'.$_larray.'")->widget(\yii\redactor\widgets\Redactor::className(), [ 
                      "clientOptions" => [ 
                      "lang" => "es", 
                      "buttons" => ["format", "bold", "italic","underline", "ol", "ul", "indent", "outdent"],
                      "plugins" => ["clips", "fontcolor","imagemanager"] 
                      ] 
                      ]); ';
        }
        
        /*Caja para escribir si la condicion es valida o no*/
        $_string4.= 'echo "'.$this->_condicion.'"; ?>';
        return $_string4;
    }
    
    
    
    
    /*Funcion para tipo ENTERO*/
    public function tipo_5($_vdata,$_larray){
        
        
         /*Averiguando si la pregunta es multiple o es sencilla ===========================================================*/
        if($_vdata['caracteristica_preg'] == 'M'){
            
            //=====================================Averiguando si existen respuestas asociadas a la pregunta tipo M========================//
            $_stringresponse = $this->getResponse('5', $_vdata['id_pregunta'], $_vdata['id_capitulo'], $this->id_fmt, $this->id_version, $_vdata['id_conjunto_pregunta'], $this->id_conj_rpta); 
            
            if(empty($_vdata['format'])){
                $_string4='<?= $form->field($model, "rpta'.$_larray.'")->input("text")->label(false); ';
            }else{
                $formato=str_replace("#","9",$_vdata['format']);
                $_string4='<?= $form->field($model,  "rpta'.$_larray.'")->widget(\yii\widgets\MaskedInput::className(), [
                "mask" => "'.$formato.'",])->label(false); ';
            }
            
             /*Caja para escribir si la condicion es valida o no*/
            $_string4.= 'echo "'.$this->_condicion.'"; ?>';
            
             $_string4.= ' <div>';
                $_string4.= ' <div style="float:left">';
                $_string4.= ' <?= yii\helpers\Html::button("Adicionar", '
                            . '["onclick"=> "getRptaM'
                                         . '('.$this->id_conj_rpta.','.
                                             $_vdata['id_conjunto_pregunta'].','
                                             .$_vdata['id_pregunta'].','
                                             .'0,'
                                             .$this->id_fmt.','
                                             .$_vdata['id_tpregunta'].','
                                             .$this->id_version.','
                                             .$_vdata['id_capitulo'].','
                                             .$_larray.')" ]); ?>';
                $_string4.= ' </div>';
                 $_string4.= ' <div style="float:right"  id="prueba'.$_larray.'">';
                    $_string4.= $_stringresponse;
                $_string4.= '</div>';
            $_string4.= ' </div>';
            
            
        }else{
        
        
            if(empty($_vdata['format'])){
                $_string4='<?= $form->field($model, "rpta'.$_larray.'")->input("text")->label(false); ';
            }else{
                $formato=str_replace("#","9",$_vdata['format']);
                $_string4='<?= $form->field($model,  "rpta'.$_larray.'")->widget(\yii\widgets\MaskedInput::className(), [
                "mask" => "'.$formato.'",])->label(false); ';
            }
            
             /*Caja para escribir si la condicion es valida o no*/
            $_string4.= 'echo "'.$this->_condicion.'"; ?>';
            
        }   
       
        return $_string4;
    }
    
    
    /*Funcion para tipo Decimal*/
    public function tipo_6($_vdata,$_larray){
        
        
        /*Averiguando si la pregunta es multiple o es sencilla ===========================================================*/
        if($_vdata['caracteristica_preg'] == 'M'){
            
            //=====================================Averiguando si existen respuestas asociadas a la pregunta tipo M========================//
            $_stringresponse = $this->getResponse('6', $_vdata['id_pregunta'], $_vdata['id_capitulo'], $this->id_fmt, $this->id_version, $_vdata['id_conjunto_pregunta'], $this->id_conj_rpta); 
            
            
            if(empty($_vdata['format'])){
                $_string4='<?= $form->field($model, "rpta'.$_larray.'")->input("text")->label(false); ';
            }else{
                $formato=str_replace("#","9",$_vdata['format']);
                $_string4='<?= $form->field($model,  "rpta'.$_larray.'")->widget(\yii\widgets\MaskedInput::className(), [
                "mask" => "'.$formato.'",])->label(false); ';
            }
            
             /*Caja para escribir si la condicion es valida o no*/
            $_string4.= 'echo "'.$this->_condicion.'"; ?>';
            
             $_string4.= ' <div>';
                $_string4.= ' <div style="float:left">';
                $_string4.= ' <?= yii\helpers\Html::button("Adicionar", '
                            . '["onclick"=> "getRptaM'
                                         . '('.$this->id_conj_rpta.','.
                                             $_vdata['id_conjunto_pregunta'].','
                                             .$_vdata['id_pregunta'].','
                                             .'0,'
                                             .$this->id_fmt.','
                                             .$_vdata['id_tpregunta'].','
                                             .$this->id_version.','
                                             .$_vdata['id_capitulo'].','
                                             .$_larray.')" ]); ?>';
                $_string4.= ' </div>';
                 $_string4.= ' <div style="float:right"  id="prueba'.$_larray.'">';
                    $_string4.= $_stringresponse;
                $_string4.= '</div>';
            $_string4.= ' </div>';
            
        }else{
        
            if(empty($_vdata['format'])){
                $_string4='<?= $form->field($model, "rpta'.$_larray.'")->input("text")->label(false); ';
            }else{
                $formato=str_replace("#","9",$_vdata['format']);
                $_string4='<?= $form->field($model,  "rpta'.$_larray.'")->widget(\yii\widgets\MaskedInput::className(), [
                "mask" => "'.$formato.'",])->label(false); ';
            }
            
             /*Caja para escribir si la condicion es valida o no*/
            $_string4.= 'echo "'.$this->_condicion.'"; ?>';
            
        }    
        
       
        return $_string4;
    }
    
    
    /*Funcion para tipo Porcentaje*/
    public function tipo_7($_vdata,$_larray){
        
        /*Averiguando si la pregunta es multiple o es sencilla ===========================================================*/
        if($_vdata['caracteristica_preg'] == 'M'){
            
                   
            //=====================================Averiguando si existen respuestas asociadas a la pregunta tipo M========================//
            $_stringresponse = $this->getResponse('7', $_vdata['id_pregunta'], $_vdata['id_capitulo'], $this->id_fmt, $this->id_version, $_vdata['id_conjunto_pregunta'], $this->id_conj_rpta); 
     
            
            if(empty($_vdata['format'])){
                $_string4='<?= $form->field($model, "rpta'.$_larray.'")->input("text")->label(false); ';
            }else{
                $formato=str_replace("#","9",$_vdata['format']);
                $_string4='<?= $form->field($model,  "rpta'.$_larray.'")->widget(\yii\widgets\MaskedInput::className(), [
                "mask" => "'.$formato.'",])->label(false); ';
            }

            /*Caja para escribir si la condicion es valida o no*/
            $_string4.= 'echo "'.$this->_condicion.'"; ?>';
            
            
             $_string4.= ' <div>';
                $_string4.= ' <div style="float:left">';
                $_string4.= ' <?= yii\helpers\Html::button("Adicionar", '
                            . '["onclick"=> "getRptaM'
                                         . '('.$this->id_conj_rpta.','.
                                             $_vdata['id_conjunto_pregunta'].','
                                             .$_vdata['id_pregunta'].','
                                             .'0,'
                                             .$this->id_fmt.','
                                             .$_vdata['id_tpregunta'].','
                                             .$this->id_version.','
                                             .$_vdata['id_capitulo'].','
                                             .$_larray.')" ]); ?>';
                $_string4.= ' </div>';
                 $_string4.= ' <div style="float:right"  id="prueba'.$_larray.'">';
                    $_string4.= $_stringresponse;
                $_string4.= '</div>';
            $_string4.= ' </div>';
        
        }else{
        
            if(empty($_vdata['format'])){
                $_string4='<?= $form->field($model, "rpta'.$_larray.'")->input("text")->label(false); ';
            }else{
                $formato=str_replace("#","9",$_vdata['format']);
                $_string4='<?= $form->field($model,  "rpta'.$_larray.'")->widget(\yii\widgets\MaskedInput::className(), [
                "mask" => "'.$formato.'",])->label(false); ';
            }

            /*Caja para escribir si la condicion es valida o no*/
            $_string4.= 'echo "'.$this->_condicion.'"; ?>';
            
        }
        
        return $_string4;
    }
    
    
    /*Funcion para tipo Moneda
    */
    
    public function tipo_8($_vdata,$_larray){
        
        /*Averiguando si la pregunta es multiple o es sencilla ===========================================================*/
        if($_vdata['caracteristica_preg'] == 'M'){
            
              //=====================================Averiguando si existen respuestas asociadas a la pregunta tipo M========================//
              $_stringresponse = $this->getResponse('8', $_vdata['id_pregunta'], $_vdata['id_capitulo'], $this->id_fmt, $this->id_version, $_vdata['id_conjunto_pregunta'], $this->id_conj_rpta); 
             
              $_string4='<?= $form->field($model, "rpta'.$_larray.'")->widget(\yii\widgets\MaskedInput::className(), ['
                . '"clientOptions" => ['
                . '"alias" => "decimal",'
                . '"groupSeparator" => ",",'
                . '"autoGroup" => true,'
                . '"removeMaskOnSubmit" => true'
                .']'
                .']); ';
        
                /*Caja para escribir si la condicion es valida o no*/
                $_string4.= 'echo "'.$this->_condicion.'"; ?>';
                
                $_string4.= ' <div>';
                $_string4.= ' <div style="float:left">';
                $_string4.= ' <?= yii\helpers\Html::button("Adicionar", '
                            . '["onclick"=> "getRptaM'
                                         . '('.$this->id_conj_rpta.','.
                                             $_vdata['id_conjunto_pregunta'].','
                                             .$_vdata['id_pregunta'].','
                                             .'0,'
                                             .$this->id_fmt.','
                                             .$_vdata['id_tpregunta'].','
                                             .$this->id_version.','
                                             .$_vdata['id_capitulo'].','
                                             .$_larray.')" ]); ?>';
                $_string4.= ' </div>';
                 $_string4.= ' <div style="float:right"  id="prueba'.$_larray.'">';
                    $_string4.= $_stringresponse;
                $_string4.= '</div>';
                $_string4.= ' </div>';
                
        }else{
        
        
            $_string4='<?= $form->field($model, "rpta'.$_larray.'")->widget(\yii\widgets\MaskedInput::className(), ['
                    . '"clientOptions" => ['
                    . '"alias" => "decimal",'
                    . '"groupSeparator" => ",",'
                    . '"autoGroup" => true,'
                    . '"removeMaskOnSubmit" => true'
                    .']'
                    .']); ';

            /*Caja para escribir si la condicion es valida o no*/
            $_string4.= 'echo "'.$this->_condicion.'"; ?>';
            
        }
        
        return $_string4;
    }
    
    
    /*Funcion para Detalle Mensual Decimal
     * Esta pantalla tiene una variacion debe
     * enviar: formato, version, estado, conjunto_respuesta, pregunta, respuesta
    */
    public function tipo_9($_vdata,$_larray){
        
       if($this->_estnumerado == 'S' and !empty($this->_numseccion)){
            $_numeracion = $this->_numcapitulo.".".$this->_numseccion.".".$this->_numpregunta;
        }else{
            $_numeracion = "";
        } 
        
        if( $this->_pactualizar == 'S'){
           $_titulo = "Diligenciar";
        }else{
           $_titulo = "Ver";
        }
        
        $_string4 = '<?= yii\helpers\Html::submitButton("'.$_titulo.'", '
                 . '["class"=>"btn btn-default btn-xs btn-primary","id"=>"detcapitulo-rpta'.$_larray.'","value"=>"tp_detallemensual%%'.$_vdata["tp_url_subpantalla"].'%%'.$_vdata["id_pregunta"].'%%'.$_vdata["id_respuesta"].'%%'.$_numeracion.'%%'.$_vdata["nom_pregunta"].'%%'.$this->vactual.'%%'.$_vdata['id_tselect'].'%%'.$_vdata['id_capitulo'].'%%'.$_larray.'","name"=>"subpantalla","onclick"=>"clicked=\'subpantalla\'"]); ';
       
        
       /* $_string4 = '<?= yii\helpers\Html::a(Yii::t("app","'.$_titulo.'"), 
                    ["'.$_vdata["tp_url_subpantalla"].'","id_fmt"=>'.$this->id_fmt.',"id_version"=>'.$this->id_version.',"id_cnj_rpta"=>'.$this->id_conj_rpta.',"id_capitulo"=>'.$_vdata["id_capitulo"].',"id_conj_prta"=>'.$_vdata["id_conjunto_pregunta"].',"id_prta"=>'.$_vdata["id_pregunta"].',"id_rpta"=>"'.$_vdata["id_respuesta"].'","numerar"=>"'.$_numeracion.'","nom_prta"=>"'.$_vdata["nom_pregunta"].'","migadepan"=>"'.$this->vactual.'","estado"=>"'.$this->estado.'","capitulo"=>"'.$this->capituloid.'","provincia"=>"'.$this->provincia.'","cantones"=>"'.$this->cantones.'","parroquias"=>"'.$this->parroquias.'","periodos"=>"'.$this->periodos.'","antvista"=>"'.$this->antvista.'","tipo_select"=>"'.$_vdata['id_tselect'].'"],
                    ["class" => "btn btn-default btn-xs "]); ';*/
        
        /*Caja para escribir si la condicion es valida o no*/
        $_string4.= 'echo "'.$this->_condicion.'"; ?>';

        return $_string4;
    }
    
    
    
    /*Funcion para tipo Boton
     *FALTA ASIGNAR A DONDE VA EL BOTOOOON? */
    
    public function tipo_10($_vdata,$_larray){
        
        
        $_string4 = '<?php echo yii\helpers\Html::button("'.$_vdata["nom_pregunta"].'", 
                    ["value"=>yii\helpers\Url::toRoute(["detcapitulo/ejec_comand","id_prta"=>'.$_vdata["id_pregunta"].',"id_conj_prta"=>'.$_vdata["id_conjunto_pregunta"].'],true),
                     "class" => "btn btn-default btn-xs showModalButton"
                    ]); ';
        
       /*Caja para escribir si la condicion es valida o no*/
        $_string4.= 'echo "'.$this->_condicion.'"; ?>';
        
       
        return $_string4;
    }
    
    /*Funcion para tipo SOPORTE
     * Se  asigna input para recibir archivo
     *  
     */
    public function tipo_11($_vdata,$_larray){
        
        //Recoge los nombres de los iput que son tipo archivo ejemplo rpta0,rpta1
        $this->_tiposoporte[] = "rpta".$_larray;        
        
        $_string4 = '<?= $form->field($model, "rpta'.$_larray.'[]")->fileInput(["multiple" => true, "accept" => "file_extension|image/*"])->label(false); ' ;
        
        
        //Averigua si existen archivos para presentarlos ====================//
        if(!empty($_vdata['id_respuesta'])){
            
            $sop_idrespuesta = $_vdata['id_respuesta'];
            $_soportes = Yii::$app->db->createCommand('SELECT ruta_soporte,titulo_soporte FROM sop_soportes WHERE id_respuesta='.$sop_idrespuesta)->queryAll();
           
            foreach($_soportes as $_clave11){
                
               $_string4.= 'echo "<li>'.$_clave11['titulo_soporte'].'</li>" ;';
                
            }
            
        }
        
        /*Caja para escribir si la condicion es valida o no*/
        $_string4.= 'echo "'.$this->_condicion.'"; ?>';
        
  
        return $_string4;
       
    }
    
     /*Funcion para tipo COORDENADAS
     * Se  programa el enlace a tp_url_subpantalla en modo showModalButton
     */
    public function tipo_12($_vdata,$_larray){
        
       if($this->_estnumerado == 'S' and !empty($this->_numseccion)){
            $_numeracion = $this->_numcapitulo.".".$this->_numseccion.".".$this->_numpregunta;
        }else{
            $_numeracion = "";
        } 
        
        if( $this->_pactualizar == 'S'){
           $_titulo = "Diligenciar";
        }else{
           $_titulo = "Ver";
        } 
        
        $_string4 = '<?= yii\helpers\Html::submitButton("'.$_titulo.'", '
                 . '["class"=>"btn btn-default btn-xs btn-primary","id"=>"detcapitulo-rpta'.$_larray.'","value"=>"tp_coordenadas%%'.$_vdata["tp_url_subpantalla"].'%%'.$_vdata["id_pregunta"].'%%'.$_vdata["id_respuesta"].'%%'.$_numeracion.'%%'.$_vdata["nom_pregunta"].'%%'.$this->vactual.'%%'.$_vdata['id_capitulo'].'%%'.$_larray.'","name"=>"subpantalla","onclick"=>"clicked=\'subpantalla\'"]); ';
        
  //      $_string4 = '<?= yii\helpers\Html::a(Yii::t("app","'.$_titulo.'"), 
    //                ["'.$_vdata["tp_url_subpantalla"].'","id_fmt"=>'.$this->id_fmt.',
    //                "id_version"=>'.$this->id_version.',"id_cnj_rpta"=>'.$this->id_conj_rpta.',
    //                "id_capitulo"=>'.$_vdata["id_capitulo"].',"id_conj_prta"=>'.$_vdata["id_conjunto_pregunta"].',
    //                "id_prta"=>'.$_vdata["id_pregunta"].',"id_rpta"=>"'.$_vdata["id_respuesta"].'","numerar"=>"'.$_numeracion.'","nom_prta"=>"'.$_vdata["nom_pregunta"].'","migadepan"=>"'.$this->vactual.'","estado"=>"'.$this->estado.'","capitulo"=>"'.$this->capituloid.'","provincia"=>"'.$this->provincia.'","cantones"=>"'.$this->cantones.'","parroquias"=>"'.$this->parroquias.'","periodos"=>"'.$this->periodos.'","antvista"=>"'.$this->antvista.'"],
      //              ["class" => "btn btn-default btn-xs "]); ';
        
        /*Caja para escribir si la condicion es valida o no*/
        $_string4.= 'echo "'.$this->_condicion.'"; ?>';
       
        return $_string4;
    }
    
    
    
     /*Funcion para tipo INVOLUCRADOS*/
    public function tipo_13($_vdata,$_larray){
        
        if($this->_estnumerado == 'S' and !empty($this->_numseccion)){
            $_numeracion = $this->_numcapitulo.".".$this->_numseccion.".".$this->_numpregunta;
        }else{
            $_numeracion = "";
        } 
        
        if( $this->_pactualizar == 'S'){
           $_titulo = "Diligenciar";
        }else{
           $_titulo = "Ver";
        } 
        
        $_string4 = '<?= yii\helpers\Html::submitButton("'.$_titulo.'", '
                 . '["class"=>"btn btn-default btn-xs btn-primary","id"=>"detcapitulo-rpta'.$_larray.'","value"=>"tp_involucrados%%'.$_vdata["tp_url_subpantalla"].'%%'.$_vdata["id_pregunta"].'%%'.$_vdata["id_respuesta"].'%%'.$_numeracion.'%%'.$_vdata["nom_pregunta"].'%%'.$this->vactual.'%%'.$_vdata['id_capitulo'].'%%'.$_larray.'","name"=>"subpantalla","onclick"=>"clicked=\'subpantalla\'"]); ';
       
        
       /* $_string4 = '<?= yii\helpers\Html::a(Yii::t("app","'.$_titulo.'"), 
                    ["'.$_vdata["tp_url_subpantalla"].'","id_fmt"=>'.$this->id_fmt.',"id_version"=>'.$this->id_version.',"id_cnj_rpta"=>'.$this->id_conj_rpta.',"id_capitulo"=>'.$_vdata["id_capitulo"].',"id_conj_prta"=>'.$_vdata["id_conjunto_pregunta"].',"id_prta"=>'.$_vdata["id_pregunta"].',"id_rpta"=>"'.$_vdata["id_respuesta"].'","numerar"=>"'.$_numeracion.'","nom_prta"=>"'.$_vdata["nom_pregunta"].'","migadepan"=>"'.$this->vactual.'","estado"=>"'.$this->estado.'","capitulo"=>"'.$this->capituloid.'","provincia"=>"'.$this->provincia.'","cantones"=>"'.$this->cantones.'","parroquias"=>"'.$this->parroquias.'","periodos"=>"'.$this->periodos.'","antvista"=>"'.$this->antvista.'"],
                    ["class" => "btn btn-default btn-xs "]); ';*/
        
        /*Caja para escribir si la condicion es valida o no*/
        $_string4.= 'echo "'.$this->_condicion.'"; ?>';
       
        return $_string4;
    }
    
    /*Funcion para tipo UBICACION
     * Se retira la ventana modal = showModalButton     */
     public function tipo_14($_vdata,$_larray){
         
        if($this->_estnumerado == 'S' and !empty($this->_numseccion)){
            $_numeracion = $this->_numcapitulo.".".$this->_numseccion.".".$this->_numpregunta;
        }else{
            $_numeracion = "";
        } 
        
        if( $this->_pactualizar == 'S'){
           $_titulo = "Diligenciar";
        }else{
           $_titulo = "Ver";
        } 
        
        $_string4 = '<?= yii\helpers\Html::submitButton("'.$_titulo.'", '
                 . '["class"=>"btn btn-default btn-xs btn-primary","id"=>"detcapitulo-rpta'.$_larray.'","value"=>"tp_ubicacion%%'.$_vdata["tp_url_subpantalla"].'%%'.$_vdata["id_pregunta"].'%%'.$_vdata["id_respuesta"].'%%'.$_numeracion.'%%'.$_vdata["nom_pregunta"].'%%'.$this->vactual.'%%'.$_vdata['id_capitulo'].'%%'.$_larray.'","name"=>"subpantalla","onclick"=>"clicked=\'subpantalla\'"]); ';
       
        
        /*$_string4 = '<?= yii\helpers\Html::a(Yii::t("app","'.$_titulo.'"), 
                    ["'.$_vdata["tp_url_subpantalla"].'","id_fmt"=>'.$this->id_fmt.',"id_version"=>'.$this->id_version.',"id_cnj_rpta"=>'.$this->id_conj_rpta.',"id_capitulo"=>'.$_vdata["id_capitulo"].',"id_conj_prta"=>'.$_vdata["id_conjunto_pregunta"].',"id_prta"=>'.$_vdata["id_pregunta"].',"id_rpta"=>"'.$_vdata["id_respuesta"].'","numerar"=>"'.$_numeracion.'","nom_prta"=>"'.$_vdata["nom_pregunta"].'","migadepan"=>"'.$this->vactual.'","estado"=>"'.$this->estado.'","capitulo"=>"'.$this->capituloid.'","provincia"=>"'.$this->provincia.'","cantones"=>"'.$this->cantones.'","parroquias"=>"'.$this->parroquias.'","periodos"=>"'.$this->periodos.'","antvista"=>"'.$this->antvista.'"],
                    ["class" => "btn btn-default btn-xs "]); ';*/
        
        /*Caja para escribir si la condicion es valida o no*/
        $_string4.= 'echo "'.$this->_condicion.'"; ?>';
       
        return $_string4;
    }
    
    
     /*Funcion para tipo TEXTO
        Ejemplo de lo que debe entregar la funcion: <?= $form->field($model, "rpta1")->input("text") ?> 
     *   
     *      */
    public function tipo_15($_vdata,$_larray){
        
        /*Averiguando si la pregunta es multiple o es sencilla ===========================================================*/
        if($_vdata['caracteristica_preg'] == 'M'){
            
            //=====================================Averiguando si existen respuestas asociadas a la pregunta tipo M========================//
            $_stringresponse = $this->getResponse('15', $_vdata['id_pregunta'], $_vdata['id_capitulo'], $this->id_fmt, $this->id_version, $_vdata['id_conjunto_pregunta'], $this->id_conj_rpta); 
            
            $_string4='<?= $form->field($model, "rpta'.$_larray.'")->input("text"); ';
            
            /*Caja para escribir si la condicion es valida o no*/
            $_string4.= 'echo "'.$this->_condicion.'"; ?>';
            
             $_string4.= ' <div>';
                $_string4.= ' <div style="float:left">';
                $_string4.= ' <?= yii\helpers\Html::button("Adicionar", '
                            . '["onclick"=> "getRptaM'
                                         . '('.$this->id_conj_rpta.','.
                                             $_vdata['id_conjunto_pregunta'].','
                                             .$_vdata['id_pregunta'].','
                                             .'0,'
                                             .$this->id_fmt.','
                                             .$_vdata['id_tpregunta'].','
                                             .$this->id_version.','
                                             .$_vdata['id_capitulo'].','
                                             .$_larray.')" ]); ?>';
                $_string4.= ' </div>';
                 $_string4.= ' <div style="float:right"  id="prueba'.$_larray.'">';
                    $_string4.= $_stringresponse;
                $_string4.= '</div>';
            $_string4.= ' </div>';
            
            
        }else{
            
            $_string4='<?= $form->field($model, "rpta'.$_larray.'")->input("text"); ';
            
            /*Caja para escribir si la condicion es valida o no*/
            $_string4.= 'echo "'.$this->_condicion.'"; ?>';
        }
            
       
        return $_string4;
    }
    
    
    
    
/*=====================================================FUNCIONES PARA TIPO DE FORMATO HTML=======================================*/
/*===============================================================================================================================*/    
    
    /*Funcion para presentacion de texto segun tipo de pregunta
     * se presenta solo el contenido de la respuesta aplica para tipos
     * 1,3,4
     */
    
    public function tipohtmltexto($model,$_larray,$vectordata){
        
        $_htmlrespuesta="";
        
        if($vectordata["id_tpregunta"] == '3'){
            
            $_namevalue= FdOpcionSelect::find()->where(['id_tselect' => $vectordata["id_tselect"],'id_opcion_select' => $model->{"rpta".$_larray} ])->asArray()->all();
            
            foreach($_namevalue as $_textocl){
                
                $_htmlrespuesta = $_textocl["nom_opcion_select"];
                
            }
            
         
        }else{

            $_htmlrespuesta = $model->{"rpta".$_larray};

        }    
        return $_htmlrespuesta;
    }
    
    
    /*Funcion para tipo check sin agrupacion*/
    public function tipohtmlcheck1($model,$_larray){
        
        if($model->{"rpta".$_larray} == TRUE){
            $_htmlrespuesta= " &#9745;";
        }else{
           $_htmlrespuesta=  " &#9744;";
        }
        
        return $_htmlrespuesta;
    }
    
    /*Funcion para tipo check con agrupacion+/
     * 
     */
    public function tipohtmlcheck_grupo($model,$_larray,$vectordata,$totalcolumnas,$access){
        
        /*Este pedazo del algoritmo funcion asi:
        */
        $_indiceagrupado = $vectordata["id_agrupacion"];
        $iniciolinea="";
        $finlinea="";
        
        //Iniciando linea si access es igual a 0
        if($access==0 and empty($this->td_agrupadas[$_indiceagrupado]["ag_descripcion"]) ){
            $iniciolinea="<tr>";
        }
        
        
        if($model->{"rpta".$_larray} == TRUE){
            $_htmlrespuesta="&#9745; ". $vectordata["nom_pregunta"];
        }else{
            $_htmlrespuesta="&#9744; ".$vectordata["nom_pregunta"];
        }
          
        if(empty($this->td_agrupadas[$_indiceagrupado])){
            
             //sumando columnas utilizadas
            $access=$access+$vectordata['num_col_label']+$vectordata['num_col_input'];
            
            $this->td_agrupadas[$_indiceagrupado]["respuestas"]="<td class='inputpregunta".$vectordata['stylecss']."' colspan='".$vectordata['num_col_input']."'>".$_htmlrespuesta; 
            $this->_vectorcntag[$_indiceagrupado] = 1;
            
            $_colsl=$vectordata['num_col_label'];
            $_colsi=$vectordata['num_col_input'];
            
        }else{
            
            $_ancho=$vectordata['num_col_label']+$vectordata['num_col_input'];
            
            $this->td_agrupadas[$_indiceagrupado]["respuestas"]=$this->td_agrupadas[$_indiceagrupado]["respuestas"]."<br/>".$_htmlrespuesta; 
            $this->_vectorcntag[$_indiceagrupado] =$this->_vectorcntag[$_indiceagrupado]+1;
            $_colsl=$vectordata['num_col_label'];
            $_colsi=$_colsi=$vectordata['num_col_input'];
           
           
        }
        
        //Guardando Descripcion=========================================================
        /*rowspan='".($this->_vectorcntag[$_indiceagrupado])."'*/
        $this->td_agrupadas[$_indiceagrupado]["ag_descripcion"]=$iniciolinea."<td class='labelpregunta".$vectordata['stylecss']."' colspan='".$vectordata['num_col_label']."' >".$vectordata["ag_descripcion"]."</td>";
        
        return [$_colsi,$_colsl];
    }
    
    
    /*Funcion para presentar la informacion
     * de una pregunta tipo 9 -> Detalle mensual en el formato de imrpesion HTML
     */
    
    public function tipohtml_9($vectordata,$totalcolumnas){
        
        if($this->tipo_archivo == 'excel'){
            $this->htmlvista.="</table>";
            
            if($totalcolumnas<13){
                
                $_anchoinicial=13;
                $_ancho_1=1;
                $_ancho_2=1;
            
            }else{
                $_anchoinicial=$totalcolumnas;
                $_anchocolumnas=$totalcolumnas/13;

                if(!is_int($_anchocolumnas)){
                    $_ancho_1=floor($_anchocolumnas);
                    $_ancho_2=ceil($_anchocolumnas);
                }else{
                    $_ancho_1=$_anchocolumnas;
                    $_ancho_2=$_anchocolumnas;
                }
            }
            
            
        }else{
            
            $_anchoinicial=13;
            $_ancho_1=1;
            $_ancho_2=1;
            
            //$this->htmlvista.="<td colspan='".$totalcolumnas."'>&nbsp;</td></tr><tr>";
            //$this->htmlvista.="<td colspan='".$totalcolumnas."'>";
            
            $this->htmlvista.="</table>";
            
        }
        
        $this->htmlvista.="<table width='100%'><tr><td colspan='".$_anchoinicial."' class='labelpregunta2'>".$vectordata["nom_pregunta"]."</td></tr>";
        $this->htmlvista.="<tr><td class='tdtable_tipo' colspan='".$_ancho_2."'>Descripcion</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Enero</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Febrero</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Marzo</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Abril</td> "
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Mayo</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Junio</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Julio</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Agosto</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Septiembre</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Octubre</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Noviembre</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Diciembre</td></tr>";
        
        $_ct=0;
        if(!empty( $vectordata["id_respuesta"]) ){
            
            $_searchinv = FdRespuestaXMes::find()
                            ->where(['id_respuesta' => $vectordata["id_respuesta"],'id_pregunta' => $vectordata["id_pregunta"] ])
                            ->asArray()->all();

           foreach($_searchinv as $_tipo13cl){
                 
                if(!empty($_tipo13cl['id_opcion_select'])){
                    
                      $_nomselect = FdOpcionSelect::find()->where(['id_opcion_select' => $vectordata["id_tselect"] ])
                            ->one();
                      
                      $this->htmlvista.="<tr><td class='inputpregunta'>".$_nomselect->nom_opcion_select."</td>";
                 }else{
                      $this->htmlvista.="<tr><td class='inputpregunta'>".$_tipo13cl['descripcion']."</td>";
                 }
                 
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo13cl['ene_decimal']."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo13cl['feb_decimal']."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo13cl['mar_decimal']."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo13cl['abr_decimal']."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo13cl['may_decimal']."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo13cl['jun_decimal']."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo13cl['jul_decimal']."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo13cl['ago_decimal']."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo13cl['sep_decimal']."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo13cl['oct_decimal']."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo13cl['nov_decimal']."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo13cl['dic_decimal']."</td></tr>";    
                 
                 $_ct+=1;
            }
            
        }
        
        if($_ct == 0){
                 $this->htmlvista.="<tr><td colspan='13' class='inputpregunta'>No hay Respuesta.</td></tr>";
        }  
        
        
        $this->htmlvista.="</table><table width='100%' class='tbespeciales'>";
        
    }
    
    
    /*Funcion para tipo de pregunta soporte
     * esta funcion genera una tabla por aparte que es incluida en el formato
     * dado que es de tipo ventana independiente
     */
    public function tipohtml_11($vectordata,$totalcolumnas){

        //1)Busqueda de las respuestas que se encuentra en la tabla sop_soporte asociadas a la respuesta en fd_respuesta de la pregunta
        //en fd_pregunta
       if($this->tipo_archivo == 'excel'){
            $this->htmlvista.="</table>";
            
            $_anchoinicial=$totalcolumnas;
            $_anchocolumnas=$totalcolumnas/2;
            
            if(!is_int($_anchocolumnas)){
                $_ancho_1=floor($_anchocolumnas);
                $_ancho_2=ceil($_anchocolumnas);
            }else{
                $_ancho_1=$_anchocolumnas;
                $_ancho_2=$_anchocolumnas;
            }
            
            
        }else{
            
            $_anchoinicial=2;
            $_ancho_1=1;
            $_ancho_2=1;
            
             $this->htmlvista.="</table>";
            
        }
        
        $this->htmlvista.="<table width='100%' class='tbespeciales'><tr><td colspan='".$_anchoinicial."' class='labelpregunta2'>".$vectordata["nom_pregunta"]."</td></tr>";
        $this->htmlvista.="<tr><td class='tdtable_tipo' colspan='".$_ancho_2."'>Soporte</td><td class='tdtable_tipo' colspan='".$_ancho_1."'>Tama&ntilde;o en Bytes</td></tr>";
       
        if(!empty( $vectordata["id_respuesta"]) ){
            
            $_searchsop = SopSoportes::find()->where(['id_respuesta' => $vectordata["id_respuesta"]])->asArray()->all();

            foreach($_searchsop as $_tipo11cl){
                 $this->htmlvista.="<tr><td class='inputpregunta' align='center' colspan='".$_ancho_2."'>".$_tipo11cl['titulo_soporte']."</td>";
                 $this->htmlvista.="<td class='inputpregunta' align='center' colspan='".$_ancho_1."'>".$_tipo11cl['tamanio_soportes']."</td></tr>";
            }
            
        }else{
                 $this->htmlvista.="<tr><td colspan='".$_anchoinicial."' class='inputpregunta'>No hay Respuesta.</td></tr>";
        }  
        
       
         $this->htmlvista.="</table><table width='100%' class='tbespeciales'>";
        
    }
    
    
    /*Funcion para tipo de pregunta coordenadas
     * esta funcion genera una tabla por aparte que es incluida en el formato
     * dado que es de tipo ventana independiente
     */
    public function tipohtml_12($vectordata,$totalcolumnas){
        
         if($this->tipo_archivo == 'excel'){
            $this->htmlvista.="</table>";
            
            if($totalcolumnas<6){
                
                $_anchoinicial=6;
                $_ancho_1=1;
                $_ancho_2=1;
            
            }else{
            
                $_anchoinicial=$totalcolumnas;
                $_anchocolumnas=$totalcolumnas/6;

                if(!is_int($_anchocolumnas)){
                    $_ancho_1=floor($_anchocolumnas);
                    $_ancho_2=ceil($_anchocolumnas);
                }else{
                    $_ancho_1=$_anchocolumnas;
                    $_ancho_2=$_anchocolumnas;
                }
            }
            
        }else{
            
            $_anchoinicial=6;
            $_ancho_1=1;
            $_ancho_2=1;
            
            //$this->htmlvista.="<td colspan='".$totalcolumnas."'>&nbsp;</td></tr><tr>";
            //$this->htmlvista.="<td colspan='".$totalcolumnas."'>";
            $this->htmlvista.="</table>";
            
        }
        
        $this->htmlvista.="<table width='100%' class='tbespeciales'><tr><td colspan='".$_anchoinicial."' class='labelpregunta2'>".$vectordata["nom_pregunta"]."</td></tr>";
        $this->htmlvista.="<tr><td class='tdtable_tipo' colspan='".$_ancho_1."'>X</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Y</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Altura</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Longitud</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Latitud</td> "
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Tipo de Coordenada</td></tr>";
        
       $_ct=0;
        if(!empty( $vectordata["id_respuesta"]) ){
            
            $_searchcoor = FdCoordenada::find()
                            ->where(['id_respuesta' => $vectordata["id_respuesta"],'id_pregunta' => $vectordata["id_pregunta"], 'id_conjunto_respuesta' => $this->id_conj_rpta])
                            ->asArray()->all();

           foreach($_searchcoor as $_tipo12cl){
                 $this->htmlvista.="<tr><td class='inputpregunta' colspan='".$_ancho_1."'>".$_tipo12cl['x']."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo12cl['y']."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo12cl['altura']."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo12cl['longitud']."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo12cl['latitud']."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo12cl['id_tcoordenada']."</td></tr>";
                 
                 $_ct+=1;
            }
            
        }
        
        if($_ct == 0){
                 $this->htmlvista.="<tr><td colspan='".$_anchoinicial."' class='inputpregunta'>No hay Respuesta.</td></tr>";
        }  
        
       
         $this->htmlvista.="</table><table width='100%' class='tbespeciales'>";
        
    }
    
    
    /*Funcion para tipo de preggunta involucrados
     * este funcion genera una tabla por aparte que es incluida en el formato
     * dado que es de tipo ventana independiente
     */
    
    
    public function tipohtml_13($vectordata,$totalcolumnas){
        
       if($this->tipo_archivo == 'excel'){
            $this->htmlvista.="</table>";
            
            $_anchoinicial=$totalcolumnas;
            $_anchocolumnas=$totalcolumnas/4;
            
            if(!is_int($_anchocolumnas)){
                $_ancho_1=floor($_anchocolumnas);
                $_ancho_2=ceil($_anchocolumnas);
            }else{
                $_ancho_1=$_anchocolumnas;
                $_ancho_2=$_anchocolumnas;
            }
            
            
        }else{
            
            $_anchoinicial=4;
            $_ancho_1=1;
            $_ancho_2=1;
            
            //$this->htmlvista.="<td colspan='".$totalcolumnas."'>&nbsp;</td></tr><tr>";
            //$this->htmlvista.="<td colspan='".$totalcolumnas."'>";
            $this->htmlvista.="</table>";
            
        }
        
        
        $this->htmlvista.="<table width='100%' class='tbespeciales'><tr><td colspan='".$_anchoinicial."' class='labelpregunta2'>".$vectordata["nom_pregunta"]."</td></tr>";
        $this->htmlvista.="<tr><td class='tdtable_tipo' colspan='".$_ancho_2."'>Nombre</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_1."'>Telefono</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Celular</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Correo Electr&oacute;nico</td> </tr>";
        
        $_ct=0;
        if(!empty( $vectordata["id_respuesta"]) ){
            
            $_searchinv = FdInvolucrado::find()
                            ->where(['id_respuesta' => $vectordata["id_respuesta"],'id_pregunta' => $vectordata["id_pregunta"], 'id_conjunto_respuesta' => $this->id_conj_rpta])
                            ->asArray()->all();

           foreach($_searchinv as $_tipo13cl){
                 $this->htmlvista.="<tr><td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo13cl['nombre']."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_1."'>".$_tipo13cl['telefono_convencional']."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo13cl['celular']."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo13cl['correo_electronico']."</td></tr>";    
                 
                 $_ct+=1;
            }
            
        }
        
        if($_ct == 0){
                 $this->htmlvista.="<tr><td colspan='".$_anchoinicial."' class='inputpregunta'>No hay Respuesta.</td></tr>";
        }  
        
     
        $this->htmlvista.="</table><table width='100%' class='tbespeciales'>";
     
    }
    
    
    /*Funcion para tipo de preggunta ubicacion
     * este funcion genera una tabla por aparte que es incluida en el formato
     * dado que es de tipo ventana independiente
     */
    
    
    public function tipohtml_14($vectordata,$totalcolumnas){
        
        if($this->tipo_archivo == 'excel'){
            $this->htmlvista.="</table>";
            
            $_anchoinicial=$totalcolumnas;
            $_anchocolumnas=$totalcolumnas/4;
            
            if(!is_int($_anchocolumnas)){
                $_ancho_1=floor($_anchocolumnas);
                $_ancho_2=ceil($_anchocolumnas);
            }else{
                $_ancho_1=$_anchocolumnas;
                $_ancho_2=$_anchocolumnas;
            }
            
            
        }else{
            
            $_anchoinicial=4;
            $_ancho_1=1;
            $_ancho_2=1;
            
            //$this->htmlvista.="<td colspan='".$totalcolumnas."'>&nbsp;</td></tr><tr>";
            //$this->htmlvista.="<td colspan='".$totalcolumnas."'>";
            
             $this->htmlvista.="</table>";
            
        }
        
        
        $this->htmlvista.="<table width='100%' class='tbespeciales'><tr><td colspan='".$_anchoinicial."' class='labelpregunta2'>".$vectordata["nom_pregunta"]."</td></tr>";
        $this->htmlvista.="<tr><td class='tdtable_tipo' colspan='".$_ancho_1."'>Parroquia</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Demarcacion</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Centro atencion ciudadano</td>"
                        . "<td class='tdtable_tipo' colspan='".$_ancho_2."'>Descripcion</td></tr>";
        
        $_ct=0;
        if(!empty( $vectordata["id_respuesta"]) ){
            
            $_searchubc = FdUbicacion::find()
                            ->where(['id_respuesta' => $vectordata["id_respuesta"],'id_pregunta' => $vectordata["id_pregunta"], 'id_conjunto_respuesta' => $this->id_conj_rpta])
                            ->asArray()->all();

           foreach($_searchubc as $_tipo14cl){
               
                 $this->htmlvista.="<tr><td class='inputpregunta' colspan='".$_ancho_1."'>".$this->nom_parroquia($_tipo14cl['cod_parroquia'],$_tipo14cl['cod_canton'],$_tipo14cl['cod_provincia'])."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$this->demarcaciones($_tipo14cl['id_demarcacion'])."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$this->centrociudadano($_tipo14cl['cod_centro_atencion_ciudadano'])."</td>";
                 $this->htmlvista.="<td class='inputpregunta' colspan='".$_ancho_2."'>".$_tipo14cl['descripcion_ubicacion']."</td></tr>";
                 
                 $_ct+=1;
            }
            
        }
        
        if($_ct == 0){
                 $this->htmlvista.="<tr><td colspan='".$_anchoinicial."' class='inputpregunta'>No hay Respuesta.</td></tr>";
        }  
        
        $this->htmlvista.="</table><table width='100%' class='tbespeciales'>";
    }
   
/*=========================================FUNCIONES PQRS===============================================================*/    
    public function gen_htmlPqrsCuerpoExcel($pqrs,$responsable,$actividad_quipux){
     $_string = ' <tr>
                        <td class="datosbasicos2">'.$pqrs->idCproceso['numero'].'</td>
                        <td class="datosbasicos2">'.$pqrs->fecha_recepcion.'</td>
                        <td class="datosbasicos2">'.$pqrs->idCproceso['num_quipux'].' </td>
                        <td class="datosbasicos2">'.$responsable->idTresponsabilidad['nom_responsabilidad'].'</td>
                            
                        <td class="datosbasicos2">'.$responsable->idUsuario['nombres'].'</td>
                        <td class="datosbasicos2">'.$pqrs->usuario['nombres'].'</td>
                        <td class="datosbasicos2"> '.$pqrs->idCproceso['fecha_registro_quipux'].' </td>
                        <td class="datosbasicos2">'.$actividad_quipux['accion_realizada'].'</td>
                            
                        <td class="datosbasicos2">'.$actividad_quipux['usuario_destino_quipux'].'</td>
                        <td class="datosbasicos2">'.$pqrs->idCproceso['asunto_quipux'].'</td>
                        <td class="datosbasicos2"> '.$pqrs->idCproceso['ult_fecha_actividad'].' </td>
                        <td class="datosbasicos2">'.$pqrs->estado['nom_eproceso'].'</td>
                            
                        <td class="datosbasicos2">'.$pqrs->actividad['nom_actividad'].'</td>
                        <td class="datosbasicos2">'.$pqrs->idCproceso['ult_fecha_estado'].'</td>

                    </tr>';
        return utf8_decode($_string);//$_string;
    }
    public function gen_htmlPqrsEncabezadoExcel(){
         $_string = '<table class="excelpqrs"> <tr>
                            <td class="pqrstitulo"> N&uacute;mero documento</td>
                            <td class="pqrstitulo">Fecha de Oficio</td>
                            <td class="pqrstitulo"> N&uacute;mero de oficio </td>
                            <td class="pqrstitulo">&Aacute;rea Responsable</td>

                            <td class="pqrstitulo"> Reasignado por</td>
                            <td class="pqrstitulo">Funcionario responsable</td>
                            <td class="pqrstitulo"> Fecha Actualizaci&oacute;n QUIPUX </td>
                            <td class="pqrstitulo">&Uacute;ltimo comentario QUIPUX</td>

                            <td class="pqrstitulo">Firmado por </td>
                            <td class="pqrstitulo">Asunto</td>
                            <td class="pqrstitulo"> Fecha de Reasignaci&oacute;n </td>
                            <td class="pqrstitulo">Estado del Tramite</td>

                            <td class="pqrstitulo">Actividad</td>
                            <td class="pqrstitulo">Fecha &Uacute;ltima Actividad</td>

                        </tr>';
         return $_string;
    }

    public function gen_htmlPqrsPieExcel(){
         $_string = '</table>';
         return $_string;
    }
    
    public function gen_htmlPqrsDenunciaPdf($pqrs){
     $_string = ' <table class="pdfpqrs"> 
                    <tr>
                        <td class="titulopqr" colspan="7">FORMATO DE DENUNCIAS</td>
                    </tr>
                    <tr>
                        <td class="legalpqr" colspan="7">Comunicación de carácter legal y debidamente motivada a través de la cual se pone en conocimiento de
                            la autoridad administrativa de la presunta ocurrencia de hechos u omisiones que amenazan el uso adecuado, eficiente, eficaz
                            o el incumplimiento de los requisitos y disposiciones legales.</td>
                    </tr>
                    <tr>
                        <td class="datospdf1" colspan="2">Fecha de recepción</td>
                        <td class="datospdf2" colspan="2">'.$pqrs->fecha_recepcion.'</td>
                        <td class="datospdf1" colspan="2">No Consecutivo <span class="legalpqrspan">(Automatico por sistema no visible para el usuario)</span></td>
                        <td class="datospdf3">'.$pqrs->num_consecutivo.'</td>
                    </tr>
                    
                    <tr>
                        <td class="titulopqr" colspan="7">INDENTIFICACIÓN DEL USUARIO</td>
                    </tr>
                    <tr>
                        <td class="datospdf1" colspan="2">Nombres y Apellidos Completos </td>
                        <td class="datospdf4" colspan="5">'.$pqrs->sol_nombres.'</td>
                    </tr>
                    <tr>
                        <td class="datospdf1" colspan="2">Documento de identificación </td>
                        <td class="datospdf4" colspan="5">'.$pqrs->sol_doc_identificacion.'</td>
                    </tr>
                    <tr>
                        <td class="datospdf1" colspan="2">Dirección </td>
                        <td class="datospdf4" colspan="5">'.$pqrs->sol_direccion.'</td>
                    </tr>
                    <tr>
                        <td class="datospdf1" colspan="2">Provincia</td>
                        <td class="datospdf5">'.$pqrs->solCodProvincia0['nombre_provincia'].'</td>
                        <td class="datospdf6">Cantón</td>
                        <td class="datospdf7" colspan="3">'.$pqrs->solCodProvincia['nombre_canton'].'</td>
                    </tr>
                    <tr>
                        <td class="datospdf1" colspan="2">Correo Electrónico</td>
                        <td class="datospdf5">'.$pqrs->sol_email.'</td>
                        <td class="datospdf6">Teléfono</td>
                        <td class="datospdf7" colspan="3">'.$pqrs->sol_telefono.'</td>
                    </tr>
                    <tr>
                        <td class="titulopqr" colspan="7">**Si usted escribe en representación de una empresa o una organización por favor incluya</td>
                    </tr>
                     <tr>
                        <td class="datospdf8">Nombre </td>
                        <td class="datospdf9" colspan="6">'.$pqrs->en_nom_nombres.'</td>
                    </tr>
                    
                    <tr>
                        <td class="datospdf8">RUC </td>
                        <td class="datospdf9" colspan="6">'.$pqrs->en_nom_ruc.'</td>
                    </tr>
                    <tr>
                        <td class="datospdf8">Dirección </td>
                        <td class="datospdf9" colspan="6">'.$pqrs->en_nom_direccion.'</td>
                    </tr>
                    <tr>
                        <td class="datospdf10">Provincia</td>
                        <td class="datospdf11"  colspan="2">'.$pqrs->enNomCodProvincia0['nombre_provincia'].'</td>
                        <td class="datospdf6">Cantón</td>
                        <td class="datospdf7"  colspan="3">'.$pqrs->enNomCodProvincia['nombre_canton'].'</td>
                    </tr>
                    <tr>
                        <td class="datospdf10">Correo Electrónico</td>
                        <td class="datospdf11" colspan="2">'.$pqrs->en_nom_email.'</td>
                        <td class="datospdf6">Teléfono</td>
                        <td class="datospdf7" colspan="3">'.$pqrs->en_nom_telefono.'</td>
                    </tr>
                    <tr>
                        <td class="titulopqr" colspan="7">DESCRIPCIÓN DE LA DENUNCIA</td>
                    </tr>
                    <tr>
                        <td class="titulopqr" colspan="7">Datos del Denunciado</td>
                    </tr>
                    <tr>
                        <td class="datospdf8">Nombre del denunciado </td>
                        <td class="datospdf9" colspan="6">'.$pqrs->denunc_nombre.'</td>
                    </tr>
                    
                    <tr>
                        <td class="datospdf10">Dirección</td>
                        <td class="datospdf11" colspan="2">'.$pqrs->denunc_direccion.'</td>
                        <td class="datospdf6">Teléfono</td>
                        <td class="datospdf7" colspan="3">'.$pqrs->denunc_telefono.'</td>
                    </tr>
                    <tr>
                        <td class="datospdf1" colspan="2">Lugar donde ocurrió el hecho</td>
                        <td class="datospdf5">'.$pqrs->lugar_hechos.'</td>
                        <td class="datospdf6">Fecha</td>
                        <td class="datospdf7" colspan="3">'.$pqrs->fecha_hechos.'</td>
                    </tr>
                    
                    <tr>
                        <td class="datospdf1" colspan="1">Narración de los hechos <span class="legalpqrspan">(La narración debe ser concreta , describiendo la forma en que sucedieron los hechos, especificando el orden en que acontecieron)</span>  </td>
                        <td class="datospdf4" colspan="6">'.$pqrs->naracion_hechos.'</td>
                    </tr>
                    <tr>
                        <td class="datospdf1" colspan="1">Elementos de prueba <span class="legalpqrspan"> En caso que tenga algún elemento que pueda servir como prueba, favor adjuntarlo y describirlo (documentos, fotografias, etc))</span> </td>
                        <td class="datospdf4" colspan="6">'.$pqrs->elementos_probatorios.'</td>
                    </tr>
                    
                </table>
                       ';
        return $_string;
    }
    
    public function gen_htmlPqrsQuejaPdf($pqrs){
     $_string = ' <table class="pdfpqrs"> 
                    <tr>
                            <td class="titulopqr" colspan="9">FORMATO DE QUEJA / RECLAMO / CONTROVERSIA</td>
                    </tr>
                    <tr>
                            <td class="titulopqr" colspan="9">Seleccionar la casilla correspondiente a la diligencia que ud desea realizar</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">Queja</td>
                        <td class="pdf" colspan="1">'.($pqrs->subtipo_queja ? 'X':'').'</td>
                        <td class="pdft" colspan="2">Reclamo</td>
                        <td class="pdf" colspan="1">'.($pqrs->subtipo_reclamo ? 'X':'').'</td>
                        <td class="pdft" colspan="2">Controversia</span></td>
                        <td class="pdf" colspan="1">'.($pqrs->subtipo_controversia ? 'X':'').'</td>
                    </tr>
                    <tr>
                        <td  colspan="3" class="pdf33t" > 
                                <span class="legalpqrspan">Manifestación de protesta, censura, descontento o inconformidad debidamente motivada, 
                                que formula una persona con realción a la conducta irregular realizada por un servidor público de la ARCA en el 
                                desarrollo de sus funciones</span>  
                        </td>
                        <td  colspan="3" class="pdf33t"> 
                                <span class="legalpqrspan">Manifestación debidamente motivada, referente a la prestación indebida de los servicios 
                                de la ARCA o la falta de atención oportuna de una solicitud presentada ante la ARCA. </span> 
                        </td>
                        <td  colspan="3" class="pdf33t"> 
                                <span class="legalpqrspan">Discución de opiniones contrapuestas entre dos o más personas</span>  
                        </td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="3">Fecha de recepción</td>
                        <td class="pdf" colspan="3">'.$pqrs->fecha_recepcion.'</td>
                        <td class="pdft" colspan="2">No Consecutivo <span class="legalpqrspan">(Automatico por sistema no visible para el usuario)</span></td>
                        <td class="pdf">'.$pqrs->num_consecutivo.'</td>
                    </tr>
                    <tr>
                        <td class="titulopqr" colspan="9">INDENTIFICACIÓN DEL USUARIO</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="3">Nombres y Apellidos Completos </td>
                        <td class="pdf" colspan="6">'.$pqrs->sol_nombres.'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="3">Documento de identificación </td>
                        <td class="pdf" colspan="6">'.$pqrs->sol_doc_identificacion.'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="3">Dirección </td>
                        <td class="pdf" colspan="6">'.$pqrs->sol_direccion.'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">Provincia</td>
                        <td class="pdf" colspan="2">'.$pqrs->solCodProvincia0['nombre_provincia'].'</td>
                        <td class="pdft" colspan="2">Cantón</td>
                        <td class="pdf" colspan="3">'.$pqrs->solCodProvincia['nombre_canton'].'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">Correo Electrónico</td>
                        <td class="pdf" colspan="2">'.$pqrs->sol_email.'</td>
                        <td class="pdft" colspan="2">Teléfono</td>
                        <td class="pdf" colspan="3">'.$pqrs->sol_telefono.'</td>
                    </tr>
                    <tr>
                        <td class="titulopqr" colspan="9">**Si usted escribe en representación de una empresa o una organización por favor incluya</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">Nombre </td>
                        <td class="pdf" colspan="7">'.$pqrs->en_nom_nombres.'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">RUC </td>
                        <td class="pdf" colspan="7">'.$pqrs->en_nom_ruc.'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">Dirección </td>
                        <td class="pdf" colspan="7">'.$pqrs->en_nom_direccion.'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">Provincia</td>
                        <td class="pdf"  colspan="2">'.$pqrs->enNomCodProvincia0['nombre_provincia'].'</td>
                        <td class="pdft" colspan="2">Cantón</td>
                        <td class="pdf"  colspan="3">'.$pqrs->enNomCodProvincia['nombre_canton'].'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">Correo Electrónico</td>
                        <td class="pdf" colspan="2">'.$pqrs->en_nom_email.'</td>
                        <td class="pdft" colspan="2">Teléfono</td>
                        <td class="pdf" colspan="3">'.$pqrs->en_nom_telefono.'</td>
                    </tr>
                    <tr>
                        <td class="titulopqr" colspan="9">DESCRIPCIÓN QUEJA / RECLAMO / CONTROVERSIA</td>
                    </tr>

                    <tr>
                        <td class="pdft" colspan="3">Queja / <br> Proceso, servicio o producto objeto del reclamo / <br> Ente o persona con el que surgio la controversia</td>
                        <td class="pdf" colspan="6">'.$pqrs->aquien_dirige.'</td>
                    </tr>
                    
                    <tr>
                        <td class="pdft" colspan="2">Lugar donde ocurrió el hecho</td>
                        <td class="pdf" colspan="2">'.$pqrs->lugar_hechos.'</td>
                        <td class="pdft" colspan="2">Fecha</td>
                        <td class="pdf" colspan="3">'.$pqrs->fecha_hechos.'</td>
                    </tr>
                    
                    <tr>
                        <td class="pdft" colspan="3">Narración de los hechos <span class="legalpqrspan">(La narración debe ser concreta , describiendo la forma en que sucedieron los hechos, especificando el orden en que acontecieron)</span>  </td>
                        <td class="pdf" colspan="6">'.$pqrs->naracion_hechos.'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="3">Elementos de prueba <span class="legalpqrspan"> En caso que tenga algún elemento que pueda servir como prueba, favor adjuntarlo y describirlo (documentos, fotografias, etc))</span> </td>
                        <td class="pdf" colspan="6">'.$pqrs->elementos_probatorios.'</td>
                    </tr>
   
                </table>
                       ';
        return $_string;
    }
    
    public function gen_htmlPqrsPeticionPdf($pqrs){
     $_string = ' <table class="pdfpqrs"> 
                    <tr>
                            <td class="titulopqr" colspan="9">FORMATO DE PETICIONES</td>
                    </tr>
                    <tr>
                            <td class="pdf" colspan="9"><span class="legalpqrspan">Derecho que tiene toda persona para solicitar, para elevar solicitudes respetuosas de información y/o consulta sobre los servicios que presata la ARCA, para la satisfacción de sus necesidades, la petición debe estar debidamente motivada</span></td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="3">Fecha de recepción</td>
                        <td class="pdf" colspan="3">'.$pqrs->fecha_recepcion.'</td>
                        <td class="pdft" colspan="2">No Consecutivo <span class="legalpqrspan">(Automatico por sistema no visible para el usuario)</span></td>
                        <td class="pdf">'.$pqrs->num_consecutivo.'</td>
                    </tr>

                    <tr>
                        <td class="titulopqr" colspan="9">INDENTIFICACIÓN DEL USUARIO</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="3">Nombres y Apellidos Completos </td>
                        <td class="pdf" colspan="6">'.$pqrs->sol_nombres.'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="3">Documento de identificación </td>
                        <td class="pdf" colspan="6">'.$pqrs->sol_doc_identificacion.'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="3">Dirección </td>
                        <td class="pdf" colspan="6">'.$pqrs->sol_direccion.'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">Provincia</td>
                        <td class="pdf" colspan="2">'.$pqrs->solCodProvincia0['nombre_provincia'].'</td>
                        <td class="pdft" colspan="2">Cantón</td>
                        <td class="pdf" colspan="3">'.$pqrs->solCodProvincia['nombre_canton'].'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">Correo Electrónico</td>
                        <td class="pdf" colspan="2">'.$pqrs->sol_email.'</td>
                        <td class="pdft" colspan="2">Teléfono</td>
                        <td class="pdf" colspan="3">'.$pqrs->sol_telefono.'</td>
                    </tr>
                    <tr>
                        <td class="titulopqr" colspan="9">**Si usted escribe en representación de una empresa o una organización por favor incluya</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">Nombre </td>
                        <td class="pdf" colspan="7">'.$pqrs->en_nom_nombres.'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">RUC </td>
                        <td class="pdf" colspan="7">'.$pqrs->en_nom_ruc.'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">Dirección </td>
                        <td class="pdf" colspan="7">'.$pqrs->en_nom_direccion.'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">Provincia</td>
                        <td class="pdf"  colspan="2">'.$pqrs->enNomCodProvincia0['nombre_provincia'].'</td>
                        <td class="pdft" colspan="2">Cantón</td>
                        <td class="pdf"  colspan="3">'.$pqrs->enNomCodProvincia['nombre_canton'].'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">Correo Electrónico</td>
                        <td class="pdf" colspan="2">'.$pqrs->en_nom_email.'</td>
                        <td class="pdft" colspan="2">Teléfono</td>
                        <td class="pdf" colspan="3">'.$pqrs->en_nom_telefono.'</td>
                    </tr>
                    <tr>
                        <td class="titulopqr" colspan="9">DESCRIPCIÓN DE LA PETICIÓN</td>
                    </tr>

                    <tr>
                        <td class="pdft" colspan="3">Proceso, servicio, producto a que se dirige la petición</td>
                        <td class="pdf" colspan="6">'.$pqrs->aquien_dirige.'</td>
                    </tr>
                    
                    <tr>
                        <td class="pdft" colspan="3">Objeto de la petición <span class="legalpqrspan">(Qué es lo que desea alcanzar por medio de la petición)</span></td>
                        <td class="pdf" colspan="6">'.$pqrs->objeto_peticion.'</td>

                    </tr>
                    <tr>
                        <td class="pdft" colspan="3">Descripción de la petición <span class="legalpqrspan">(Explique claramente lo que requiere, este debe ser alcanzable y estar en competencia de la ARCA)</span>  </td>
                        <td class="pdf" colspan="6">'.$pqrs->naracion_hechos.'</td>
                    </tr>
                    
                </table>
                       ';
        return $_string;
    }
    
    public function gen_htmlPqrsSugerenciaPdf($pqrs){
     $_string = ' <table class="pdfpqrs"> 
                    <tr>
                            <td class="titulopqr" colspan="9">FORMATO DE SUGERENCIAS Y FELICITACIONESX</td>
                    </tr>
                   <tr>
                            <td class="titulopqr" colspan="9">Seleccionar la casilla correspondiente a la diligencia que ud desea realizar</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="4">Sugerencia</td>
                        <td class="pdf" colspan="1">'.($pqrs->subtipo_sugerencia ? 'X':'').'</td>
                        <td class="pdft" colspan="3">Felicitacion</td>
                        <td class="pdf" colspan="1">'.($pqrs->subtipo_felicitacion ? 'X':'').'</td>

                    </tr>
                    <tr>
                        <td  colspan="5" class="pdf" > 
                                <span class="legalpqrspan">Propuesta que se presenta para incidir o mejorar un proceso cuyo objeto está relacionado con la prestación de un servicio o el cumplimiento de una función pública.</span>  
                        </td>
                        <td  colspan="4" class="pdf"> 
                                <span class="legalpqrspan">Expresión de la alegria y satisfacción que se siente por la atención de los servidores de la ARCA y/o provisión de un servicio de la ARCA</span> 
                        </td>
                       
                    </tr>
                    <tr>
                        <td class="pdft" colspan="3">Fecha de recepción</td>
                        <td class="pdf" colspan="2">'.$pqrs->fecha_recepcion.'</td>
                        <td class="pdft" colspan="3">No Consecutivo <span class="legalpqrspan">(Automatico por sistema no visible para el usuario)</span></td>
                        <td class="pdf">'.$pqrs->num_consecutivo.'</td>
                    </tr>

                    <tr>
                        <td class="titulopqr" colspan="9">INDENTIFICACIÓN DEL USUARIO</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="3">Nombres y Apellidos Completos </td>
                        <td class="pdf" colspan="6">'.$pqrs->sol_nombres.'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="3">Documento de identificación </td>
                        <td class="pdf" colspan="6">'.$pqrs->sol_doc_identificacion.'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="3">Dirección </td>
                        <td class="pdf" colspan="6">'.$pqrs->sol_direccion.'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">Provincia</td>
                        <td class="pdf" colspan="2">'.$pqrs->solCodProvincia0['nombre_provincia'].'</td>
                        <td class="pdft" colspan="2">Cantón</td>
                        <td class="pdf" colspan="3">'.$pqrs->solCodProvincia['nombre_canton'].'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">Correo Electrónico</td>
                        <td class="pdf" colspan="2">'.$pqrs->sol_email.'</td>
                        <td class="pdft" colspan="2">Teléfono</td>
                        <td class="pdf" colspan="3">'.$pqrs->sol_telefono.'</td>
                    </tr>
                    <tr>
                        <td class="titulopqr" colspan="9">**Si usted escribe en representación de una empresa o una organización por favor incluya</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">Nombre </td>
                        <td class="pdf" colspan="7">'.$pqrs->en_nom_nombres.'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">RUC </td>
                        <td class="pdf" colspan="7">'.$pqrs->en_nom_ruc.'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">Dirección </td>
                        <td class="pdf" colspan="7">'.$pqrs->en_nom_direccion.'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">Provincia</td>
                        <td class="pdf"  colspan="2">'.$pqrs->enNomCodProvincia0['nombre_provincia'].'</td>
                        <td class="pdft" colspan="2">Cantón</td>
                        <td class="pdf"  colspan="3">'.$pqrs->enNomCodProvincia['nombre_canton'].'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="2">Correo Electrónico</td>
                        <td class="pdf" colspan="2">'.$pqrs->en_nom_email.'</td>
                        <td class="pdft" colspan="2">Teléfono</td>
                        <td class="pdf" colspan="3">'.$pqrs->en_nom_telefono.'</td>
                    </tr>
                    <tr>
                        <td class="titulopqr" colspan="9">DESCRIPCIÓN DE LA PETICIÓN</td>
                    </tr>

                    <tr>
                        <td class="pdft" colspan="3">Proceso, servicio, producto a que se dirige la comunicación</td>
                        <td class="pdf" colspan="6">'.$pqrs->aquien_dirige.'</td>
                    </tr>
                    <tr>
                        <td class="pdft" colspan="3">Descripción de la sugerencia o la felicitación </td>
                        <td class="pdf" colspan="6">'.$pqrs->descripcion_sugerencia.'</td>
                    </tr>
                    
                </table>
                       ';
        return $_string;
    }

/*===========================================FUNCIONES DE FORMATO====================================================================*/
    protected function romanic_number($integer) 
    { 
        $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1); 
        $return = ''; 
        while($integer > 0) 
        { 
            foreach($table as $rom=>$arb) 
            { 
                if($integer >= $arb) 
                { 
                    $integer -= $arb; 
                    $return .= $rom; 
                    break; 
                } 
            } 
        } 

        return $return; 
    }
    
     /*Reemplaza para construir el formato de fecha valido para PHP
    * dd -> d => entrega el dia en formato de dos digitos
    * MM -> m => entrega el mes en formato de dos digitos
    * YYYY -> Y => entrega el año en formato de 4 digitos
    */ 
   protected function formatear($tipoformato){
       
       $tipoformato = str_replace("dd", "d", $tipoformato);
       $tipoformato = str_replace("MM", "m", $tipoformato);
       $tipoformato = str_replace("yyyy", "Y", $tipoformato);
       
       return $tipoformato;
   } 
    
   
   /*Funcion que entrega e nombre de una parroqui*/
   protected function nom_parroquia($cod_parroquia,$cod_canton,$cod_provincia){
       
       $_data= Parroquias::find()->where(['cod_parroquia'=>$cod_parroquia,'cod_canton'=>$cod_canton,'cod_provincia'=>$cod_provincia])->one();
       return $_data->nombre_parroquia;
   }
   
   
   /*Funcion que entrega e nombre de un canton*/
   protected function nom_canton($cod_canton,$cod_provincia=null){
       
       $_data = Cantones::find()->where(['cod_canton'=>$cod_canton,'cod_provincia'=>$cod_provincia])->one();
       return $_data->nombre_canton;
   }
   
   /*Funcion que entrega el nombre de una provincia*/
   protected function nom_provincia($cod_provincia){
       
       $_data = Provincias::find()->where(['cod_provincia'=>$cod_provincia])->one();
       return $_data->nombre_provincia;
   }
   
   
   /*Funcion par anombre demarcaciones*/
   protected function demarcaciones($id_demarcacion){
       
       $_data = \common\models\autenticacion\Demarcaciones::find()->where(['id_demarcacion'=>$id_demarcacion])->one();
       return $_data->nombre_demarcacion;
   }
   
   
   /*Funcion par anombre demarcaciones*/
   protected function centrociudadano($id_centro){
       
       $_data = \common\models\poc\CentroAtencionCiudadano::find()->where(['cod_centro_atencion_ciudadano'=>$id_centro])->one();
       return $_data->nom_centro_atencion_ciudadano;
   }
   
   
   /*Funcion par anombre de tipo de coordenada*/
    protected function tipocoordenada($id_tcoordenada){
       
       $_data = \common\models\poc\TrTipoCoordenada::find()->where(['id_tcoordenada'=>$id_tcoordenada])->one();
       return $_data->nom_tcoordenada;
   }
   
   
   /*Funcion para traer la respuesta a un tipo de pregunta Multiple*/
   protected function getResponse($tipo,$id_pregunta,$id_capitulo,$id_formato,$id_version,$id_conjunto_pregunta,$id_conjunto_respuesta){
       
       $_stringreturn='';
       
       if($tipo==1){
           $_campo = "ra_fecha";
       }else if($tipo == 15){
           $_campo = "ra_descripcion";
       }else if($tipo == 8){
           $_campo = "ra_moneda";
       }else if($tipo == 7){
           $_campo = "ra_porcentaje";
       }else if($tipo == 6){
           $_campo = "ra_decimal"; 
       }else if($tipo == 5){
           $_campo = "ra_entero";
       }else if($tipo == '3'){
           $_campo = "id_opcion_select";
       }
       
       $_data = \common\models\poc\FdRespuesta::find()
               ->where(['id_capitulo' =>$id_capitulo,
                        'id_formato'=>$id_formato,
                        'id_version'=>$id_version,
                        'id_conjunto_pregunta'=>$id_conjunto_pregunta,
                        'id_conjunto_respuesta'=>$id_conjunto_respuesta,
                        'id_pregunta'=>$id_pregunta])->all();
       
       
       foreach($_data as $_clave){
           
            if($tipo == '3'){
                $_nombrevalor = FdOpcionSelect::find()
                        ->where(['id_opcion_select'=>$_clave->{$_campo}])
                        ->one();
                
                $_datarespuesta = $_nombrevalor->nom_opcion_select; 
            }else{
                $_datarespuesta = $_clave->{$_campo};
            }
           
            $_stringreturn .= "<div id='".$_clave->id_respuesta."' style='display:block'>".$_datarespuesta;
            $_stringreturn .= '<button type="button" onclick="deleteRpta('.$_clave->id_respuesta.')">-</button></div>';
       }
        
      return $_stringreturn;
       
   }
}

