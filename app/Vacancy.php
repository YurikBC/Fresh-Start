<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

class Vacancy extends Model
{
    use SpatialTrait;

    protected $guarded = [];
    protected $spatialFields = [
        'address',
    ];

    public function user()
    {
        return $this->belongsToMany('App\User');
    }

    public function scopeGetUrls($query)
    {
        return $query->get()->map(function ($item) {
            return $item->url;
        });
    }

    public function scopeGetKeysVacanciesForDelete($query, $externalUrls)
    {
        $myUrls = $this->getUrls();
        if ($myUrls->isEmpty()) {
            return collect([]);
        }
        return $myUrls->diff($externalUrls);
    }

    public function scopeGetKeysVacanciesForAdd($query, $externalUrls)
    {
        if ($externalUrls->isEmpty()) {
            return collect([]);
        }
        return $externalUrls->diff($this->getUrls());
    }

    public function scopeGetSendingVacancies($query,$last_sended)
    {
        return $query->where('id','>',$last_sended)->get();
    }
}