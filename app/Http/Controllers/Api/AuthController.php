<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as Response;
use Illuminate\Support\Facades\Hash;
use Exception;
use App\Enums\Error;
use App\Rules\MacRule;
use App\User;
use Illuminate\Support\Str;
class AuthController extends Controller
{
    /**
    * @OA\Post(
    *         path="/api/auth/login",
    *         tags={"Authentication"},
    *         summary="Login",
    *         description="Login an user",
    *         operationId="login",
    *         @OA\Response(
    *             response=200,
    *             description="Successful operation"
    *         ),
    *         @OA\Response(
    *             response=422,
    *             description="Validation error"
    *         ),
    *         @OA\Response(
    *             response=403,
    *             description="Wrong combination of email and password or email not verified"
    *         ),
    *         @OA\Response(
    *             response=500,
    *             description="Server error"
    *         ),
    *         @OA\RequestBody(
    *             required=true,
    *             @OA\MediaType(
    *                 mediaType="application/x-www-form-urlencoded",
    *                 @OA\Schema(
    *                     type="object",
    *                      @OA\Property(
    *                         property="email",
    *                         description="email",
    *                         type="string",
    *                     ),
    *                     @OA\Property(
    *                         property="password",
    *                         description="Password",
    *                         type="string",
    *                         format="password"
    *                     ),
    *                 )
    *             )
    *         )
    * )
    */
    public function login(Request $request)
    {
        $request['password'] = '123123';
        // Validate input data
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', new MacRule],
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(
            [
                'error' =>
                        [
                            'code' => Error::GENR0002,
                            'message' => Error::getDescription(Error::GENR0002)
                        ],
                'validation' => $validator->errors()
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $credentials = request(['email', 'password']);
        $credentials['deleted_at'] = null;
        // Check User Exist
        $user = User::where('email',$request->email)->first();
        if($user === null){
            $this->register($request);
        }
        // Check the combination of email and password, also check for activation status
        if(!$token = auth('api')->attempt($credentials)) {
            return response()->json(
                ['error' =>
                            [
                                'code' => Error::AUTH0001,
                                'message' => Error::getDescription(Error::AUTH0001),
                                'token'=>auth('api')->attempt($credentials),
                                'request'=> $request->all()
                            ]
                ], Response::HTTP_UNAUTHORIZED,
                ['request'=> $request->all()],
            );
        }

        $user = auth('api')->user();
        $user->updated_at = date('Y-m-d H:i:s');
        $user->active = 1;
        $user->save();
        // $user['roles'] = $user->getRoleNames();
        return $this->respondWithToken($token);
        // return response()->json(['user' => $user], Response::HTTP_OK)->withCookie('token', $token, config('jwt.ttl'), "/", null, false, true);
    }
    
    private function register(Request $request){
        $user = new User([
            'name' => $request->get('name', $request->email),
            'email' => $request->email,
            'password' => Hash::make('123123'),
            'token'             => Str::random(64),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'activated'         => 1,
            'menuroles' => 'user' 
        ]);
        $user->save();
        $user->assignRole('user');
        return true;
    }
    protected function respondWithToken($token)
    {
        return ['token'=>$token];
        // return [
        //     'token' => $token,
        //     'token_type'   => 'bearer',
        //     'expires_in' => auth('api')->factory()->getTTL() * 60
        // ];
    }
}
