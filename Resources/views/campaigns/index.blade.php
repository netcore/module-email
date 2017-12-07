@extends('admin::layouts.master')

@section('content')
    {!! Breadcrumbs::render('admin.campaigns') !!}

    <div class="page-header">
        <h1>
            <span class="text-muted font-weight-light">
                <i class="page-header-icon fa fa-envelope"></i>
                Campaigns
            </span>
        </h1>
    </div>

    @include('admin::_partials._messages')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="{{ route('admin::campaigns.create') }}" class="btn btn-xs btn-success">
                            <i class="fa fa-plus"></i> Create
                        </a>
                    </div>
                    <h4 class="panel-title">Campaigns</h4>
                </div>
                <div class="panel-body">
                    <div class="table-primary">
                        <table class="table table-bordered datatable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th width="15%" class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($campaigns as $campaign)
                                <tr>
                                    <td>
                                        @foreach(\Netcore\Translator\Helpers\TransHelper::getAllLanguages() as $language)
                                            <strong>{{ strtoupper($language->iso_code) }}:</strong> {{ trans_model($campaign, $language, 'name') }}<br>
                                        @endforeach
                                    </td>
                                    <td width="10%">
                                        {{ $campaign->getStatus() }}
                                    </td>
                                    <td width="15%" class="text-center">
                                        <a href="{{ route('admin::campaigns.preview', $campaign) }}"
                                           class="btn btn-xs btn-default">
                                            <i class="fa fa-eye"></i> Preview
                                        </a>
                                        @if ($campaign->inProgress())
                                            <a href="{{ route('admin::campaigns.stop', $campaign) }}"
                                               class="btn btn-warning btn-xs"><i class="fa fa-stop"></i> Stop</a>
                                        @endif
                                        @if ($campaign->isStopped() || $campaign->notStarted())
                                            <a href="{{ route('admin::campaigns.start', $campaign) }}"
                                               class="btn btn-success btn-xs"><i class="fa fa-play"></i> {{ $campaign->notStarted() ? 'Start' : 'Resume' }}</a>
                                        @endif
                                        <a href="{{ route('admin::campaigns.edit', $campaign) }}"
                                           class="btn btn-xs btn-primary">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <a href="{{ route('admin::campaigns.destroy', $campaign) }}"
                                           class="btn btn-danger btn-xs confirm-delete" data-id="{{ $campaign->id }}">
                                            <i class="fa fa-trash"></i> Delete
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
@endsection

@section('scripts')
    <script src="{{ versionedAsset('assets/email/admin/js/campaigns_index.js') }}" type="text/javascript"></script>
@endsection
