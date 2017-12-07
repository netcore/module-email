@extends('admin::layouts.master')

@section('content')
    {!! Breadcrumbs::render('admin.subscribers') !!}

    <div class="page-header">
        <h1>
            <span class="text-muted font-weight-light">
                <i class="page-header-icon fa fa-envelope"></i>
                Subscribers
            </span>
        </h1>
    </div>

    @include('admin::_partials._messages')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="{{ route('admin::subscribers.export', 'xls') }}" class="btn btn-xs btn-success">
                            <i class="fa fa-file-excel-o"></i> Export (XLS)
                        </a>
                        <a href="{{ route('admin::subscribers.export', 'csv') }}" class="btn btn-xs btn-success">
                            <i class="fa fa-file-o"></i> Export (CSV)
                        </a>
                    </div>
                    <h4 class="panel-title">Subscribers</h4>
                </div>
                <div class="panel-body">
                    <div class="table-primary">
                        <table class="table table-bordered datatable">
                            <thead>
                            <tr>
                                <th>Email</th>
                                <th>Subscribed at</th>
                                <th width="15%" class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var pagination_url = '{{ route('admin::subscribers.pagination') }}';
    </script>
    <script src="{{ versionedAsset('assets/email/admin/js/subscribers_index.js') }}" type="text/javascript"></script>
@endsection
