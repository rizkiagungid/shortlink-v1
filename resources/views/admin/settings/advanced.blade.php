@section('site_title', formatTitle([__('Advanced'), __('Settings'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('admin.dashboard'), 'title' => __('Admin')],
    ['title' => __('Settings')],
]])

<h1 class="h2 mb-3 d-inline-block">{{ __('Advanced') }}</h1>

<div class="card border-0 shadow-sm">
    <div class="card-header"><div class="font-weight-medium py-1">{{ __('Advanced') }}</div></div>
    <div class="card-body">

        <ul class="nav nav-pills d-flex flex-fill flex-column flex-md-row mb-3" id="pills-tab" role="tablist">
            <li class="nav-item flex-grow-1 text-center">
                <a class="nav-link active" id="pills-general-tab" data-toggle="pill" href="#pills-general" role="tab" aria-controls="pills-general" aria-selected="true">{{ __('General') }}</a>
            </li>
            <li class="nav-item flex-grow-1 text-center">
                <a class="nav-link" id="pills-crawler-tab" data-toggle="pill" href="#pills-crawler" role="tab" aria-controls="pills-crawler" aria-selected="false">{{ __('Crawler') }}</a>
            </li>
            <li class="nav-item flex-grow-1 text-center">
                <a class="nav-link" id="pills-shortener-tab" data-toggle="pill" href="#pills-shortener" role="tab" aria-controls="pills-shortener" aria-selected="false">{{ __('Shortener') }}</a>
            </li>
            <li class="nav-item flex-grow-1 text-center">
                <a class="nav-link" id="pills-gsb-tab" data-toggle="pill" href="#pills-gsb" role="tab" aria-controls="pills-gsb" aria-selected="false">{{ __('Google Safe Browsing') }}</a>
            </li>
        </ul>

        @include('shared.message')

        <form action="{{ route('admin.settings', 'shortener') }}" method="post" enctype="multipart/form-data">

            @csrf

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-general" role="tabpanel" aria-labelledby="pills-general-tab">
                    <div class="form-group">
                        <label for="i-bad-words">{{ __('Bad words') }}</label>
                        <textarea name="bad_words" id="i-bad-words" class="form-control{{ $errors->has('bad_words') ? ' is-invalid' : '' }}" rows="3">{{ config('settings.bad_words') }}</textarea>
                        @if ($errors->has('bad_words'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('bad_words') }}</strong>
                            </span>
                        @endif
                        <small class="form-text text-muted">{{ __('One per line.') }}</small>
                    </div>
                </div>

                <div class="tab-pane fade" id="pills-crawler" role="tabpanel" aria-labelledby="pills-crawler-tab">
                    <div class="form-group">
                        <label for="i-request-user-agent">{{ __('User-Agent') }}</label>
                        <input type="text" name="request_user_agent" id="i-request-user-agent" class="form-control{{ $errors->has('request_user_agent') ? ' is-invalid' : '' }}" value="{{ old('request_user_agent') ?? config('settings.request_user_agent') }}">
                        @if ($errors->has('request_user_agent'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('request_user_agent') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="i-request-proxy">{{ __('Proxies') }}</label>
                        <textarea name="request_proxy" id="i-request-proxy" class="form-control{{ $errors->has('request_proxy') ? ' is-invalid' : '' }}" rows="3" placeholder="http://username:password@ip:port
">{{ config('settings.request_proxy') }}</textarea>
                        @if ($errors->has('request_proxy'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('request_proxy') }}</strong>
                            </span>
                        @endif
                        <small class="form-text text-muted">{{ __('One per line.') }}</small>
                    </div>
                </div>

                <div class="tab-pane fade" id="pills-shortener" role="tabpanel" aria-labelledby="pills-shortener-tab">
                    <div class="form-group">
                        <label for="i-short-guest">{{ __('Guest') }}</label>
                        <select name="short_guest" id="i-short-guest" class="custom-select{{ $errors->has('short_guest') ? ' is-invalid' : '' }}">
                            @foreach([0 => __('Disabled'), 1 => __('Enabled')] as $key => $value)
                                <option value="{{ $key }}" @if ((old('short_guest') !== null && old('short_guest') == $key) || (config('settings.short_guest') == $key && old('short_guest') == null)) selected @endif>{{ $value }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('short_guest'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('short_guest') }}</strong>
                            </span>
                        @endif
                        <small class="form-text text-muted">{{ __('Allow guests to shorten links.') }}</small>
                    </div>

                    <div class="form-group">
                        <label for="i-short-protocol" class="d-flex align-items-center"><span>{{ __('Domains protocol') }}</span> <span data-tooltip="true" title="{{ __('Use HTTPS only if you are able to generate SSL certificates for the additional domains.') }}" class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">@include('icons.info', ['class' => 'fill-current text-muted width-4 height-4'])</span></label>
                        <select name="short_protocol" id="i-short-protocol" class="custom-select{{ $errors->has('short_protocol') ? ' is-invalid' : '' }}">
                            @foreach(['http' => 'HTTP', 'https' => 'HTTPS'] as $key => $value)
                                <option value="{{ $key }}" @if ((old('short_protocol') !== null && old('short_protocol') == $key) || (config('settings.short_protocol') == $key && old('short_protocol') == null)) selected @endif>{{ $value }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('short_protocol'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('short_protocol') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="i-short-domain" class="d-inline-flex align-items-center"><span class="{{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">{{ __('Domain') }}</span><span class="badge badge-secondary">{{ __('Default') }}</span></label>
                        <select name="short_domain" id="i-short-domain" class="custom-select">
                            @foreach($domains as $domain)
                                <option value="{{ $domain->id }}" @if (config('settings.short_domain') == $domain->id) selected @endif>{{ str_replace(['http://', 'https://'], '', $domain->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="tab-pane fade" id="pills-gsb" role="tabpanel" aria-labelledby="pills-gsb-tab">
                    <div class="form-group">
                        <label for="i-gsb">{{ __('Enabled') }}</label>
                        <select name="gsb" id="i-gsb" class="custom-select{{ $errors->has('gsb') ? ' is-invalid' : '' }}">
                            @foreach([0 => __('No'), 1 => __('Yes')] as $key => $value)
                                <option value="{{ $key }}" @if ((old('gsb') !== null && old('gsb') == $key) || (config('settings.gsb') == $key && old('gsb') == null)) selected @endif>{{ $value }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('gsb'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('gsb') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="i-gsb-key">{{ __('API key') }}</label>
                        <input type="password" name="gsb_key" id="i-gsb-key" class="form-control{{ $errors->has('gsb_key') ? ' is-invalid' : '' }}" value="{{ old('gsb_key') ?? config('settings.gsb_key') }}">
                        @if ($errors->has('gsb_key'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('gsb_key') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>

    </div>
</div>