@if(request()->is('admin/*') || request()->is('dashboard') || request()->is('links') || request()->is('links/*'))
    <a href="#" class="btn d-flex align-items-center btn-sm text-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@include('icons.more-horiz', ['class' => 'fill-current width-4 height-4'])&#8203;</a>
@endif

<div class="dropdown-menu {{ (__('lang_dir') == 'rtl' ? 'dropdown-menu' : 'dropdown-menu-right') }} border-0 shadow">
    @if(request()->is('admin/*') || Auth::check() && Auth::user()->role == 1 || Auth::check() && $link->user_id == Auth::user()->id)
        <a class="dropdown-item d-flex align-items-center" href="{{ request()->is('admin/*') || (Auth::user()->role == 1 && $link->user_id != Auth::user()->id) ? route('admin.links.edit', $link->id) : route('links.edit', $link->id) }}">@include('icons.edit', ['class' => 'text-muted fill-current width-4 height-4 '.(__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3')]) {{ __('Edit') }}</a>
    @endif

    <a class="dropdown-item d-flex align-items-center" href="{{ (($link->domain->url ?? config('app.url')) . '/' . $link->alias) }}" target="_blank" rel="nofollow noreferrer noopener">@include('icons.eye', ['class' => 'text-muted fill-current width-4 height-4 '.(__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3')]) {{ __('View') }}</a>

    <a class="dropdown-item d-flex align-items-center" href="{{ (($link->domain->url ?? config('app.url')) . '/' . $link->alias) }}/+" target="_blank">@include('icons.eye-preview', ['class' => 'text-muted fill-current width-4 height-4 '.(__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3')]) {{ __('Preview') }}</a>

    @if(isset($link->user_id))
        <a class="dropdown-item d-flex align-items-center" href="{{ route('stats.overview', $link->id) }}">@include('icons.bar-chart', ['class' => 'text-muted fill-current width-4 height-4 '.(__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3')]) {{ __('Stats') }}</a>
    @endif

    <a class="dropdown-item d-flex align-items-center link-share" href="#" data-toggle="modal" data-target="#share-modal" data-url="{{ (($link->domain->url ?? config('app.url')) . '/' . $link->alias) }}" data-title="@if($link->title){{ $link->title }}@else{{ str_replace(['http://', 'https://'], '', $link->url) }}@endif" data-qr="{{ route('qr', $link->id) }}">@include('icons.share', ['class' => 'text-muted fill-current width-4 height-4 '.(__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3')]) {{ __('Share') }}</a>

    <a class="dropdown-item d-flex align-items-center" href="{{ $link->url }}" target="_blank" rel="nofollow noreferrer noopener">@include('icons.open-in-new', ['class' => 'text-muted fill-current width-4 height-4 '.(__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3')]) {{ __('Open') }}</a>

    @if(request()->is('admin/*') || Auth::check() && Auth::user()->role == 1 || Auth::check() && $link->user_id == Auth::user()->id)
        <div class="dropdown-divider"></div>
        <a class="dropdown-item text-danger d-flex align-items-center" href="#" data-toggle="modal" data-target="#modal" data-action="{{ request()->is('admin/*') || (Auth::user()->role == 1 && $link->user_id != Auth::user()->id) ? route('admin.links.destroy', $link->id) : route('links.destroy', $link->id) }}" data-button="btn btn-danger" data-title="{{ __('Delete') }}" data-text="{{ __('Are you sure you want to delete :name?', ['name' => (str_replace(['http://', 'https://'], '', (($link->domain->url ?? config('app.url')) . '/' . $link->alias)))]) }}">@include('icons.delete', ['class' => 'fill-current width-4 height-4 '.(__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3')]) {{ __('Delete') }}</a>
    @endif
</div>