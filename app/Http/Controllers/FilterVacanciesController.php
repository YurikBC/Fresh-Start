<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Vacancy;

class FilterVacanciesController extends Controller
{

    private $request;
    private $fullUrl;
    private $filterField;
    private $queryFinalArray;

    public function __construct(Request $request)
    {
        $this->fullUrl=$request->fullUrl();

        $this->request = $request;
        //If you need to add a filter, just add to this array "queryURL" => "databaseColumn"
        $this->filterField=Array(
            "city" => "location",
            "employment" => "employment",
        );

        foreach ($this->filterField as $fieldKey => $fieldValue){
            foreach ($request->all() as $requestKey => $requestValue)
            {
                if(strcasecmp($fieldKey,$requestKey)==0)
                {
                    $this->queryFinalArray[$fieldValue]=$requestValue;
                }
            }
        }
    }

    /**
     * @SWG\Get(
     *     path="/filter",
     *     summary="Get filtered jobs",
     *     tags={"filterVacancies"},
     *     description="Filter vacancies by city and employment",
     *     operationId="filterVacancies",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="city",
     *         in="query",
     *         description="Filter by city",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="page",
     *         in="query",
     *         description="Number of the page",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="number",
     *         in="query",
     *         description="Number of vacancies",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="employment",
     *         in="query",
     *         description="Filter by employment",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         examples={
     *          "application/json":{
     *              {
     *                  "current_page": 1,
     *                   "data": {
     *                               {
     *                                   "title": "exposit",
     *                                   "location": "Grodno",
     *                                   "description": "asdasdasda",
     *                                   "url": "sasd",
     *                                   "date": 123542135123,
     *                                   "employment": "exposit",
     *                                   "company": "sec",
     *                               }
     *                           },
     *                   "from": 1,
     *                   "last_page": 1,
     *                   "next_page_url": null,
     *                   "path": "http://localhost/api/filter",
     *                   "per_page": 10,
     *                   "prev_page_url": null,
     *                   "to": 1,
     *                   "total": 1
     *              }
     *          },
     *         },
     *     ),
     *    @SWG\Response(
     *         response=400,
     *         description="Bad Request",
     *         examples={
     *          "application/json":{
     *              {
     *                   "error": {
     *                      "status": 400,
     *                      "title": "Bad Request",
     *                      "detail": "No parameters for filtering",
     *                      "source": {
     *                          "parameter": "http://localhost/api/filter"
     *                      }
     *                   }
     *              }
     *          },
     *         },
     *     ),
     * )
     */

    public function filterAndPaginate()
    {
        if(count($this->queryFinalArray)>0)
        {
            $this->request->page = $this->request->page ?? 1;
            $number = $this->request->number ?? 10;

            $vacancy= new Vacancy;
            $result = $vacancy
                ->select('title', 'location', 'description', 'url', 'date', 'employment', 'company')
                ->where($this->queryFinalArray)
                ->paginate($number);

            return $result;

        }
        return $this->exceptionResponses('Bad Request','No parameters for filtering',400);
    }

    private function exceptionResponses($title, $detail, $statuscode)
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
