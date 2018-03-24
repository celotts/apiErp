<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use League\Flysystem\Exception;

class Empresa extends Model
{
    //
    protected $connection = 'db1';
    protected  $table = 'empresas';
    protected $primaryKey = 'id';

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : BuscaEmpresa
     * Función           : Busca empresa
     * Parametros        : nada
     * Devuelve          : datos
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function BuscaEmpresa()
    {
        $cant=self::cuentaEmpresa();
        if($cant==1) {
            $data = DB::connection('db1')
                ->table('empresas')
                ->selectRaw('*')
                ->where('estado', '=', 0)
                ->get();
            foreach ($data as $item) {
                return array(
                    'id'=>$item->id,
                    'nombre'=>$item->nombre,
                    'rut'=>$item->rut
                );
            }
        }
        if($cant==0){
            return array(
                'id'=>'0',
                'nombre'=>'NO HAY EMPRESAS',
                'rut'=>''
            );
        }
        if($cant>1){
            return array(
                'id'=>'0',
                'nombre'=>'SIN SELECCIONAR',
                'rut'=>''
            );
        }
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : cuentaEmpresa
     * Función           : Cuenta la cantidad de empresa
     * Parametros        : nada
     * Devuelve          : cantidad
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function cuentaEmpresa()
    {
        return DB::connection('db1')
            ->table('empresas')
            ->selectRaw('*')
            ->where('estado','=',0)
            ->count();
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++


    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : nomEmpresaId
     * Función           : Busca el nombre de la empresa
     *                     x id
     * Parametros        : $idEmpresa
     * Devuelve          : nombre
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function nomEmpresaID($idEmpresa)
    {
        try{
            return strtoupper(Empresa::find($idEmpresa)->nombre);
        }catch (Exception $e){
            return'NO CREADA';
        }

    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : rutEmpresa
     * Función           : Busca Rut empresa x idEmpresa
     * Parametros        : $idEmpresa
     * Devuelve          : rut
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function rutEmpresa($idEmpresa)
    {
        return Empresa::find($idEmpresa)->rut;
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++


    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : empresaCombo
     * Función           : Carga las empresa x usuario
     * Parametros        : $idUsuario
     * Devuelve          : cursor
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function empresaCombo($idPerfil)
    {
        $data=MovPerfilEmpresa::relacionPerfilEmpresa($idPerfil);
        $dat=array();
        $sw=0;
        //Recorre Aray $data
        foreach ($data as $key){
            if($sw==0){
                array_push($dat,array(
                    'id'=>0,
                    'idEmpresa'=>0,
                    'idPerfil'=>$key->idPerfil,
                    'nombre'=>'Empresa: [SELECCIONAR]',
                    'rut'=>''
                ));
                $sw=1;
            }
            array_push($dat,array(
                'id'=>$key->id,
                'idEmpresa'=>$key->idEmpresa,
                'idPrfil'=>$key->idPerfil,
                'nombre'=>'Empresa: '.self::nomEmpresaID($key->idEmpresa),
                'rut'=>self::rutEmpresa($key->idEmpresa),
            ));
        }
        return $dat;
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++


}
