<div class="modal fade" id="utm-modal" tabindex="-1" role="dialog" aria-labelledby="utm-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h6 class="modal-title" id="utm-modal-label">{{ __('UTM builder') }}</h6>
                <button type="button" class="close d-flex align-items-center justify-content-center width-12 height-14" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="d-flex align-items-center">@include('icons.close', ['class' => 'fill-current width-4 height-4'])</span>
                </button>
            </div>
            <div class="modal-body">
                @can('utm', ['App\Models\Link'])
                <div class="form-row">
                    <div class="form-group col-12 col-md-6">
                        <label for="utm-source">{{ __('Source') }}</label>
                        <input type="text" name="utm_source" id="i-utm-source" class="form-control">
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="utm-medium">{{ __('Medium') }}</label>
                        <input type="text" name="utm_medium" id="i-utm-medium" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12 col-md-6">
                        <label for="utm-campaign">{{ __('Campaign') }}</label>
                        <input type="text" name="utm_campaign" id="i-utm-campaign" class="form-control">
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="utm-term">{{ __('Term') }}</label>
                        <input type="text" name="utm_term" id="i-utm-term" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12 col-md-6">
                        <label for="utm-content">{{ __('Content') }}</label>
                        <input type="text" name="utm_content" id="i-utm-content" class="form-control">
                    </div>
                    <div class="form-group col-12 col-md-6">
                    </div>
                </div>
                @else
                    @if(paymentProcessors())
                        @include('shared.features.locked')
                    @else
                        @include('shared.features.unavailable')
                    @endif
                @endcan
            </div>
            <div class="modal-footer">
                @can('utm', ['App\Models\Link'])
                    <button type="button" class="btn btn-primary" data-dismiss="modal">{{ __('Close') }}</button>
                @else
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                @endcan
            </div>
        </div>
    </div>
</div>