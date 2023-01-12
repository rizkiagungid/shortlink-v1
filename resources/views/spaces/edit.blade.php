@section('site_title', formatTitle([__('Edit'), __('Space'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => request()->is('admin/*') ? route('admin.dashboard') : route('dashboard'), 'title' => request()->is('admin/*') ? __('Admin') : __('Home')],
    ['url' => request()->is('admin/*') ? route('admin.spaces') : route('spaces'), 'title' => __('Spaces')],
    ['title' => __('Edit')],
]])

<div class="d-flex">
    <h1 class="h2 mb-3 text-break">{{ __('Edit') }}</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col">
                <div class="font-weight-medium py-1">{{ __('Space') }}</div>
            </div>
            <div class="col-auto">
                <div class="form-row">
                    <div class="col">
                        @include('spaces.partials.menu')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ request()->is('admin/*') ? route('admin.spaces.edit', $space->id) : route('spaces.edit', $space->id) }}" method="post" enctype="multipart/form-data">
            @csrf

            @if(request()->is('admin/*'))
                <input type="hidden" name="user_id" value="{{ $space->user->id }}">
            @endif

            <div class="form-group">
                <label for="i-name">{{ __('Name') }}</label>
                <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="i-name" value="{{ old('name') ?? $space->name }}">
                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-color1">{{ __('Color') }}</label>
                <div class="row mx-n2">
                    @foreach(formatSpace() as $key => $value)
                        <div class="col-4 col-sm px-2">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="i-color{{ $key }}" name="color" class="custom-control-input{{ $errors->has('color') ? ' is-invalid' : '' }}" value="{{ $key }}" @if($key == $space->color) checked @endif>
                                <label class="custom-control-label d-flex align-items-center" for="i-color{{ $key }}"><div class="width-4 height-4 bg-{{ $value }} rounded-circle cursor-pointer"></div></label>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if ($errors->has('color'))
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $errors->first('color') }}</strong>
                    </span>
                @endif
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>
    </div>
</div>

@if(request()->is('admin/*'))
    <div class="mb-3">
        @include('admin.users.partials.card', ['user' => $space->user])
    </div>

    <div class="row m-n2">
        @php
            $menu = [
                ['icon' => 'icons.link', 'route' => 'admin.links', 'title' => __('Links'), 'stats' => 'links']
            ];
        @endphp

        @foreach($menu as $link)
            <div class="col-12 col-md-6 col-lg-4 p-2">
                <a href="{{ route($link['route'], ['space' => $space->id]) }}" class="text-decoration-none text-secondary">
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