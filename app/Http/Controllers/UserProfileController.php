<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @SWG\Get(
 *     path="/user/show",
 *     summary="Show user info",
 *     tags={"user"},
 *     description="Return information about currently authenticated user",
 *     operationId="showUser",
 *     produces={"application/json"},
 *     security={
 *       {"api_key": {}}
 *     },
 *     @SWG\Response(
 *         response=200,
 *         description="User information fetched successfully",
 *         examples={
 *          "application/json":{
 *              "data":{
 *                  "id":"32",
 *                  "name":"Alex",
 *                  "email":"alex@mailserver.org",
 *                  "city":"Минск",
 *                  "phone":"+375123456789",
 *                  "newsletter":"1",
 *                  "status":"1",
 *                  "created_at": "2017-07-25 12:13:36",
 *                  "updated_at": "2017-07-27 10:57:36"
 *              },
 *          },
 *     },
 *     ),
 *     @SWG\Response(
 *         response="400",
 *         description="Token absent",
 *         examples={
 *          "application/json":{
 *              "errors":{
 *                  "status": 400,
 *                  "title": "Token absent",
 *                  "detail": "Token can not be parsed from the request headers",
 *              }
 *          },
 *         },
 *     ),
 * )
 */

/**
 * @SWG\Patch(
 *     path="/user/update",
 *     summary="Update user info",
 *     tags={"user"},
 *     description="Update information about currently authenticated user",
 *     operationId="updateUser",
 *     consumes={"application/x-www-form-urlencoded"},
 *     produces={"application/json"},
 *     security={
 *       {"api_key": {}}
 *     },
 *     @SWG\Parameter(
 *         name="name",
 *         in="formData",
 *         description="Name of the user",
 *         required=false,
 *         type="string",
 *         maxLength=255,
 *     ),
 *     @SWG\Parameter(
 *         name="password",
 *         in="formData",
 *         description="Password of the user",
 *         required=false,
 *         type="string",
 *         maxLength=255,
 *     ),
 *     @SWG\Parameter(
 *         name="password_confirmation",
 *         in="formData",
 *         description="Password confirmation (REQUIRED IF password IS SET)",
 *         required=false,
 *         type="string",
 *         maxLength=255,
 *     ),
 *     @SWG\Parameter(
 *         name="city",
 *         in="formData",
 *         description="City where the user live",
 *         required=false,
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
 *         description="Agreement of the user for getting newsletter with suitable jobs",
 *         required=false,
 *         type="number",
 *     ),
 *     @SWG\Response(
 *         response=200,
 *         description="User information updated successfully",
 *         examples={
 *          "application/json":{
 *              "data":{
 *                  "id":"32",
 *                  "name":"Alex",
 *                  "email":"alex@mailserver.org",
 *                  "city":"Минск",
 *                  "phone":"+375987654321",
 *                  "newsletter":"1",
 *                  "status":"1",
 *                  "created_at": "2017-07-25 12:13:36",
 *                  "updated_at": "2017-07-27 10:57:36"
 *              },
 *          },
 *         },
 *     ),
 *     @SWG\Response(
 *         response="400",
 *         description="Provided data is invalid and can not be used",
 *         examples={
 *          "application/json":{
 *              "errors":{
 *                  "status": 422,
 *                  "title": "Invalid data",
 *                  "detail": {
 *                      "name":"The name field is required.",
 *                  },
 *              }
 *          },
 *         },
 *     ),
 * )
 */
class UserProfileController extends Controller
{
    public function show()
    {
        $user = JWTAuth::parseToken()->authenticate();

        return response()->json(['data' => $user], 200);
    }

    public function update(Request $request)
    {
        JWTAuth::parseToken()->authenticate();

        $fields = array_filter($request->only(Auth::user()->getChangeable()), function ($value) {
            return ($value !== '' && $value !== null);
        });

        Auth::user()->validate($fields, [
            'name' => 'required|string|max:255',
            'password' => 'sometimes|required|string|min:6|max:255|confirmed',
            'city' => 'string|max:255',
            'phone' => 'string|max:255|unique:users,phone,' . Auth::id(),
            'newsletter' => 'boolean'
        ]);

        Auth::user()->update($fields);

        return response()->json(['data' => Auth::user()->toArray()], 200);
    }
}