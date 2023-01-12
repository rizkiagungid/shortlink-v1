@extends('layouts.app')

@section('site_title', formatTitle([__('Pixels'), __('Developers'), config('settings.title')]))

@section('head_content')

@endsection

@section('content')
    <div class="bg-base-1 flex-fill">
        <div class="container h-100 py-3 my-3">

            @include('shared.breadcrumbs', ['breadcrumbs' => [
                ['url' => route('home'), 'title' => __('Home')],
                ['url' => route('developers'), 'title' => __('Developers')],
                ['title' => __('Pixels')]
            ]])

            <h1 class="h2 mb-0 d-inline-block">{{ __('Pixels') }}</h1>

            @include('developers.notes')

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header align-items-center">
                    <div class="row">
                        <div class="col"><div class="font-weight-medium py-1">{{ __('List') }}</div></div>
                    </div>
                </div>

                <div class="card-body">
                    <p class="mb-1">
                        {{ __('API endpoint') }}:
                    </p>

<div class="bg-dark text-light p-3 rounded d-flex align-items-center mb-3" dir="ltr">
    <span class="badge bg-light text-success px-2 py-1 mr-3">GET</span>
    <pre class="m-0 text-light">{{ route('api.pixels.index') }}</pre>
</div>

                    <p class="mb-1">
                        {{ __('Request example') }}:
                    </p>
<pre class="bg-dark text-light p-3 mb-0 rounded text-left" dir="ltr">
curl --location --request GET '{{ route('api.pixels.index') }}' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer <span class="text-success">{api_key}</span>'
</pre>

                @include('developers.pixels.list', ['type' => 0])
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header align-items-center">
                    <div class="row">
                        <div class="col"><div class="font-weight-medium py-1">{{ __('Show') }}</div></div>
                    </div>
                </div>

                <div class="card-body">
                    <p class="mb-1">
                        {{ __('API endpoint') }}:
                    </p>

<div class="bg-dark text-light p-3 rounded d-flex align-items-center mb-3" dir="ltr">
<span class="badge bg-light text-success px-2 py-1 mr-3">GET</span>
<pre class="m-0 text-light">{!! str_replace(':id', '<span class="text-success">{id}</span>', route('api.pixels.show', ['id' => ':id'])) !!}</pre>
</div>

                    <p class="mb-1">
                        {{ __('Request example') }}:
                    </p>
<pre class="bg-dark text-light p-3 mb-0 rounded text-left" dir="ltr">
curl --location --request GET '{!! str_replace(':id', '<span class="text-success">{id}</span>', route('api.pixels.show', ['id' => ':id'])) !!}' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer <span class="text-success">{api_key}</span>'
</pre>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header align-items-center">
                    <div class="row">
                        <div class="col"><div class="font-weight-medium py-1">{{ __('Store') }}</div></div>
                    </div>
                </div>

                <div class="card-body">
                    <p class="mb-1">
                        {{ __('API endpoint') }}:
                    </p>

<div class="bg-dark text-light p-3 rounded d-flex align-items-center mb-3" dir="ltr">
<span class="badge bg-light text-warning px-2 py-1 mr-3">POST</span>
<pre class="m-0 text-light">{{ route('api.pixels.store') }}</pre>
</div>
                    <p class="mb-1">
                        {{ __('Request example') }}:
                    </p>
<pre class="bg-dark text-light p-3 rounded text-left" dir="ltr">
curl --location --request POST '{{ route('api.pixels.store') }}' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--header 'Authorization: Bearer <span class="text-success">{api_key}</span>' \
--data-urlencode 'name=<span class="text-success">{name}</span>' \
--data-urlencode 'type=<span class="text-success">{type}</span>' \
--data-urlencode 'value=<span class="text-success">{value}</span>'
</pre>

                    @include('developers.pixels.store-update', ['type' => 1])
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header align-items-center">
                    <div class="row">
                        <div class="col"><div class="font-weight-medium py-1">{{ __('Update') }}</div></div>
                    </div>
                </div>

                <div class="card-body">
                    <p class="mb-1">
                        {{ __('API endpoint') }}:
                    </p>

                    <div class="bg-dark text-light p-3 rounded d-flex align-items-center mb-3" dir="ltr">
                        <span class="badge bg-light text-info px-2 py-1 mr-2">PUT</span> <span class="badge bg-light text-info px-2 py-1 mr-3">PATCH</span>
                        <pre class="m-0 text-light">{!! str_replace(':id', '<span class="text-success">{id}</span>', route('api.pixels.update', ['id' => ':id'])) !!}</pre>
                    </div>

                    <p class="mb-1">
                        {{ __('Request example') }}:
                    </p>
                    <pre class="bg-dark text-light p-3 rounded text-left" dir="ltr">
curl --location --request PUT '{!! str_replace(':id', '<span class="text-success">{id}</span>', route('api.pixels.update', ['id' => ':id'])) !!}' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--header 'Authorization: Bearer <span class="text-success">{api_key}</span>'
</pre>

                    @include('developers.pixels.store-update', ['type' => 0])
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header align-items-center">
                    <div class="row">
                        <div class="col"><div class="font-weight-medium py-1">{{ __('Delete') }}</div></div>
                    </div>
                </div>

                <div class="card-body">
                    <p class="mb-1">
                        {{ __('API endpoint') }}:
                    </p>

                    <div class="bg-dark text-light p-3 rounded d-flex align-items-center mb-3" dir="ltr">
                        <span class="badge bg-light text-danger px-2 py-1 mr-3">DELETE</span>
                        <pre class="m-0 text-light">{!! str_replace(':id', '<span class="text-success">{id}</span>', route('api.pixels.destroy', ['id' => ':id'])) !!}</pre>
                    </div>

                    <p class="mb-1">
                        {{ __('Request example') }}:
                    </p>
                    <pre class="bg-dark text-light p-3 mb-0 rounded text-left" dir="ltr">
curl --location --request DELETE '{!! str_replace(':id', '<span class="text-success">{id}</span>', route('api.pixels.destroy', ['id' => ':id'])) !!}' \
--header 'Authorization: Bearer <span class="text-success">{api_key}</span>'
</pre>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('shared.sidebars.user')