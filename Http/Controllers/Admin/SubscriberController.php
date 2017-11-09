<?php

namespace Modules\Email\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Email\Models\Subscriber;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $subscribers = Subscriber::all();

        return view('email::subscribers.index', compact('subscribers'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Subscriber $subscriber
     * @return Response
     */
    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();

        if (request()->ajax()) {
            return response()->json([
                'state' => 'success'
            ]);
        }

        return back()->withSuccess('Successfully deleted');
    }
}
