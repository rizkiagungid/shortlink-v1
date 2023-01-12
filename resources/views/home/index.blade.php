@section('site_title', formatTitle([config('settings.title'), __(config('settings.tagline'))]))

@extends('layouts.app')

@section('content')
    <div class="flex-fill">
    <div class="bg-base-0 position-relative">
        <div class="container position-relative py-5 py-sm-6">
            <div class="row">
                <div class="col-12 py-sm-5 text-break">
                    <h1 class="display-4 mb-0 font-weight-bold text-center">
                        {{ __('Smart and powerful short links') }}
                    </h1>

                    <p class="text-muted font-weight-normal my-4 font-size-xl text-center">
                        {{ __('Stay in control of your links with advanced features for shortening, targeting, and tracking.') }}
                    </p>

                    <div class="row">
                        <div class="col-2 d-none d-lg-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.95 16" style="width: 1.4rem; height: 1.4rem; transform: rotate(-17deg); {{ (__('lang_dir') == 'rtl' ? 'left' : 'right') }}: 2rem; top: 2rem; filter: blur(1px);" class="position-absolute"><path d="M10,.42,8.46,0,7.14,5,5.94.48,4.37.9,5.66,5.73,2.44,2.51,1.29,3.66,4.82,7.19.42,6,0,7.59,4.81,8.87a2.92,2.92,0,0,1-.09-.73,3.26,3.26,0,1,1,6.52,0,3.55,3.55,0,0,1-.08.73L15.52,10,16,8.47l-4.83-1.3L15.52,6,15.1,4.42l-4.83,1.3,3.22-3.23L12.34,1.34,8.86,4.83Z" style="fill:#f97316"/><path d="M11.15,8.89a3.2,3.2,0,0,1-.81,1.49l3.17,3.17,1.15-1.15Z" style="fill:#f97316"/><path d="M10.31,10.41a3.3,3.3,0,0,1-1.46.87L10,15.57l1.58-.42Z" style="fill:#f97316"/><path d="M8.79,11.29a3.1,3.1,0,0,1-.81.1,3.58,3.58,0,0,1-.87-.11L6,15.58,7.53,16Z" style="fill:#f97316"/><path d="M7.06,11.26a3.18,3.18,0,0,1-1.43-.87L2.45,13.56,3.6,14.71Z" style="fill:#f97316"/><path d="M5.6,10.36a3.23,3.23,0,0,1-.79-1.48L.43,10.06l.42,1.57Z" style="fill:#f97316"/></svg>

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" style="width: 1.7rem; height: 1.7rem; transform: rotate(22deg); {{ (__('lang_dir') == 'rtl' ? 'left' : 'right') }}: 6rem; top: 0; filter: blur(1px);" class="position-absolute"><polygon points="0 0 50 0 0 50 0 0" style="fill:#009cea"/><polygon points="0 50 50 50 0 100 0 50" style="fill:#009cea"/><polygon points="50 0 100 0 50 50 50 0" style="fill:#009cea"/><circle cx="75" cy="75" r="25" style="fill:#009cea"/></svg>

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45 45" style="width: 1.3rem; height: 1.3rem; transform: rotate(-5deg); {{ (__('lang_dir') == 'rtl' ? 'left' : 'right') }}: 4.5rem; top: 4rem; filter: blur(1px);" class="position-absolute"><path d="M22.5,11.25A11.25,11.25,0,0,1,11.25,22.5H0V11.25a11.25,11.25,0,0,1,22.5,0Z" style="fill:#f5718b"/><path d="M22.5,33.75A11.25,11.25,0,0,1,33.75,22.5H45V33.75a11.25,11.25,0,0,1-22.5,0Z" style="fill:#f5718b"/><path d="M0,33.75A11.25,11.25,0,0,0,11.25,45H22.5V33.75a11.25,11.25,0,0,0-22.5,0Z" style="fill:#f5718b"/><path d="M45,11.25A11.25,11.25,0,0,0,33.75,0H22.5V11.25a11.25,11.25,0,0,0,22.5,0Z" style="fill:#f5718b"/></svg>
                        </div>

                        @if(config('settings.short_guest'))
                            <div class="col-12 col-lg-8 mt-4">
                                <div class="form-group mb-0" id="short-form-container"@if(request()->session()->get('link')) style="display: none;"@endif>
                                    <form action="{{ route('guest') }}" method="post" enctype="multipart/form-data" id="short-form">
                                        @csrf
                                        <div class="form-row">
                                            <div class="col-12 col-sm">
                                                <input type="text" dir="ltr" autocomplete="off" autocapitalize="none" spellcheck="false" name="url" class="form-control form-control-lg font-size-lg{{ $errors->has('url') || $errors->has('domain') || $errors->has('g-recaptcha-response') ? ' is-invalid' : '' }}" placeholder="{{ __('Shorten your link') }}" autofocus>
                                                @if ($errors->has('url'))
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $errors->first('url') }}</strong>
                                                    </span>
                                                @endif

                                                @if ($errors->has('domain'))
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $errors->first('domain') }}</strong>
                                                    </span>
                                                @endif

                                                @if ($errors->has('g-recaptcha-response'))
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="col-12 col-sm-auto">
                                                @if(config('settings.captcha_shorten'))
                                                    {!! NoCaptcha::displaySubmit('short-form', __('Shorten'), ['data-theme' => (config('settings.dark_mode') == 1 ? 'dark' : 'light'), 'data-size' => 'invisible', 'class' => 'btn btn-primary btn-lg btn-block font-size-lg mt-3 mt-sm-0']) !!}

                                                    {!! NoCaptcha::renderJs(__('lang_code')) !!}
                                                @else
                                                    <button class="btn btn-primary btn-lg btn-block font-size-lg mt-3 mt-sm-0" type="submit" data-button-loader>
                                                        <div class="position-absolute top-0 right-0 bottom-0 left-0 d-flex align-items-center justify-content-center">
                                                            <span class="d-none spinner-border spinner-border-sm width-4 height-4" role="status"></span>
                                                        </div>
                                                        <span class="spinner-text">{{ __('Shorten') }}</span>&#8203;
                                                    </button>
                                                @endif
                                            </div>
                                        </div>

                                        <input type="hidden" name="domain" value="{{ $defaultDomain }}">
                                    </form>
                                </div>

                                @include('home.link')
                            </div>
                        @else
                            <div class="col-12 col-lg-8 pt-2 d-flex flex-column flex-sm-row justify-content-center">
                                <a href="{{ config('settings.registration') ? route('register') : route('login') }}" class="btn btn-primary btn-lg font-size-lg align-items-center mt-3">{{ __('Get started') }}</a>

                                <a href="#features" class="btn btn-outline-primary btn-lg font-size-lg d-inline-flex align-items-center justify-content-center mt-3 {{ (__('lang_dir') == 'rtl' ? 'mr-sm-3' : 'ml-sm-3') }}">{{ __('Learn more') }}</a>
                            </div>
                        @endif

                        <div class="col-2 d-none d-lg-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" style="width: 1.4rem; height: 1.4rem; transform: rotate(7deg); {{ (__('lang_dir') == 'rtl' ? 'right' : 'left') }}: 2rem; top: 2rem; filter: blur(1px);" class="position-absolute"><path d="M8.55,3.6A20,20,0,0,0,4.71,7.11c4.58-.42,10.42.27,17.18,3.66,7.23,3.61,13,3.73,17.1,3a20.14,20.14,0,0,0-1.37-3.2C33,11,27,10.36,20.11,6.9A29.64,29.64,0,0,0,8.55,3.6ZM34.91,6.67A20,20,0,0,0,15,.64a37,37,0,0,1,6.93,2.68A28.82,28.82,0,0,0,34.91,6.67Zm5,11c-4.89,1-11.65.77-19.75-3.29C12.53,10.56,6.5,10.6,2.43,11.51l-.61.14A19.82,19.82,0,0,0,.56,15.29c.32-.08.66-.17,1-.24C6.5,14,13.47,14,21.89,18.21,29.47,22,35.5,22,39.57,21.05L40,21c0-.31,0-.63,0-.95A20.66,20.66,0,0,0,39.86,17.63Zm-.54,7.54c-4.84.85-11.4.52-19.21-3.38C12.53,18,6.5,18.05,2.43,19A19.75,19.75,0,0,0,0,19.66V20a20,20,0,0,0,39.32,5.17Z" style="fill:#10d08f;fill-rule:evenodd"/></svg>

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" style="width: 1.65rem; height: 1.65rem; transform: rotate(22deg); {{ (__('lang_dir') == 'rtl' ? 'right' : 'left') }}: 6rem; top: .5rem; filter: blur(1px);" class="position-absolute"><path d="M20,40A20,20,0,1,0,0,20,20,20,0,0,0,20,40ZM26.24,9.32c.3-1.08-.74-1.72-1.7-1L11.19,17.79c-1,.74-.87,2.21.25,2.21H15v0H21.8l-5.58,2-2.46,8.74c-.3,1.08.74,1.72,1.7,1l13.35-9.51c1-.74.87-2.21-.25-2.21H23.23Z" style="fill:#f15757;fill-rule:evenodd"/></svg>

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" style="width: 1.3rem; height: 1.3rem; transform: rotate(-20deg); {{ (__('lang_dir') == 'rtl' ? 'right' : 'left') }}: 5rem; top: 4rem; filter: blur(1px);" class="position-absolute"><path d="M20,40A20,20,0,1,0,0,20,20,20,0,0,0,20,40Zm3.09-24.55a4.37,4.37,0,1,0-6.18,0L20,18.54Zm1.46,7.64a4.37,4.37,0,1,0,0-6.18L21.46,20Zm-1.46,7.63a4.37,4.37,0,0,0,0-6.17L20,21.46l-3.09,3.09a4.37,4.37,0,0,0,6.18,6.17ZM9.28,23.09a4.37,4.37,0,1,1,6.17-6.18L18.54,20l-3.09,3.09A4.37,4.37,0,0,1,9.28,23.09Z" style="fill:#946fff;fill-rule:evenodd"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-base-1 scroll-margin-top-18" id="features">
        <div class="container py-5 py-md-7">
            <div class="text-center">
                <h3 class="h2 mb-3 font-weight-bold text-center">{{ __('Features') }}</h3>
                <div class="mx-auto mb-5">
                    <p class="text-muted font-weight-normal font-size-lg mb-0">{{ __('Measure traffic, know your audience, stay in control of your links.') }}</p>
                </div>
            </div>

            <div class="row m-n2 m-sm-n3">
                <div class="col-12 col-sm-6 col-md-4 p-2 p-sm-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex width-12 height-12 position-relative align-items-center justify-content-center flex-shrink-0 mb-3">
                                <div class="position-absolute bg-cyan opacity-10 top-0 right-0 bottom-0 left-0 border-radius-2xl"></div>
                                @include('icons.devices-other', ['class' => 'fill-current width-6 height-6 text-cyan'])
                            </div>
                            <div class="d-block w-100"><h5 class="mt-1 mb-1 d-inline-block font-weight-bold">{{ __('Target') }}</h5></div>
                            <div class="d-block w-100 text-muted">{{ __('Redirect your users based on the country, platform, or language.') }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 p-2 p-sm-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex width-12 height-12 position-relative align-items-center justify-content-center flex-shrink-0 mb-3">
                                <div class="position-absolute bg-blue opacity-10 top-0 right-0 bottom-0 left-0 border-radius-2xl"></div>
                                @include('icons.filter-center-focus', ['class' => 'fill-current width-6 height-6 text-blue'])
                            </div>
                            <div class="d-block w-100"><h5 class="mt-1 mb-1 d-inline-block font-weight-bold">{{ __('Retarget') }}</h5></div>
                            <div class="d-block w-100 text-muted">{{ __('Retarget your audience by adding tracking pixels to your links.') }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 p-2 p-sm-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex width-12 height-12 position-relative align-items-center justify-content-center flex-shrink-0 mb-3">
                                <div class="position-absolute bg-purple opacity-10 top-0 right-0 bottom-0 left-0 border-radius-2xl"></div>
                                @include('icons.bar-chart', ['class' => 'fill-current width-6 height-6 text-purple'])
                            </div>
                            <div class="d-block w-100"><h5 class="mt-1 mb-1 d-inline-block font-weight-bold">{{ __('Statistics') }}</h5></div>
                            <div class="d-block w-100 text-muted">{{ __('Get to know your audience, analyze the performance of your links.') }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 p-2 p-sm-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex width-12 height-12 position-relative align-items-center justify-content-center flex-shrink-0 mb-3">
                                <div class="position-absolute bg-rose opacity-10 top-0 right-0 bottom-0 left-0 border-radius-2xl"></div>
                                @include('icons.share', ['class' => 'fill-current width-6 height-6 text-rose'])
                            </div>
                            <div class="d-block w-100"><h5 class="mt-1 mb-1 d-inline-block font-weight-bold">{{ __('Share') }}</h5></div>
                            <div class="d-block w-100 text-muted">{{ __('Share your links on popular social platforms or via QR codes.') }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 p-2 p-sm-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex width-12 height-12 position-relative align-items-center justify-content-center flex-shrink-0 mb-3">
                                <div class="position-absolute bg-pink opacity-10 top-0 right-0 bottom-0 left-0 border-radius-2xl"></div>
                                @include('icons.lock', ['class' => 'fill-current width-6 height-6 text-pink'])
                            </div>
                            <div class="d-block w-100"><h5 class="mt-1 mb-1 d-inline-block font-weight-bold">{{ __('Privacy') }}</h5></div>
                            <div class="d-block w-100 text-muted">{{ __('Secure your links with password and expiration options.') }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 p-2 p-sm-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex width-12 height-12 position-relative align-items-center justify-content-center flex-shrink-0 mb-3">
                                <div class="position-absolute bg-magenta opacity-10 top-0 right-0 bottom-0 left-0 border-radius-2xl"></div>
                                @include('icons.file-download', ['class' => 'fill-current width-6 height-6 text-magenta'])
                            </div>
                            <div class="d-block w-100"><h5 class="mt-1 mb-1 d-inline-block font-weight-bold">{{ __('Export') }}</h5></div>
                            <div class="d-block w-100 text-muted">{{ __('Export all your links and statistics in CSV format.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-base-0 overflow-hidden">
        <div class="container py-5 py-md-7 position-relative z-1">
            <div class="row mx-n5">
                <div class="col-12 col-lg-6 px-5">
                    <div class="row">
                        <div class="col-12 text-center {{ (__('lang_dir') == 'rtl' ? 'text-lg-right' : 'text-lg-left') }}">
                            <h3 class="h2 mb-3 font-weight-bold">{{ __('Link management') }}</h3>
                            <div class="m-auto">
                                <p class="text-muted font-weight-normal font-size-lg mb-0">{{ __('Complete link management platform to brand, track and share your short links.') }}</p>
                            </div>
                        </div>

                        <div class="col-12 pt-4 mt-4">
                            <div class="d-flex flex-row">
                                <div class="d-flex width-12 height-12 position-relative align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                                    <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-2xl"></div>
                                    @include('icons.link', ['class' => 'fill-current width-6 height-6 text-primary'])
                                </div>
                                <div class="{{ (__('lang_dir') == 'rtl' ? 'mr-1' : 'ml-1') }}">
                                    <div class="d-block w-100"><h5 class="mt-0 mb-1 d-inline-block font-weight-bold">{{ __('Links') }}</h5></div>
                                    <div class="d-block w-100 text-muted">{{ __('Shorten, share, and export your links with our advanced set of features.') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 pt-4 mt-4">
                            <div class="d-flex flex-row">
                                <div class="d-flex width-12 height-12 position-relative align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                                    <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-2xl"></div>
                                    @include('icons.workspaces', ['class' => 'fill-current width-6 height-6 text-primary'])
                                </div>
                                <div class="{{ (__('lang_dir') == 'rtl' ? 'mr-1' : 'ml-1') }}">
                                    <div class="d-block w-100"><h5 class="mt-0 mb-1 d-inline-block font-weight-bold">{{ __('Spaces') }}</h5></div>
                                    <div class="d-block w-100 text-muted">{{ __('Group your links and keep them well organized through custom spaces.') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 pt-4 mt-4">
                            <div class="d-flex flex-row">
                                <div class="d-flex width-12 height-12 position-relative align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                                    <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-2xl"></div>
                                    @include('icons.website', ['class' => 'fill-current width-6 height-6 text-primary'])
                                </div>
                                <div class="{{ (__('lang_dir') == 'rtl' ? 'mr-1' : 'ml-1') }}">
                                    <div class="d-block w-100"><h5 class="mt-0 mb-1 d-inline-block font-weight-bold">{{ __('Domains') }}</h5></div>
                                    <div class="d-block w-100 text-muted">{{ __('Brand your links with your domains, inspire trust and increase your click-through rate.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6 position-relative px-5 mt-5 mt-lg-0">
                    <div class="position-relative">
                        <div class="position-absolute top-0 right-0 bottom-0 left-0 bg-primary opacity-10 border-radius-2xl" style="transform: translate(1rem, 1rem);"></div>

                        <div class="card border-0 shadow-lg border-radius-2xl overflow-hidden cursor-default">
                            <div class="card-body">
                                <div class="list-group list-group-flush my-n3">
                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col d-flex text-truncate">
                                                <div class="text-truncate">
                                                    <div class="d-flex align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}" viewBox="0 0 15.95 16"><path d="M10,.42,8.46,0,7.14,5,5.94.48,4.37.9,5.66,5.73,2.44,2.51,1.29,3.66,4.82,7.19.42,6,0,7.59,4.81,8.87a2.92,2.92,0,0,1-.09-.73,3.26,3.26,0,1,1,6.52,0,3.55,3.55,0,0,1-.08.73L15.52,10,16,8.47l-4.83-1.3L15.52,6,15.1,4.42l-4.83,1.3,3.22-3.23L12.34,1.34,8.86,4.83Z" style="fill:#f97316"/><path d="M11.15,8.89a3.2,3.2,0,0,1-.81,1.49l3.17,3.17,1.15-1.15Z" style="fill:#f97316"/><path d="M10.31,10.41a3.3,3.3,0,0,1-1.46.87L10,15.57l1.58-.42Z" style="fill:#f97316"/><path d="M8.79,11.29a3.1,3.1,0,0,1-.81.1,3.58,3.58,0,0,1-.87-.11L6,15.58,7.53,16Z" style="fill:#f97316"/><path d="M7.06,11.26a3.18,3.18,0,0,1-1.43-.87L2.45,13.56,3.6,14.71Z" style="fill:#f97316"/><path d="M5.6,10.36a3.23,3.23,0,0,1-.79-1.48L.43,10.06l.42,1.57Z" style="fill:#f97316"/></svg>

                                                        <div class="text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                            <div class="text-primary text-truncate" dir="ltr">example.com/b6vxe</div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="width-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}"></div>
                                                        <div class="text-muted text-truncate small">
                                                            <span class="text-muted">Consectetur - Adipiscing</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="btn btn-sm text-primary d-flex align-items-center cursor-default">
                                                            @include('icons.copy-link', ['class' => 'fill-current width-4 height-4'])&#8203;
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="btn text-primary btn-sm d-flex align-items-center cursor-default">
                                                            @include('icons.more-horiz', ['class' => 'fill-current width-4 height-4'])&#8203;
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-lg border-radius-2xl overflow-hidden cursor-default mt-3">
                            <div class="card-body">
                                <div class="list-group list-group-flush my-n3">
                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col d-flex text-truncate">
                                                <div class="text-truncate">
                                                    <div class="d-flex align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}" viewBox="0 0 100 100"><polygon points="0 0 50 0 0 50 0 0" style="fill:#009cea"/><polygon points="0 50 50 50 0 100 0 50" style="fill:#009cea"/><polygon points="50 0 100 0 50 50 50 0" style="fill:#009cea"/><circle cx="75" cy="75" r="25" style="fill:#009cea"/></svg>

                                                        <div class="text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                            <div class="text-primary text-truncate" dir="ltr">example.org/e362o</div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="width-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}"></div>
                                                        <div class="text-muted text-truncate small">
                                                            <span class="text-muted">Fusce - Vehicula</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="btn btn-sm text-primary d-flex align-items-center cursor-default">
                                                            @include('icons.copy-link', ['class' => 'fill-current width-4 height-4'])&#8203;
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="btn text-primary btn-sm d-flex align-items-center cursor-default">
                                                            @include('icons.more-horiz', ['class' => 'fill-current width-4 height-4'])&#8203;
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-lg border-radius-2xl overflow-hidden cursor-default mt-3">
                            <div class="card-body">
                                <div class="list-group list-group-flush my-n3">
                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col d-flex text-truncate">
                                                <div class="text-truncate">
                                                    <div class="d-flex align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}" viewBox="0 0 40 40"><path d="M8.55,3.6A20,20,0,0,0,4.71,7.11c4.58-.42,10.42.27,17.18,3.66,7.23,3.61,13,3.73,17.1,3a20.14,20.14,0,0,0-1.37-3.2C33,11,27,10.36,20.11,6.9A29.64,29.64,0,0,0,8.55,3.6ZM34.91,6.67A20,20,0,0,0,15,.64a37,37,0,0,1,6.93,2.68A28.82,28.82,0,0,0,34.91,6.67Zm5,11c-4.89,1-11.65.77-19.75-3.29C12.53,10.56,6.5,10.6,2.43,11.51l-.61.14A19.82,19.82,0,0,0,.56,15.29c.32-.08.66-.17,1-.24C6.5,14,13.47,14,21.89,18.21,29.47,22,35.5,22,39.57,21.05L40,21c0-.31,0-.63,0-.95A20.66,20.66,0,0,0,39.86,17.63Zm-.54,7.54c-4.84.85-11.4.52-19.21-3.38C12.53,18,6.5,18.05,2.43,19A19.75,19.75,0,0,0,0,19.66V20a20,20,0,0,0,39.32,5.17Z" style="fill:#10d08f;fill-rule:evenodd"/></svg>

                                                        <div class="text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                            <div class="text-primary text-truncate" dir="ltr">example.com/gmyux</div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="width-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}"></div>
                                                        <div class="text-muted text-truncate small">
                                                            <span class="text-muted">Consequat - Elit Ornare</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="btn btn-sm text-primary d-flex align-items-center cursor-default">
                                                            @include('icons.copy-link', ['class' => 'fill-current width-4 height-4'])&#8203;
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="btn text-primary btn-sm d-flex align-items-center cursor-default">
                                                            @include('icons.more-horiz', ['class' => 'fill-current width-4 height-4'])&#8203;
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-lg border-radius-2xl overflow-hidden cursor-default mt-3">
                            <div class="card-body">
                                <div class="list-group list-group-flush my-n3">
                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col d-flex text-truncate">
                                                <div class="text-truncate">
                                                    <div class="d-flex align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}" viewBox="0 0 40 40"><path d="M20,40A20,20,0,1,0,0,20,20,20,0,0,0,20,40Zm3.09-24.55a4.37,4.37,0,1,0-6.18,0L20,18.54Zm1.46,7.64a4.37,4.37,0,1,0,0-6.18L21.46,20Zm-1.46,7.63a4.37,4.37,0,0,0,0-6.17L20,21.46l-3.09,3.09a4.37,4.37,0,0,0,6.18,6.17ZM9.28,23.09a4.37,4.37,0,1,1,6.17-6.18L18.54,20l-3.09,3.09A4.37,4.37,0,0,1,9.28,23.09Z" style="fill:#946fff;fill-rule:evenodd"/></svg>

                                                        <div class="text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                            <div class="text-primary text-truncate" dir="ltr">example.net/qyd8s</div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="width-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}"></div>
                                                        <div class="text-muted text-truncate small">
                                                            <span class="text-muted">Sit - Amet</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="btn btn-sm text-primary d-flex align-items-center cursor-default">
                                                            @include('icons.copy-link', ['class' => 'fill-current width-4 height-4'])&#8203;
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="btn text-primary btn-sm d-flex align-items-center cursor-default">
                                                            @include('icons.more-horiz', ['class' => 'fill-current width-4 height-4'])&#8203;
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-lg border-radius-2xl overflow-hidden cursor-default mt-3">
                            <div class="card-body">
                                <div class="list-group list-group-flush my-n3">
                                    <div class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col d-flex text-truncate">
                                                <div class="text-truncate">
                                                    <div class="d-flex align-items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}" viewBox="0 0 40 40"><path d="M20,40A20,20,0,1,0,0,20,20,20,0,0,0,20,40ZM26.24,9.32c.3-1.08-.74-1.72-1.7-1L11.19,17.79c-1,.74-.87,2.21.25,2.21H15v0H21.8l-5.58,2-2.46,8.74c-.3,1.08.74,1.72,1.7,1l13.35-9.51c1-.74.87-2.21-.25-2.21H23.23Z" style="fill:#f15757;fill-rule:evenodd"/></svg>

                                                        <div class="text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                            <div class="text-primary text-truncate" dir="ltr">example.com/bqh6e</div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <div class="width-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}"></div>
                                                        <div class="text-muted text-truncate small">
                                                            <span class="text-muted">Lorem - Ipsum Dolorem</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="form-row">
                                                    <div class="col">
                                                        <div class="btn btn-sm text-primary d-flex align-items-center cursor-default">
                                                            @include('icons.copy-link', ['class' => 'fill-current width-4 height-4'])&#8203;
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="btn text-primary btn-sm d-flex align-items-center cursor-default">
                                                            @include('icons.more-horiz', ['class' => 'fill-current width-4 height-4'])&#8203;
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-base-1 overflow-hidden">
        <div class="container py-5 py-md-7 position-relative z-1">
            <div class="row mx-n5">
                <div class="col-12 col-lg-6 px-5 order-1 order-lg-2">
                    <div class="row">
                        <div class="col-12 text-center {{ (__('lang_dir') == 'rtl' ? 'text-lg-right' : 'text-lg-left') }}">
                            <h3 class="h2 mb-3 font-weight-bold">{{ __('Statistics') }}</h3>
                            <div class="mx-auto mb-4">
                                <p class="text-muted font-weight-normal font-size-lg mb-0">
                                    {{ __('Get to know your audience with our detailed statistics and better understand the performance of your links, while also being GDPR, CCPA and PECR compliant.') }}
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 mt-4">
                            <div class="d-flex">
                                <div class="d-flex position-relative text-primary width-8 height-8 align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                                    <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-lg"></div>
                                    @include('icons.assesment', ['class' => 'fill-current width-4 height-4'])
                                </div>
                                <div>
                                    <div class="d-block w-100"><div class="mt-1 mb-1 d-inline-block font-weight-bold font-size-lg">{{ __('Overview') }}</div></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 mt-4">
                            <div class="d-flex">
                                <div class="d-flex position-relative text-primary width-8 height-8 align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                                    <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-lg"></div>
                                    @include('icons.link', ['class' => 'fill-current width-4 height-4'])
                                </div>
                                <div>
                                    <div class="d-block w-100"><div class="mt-1 mb-1 d-inline-block font-weight-bold font-size-lg">{{ __('Referrers') }}</div></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 mt-4">
                            <div class="d-flex">
                                <div class="d-flex position-relative text-primary width-8 height-8 align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                                    <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-lg"></div>
                                    @include('icons.flag', ['class' => 'fill-current width-4 height-4'])
                                </div>
                                <div>
                                    <div class="d-block w-100"><div class="mt-1 mb-1 d-inline-block font-weight-bold font-size-lg">{{ __('Countries') }}</div></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 mt-4">
                            <div class="d-flex">
                                <div class="d-flex position-relative text-primary width-8 height-8 align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                                    <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-lg"></div>
                                    @include('icons.business', ['class' => 'fill-current width-4 height-4'])
                                </div>
                                <div>
                                    <div class="d-block w-100"><div class="mt-1 mb-1 d-inline-block font-weight-bold font-size-lg">{{ __('Cities') }}</div></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 mt-4">
                            <div class="d-flex">
                                <div class="d-flex position-relative text-primary width-8 height-8 align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                                    <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-lg"></div>
                                    @include('icons.language', ['class' => 'fill-current width-4 height-4'])
                                </div>
                                <div>
                                    <div class="d-block w-100"><div class="mt-1 mb-1 d-inline-block font-weight-bold font-size-lg">{{ __('Languages') }}</div></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 mt-4">
                            <div class="d-flex">
                                <div class="d-flex position-relative text-primary width-8 height-8 align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                                    <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-lg"></div>
                                    @include('icons.devices', ['class' => 'fill-current width-4 height-4'])
                                </div>
                                <div>
                                    <div class="d-block w-100"><div class="mt-1 mb-1 d-inline-block font-weight-bold font-size-lg">{{ __('Platforms') }}</div></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 mt-4">
                            <div class="d-flex">
                                <div class="d-flex position-relative text-primary width-8 height-8 align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                                    <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-lg"></div>
                                    @include('icons.tab', ['class' => 'fill-current width-4 height-4'])
                                </div>
                                <div>
                                    <div class="d-block w-100"><div class="mt-1 mb-1 d-inline-block font-weight-bold font-size-lg">{{ __('Browsers') }}</div></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 mt-4">
                            <div class="d-flex">
                                <div class="d-flex position-relative text-primary width-8 height-8 align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                                    <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-lg"></div>
                                    @include('icons.devices-other', ['class' => 'fill-current width-4 height-4'])
                                </div>
                                <div>
                                    <div class="d-block w-100"><div class="mt-1 mb-1 d-inline-block font-weight-bold font-size-lg">{{ __('Devices') }}</div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6 px-5 position-relative order-2 order-lg-1 mt-5 mt-lg-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="position-relative">
                                <div class="position-absolute top-0 right-0 bottom-0 left-0 bg-primary opacity-10 border-radius-2xl" style="transform: translate(-1rem, 1rem);"></div>

                                <div class="card border-0 shadow-lg border-radius-2xl overflow-hidden cursor-default">
                                    <div class="card-body">
                                        <div class="list-group list-group-flush my-n3">
                                            <div class="list-group-item px-0">
                                                <div class="d-flex flex-column">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <div class="d-flex text-truncate align-items-center">
                                                            <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"><img src="{{ asset('images') }}/icons/countries/us.svg" class="width-4 height-4"></div>
                                                            <div class="text-truncate">
                                                                <span class="text-body">United States</span>
                                                            </div>
                                                        </div>

                                                        <div class="d-flex align-items-baseline {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                                            <div>
                                                                {{ number_format(12, 0, __('.'), __(',')) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="progress height-1.25 w-100">
                                                        <div class="progress-bar rounded" role="progressbar" style="width: 18%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border-0 shadow-lg border-radius-2xl overflow-hidden cursor-default mt-3">
                                    <div class="card-body">
                                        <div class="list-group list-group-flush my-n3">
                                            <div class="list-group-item px-0 border-0">
                                                <div class="d-flex flex-column">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <div class="d-flex text-truncate align-items-center">
                                                            <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"><img src="{{ asset('images') }}/icons/platforms/windows.svg" class="width-4 height-4"></div>
                                                            <div class="text-truncate">
                                                                Windows
                                                            </div>
                                                        </div>

                                                        <div class="d-flex align-items-baseline {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                                            <div>
                                                                {{ number_format(30, 0, __('.'), __(',')) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="progress height-1.25 w-100">
                                                        <div class="progress-bar rounded" role="progressbar" style="width: 60%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border-0 shadow-lg border-radius-2xl overflow-hidden cursor-default mt-3">
                                    <div class="card-body">
                                        <div class="list-group list-group-flush my-n3">
                                            <div class="list-group-item px-0 border-0">
                                                <div class="d-flex flex-column">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <div class="d-flex text-truncate align-items-center">
                                                            <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"><img src="{{ asset('images') }}/icons/browsers/chrome.svg" class="width-4 height-4"></div>
                                                            <div class="text-truncate">
                                                                Chrome
                                                            </div>
                                                        </div>

                                                        <div class="d-flex align-items-baseline {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                                            <div>
                                                                {{ number_format(25, 0, __('.'), __(',')) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="progress height-1.25 w-100">
                                                        <div class="progress-bar rounded" role="progressbar" style="width: 48%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border-0 shadow-lg border-radius-2xl overflow-hidden cursor-default mt-3">
                                    <div class="card-body">
                                        <div class="list-group list-group-flush my-n3">
                                            <div class="list-group-item px-0 border-0">
                                                <div class="d-flex flex-column">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <div class="d-flex text-truncate align-items-center">
                                                            <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="width-4 height-4" viewBox="0 0 40 40"><path d="M20,40A20,20,0,1,0,0,20,20,20,0,0,0,20,40ZM26.24,9.32c.3-1.08-.74-1.72-1.7-1L11.19,17.79c-1,.74-.87,2.21.25,2.21H15v0H21.8l-5.58,2-2.46,8.74c-.3,1.08.74,1.72,1.7,1l13.35-9.51c1-.74.87-2.21-.25-2.21H23.23Z" style="fill:#f15757;fill-rule:evenodd"/></svg>
                                                            </div>

                                                            <div class="d-flex text-truncate">
                                                                <div class="text-truncate" dir="ltr">example.com</div> <span class="text-secondary d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}"><svg xmlns="http://www.w3.org/2000/svg" class="fill-current width-3 height-3" viewBox="0 0 18 18"><path d="M16,16H2V2H9V0H2A2,2,0,0,0,0,2V16a2,2,0,0,0,2,2H16a2,2,0,0,0,2-2V9H16ZM11,0V2h3.59L4.76,11.83l1.41,1.41L16,3.41V7h2V0Z"></path></svg>
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="d-flex align-items-baseline {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                                            <div>
                                                                {{ number_format(18, 0, __('.'), __(',')) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="progress height-1.25 w-100">
                                                        <div class="progress-bar rounded" role="progressbar" style="width: 22%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border-0 shadow-lg border-radius-2xl overflow-hidden cursor-default mt-3">
                                    <div class="card-body">
                                        <div class="list-group list-group-flush my-n3">
                                            <div class="list-group-item px-0 border-0">
                                                <div class="d-flex flex-column">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <div class="d-flex text-truncate align-items-center">
                                                            <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"><img src="{{ asset('images') }}/icons/devices/desktop.svg" class="width-4 height-4"></div>
                                                            <div class="text-truncate">
                                                                Desktop
                                                            </div>
                                                        </div>

                                                        <div class="d-flex align-items-baseline {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                                            <div>
                                                                {{ number_format(36, 0, __('.'), __(',')) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="progress height-1.25 w-100">
                                                        <div class="progress-bar rounded" role="progressbar" style="width: 66%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-base-0">
        <div class="container position-relative text-center py-5 py-md-7 d-flex flex-column z-1">
            <h3 class="h2 mb-3 font-weight-bold text-center">{{ __('Integrations') }}</h3>
            <div class="m-auto text-center">
                <p class="text-muted font-weight-normal font-size-lg mb-0">{{ __('Easily integrates with your favorite retargeting platforms.') }}</p>
            </div>

            <div class="d-flex flex-wrap justify-content-center justify-content-lg-between mt-4 mx-n3">
                <div class="bg-base-1 d-flex width-20 height-20 position-relative align-items-center justify-content-center flex-shrink-0 border-radius-3xl mx-3 mt-3" data-tooltip="true" title="{{ __('Google Ads') }}">
                    <img src="{{ asset('/images/icons/pixels/' . md5('google-ads')) }}.svg" class="height-8">
                </div>

                <div class="bg-base-1 d-flex width-20 height-20 position-relative align-items-center justify-content-center flex-shrink-0 border-radius-3xl mx-3 mt-3" data-tooltip="true" title="{{ __('Google Analytics') }}">
                    <img src="{{ asset('/images/icons/pixels/' . md5('google-analytics')) }}.svg" class="height-8">
                </div>

                <div class="bg-base-1 d-flex width-20 height-20 position-relative align-items-center justify-content-center flex-shrink-0 border-radius-3xl mx-3 mt-3" data-tooltip="true" title="{{ __('Google Tag Manager') }}">
                    <img src="{{ asset('/images/icons/pixels/' . md5('google-tag-manager')) }}.svg" class="height-8">
                </div>

                <div class="bg-base-1 d-flex width-20 height-20 position-relative align-items-center justify-content-center flex-shrink-0 border-radius-3xl mx-3 mt-3" data-tooltip="true" title="{{ __('Facebook') }}">
                    <img src="{{ asset('/images/icons/pixels/' . md5('facebook')) }}.svg" class="height-8">
                </div>

                <div class="bg-base-1 d-flex width-20 height-20 position-relative align-items-center justify-content-center flex-shrink-0 border-radius-3xl mx-3 mt-3" data-tooltip="true" title="{{ __('Bing') }}">
                    <img src="{{ asset('/images/icons/pixels/' . md5('bing')) }}.svg" class="height-8">
                </div>

                <div class="bg-base-1 d-flex width-20 height-20 position-relative align-items-center justify-content-center flex-shrink-0 border-radius-3xl mx-3 mt-3" data-tooltip="true" title="{{ __('Twitter') }}">
                    <img src="{{ asset('/images/icons/pixels/' . md5('twitter')) }}.svg" class="height-8">
                </div>

                <div class="bg-base-1 d-flex width-20 height-20 position-relative align-items-center justify-content-center flex-shrink-0 border-radius-3xl mx-3 mt-3" data-tooltip="true" title="{{ __('Pinterest') }}">
                    <img src="{{ asset('/images/icons/pixels/' . md5('pinterest')) }}.svg" class="height-8">
                </div>

                <div class="bg-base-1 d-flex width-20 height-20 position-relative align-items-center justify-content-center flex-shrink-0 border-radius-3xl mx-3 mt-3" data-tooltip="true" title="{{ __('LinkedIn') }}">
                    <img src="{{ asset('/images/icons/pixels/' . md5('linkedin')) }}.svg" class="height-8">
                </div>

                <div class="bg-base-1 d-flex width-20 height-20 position-relative align-items-center justify-content-center flex-shrink-0 border-radius-3xl mx-3 mt-3" data-tooltip="true" title="{{ __('Quora') }}">
                    <img src="{{ asset('/images/icons/pixels/' . md5('quora')) }}.svg" class="height-8">
                </div>

                <div class="bg-base-1 d-flex width-20 height-20 position-relative align-items-center justify-content-center flex-shrink-0 border-radius-3xl mx-3 mt-3" data-tooltip="true" title="{{ __('Adroll') }}">
                    <img src="{{ asset('/images/icons/pixels/' . md5('adroll')) }}.svg" class="height-8">
                </div>
            </div>
        </div>
    </div>

    @if(paymentProcessors())
        <div class="bg-base-1">
            <div class="container py-5 py-md-7 position-relative z-1">
                <div class="text-center">
                    <h3 class="h2 mb-3 font-weight-bold text-center">{{ __('Pricing') }}</h3>
                    <div class="m-auto">
                        <p class="text-muted font-weight-normal font-size-lg mb-0">{{ __('Simple pricing plans for everyone and every budget.') }}</p>
                    </div>
                </div>

                @include('shared.pricing')

                <div class="d-flex justify-content-center">
                    <a href="{{ route('pricing') }}" class="btn btn-outline-primary py-2 mt-5">{{ __('Learn more') }}</a>
                </div>
            </div>
        </div>
    @else
        <div class="bg-base-1">
            <div class="container position-relative text-center py-5 py-md-7 d-flex flex-column z-1">
                <div class="flex-grow-1">
                    <div class="badge badge-pill badge-success mb-3 px-3 py-2">{{ __('Join us') }}</div>
                    <div class="text-center">
                        <h4 class="mb-3 font-weight-bold">{{ __('Ready to get started?') }}</h4>
                        <div class="m-auto">
                            <p class="font-weight-normal text-muted font-size-lg mb-0">{{ __('Create an account in seconds.') }}</p>
                        </div>
                    </div>
                </div>

                <div><a href="{{ config('settings.registration') ? route('register') : route('login') }}" class="btn btn-primary btn-lg font-size-lg mt-5">{{ __('Get started') }}</a></div>
            </div>
        </div>
    @endif
</div>
@endsection