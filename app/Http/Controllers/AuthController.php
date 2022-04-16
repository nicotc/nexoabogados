<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ModelHasRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;


/**
* @OA\Info(title="nexoabogados", version="1.0")
*
* @OA\Server(url="http://nexoabogados.test/")
*
* @OA\PathItem(path="/api/v1")
*/

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *    path="/api/v1/auth/login",
     *   tags={"Auth"},
     *   summary="Login",
     *   description="Login de usuario en el sistema",
     *   @OA\RequestBody(
     *     required=true,
     *    @OA\MediaType(
     *        mediaType="application/json",
     *       @OA\Schema(
     *          @OA\Property(
     *             property="email",
     *            type="string",
     *           description="Email"
     *         ),
     *        @OA\Property(
     *            property="password",
     *           type="string",
     *          description="Password"
     *       )
     *    )
     *  )
     * ),
     * @OA\Response(
     *   response=200,
     *  description="Success",
     * @OA\MediaType(
     *        mediaType="application/json",
     *      @OA\Schema(
     *         @OA\Property(
     *            property="token",
     *           type="string",
     *         description="Token"
     *      )
     *   )
     * )
     * ),
     * @OA\Response(
     *  response=401,
     * description="Unauthorized",
     * @OA\MediaType(
     *       mediaType="application/json",
     *     @OA\Schema(
     *        @OA\Property(
     *          property="error",
     *         type="string",
     *       description="Error"
     *    )
     * )
     * )
     * )
     * )
     */




    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');


        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->accessToken;
            return response()->json([
                'user' => $user,
                'token' => $token], 200);
        } else {
            return response()->json(['error' => 'Usuario o clave incorrectos'], 401);
        }
    }



    /**
     * @OA\Post(
     *   path="/api/v1/auth/register",
     *   tags={"Auth"},
     *   summary="Registro de usuario",
     *   description="Registro de usuario en el sistema",
     *   @OA\RequestBody(
     *      required=true,
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="Name"
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  description="Email"
     *              ),
     *              @OA\Property(
     *                  property="password",
     *                  type="string",
     *                  description="Password"
     *               ),
     *          )
     *    )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  description="Message"
     *              )
     *          )
     *      )
     *  ),
     *  @OA\Response(
     *      response=401,
     *      description="Unauthorized",
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  description="Error"
     *              )
     *           )
     *     )
     *  )
     * )
     */



    public function register(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
         ]);


         if ($validator->fails()) {
            return response()->json([
              'errors' => $validator->errors(),
              'status' => false,
            ], 401);
        }



        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);


        ModelHasRole::create([
            'role_id' => 1,
            'model_id' => $user->id,
            'model_type' => 'App\Models\User',
        ]);


        $token = $user->createToken('MyApp')->accessToken;

        return response()->json(['user' => $user, 'token' => $token], 200);
    }


    /**
     * @OA\post(
     *  path="/api/v1/auth/logout",
     * tags={"Auth"},
     * summary="Salir del sistema",
     * description="Salir del sistema",
     * @OA\Response(
     *  response=200,
     * description="Success",
     * @OA\MediaType(
     *      mediaType="application/json",
     *     @OA\Schema(
     *       @OA\Property(
     *         property="message",
     *        type="string",
     *      description="Message"
     *   )
     * )
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized",
     * @OA\MediaType(
     *      mediaType="application/json",
     *    @OA\Schema(
     *      @OA\Property(
     *       property="error",
     *     type="string",
     *  description="Error"
     * )
     * )
     * )
     * )
     * )
     */



    public function logout(Request $request)
    {
        Auth::user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

}
