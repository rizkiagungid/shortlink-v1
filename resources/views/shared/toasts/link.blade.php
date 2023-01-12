@if(request()->session()->get('toast'))
    <div class="position-relative position-lg-fixed z-1001 width-lg-96 top-0 {{ (__('lang_dir') == 'rtl' ? 'left-0' : 'right-0') }}">
        @foreach(request()->session()->get('toast') as $link)
            <div aria-live="polite" aria-atomic="true" class="position-relative">
                <div class="toast backdrop-filter-blur fade show border-0 font-size-base mx-lg-3 shadow-sm mt-3 overflow-hidden max-width-full" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false" style="max-width: inherit;">
                    <div class="toast-header px-1 py-2">
                        <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'pr-2 pl-3' : 'pl-2 pr-3') }}">@include('icons.link', ['class' => 'fill-current width-4 height-4'])</div>
                        <div class="{{ (__('lang_dir') == 'rtl' ? 'ml-auto' : 'mr-auto') }}">{{ __('Link shortened') }}</div>
                        <button type="button" class="close d-flex align-items-center justify-content-center p-2" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true" class="d-flex align-items-center">@include('icons.close', ['class' => 'fill-current width-4 height-4'])</span>
                        </button>
                    </div>
                    <div class="toast-body">
                        <div class="row">
                            <div class="col d-flex text-truncate">
                                <div class="{{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"><img src="https://icons.duckduckgo.com/ip3/{{ parse_url($link->url)['host'] }}.ico" rel="noreferrer" class="width-4 height-4"></div>

                                <div class="text-truncate">
                                    <a href="{{ route('stats.overview', $link->id) }}" dir="ltr">{{ str_replace(['http://', 'https://'], '', ($link->domain->url ?? config('app.url'))) .'/'.$link->alias }}</a>

                                    <div class="text-dark text-truncate small">
                                        <span class="text-secondary cursor-help" data-toggle="tooltip-url" title="{{ $link->url }}">@if($link->title){{ $link->title }}@else<span dir="ltr">{{ str_replace(['http://', 'https://'], '', $link->url) }}</span>@endif</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-auto d-flex">
                                @include('shared.buttons.copy-link', ['class' => 'btn-sm text-primary'])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif