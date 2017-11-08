<?php

namespace Modules\Email\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Email\Http\Requests\Admin\CampaignRequest;
use Modules\Email\Models\Campaign;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $campaigns = Campaign::all();

        return view('email::campaigns.index', compact('campaigns'));
    }

    /**
     * Show the automated_email for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $filters = [];

        return view('email::campaigns.create', compact('filters'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CampaignRequest $request
     * @return Response
     */
    public function store(CampaignRequest $request)
    {
        $campaign = Campaign::create($request->all());
        $campaign->storeTranslations($request->get('translations', []));

        return redirect()->route('admin::campaigns.index')->withSuccess('Successfully created');
    }

    /**
     * Show the automated_email for editing the specified resource.
     *
     * @param  Campaign $campaign
     * @return Response
     */
    public function edit(Campaign $campaign)
    {
        $filters = [];

        return view('email::campaigns.edit', compact('campaign', 'filters'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CampaignRequest $request
     * @param Campaign        $campaign
     * @return Response
     */
    public function update(CampaignRequest $request, Campaign $campaign)
    {
        $campaign->update($request->all());
        $campaign->updateTranslations($request->get('translations', []));

        return back()->withSuccess('Successfully saved');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Campaign $campaign
     * @return Response
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        if (request()->ajax()) {
            return response()->json([
                'state' => 'success'
            ]);
        }

        return back()->withSuccess('Successfully deleted');
    }

    public function getUsers()
    {

    }

    public function searchUsers()
    {

    }
}
