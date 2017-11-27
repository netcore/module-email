@extends('admin::layouts.master')

@section('content')
    {!! Breadcrumbs::render('admin.automated_emails.edit', $automatedEmail) !!}

    <div class="page-header">
        <h1>
            <span class="text-muted font-weight-light">
                <i class="page-header-icon fa fa-envelope"></i>
                Automated emails
            </span>
        </h1>
    </div>

    @include('admin::_partials._messages')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="{{ route('admin::automated_emails.index') }}" class="btn btn-xs btn-primary">
                            <i class="fa fa-undo"></i> Back to list
                        </a>
                    </div>
                    <h4 class="panel-title">Edit automated email</h4>
                </div>
                <div class="panel-body">
                    {!! Form::model($automatedEmail, ['route' => ['admin::automated_emails.update', $automatedEmail], 'method' => 'PATCH', 'class' => 'form-horizontal']) !!}

                    @include('email::automated_emails._form')

                    <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Save</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
