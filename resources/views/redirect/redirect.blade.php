@extends('layouts.redirect-pixels')

@section('site_title', __($link->title))

@section('head_content')
    <meta name="robots" content="noindex">

    @if($link->title)
        <meta property="og:title" content="{{ $link->title }}">
    @endif

    @if($link->description)
        <meta name="description" content="{{ $link->description }}">
        <meta property="og:description" content="{{ $link->description }}">
    @endif

    @if($link->image)
        <meta property="og:image" content="{{ $link->image }}">
    @endif

    <meta property="og:url" content="{{ $link->url }}">
@endsection

@section('content')
    @foreach($link->pixels as $pixel)
        @include('redirect.pixels.' . $pixel->type)
    @endforeach

    <script>
        setTimeout(function () { window.location = '{!! htmlspecialchars($link->url) !!}'; }, 500);
    </script>
@endsection