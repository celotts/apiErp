<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Auditor extends Model
{
    protected $connection = 'db1';
    protected  $table = 'auditors';
    protected $primaryKey = 'id';
    //
    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : trazas
     * Función           : Registra trazas del la ap
     * Parametros        : $value
     * Devuelve          : nada
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function trazas($value)
    {
        /**
         * Busca geoPosicion
         */
         $parsedgeoip  = self::geoPosition($_SERVER['REMOTE_ADDR']);
        /**
         * Fin
         */
        $data=new Auditor();
        $data->idEmpresa=$value['idEmpresa'];
        $data->accion=strtoupper($value['accion']);
        $data->dispositivo=self::tipoDispositivo();
        $data->fecha = DatosApp::cambiaFechaPhp(date('d/m/Y'));
        $data->hora = date("H:i:s");
        $data->ip=$_SERVER['REMOTE_ADDR'];
        $data->operacion=strtoupper($value['operacion']);
        $data->idRegProcesado=$value['idProcesado'];
        $data->country_code=$parsedgeoip->country_code;
        $data->country_name=$parsedgeoip->country_name;
        $data->latitude=$parsedgeoip->latitude;
        $data->longitude=$parsedgeoip->longitude;
        $data->metro_code=$parsedgeoip->metro_code;
        $data->region_code=$parsedgeoip->region_code;
        $data->region_name=$parsedgeoip->region_name;
        $data->time_zone=$parsedgeoip->time_zone;
        $data->registrado_por=$value['username'];
        $data->save();
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : geoPosition
     * Función           : Datos de la posicion del pc
     * Parametros        : ip
     * Devuelve          : array
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function geoPosition($ip)
    {
        $geoip = file_get_contents('http://freegeoip.net/json/' . $ip);
        return json_decode($geoip);

    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++

    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : tipoDispositivo
     * Función           : Detecta el tipo de dispositivo que procesa los datos
     * Parametros        :
     * Devuelve          :
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function tipoDispositivo()
    {
        $tablet_browser = 0;
        $mobile_browser = 0;
        $body_class = 'desktop';

        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $tablet_browser++;
            $body_class = "tablet";
        }

        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $mobile_browser++;
            $body_class = "mobile";
        }

        if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
            $mobile_browser++;
            $body_class = "mobile";
        }

        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
            'newt','noki','palm','pana','pant','phil','play','port','prox',
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
            'wapr','webc','winw','winw','xda ','xda-');

        if (in_array($mobile_ua,$mobile_agents)) {
            $mobile_browser++;
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
            $mobile_browser++;
            //Check for tablets on opera mini alternative headers
            $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
                $tablet_browser++;
            }
        }
        if ($tablet_browser > 0) {
// Si es tablet has lo que necesites
            return 'Tablet';
        }
        else if ($mobile_browser > 0) {
// Si es dispositivo mobil has lo que necesites
            return 'Mobil';
        }
        else {
// Si es ordenador de escritorio has lo que necesites
            return 'PC/LAPTOP';
        }
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++


}
