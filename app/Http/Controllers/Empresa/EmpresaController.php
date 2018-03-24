<?php

namespace App\Http\Controllers\Empresa;

use App\Empresa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmpresaController extends Controller
{
    //
    /*+++++++++++++++++++++++++++++++++++++++++++++++++
     * Creado por        : FC Quality c.a.
     * Desarrollado por  : Ing. Msc. Carlos Lott
     * Nombre            : BuscaEmpresa
     * Función           : Busca empresa App
     * Parametros        : Nada
     * Devuelve          : datos Empresa
     *++++++++++++++++++++++++++++++++++++++++++++++++*/
    public static function BuscaEmpresa()
    {
        return response()->json(Empresa::BuscaEmpresa());
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++


}
