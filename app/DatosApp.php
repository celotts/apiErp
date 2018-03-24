<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatosApp extends Model
{
    //

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : cambiaFechaPhp
     * Función           : cambia fecha formato php
     * Parametros        : fecha
     * Devuelve          :  fecha YYYY-mm-dd
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function cambiaFechaPhp($fecha)
    {

        # --------------------------------------
        # Cambia la fecha del formato dd/mm/dd a
        # la Fecha con Formato YYYY-mm-dd
        # --------------------------------------

        date_default_timezone_set('America/Caracas');
        $date = str_replace('/', '-', $fecha);
        return date('Y-m-d', strtotime($date));

    }

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : cambiaFechaPhp
     * Función           : cambia fecha formato mysql
     * Parametros        : fecha
     * Devuelve          :  fecha YYYY-mm-dd
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function cambiaFechaMysql($fecha)
    {

        #
        # Cambia la fecha del formato dd/mm/dd a
        # la Fecha con Formato YYYY-mm-dd
        #


        $date = str_replace('/', '-', $fecha);
        return date('Y-m-d', strtotime($date));

    }

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : cambiaFechaNormal
     * Función           : cambia fecha formato normal
     * Parametros        : fecha
     * Devuelve          :  fecha dd/mm/YYYY
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function cambiaFechaNormal($fecha)
    {
        #
        # Cambia la fecha del formato 0000-mm-dd a
        # la Fecha con Formato dd/mm/YYYY
        #

        date_default_timezone_set('America/Caracas');

        return date("d/m/Y", strtotime($fecha));

    }

    /*++++++++++++++++++++++++++++++++++++++++++++
    + Función para obtener el nro del dia con la
    + la fecha
    ++++++++++++++++++++++++++++++++++++++++++++*/
    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : DiaSemana
     * Función           : para obtener el dia
     *  con la fecha
     * Parametros        : fecha
     * Devuelve          : dia
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function DiaSemana($value)
    {
        # ------------------------------------------------
        # Devuelve el nro de dia de una fecha determinada
        # ------------------------------------------------
        $nrod= date('w',strtotime(self::cambiaFechaPhp($value)));
        if($nrod==0){
            return 'DOMINGO';
        }else{
            if($nrod==1){
                return 'LUNES';

            }
            if($nrod==2){
                return 'MARTES';

            }
            if($nrod==3){
                return 'MIÉRCOLES';

            }
            if($nrod==4){
                return 'JUEVES';

            }
            if($nrod==5){
                return 'VIERNES';

            }
            if($nrod==6){
                return 'SÁBADO';

            }

        }
    }

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : Meses
     * Función           : devuelve en mes
     * Parametros        : numro mes
     * Devuelve          : mes
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function Meses($nrod)
    {
        # ------------------------------------------------
        # Devuelve el mes
        # ------------------------------------------------

        if($nrod=='01'){
            return 'ENERO';
        }

        if($nrod=='02'){
            return 'FEBRERO';

        }

        if($nrod=='03'){
            return 'MARZO';

        }

        if($nrod=='04'){

            return 'ABRIL';

        }

        if($nrod=='05'){
            return 'MAYO';

        }

        if($nrod=='06'){
            return 'JUNIO';
        }

        if($nrod=='07'){
            return 'JULIO';

        }

        if($nrod=='08'){
            return 'AGOSTO';

        }

        if($nrod=='09'){
            return 'SEPTIEMBRE';

        }

        if($nrod=='10'){
            return 'OCTUBRE';

        }

        if($nrod=='11'){
            return 'NOVIEMBRE';

        }

        if($nrod=='12'){
            return 'DICIEMBRE';

        }


    }

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            :
     * Función           : Función completar espacios con
     *  cero recibe el valor ($value) y la cantidad de
     *  estacios ($cant)
     * Parametros        : value y cantidad
     * Devuelve          : numero relleno con cero
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function stringPad($value, $cant)
    {
        # ----------------------------------
        # Agrega cero a las izuerda a un nro
        # donde cant, es la logitud del nro
        # $value el nro a convertir
        return str_pad($value, $cant, "0", STR_PAD_LEFT);
    }

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : diasTranscurrido
     * Función           : obtener los dias trasncurrido
     * desde la fecha inicial hasta hoy
     * Parametros        : fecha
     * Devuelve          : dias transcurrido
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function diasTranscurrido($value){
        date_default_timezone_set('America/Caracas');

        $dias	= (strtotime($value) - strtotime(date('Y-m-d')))/86400;
        $dias 	= abs($dias);
        $dias = floor($dias);
        return $dias;

    }

    /*++++++++++++++++++++++++++++++++++++++++++++
    + Función para obtener l nombre del perfil
    + de un usuario
    ++++++++++++++++++++++++++++++++++++++++++++*/
    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : nombreperfil
     * Función           : para obtener l nombre del perfil
     *  de un usuario
     * Parametros        : nada
     * Devuelve          : nombre del perfil
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public function nombreperfil(){
        /*
            Prepara datos para el array fijo del menu
        */
        // Carga perfil del usuario
        $usuario= DB::table('usuarios')->whereId(Auth::user()->id)->first();

        // Carga Perfil

        $perfil=DB::table('perfiles')->whereId($usuario->perfil_id)->first();

        return strtoupper($perfil->nombre)	;

    }

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : sumaDiaFecha
     * Función           : suma dia a la fecha
     * Parametros        : fecha
     * Devuelve          : fecha d/m/y
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function sumaDiaFecha($value)
    {
        # suma dia a la fecha
        list($day,$mon,$year) = explode('/',$value['fecha']);
        return date('d/m/Y',mktime(0,0,0,$mon,$day+$value['dia'],$year));
    }

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : sumaDiaFechaYMD
     * Función           : suma dia a la fecha
     * Parametros        : fecha
     * Devuelve          : fecha y-m-d
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function sumaDiaFechaYMD($fecha,$ndia)
    {
        # suma dia a la fecha
        list($year,$mon,$day) = explode('-',$fecha);
        return date('Y-m-d',mktime(0,0,0,$mon,$day+$ndia,$year));
    }

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : restaDiaFechaYDM
     * Función           : resta dia a la fecha
     * Parametros        : fecha
     * Devuelve          : fecha y-m-d
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function restaDiaFechaYMD($fecha,$ndia)
    {
        # suma dia a la fecha
        list($year,$mon,$day) = explode('-',$fecha);
        return date("Y-m-d",mktime(0,0,0,$mon,$day-$ndia,$year));

    }

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : HoyYMD
     * Función           : obtener el dia de hoy formato
     *  YMD
     * Parametros        : nada
     * Devuelve          : hoy
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public function HoyYMD()
    {
        # ------------------------------
        # Devuelve fecha de hoy con
        # Format Y-M-D
        # ------------------------------
        return date('Y-m-d');
    }

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : HoyDMY
     * Función           : obtener el dia de hoy formato
     *  DMY
     * Parametros        : nada
     * Devuelve          : hoy
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public function HoyDMY()
    {
        # ------------------------------
        # Devuelve fecha de hoy con
        # Format D/M/Y
        # ------------------------------
        return date('d/m/Y');
    }

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : obtieneNroDiadeunFechaN
     * Función           : obtener Numero de dia de una
     *  Fecha formato N
     * Parametros        : nada
     * Devuelve          : numero dia
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function obtieneNroDiadeunFechaN($value)
    {
        # ------------------------------------------------
        # Devuelve el nro de dia de una fecha determinada
        # ------------------------------------------------
        $nrod= date('N',strtotime(self::cambiaFechaPhp($value)));
        if($nrod==0){
            return 0;
        }else{

            return $nrod;

        }
    }

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : diaFecha
     * Función           : obtiene el dia de una fecha
     *  formato dd/mm/yyyy
     * Parametros        : $fecha
     * Devuelve          : dia de una fecha
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function diaFecha($fecha)
    {
        return substr($fecha,8,2);
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : mesFecha
     * Función           : obtiene el mes de una fecha
     *  formato dd/mm/yyyy
     * Parametros        : $fecha
     * Devuelve          : mes de una fecha
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function mesFecha($fecha)
    {
        return substr($fecha,5,2);
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : anoFecha
     * Función           : obtiene el ano de una fecha
     *  formato dd/mm/yyyy
     * Parametros        : $fecha
     * Devuelve          : ano de una fecha
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function anoFecha($fecha)
    {
        return substr($fecha,0,4);
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++


    /*++++++++++++++++++++++++++++++++++++++++++++
    + Función para obtener Numero de dia de una
    + Fecha formato W
    ++++++++++++++++++++++++++++++++++++++++++++*/
    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : obtieneNroDiadeunFecha
     * Función           : obtener Numero de dia de una
     *   Fecha formato W
     * Parametros        : nada
     * Devuelve          : numero dia
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function obtieneNroDiadeunFecha($value)
    {
        # ------------------------------------------------
        # Devuelve el nro de dia de una fecha determinada
        # ------------------------------------------------
        $nrod= date('w',strtotime(self::cambiaFechaPhp($value)));
        if($nrod==0){
            return 0;
        }else{

            return $nrod;

        }
    }

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : nomIndicador
     * Función           : Devuelve el nombre del indicador segun su id
     * Parametros        : $id
     * Devuelve          : nombre
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function nomIndicador($ind)
    {
        if($ind==0){
            return 'BLOQUEADO';
        }
        if($ind==2){
            return 'ACTIVO++';
        }
        return 'ACTIVO';
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : indicadorBooleano
     * Función           : convierte valor numerico a valor booleano
     * Parametros        : $ind
     * Devuelve          : booleano
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function indicadorBooleano($ind)
    {
        if($ind==0){
            return false;
        }
        if($ind==2 || $ind==1){
            return true;
        }
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : nacionalidad
     * Función           : devuele el nombre de la nacionalidad
     * Parametros        : $id
     * Devuelve          : nombre
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function nacionalidad($id)
    {
        if($id=='1') {
            return 'VENEZOLANA';
        }
        if($id=='0') {
            return 'NO SELECCIONO';
        }
        return 'EXTRANJERA';
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : nameEstado
     * Función           : devuelve el nombre del estado
     * Parametros        :
     * Devuelve          :
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function nameEstado($estado)
    {
        if($estado==1){
            return 'BLOQUEADO';
        }
        return 'ACTIVO';
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++


}