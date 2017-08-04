<?php

namespace App\Http\Controllers;

use App\User;
use App\Mail\ActivationLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * @SWG\Post(
 *     path="/user/register",
 *     summary="Registers new user",
 *     tags={"user"},
 *     description="Registers new user on website",
 *     operationId="registerUser",
 *     consumes={"application/x-www-form-urlencoded"},
 *     produces={"application/json"},
 *     @SWG\Parameter(
 *         name="name",
 *         in="formData",
 *         description="Name of the user",
 *         required=true,
 *         type="string",
 *         maxLength=255,
 *     ),
 *     @SWG\Parameter(
 *         name="email",
 *         in="formData",
 *         description="Email of the user",
 *         required=true,
 *         type="string",
 *         maxLength=255
 *     ),
 *     @SWG\Parameter(
 *         name="password",
 *         in="formData",
 *         description="Password of the user",
 *         required=true,
 *         type="string",
 *         maxLength=255,
 *     ),
 *     @SWG\Parameter(
 *         name="password_confirmation",
 *         in="formData",
 *         description="Password confirmation",
 *         required=true,
 *         type="string",
 *         maxLength=255,
 *     ),
 *     @SWG\Parameter(
 *         name="city",
 *         in="formData",
 *         description="City where the user live",
 *         required=true,
 *         type="string",
 *         maxLength=255,
 *     ),
 *     @SWG\Parameter(
 *         name="phone",
 *         in="formData",
 *         description="Mobile phone number of the user",
 *         required=false,
 *         type="string",
 *         maxLength=255,
 *     ),
 *     @SWG\Parameter(
 *         name="newsletter",
 *         in="formData",
 *         description="Agreement of the user for getting newsletter with suitable jobs (filter by city)",
 *         required=true,
 *         type="string",
 *         default="0",
 *     ),
 *     @SWG\Response(
 *         response=201,
 *         description="User created successfully",
 *         examples={
 *          "application/json":{
 *              "data":"user registered",
 *          },
 *     },
 *     ),
 *     @SWG\Response(
 *         response="422",
 *         description="Invalid user data",
 *     examples={
 *          "application/json":{
 *              "errors":{
 *                  "status":"422",
 *                  "title":"Invalid data",
 *                  "description":{
 *                      "email":"[The email has already been taken.]",
 *                      "city":"[The city field is required.]",
 *                  }
 *              }
 *          },
 *     },
 *     ),
 * )
 */
class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $user = new User($request->all());
        $user->validate($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:255|confirmed',
            'city' => 'required|string|max:255',
            'phone' => 'string|max:255|unique:users',
            'newsletter' => 'boolean'
        ]);

        $user->activation_token = hash('sha512', $request->email . time());
        $user->save();

        Mail::to($user)->queue(new ActivationLink($user));

        return response()->json(['data' => 'user registered'], 201);
    }
}
