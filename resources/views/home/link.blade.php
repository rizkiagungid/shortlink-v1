@if(request()->session()->get('link'))
    @foreach(request()->session()->get('link') as $link)
        <div class="form-group mt-5" id="copy-form-container">
            <div class="form-row">
                <div class="col-12 col-sm">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-success {{ (__('lang_dir') == 'rtl' ? 'border-left-0' : 'border-right-0') }}"><img src="https://icons.duckduckgo.com/ip3/{{ parse_url($link->url)['host'] }}.ico" rel="noreferrer" class="width-4 height-4"></span>
                        </div>
                        <input type="text" dir="ltr" name="url" class="form-control form-control-lg font-size-lg is-valid bg-transparent{{ (__('lang_dir') == 'rtl' ? ' border-right-0 pr-0' : ' border-left-0 pl-0') }}" value="{{ str_replace(['http://', 'https://'], '', ($link->domain->url ?? config('app.url'))) . '/' . $link->alias }}" onclick="this.select();" style="background-image: none;" readonly>
                    </div>
                    <span class="valid-feedback text-break d-block" role="alert">
                        <strong>{{ __('Link successfully shortened.') }}</strong>
                    </span>
                </div>

                <div class="col-12 col-sm-auto">
                    <div class="btn-group btn-group-lg d-flex mt-3 mt-sm-0">
                        <button type="button" class="btn btn-lg btn-primary font-size-lg flex-grow-1 home-copy" data-clipboard-copy="{{ (($link->domain->url ?? config('app.url')) . '/' . $link->alias) }}">
                            <span>{{ __('Copy') }}</span><span class="d-none">{{ __('Copied') }}</span>
                        </button>
                        <button type="button" class="btn btn-primary font-size-lg dropdown-toggle dropdown-toggle-split reset-after flex-grow-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @include('icons.expand-more', ['class' => 'flex-shrink-0 fill-current width-3 height-3'])
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        @include('links.partials.menu')
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

@include('shared.modals.share-link')