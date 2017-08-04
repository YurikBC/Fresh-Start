<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Validator;

class User extends Authenticatable
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'city',
        'phone',
        'newsletter',
        'status',
        'activation_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function vacancy()
    {
        return $this->belongsToMany('App\Vacancy');
    }

    public function scopeGetSubscribers()
    {
        return $this->where([
            ['newsletter', '=', 1],
            ['status', '=', 1],
        ])->get();
    }

    /**
     * The attributes that user can change manually from control panel
     *
     * @var array
     */
    protected $changeable = [
        'name',
        'password',
        'password_confirmation',
        'city',
        'phone',
        'newsletter'
    ];

    public static function validate($data, $rules)
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            response([
                'errors' => [
                    'status' => '422',
                    'title' => 'Invalid data',
                    'detail' => $validator->errors(),
                ]
            ], 422)->send();
        }
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = password_hash($password, PASSWORD_BCRYPT, ["cost" => 10]);
    }

    public function getChangeable()
    {
        return $this->changeable;
    }
}
