@include('translate::_partials._nav_tabs')

<!-- Tab panes -->
<div class="tab-content">
    @foreach(\Netcore\Translator\Helpers\TransHelper::getAllLanguages() as $language)
        <div role="tabpanel" class="tab-pane {{ $loop->first ? 'active' : '' }}"
             id="{{ $language->iso_code }}">

            <div class="form-group{{ $errors->has('translations.' . $language->iso_code . '.name') ? ' has-error' : '' }}">
                <label class="col-md-2 control-label">Name</label>
                <div class="col-md-8">
                    {!! Form::text('translations['.$language->iso_code.'][value]', trans_model(isset($campaign) ? $campaign : null, $language, 'name'), ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="form-group{{ $errors->has('translations.' . $language->iso_code . '.text') ? ' has-error' : '' }}">
                <label class="col-md-2 control-label">Text</label>
                <div class="col-md-8">
                    {!! Form::textarea('translations['.$language->iso_code.'][value]', trans_model(isset($campaign) ? $campaign : null, $language, 'name'), ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    @endforeach
</div>

@if (isset($campaign))
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <i class="fa fa-users"></i> Campaign users
                </div>
                <div class="panel-body">
                    <table class="table datatable">
                        <thead>
                        <tr>
                            <th width="50%">Name / Surname</th>
                            <th width="25%">Email</th>
                            <th width="15%">Phone</th>
                            <th width="10%">Sent?</th>
                            <th width="10%">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="row">
    <div class="col-lg-3">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <i class="fa fa-search"></i> Search users
            </div>
            <div class="panel-body">
                <table class="table filter-data">
                    <thead>
                    <th>Parameter</th>
                    <th>Value</th>
                    </thead>
                    <tbody>
                    @foreach($filters as $key => $filter)
                        <tr>
                            <td>{{ $filter['name'] }}</td>
                            <td>
                                @if ($filter['type'] == 'select')
                                    <select name="filters[{{ $key }}]" class="form-control select2">
                                        @foreach ($filter['values'] as $id => $value)
                                            <option value="{{ $id }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                @elseif ($filter['type'] == 'multi-select')
                                    <select name="filters[{{ $key }}][]" class="form-control select2" multiple>
                                        @foreach ($filter['values'] as $id => $value)
                                            <option value="{{ $id }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                @elseif ($filter['type'] == 'from-to')
                                    <div class="input-group">
                                        <input type="{{ isset($filter['field_type']) ? $filter['field_type'] : 'text' }}"
                                               name="filters[{{ $key }}][from]"
                                               class="form-control {{ isset($filter['class']) ? $filter['class'] : '' }}"
                                               placeholder="No"
                                               min="0"
                                                {{ isset($filter['max']) ? 'max=' . $filter['max'] : '' }}
                                                {{ isset($filter['step']) ? 'step=' . $filter['step'] : '' }}
                                        >
                                        <span class="input-group-addon">-</span>
                                        <input type="{{ isset($filter['field_type']) ? $filter['field_type'] : 'text' }}"
                                               name="filters[{{ $key }}][to]"
                                               class="form-control {{ isset($filter['class']) ? $filter['class'] : '' }}"
                                               placeholder="Līdz"
                                               min="0"
                                                {{ isset($filter['max']) ? 'max=' . $filter['max'] : '' }}
                                                {{ isset($filter['step']) ? 'step=' . $filter['step'] : '' }}
                                        >
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <button type="button" class="btn btn-md btn-primary pull-right search-users">
                    <i class="fa fa-search"></i> Search
                </button>
            </div>
        </div>

    </div>
    <div class="col-lg-9">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <i class="fa fa-users"></i> Found users
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <table class="table search">
                        <thead>
                        <tr>
                            <th width="1%"></th>
                            <th width="50%">Name / Surname</th>
                            <th width="25%">Email</th>
                            <th width="25%">Phone</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <input type="hidden" name="users" class="users"/>
            </div>
        </div>
    </div>
</div>

@section('styles')
    <link href="{{ asset('assets/admin/stylesheets/bootstrap-datetimepicker.css') }}" rel="stylesheet"/>
@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/javascripts/bootstrap-datetimepicker.min.js') }}"></script>
    <script>
        var search_url = '{{ route('admin::campaigns.search-users') }}';
        var users_url = '{{ isset($campaign) ? route('admin::campaigns.get-users', $campaign) : '' }}';
    </script>
    <script src="{{ asset('assets/admin/javascripts/campaigns_form.js') }}"></script>
@endsection
