<?php

namespace Modules\Email\Datatables;

use Modules\Email\Models\Subscriber;

trait SubscribersDatatable
{

    /**
     * Prepare data for jQuery datatable
     *
     * @return mixed
     * @throws \Exception
     */
    public function pagination()
    {
        return datatables()->of(Subscriber::select('id', 'email', 'created_at'))
            ->editColumn('created_at', function ($subscriber) {
                $createdAt = $subscriber->created_at;

                return $createdAt ? $createdAt->format(config('netcore.module-admin.date_format', 'd.m.Y H:i')) : '-';
            })
            ->addColumn('actions', function ($subscriber) {
                return view('email::subscribers.tds.actions', compact('subscriber'))->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
