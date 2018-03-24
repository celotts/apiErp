<?php

namespace App\Http\Controllers\apiLogin;

use App\Auditor;
use App\User;
use DB;
use Hash;
use JWTAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    //

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : imagen
     * Función           : Foto del al App
     * Parametros        : $_REQUEST
     * Devuelve          : fOTO
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function imagen()
    {
        $value=array('email'=>$_REQUEST['usuario']);
        return response()->json(User::Foto($value['email']));
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++


    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : sosRecoverPasswordMaestro
     * Función           : Genera Clave Maestra usuario
     * Parametros        : $_REQUEST
     * Devuelve          : NADA
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function sosRecoverPasswordMaestro()
    {
        $value=array(
            'email'=>$_REQUEST['email']
        );
        return response()->json(User::sosRecoverPasswordMaestro($value));
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++


    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : login
     * Función           : loguea la app
     * Parametros        : $_REQUEST
     * Devuelve          : datos
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function loguear(Request $request)
    {
        /**
         * ------------------------------------------------
         * En caso de perdida de clave se debe
         * habilitar la funcion sosRecoverPasswordMaestro
         * Para crear un superUsuario
         */
        //User::sosRecoverPasswordMaestro();
        /**
         * ------------------------------------------------
         */

        /**
         * Valida si el usuario esta bloqueado
         */
        $credentials = $request->all();
        $credentials1 = array(
            'email'=>$credentials['email'],
            'password'=>$credentials['password']
        );
        /**
         * Valida el estado del usuario
         */
        $estado=User::EstadoUser($credentials['email']);
        if($estado==1){
            /**
             * Usuario Suspendido
             */
            return array(
                'token' => '',
                'data'=>'',
                'success' => false,
                'mensaje'=>'El usuario fue suspendido, acceso restringido...');
        }
        if($estado==2){
            /**
             * Usuario Suspendido
             */
            return array(
                'token' => '',
                'data'=>'',
                'success' => false,
                'mensaje'=>'El usuario fue dado de baja, acceso restringido...');
        }
        if($estado==0){
            // No se usa condicion
        }else{
            if($estado['codigo']==100){
                return array(
                    'token' => '',
                    'data'=>'',
                    'success' => false,
                    'mensaje'=>$estado['mensaje']);
            }
        }
        /**
         * Asigna los valores enviado a la variable
         * $credentials
         */
        try {
            /**
             * Valida las credenciales para crear el token
             */
            if (!$token = JWTAuth::attempt($credentials1)) {
                /**
                 * --------------------------------------------------------
                 * Si se presenta una falla en la revision de las
                 * Credenciales (email y Password) del usuario actualiza
                 * el registro incrementando el campo Intento Fallido.
                 * Siempre y cuando el user este registrado
                 *
                 * --------------------------------------------------------
                */
                $intento=User::intentoFallido($credentials);
                if($intento==1){
                    return response()->json([
                        'token' => '',
                        'data'=>'',
                        'success' => false,
                        'mensaje' => 'Usuario o Clave de acceso incorrecto.',
                        'conexion' => 0]);

                }else{
                    if($intento==10) {
                        return response()->json([
                            'token' => '',
                            'data'=>'',
                            'success' => false,
                            'mensaje' => 'Usuario Suspendido por más de 10 intentos fallidos. Notifique al administrador',
                            'conexion' => 0]);
                    }
                    if($intento['codigo']==98){
                        return response()->json([
                            'mensaje' => $intento['mensaje'],
                            'token' => '',
                            'data'=>'',
                            'success' => false,
                            'conexion' => 0]);
                    }
                }


            }
        }catch (JWTException $e) {
            /**
             * Si existe falla en la autenticación devuelve un mensaje
             */
            return array(
                'token' => '',
                'data'=>'',
                'success' => false,
                'mensaje'=>'Falla en la autenticación del usuario, Intente de nuevo.');

        }
        /**
         * Si la autenticacion fue un exito
         * Actualiza la traza del Auditor
         */
        //TODO REVISAR IDEMPRESA ESTA CON 1 YA QUE CON LA VAIABLE DA ERROR
        Auditor::trazas(array(
            'username'=>$credentials['email'],
            'idEmpresa'=>1,//$credentials['idEmpresa'],
            'idProcesado'=>0,
            'accion'=>'INICIA SESION',
            'tabla'=>'USUARIOS',
            'operacion'=>'EL USUARIO INICIA SESION A LA APLICACION'
        ));
        /**
         * conexion= 1 Ok --> Conectado
         * Devuelve datos del usuario
         * conectado
         */
        return response()->json([
            'token' => $token,
            'data'=>User::datosUsuario($credentials),
            'success' => true,
            'mensaje'=>''
        ]);

    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++


}
