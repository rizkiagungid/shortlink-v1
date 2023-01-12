@extends('layouts.redirect')

@section('site_title', __('Link preview'))

@section('content')
<div class="bg-base-1 d-flex align-items-center flex-fill">
    <div class="container">
        <div class="row h-100 justify-content-center align-items-center py-3">
            <div class="col-lg-12">
                <h1 class="h2 mb-5 text-center">{{ __('Link preview') }}</h1>
                <p class="text-center text-break text-dark font-weight-medium" dir="ltr">{{ str_replace(['http://', 'https://'], '', ($link->domain->url ?? config('app.url'))) .'/'.$link->alias }}</p>

                @if($link->expiration_clicks || $link->ends_at)
                    <div class="my-4">
                    @if($link->expiration_clicks)
                        <p class="my-2 text-center text-break text-muted">{!! __('Will expire after :count clicks', ['count' => '<span class="text-dark font-weight-medium">' . $link->expiration_clicks . '</span>']) !!}</p>
                    @endif

                    @if($link->ends_at)
                        <p class="my-2 text-center text-break text-muted">{!! __('Will expire on :date at :time', ['date' => '<span class="text-dark font-weight-medium">' . $link->ends_at->tz(Auth::user()->timezone ?? config('app.timezone'))->format(__('Y-m-d')) . '</span>', 'time' => '<span class="text-dark font-weight-medium">' . $link->ends_at->tz(Auth::user()->timezone ?? config('app.timezone'))->format('H:i') . '</span>']) !!} UTC{{ \Carbon\CarbonTimeZone::create(Auth::user()->timezone ?? config('app.timezone'))->toOffsetName() }}</p>
                    @endif

                    @if($link->expiration_url)
                        <p class="my-2 text-center text-break text-muted">{!! __('Will redirect to :url once expired', ['url' => '<span class="text-dark font-weight-medium" dir="ltr">' . e($link->expiration_url) . '</span>']) !!}</p>
                    @endif
                    </div>
                @endif

                @if($link->target_type)
                    <div class="my-4">
                    @if($link->target_type == 1 && $link->country_target !== null)
                        @foreach($link->country_target as $country)
                            <p class="my-2 text-center text-break text-muted">{!! __('If the country is :country will redirect to :url', ['country' => '<span class="text-dark font-weight-medium">' . __(config('countries')[$country->key]) . '</span>', 'url' => '<span class="text-dark font-weight-medium" dir="ltr">' . e($country->value) . '</span>']) !!}</p>
                        @endforeach
                    @endif

                    @if($link->target_type == 2 && $link->platform_target !== null)
                        @foreach($link->platform_target as $platform)
                            <p class="my-2 text-center text-break text-muted">{!! __('If the platform is :platform will redirect to :url', ['platform' => '<span class="text-dark font-weight-medium">' . $platform->key . '</span>', 'url' => '<span class="text-dark font-weight-medium" dir="ltr">' . e($platform->value) . '</span>']) !!}</p>
                        @endforeach
                    @endif

                    @if($link->target_type == 3 && $link->language_target !== null)
                        @foreach($link->language_target as $language)
                            <p class="my-2 text-center text-break text-muted">{!! __('If the language is :language will redirect to :url', ['language' => '<span class="text-dark font-weight-medium">' . $language->key . '</span>', 'url' => '<span class="text-dark font-weight-medium" dir="ltr">' . e($language->value) . '</span>']) !!}</p>
                        @endforeach
                    @endif

                    @if($link->target_type == 4 && $link->rotation_target !== null)
                        @foreach($link->rotation_target as $rotation)
                            <p class="my-2 text-center text-break text-muted">{!! __('Will rotate to :url', ['url' => '<span class="text-dark font-weight-medium" dir="ltr">' . e($rotation->value) . '</span>']) !!}</p>
                        @endforeach
                    @endif
                    </div>
                @endif

                <p class="text-center text-break text-muted">{!! __('Will redirect to :url', ['url' => '<span class="text-dark font-weight-medium" dir="ltr">' . e($link->url) . '</span>']) !!}</p>

                @if(url()->previous() != url()->current())
                    <div class="text-center mt-5">
                        <a href="{{ url()->previous() }}" class="btn btn-primary">{{ __('Go back') }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection