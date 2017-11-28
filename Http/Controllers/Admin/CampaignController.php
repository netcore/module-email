<?php

namespace Modules\Email\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Email\Http\Requests\Admin\CampaignRequest;
use Modules\Email\Models\Campaign;
use Modules\Email\Models\CampaignReceiver;

class CampaignController extends Controller
{
    /**
     * @var string
     */
    private $viewNamespace = 'email::campaigns';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $campaigns = Campaign::all();

        return view($this->viewNamespace . '.index', compact('campaigns'));
    }

    /**
     * Show the automated_email for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $filters = email()->getFilters();

        return view($this->viewNamespace . '.create', compact('filters'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CampaignRequest $request
     * @return Response
     */
    public function store(CampaignRequest $request)
    {
        $campaign = Campaign::create([]);
        $campaign->storeTranslations($request->get('translations', []));

        // Store receivers
        $except = json_decode($request->get('except', []));
        $receivers = email()->searchQuery()->get();
        $receivers = $receivers->reject(function ($receiver) use ($except) {
            return in_array($receiver->email, $except);
        });
        $receivers->each(function ($receiver) use ($campaign) {
            $campaign->receivers()->create([
                'user_id' => $receiver->id,
                'email'   => $receiver->email
            ]);
        });

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
        $filters = email()->getFilters();

        return view($this->viewNamespace . '.edit', compact('campaign', 'filters'));
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
        $campaign->updateTranslations($request->get('translations', []));

        // Store receivers
        $except = json_decode($request->get('except', []));
        $receivers = email()->searchQuery()->get();
        $receivers = $receivers->reject(function ($receiver) use ($except) {
            return in_array($receiver->email, $except);
        });
        $receivers->each(function ($receiver) use ($campaign) {
            $campaign->receivers()->firstOrCreate([
                'email' => $receiver->email
            ], [
                'user_id' => $receiver->id,
                'email'   => $receiver->email
            ]);
        });

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

        return response()->json([
            'state' => 'success'
        ]);
    }

    /**
     * Remove receiver from campaign
     *
     * @param Campaign         $campaign
     * @param CampaignReceiver $receiver
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyReceiver(Campaign $campaign, CampaignReceiver $receiver)
    {
        $receiver = $campaign->receivers()->find($receiver);

        if (!$receiver) {
            return response()->json([
                'message' => 'Receiver not found!'
            ], 422);
        }

        $receiver->delete();

        return response()->json([
            'state' => 'success'
        ]);
    }

    /**
     * Start the campaign
     *
     * @param Campaign $campaign
     * @return mixed
     */
    public function start(Campaign $campaign)
    {
        $campaign->start();

        return back()->withSuccess('Campaign started!');
    }

    /**
     * Stop the campaign
     *
     * @param Campaign $campaign
     * @return mixed
     */
    public function stop(Campaign $campaign)
    {
        $campaign->stop();

        return back()->withSuccess('Campaign stopped!');
    }

    /**
     * Search users based on filters
     *
     * @param Request $request
     * @return mixed
     */
    public function searchReceivers()
    {
        return email()->searchReceivers();
    }

    /**
     * Get campaign receivers
     *
     * @param Campaign $campaign
     * @return mixed
     */
    public function getReceivers(Campaign $campaign)
    {
        // TODO: dont use collection
        $campaign->receivers->load('user');
        return datatables()->of($campaign->receivers)->addColumn('user', function ($receiver) {
            return view('email::campaigns.tds.user', compact('receiver'))->render();
        })->addColumn('sent', function ($receiver) {
            return view('email::campaigns.tds.sent', compact('receiver'))->render();
        })->addColumn('actions', function ($receiver) use ($campaign) {
            return view('email::campaigns.tds.actions', compact('campaign', 'receiver'))->render();
        })->rawColumns(['sent', 'actions'])->make(true);
    }
}
