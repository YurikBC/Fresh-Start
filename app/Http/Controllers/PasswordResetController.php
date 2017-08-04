<?php

namespace App\Http\Controllers;

use App\Mail\PasswordReset;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

/**
 * @SWG\Post(
 *     path="/user/password_reset",
 *     summary="Resets password for user",
 *     tags={"user"},
 *     description="Resets password for user in case it was forgotten",
 *     operationId="password_reset",
 *     consumes={"application/x-www-form-urlencoded"},
 *     produces={"application/json"},
 *     @SWG\Parameter(
 *         name="email",
 *         in="formData",
 *         description="Email of the user",
 *         required=true,
 *         type="string",
 *         maxLength=255,
 *     ),
 *     @SWG\Response(
 *         response=200,
 *         description="Password reset attempt received",
 *         examples={
 *          "application/json":{
 *              "data":"If email is correct, you will get the letter with your new password within few minutes",
 *          },
 *     },
 *     ),
 *     @SWG\Response(
 *         response="422",
 *         description="Invalid email",
 *     examples={
 *          "application/json":{
 *              "errors":{
 *                  "status":"422",
 *                  "title":"Invalid data",
 *                  "description":{
 *                      "email":"[The email must be a valid email address.]",
 *                  }
 *              }
 *          },
 *     },
 *     ),
 * )
 */
class PasswordResetController extends Controller
{
    public function reset(Request $request)
    {
        User::validate($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);

        try {
            $user = User::where('email', $request->email)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'data' => 'If email is correct, you will get the letter with your new password within few minutes',
                ], 200);
        }

        $newPassword = substr(md5(microtime()),rand(0,10),8);

        $user->password = $newPassword;
        $user->save();

        Mail::to($user)->queue(new PasswordReset($newPassword));

        return response()->json([
            'data' => 'If email is correct, you will get the letter with your new password within few minutes',
            ], 200);

    }
}