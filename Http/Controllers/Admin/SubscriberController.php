<?php

namespace Modules\Email\Http\Controllers\Admin;

use Carbon\Carbon;
use Excel;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Writers\CellWriter;
use Modules\Email\Datatables\SubscribersDatatable;
use Modules\Email\Models\Subscriber;

class SubscriberController extends Controller
{
    use SubscribersDatatable;

    /**
     * @var string
     */
    private $viewNamespace = 'email::subscribers';

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

    /**
     * @param string $type
     */
    public function export($type = 'xlsx')
    {
        $subscribers = Subscriber::all();

        if (!in_array($type, ['xlsx', 'csv'])) {
            $type = 'xlsx';
        }

        $name = 'Subscribers-' . Carbon::now()->format('d.m.Y-H:i');
        Excel::create($name, function ($excel) use ($subscribers) {
            $excel->sheet('Subscribers', function ($sheet) use ($subscribers) {
                $sheet->row(1, ['Email', 'Subscribed At']);
                $sheet->row(1, function (CellWriter $row) {
                    $row->setFontWeight(true);
                    $row->setBackground('#cccccc');
                });

                $r = 2;

                foreach ($subscribers as $subscriber) {
                    $sheet->row($r, [$subscriber->email, $subscriber->created_at->format('d.m.Y H:i')]);
                    $r++;
                }
            });

        })->download($type);
    }
}
