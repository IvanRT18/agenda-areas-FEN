<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use App\Login\LoginClass;
use Symfony\Component\VarDumper\VarDumper;

ob_start();

class LoginController extends Controller
{
    public function index()
    {
        $LoginParams = config('login_param');
        
        // var_dump($LoginParams);
        //Si no existe sesi�n de Usuario

		if(!isset($_GET['usuario']) && !isset($_GET['tipo']))
		{
            // var_dump('Sesión no existente');
            $cliente_login = new LoginClass($LoginParams['serviceUrl'], $LoginParams['serviceLoginFormUrl'], $LoginParams['serviceClientId'], $LoginParams['serviceReturnURL']);
            // var_dump($cliente_login);
			$cliente_login->autentificar();
            // var_dump('Despues de identificarse');
			echo($cliente_login->mensaje);
		}
		//Si existe la Sesión de Usuario.
		else
		{
            var_dump('Sesión sí existente');
            var_dump($_GET);
			header('Location: '."http://localhost/agendaLabPrueba/resources/views/inicio.php?user=".$_GET['usuario']."&type=".$_GET['tipo']);
            // exit;
		}
    }

    public function home()
    {
        
    }
}