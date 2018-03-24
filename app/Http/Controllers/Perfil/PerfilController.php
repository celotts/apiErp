<?php

namespace App\Http\Controllers\Perfil;
use App\Perfil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PerfilController extends Controller
{
    //
    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : savePerfil
     * Función           : Save datos del perfil
     * Parametros        : $_REQUEST
     * Devuelve          : nada
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function savePerfil()
    {
        $value=array(
            'id'=>$_REQUEST['id'],
            'nombre'=>$_REQUEST['nombre'],
            'nivel'=>$_REQUEST['nivel'],
            'user'=>$_REQUEST['user'],
            'idEmpresa'=>$_REQUEST['idEmpresa']
        );
        return response()->json(Perfil::savePerfil($value));
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : allPerfil
     * Función           : Lista all Perfil
     * Parametros        : $_REQUEST
     * Devuelve          : cursor
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function allPerfil()
    {
        $value=array(
            'nombre'=>'%'.$_REQUEST['nombre'].'%',
            'user'=>$_REQUEST['user'],
            'pagina'=>$_REQUEST['pagina'],
            'cantItem'=>$_REQUEST['cantItem'],
            'idEmpresa'=>$_REQUEST['idEmpresa']
        );
        return response()->json(Perfil::allPerfiles($value));
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : delPerfil
     * Función           : Route::post('allPerfil', 'Perfil\PerfilController@allPerfil');
     * Parametros        : $_REQUEST
     * Devuelve          : nada
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function delPerfil()
    {
        $data=array(
            'id'=>$_REQUEST['id'],
            'idEmpresa'=>$_REQUEST['idEmpresa'],
            'user'=>$_REQUEST['user']
        );

        return response()->json(Perfil::delPerfil($data));
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++


}
