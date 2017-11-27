@include('translate::_partials._nav_tabs')

<!-- Tab panes -->
<div class="tab-content">
    @foreach(\Netcore\Translator\Helpers\TransHelper::getAllLanguages() as $language)
        <div role="tabpanel" class="tab-pane {{ $loop->first ? 'active' : '' }}"
             id="{{ $language->iso_code }}">

            <div class="form-group{{ $errors->has('translations.' . $language->iso_code . '.name') ? ' has-error' : '' }}">
                <label class="col-md-2 control-label">Name</label>
                <div class="col-md-8">
                    {!! Form::text('translations['.$language->iso_code.'][name]', trans_model(isset($campaign) ? $campaign : null, $language, 'name'), ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="form-group{{ $errors->has('translations.' . $language->iso_code . '.text') ? ' has-error' : '' }}">
                <label class="col-md-2 control-label">Text</label>
                <div class="col-md-8">
                    {!! Form::textarea('translations['.$language->iso_code.'][text]', trans_model(isset($campaign) ? $campaign : null, $language, 'text'), ['class' => 'summernote']) !!}
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
                    <div class="table-primary">
                        <table class="table table-bordered datatable">
                            <thead>
                            <tr>
                                <th>Name / Surname</th>
                                <th>Email</th>
                                <th>Sent?</th>
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
    </div>
@endif

<div class="row">
    <div class="col-lg-3">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <i class="fa fa-search"></i> Search users
            </div>
            <div class="panel-body filter-data">
                <div class="form-group">
                    <select name="receivers" class="form-control" v-model="receivers">
                        <option value="users">Filter users</option>
                        <option value="subscribers">Subscribers</option>
                    </select>
                </div>
                <div class="filters" v-if="receivers === 'users'">
                    @foreach($filters as $key => $filter)
                        <div class="form-group">
                            {!! Form::label($key, $filter['name']) !!}
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
                                           placeholder="From"
                                           min="0"
                                            {{ isset($filter['max']) ? 'max=' . $filter['max'] : '' }}
                                            {{ isset($filter['step']) ? 'step=' . $filter['step'] : '' }}
                                    >
                                    <span class="input-group-addon">-</span>
                                    <input type="{{ isset($filter['field_type']) ? $filter['field_type'] : 'text' }}"
                                           name="filters[{{ $key }}][to]"
                                           class="form-control {{ isset($filter['class']) ? $filter['class'] : '' }}"
                                           placeholder="To"
                                           min="0"
                                            {{ isset($filter['max']) ? 'max=' . $filter['max'] : '' }}
                                            {{ isset($filter['step']) ? 'step=' . $filter['step'] : '' }}
                                    >
                                </div>
                            @elseif ($filter['type'] == 'text')
                                <input type="text" name="filters[{{ $key }}]" class="form-control"/>
                            @endif
                        </div>
                    @endforeach
                </div>
                <br/>
                <button type="button" class="btn btn-md btn-primary pull-right" @click="searchReceivers">
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
                    <div class="table-primary">
                        <table class="table table-bordered search">
                            <thead>
                            <tr>
                                <th width="2%">#</th>
                                <th width="50%">Name / Surname</th>
                                <th width="25%">Email</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <input type="hidden" name="except" value="[]" class="users"/>
            </div>
        </div>
    </div>
</div>

@section('styles')
@endsection

@section('scripts')
    <script>
        var search_url = '{{ route('admin::campaigns.search-receivers') }}';
        var receivers_url = '{{ isset($campaign) ? route('admin::campaigns.get-receivers', $campaign) : '' }}';
    </script>
    <script src="{{ versionedAsset('assets/email/admin/js/campaigns_form.js') }}"></script>
@endsection
