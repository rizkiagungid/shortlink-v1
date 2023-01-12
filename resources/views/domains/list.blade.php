@section('site_title', formatTitle([__('Domains'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['title' => __('Domains')]
]])

<div class="d-flex">
    <div class="flex-grow-1">
        <h1 class="h2 mb-3 d-inline-block">{{ __('Domains') }}</h1>
    </div>
    <div>
        <a href="{{ route('domains.new') }}" class="btn btn-primary mb-3">{{ __('New') }}</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col"><div class="font-weight-medium py-1">{{ __('Domains') }}</div></div>
            <div class="col-auto">
                <form method="GET" action="{{ route('domains') }}">
                    <div class="input-group input-group-sm">
                        <input class="form-control" name="search" placeholder="{{ __('Search') }}" value="{{ app('request')->input('search') }}">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-primary d-flex align-items-center dropdown-toggle dropdown-toggle-split reset-after" data-tooltip="true" title="{{ __('Filters') }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@include('icons.filter', ['class' => 'fill-current width-4 height-4'])&#8203;</button>
                            <div class="dropdown-menu {{ (__('lang_dir') == 'rtl' ? 'dropdown-menu' : 'dropdown-menu-right') }} border-0 shadow width-64 p-0" id="search-filters">
                                <div class="dropdown-header py-3">
                                    <div class="row">
                                        <div class="col"><div class="font-weight-medium m-0 text-body">{{ __('Filters') }}</div></div>
                                        <div class="col-auto"><a href="{{ route('domains') }}" class="text-secondary">{{ __('Reset') }}</a></div>
                                    </div>
                                </div>

                                <div class="dropdown-divider my-0"></div>

                                <div class="max-height-96 overflow-auto pt-3">
                                    <div class="form-group px-4">
                                        <label for="i-search-by" class="small">{{ __('Search by') }}</label>
                                        <select name="search_by" id="i-search-by" class="custom-select custom-select-sm">
                                            @foreach(['name' => __('Name')] as $key => $value)
                                                <option value="{{ $key }}" @if(request()->input('search_by') == $key || !request()->input('search_by') && $key == 'name') selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group px-4">
                                        <label for="i-sort-by" class="small">{{ __('Sort by') }}</label>
                                        <select name="sort_by" id="i-sort-by" class="custom-select custom-select-sm">
                                            @foreach(['id' => __('Date created'), 'name' => __('Name')] as $key => $value)
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
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        @if(count($domains) == 0)
            {{ __('No results found.') }}
        @else
            <div class="list-group list-group-flush my-n3">
                <div class="list-group-item px-0 text-muted">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="row align-items-center">
                                <div class="col-12 col-lg-8 d-flex">
                                    {{ __('Name') }}
                                </div>

                                <div class="d-none d-lg-block col-lg-2">
                                    {{ __('Links') }}
                                </div>

                                <div class="d-none d-lg-block col-lg-2">
                                    {{ __('Created at') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="form-row">
                                <div class="col">
                                    <div class="invisible btn d-flex align-items-center btn-sm text-primary">@include('icons.more-horiz', ['class' => 'fill-current width-4 height-4'])&#8203;</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach($domains as $domain)
                    <div class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col text-truncate">
                                <div class="row align-items-center">
                                    <div class="col-12 col-lg-8 d-flex">
                                        <div class="text-truncate">
                                            <div class="d-flex">
                                                <div class="d-flex align-items-center text-truncate">
                                                    <img src="https://icons.duckduckgo.com/ip3/{{ $domain->name }}.ico" rel="noreferrer" class="width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                                                    <div class="text-truncate"><a href="{{ route('domains.edit', $domain->id) }}" dir="ltr">{{ $domain->name }}</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-none d-lg-block col-lg-2">
                                        <a href="{{ route('links', ['domain' => $domain->id]) }}" class="text-dark">{{ $domain->totalLinks }}</a>
                                    </div>

                                    <div class="d-none d-lg-block col-lg-2">
                                        <div class="text-truncate" data-tooltip="true" title="{{ $domain->created_at->tz(Auth::user()->timezone ?? config('app.timezone'))->format(__('Y-m-d') . ' H:i:s') }}">{{ $domain->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
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
                @endforeach

                <div class="mt-3 align-items-center">
                    <div class="row">
                        <div class="col">
                            <div class="mt-2 mb-3">{{ __('Showing :from-:to of :total', ['from' => $domains->firstItem(), 'to' => $domains->lastItem(), 'total' => $domains->total()]) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            {{ $domains->onEachSide(1)->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>