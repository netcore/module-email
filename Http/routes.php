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
    Route::get('campaigns/get-receivers/{campaign}', [
        'as'   => 'campaigns.get-receivers',
        'uses' => 'CampaignController@getReceivers'
    ]);
    Route::post('campaigns/search-receivers', [
        'as'   => 'campaigns.search-receivers',
        'uses' => 'CampaignController@searchReceivers'
    ]);
    Route::delete('/campaigns/{campaign}/{receiver}/delete', [
        'uses' => 'CampaignController@destroyReceiver',
        'as'   => 'campaigns.destroy-receiver'
    ]);

    Route::resource('subscribers', 'SubscriberController', ['only' => ['index', 'destroy']]);
    Route::get('subscribers/pagination', [
        'as'   => 'subscribers.pagination',
        'uses' => 'SubscriberController@pagination'
    ]);
});
