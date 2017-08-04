<?php

namespace App\Services;

use App\Mailer;
use App\User;
use App\Vacancy;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class MailerService
{
    private $modelMailer;
    private $modelVacancy;
    private $modelUser;

    public function __construct()
    {
        $this->modelMailer = new Mailer();
        $this->modelVacancy = new Vacancy();
        $this->modelUser = new User();
    }

    public function sendVacancyOnFilter()
    {
        $lastSendedIdVacancy = $this->modelMailer->findOrFail(1)->last_sended;
        $sendingVacancies = $this->modelVacancy->getSendingVacancies($lastSendedIdVacancy)->toArray();
        if (!count($sendingVacancies)) {
            return;
        }
        $lastSendingId = $this->modelVacancy->max('id');
        $this->modelMailer->where('id', 1)->update(['last_sended' => $lastSendingId]);
        $subscribers = $this->modelUser->getSubscribers()->toArray();
        $sortedVacanciesByLocation = $this->sortArrByKey($sendingVacancies, 'location');
        $sortUsersByCity = $this->sortArrByKey($subscribers, 'city');
        $this->sendEmails($sortUsersByCity, $sortedVacanciesByLocation);
    }

    private function sortArrByKey($arr, $location)
    {
        $result = array();
        foreach ($arr as $item) {
            if (array_key_exists($item[$location], $result)) {
                $result[$item[$location]][] = $item;
            } else {
                $result[$item[$location]] = [$item];
            }
        }
        return $result;
    }

    private function sendEmails($users, $vacancies)
    {
        foreach ($users as $key => $value) {
            if (array_key_exists($key, $vacancies)) {
                foreach ($value as $item) {
                    Mail::send('mailer', ['vacancy' => $vacancies[$key]], function ($m) use ($item) {
                        $m->from('my@App.com', 'AppTest');

                        $m->to($item['email'], $item['name'])->subject('New Vacancies');
                    });
                }
            }
        }
    }
}