@if(calcGrowth($growthCurrent, $growthPrevious) > 0)
    <!-- Increase -->
    <div class="d-flex align-items-center text-truncate text-success">
        <div class="d-flex align-items-center justify-content-center width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">@include('icons.trending-up', ['class' => 'fill-current width-3 height-3'])</div>

        <div class="text-truncate">
            {{ str_replace(['-', __('.') . '0'], '', number_format(calcGrowth($growthCurrent, $growthPrevious), 1, __('.'), __(','))) }}%
        </div>
    </div>
@elseif(calcGrowth($growthCurrent, $growthPrevious) < 0)
    <!-- Decrease -->
    <div class="d-flex align-items-center text-truncate text-danger">
        <div class="d-flex align-items-center justify-content-center width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">@include('icons.trending-down', ['class' => 'fill-current width-3 height-3'])</div>

        <div class="text-truncate">
            {{ str_replace(['-', __('.') . '0'], '', number_format(calcGrowth($growthCurrent, $growthPrevious), 1, __('.'), __(','))) }}%
        </div>
    </div>
@else
    @if($growthCurrent == $growthPrevious && $growthCurrent > 0)
        <!-- Constant -->
        <div class="text-muted">
            —
        </div>
    @elseif(!$growthPrevious)
        <div class="text-muted text-truncate">
            {{ __('No prior data') }}
        </div>
    @else
        <div class="text-muted text-truncate">
            {{ __('No current data') }}
        </div>
    @endif
@endif