<?php

Route::group([
    'middleware' => ['web', 'auth.admin'],
    'prefix'     => 'admin',
    'as'         => 'admin::',
    'namespace'  => 'Modules\Email\Http\Controllers\Admin'
], function () {
    Route::resource('automated_emails', 'AutomatedEmailController', ['only' => ['index', 'edit', 'update']]);

    Route::resource('campaigns', 'CampaignController', ['except' => ['show']]);
    Route::post('campaigns/get-users', ['as' => 'campaigns.get-users', 'uses' => 'CampaignController@getUsers']);
    Route::post('campaigns/search-users', ['as' => 'campaigns.search-users', 'uses' => 'CampaignController@searchUsers']);

    Route::post('subscribers', ['as' => 'subscribers.index', 'uses' => 'SubscriberController@index']);
});
