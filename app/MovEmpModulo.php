<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class MovEmpModulo extends Model
{
    //
    protected $connection = 'db1';
    protected  $table = 'movempmodulos';
    protected $primaryKey = 'id';

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : RelacionEmpModulo
     * Función           : Relaciona Empresa con los Modulos
     *                     de la aplicacion x perfil
     * Parametros        : $value
     * Devuelve          : cursor
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function RelacionEmpModulo($value)
    {
        return DB::connection('db1')
            ->table('movempmodulos')
            ->selectRaw('*')
            ->where('idEmpresa','=',$value['idEmpresa'])
            ->where('idPerfil','=',$value['idPerfil'])
            ->get();
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++


}
