@section('site_title', formatTitle([__('Plan'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('dashboard'), 'title' => __('Home')],
    ['url' => route('account'), 'title' => __('Account')],
    ['title' => __('Plan')]
]])

<h1 class="h2 mb-3 d-inline-block">{{ __('Plan') }}</h1>

<div class="card border-0 shadow-sm">
    <div class="card-header align-items-center">
        <div class="row">
            <div class="col"><div class="font-weight-medium py-1">{{ __('Plan') }}</div></div>
            @if(paymentProcessors())
                <div class="col-auto">
                    @if(Auth::user()->planOnDefault())
                        <a href="{{ route('pricing') }}" class="btn btn-sm btn-outline-primary btn-block d-flex justify-content-center align-items-center">@include('icons.unarchive', ['class' => 'width-4 height-4 fill-current '.(__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')]){{ __('Upgrade') }}</a>
                    @else
                        <a href="{{ route('pricing') }}" class="btn btn-sm btn-outline-primary btn-block d-flex justify-content-center align-items-center">@include('icons.package', ['class' => 'width-4 height-4 fill-current '.(__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')]){{ __('Plans') }}</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        @include('shared.message')

        <form action="{{ route('account.plan') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-12 col-lg-6 mb-3">
                    <div class="text-muted">{{ __('Plan') }}</div>
                    <div>{{ $user->plan->name }}</div>
                </div>

                @if(!$user->planOnDefault())
                    @if($user->plan_payment_processor)
                        <div class="col-12 col-lg-6 mb-3">
                            <div class="text-muted">{{ __('Processor') }}</div>
                            <div>{{ config('payment.processors.' . $user->plan_payment_processor)['name'] }}</div>
                        </div>
                    @endif

                    @if($user->plan_amount && $user->plan_currency && $user->plan_interval)
                        <div class="col-12 col-lg-6 mb-3">
                            <div class="text-muted">{{ __('Amount') }}</div>
                            <div>{{ formatMoney($user->plan_amount, $user->plan_currency) }} {{ $user->plan_currency }} / <span class="text-lowercase">{{ $user->plan_interval == 'month' ? __('Month') : __('Year') }}</span></div>
                        </div>
                    @endif

                    @if($user->plan_recurring_at)
                        <div class="col-12 col-lg-6 mb-3">
                            <div class="text-muted">{{ __('Recurring at') }}</div>
                            <div>{{ $user->plan_recurring_at->tz($user->timezone ?? config('app.timezone'))->format(__('Y-m-d')) }}</div>
                        </div>
                    @endif

                    @if($user->plan_trial_ends_at && $user->plan_trial_ends_at->gt(Carbon\Carbon::now()))
                        <div class="col-12 col-lg-6 mb-3">
                            <div class="text-muted">{{ __('Trial ends at') }}</div>
                            <div>{{ $user->plan_trial_ends_at->tz($user->timezone ?? config('app.timezone'))->format(__('Y-m-d')) }}</div>
                        </div>
                    @endif

                    @if($user->plan_ends_at)
                        <div class="col-12 col-lg-6 mb-3">
                            <div class="text-muted">{{ __('Ends at') }}</div>
                            <div>{{ $user->plan_ends_at->tz($user->timezone ?? config('app.timezone'))->format(__('Y-m-d')) }}</div>
                        </div>
                    @endif
                @endif
            </div>

            <div class="hr-text mt-0"><span class="font-weight-medium text-body">{{ __('Features') }}</span></div>

            <div class="row m-n2">
                <div class="col-12 col-md-6 p-2 d-flex align-items-center">
                    @if($user->plan->features->links != 0)
                        @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                    @else
                        @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                    @endif

                    <div class="{{ ($user->plan->features->links == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                        @if($user->plan->features->links < 0)
                            {{ __('Unlimited links') }}
                        @elseif($user->plan->features->links)
                            <span class="text-muted">{{ $linksCount }} /</span> {{ __(($user->plan->features->links == 1 ? ':number link' : ':number links'), ['number' => number_format($user->plan->features->links, 0, __('.'), __(','))]) }}
                        @endif
                    </div>
                </div>

                <div class="col-12 col-md-6 p-2 d-flex align-items-center">
                    @if($user->plan->features->spaces != 0)
                        @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                    @else
                        @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                    @endif

                    <div class="{{ ($user->plan->features->spaces == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                        @if($user->plan->features->spaces < 0)
                            {{ __('Unlimited spaces') }}
                        @elseif($user->plan->features->spaces)
                            <span class="text-muted">{{ $stats['spaces'] }} /</span> {{ __(($user->plan->features->spaces == 1 ? ':number space' : ':number spaces'), ['number' => number_format($user->plan->features->spaces, 0, __('.'), __(','))]) }}
                        @endif
                    </div>
                </div>

                <div class="col-12 col-md-6 p-2 d-flex align-items-center">
                    @if($user->plan->features->domains != 0)
                        @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                    @else
                        @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                    @endif

                    <div class="{{ ($user->plan->features->domains == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                        @if($user->plan->features->domains < 0)
                            {{ __('Unlimited domains') }}
                        @elseif($user->plan->features->domains)
                            <span class="text-muted">{{ $stats['domains'] }} /</span> {{ __(($user->plan->features->domains == 1 ? ':number domain' : ':number domains'), ['number' => number_format($user->plan->features->domains, 0, __('.'), __(','))]) }}
                        @endif
                    </div>
                </div>

                <div class="col-12 col-md-6 p-2 d-flex align-items-center">
                    @if($user->plan->features->pixels != 0)
                        @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                    @else
                        @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                    @endif

                    <div class="{{ ($user->plan->features->pixels == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                        @if($user->plan->features->pixels < 0)
                            {{ __('Unlimited pixels') }}
                        @elseif($user->plan->features->pixels)
                            <span class="text-muted">{{ $stats['pixels'] }} /</span> {{ __(($user->plan->features->pixels == 1 ? ':number pixel' : ':number pixels'), ['number' => number_format($user->plan->features->pixels, 0, __('.'), __(','))]) }}
                        @endif
                    </div>
                </div>

                @if(count($domains))
                    <div class="col-12 col-md-6 p-2 d-flex align-items-center">
                        @if($user->plan->features->global_domains)
                            @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                        @else
                            @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                        @endif

                        <div class="{{ ($user->plan->features->global_domains == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                            {{ __('Additional domains') }}
                        </div>

                        <div class="d-flex align-content-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}" data-tooltip="true" title="{{ __('Access to additional domains: :domains.', ['domains' => implode(', ', $domains)]) }}">@include('icons.info', ['class' => 'text-muted width-4 height-4 fill-current'])</div>
                    </div>
                @endif

                <div class="col-12 col-md-6 p-2 d-flex align-items-center">
                    @if($user->plan->features->link_stats)
                        @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                    @else
                        @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                    @endif

                    <div class="{{ ($user->plan->features->link_stats == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                        {{ __('Link stats') }}
                    </div>
                </div>

                <div class="col-12 col-md-6 p-2 d-flex align-items-center">
                    @if($user->plan->features->link_targeting)
                        @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                    @else
                        @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                    @endif

                    <div class="{{ ($user->plan->features->link_targeting == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                        {{ __('Link targeting') }}
                    </div>
                </div>

                <div class="col-12 col-md-6 p-2 d-flex align-items-center">
                    @if($user->plan->features->link_password)
                        @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                    @else
                        @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                    @endif

                    <div class="{{ ($user->plan->features->link_password == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                        {{ __('Link password') }}
                    </div>
                </div>

                <div class="col-12 col-md-6 p-2 d-flex align-items-center">
                    @if($user->plan->features->link_expiration)
                        @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                    @else
                        @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                    @endif

                    <div class="{{ ($user->plan->features->link_expiration == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                        {{ __('Link expiration') }}
                    </div>
                </div>

                <div class="col-12 col-md-6 p-2 d-flex align-items-center">
                    @if($user->plan->features->link_disabling)
                        @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                    @else
                        @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                    @endif

                    <div class="{{ ($user->plan->features->link_disabling == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                        {{ __('Link disabling') }}
                    </div>
                </div>

                <div class="col-12 col-md-6 p-2 d-flex align-items-center">
                    @if($user->plan->features->link_deep)
                        @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                    @else
                        @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                    @endif

                    <div class="{{ ($user->plan->features->link_deep == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                        {{ __('Deep linking') }}
                    </div>
                </div>

                <div class="col-12 col-md-6 p-2 d-flex align-items-center">
                    @if($user->plan->features->link_utm)
                        @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                    @else
                        @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                    @endif

                    <div class="{{ ($user->plan->features->link_utm == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                        {{ __('UTM builder') }}
                    </div>
                </div>

                <div class="col-12 col-md-6 p-2 d-flex align-items-center">
                    @if($user->plan->features->data_export)
                        @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                    @else
                        @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                    @endif

                    <div class="{{ ($user->plan->features->data_export == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                        {{ __('Data export') }}
                    </div>
                </div>

                <div class="col-12 col-md-6 p-2 d-flex align-items-center">
                    @if($user->plan->features->api)
                        @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                    @else
                        @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                    @endif

                    <div class="{{ ($user->plan->features->api == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                        {{ __('API') }}
                    </div>
                </div>
            </div>

            @if(paymentProcessors())
                @if($user->plan_recurring_at)
                    <button type="button" class="btn btn-outline-danger mt-3" data-toggle="modal" data-target="#modal" data-action="{{ route('account.plan') }}" data-button="btn btn-danger" data-title="{{ __('Cancel') }}" data-text="{{ __('You\'ll continue to have access to the features you\'ve paid for until the end of your billing cycle.') }}" data-sub-text="{{ __('Are you sure you want to cancel :name?', ['name' => $user->plan->name]) }}">{{ __('Cancel') }}</button>
                @endif
            @endif
        </form>
    </div>
</div>