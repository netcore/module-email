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
                        <table class="table table-bordered receivers-table">
                            <thead>
                            <tr>
                                <th>Email</th>
                                <th class="text-center" width="10%">Sent?</th>
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
    <div class="col-lg-4">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <i class="fa fa-search"></i> Search users
            </div>
            <div class="panel-body filter-data">
                <div class="form-group">
                    <select name="receivers" class="form-control" v-model="receivers" @change="resetReceivers()">
                        <option value="all-users">All users</option>
                        <option value="users">Filter users</option>
                        <option value="subscribers">Subscribers</option>
                    </select>
                </div>
                <div class="filters" v-if="receivers === 'users'">
                    <hr>
                    <div v-if="Object.keys(filters).length">
                        <div v-for="(filter, key) in filters" class="form-group">
                            <label v-text="filter.name"></label>
                            <template v-if="filter.type === 'select'">
                                <select2
                                        :data="filter.values"
                                        :name="'filters['+key+']'"
                                        :placeholder="'Please select'"
                                        v-model="values[key]"
                                ></select2>
                            </template>
                            <template v-if="filter.type === 'multi-select'">
                                <select2
                                        :data="filter.values"
                                        :name="'filters['+key+'][]'"
                                        :placeholder="'Please select'"
                                        :multiple="true"
                                        v-model="values[key]"
                                ></select2>
                            </template>
                            <template v-if="filter.type === 'from-to'">
                                <input type="text"
                                       :name="'filters['+key+'][from]'"
                                       class="form-control"
                                       placeholder="From"
                                       v-model="values[key].from"
                                >
                                <span class="input-group-addon">-</span>
                                <input type="text"
                                       :name="'filters['+key+'][to]'"
                                       class="form-control"
                                       placeholder="To"
                                       v-model="values[key].to"
                                >
                            </template>
                            <template v-if="filter.type === 'text'">
                                <input type="text"
                                       :name="'filters['+key+']'"
                                       class="form-control"
                                       placeholder=""
                                       v-model="values[key]"
                                >
                            </template>
                        </div>
                    </div>
                    <div class="v-cloak--hidden" v-else>No filters!</div>
                </div>

                <div class="form-group"
                     v-if="Object.keys(filters).length || receivers === 'all-users' || receivers === 'subscribers'">
                    <button type="button" class="btn btn-md btn-primary pull-right" @click="searchReceivers">
                        <i class="fa fa-search"></i> Search
                    </button>
                </div>
            </div>
        </div>

    </div>
    <div class="col-lg-8">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <i class="fa fa-users"></i> Found users
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="table-primary">
                        <table class="table table-bordered search-table">
                            <thead>
                            <tr>
                                <th width="2%">#</th>
                                <th width="98%">Email</th>
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

    <input id="filters-json" type="hidden" value="{{$filters->toJson()}}">
</div>

@section('scripts')
    <script>
        var search_url = '{{ route('admin::campaigns.search-receivers') }}';
        var receivers_url = '{{ isset($campaign) ? route('admin::campaigns.get-receivers', $campaign) : '' }}';
        var filters = JSON.parse(jQuery('#filters-json').val());
    </script>
    <script src="{{ versionedAsset('assets/email/admin/js/campaigns_form.js') }}"></script>
@endsection
