<a href="#" class="btn d-flex align-items-center {{ $class }}" data-clipboard-copy="{{ (($link->domain->url ?? config('app.url')) . '/' . $link->alias) }}" data-tooltip-copy="true" title="{{ __('Copy') }}" data-text-copy="{{ __('Copy') }}" data-text-copied="{{ __('Copied') }}">
    @include('icons.copy-link', ['class' => 'fill-current width-4 height-4'])&#8203;
</a>