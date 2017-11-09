@extends('admin::layouts.master')

@section('content')
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
                        <table class="table table-bordered" id="datatable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th class="text-center">Actions</th>
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
                                    <td width="15%" class="text-center">
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
    <script>
        $(function () {
            $('#datatable').DataTable({
                responsive: true,

                columns: [
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        searchable: false,
                        sortable: false,
                        width: '10%',
                        className: 'text-center'
                    }
                ],

                order: [[0, 'asc']]
            })
        });
    </script>
@endsection
