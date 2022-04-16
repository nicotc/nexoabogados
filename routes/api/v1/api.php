<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Route::get('/', function () {
//     return "Hello World";
// });


Route::prefix("auth")->group(function () {
    Route::post("login", "App\Http\Controllers\AuthController@login");
    Route::post("register", "App\Http\Controllers\AuthController@register");
    Route::group(['middleware' => 'auth:api'], function() {
        Route::post("logout", "App\Http\Controllers\AuthController@logout");
    });
});



//  Crear suscripción,                                                                              store                                               listo
//  Ver la suscripción actual                                                                       show                                                listo
//  Actualizar suscripción de pago                                                                  update                                              listo
//  Eliminar mi suscripción actual.                                                                 destroy                                             listo


Route::prefix("abogados")->group(function () {
    Route::group(['middleware' => ['role:abogado','auth:api']], function() {
        Route::post("create_subscription", "App\Http\Controllers\subscriptionController@store");
        Route::get("actual_subscription", "App\Http\Controllers\subscriptionController@actual_subscription");
        Route::put("update_subscription", "App\Http\Controllers\subscriptionController@update");
        Route::delete("delete_subscription", "App\Http\Controllers\subscriptionController@destroy");
    });
});






//  Listar Subscriptiones activas                                                                   index                                               listo
//  Listar Subscriptiones desactivadas                                                              index                                               listo
//  Ver detalle de una suscripción                                                                  show                                                listo
//  Cancelar una suscripción                                                                        cancel_Subscription                                 listo
//  Reintentar pago de una suscripción Manual : si el pago falla                                    retry_Subscription

Route::prefix("panel")->group(function () {
     Route::group(['middleware' => ['role:panel','auth:api']], function() {
        Route::get('list_active_subscription', "App\Http\Controllers\subscriptionController@index");
        Route::get('list_inactive_subscription', "App\Http\Controllers\subscriptionController@index");
        Route::get('show_subscription/{id}', "App\Http\Controllers\subscriptionController@show");
        Route::post('cancel_subscription', "App\Http\Controllers\subscriptionController@cancel_subscription");
        Route::get('attempt_subscription/{id_subscription}/{directa?}', "App\Http\Controllers\subscriptionController@attempt_subscription");
    });
});





//Route::resource('users', App\Http\Controllers\API\UserController::class);



