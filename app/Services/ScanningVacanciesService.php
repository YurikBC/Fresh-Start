<?php

namespace App\Services;

use App\Vacancy;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Redis\RedisManager as Redis;
use Illuminate\Support\Collection;

class ScanningVacanciesService
{
    private $redis;
    private $vacancy;
    private $templateKeys = [
        'title_vacancy',
        'location',
        'address',
        'description',
        'original_url',
        'published_date',
        'employment',
        'title_company'
    ];

    public function __construct(Vacancy $vacancy, Redis $redis)
    {
        $this->vacancy = $vacancy;
        $this->redis = $redis;
    }

    public function updateVacancies()
    {
        $externalUrls = $this->getUrls();
        $keysVacanciesForDelete = $this->vacancy->getKeysVacanciesForDelete($externalUrls);
        $keysVacanciesForAdd = $this->vacancy->getKeysVacanciesForAdd($externalUrls);
        if ($keysVacanciesForDelete->isEmpty() && $keysVacanciesForAdd->isEmpty()) {
            return;
        }
        $vacanciesForAdd = $this->getVacancies($keysVacanciesForAdd);
        $this->validate($vacanciesForAdd);
        $this->saveVacancies($vacanciesForAdd);
        $this->detachVacanciesAndUsers($keysVacanciesForDelete);
        $this->deleteVacancies($keysVacanciesForDelete);
    }

    private function validate(Collection $arr)
    {
        $arr->each(function ($item) {
            $result = collect($item)->diffKeys(array_flip($this->templateKeys));
            if (count($result)) {
                throw new \Exception("no keys");
            }
        });
    }

    private function saveVacancies(Collection $arr)
    {
        if ($arr->isEmpty()) {
            return;
        }
        $arr->each(function ($item) {
            $add = $item->address;
            if ($item->address !== null) {
                $add = new Point((float)$item->address->lat, (float)$item->address->lng);
            }
            $this->vacancy->create([
                'title' => $item->title_vacancy,
                'location' => $item->location,
                'description' => $item->description,
                'url' => $item->original_url,
                'date' => $item->published_date,
                'employment' => $item->employment,
                'company' => $item->title_company,
                'address' => $add
            ]);
        });
    }

    private function deleteVacancies(Collection $arr)
    {
        if ($arr->isEmpty()) {
            return;
        }
        $arr->each(function ($item) {
            $this->vacancy->where('url', '=', $item)->delete();
        });
    }

    private function detachVacanciesAndUsers($key)
    {
        foreach ($key as $item) {
            $this->vacancy->where('url', '=', $item)->first()->user()->detach();
        }
    }

    //redis
    private function getVacancies(Collection $keys)
    {
        return $keys->map(function ($key) {
            return json_decode($this->redis->get($key));
        });
    }

    private function getUrls()
    {
        return collect($this->redis->keys('*'));
    }
}