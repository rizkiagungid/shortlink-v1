@section('site_title', formatTitle([__('Edit'), __('Pixel'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => request()->is('admin/*') ? route('admin.dashboard') : route('dashboard'), 'title' => request()->is('admin/*') ? __('Admin') : __('Home')],
    ['url' => request()->is('admin/*') ? route('admin.pixels') : route('pixels'), 'title' => __('Pixels')],
    ['title' => __('Edit')],
]])

<div class="d-flex">
    <h1 class="h2 mb-3 text-break">{{ __('Edit') }}</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('Pixel') }}</div>
            </div>
            <div class="col-auto">
                <div class="form-row">
                    <div class="col">
                        @include('pixels.partials.menu')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ request()->is('admin/*') ? route('admin.pixels.edit', $pixel->id) : route('pixels.edit', $pixel->id) }}" method="post" enctype="multipart/form-data">
            @csrf

            @if(request()->is('admin/*'))
                <input type="hidden" name="user_id" value="{{ $pixel->user->id }}">
            @endif

            <div class="form-group">
                <label for="i-name">{{ __('Name') }}</label>
                <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="i-name" value="{{ old('name') ?? $pixel->name }}">
                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-type">{{ __('Type') }}</label>
                <select name="type" id="i-type" class="custom-select{{ $errors->has('type') ? ' is-invalid' : '' }}">
                    @foreach(config('pixels') as $key => $value)
                        <option value="{{ $key }}" @if((old('type') !== null && old('type') == $key) || ($pixel->type == $key && old('type') == null)) selected @endif>{{ $value['name'] }}</option>
                    @endforeach
                </select>
                @if ($errors->has('type'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('type') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-value" class="d-flex align-items-center">{{ __('Value') }} <span class="d-flex align-content-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}" data-tooltip="true" title="{{ __('The pixel ID value.') }}">@include('icons.info', ['class' => 'text-muted width-4 height-4 fill-current'])</span></label>
                <input type="text" name="value" class="form-control{{ $errors->has('value') ? ' is-invalid' : '' }}" id="i-value" value="{{ old('value') ?? $pixel->value }}">
                @if ($errors->has('value'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('value') }}</strong>
                    </span>
                @endif
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>
    </div>
</div>

@if(request()->is('admin/*'))
    <div class="mb-3">
        @include('admin.users.partials.card', ['user' => $pixel->user])
    </div>

    <div class="row m-n2">
        @php
            $menu = [
                ['icon' => 'icons.link', 'route' => 'admin.links', 'title' => __('Links'), 'stats' => 'links']
            ];
        @endphp

        @foreach($menu as $link)
            <div class="col-12 col-md-6 col-lg-4 p-2">
                <a href="{{ route($link['route'], ['pixel' => $pixel->id]) }}" class="text-decoration-none text-secondary">
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