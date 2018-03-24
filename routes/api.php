<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

//Route::post('register', 'AuthController@register');
Route::post('imagen', 'ApiLogin\LoginController@imagen');
Route::post('BuscaEmpresa', 'Empresa\EmpresaController@BuscaEmpresa');
Route::post('login', 'ApiLogin\LoginController@loguear');
Route::post('sosRecoverPasswordMaestro', 'ApiLogin\LoginController@sosRecoverPasswordMaestro');
//Route::post('recover', 'AuthController@recover');
Route::group(['middleware' => ['jwt.auth']], function() {
    Route::post('savePerfil', 'Perfil\PerfilController@savePerfil');
    Route::post('allPerfil', 'Perfil\PerfilController@allPerfil');
    Route::post('delPerfil', 'Perfil\PerfilController@delPerfil');

    /**
     * Menu
     */
    Route::post('saveMenu', 'Menu\MenuController@saveMenu');
    Route::post('allMenu', 'Menu\MenuController@allMenu');
    Route::post('delMenu', 'Menu\MenuController@delMenu');

});

