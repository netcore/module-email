<?php

namespace Modules\Email\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Email\Emails\AutomatedEmails;
use Modules\Email\Http\Requests\Admin\AutomatedEmailRequest;
use Modules\Email\Models\AutomatedEmail;

class AutomatedEmailController extends Controller
{
    /**
     * @var string
     */
    private $viewNamespace = 'email::automated_emails';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $automatedEmails = AutomatedEmail::all();

        return view($this->viewNamespace . '.index', compact('automatedEmails'));
    }

    /**
     * Show the automated_email for editing the specified resource.
     *
     * @param  AutomatedEmail $automatedEmail
     * @return Response
     */
    public function edit(AutomatedEmail $automatedEmail)
    {
        $periodNumber = intval(preg_replace('/[^0-9]+/', '', $automatedEmail->period), 10);
        $periodType = substr($automatedEmail->period, -1);

        return view($this->viewNamespace . '.edit', compact('automatedEmail', 'periodNumber', 'periodType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AutomatedEmailRequest $request
     * @param AutomatedEmail        $automatedEmail
     * @return Response
     */
    public function update(AutomatedEmailRequest $request, AutomatedEmail $automatedEmail)
    {
        $request->merge([
            'is_active' => $request->has('is_active'),
            'period'    => $request->get('period_number') . $request->get('period_type')
        ]);
        $automatedEmail->update($request->all());
        $automatedEmail->updateTranslations($request->get('translations', []));

        return back()->withSuccess('Successfully saved');
    }

    /**
     * @param AutomatedEmail $automatedEmail
     * @return AutomatedEmails
     */
    public function preview(AutomatedEmail $automatedEmail)
    {
        return new AutomatedEmails($automatedEmail, auth()->user());
    }
}
