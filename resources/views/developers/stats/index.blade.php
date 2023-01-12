@extends('layouts.app')

@section('site_title', formatTitle([__('Stats'), __('Developers'), config('settings.title')]))

@section('head_content')

@endsection

@section('content')
    <div class="bg-base-1 flex-fill">
        <div class="container h-100 py-3 my-3">

            @include('shared.breadcrumbs', ['breadcrumbs' => [
                ['url' => route('home'), 'title' => __('Home')],
                ['url' => route('developers'), 'title' => __('Developers')],
                ['title' => __('Stats')]
            ]])

            <h1 class="h2 mb-0 d-inline-block">{{ __('Stats') }}</h1>

            @include('developers.notes')

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
<pre class="m-0 text-light">{!! str_replace(':id', '<span class="text-success">{id}</span>', route('api.stats.show', ['id' => ':id'])) !!}</pre>
</div>

                    <p class="mb-1">
                        {{ __('Request example') }}:
                    </p>
<pre class="bg-dark text-light p-3 mb-0 rounded text-left" dir="ltr">
curl --location --request GET '{!! str_replace([':id', '%3Aname', '%3Afrom', '%3Ato'], ['<span class="text-success">{id}</span>', '<span class="text-success">{name}</span>', '<span class="text-success">{from}</span>', '<span class="text-success">{to}</span>'], route('api.stats.show', ['id' => ':id', 'name' => ':name', 'from' => ':from', 'to' => ':to'])) !!}' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer <span class="text-success">{api_key}</span>'
</pre>
                @include('developers.stats.list', ['type' => 0])
                </div>
            </div>
        </div>
    </div>
@endsection

@include('shared.sidebars.user')