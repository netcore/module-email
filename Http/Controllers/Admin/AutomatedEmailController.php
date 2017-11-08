<?php

namespace Modules\Email\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Email\Models\AutomatedEmail;

class AutomatedEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $automatedEmails = AutomatedEmail::all();

        return view('email::automated_emails.index', compact('automatedEmails'));
    }

    /**
     * Show the automated_email for editing the specified resource.
     *
     * @param  AutomatedEmail $automatedEmail
     * @return Response
     */
    public function edit(AutomatedEmail $automatedEmail)
    {
        return view('email::automated_emails.edit', compact('automatedEmail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AutomatedEmailsRequest $request
     * @param AutomatedEmail         $automatedEmail
     * @return Response
     */
    public function update(AutomatedEmailsRequest $request, AutomatedEmail $automatedEmail)
    {
        $automatedEmail->update($request->all());
        $automatedEmail->updateTranslations($request->get('translations', []));

        return back()->withSuccess('Successfully saved');
    }
}
