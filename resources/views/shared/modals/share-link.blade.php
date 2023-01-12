<div class="modal fade" id="share-modal" tabindex="-1" role="dialog" aria-labelledby="share-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h6 class="modal-title" id="share-modal-label">{{ __('Share') }}</h6>
                <button type="button" class="close d-flex align-items-center justify-content-center width-12 height-14" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="d-flex align-items-center">@include('icons.close', ['class' => 'fill-current width-4 height-4'])</span>
                </button>
            </div>
            <div class="modal-body d-flex flex-wrap pt-0">
                <a href="#" id="share-twitter" class="btn d-flex align-items-center icon-twitter p-2 mt-3 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }} rounded" data-tooltip="true" title="{{ __('Twitter') }}">
                    @include('icons.share.twitter',  ['class' => 'width-5 height-5 text-light fill-current'])
                </a>

                <a href="#" id="share-facebook" class="btn d-flex align-items-center icon-facebook p-2 mt-3 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }} rounded" data-tooltip="true" title="{{ __('Facebook') }}">
                    @include('icons.share.facebook',  ['class' => 'width-5 height-5 text-light fill-current'])
                </a>

                <a href="#" id="share-reddit" class="btn d-flex align-items-center icon-reddit p-2 mt-3 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }} rounded" data-tooltip="true" title="{{ __('Reddit') }}">
                    @include('icons.share.reddit',  ['class' => 'width-5 height-5 text-light fill-current'])
                </a>

                <a href="#" id="share-pinterest" class="btn d-flex align-items-center icon-pinterest p-2 mt-3 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }} rounded" data-tooltip="true" title="{{ __('Pinterest') }}">
                    @include('icons.share.pinterest',  ['class' => 'width-5 height-5 text-light fill-current'])
                </a>

                <a href="#" id="share-linkedin" class="btn d-flex align-items-center icon-linkedin p-2 mt-3 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }} rounded" data-tooltip="true" title="{{ __('LinkedIn') }}">
                    @include('icons.share.linkedin',  ['class' => 'width-5 height-5 text-light fill-current'])
                </a>

                <a href="#" id="share-tumblr" class="btn d-flex align-items-center icon-tumblr p-2 mt-3 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }} rounded" data-tooltip="true" title="{{ __('Tumblr') }}">
                    @include('icons.share.tumblr',  ['class' => 'width-5 height-5 text-light fill-current'])
                </a>

                <a href="#" id="share-email" class="btn d-flex align-items-center icon-email p-2 mt-3 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }} rounded" data-tooltip="true" title="{{ __('Email') }}">
                    @include('icons.share.email',  ['class' => 'width-5 height-5 text-light fill-current'])
                </a>

                <a href="#" id="share-qr" class="btn d-flex align-items-center icon-qr p-2 mt-3 rounded" data-tooltip="true" title="{{ __('QR code') }}">
                    @include('icons.share.qr',  ['class' => 'width-5 height-5 text-light fill-current'])
                </a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>