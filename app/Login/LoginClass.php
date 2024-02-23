<?php

namespace App\Login;
ob_start();
/*
 *      /* 
 *      
 *      login_v1.1.php
 *      
 *      v1.1 Noviembre 2011 Guillermo Canto
 * 		---------------------------------------
 *      
 *      login_v1.0.php
 *      
 *      V1.0 Julio 2011 Guillermo Canto <guillermo.canto@uaslp.mx>
 *      
 * 		---------------------------------------
 
 * 		**Libre para Modificaci�n y distribuci�n.(GPL)
 * 
 * 		---NO MODIFICAR ESTE ARCHIVO SI SE ENCUENTRA EN PRODUCCION ---
 * 
 *  	Procurar quitar permisos de ejecuci�n y de apertura por cualquier 
 * 		usuario en el servidor - por motivos de seguridad -.
 * 		El objetivo del archivo es que solo sea accesible desde el mismo 
 * 		servidor y no desde el cliente.
 *      
 *      Clase para la implementaci�n del cliente de autentificaci�n
 * 		institucional de la UASLP- Versión para PHP 5.
 * 
 * 		La Clase hace uso del archivo login_param.inc, deben permancer
 * 		en la misma carpeta.
 * 
 * 		Incluir este archivo en los proyectos que requieran 
 * 		autentificación. Utilizar instrucci�n "require" o "include"
 *      preferentemente.    
 *      
 * EJEMPLO DE MODO DE USO:
 * 
 * 		require('login_param.inc');
 *		include("login_v1.1.inc");
 *		//Verificación libre de usuario autentificado. Ej: Sesiones.
 * 		if(!isset($_SESION['user']) && &_SESION['auth'] != true)
 *		{
 *			// Creación de objeto y llamada al metodo para iniciar 
 * 			// proceso de autentificación.
 * 			$cliente_login = new login_client($serviceUrl,$serviceLoginFormUrl,
 * 											  $serviceClientId,$serviceReturnURL);
 *			$cliente_login->autentificar();
 *		}
 * 		else
 * 		{  //Flujo normal de aplicación ya con la autentificación exitosa. }
 */

//Clase pricipal.
/*Login_client:
 * Objeto que realiza la conexi�n al servicuio de autentificaci�n en el dominio de la UASLP.
 * Utiliza los parametos establoecidos en el archivo login_param.inc
 * 
 * NOTAS:
 * Utilizar el m�todo autentificar.
 * Editar el m�todo _iniciaSesionContinua
 * */
class LoginClass
{
	private $_svc_url;
	private $_svc_id;
	private $_svc_return;
	private $_svc_instance;
	private $_svc_form_url;
	
	public $mensaje  = 'Vacio';
	public $succesfull_login = false;
	public $data = null;
	
	public function __construct($serviceUrl,$serviceLoginFormUrl,$serviceClientId,$serviceReturnURL)
	{
		$this->_svc_url = $serviceUrl; //URL de Servicio para consumir
		$this->_svc_id = $serviceClientId; //Id de la app
		$this->_svc_return = $serviceReturnURL; //URL de regreso
		$this->_svc_form_url = $serviceLoginFormUrl; //Url con formulario de login
		$this->_svc_instance = new \SoapClient($serviceUrl, array('soap_version'=> SOAP_1_1));
	}
	
	
	//--Modificable para creacion de la sesion o manipulaci�n de datos.--//
	private function _iniciaSesionContinua()
	{
		//Ejemplo Autentificaci�n exitosa: Redireccion a URL de Inicio 	
		//Enviando parametros.
		// En este ejemplo se utiliza valores GET para validar.
		//----------------------------------------------- 
		// NO DEBE USARSE EN ENTORNOS DE PRODUCCI�N.!!!!!
		//-----------------------------------------------
		// Preferible utilizar sesiones, Ej: if(!isset($_SESION['user']))..etc
		header('Location: '.$this->_svc_return."?usuario=".$this->data['Usuario']."&tipo=".$this->data['Tipo']);
	}
	
	public function autentificar()
	{
		//Verifica si existe un tiket 
		if(isset($_GET["Ticket"]))
		{
			// var_dump('Sí hay ticket');
			//Verifica existencia de la cookie.
			if(isset($_COOKIE["SC"]))
			{	
				$this->_verificarTicket();
				if((bool)$this->succesfull_login)
					//Autentificación Exitosa, regresa a aplicación.
					$this->_iniciaSesionContinua();
				else
					echo "Fallo:".$this->mensaje;
			}
			else
			{
				//##Mensaje de Debug.
				echo  "No se encontró cookie.";
				//Se Redirecciona a página inicial para iniciar proceso de login.
				header('Location: '.$this->_svc_return);
			}	
		}
		//No existe tiket. Se crea nuevo y se envia a pantalla de login.
		else
		{	
			// var_dump("No hay ticket");
			$this->_crearNuevoTicket();
		}
	}
	
	private function _crearNuevoTicket()
	{
		// var_dump('Creando nuevo ticket');
		//EDIT version 1.1 
		/*
		 * Se quita la llamada al m�todo sin URL de regreso explicito.
		 * Se utiliza solo llamada a m�todo de servicio con el parametro de ReturnURL
		 * - por problemas con redirecci�n del lado de servidor -
		 * */
		//if(isset($_GET["ReturnUrl"]))
		{		
			$parametros = array ('idAplicacion' => $this->_svc_id, 'ReturnUrl' => $this->_svc_return);
			$Respuesta = $this->_svc_instance->NuevaSesionConUrlRetorno($parametros);
			// var_dump($Respuesta);	
			$xmlObject = simplexml_load_string($Respuesta->NuevaSesionConUrlRetornoResult);
			// var_dump($xmlObject);	
		}
//		else
//		{
//			$parametros = array ('idAplicacion' => $this->_svc_id);
//			$Respuesta = $this->_svc_instance->NuevaSesion($parametros);								
//			$xmlObject = simplexml_load_string($Respuesta->NuevaSesionResult);	
//		}	
		//##Mensaje de Debug.	
		// print_r($xmlObject);
		//Se obtiene Ticket de autentifcación
		$ticket = (int)$xmlObject['Ticket'];
		$sesionId = (string)$xmlObject['ClaveSesion'];

		// var_dump('Este es el ticket');
		// var_dump($ticket);
		// var_dump('Este es sesionId');
		// var_dump($sesionId);
		// //Se crea la cookie de autentificación.
		// setcookie("SC",$sesionId,time()+600);
		// //Se redirige a pantalla de Login institucional
		// var_dump('Esta es la form_url');
		// var_dump($this->_svc_form_url);

		header('Location: '.$this->_svc_form_url.'?Ticket='.$ticket);

		// return view('inicio', compact('ticket'));
		exit;
	}

	private function _verificarTicket()
	{
		//##Mensaje de Debug.
		echo "Se encontro la cookie:".$_COOKIE['SC'];
		//Verificar validez de COOKIE	
		$valido = -1;
		$clave = $_COOKIE["SC"];
		//Se elimina la cookie del sistema.
		setcookie("SC","",0);
		//Se establecen aprametros para servicio..
		$parametros = array ('Ticket' => $_GET["Ticket"], 'ClaveSesion' => $clave);
		//Validar cookie
		if ( $_GET["Ticket"] > 0 )
		{			
			$Respuesta = $this->_svc_instance->ValidaCliente($parametros);
			$valido = $Respuesta->ValidaClienteResult;	
		}
		//Analiza Resultado de la consulta
		switch($valido)
                    {
				case 0: // Cookie no es valida o expiro Ticket.
					 $this->mensaje = "ERROR: Inicio de Sesión no valido.";
					 break; 
				case 1: // Ok  Cookie Valida!                                
						//Obtener Resultado                           
						$Respuesta = $this->_svc_instance->EstadoUsuario($parametros);
						$xmlObject = simplexml_load_string($Respuesta->EstadoUsuarioResult);
						
						//##Mensaje de Debug.
						print_r($xmlObject);					
						if($xmlObject->attributes()->count() !== 0)
						{     
							//Verificar que no exista un error                                         
							 if (!isset($xmlObject['TextoError']))
							 {
								 //Si esta autentificado Autentificar por programación la sesion local.								 
								 if ((bool)$xmlObject['Autenticado'])
								 {
									 $this->succesfull_login = true;
									 $this->data = $xmlObject;
								 }
								 else 
									$this->mensaje =  "<p>Error: El Usuario no esta autentificado.</p>";								                                        
							 }
							 else
								$this->mensaje =  "<p>Error:Se agoto el tiempo.</p>";                                    
						}
						else 
						{                                    
							$this->mensaje =  "<p>Error:El Ticket Expiro.</p>";
						}
					break;
				case -1: // Error al conectar, no se pudo validar cookie!                               
					   $this->mensaje =  "<p>No se pudo validar, Intentelo de nuevo.</p>";
					break; 
			}
	}	
}

?>