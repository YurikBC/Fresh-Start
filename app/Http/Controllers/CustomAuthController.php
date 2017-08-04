<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;

class CustomAuthController extends Controller
{
    private $fullUrl;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * @SWG\Get(
     *     path="/authenticate",
     *     summary="Get info about user",
     *     tags={"authenticate"},
     *     description="Getting information about the user",
     *     operationId="getUserInfo",
     *     produces={"application/json"},
     *     security={
     *       {"api_key": {}}
     *     },
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         examples={
     *          "application/json":{
     *              "data":{
     *                    "name":"nikasd",
     *                    "email":"asdqwee@mail.ru",
     *                    "registered_at":"2017-07-31 11:33:07"
     *             }
     *          },
     *         },
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid token",
     *         examples={
     *          "application/json":{
     *              "errors":{
     *                  "status":"400",
     *                  "title":"Invalid token",
     *                  "detail":"Could not decode token: The token ""eyJ0eXAiOiJKV1QiLCJhsbGciOiJIUzI1NiJ9.eyJleHAiOjE1MTM2NzIwNDgsInN1YiI6NiwiaXNzIjoiaHR0cDovL2xvY2FsaG9zdC9hcGkvYXV0aGVudGljYXRlIiwiaWF0IjoxNTAxNTc2MDQ5LCJuYmYiOjE1MDE1NzYwNDksImp0aSI6IkZVcW42NXRlY0xDSm5kN1AifQ.K2wOp6Qk0URWaYV_FXxTqWQPx-zyyzglVryMpvLYZgU"" is an invalid JWS",
     *              },
     *          },
     *     },
     *     ),
     *      @SWG\Response(
     *         response="401",
     *         description="Unauthorized",
     *         examples={
     *          "application/json":{
     *              "errors":{
     *                  "status":"400",
     *                  "title":"Invalid token",
     *                  "detail":"The token has been blacklisted",
     *              },
     *          },
     *     },
     *     ),
     * )
     */

    public function index()
    {
        try
        {
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);

            return response()
                ->json([
                    'data' =>
                        [
                            'name'          => $user->name,
                            'email'         => $user->email,
                            'registered_at' => $user->created_at->toDateTimeString()
                        ]
                ]);
        }
        catch(JWTException $exception)
        {
            return response()
                ->json([
                    'errors' =>
                    [
                        'status' => $exception->getStatusCode(),
                        'title'  => 'Invalid token',
                        'detail'  => $exception->getMessage(),
                    ]
                ], $exception->getStatusCode());
        }
    }


    /**
     * @SWG\Post(
     *     path="/authenticate",
     *     summary="Login",
     *     tags={"authenticate"},
     *     description="Authenticate user with email and password",
     *     operationId="getToken",
     *     consumes={"application/x-www-form-urlencoded"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         description="User email",
     *         required=true,
     *         type="string",     *
     *     ),
     *    @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         description="User password",
     *         required=true,
     *         type="string",     *
     *     ),
     *     @SWG\Parameter(
     *         name="remember",
     *         in="formData",
     *         description="Remember user",
     *         required=false,
     *         type="boolean",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         examples={
     *                 "application/json":{
     *                     "data":{
     *                     "token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1MTM2ODI1MDQsInN1YiI6NiwiaXNzIjoiaHR0cDovL2xvY2FsaG9zdC9hcGkvYXV0aGVudGljYXRlIiwiaWF0IjoxNTAxNTg2NTA0LCJuYmYiOjE1MDE1ODY1MDQsImp0aSI6ImJvM3E3OG9uQjEySE1ZU0oifQ.hQNn9CYY0GHvDZ2xCC3KD6kOM6tQllQpieyx4bSOauk"
     *                     }
     *                 },
     *         },
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Access is denied",
     *         examples={
     *              {
     *                  "application/json":{
     *                      "errors":{
     *                          "status":"401",
     *                          "title":"Access is denied",
     *                          "detail":"Wrong password",
     *                          "source":{
     *                               "parameter":"http:\/\/localhost\/api\/authenticate"
     *                          }
     *                       },
     *                   },
     *             },
     *             {
     *                  "application/json":{
     *                      "errors":{
     *                          "status":"401",
     *                          "title":"Access is denied",
     *                          "detail":"Wrong email",
     *                          "source":{
     *                               "parameter":"http:\/\/localhost\/api\/authenticate"
     *                           }
     *                       },
     *                  },
     *             },
     *         },
     *    ),
     *     @SWG\Response(
     *         response=500,
     *         description="Undeclared error",
     *         examples={
     *                 "application/json":{
     *                      "errors":{
     *                          "status":"500",
     *                          "title":"Undeclared error",
     *                          "detail":"Something went wrong!",
     *                          "source":{
     *                               "parameter":"http:\/\/localhost\/api\/authenticate"
     *                           }
     *                       },
     *                 },
     *         },
     *     ),
     *
     * )
     */


    public function store(Request $request) //authenticate
    {
        $credentials = $request->only('email','password');
        $remember=$request->only('remember');
        $this->fullUrl=$request->fullUrl();
        try
        {
            if($remember["remember"]=="true") {
                $customClaims = ['exp' => strtotime(date('Y-m-d H:i:s', strtotime('+20 week')))];
                $token = JWTAuth::attempt($credentials, $customClaims);
            }
            else
            {
                $token = JWTAuth::attempt($credentials);
            }

            if(! $token )
            {

                $user = User::where('email',$credentials["email"]) -> first();
                if(!is_null($user))
                {
                   return $this->exceptionResponses('Access is denied', 'Wrong password', 401);
                }
                else
                {
                    return $this->exceptionResponses('Access is denied', 'Wrong email', 401);
                }
            }
        }
        catch(JWTException $exception)
        {
            return $this->exceptionResponses('Undeclared error', 'Something went wrong!', $exception->getStatusCode());
        }

        return response()
            ->json([
             'data' =>
                 [
                    'token' => $token
                 ]
             ],200);
    }


    /**
     * @SWG\Delete(
     *     path="/authenticate/{token}",
     *     summary="Logout",
     *     tags={"authenticate"},
     *     description="Brings a token to the black list",
     *     operationId="logoutUser",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="token",
     *         in="path",
     *         description="token",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         examples={
     *               "application/json":{     *
     *                     "name": "nikasd",
     *                     "email": "asdqwee@mail.ru"
     *               },
     *         },
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Token in the blacklist",
     *         examples={
     *          "application/json":{
     *              "error": {
     *                   "status": 401,
     *                   "title": "Bad Request",
     *                   "detail": "The token has been blacklisted",
     *                   "source": {
     *                        "parameter": "http://localhost/api/authenticate/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjYsImlzcyI6Imh0dHA6Ly9sb2NhbGhvc3QvYXBpL2F1dGhlbnRpY2F0ZSIsImlhdCI6MTUwMTU5MDEzOCwiZXhwIjoxNTAxNTkzNzM4LCJuYmYiOjE1MDE1OTAxMzgsImp0aSI6ImxFM1c1bzVIN1VSTzQ5U2EifQ.lsI26YI_528SKXyG9K189Nle-2HRLUpG2DOro6Aa6Oc"
     *                   }
     *              }
     *          },
     *     },
     *     ),
     *      @SWG\Response(
     *         response="400",
     *         description="Invalid token",
     *         examples={
     *              "application/json":{
     *                   "error": {
     *                       "status": 400,
     *                       "title": "Bad Request",
     *                       "detail": "Could not decode token: The token ""eyJ0eXAiOiJKV1QiLCJhsbGciOiJIUzI1NiJ9.eyJleHAiOjE1MTM2NzIwNDgsInN1YiI6NiwiaXNzIjoiaHR0cDovL2xvY2FsaG9zdC9hcGkvYXV0aGVudGljYXRlIiwiaWF0IjoxNTAxNTc2MDQ5LCJuYmYiOjE1MDE1NzYwNDksImp0aSI6IkZVcW42NXRlY0xDSm5kN1AifQ.K2wOp6Qk0URWaYV_FXxTqWQPx-zyyzglVryMpvLYZgU"" is an invalid JWS",
     *                       "source": {
     *                           "parameter": "http://localhost/api/authenticate/eyJ0eXAiOiJKV1QiLCJhsbGciOiJIUzI1NiJ9.eyJleHAiOjE1MTM2NzIwNDgsInN1YiI6NiwiaXNzIjoiaHR0cDovL2xvY2FsaG9zdC9hcGkvYXV0aGVudGljYXRlIiwiaWF0IjoxNTAxNTc2MDQ5LCJuYmYiOjE1MDE1NzYwNDksImp0aSI6IkZVcW42NXRlY0xDSm5kN1AifQ.K2wOp6Qk0URWaYV_FXxTqWQPx-zyyzglVryMpvLYZgU"
     *                       }
     *                   }
     *              },
     *         },
     *      ),
     * )
     */

    public function destroy($id,Request $request)
    {
        try
        {
            $this->fullUrl=$request->fullUrl();
            $user = JWTAuth::toUser($id);
            JWTAuth::invalidate($id);

            return response()
                ->json([
                    'name'          => $user->name,
                    'email'         => $user->email,
                ],200);
        }
        catch(JWTException $exception)
        {
            return $this->exceptionResponses('Bad Request', $exception->getMessage(), $exception->getStatusCode());
        }
    }

    protected function exceptionResponses($title, $detail, $statuscode)
    {

        return response()->json(
            [
                'error' => [
                    "status" => $statuscode,
                    "title" => $title,
                    "detail" => $detail,
                    "source" => [
                        "parameter" => $this->fullUrl
                    ],
                ]
            ],$statuscode);
    }
}
