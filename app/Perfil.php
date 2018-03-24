<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use League\Flysystem\Exception;

class Perfil extends Model
{
    //
    protected $connection = 'db1';
    protected  $table = 'perfiles';
    protected $primaryKey = 'id';

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : searchNamePerfil
     * Función           : Busca el nombre del perfil
     *                     mediante del id
     * Parametros        : $id
     * Devuelve          : name
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function searchNamePerfil($id)
    {
        try{
            $data=Perfil::find($id);
            return $data->nombre;
        }catch (Exception $e){
            return 'No definido';
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
    public static function savePerfil($value)
    {
        if($value['id']==0){
            /**
             * Nuevo
             */
            $data= new  Perfil();
            $accion='INSERTA';
            $data->registrado_por=$value['user'];
        }else{
            /**
             * Editar
             */
            $accion='ACTUALIZA';
            $data=Perfil::find($value['id']);
            $data->modificado_por=$value['user'];
        }
        $data->nombre=strtoupper($value['nombre']);
        $data->nivel=$value['nivel'];
        $data->save();
        Auditor::trazas(array(
            'username'=>$value['user'],
            'idEmpresa'=>$value['idEmpresa'],
            'idProcesado'=>$data->id,
            'accion'=>$accion,
            'tabla'=>'PERFILES',
            'operacion'=>$accion.' EL PERFIL '.$value['nombre']
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
    public static function allPerfiles($value)
    {
        $data=DB::connection('db1')
            ->table('perfiles')
            ->selectRaw('*')
            ->where('nombre','like',$value['nombre'])
            ->orderBy('nombre','Asc')
            ->get();
        $dat=array();
        foreach ($data as $item) {
            array_push($dat,array(
                'id'=>$item->id,
                'nombre'=>strtoupper($item->nombre),
                'nivel'=>$item->nivel,
                'estado'=>$item->estado,
                'nameEstado'=> DatosApp::nameEstado($item->estado)
            ));
        }
        return array('data'=>$dat,'totRegistro'=>self::countPerfil($value));
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
    public static function countPerfil($value)
    {
        return DB::connection('db1')
            ->table('perfiles')
            ->selectRaw('*')
            ->where('nombre','like',$value['nombre'])
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
            ->table('perfiles')
            ->selectRaw('*')
            ->where('id','=',$value['id'])
            ->get();
        foreach ($data as $item) {
            $dataa1=DB::connection('db1')
                ->table('movempmodulos')
                ->selectRaw('*')
                ->where('idPerfil','=',$value['id'])
                ->get();
            foreach ($dataa1 as $item1) {
                try{

                    $delReg=MovEmpModulo::find($item1->id);
                    try{
                        $dtax=DB::connection('db1')
                            ->table('menuasignadomodulos')
                            ->selectRaw('*')
                            ->where('idMovEmpModulo','=',$delReg->id)
                            ->get();
                        foreach ($dtax as $itemx) {
                            MenuAsignadoModulo::find($itemx->id)->delete();
                        }
                    }catch (Exception $e){}
                    $delReg->delete();
                }catch (Exception $e){}
            }
            Auditor::trazas(array(
                'username'=>$value['user'],
                'idEmpresa'=>$value['idEmpresa'],
                'idProcesado'=>$item->id,
                'accion'=>'ELIMINAR',
                'tabla'=>'PERFILES',
                'operacion'=>'ELIMINAR'.' EL PERFIL '.$item->nombre
            ));
            Perfil::find($item->id)->delete();
        }
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++


}
