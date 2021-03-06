@include('translate::_partials._nav_tabs')

<!-- Tab panes -->
<div class="tab-content">
    @foreach(\Netcore\Translator\Helpers\TransHelper::getAllLanguages() as $language)
        <div role="tabpanel" class="tab-pane {{ $loop->first ? 'active' : '' }}"
             id="{{ $language->iso_code }}">

            <div class="form-group{{ $errors->has('translations.' . $language->iso_code . '.name') ? ' has-error' : '' }}">
                <label class="col-md-2 control-label">Name</label>
                <div class="col-md-8">
                    {!! Form::text('translations['.$language->iso_code.'][name]', trans_model(isset($automatedEmail) ? $automatedEmail : null, $language, 'name'), ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="form-group{{ $errors->has('translations.' . $language->iso_code . '.text') ? ' has-error' : '' }}">
                <label class="col-md-2 control-label">Text</label>
                <div class="col-md-8">
                    {!! Form::textarea('translations['.$language->iso_code.'][text]', trans_model(isset($automatedEmail) ? $automatedEmail : null, $language, 'text'), ['class' => 'summernote']) !!}
                </div>
            </div>
        </div>
    @endforeach
</div>

<hr>

@if ($automatedEmail->type === 'period')
    <div class="form-group">
        <label class="col-md-2 control-label">Frequency</label>
        <div class="col-md-8">
            <div class="input-group">
                {!! Form::number('period_number', $periodNumber, ['class' => 'form-control','min' => 1, 'max' => 365]) !!}
                <span class="input-group-addon"></span>
                {!! Form::select('period_type', ['d' => 'day/s', 'w' => 'week/s', 'm' => 'month/s', 'y' => 'year/s'], $periodType, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
@endif

<div class="form-group">
    <label class="col-md-2 control-label">Active?</label>
    <div class="col-md-8">
        {!! Form::checkbox('is_active', null, null) !!}
    </div>
</div>

@section('scripts')
    <script src="{{ versionedAsset('assets/email/admin/js/automated_emails_form.js') }}"></script>
@endsection
