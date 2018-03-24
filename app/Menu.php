<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use League\Flysystem\Exception;

class Menu extends Model
{
    //
    protected $connection = 'db1';
    protected  $table = 'menus';
    protected $primaryKey = 'id';

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : cargaMenuApp
     * Función           : Carga menu del modulo
     * Parametros        : $value
     * Devuelve          : cursor
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function cargaMenuApp($value)
    {
        $dat=array();
        $data=MenuAsignadoModulo::cargaMenuAsignadoModulo($value);
        foreach ($data as $item) {
            /**
             * Carga Menu Padre
             */
            $data1=self::datosMenu(array('idMenuPadre'=>0,'idMenu'=>$item->idMenu));

            foreach ($data1 as $item1) {
                array_push($dat,array(
                    'id'=>$item1->id,
                    'nombre'=>strtoupper($item1->nombre),
                    'controlador'=>'',
                    'expande'=>false,
                    'active'=>false,
                    'menuAsignado'=>$value,
                    'data1'=>$data1,
                    'menuHijo'=>self::cargaMenuHijo($item1->id)
                ));
            }
        }
        return $dat;
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : $datosMenu
     * Función           : Carga los datos del menu
     * Parametros        : $value
     * Devuelve          : cursor
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function datosMenu($value)
    {
        return DB::connection('db1')
            ->table('menus')
            ->selectRaw('*')
            ->where('idMenuPadre','=',$value['idMenuPadre'])
            ->where('id','=',$value['idMenu'])
            ->where('estado','=',0)
            ->orderBy('nombre')
            ->get();
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : $datosMenu
     * Función           : Carga los datos del menu
     * Parametros        : $value
     * Devuelve          : cursor
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function datosMenuHijo($idMenuPadre)
    {
        return DB::connection('db1')
            ->table('menus')
            ->selectRaw('*')
            ->where('idMenuPadre','=',$idMenuPadre)
            ->where('estado','=',0)
            ->orderBy('nombre')
            ->get();
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++


    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : cargaMenuHijo
     * Función           : Carga Menu hijo del modulo
     * Parametros        : $idMenuPadre
     * Devuelve          : Cursor
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function cargaMenuHijo($idMenuPadre)
    {
        /**
         * Carma Menu Hijo
         */
        try {
            $data = self::datosMenuHijo($idMenuPadre);
            $dat = array();
            foreach ($data as $item1) {
                array_push($dat, array(
                    'id' => $item1->id,
                    'icono'=>$item1->icono,
                    'nombre' => strtoupper($item1->nombre),
                    'expande'=>false,
                    'active'=>false,
                    'controlador' => $item1->controlador,
                ));
            }
            return $dat;
        }catch (Exception $e){
            return [];
        }
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : savePerfil
     * Función           : Save Perfil
     * Parametros        : $value
     * Devuelve          : nada
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function saveMenu($value)
    {
        if($value['id']==0){
            /**
             * Nuevo
             */
            $data= new  Menu();
            $accion='INSERTA';
            $data->registrado_por=$value['user'];
        }else{
            /**
             * Editar
             */
            $accion='ACTUALIZA';
            $data=Menu::find($value['id']);
            $data->modificado_por=$value['user'];
        }
        $data->nombre=strtoupper($value['nombre']);
        $data->controlador=$value['controlador'];
        $data->icono=$value['icono'];
        $data->idMenuPadre=$value['tipoMenu'];
        $data->save();
        Auditor::trazas(array(
            'username'=>$value['user'],
            'idEmpresa'=>$value['idEmpresa'],
            'idProcesado'=>$data->id,
            'accion'=>$accion,
            'tabla'=>'MENUS',
            'operacion'=>$accion.' EL MENU '.$value['nombre']
        ));
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            :
     * Función           :
     * Parametros        :
     * Devuelve          :
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function allMenus($value)
    {
        $data=DB::connection('db1')
            ->table('menus')
            ->selectRaw('*')
            ->where('nombre','like',$value['nombre'])
            //->where('controlador','like',$value['controlador'])
            //->where('icono','like',$value['icono'])
            ->orderBy('nombre','Asc')
            ->get();
        $dat=array();
        foreach ($data as $item) {
            array_push($dat,array(
                'id'=>$item->id,
                'nombre'=>strtoupper($item->nombre),
                'controlador'=>$item->controlador,
                'tipoMenu'=>$item->idMenuPadre,
                'icono'=>$item->icono,
                'estado'=>$item->estado,
                'nameEstado'=> DatosApp::nameEstado($item->estado)
            ));
        }
        return array('data'=>$dat,'totRegistro'=>self::countMenu($value));
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : countPerfil
     * Función           : Cuenta la cantidad de perfil
     * Parametros        : $value
     * Devuelve          :  cursor
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function countMenu($value)
    {
        return DB::connection('db1')
            ->table('menus')
            ->selectRaw('*')
            ->where('nombre','like',$value['nombre'])
            ->where('controlador','like',$value['controlador'])
            ->where('icono','like',$value['icono'])
            ->count();
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : delPerfil
     * Función           : Elimina el perfil y sus relaciones
     * Parametros        : $value
     * Devuelve          : nada
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function delPerfil($value)
    {
        $data=DB::connection('db1')
            ->table('menus')
            ->selectRaw('*')
            ->where('id','=',$value['id'])
            ->get();
        $delReg='';
        foreach ($data as $item) {
            $delReg=Menu::find($item->id);
            $dtax=DB::connection('db1')
                ->table('menuasignadomodulos')
                ->selectRaw('*')
                ->where('idMenu','=',$delReg->id)
                ->get();
            foreach ($dtax as $itemx) {
                MenuAsignadoModulo::find($itemx->id)->delete();
            }
            $delReg->delete();
        }

        Auditor::trazas(array(
            'username'=>$value['user'],
            'idEmpresa'=>$value['idEmpresa'],
            'idProcesado'=>$item->id,
            'accion'=>'ELIMINAR',
            'tabla'=>'MENUS',
            'operacion'=>'ELIMINAR'.' EL MENU '.$item->nombre
        ));


    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++
    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : allMenuPadre
     * Función           : Carga solo Menu Padre
     * Parametros        : nada
     * Devuelve          : cursor
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function allMenuPadre()
    {
        return DB::connection('db1')
            ->tble('menus')
            ->selectRaw('*')
            ->where('idMenuPadre','=',0)
            ->get();
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

}
