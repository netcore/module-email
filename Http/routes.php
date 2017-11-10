<?php

Route::group([
    'middleware' => ['web', 'auth.admin'],
    'prefix'     => 'admin',
    'as'         => 'admin::',
    'namespace'  => 'Modules\Email\Http\Controllers\Admin'
], function () {
    Route::resource('automated_emails', 'AutomatedEmailController', ['only' => ['index', 'edit', 'update']]);

    Route::resource('campaigns', 'CampaignController', ['except' => ['show']]);
    Route::get('/campaigns/{campaign}/start', [
        'uses' => 'CampaignController@start',
        'as'   => 'campaigns.start'
    ]);
    Route::get('/campaigns/{campaign}/stop', [
        'uses' => 'CampaignController@stop',
        'as'   => 'campaigns.stop'
    ]);
    Route::get('campaigns/get-users/{campaign}', [
        'as'   => 'campaigns.get-users',
        'uses' => 'CampaignController@getUsers'
    ]);
    Route::post('campaigns/search-users', [
        'as'   => 'campaigns.search-users',
        'uses' => 'CampaignController@searchUsers'
    ]);
    Route::delete('/campaigns/{campaign}/{user}/delete', [
        'uses' => 'CampaignController@destroyUser',
        'as'   => 'campaigns.destroy-user'
    ]);

    Route::resource('subscribers', 'SubscriberController', ['only' => ['index', 'destroy']]);
    Route::get('subscribers/pagination', [
        'as'   => 'subscribers.pagination',
        'uses' => 'SubscriberController@pagination'
    ]);
});
