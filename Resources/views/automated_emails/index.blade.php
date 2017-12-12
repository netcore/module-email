@extends('admin::layouts.master')

@section('content')
    {!! Breadcrumbs::render('admin.automated_emails') !!}

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
                    <h4 class="panel-title">Automated emails</h4>
                </div>
                <div class="panel-body">
                    <div class="table-primary">
                        <table class="table table-bordered datatable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th width="15%" class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($automatedEmails as $automatedEmail)
                                <tr>
                                    <td>
                                        @foreach(\Netcore\Translator\Helpers\TransHelper::getAllLanguages() as $language)
                                            <strong>{{ strtoupper($language->iso_code) }}:</strong> {{ trans_model($automatedEmail, $language, 'name') }}<br>
                                        @endforeach
                                    </td>
                                    <td width="15%" class="text-center">
                                        <button type="button" class="btn btn-default btn-xs preview" data-toggle="modal"
                                                data-target="#previewModal"
                                                data-url="{{ route('admin::automated_emails.preview', $automatedEmail) }}">
                                            <i class="fa fa-eye"></i> Preview
                                        </button>
                                        <a href="{{ route('admin::automated_emails.edit', $automatedEmail) }}"
                                           class="btn btn-xs btn-primary">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('email::_partials._preview_modal')
@endsection

@section('scripts')
    <script src="{{ versionedAsset('assets/email/admin/js/automated_emails_index.js') }}" type="text/javascript"></script>
@endsection
