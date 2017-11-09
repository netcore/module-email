<?php

namespace Modules\Email\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Email\Models\Subscriber;

class SubscriberController extends Controller
{
    /**
     * @var string
     */
    private $viewNamespace = 'email::subscribers';

    /**
     * @return mixed
     */
    public function pagination()
    {
        return datatables()->of(Subscriber::select('id', 'email'))->addColumn('actions', function ($subscriber) {
            return view('email::subscribers.tds.actions', compact('subscriber'))->render();
        })->rawColumns(['actions'])->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view($this->viewNamespace . '.index');
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
