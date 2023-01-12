@section('site_title', formatTitle([__('Preferences'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['url' => route('account'), 'title' => __('Account')],
    ['title' => __('Preferences')]
]])

<div class="d-flex"><h1 class="h2 mb-3 text-break">{{ __('Preferences') }}</h1></div>

<div class="card border-0 shadow-sm">
    <div class="card-header">
        <div class="font-weight-medium py-1">
            {{ __('Preferences') }}
        </div>
    </div>
    <div class="card-body">
        <ul class="nav nav-pills d-flex flex-fill flex-column flex-md-row mb-3" id="pills-tab" role="tablist">
            <li class="nav-item flex-grow-1 text-center">
                <a class="nav-link active" id="pills-shortener-tab" data-toggle="pill" href="#pills-shortener" role="tab" aria-controls="pills-shortener" aria-selected="true">{{ __('Shortener') }}</a>
            </li>
        </ul>

        @include('shared.message')

        <form action="{{ route('account.preferences') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-shortener" role="tabpanel" aria-labelledby="pills-shortener-tab">
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="i-default-domain" class="d-inline-flex align-items-center"><span class="{{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">{{ __('Domain') }}</span><span class="badge badge-secondary">{{ __('Default') }}</span></label>
                            </div>
                            <div class="col-auto">
                                @cannot('domains', ['App\Models\Link'])
                                    @if(paymentProcessors())
                                        <a href="{{ route('pricing') }}" data-tooltip="true" title="{{ __('Unlock feature') }}">@include('icons.lock-open', ['class' => 'fill-current text-primary width-4 height-4'])</a>
                                    @endif
                                @endcannot
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">@include('icons.website', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                            </div>
                            <select name="default_domain" id="i-default-domain" class="custom-select{{ $errors->has('default_domain') ? ' is-invalid' : '' }}">
                                @foreach($domains as $domain)
                                    <option value="{{ $domain->id }}" @if((Auth::user()->default_domain == $domain->id && old('default_domain') == null) || ($domain->id == old('default_domain'))) selected @endif>{{ str_replace(['http://', 'https://'], '', $domain->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('default_domain'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('default_domain') }}</strong>
                            </span>
                        @endif
                    </div>
        
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="i-default-space" class="d-inline-flex align-items-center"><span class="{{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">{{ __('Space') }}</span><span class="badge badge-secondary">{{ __('Default') }}</span></label>
                            </div>
                            <div class="col-auto">
                                @cannot('spaces', ['App\Models\Link'])
                                    @if(paymentProcessors())
                                        <a href="{{ route('pricing') }}" data-tooltip="true" title="{{ __('Unlock feature') }}">@include('icons.lock-open', ['class' => 'fill-current text-primary width-4 height-4'])</a>
                                    @endif
                                @endcannot
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">@include('icons.workspaces', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                            </div>
                            <select name="default_space" id="i-default-space" class="custom-select{{ $errors->has('default_space') ? ' is-invalid' : '' }}" @cannot('spaces', ['App\Models\Link']) disabled @endcan>
                                <option value="">{{ __('None') }}</option>
                                @foreach($spaces as $space)
                                    <option value="{{ $space->id }}" @if((Auth::user()->default_space == $space->id && old('default_space') == null) || ($space->id == old('default_space'))) selected @endif>{{ $space->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('default_space'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('default_space') }}</strong>
                            </span>
                        @endif
                    </div>
        
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="i-default-stats" class="d-inline-flex align-items-center"><span class="{{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">{{ __('Stats') }}</span><span class="badge badge-secondary">{{ __('Default') }}</span></label>
                            </div>
                            <div class="col-auto">
                                @cannot('stats', ['App\Models\Link'])
                                    @if(paymentProcessors())
                                        <a href="{{ route('pricing') }}" data-tooltip="true" title="{{ __('Unlock feature') }}">@include('icons.lock-open', ['class' => 'fill-current text-primary width-4 height-4'])</a>
                                    @endif
                                @endcannot
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">@include('icons.bar-chart', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                            </div>
                            <select name="default_stats" id="i-default-stats" class="custom-select{{ $errors->has('default_stats') ? ' is-invalid' : '' }}" @cannot('stats', ['App\Models\Link']) disabled @endcan>
                                @foreach([0 => __('Public'), 1 => __('Private')] as $key => $value)
                                    <option value="{{ $key }}" @if((Auth::user()->default_stats == $key && old('default_stats') == null) || (old('default_stats') !== null && old('default_stats') == $key)) selected @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('default_stats'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('default_stats') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
                <div class="col-auto">
                </div>
            </div>
        </form>
    </div>
</div>