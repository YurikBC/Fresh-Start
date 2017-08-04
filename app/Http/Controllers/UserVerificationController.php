<?php

namespace App\Http\Controllers;

use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @SWG\Get(
 *     path="/activate/{token}",
 *     summary="Activating a user",
 *     tags={"user"},
 *     description="Activating a user by confirming email address",
 *     operationId="activateUser",
 *     produces={"application/json"},
 *     @SWG\Parameter(
 *         name="token",
 *         in="path",
 *         description="Token for activation",
 *         required=true,
 *         type="string",
 *     ),
 *     @SWG\Response(
 *         response=200,
 *         description="User activated",
 *         examples={
 *          "application/json":{
 *              "data":{
 *                  "token":"db5d8d8ab7f0c37f717856469fde30de6b2a242f77f60fbb929167ef5abb8f386ba36851eb9b5d99c50b0a0f60e113575d534122a8d7e1c80d5698fd7484f37d",
 *              },
 *          },
 *         },
 *     ),
 *     @SWG\Response(
 *         response="422",
 *         description="Invalid user data",
 *         examples={
 *          "application/json":{
 *              "errors":{
 *                  "status":"422",
 *                  "title":"Invalid token",
 *                  "detail":"Provided token not exist",
 *              },
 *          },
 *     },
 *     ),
 * )
 */
class UserVerificationController extends Controller
{
    public function activate($token)
    {
        try {
            $user = User::where('activation_token', $token)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return response()->json(['errors' => [
                'status' => '422',
                'title' => 'Invalid token',
                'detail' => 'Provided token not exist'
            ]], 422);
        }

        $user->status = 1;
        $user->activation_token = null;
        $user->save();

        Auth::loginUsingId($user->id, true);

        return response()->json(['data' => [
            'token' => JWTAuth::fromUser($user)
        ]]);
    }
}
