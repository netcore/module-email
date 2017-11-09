@extends('admin::layouts.master')

@section('content')
    @include('admin::_partials._messages')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Subscribers</h4>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered" id="datatable">
                        <thead>
                        <tr>
                            <th>Email</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($subscribers as $subscriber)
                            <tr>
                                <td>{{ $subscriber->email }}</td>
                                <td width="15%" class="text-center">
                                    <a href="{{ route('admin::subscribers.destroy', $subscriber) }}"
                                       class="btn btn-danger btn-xs confirm-delete" data-id="{{ $subscriber->id }}">
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
@endsection

@section('scripts')
    <script>
        $(function () {
            $('#datatable').DataTable({
                responsive: true,

                columns: [
                    {
                        data: 'email',
                        name: 'email'
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
