<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use League\Flysystem\Exception;

class Modulo extends Model
{
    //
    protected $connection = 'db1';
    protected  $table = 'modulos';
    protected $primaryKey = 'id';

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : nameModule
     * Función           : busca nombre del modulo mediante id
     * Parametros        : $id
     * Devuelve          : nombre
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function nameModulo($id)
    {
        try{

            return strtoupper(Modulo::find($id)->nombre);

        }catch (Exception $e){
            return '';
        }
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : EstadoModulo
     * Función           : Busca el estado del modulo
     * Parametros        : $idModulo
     * Devuelve          : 0 Activo 1 Bloqueado
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function EstadoModulo($idModulo)
    {
        try{

            return Modulo::find($idModulo)->estado;

        }catch (Exception $e){
            return 1;
        }
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++


    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : modulos
     * Función           : Carga los modulos por empresa
     *                     y perfil
     * Parametros        : $idvalue
     * Devuelve          : cursor
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function modulos($value)
    {
        $data=MovEmpModulo::RelacionEmpModulo($value);
        $dat=array();
        foreach ($data as $item) {
            /**
             * Filtra los modulo activo (0 == Activo)
             */
            if((self::EstadoModulo($item->idModulo))==0) {
                array_push($dat, array(
                    'id' => $item->id,
                    'idEmpresa' => $item->idEmpresa,
                    'idModulo' => $item->idModulo,
                    'icono'=>'',
                    'nombre' => self::nameModulo($item->idModulo),
                    'opcMenu'=>Menu::cargaMenuApp($item)
                ));
            }
        }
        return $dat;
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++


}
