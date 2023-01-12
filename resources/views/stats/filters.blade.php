<div class="col">
    <form method="GET" action="{{ route(Route::currentRouteName(), ['id' => $link->id, 'from' => $range['from'], 'to' => $range['to']]) }}" class="d-md-flex">
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
                                    <a href="{{ route(Route::currentRouteName(), ['id' => $link->id, 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-secondary">{{ __('Reset') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="dropdown-divider my-0"></div>

                    <input name="from" type="hidden" value="{{ $range['from'] }}">
                    <input name="to" type="hidden" value="{{ $range['to'] }}">

                    <div class="max-height-96 overflow-auto pt-3">
                        <div class="form-group px-4">
                            <label for="i-search-by" class="small">{{ __('Search by') }}</label>
                            <select name="search_by" id="i-search-by" class="custom-select custom-select-sm">
                                @foreach(['value' => $name] as $key => $value)
                                    <option value="{{ $key }}" @if(request()->input('search_by') == $key || !request()->input('search_by') && $key == 'name') selected @endif>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="i-sort-by" class="small">{{ __('Sort by') }}</label>
                            <select name="sort_by" id="i-sort-by" class="custom-select custom-select-sm">
                                @foreach(['count' => $count, 'value' => $name] as $key => $value)
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
    <a href="{{ route($export, ['id' => $link->id] + Request::query()) }}" data-toggle="modal" data-target="#export-modal" class="btn btn-sm btn-outline-primary d-flex align-items-center" data-tooltip="true" title="{{ __('Export') }}">@include('icons.file-download', ['class' => 'fill-current width-4 height-4'])&#8203;</a>

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
                    @if($link->user->can('dataExport', ['App\Models\User']))
                        {{ __('Are you sure you want to export this table?') }}
                    @else
                        @if(paymentProcessors())
                            @if(Auth::check() && $link->user->id == Auth::user()->id)
                                @include('shared.features.locked')
                            @else
                                @include('shared.features.unavailable')
                            @endif
                        @else
                            @include('shared.features.unavailable')
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    @if($link->user->can('dataExport', ['App\Models\User']))
                        <a href="{{ route($export, ['id' => $link->id] + Request::query()) }}" target="_self" class="btn btn-primary" id="exportButton">{{ __('Export') }}</a>
                    @endif
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
</div>