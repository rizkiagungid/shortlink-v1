@section('site_title', formatTitle([__('Edit'), __('Domain'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => request()->is('admin/*') ? route('admin.dashboard') : route('dashboard'), 'title' => request()->is('admin/*') ? __('Admin') : __('Home')],
    ['url' => request()->is('admin/*') ? route('admin.domains') : route('domains'), 'title' => __('Domains')],
    ['title' => __('Edit')],
]])

<div class="d-flex">
    <h1 class="h2 mb-3 text-break">{{ __('Edit') }}</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('Domain') }}</div>
            </div>
            <div class="col-auto">
                <div class="form-row">
                    <div class="col">
                        @include('domains.partials.menu')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ request()->is('admin/*') ? route('admin.domains.edit', $domain->id) : route('domains.edit', $domain->id) }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="i-name">{{ __('Domain') }}</label>
                <input type="text" dir="ltr" name="name" class="form-control" id="i-name" value="{{ old('name') ?? $domain->name }}" readonly>
            </div>

            <div class="form-group">
                <label for="i-index-page">{{ __('Custom index') }}</label>
                <input type="text" dir="ltr" name="index_page" id="i-index-page" class="form-control{{ $errors->has('index_page') ? ' is-invalid' : '' }}" value="{{ (old('index_page') ?? $domain->index_page) }}">
                @if ($errors->has('index_page'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('index_page') }}</strong>
                    </span>
                @endif
                <small class="text-muted">{{ __('Add a custom index page.') }}</small>
            </div>

            <div class="form-group">
                <label for="i-not-found-page">{{ __('Custom 404') }}</label>
                <input type="text" dir="ltr" name="not_found_page" id="i-not-found-page" class="form-control{{ $errors->has('not_found_page') ? ' is-invalid' : '' }}" value="{{ (old('not_found_page') ?? $domain->not_found_page) }}">
                @if ($errors->has('not_found_page'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('not_found_page') }}</strong>
                    </span>
                @endif
                <small class="form-text text-muted">{{ __('Add a custom 404 page.') }}</small>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>
    </div>
</div>

@if(request()->is('admin/*'))
    <div class="mb-3">
        @include('admin.users.partials.card', ['user' => $domain->user])
    </div>

    <div class="row m-n2">
        @php
            $menu = [
                ['icon' => 'icons.link', 'route' => 'admin.links', 'title' => __('Links'), 'stats' => 'links']
            ];
        @endphp

        @foreach($menu as $link)
            <div class="col-12 col-md-6 col-lg-4 p-2">
                <a href="{{ route($link['route'], ['domain' => $domain->id]) }}" class="text-decoration-none text-secondary">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            @include($link['icon'], ['class' => 'fill-current width-4 height-4 ' . (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3 ')])
                            <div class="text-truncate">{{ $link['title'] }}</div>
                            @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current mx-2'])
                            <div class="{{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }} badge badge-primary">{{ number_format($stats[$link['stats']], 0, __('.'), __(',')) }}</div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endif