@extends('layouts.app')

@section('site_title', formatTitle([__('Dashboard'), config('settings.title')]))

@section('content')
<div class="bg-base-1 flex-fill">
    <div class="bg-base-0">
        <div class="container py-5">
            <div class="d-flex">
                <div class="row no-gutters w-100">
                    <div class="d-flex col-12 col-md">
                        <div class="flex-shrink-1">
                            <a href="{{ route('account') }}" class="d-block"><img src="{{ gravatar(Auth::user()->email, 128) }}" class="rounded-circle width-16 height-16"></a>
                        </div>
                        <div class="flex-grow-1 d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-3' : 'ml-3') }}">
                            <div>
                                <h4 class="font-weight-medium mb-0">{{ Auth::user()->name }}</h4>

                                <div class="d-flex flex-wrap">
                                    @if(paymentProcessors())
                                        <div class="d-inline-block mt-2 {{ (__('lang_dir') == 'rtl' ? 'ml-4' : 'mr-4') }}">
                                            <div class="d-flex">
                                                <div class="d-inline-flex align-items-center">
                                                    @include('icons.package', ['class' => 'text-muted fill-current width-4 height-4'])
                                                </div>

                                                <div class="d-inline-block {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">
                                                    <a href="{{ route('account.plan') }}" class="text-dark text-decoration-none">{{ Auth::user()->plan->name }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-inline-block mt-2 {{ (__('lang_dir') == 'rtl' ? 'ml-4' : 'mr-4') }}">
                                            <div class="d-flex">
                                                <div class="d-inline-flex align-items-center">
                                                    @include('icons.email', ['class' => 'text-muted fill-current width-4 height-4'])
                                                </div>

                                                <div class="d-inline-block {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">
                                                    {{ Auth::user()->email }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(paymentProcessors())
                        @if(Auth::user()->planOnDefault())
                            <div class="col-12 col-md-auto d-flex flex-row-reverse align-items-center">
                                <a href="{{ route('pricing') }}" class="btn btn-outline-primary btn-block d-flex justify-content-center align-items-center mt-3 mt-md-0 {{ (__('lang_dir') == 'rtl' ? 'ml-md-3' : 'mr-md-3') }}">@include('icons.unarchive', ['class' => 'width-4 height-4 fill-current '.(__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')]){{ __('Upgrade') }}</a>
                            </div>
                        @else
                            <div class="col-12 col-md-auto d-flex flex-row-reverse align-items-center">
                                <a href="{{ route('pricing') }}" class="btn btn-outline-primary btn-block d-flex justify-content-center align-items-center mt-3 mt-md-0 {{ (__('lang_dir') == 'rtl' ? 'ml-md-3' : 'mr-md-3') }}">@include('icons.package', ['class' => 'width-4 height-4 fill-current '.(__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')]){{ __('Plans') }}</a>
                            </div>
                        @endif
                    @endif

                    <div class="col-12 col-md-auto d-flex flex-row-reverse align-items-center">
                        <a href="{{ route('links') }}" class="btn btn-primary btn-block d-flex justify-content-center align-items-center mt-3 mt-md-0">@include('icons.add', ['class' => 'width-4 height-4 fill-current '.(__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')]){{ __('New link') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-base-1">
        <div class="container py-3 my-3">
            <h4 class="mb-3">{{ __('Overview') }}</h4>

            <div class="row m-n2">
                @php
                    $cards = [
                        'users' =>
                        [
                            'title' => 'Links',
                            'value' => $linksCount,
                            'route' => 'links',
                            'icon' => 'link'
                        ],
                        [
                            'title' => 'Spaces',
                            'value' => $stats['spaces'],
                            'route' => 'spaces',
                            'icon' => 'workspaces'
                        ],
                        [
                            'title' => 'Domains',
                            'value' => $stats['domains'],
                            'route' => 'domains',
                            'icon' => 'website'
                        ],
                        [
                            'title' => 'Pixels',
                            'value' => $stats['pixels'],
                            'route' => 'pixels',
                            'icon' => 'filter-center-focus'
                        ]
                    ];
                @endphp

                @foreach($cards as $card)
                    <div class="col-12 col-md-6 col-xl-3 p-2">
                        <div class="card border-0 shadow-sm h-100 overflow-hidden">
                            <div class="card-body d-flex">
                                <div class="d-flex position-relative text-primary width-10 height-10 align-items-center justify-content-center flex-shrink-0">
                                    <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-xl"></div>
                                    @include('icons.' . $card['icon'], ['class' => 'fill-current width-5 height-5'])
                                </div>

                                <div class="flex-grow-1"></div>

                                <div class="d-flex align-items-center h2 font-weight-bold mb-0 text-truncate">
                                    {{ number_format($card['value'], 0, __('.'), __(',')) }}
                                </div>
                            </div>
                            <div class="card-footer bg-base-2 border-0">
                                <a href="{{ route($card['route']) }}" class="text-muted font-weight-medium d-inline-flex align-items-baseline">{{ __($card['title']) }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <h4 class="mb-3 mt-5">{{ __('Activity') }}</h4>

            <div class="row m-n2">
                <div class="col-12 col-lg-6 p-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header align-items-center">
                            <div class="row">
                                <div class="col"><div class="font-weight-medium py-1">{{ __('Latest links') }}</div></div>
                            </div>
                        </div>

                        <div class="card-body">
                            @if(count($latestLinks) == 0)
                                {{ __('No data') }}.
                            @else
                                <div class="list-group list-group-flush my-n3">
                                    @foreach($latestLinks as $link)
                                        <div class="list-group-item px-0">
                                            <div class="row align-items-center">
                                                <div class="col d-flex text-truncate">
                                                    <div class="text-truncate">
                                                        <div class="d-flex align-items-center">
                                                            <img src="https://icons.duckduckgo.com/ip3/{{ parse_url($link->url)['host'] }}.ico" rel="noreferrer" class="width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">

                                                            <div class="text-truncate">
                                                                <a href="{{ route('stats.overview', $link->id) }}" class="{{ ($link->disabled || $link->expiration_clicks && $link->clicks >= $link->expiration_clicks || \Carbon\Carbon::now()->greaterThan($link->ends_at) && $link->ends_at ? 'text-danger' : '') }}" dir="ltr">{{ str_replace(['http://', 'https://'], '', ($link->domain->url ?? config('app.url'))) .'/'.$link->alias }}</a>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <div class="width-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}"></div>
                                                            <div class="text-muted text-truncate small cursor-help" data-toggle="tooltip-url" title="{{ $link->url }}">
                                                                @if($link->title){{ $link->title }}@else<span dir="ltr">{{ str_replace(['http://', 'https://'], '', $link->url) }}</span>@endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="form-row">
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
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        @if(count($latestLinks) > 0)
                            <div class="card-footer bg-base-2 border-0">
                                <a href="{{ route('links') }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('View all') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-lg-6 p-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header align-items-center">
                            <div class="row">
                                <div class="col"><div class="font-weight-medium py-1">{{ __('Popular links') }}</div></div>
                            </div>
                        </div>

                        <div class="card-body">
                            @if(count($popularLinks) == 0)
                                {{ __('No data') }}.
                            @else
                                <div class="list-group list-group-flush my-n3">
                                    @foreach($popularLinks as $link)
                                        <div class="list-group-item px-0">
                                            <div class="row align-items-center">
                                                <div class="col d-flex text-truncate">
                                                    <div class="text-truncate">
                                                        <div class="d-flex align-items-center">
                                                            <img src="https://icons.duckduckgo.com/ip3/{{ parse_url($link->url)['host'] }}.ico" rel="noreferrer" class="width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">

                                                            <div class="text-truncate">
                                                                <a href="{{ route('stats.overview', $link->id) }}" class="{{ ($link->disabled || $link->expiration_clicks && $link->clicks >= $link->expiration_clicks || \Carbon\Carbon::now()->greaterThan($link->ends_at) && $link->ends_at ? 'text-danger' : '') }}" dir="ltr">{{ str_replace(['http://', 'https://'], '', ($link->domain->url ?? config('app.url'))) .'/'.$link->alias }}</a>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <div class="width-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}"></div>
                                                            <div class="text-muted text-truncate small cursor-help" data-toggle="tooltip-url" title="{{ $link->url }}">
                                                                @if($link->title){{ $link->title }}@else<span dir="ltr">{{ str_replace(['http://', 'https://'], '', $link->url) }}</span>@endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="form-row">
                                                        <div class="col">
                                                            <a href="{{ route('stats.overview', $link->id) }}" class="btn btn-sm text-primary d-flex align-items-center" data-tooltip="true" title="{{ __('Stats') }}">
                                                                @include('icons.bar-chart', ['class' => 'fill-current width-4 height-4'])&#8203;
                                                            </a>
                                                        </div>
                                                        <div class="col">
                                                            @include('links.partials.menu')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        @if(count($popularLinks) > 0)
                            <div class="card-footer bg-base-2 border-0">
                                <a href="{{ route('links', ['sort' => 'max']) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('View all') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <h4 class="mb-3 mt-5">{{ __('More') }}</h4>

            <div class="row m-n2">
                <div class="col-12 col-xl-4 p-2">
                    <div class="card border-0 h-100 shadow-sm">
                        <div class="card-body d-flex">
                            <div class="d-flex position-relative text-primary width-12 height-12 align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                                <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-2xl"></div>
                                @include('icons.workspaces', ['class' => 'fill-current width-6 height-6'])
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <a href="{{ route('spaces.new') }}" class="text-dark font-weight-medium text-decoration-none stretched-link">{{ __('Space') }}</a>

                                <div class="text-muted">
                                    {{ __('Create a new space.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4 p-2">
                    <div class="card border-0 h-100 shadow-sm">
                        <div class="card-body d-flex">
                            <div class="d-flex position-relative text-primary width-12 height-12 align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                                <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-2xl"></div>
                                @include('icons.website', ['class' => 'fill-current width-6 height-6'])
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <a href="{{ route('domains.new') }}" class="text-dark font-weight-medium text-decoration-none stretched-link">{{ __('Domain') }}</a>

                                <div class="text-muted">
                                    {{ __('Add a new domain.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4 p-2">
                    <div class="card border-0 h-100 shadow-sm">
                        <div class="card-body d-flex">
                            <div class="d-flex position-relative text-primary width-12 height-12 align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                                <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-2xl"></div>
                                @include('icons.filter-center-focus', ['class' => 'fill-current width-6 height-6'])
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <a href="{{ route('pixels.new') }}" class="text-dark font-weight-medium text-decoration-none stretched-link">{{ __('Pixel') }}</a>

                                <div class="text-muted">
                                    {{ __('Integrate a new pixel.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('shared.modals.share-link')
@endsection

@include('shared.sidebars.user')
