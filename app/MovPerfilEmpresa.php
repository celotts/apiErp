<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class MovPerfilEmpresa extends Model
{
    //
    protected $connection = 'db1';
    protected  $table = 'movperfilempresas';
    protected $primaryKey = 'id';

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : relacionUsuarioEmpresa
     * Función           : Relacion de uno a varios de
     *                     usuario x empresa
     * Parametros        : $idUsuario
     * Devuelve          : cursor
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function relacionPerfilEmpresa($idPerfil)
    {
        return DB::connection('db1')
            ->table('movperfilempresas')
            ->selectRaw('*')
            ->where('idPerfil','=',$idPerfil)
            ->get();
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++
}
