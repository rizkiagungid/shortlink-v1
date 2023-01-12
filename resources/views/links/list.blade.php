@section('site_title', formatTitle([__('Links'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['title' => __('Links')],
]])

<div class="d-flex">
    <div class="flex-grow-1">
        <h1 class="h2 mb-0 d-inline-block">{{ __('Links') }}</h1>
    </div>
</div>

@include('links.new')

<div class="card border-0 shadow-sm mt-3">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col"><div class="font-weight-medium py-1">{{ __('Links') }}</div></div>
            <div class="col-auto">
                <div class="form-row">
                    <div class="col">
                        <form method="GET" action="{{ route('links') }}" class="d-md-flex">
                            <div class="input-group input-group-sm">
                                <input class="form-control" name="search" placeholder="{{ __('Search') }}" value="{{ app('request')->input('search') }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-primary d-flex align-items-center dropdown-toggle dropdown-toggle-split reset-after" data-tooltip="true" title="{{ __('Filters') }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@include('icons.filter', ['class' => 'fill-current width-4 height-4'])&#8203;</button>
                                    <div class="dropdown-menu {{ (__('lang_dir') == 'rtl' ? 'dropdown-menu' : 'dropdown-menu-right') }} border-0 shadow width-64 p-0" id="search-filters">
                                        <div class="dropdown-header py-3">
                                            <div class="row">
                                                <div class="col"><div class="font-weight-medium m-0 text-body">{{ __('Filters') }}</div></div>
                                                <div class="col-auto">
                                                    @if(request()->input('per_page'))
                                                        <a href="{{ route('links') }}" class="text-secondary">{{ __('Reset') }}</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="dropdown-divider my-0"></div>

                                        <div class="max-height-96 overflow-auto pt-3">
                                            <div class="form-group px-4">
                                                <label for="i-search-by" class="small">{{ __('Search by') }}</label>
                                                <select name="search_by" id="i-search-by" class="custom-select custom-select-sm">
                                                    @foreach(['title' => __('Title'), 'alias' => __('Alias'), 'url' => __('URL')] as $key => $value)
                                                        <option value="{{ $key }}" @if(request()->input('search_by') == $key || !request()->input('search_by') && $key == 'name') selected @endif>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group px-4">
                                                <label for="i-domain" class="small">{{ __('Domain') }}</label>
                                                <select name="domain" id="i-domain" class="custom-select custom-select-sm">
                                                    <option value="">{{ __('All') }}</option>
                                                    @foreach($domains as $domain)
                                                        <option value="{{ $domain->id }}" @if(request()->input('domain') == $domain->id && request()->input('domain') !== null) selected @endif>{{ $domain->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group px-4">
                                                <label for="i-space" class="small">{{ __('Space') }}</label>
                                                <select name="space" id="i-space" class="custom-select custom-select-sm">
                                                    <option value="">{{ __('All') }}</option>
                                                    <option value="0" @if(request()->input('space') == 0 && request()->input('space') !== null) selected @endif>{{ __('None') }}</option>
                                                    @foreach($spaces as $space)
                                                        <option value="{{ $space->id }}" @if(request()->input('space') == $space->id && request()->input('space') !== null) selected @endif>{{ $space->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group px-4">
                                                <label for="i-pixel" class="small">{{ __('Pixel') }}</label>
                                                <select name="pixel" id="i-pixel" class="custom-select custom-select-sm">
                                                    <option value="">{{ __('All') }}</option>
                                                    @foreach($pixels as $pixel)
                                                        <option value="{{ $pixel->id }}" @if(request()->input('pixel') == $pixel->id && request()->input('pixel') !== null) selected @endif>{{ $pixel->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group px-4">
                                                <label for="i-status" class="small">{{ __('Status') }}</label>
                                                <select name="status" id="i-status" class="custom-select custom-select-sm">
                                                    @foreach([0 => __('All'), 1 => __('Active'), 2 => __('Expired'), 3 => __('Disabled')] as $key => $value)
                                                        <option value="{{ $key }}" @if(request()->input('status') == $key && request()->input('status') !== null) selected @endif>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group px-4">
                                                <label for="i-sort-by" class="small">{{ __('Sort by') }}</label>
                                                <select name="sort_by" id="i-sort-by" class="custom-select custom-select-sm">
                                                    @foreach(['id' => __('Date created'), 'clicks' => __('Clicks'), 'title' => __('Title'), 'alias' => __('Alias'), 'url' => __('URL')] as $key => $value)
                                                        <option value="{{ $key }}" @if(request()->input('sort_by') == $key) selected @endif>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group px-4">
                                                <label for="i-sort" class="small">{{ __('Sort') }}</label>
                                                <select name="sort" id="i-sort" class="custom-select custom-select-sm">
                                                    @foreach(['desc' => __('Descending'), 'asc' => __('Ascending')] as $key => $value)
                                                        <option value="{{ $key }}" @if(request()->input('sort') == $key) selected @endif>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group px-4">
                                                <label for="i-per-page" class="small">{{ __('Results per page') }}</label>
                                                <select name="per_page" id="i-per-page" class="custom-select custom-select-sm">
                                                    @foreach([10, 25, 50, 100] as $value)
                                                        <option value="{{ $value }}" @if(request()->input('per_page') == $value || request()->input('per_page') == null && $value == config('settings.paginate')) selected @endif>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="dropdown-divider my-0"></div>

                                        <div class="px-4 py-3">
                                            <button type="submit" class="btn btn-primary btn-sm btn-block">{{ __('Search') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-auto">
                        <button type="button" class="btn btn-sm btn-outline-primary d-flex align-items-center" data-toggle="modal" data-target="#export-modal" data-tooltip="true" title="{{ __('Export') }}">@include('icons.file-download', ['class' => 'fill-current width-4 height-4'])&#8203;</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        @if(count($links) == 0)
            {{ __('No results found.') }}
        @else
            <div class="list-group list-group-flush my-n3">
                <div class="list-group-item px-0 text-muted">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="row align-items-center">
                                <div class="col-12 col-md-8 col-lg-6 d-flex text-truncate">
                                    {{ __('URL') }}
                                </div>

                                <div class="d-none d-md-block col-md-4 col-lg-2 text-truncate">
                                    {{ __('Space') }}
                                </div>

                                <div class="d-none d-lg-block col-lg-2 text-truncate">
                                    {{ __('Clicks') }}
                                </div>

                                <div class="d-none d-lg-block col-lg-2 text-truncate">
                                    {{ __('Created at') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="form-row">
                                <div class="col">
                                    <button type="button" class="btn btn-sm text-primary d-flex align-items-center invisible">@include('icons.copy-link', ['class' => 'fill-current width-4 height-4'])&#8203;</button>
                                </div>
                                <div class="col">
                                    <div class="invisible btn d-flex align-items-center btn-sm text-primary">@include('icons.more-horiz', ['class' => 'fill-current width-4 height-4'])&#8203;</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach($links as $link)
                    <div class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col text-truncate">
                                <div class="row align-items-center">
                                    <div class="col-12 col-md-8 col-lg-6 d-flex text-truncate">
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

                                    <div class="d-none d-md-flex align-items-center col-md-4 col-lg-2">
                                        @if(isset($link->space->name))
                                            <a href="{{ route('links', ['space' => $link->space->id]) }}" class="badge badge-{{ formatSpace()[$link->space->color] }} text-truncate">{{ $link->space->name }}</a>
                                        @else
                                            <div class="badge badge-secondary text-truncate">{{ __('None') }}</div>
                                        @endif
                                    </div>

                                    <div class="d-none d-lg-block col-lg-2 text-truncate">
                                        <a href="{{ route('stats.overview', $link->id) }}" dir="ltr" class="text-dark text-truncate">{{ number_format($link->clicks, 0, __('.'), __(',')) }}</a>
                                    </div>

                                    <div class="d-none d-lg-flex col-lg-2">
                                        <div class="text-truncate" data-tooltip="true" title="{{ $link->created_at->tz(Auth::user()->timezone ?? config('app.timezone'))->format(__('Y-m-d') . ' H:i:s') }}">{{ $link->created_at->diffForHumans() }}</div>
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

                <div class="mt-3 align-items-center">
                    <div class="row">
                        <div class="col">
                            <div class="mt-2 mb-3">{{ __('Showing :from-:to of :total', ['from' => $links->firstItem(), 'to' => $links->lastItem(), 'total' => $links->total()]) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            {{ $links->onEachSide(1)->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="export-modal" tabindex="-1" role="dialog" aria-labelledby="export-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h6 class="modal-title" id="export-modal-label">{{ __('Export') }}</h6>
                <button type="button" class="close d-flex align-items-center justify-content-center width-12 height-14" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="d-flex align-items-center">@include('icons.close', ['class' => 'fill-current width-4 height-4'])</span>
                </button>
            </div>
            <div class="modal-body">
                @can('dataExport', ['App\Models\User'])
                    {{ __('Are you sure you want to export this table?') }}
                @else
                    @if(paymentProcessors())
                        @include('shared.features.locked')
                    @else
                        @include('shared.features.unavailable')
                    @endif
                @endcan
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                @can('dataExport', ['App\Models\User'])
                    <a href="{{ route('links.export', Request::query()) }}" target="_self" class="btn btn-primary" id="exportButton">{{ __('Export') }}</a>
                @endcan
            </div>
        </div>
    </div>
</div>
<script>
    'use strict';

    window.addEventListener('DOMContentLoaded', function () {
        jQuery('#exportButton').on('click', function () {
            jQuery('#export-modal').modal('hide');
        });
    });
</script>

@include('shared.modals.share-link')