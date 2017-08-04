<?php
/**
 * @SWG\Get(
 *     path="/?page=1&limit=1",
 *     summary="Paginate vacancies",
 *     tags={"vacancy"},
 *     description="Paginate vacancies",
 *     operationId="paginatinVacancy",
 *     produces={"application/json"},
 *     @SWG\Parameter(
 *         name="page",
 *         in="path",
 *         description="Number page",
 *         required=false,
 *         type="integer",
 *     ),
 *     @SWG\Parameter(
 *         name="limit",
 *         in="path",
 *         description="Number of vacancies",
 *         required=false,
 *         type="integer",
 *     ),
 *     @SWG\Response(
 *         response=200,
 *         description="paginate",
 *         examples={
 *          "application/json":{
 *              "current_page": 3,
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
 *           "from": 3,
 *           "last_page": 22,
 *           "next_page_url": "http://localhost/api?page=4",
 *           "path": "http://localhost/api",
 *           "per_page": "1",
 *           "prev_page_url": "http://localhost/api?page=2",
 *           "to": 3,
 *           "total": 22
 *          }
 *     },
 *     ),
 *     @SWG\Response(
 *         response="204",
 *         description="New vacancies not found",
 *         examples={
 *              "error": {
 *                  "status": 204,
 *                  "title": "New vacancy not found",
 *                  "source": {
 *                      "parameter": "http://localhost/api?number=20&page=3"
 *                  }
 *              }
 *          }
 *     ),
 *     deprecated=false
 * )
 */
/**
 * @SWG\Get(
 *     path="/detail/{id}",
 *     summary="Detail vacancy",
 *     tags={"vacancy"},
 *     description="Detail vacancy",
 *     operationId="detailVacancy",
 *     produces={"application/json"},
 *     @SWG\Parameter(
 *         name="id",
 *         in="path",
 *         description="Id vacancy",
 *         required=true,
 *         type="integer",
 *     ),
 *     @SWG\Response(
 *         response=200,
 *         description="detail",
 *         examples={
 *          "application/json":{
 *              "id": 12,
 *              "title": "Junior Account Manager",
 *              "location": "Гомель",
 *              "description": "description vacancy with HTML",
 *              "url": "https://jobs.tut.by/vacancy/22092038?query=junior",
 *              "date": 1500940800000,
 *              "employment": "Полная занятость, полный день",
 *              "company": " Oxagile",
 *              "address": null,
 *              "created_at": "2017-07-29 15:29:07",
 *              "updated_at": "2017-07-29 15:29:07"
 *          },
 *          },
 *     ),
 *     @SWG\Response(
 *         response="204",
 *         description="New vacancy not found",
 *         examples={
 *              "error": {
 *                  "status": 204,
 *                  "title": "New vacancy not found",
 *                  "source": {
 *                      "parameter": "http://localhost/api/detail/125"
 *                  }
 *              }
 *          }
 *     ),
 *     deprecated=false
 * )
 */

namespace App\Http\Controllers;

use App\Mailer;
use App\Services\MailerService;
use App\Services\ScanningVacanciesService;
use App\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Tymon\JWTAuth\Facades\JWTAuth;

class VacancyController extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function paginate()
    {
        $this->request->page = $this->request->page ?? 1;
        $limit = $this->request->limit ?? 10;
        $result = Vacancy::orderBy("date", "desc")->paginate($limit);
        if ($result->isEmpty()) {
            return $this->exceptionResponse('New vacancy not found');
        }
        return $result;
    }

    public function detail($id)
    {
        $result = Vacancy::find($id);
        if ($result === null) {
            return $this->exceptionResponse('Vacancy not found');
        }
        return $result;
    }

    public function ref()
    {
        $redis = Redis::getFacadeRoot();
        $service = new ScanningVacanciesService(new Vacancy(),$redis);
        $service->updateVacancies();
    }
}
