<?php
/**
 * @SWG\Get(
 *     path="/favorite",
 *     summary="Send all favorite vacancies",
 *     tags={"vacancy"},
 *     description="Send all favorite vacancies",
 *     operationId="SendFavorite",
 *     produces={"application/json"},
 *     @SWG\Response(
 *         response=200,
 *         description="favorite",
 *         examples={
 *          "application/json":{
 *              "data": {
 *                      "id": 9,
 *                      "title": "Junior Python Developer",
 *                      "location": "Витебск",
 *                      "description": "description vacancy with HTML",
 *                      "url": "https://jobs.tut.by/vacancy/21524322?query=junior",
 *                      "date": 1501113600000,
 *                      "employment": "Полная занятость, полный день",
 *                      "company": " iTechArt Group",
 *                      "address": {
 *                          "type": "Point",
 *                              "coordinates": {
 *                                  27.544296,
 *                                  53.888329
 *                              },
 *                      },
 *           "created_at": "2017-07-29 15:29:07",
 *           "updated_at": "2017-07-29 15:29:07"
 *           },
 *          }
 *     },
 *     ),
 *     @SWG\Response(
 *         response="204",
 *         description="Not found favorite vacancies",
 *         examples={
 *              "error": {
 *                  "status": 204,
 *                  "title": "Not found favorite vacancies",
 *                  "source": {
 *                      "parameter": "http://localhost/favorite"
 *                  }
 *              }
 *          }
 *     ),
 *     security={
 *         {
 *             "petstore_auth": {"write:pets", "read:pets"}
 *         }
 *     },
 *     deprecated=false
 * )
 */
/**
 * @SWG\Put(
 *     path="/favorite/{id}",
 *     summary="Add vacancy to favorite",
 *     tags={"vacancy"},
 *     description="Add vacancy to favorite",
 *     operationId="Add vacancy to favorite",
 *     produces={"application/json"},
 *     @SWG\Parameter(
 *         name="id",
 *         in="path",
 *         description="Id vacancy",
 *         required=true,
 *         type="integer",
 *     ),
 *     @SWG\Response(
 *         response="204",
 *         description="New vacancy not found",
 *         examples={
 *              "data": {
 *                  "type": "vacancy",
 *                  "attributes": {
 *                      "title": "Специалист по интернет-маркетингу",
 *                      "src": "http://localhost/api/detail/104"
 *                  },
 *              "relationships": {
 *                  "user": {
 *                      "data": {
 *                          "type": "people",
 *                          "id": 1
 *                      }
 *                  }
 *                  }
 *              }
 *          }
 *     ),
 *     security={
 *         {
 *             "petstore_auth": {"write:pets", "read:pets"}
 *         }
 *     },
 *     deprecated=false
 * )
 */
/**
 * @SWG\Delete(
 *     path="/favorite/{id}",
 *     summary="Delete vacancy from favorite",
 *     tags={"vacancy"},
 *     description="Delete vacancy from favorite",
 *     operationId="Delete vacancy from favorite",
 *     produces={"application/json"},
 *     @SWG\Parameter(
 *         name="id",
 *         in="path",
 *         description="Id vacancy",
 *         required=true,
 *         type="integer",
 *     ),
 *     @SWG\Response(
 *         response="200",
 *         description = "detelete from favorite"
 *     ),
 *     security={
 *         {
 *             "petstore_auth": {"write:pets", "read:pets"}
 *         }
 *     },
 *     deprecated=false
 * )
 */
namespace App\Http\Controllers;

use App\User;
use App\Vacancy;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(JWTAuth::parseToken()->authenticate()->id);
        $result = $user->vacancy()->get();
        if ($result->isEmpty()) {
            return $this->exceptionResponse('Not found favorite vacancies');
        }
        return $result;
    }

    public function update($id)
    {
        $user = User::findOrFail(JWTAuth::parseToken()->authenticate()->id);
        $vacancy = Vacancy::findOrFail($id);
        $user->vacancy()->save($vacancy);
        return response()->json([
            "data" => [
                "type" => "vacancy",
                "attributes" => [
                    "title" => $vacancy->title,
                    "src" => route('detail', ['id' => $id]),
                ],
                "relationships" => [
                    "user" => [
                        "data" => [
                            "type" => "people",
                            "id" => $user->id,
                        ]
                    ]
                ]
            ],
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail(JWTAuth::parseToken()->authenticate()->id);
        $user->vacancy()->detach(Vacancy::findOrFail($id));
        return response(null,200);
    }

}
