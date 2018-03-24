<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class MenuAsignadoModulo extends Model
{
    //
    protected $connection = 'db1';
    protected  $table = 'menuasignadomodulos';
    protected $primaryKey = 'id';

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : cargaMenuAsignadoModulo
     * Función           : Carga menu aignado al modulo
     * Parametros        : $value
     * Devuelve          : cursor
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function cargaMenuAsignadoModulo($value)
    {
        return DB::connection('db1')
            ->table('menuasignadomodulos')
            ->selectRaw('*')
            ->where('idMovEmpModulo','=',$value->id)
            ->get();
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++


}
