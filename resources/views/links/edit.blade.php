@section('site_title', formatTitle([__('Edit'), __('Link'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => request()->is('admin/*') ? route('admin.dashboard') : route('dashboard'), 'title' => request()->is('admin/*') ? __('Admin') : __('Home')],
    ['url' => request()->is('admin/*') ? route('admin.links') : route('links'), 'title' => __('Links')],
    ['title' => __('Edit')],
]])

<div class="d-flex">
    <h1 class="h2 mb-0 flex-grow-1 text-break">{{ __('Edit') }}</h1>
</div>

<div class="card border-0 shadow-sm mt-3">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('Link') }}</div>
            </div>

            <div class="col-auto">
                <div class="form-row flex-nowrap">
                    <div class="col">
                        @include('shared.buttons.copy-link', ['class' => 'btn-sm text-primary'])
                    </div>

                    <div class="col">
                        @include('links.partials.menu')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ request()->is('admin/*') ? route('admin.links.edit', $link->id) : route('links.edit', $link->id) }}" method="post" enctype="multipart/form-data" autocomplete="off">
            @csrf

            @if(request()->is('admin/*'))
                <input type="hidden" name="user_id" value="{{ isset($link->user) ? $link->user->id : '0' }}">
            @endif

            <div class="row mx-n2">
                <div class="col-12 px-2">
                    <label for="i-url">{{ __('Link') }}</label>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">@include('icons.link', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                            </div>
                            <input type="text" dir="ltr" name="url" class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}" autocapitalize="none" spellcheck="false" id="i-url" value="{{ old('url') ?? $link->url }}">
                            <div class="input-group-append" data-tooltip="true" title="{{ __('UTM builder') }}">
                                <a href="#" class="btn text-secondary bg-transparent input-group-text d-flex align-items-center" data-toggle="modal" data-target="#utm-modal" id="utm-builder">
                                    @include('icons.label', ['class' => 'fill-current width-4 height-4'])
                                </a>
                            </div>
                        </div>
                        @if ($errors->has('url'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('url') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-lg-6 px-2">
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="i-domain">{{ __('Domain') }}</label>
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
                            <select name="domain" id="i-domain" class="custom-select{{ $errors->has('domain') ? ' is-invalid' : '' }}" disabled>
                                <option value="">{{ __('None') }}</option>
                                @foreach($domains as $domain)
                                    <option value="{{ $domain->id }}" @if((old('domain') !== null && old('domain') == $domain->id) || ($link->domain_id == $domain->id && old('domain') == null)) selected @endif>{{ $domain->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('domain'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('domain') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-lg-6 px-2">
                    <div class="form-group">
                        <label for="i-alias">{{ __('Alias') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">@include('icons.abc', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                            </div>
                            <input type="text" name="alias" class="form-control{{ $errors->has('alias') ? ' is-invalid' : '' }}" autocapitalize="none" spellcheck="false" id="i-alias" value="{{ old('alias') ?? $link->alias }}">
                        </div>
                        @if ($errors->has('alias'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('alias') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-lg-6 px-2">
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="i-space">{{ __('Space') }}</label>
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
                            <select name="space" id="i-space" class="custom-select{{ $errors->has('space') ? ' is-invalid' : '' }}" @cannot('spaces', ['App\Models\Link']) disabled @endcannot>
                                <option value="">{{ __('None') }}</option>
                                @foreach($spaces as $space)
                                    <option value="{{ $space->id }}" @if((old('space') !== null && old('space') == $space->id) || ($link->space_id == $space->id && old('space') == null)) selected @endif>{{ $space->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('space'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('space') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-lg-6 px-2">
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="i-password">{{ __('Password') }}</label>
                            </div>
                            <div class="col-auto">
                                @cannot('password', ['App\Models\Link'])
                                    @if(paymentProcessors())
                                        <a href="{{ route('pricing') }}" data-tooltip="true" title="{{ __('Unlock feature') }}">@include('icons.lock-open', ['class' => 'fill-current text-primary width-4 height-4'])</a>
                                    @endif
                                @endcannot
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text cursor-pointer" data-tooltip="true" data-title="{{ __('Show password') }}" data-password="i-password" data-password-show="{{ __('Show password') }}" data-password-hide="{{ __('Hide password') }}">@include('icons.lock', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                            </div>
                            <input type="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="i-password" value="{{ request()->is('admin/*') ? '' : (old('password') ?? $link->password) }}" autocomplete="new-password" @if(request()->is('admin/*')) disabled @else @cannot('password', ['App\Models\Link']) disabled @endcannot @endif>
                        </div>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-lg-6 px-2">
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="i-expiration-date">{{ __('Expiration date') }}</label>
                            </div>
                            <div class="col-auto">
                                @cannot('expiration', ['App\Models\Link'])
                                    @if(paymentProcessors())
                                        <a href="{{ route('pricing') }}" data-tooltip="true" title="{{ __('Unlock feature') }}">@include('icons.lock-open', ['class' => 'fill-current text-primary width-4 height-4'])</a>
                                    @endif
                                @endcannot
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">@include('icons.date-range', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                            </div>
                            <input type="date" name="expiration_date" class="form-control{{ $errors->has('expiration_date') ? ' is-invalid' : '' }}" id="i-expiration-date" placeholder="YYYY-MM-DD" value="{{ old('expiration_date') ?? ($link->ends_at ? $link->ends_at->tz(Auth::user()->timezone ?? config('app.timezone'))->format('Y-m-d') : '') }}" @cannot('expiration', ['App\Models\Link']) disabled @endcannot>
                            <input type="time" name="expiration_time" class="form-control{{ $errors->has('expiration_time') ? ' is-invalid' : '' }}" placeholder="HH:MM" value="{{ old('expiration_time') ?? ($link->ends_at ? $link->ends_at->tz(Auth::user()->timezone ?? config('app.timezone'))->format('H:i') : '') }}" @cannot('expiration', ['App\Models\Link']) disabled @endcannot>
                            <div class="input-group-append">
                                <div class="input-group-text">@include('icons.expire', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                            </div>
                        </div>
                        <div class="row no-gutters">
                            <div class="col">
                                @if ($errors->has('expiration_date'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('expiration_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col">
                                @if ($errors->has('expiration_time'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('expiration_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6 px-2">
                    <div class="form-group">
                        <div class="row no-gutters">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col"><label for="i-expiration-url">{{ __('Expiration link') }}</label></div>
                                    <div class="col-auto">
                                        @cannot('expiration', ['App\Models\Link'])
                                            @if(paymentProcessors())
                                                <a href="{{ route('pricing') }}" data-tooltip="true" title="{{ __('Unlock feature') }}">@include('icons.lock-open', ['class' => 'fill-current text-primary width-4 height-4'])</a>
                                            @endif
                                        @endcannot
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 {{ (__('lang_dir') == 'rtl' ? 'text-left' : 'text-right') }}">
                                <label for="i-expiration-clicks">{{ __('Clicks') }}</label>
                                @cannot('expiration', ['App\Models\Link'])
                                    @if(paymentProcessors())
                                        <a href="{{ route('pricing') }}" data-tooltip="true" title="{{ __('Unlock feature') }}">@include('icons.lock-open', ['class' => 'fill-current text-primary width-4 height-4'])</a>
                                    @endif
                                @endcannot
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">@include('icons.link', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                            </div>
                            <input type="text" dir="ltr" name="expiration_url" id="i-expiration-url" class="form-control{{ $errors->has('expiration_url') ? ' is-invalid' : '' }}" autocapitalize="none" spellcheck="false" value="{{ old('expiration_url') ?? $link->expiration_url }}" @cannot('expiration', ['App\Models\Link']) disabled @endcannot>
                            <input type="number" name="expiration_clicks" id="i-expiration-clicks" class="form-control {{ $errors->has('expiration_clicks') ? ' is-invalid' : '' }}" autocapitalize="none" spellcheck="false" value="{{ old('expiration_clicks') ?? $link->expiration_clicks }}" @cannot('expiration', ['App\Models\Link']) disabled @endcannot>
                            <div class="input-group-append">
                                <div class="input-group-text">@include('icons.mouse', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                            </div>
                        </div>
                        <div class="row no-gutters">
                            <div class="col-6">
                                @if ($errors->has('expiration_url'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('expiration_url') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-6">
                                @if ($errors->has('expiration_clicks'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('expiration_clicks') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6 px-2">
                    <div class="form-group">
                        <div class="row no-gutters">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col"><label for="i-privacy">{{ __('Stats') }}</label></div>
                                    <div class="col-auto">
                                        @cannot('stats', ['App\Models\Link'])
                                            @if(paymentProcessors())
                                                <a href="{{ route('pricing') }}" data-tooltip="true" title="{{ __('Unlock feature') }}">@include('icons.lock-open', ['class' => 'fill-current text-primary width-4 height-4'])</a>
                                            @endif
                                        @endcannot
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 {{ (__('lang_dir') == 'rtl' ? 'text-left' : 'text-right') }}">
                                <label for="i-privacy-password">{{ __('Password') }}</label>
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
                            <select name="privacy" id="i-privacy" class="custom-select{{ $errors->has('privacy') ? ' is-invalid' : '' }}" @cannot('stats', ['App\Models\Link']) disabled @endcannot>
                                @foreach([1 => __('Private'), 0 => __('Public'), 2 => __('Password')] as $key => $value)
                                    <option value="{{ $key }}" @if ((old('privacy') !== null && old('privacy') == $key) || ($link->privacy == $key && old('privacy') == null)) selected @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                            <input type="password" name="privacy_password" class="form-control{{ $errors->has('privacy_password') ? ' is-invalid' : '' }}" id="i-privacy-password" value="{{ request()->is('admin/*') ? '' : (old('privacy_password') ?? $link->privacy_password) }}" autocomplete="new-password" @if(request()->is('admin/*')) disabled @else @cannot('stats', ['App\Models\Link']) disabled @endcannot @endif>
                            <div class="input-group-append">
                                <div class="input-group-text cursor-pointer" data-tooltip="true" data-title="{{ __('Show password') }}" data-password="i-privacy-password" data-password-show="{{ __('Show password') }}" data-password-hide="{{ __('Hide password') }}">@include('icons.lock', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                            </div>
                        </div>
                        <div class="row no-gutters">
                            <div class="col-6">
                                @if ($errors->has('privacy'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('privacy') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-6">
                                @if ($errors->has('privacy_password'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $errors->first('privacy_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6 px-2">
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="i-disabled">{{ __('Disabled') }}</label>
                            </div>
                            <div class="col-auto">
                                @cannot('disabled', ['App\Models\Link'])
                                    @if(paymentProcessors())
                                        <a href="{{ route('pricing') }}" data-tooltip="true" title="{{ __('Unlock feature') }}">@include('icons.lock-open', ['class' => 'fill-current text-primary width-4 height-4'])</a>
                                    @endif
                                @endcannot
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">@include('icons.block', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                            </div>
                            <select name="disabled" id="i-disabled" class="custom-select{{ $errors->has('disabled') ? ' is-invalid' : '' }}" @cannot('disabled', ['App\Models\Link']) disabled @endcannot>
                                @foreach([0 => __('No'), 1 => __('Yes')] as $key => $value)
                                    <option value="{{ $key }}" @if ((old('disabled') !== null && old('disabled') == $key) || ($link->disabled == $key && old('disabled') == null)) selected @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('disabled'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('disabled') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-12 px-2">
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="i-pixels">{{ __('Pixels') }}</label>
                            </div>
                            <div class="col-auto">
                                @cannot('pixels', ['App\Models\Link'])
                                    @if(paymentProcessors())
                                        <a href="{{ route('pricing') }}" data-tooltip="true" title="{{ __('Unlock feature') }}">@include('icons.lock-open', ['class' => 'fill-current text-primary width-4 height-4'])</a>
                                    @endif
                                @endcannot
                            </div>
                        </div>

                        <input type="hidden" name="pixels[]" value="">
                        <select name="pixels[]" id="i-pixels" class="custom-select{{ $errors->has('pixels') ? ' is-invalid' : '' }}" size="{{ (count($pixels) == 0 ? 1 : 3) }}" @cannot('pixels', ['App\Models\Link']) disabled @endcannot multiple>
                            @foreach($pixels as $pixel)
                                <option value="{{ $pixel->id }}" @if((old('pixels') !== null && in_array($pixel->id, old('pixels'))) || ($link->pixels->contains('id', $pixel->id) && old('pixels') == null)) selected @endif>{{ $pixel->name }} ({{ config('pixels')[$pixel->type]['name'] }})</option>
                            @endforeach
                        </select>
                        @if ($errors->has('pixels'))
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $errors->first('pixels') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-12 px-2">
                    <div class="form-group">
                    <label for="i-target-type">{{ __('Targeting') }}</label>

                    <div class="mb-3">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            @foreach([  ['title' => __('None'), 'value' => 0, 'icon' => '', 'input' => 'empty'],
                                        ['title' => __('Country'), 'value' => 1, 'icon' => 'flag', 'input' => 'country'],
                                        ['title' => __('Platform'), 'value' => 2, 'icon' => 'devices', 'input' => 'platform'],
                                        ['title' => __('Language'), 'value' => 3, 'icon' => 'language', 'input' => 'language'],
                                        ['title' => __('Rotation'), 'value' => 4, 'icon' => 'cached', 'input' => 'rotation']
                                        ] as $targetButton)
                                <label class="btn btn-outline-{{ ($errors->has($targetButton['input'].'.*.key') || $errors->has($targetButton['input'].'.*.value') ? 'danger' : 'secondary') }} d-flex align-items-center{{ old('target_type') !== null ? (old('target_type') == $targetButton['value'] ? ' active' : '') : ($link->target_type == $targetButton['value'] ? ' show active' : '') }}">
                                    <input type="radio" name="target_type" value="{{ $targetButton['value'] }}" data-target="#{{ $targetButton['input'] }}-container" id="{{ old('target_type') !== null ? (old('target_type') == $targetButton['value'] ? 'i-target-type' : Str::random(16)) : ($link->target_type == $targetButton['value'] ? 'i-target-type' : Str::random(16)) }}" {{ old('target_type') !== null ? (old('target_type') == $targetButton['value'] ? ' checked' : '') : ($link->target_type == $targetButton['value'] ? ' checked' : '') }}>
                                    @if($targetButton['icon'])
                                        @include('icons.'.$targetButton['icon'], ['class' => 'width-4 height-4 fill-current'])
                                    @endif
                                    <span class="d-md-inline-block {{ ($targetButton['value'] ? (__('lang_dir') == 'rtl' ? 'd-none mr-2' : 'd-none ml-2') : '') }}">&#8203;{{ $targetButton['title'] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="tab-content">
                        <div id="empty-container" class="tab-pane fade{{ old('target_type') !== null ? (old('target_type') == 0 ? ' show active' : '') : ($link->target_type == 0 ? ' show active' : '') }}"></div>

                        <div id="country-container" class="tab-pane fade{{ old('target_type') !== null ? (old('target_type') == 1 ? ' show active' : '') : ($link->target_type == 1 ? ' show active' : '') }}">
                            <input name="country[empty][key]" type="hidden" disabled>
                            <input name="country[empty][value]" type="hidden" disabled>

                            <div class="input-content">
                                <div class="row mx-n2 d-none input-template">
                                    <div class="col-12 col-md px-2">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">@include('icons.flag', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                                                </div>
                                                <select name="country_key[]" data-input="key" class="custom-select" disabled>
                                                    <option value="" selected>{{ __('Country') }}</option>
                                                    @foreach(config('countries') as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col px-2">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">@include('icons.link', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                                                </div>
                                                <input type="text" data-input="value" class="form-control" autocapitalize="none" spellcheck="false" value="" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-auto px-2 form-group d-flex align-items-start">
                                        <button type="button" class="btn btn-outline-danger d-flex align-items-center input-delete">@include('icons.delete', ['class' => 'width-4 height-4 fill-current'])&#8203;</button>
                                    </div>
                                </div>

                                @php
                                    if (old('country')) {
                                        $countryList = old('country');
                                    } elseif($link->country_target) {
                                        $countryList = json_decode(json_encode($link->country_target), true);
                                    } else {
                                        $countryList = [];
                                    }
                                @endphp

                                @foreach($countryList as $id => $country)
                                    <div class="row mx-n2">
                                        <div class="col-12 col-md px-2">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">@include('icons.flag', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                                                    </div>
                                                    <select name="country[{{ $id }}][key]" data-input="key" class="custom-select{{ $errors->has('country.'.$id.'.key') ? ' is-invalid' : '' }}">
                                                        <option value="">{{ __('Country') }}</option>
                                                        @foreach(config('countries') as $key => $value)
                                                            <option value="{{ $key }}" @if($country['key'] == $key) selected @endif>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @if ($errors->has('country.'.$id.'.key'))
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $errors->first('country.'.$id.'.key') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col px-2">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">@include('icons.link', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                                                    </div>
                                                    <input type="text" dir="ltr" name="country[{{ $id }}][value]" data-input="value" class="form-control{{ $errors->has('country.'.$id.'.value') ? ' is-invalid' : '' }}" autocapitalize="none" spellcheck="false" value="{{ $country['value'] }}">
                                                </div>
                                                @if ($errors->has('country.'.$id.'.value'))
                                                    <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $errors->first('country.'.$id.'.value') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-auto px-2 form-group d-flex align-items-start">
                                            <button type="button" class="btn btn-outline-danger d-flex align-items-center input-delete">@include('icons.delete', ['class' => 'width-4 height-4 fill-current'])&#8203;</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @can('targeting', ['App\Models\Link'])
                                <button type="button" class="btn btn-outline-secondary input-add d-inline-flex align-items-center">@include('icons.add', ['class' => 'width-4 height-4 fill-current'])&#8203;</button>
                            @else
                                @if(paymentProcessors())
                                    <a href="{{ route('pricing') }}" class="btn btn-outline-primary d-inline-flex align-items-center" data-tooltip="true" title="{{ __('Unlock feature') }}">@include('icons.lock-open', ['class' => 'width-4 height-4 fill-current'])&#8203;</a>
                                @endif
                            @endcan
                        </div>

                        <div id="platform-container" class="tab-pane fade{{ old('target_type') !== null ? (old('target_type') == 2 ? ' show active' : '') : ($link->target_type == 2 ? ' show active' : '') }}">
                            <input name="platform[empty][key]" type="hidden" disabled>
                            <input name="platform[empty][value]" type="hidden" disabled>

                            <div class="input-content">
                                <div class="row mx-n2 d-none input-template">
                                    <div class="col-12 col-md px-2">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">@include('icons.devices', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                                                </div>
                                                <select name="platform_key[]" data-input="key" class="custom-select" disabled>
                                                    <option value="" selected>{{ __('Platform') }}</option>
                                                    @foreach(config('platforms') as $platform)
                                                        <option value="{{ $platform }}">{{ $platform }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col px-2">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">@include('icons.link', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                                                </div>
                                                <input type="text" data-input="value" class="form-control" autocapitalize="none" spellcheck="false" value="" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-auto px-2 form-group d-flex align-items-start">
                                        <button type="button" class="btn btn-outline-danger d-flex align-items-center input-delete">@include('icons.delete', ['class' => 'width-4 height-4 fill-current'])&#8203;</button>
                                    </div>
                                </div>

                                @php
                                    if (old('platform')) {
                                        $platformList = old('platform');
                                    } elseif($link->platform_target) {
                                        $platformList = json_decode(json_encode($link->platform_target), true);
                                    } else {
                                        $platformList = [];
                                    }
                                @endphp

                                @foreach($platformList as $id => $platform)
                                    <div class="row mx-n2">
                                        <div class="col-12 col-md px-2">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">@include('icons.devices', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                                                    </div>
                                                    <select name="platform[{{ $id }}][key]" data-input="key" class="custom-select{{ $errors->has('platform.'.$id.'.key') ? ' is-invalid' : '' }}">
                                                        <option value="">{{ __('Platform') }}</option>
                                                        @foreach(config('platforms') as $value)
                                                            <option value="{{ $value }}" @if($platform['key'] == $value) selected @endif>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @if ($errors->has('platform.'.$id.'.key'))
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $errors->first('platform.'.$id.'.key') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col px-2">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">@include('icons.link', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                                                    </div>
                                                    <input type="text" dir="ltr" name="platform[{{ $id }}][value]" data-input="value" class="form-control{{ $errors->has('platform.'.$id.'.value') ? ' is-invalid' : '' }}" autocapitalize="none" spellcheck="false" value="{{ $platform['value'] }}">
                                                </div>
                                                @if ($errors->has('platform.'.$id.'.value'))
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $errors->first('platform.'.$id.'.value') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-auto px-2 form-group d-flex align-items-start">
                                            <button type="button" class="btn btn-outline-danger d-flex align-items-center input-delete">@include('icons.delete', ['class' => 'width-4 height-4 fill-current'])&#8203;</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @can('targeting', ['App\Models\Link'])
                                <button type="button" class="btn btn-outline-secondary input-add d-inline-flex align-items-center">@include('icons.add', ['class' => 'width-4 height-4 fill-current'])&#8203;</button>
                            @else
                                @if(paymentProcessors())
                                    <a href="{{ route('pricing') }}" class="btn btn-outline-primary d-inline-flex align-items-center" data-tooltip="true" title="{{ __('Unlock feature') }}">@include('icons.lock-open', ['class' => 'width-4 height-4 fill-current'])&#8203;</a>
                                @endif
                            @endcan
                        </div>

                        <div id="language-container" class="tab-pane fade{{ old('target_type') !== null ? (old('target_type') == 3 ? ' show active' : '') : ($link->target_type == 3 ? ' show active' : '') }}">
                            <input name="language[empty][key]" type="hidden" disabled>
                            <input name="language[empty][value]" type="hidden" disabled>

                            <div class="input-content">
                                <div class="row mx-n2 d-none input-template">
                                    <div class="col-12 col-md px-2">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">@include('icons.language', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                                                </div>
                                                <select name="language_key[]" data-input="key" class="custom-select" disabled>
                                                    <option value="" selected>{{ __('Language') }}</option>
                                                    @foreach(config('languages') as $key => $value)
                                                        <option value="{{ $key }}">{{ __($value['name']) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col px-2">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">@include('icons.link', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                                                </div>
                                                <input type="text" data-input="value" class="form-control" autocapitalize="none" spellcheck="false" value="" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-auto px-2 form-group d-flex align-items-start">
                                        <button type="button" class="btn btn-outline-danger d-flex align-items-center input-delete">@include('icons.delete', ['class' => 'width-4 height-4 fill-current'])&#8203;</button>
                                    </div>
                                </div>

                                @php
                                    if (old('language')) {
                                        $languageList = old('language');
                                    } elseif($link->language_target) {
                                        $languageList = json_decode(json_encode($link->language_target), true);
                                    } else {
                                        $languageList = [];
                                    }
                                @endphp

                                @foreach($languageList as $id => $language)
                                    <div class="row mx-n2">
                                        <div class="col-12 col-md">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">@include('icons.language', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                                                    </div>
                                                    <select name="language[{{ $id }}][key]" data-input="key" class="custom-select{{ $errors->has('language.'.$id.'.key') ? ' is-invalid' : '' }}">
                                                        <option value="">{{ __('Language') }}</option>
                                                        @foreach(config('languages') as $key => $value)
                                                            <option value="{{ $key }}" @if($language['key'] == $key) selected @endif>{{ __($value['name']) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @if ($errors->has('language.'.$id.'.key'))
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $errors->first('language.'.$id.'.key') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="col px-2">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">@include('icons.link', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                                                        </div>
                                                        <input type="text" dir="ltr" name="language[{{ $id }}][value]" data-input="value" class="form-control{{ $errors->has('language.'.$id.'.value') ? ' is-invalid' : '' }}" autocapitalize="none" spellcheck="false" value="{{ $language['value'] }}">
                                                    </div>
                                                    @if ($errors->has('language.'.$id.'.value'))
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $errors->first('language.'.$id.'.value') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-auto px-2 form-group d-flex align-items-start">
                                                <button type="button" class="btn btn-outline-danger d-flex align-items-center input-delete">@include('icons.delete', ['class' => 'width-4 height-4 fill-current'])&#8203;</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @can('targeting', ['App\Models\Link'])
                                <button type="button" class="btn btn-outline-secondary input-add d-inline-flex align-items-center">@include('icons.add', ['class' => 'width-4 height-4 fill-current'])&#8203;</button>
                            @else
                                @if(paymentProcessors())
                                    <a href="{{ route('pricing') }}" class="btn btn-outline-primary d-inline-flex align-items-center" data-tooltip="true" title="{{ __('Unlock feature') }}">@include('icons.lock-open', ['class' => 'width-4 height-4 fill-current'])&#8203;</a>
                                @endif
                            @endcan
                        </div>

                        <div id="rotation-container" class="tab-pane fade{{ old('target_type') !== null ? (old('target_type') == 4 ? ' show active' : '') : ($link->target_type == 4 ? ' show active' : '') }}">
                            <input name="rotation[empty][value]" type="hidden" disabled>
                            <div class="input-content">
                                <div class="row mx-n2 d-none input-template">
                                    <div class="col px-2">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">@include('icons.link', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                                                </div>
                                                <input type="text" data-input="value" class="form-control" autocapitalize="none" spellcheck="false" value="" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-auto px-2 form-group d-flex align-items-start">
                                        <button type="button" class="btn btn-outline-danger d-flex align-items-center input-delete">@include('icons.delete', ['class' => 'width-4 height-4 fill-current'])&#8203;</button>
                                    </div>
                                </div>

                                @php
                                    if (old('rotation')) {
                                        $rotationList = old('rotation');
                                    } elseif($link->rotation_target) {
                                        $rotationList = json_decode(json_encode($link->rotation_target), true);
                                    } else {
                                        $rotationList = [];
                                    }
                                @endphp

                                @foreach($rotationList as $id => $rotation)
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row mx-n2">
                                                <div class="col px-2">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">@include('icons.link', ['class' => 'width-4 height-4 fill-current text-muted'])</div>
                                                            </div>
                                                            <input type="text" dir="ltr" name="rotation[{{ $id }}][value]" data-input="value" class="form-control{{ $errors->has('rotation.'.$id.'.value') ? ' is-invalid' : '' }}" autocapitalize="none" spellcheck="false" value="{{ $rotation['value'] }}">
                                                        </div>
                                                        @if ($errors->has('rotation.'.$id.'.value'))
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $errors->first('rotation.'.$id.'.value') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-auto px-2 form-group d-flex align-items-start">
                                                    <button type="button" class="btn btn-outline-danger d-flex align-items-center input-delete">@include('icons.delete', ['class' => 'width-4 height-4 fill-current'])&#8203;</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @can('targeting', ['App\Models\Link'])
                                <button type="button" class="btn btn-outline-secondary input-add d-inline-flex align-items-center">@include('icons.add', ['class' => 'width-4 height-4 fill-current'])&#8203;</button>
                            @else
                                @if(paymentProcessors())
                                    <a href="{{ route('pricing') }}" class="btn btn-outline-primary d-inline-flex align-items-center" data-tooltip="true" title="{{ __('Unlock feature') }}">@include('icons.lock-open', ['class' => 'width-4 height-4 fill-current'])&#8203;</a>
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>
                </div>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>
    </div>
</div>

@if(request()->is('admin/*'))
    @include('admin.users.partials.card', ['user' => $link->user])
@endif

@include('shared.modals.utm')
@include('shared.modals.share-link')