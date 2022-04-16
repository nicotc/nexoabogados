<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Events\SubscriptionCreated;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     *
     *  @OA\Get(
     *      path="/api/v1/panel/list_active_subscription  || /api/v1/panel/list_inactive_subscription",
     *      summary="Mostrar suscripciones",
     *      description="Esta ruta list_active_subscription muestra todas las suscripciones activas y list_inactive_subscription las inactivas",
     *      operationId="index-Subscription",
     *      tags={"Panel"},
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="pagina a mostrar por defecto es 1",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              format="int32"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          description="resultados por pagina por defecto 20",
     *          required=false,
     *           @OA\Schema(
     *              type="integer",
     *              format="int32"
     *           )
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Mostrar todos las suscripciones."
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description="Ha ocurrido un error."
     *    )
     * )
     */

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 20;
        $page = $request->page && $request->page > 0 ? $request->page : 1;
        $skip = ($page - 1) * $limit;
        if(strstr(url()->current(), 'list_active_subscription')){
            $status = 1;
        }
        elseif(strstr(url()->current(), 'list_inactive_subscription')){
            $status = 0;
        }
        $Subscription = Subscription::where('status', '=', $status)->skip($skip)->take($limit)->get();
        return response()->json($Subscription, 200);
    }

    /**
     * @OA\Get(
     *    path="/api/v1/panel/show_subscription/{id}",
     *   summary="Mostrar una suscripción",
     *  description="Mostrar una suscripción",
     *  operationId="show-Subscription",
     * tags={"Panel"},
     * @OA\Parameter(
     *         name="id",
     *        in="path",
     *       description="id de Subscription",
     *      required=true,
     *     @OA\Schema(
     *          type="integer",
     *         format="int64"
     *    )
     *  ),
     * @OA\Response(
     *    response=200,
     *   description="Mostrar una suscripción."
     * ),
     * @OA\Response(
     *   response="default",
     *  description="No se encuentra el suscripción con id."
     * )
     * )
     */

    public function show($id)
    {
        $Subscription = Subscription::find($id);
        if (!$Subscription) {
            return response()->json(['error' => 'No se encuentra el suscripción con id ' . $id], 404);
        }
        return response()->json($Subscription, 200);
    }


    /**
     * @OA\Post(
     *  path="/api/v1/panel/cancel_subscription",
     *  tags={"Panel"},
     * summary="Cancelar una suscripción",
     * description="Cancelar una suscripción",
     * operationId="cancel-Subscription",
     * @OA\RequestBody(
     *   required=true,
     *  @OA\MediaType(
     *   mediaType="application/json",
     *  @OA\Schema(
     *     type="object",
     *    @OA\Property(
     *     property="id",
     *    description="id de Subscription",
     *   type="integer"
     *   ),
     *  @OA\Property(
     *    property="reason",
     *   description="motivo de cancelacion",
     *  type="string"
     *  )
     *  )
     * )
     * ),
     * @OA\Response(
     *  response=200,
     * description="Subscription array."
     * ),
     * @OA\Response(
     * response="default",
     * description="No se encuentra el suscripción con id"
     * )
     * )
     */

    public function cancel_Subscription(Request $request){


        $id= $request->id;
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);


        if ($validator->fails()) {
            return response()->json([
              'errors' => $validator->errors(),
              'status' => false,
            ], 401);
        }


        $Subscription = Subscription::find($id);

        if (!$Subscription) {
            return response()->json(['error' => 'No se encuentra el suscripción con id ' . $id], 404);
        }
        $Subscription->status = 0;
        $Subscription->save();
        return response()->json($Subscription, 200);
    }



    /**
     *  @OA\Get(
     *     path="/api/v1/panel/attempt_Subscription/{id}/{no?}",
     *    summary="intentar un suscripción",
     *  description="Intentar una suscripción  | 'id' requerido | 'no?' opcional  por defecto 'no'. En caso de querer intentarlo sin espera, agregar cualquier valor",
     * operationId="attempt-Subscription",
     * tags={"Panel"},
     * @OA\Parameter(
     *        name="id",
     *      in="path",
     *    description="id de Subscription",
     *  required=true,
     * @OA\Schema(
     *  type="integer",
     * format="int64"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="intentar un Subscription."
     * ),
     * @OA\Response(
     * response="default",
     * description="Ha ocurrido un error."
     * )
     * )
     */

    public function attempt_Subscription($id_subscription, $directo = "no"){
        $Subscription = Subscription::find($id_subscription);
        if(!$Subscription){
            return response()->json(['error' => 'No se encuentra el suscripción con id " . $id_subscription'], 200);
        }
        if($Subscription->status == 0){
            $random = rand(0,1);
            event(new SubscriptionCreated($id_subscription, $random, $directo));
            return response()->json(['success' => 'Pronto se procesará el pago'], 200);
        }
        return response()->json(['error' => 'La suscripción ya esta activa'], 200);
    }



    /**
     * @OA\Post(
     * path="/api/v1/abogados/create_subscription",
     * tags={"Abogados"},
     * summary="Crear una suscripción",
     * description="Crear una suscripción",
     * operationId="create-Subscription",
     * @OA\RequestBody(
     *  required=true,
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(
     * type="object",
     * @OA\Property(
     * property="id_user",
     * description="id de usuario",
     * type="integer"
     * ),
     * @OA\Property(
     * property="id_plan",
     * description="id de plan",
     * type="integer"
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Crear una suscripción."
     * ),
     * @OA\Response(
     * response="default",
     * description="Ha ocurrido un error."
     * )
     * )
     */



    public function store(Request $request)
    {
        $plan = ['basic', 'premium'];
        $user = Auth::id();
        $status = 0;
        $end_date = date('Y-m-d', strtotime('+1 month'));

         $validator = Validator::make($request->all(), [
            'plan' => 'required|in:'.implode(',', $plan),
         ]);


         if ($validator->fails()) {
            return response()->json([
              'errors' => $validator->errors(),
              'status' => false,
            ], 401);
        }

        $input = $request->all();
        $input['user_id'] = $user;
        $input['status'] = $status;
        $input['ends_at'] = $end_date;
        $Subscription = Subscription::create($input);

        $random = rand(0,1);

        event(new SubscriptionCreated($Subscription->id, $random));


        return response()->json($Subscription, 201);
    }


    /**
     * @OA\Get(
     * path="/api/v1/abogados/actual_subscription",
     * tags={"Abogados"},
     * summary="Obtener la suscripción actual",
     * description="Obtener la suscripción actual",
     * operationId="actual-Subscription",
     * @OA\Response(
     * response=200,
     * description="Obtener la suscripción actual."
     * ),
     * @OA\Response(
     * response="default",
     * description="Ha ocurrido un error."
     * )
     * )
     */


    public function actual_subscription(){


        $user = Auth::id();


        $Subscription = Subscription::where('user_id', $user)->where('status', '=', 1)->get();


        if (!$Subscription) {
            return response()->json(['error' => 'No se encuentra la suscripción para su usuario '], 404);
        }
        return response()->json($Subscription, 200);
    }



    /**
     * @OA\Put(
     * path="/api/v1/abogados/update_subscription",
     * tags={"Abogados"},
     * summary="Actualizar una suscripción",
     * description="Actualizar una suscripción",
     * operationId="update-Subscription",
     * @OA\RequestBody(
     * required=true,
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(
     * type="object",
     * @OA\Property(
     * property="id_subscription",
     * description="id de Subscription",
     * type="integer"
     * ),
     * )
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Actualizar un Subscription."
     * ),
     * @OA\Response(
     * response="default",
     * description="Ha ocurrido un error."
     * )
     * )
     */

    public function update(Request $request)
    {

        $id = $request->id;
        $plan = ['basic', 'premium'];
        $user = Auth::id();
        $status = 0;
        $end_date = date('Y-m-d', strtotime('+1 month'));

         $validator = Validator::make($request->all(), [
            'plan' => 'required|in:'.implode(',', $plan),
            'id' => 'required|exists:subscriptions,id',

         ]);


         if ($validator->fails()) {
            return response()->json([
              'errors' => $validator->errors(),
              'status' => false,
            ], 401);
        }

        $input = $request->all();
        //$input['user_id'] = $user;
        //$input['status'] = $status;
        //$input['ends_at'] = $end_date;

        $subscription = Subscription::findOrFail($id);

        if(!$subscription->user_id == $user){
            return response()->json(['error' => 'No se puede actualizar una suscripción de otro usuario'], 404);
        }

        if($subscription->status == 1){
            return response()->json(['error' => 'No se puede actualizar una suscripción activa'], 404);
        }

        $random = rand(0,1);

        event(new SubscriptionCreated($subscription->id, $random));

       // $subscription->update($input);
        return response()->json($subscription, 200);
    }


    /**
     * @OA\Delete(
     * path="/api/v1/abogados/delete_subscription",
     * tags={"Abogados"},
     * summary="Eliminar una suscripción",
     * description="Eliminar una suscripción",
     * operationId="delete-Subscription",
     * @OA\RequestBody(
     * required=true,
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(
     * type="object",
     * @OA\Property(
     * property="id_subscription",
     * description="id de Subscription",
     * type="integer"
     * ),
     * )
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Eliminar una suscripción."
     * ),
     * @OA\Response(
     * response="default",
     * description="Ha ocurrido un error."
     * )
     * )
     */


    public function destroy(Request $request)
    {


        $user = Auth::id();

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:subscriptions,id',

         ]);


         if ($validator->fails()) {
            return response()->json([
              'errors' => $validator->errors(),
              'status' => false,
            ], 401);
        }
        $id = $request->id;


        $user = Auth::id();
        $subscription = Subscription::findOrFail($id);
        if($subscription->user_id != $user){
            return response()->json(['error' => 'No se encuentra el la suscripción para su usuario '], 404);
        }
        $subscription->delete();
        return response()->json(null, 204);
    }





    public function show_attempts($id)
    {
        $subscription = Subscription::findOrFail($id);
        $attempts = $subscription->attempts;
        return response()->json($attempts, 200);
    }
}
