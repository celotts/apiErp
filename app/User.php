<?php

namespace App;

use DB;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Hash;
use League\Flysystem\Exception;

class User extends Authenticatable
{
    use Notifiable;
    protected $connection = 'db1';
    protected  $table = 'usuarios';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : Foto
     * Función           : Busca la foto del usuario
     * Parametros        : $user
     * Devuelve          : foto
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function Foto($user)
    {
        try {
            $data = DB::connection('db1')
                ->table('usuarios')
                ->selectRaw('*')
                ->where('email', '=', $user)
                ->get();
            foreach ($data as $key) {
                return array('foto' => $key->foto,'empresa'=>Empresa::empresaCombo($key->idPerfil));
            }
            return array('foto' => 'sinfoto.png');
        }catch(Exception $e){
            return '';
        }


    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++


    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : Loguear
     * Función           : Busca usuario y passwor
     * Parametros        : $value
     * Devuelve          : cursor
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function Loguear($value)
    {
        $data=DB::connection('db1')
            ->table('usuarios')
            ->selectRaw('*')
            ->where('email','=',$value['email'])
            ->where('password','=',bcrypt($value['password']))
            ->get();
        $dat=array();
        foreach ($data as $key){
            array_push($dat, array(
                'id'=>$key->id,
                'email'=>$key->email,
                'nombre'=>$key->nombres
            ));
        }

        return $dat;
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : sosRecoverPasswordMaestro
     * Función           : Recupera la clave maestra de la aplicacion
     * Parametros        : $vale
     * Devuelve          : nada
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function sosRecoverPasswordMaestro()
    {
        $data=new User();
        $data->email='USUARIOMAESTRO';
        $data->password=bcrypt(Hash::make('*1234asdfSOS*'));
        $data->nombres='USUARIOSOS';
        $data->save();
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : intentoFallido
     * Función           : Cuenta los intentos falidos,
     *                     cuando el usuario llega a 10
     *                     intentos fallido, se bloequea
     *                     la cuenta
     * Parametros        : $user
     * Devuelve          : nada
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function intentoFallido($value)
    {
        try{
            $data=DB::connection('db1')
                ->table('usuarios')
                ->selectRaw('*')
                ->where('email','=',$value['email'])
                ->get();
            foreach ($data as $key) {
                $dat=User::find($key->id);
                if (($dat->intentoFallido += 1) > 10) {
                    return 10;
                }
                if($dat->intentoFallido==10){
                    $dat->FechaUltBloqueo=DatosApp::cambiaFechaPhp(date('d/m/Y'));
                    Auditor::trazas(array(
                        'username'=>$value['email'],
                        'idEmpresa'=>$value['idEmpresa'],
                        'idProcesado'=>$dat->id,
                        'accion'=>'BLOQUEADO',
                        'tabla'=>'USUARIOS',
                        'operacion'=>'EL USUARIO FUE BLOQUEADO POR HABER TENIDO MAS DE 10 INTENTOS FALLIDOS',
                        'dispositivo'=>'ESCRITORIO'
                    ));
                }
                $dat->save();
            }
            return 1;
        }catch (Exception $e){
            return array('codigo'=>98,'mensaje'=>$e);
        }

    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : EstadoUser
     * Función           : Revisa el usuario estado del usuario
     *                     donde 0 Activo (Acceso a la Aplicacion)
     *                           1 Suspendido (Acceso Restringido)
     *                           2 de Baaja
     * Parametros        : $user
     * Devuelve          : 0, 1 o 2
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function EstadoUser($email)
    {
        try{
            $data=DB::connection('db1')
                ->table('usuarios')
                ->selectRaw('*')
                ->where('email','=',$email)
                ->get();
            foreach ($data as $key) {
                if ($key->estado == 1) {
                    /**
                     * Usuario Suspendido
                     */
                    return 1;
                }
                if ($key->estado == 2) {
                    /**
                     * Usuario de baja
                     */
                    return 2;
                }
            }
            /**
             * Usuario Activo
             */
            return 0;
        }catch (Exception $e){
            return array('codigo'=>100,'mensaje'=>$e);
        }
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : datosUsuario
     * Función           : Busca los datos del usuario
     * Parametros        : $value
     * Devuelve          : cursor
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function datosUsuario($value)
    {
        $data=DB::connection('db1')
            ->table('usuarios')
            ->selectRaw('*')
            ->where('email','=',$value['email'])
            ->get();
        $dat=array();
        foreach ($data as $item) {
            $reg=User::find($item->id);
            $reg->intentoFallido=0;
            $reg->save();
            array_push($dat,array(
                'id'=>$item->id,
                'email'=>$item->email,
                'nombre'=>$item->nombres,
                'idPerfil'=>$item->idPerfil,
                'perfil'=>Perfil::searchNamePerfil($item->idPerfil),
                'foto'=>$item->foto,
                'estado'=>$item->estado,
                'new'=>$item->new,
                'edit'=>$item->edit,
                'del'=>$item->del,
                'preguntaSeguridad'=>$item->pregunta_seguridad,
                'fechaUltBloqueo'=>DatosApp::cambiaFechaNormal($item->FechaUltBloqueo),
                'fechaUltBloqueoYMD'=>$item->FechaUltBloqueo,
                'fechaUltSuspencion'=>DatosApp::cambiaFechaNormal($item->FechaUltSuspencion),
                'fechaUltSuspencionYMD'=>$item->FechaUltSuspencion,
                'fechaUltBaja'=>DatosApp::cambiaFechaNormal($item->FechaUltBaja),
                'fechaUltBajaYMD'=>$item->FechaUltBaja,
                'modulos'=>Modulo::modulos(array(
                    'idEmpresa'=>$value['idEmpresa'],
                    'idPerfil'=>$item->idPerfil))
            ));
        }
        return $dat;
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++


}
