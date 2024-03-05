<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
ob_start();


$LoginParams = config('login_param');

class LoginClientServiceProvider extends ServiceProvider
{
    private $_svc_url;
	private $_svc_id;
	private $_svc_return;
	private $_svc_instance;
	private $_svc_form_url;
	
	public $mensaje  = 'Vacio';
	public $succesfull_login = false;
	public $data = null;
	
/**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }


	public function __construct($serviceUrl,$serviceLoginFormUrl,$serviceClientId,$serviceReturnURL, $LoginParams)
	{
		$this->_svc_url = $serviceUrl; //URL de Servicio para consumir
		$this->_svc_id = $serviceClientId; //Id de la app
		$this->_svc_return = $serviceReturnURL; //URL de regreso
		$this->_svc_form_url = $serviceLoginFormUrl; //Url con formulario de login

		$this->_svc_instance = new \SoapClient(
			$LoginParams.$serviceUrl, array('soap_version'=> SOAP_1_1));

		var_dump($LoginParams);
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
		exit;
	}
	
	public function autentificar()
	{
		//Verifica si existe un tiket 
		if(isset($_SESSION["Ticket"]))
		{
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
				exit;
			}	
		}
		//No existe tiket. Se crea nuevo y se envia a pantalla de login.
		else
		{	
			$this->_crearNuevoTicket();
		}
	}
	
	private function _crearNuevoTicket()
	{
		//EDIT version 1.1 
		/*
		 * Se quita la llamada al m�todo sin URL de regreso explicito.
		 * Se utiliza solo llamada a m�todo de servicio con el parametro de ReturnURL
		 * - por problemas con redirecci�n del lado de servidor -
		 * */
//		if(isset($_GET["ReturnUrl"]))
		{		
			$parametros = array ('idAplicacion' => $this->_svc_id, 'ReturnUrl' => $this->_svc_return);
			$Respuesta = $this->_svc_instance->NuevaSesionConUrlRetorno($parametros);	
			$xmlObject = simplexml_load_string($Respuesta->NuevaSesionConUrlRetornoResult);	
		}
//		else
//		{
//			$parametros = array ('idAplicacion' => $this->_svc_id);
//			$Respuesta = $this->_svc_instance->NuevaSesion($parametros);								
//			$xmlObject = simplexml_load_string($Respuesta->NuevaSesionResult);	
//		}	
		//##Mensaje de Debug.	
		print_r($xmlObject);
		//Se obtiene Ticket de autentifcación
		$ticket = (int)$xmlObject['Ticket'];
		$sesionId = (string)$xmlObject['ClaveSesion'];
		//Se crea la cookie de autentificación.
		setcookie("SC",$sesionId,time()+600);
		//Se redirige a pantalla de Login institucional
		header('Location: '.$this->_svc_form_url.'?Ticket='.$ticket);
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