@extends('layouts.app')

@section('head_content')

@endsection

@section('content')
<script src="{{ asset('js/app.extras.js') }}" defer></script>
<div class="bg-base-1 flex-fill">
    <div class="container py-3 my-3">
        @include('stats.header')

        @include('stats.' . $view)

        <div class="row mt-3 small text-muted">
            <div class="col">
                {{ __('Report generated on :date at :time (UTC :offset).', ['date' => \Carbon\Carbon::now()->format(__('Y-m-d')), 'time' => \Carbon\Carbon::now()->format('H:i:s'), 'offset' => \Carbon\CarbonTimeZone::create(config('app.timezone'))->toOffsetName()]) }} <a href="{{ Request::fullUrl() }}">{{ __('Refresh report') }}</a>
            </div>
        </div>
    </div>
</div>
@include('shared.modals.share-link')
@endsection

@include('shared.sidebars.user')