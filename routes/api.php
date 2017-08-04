<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/', 'VacancyController@paginate');
Route::get('/detail/{id}', 'VacancyController@detail')->where('id', '[0-9]+')->name('detail');
Route::group(['middleware' => 'jwt.auth'], function () {
    Route::resource('favorite', 'UserController', [
        'only' => [
            'index',
            'update',
            'destroy'
        ]
    ]);
});
Route::get('/detail/{id}', 'VacancyController@detail')->where('id', '[0-9]+');

Route::post('/user/password_reset/', 'PasswordResetController@reset');
Route::get('/activate/{token}', 'UserVerificationController@activate');
Route::post('/user/register', 'RegisterController@register');

Route::group(['middleware' => 'JwtAuth'], function () {
    Route::resource('user', 'UserProfileController', ['only' => ['update', 'show']]);
});

Route::get('/filter', 'FilterVacanciesController@filterAndPaginate');

Route::resource('/authenticate', 'CustomAuthController', ['only' => [
    'index', 'store', 'destroy'
]]);
