<?php

namespace App\Http\Controllers\Menu;

use App\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    //
    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : saveMenu
     * Función           : Save datos del Menu
     * Parametros        : $_REQUEST
     * Devuelve          : nada
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function saveMenu()
    {
        $value=array(
            'id'=>$_REQUEST['id'],
            'nombre'=>$_REQUEST['nombre'],
            'controlador'=>$_REQUEST['controlador'],
            'tipoMenu'=>$_REQUEST['tipoMenu'],
            'icono'=>$_REQUEST['icono'],
            'user'=>$_REQUEST['user'],
            'idEmpresa'=>$_REQUEST['idEmpresa']
        );
        return response()->json(Menu::saveMenu($value));
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : allMenu
     * Función           : Lista all Menu
     * Parametros        : $_REQUEST
     * Devuelve          : cursor
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function allMenu()
    {
        $value=array(
            'nombre'=>'%'.$_REQUEST['nombre'].'%',
            'controlador'=>'%'.$_REQUEST['nombre'].'%',
            'icono'=>'%'.$_REQUEST['icono'].'%',
            'user'=>$_REQUEST['user'],
            'pagina'=>$_REQUEST['pagina'],
            'cantItem'=>$_REQUEST['cantItem'],
            'idEmpresa'=>$_REQUEST['idEmpresa']
        );
        return response()->json(Menu::allMenus($value));
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : delMenu
     * Función           : Route::post('allMenu', 'Menu\MenuController@allMenu');
     * Parametros        : $_REQUEST
     * Devuelve          : nada
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function delMenu()
    {
        $data=array(
            'id'=>$_REQUEST['id'],
            'idEmpresa'=>$_REQUEST['idEmpresa'],
            'user'=>$_REQUEST['user']
        );

        return response()->json(Menu::delMenu($data));
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++


}

