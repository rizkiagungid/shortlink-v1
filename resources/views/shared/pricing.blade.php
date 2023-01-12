<div class="text-center mb-3 mt-5">
    <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-outline-dark active" id="plan-month">
            <input type="radio" name="options" autocomplete="off" checked>{{ __('Monthly') }}
        </label>
        <label class="btn btn-outline-dark" id="plan-year">
            <input type="radio" name="options" autocomplete="off">{{ __('Yearly') }}
        </label>
    </div>
</div>

<div class="row flex-column-reverse flex-md-row justify-content-center">
    @foreach($plans as $plan)
        <div class="col-12 col-md-4 pt-4">
            <div class="card border-0 shadow-sm rounded h-100 overflow-hidden plan">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="mb-3">
                        <div class="badge badge-pill badge-primary text-uppercase px-2 py-1">{{ $plan->name }}</div>
                    </div>

                    <div class="mb-4">
                        @if(!$plan->isDefault())
                            <div class="plan-preload plan-month d-none d-block">
                                <div class="h1 mb-0">
                                    <span class="font-weight-bold">
                                        {{ formatMoney($plan->amount_month, $plan->currency) }}
                                    </span>
                                    <span class="pricing-plan-price text-muted">
                                        {{ $plan->currency }}
                                    </span>
                                </div>
                                <span class="text-muted text-lowercase">{{ __('Month') }}</span>
                            </div>

                            <div class="plan-year d-none">
                                <div class="h1 mb-0">
                                    <span class="font-weight-bold">
                                        {{ formatMoney($plan->amount_year, $plan->currency) }}
                                    </span>
                                    <span class="pricing-plan-price text-muted">
                                        {{ $plan->currency }}
                                    </span>
                                </div>

                                <span class="text-muted text-lowercase">{{ __('Year') }}</span>

                                @if(($plan->amount_month * 12) > $plan->amount_year)
                                    <span class="badge badge-success">
                                        {{ __(':value% off', ['value' => number_format(((($plan->amount_month*12) - $plan->amount_year)/($plan->amount_month * 12) * 100), 0)]) }}
                                    </span>
                                @endif
                            </div>
                        @else
                            <div class="plan-preload plan-month d-none d-block">
                                <h1 class="mb-1">
                                    <span class="font-weight-bold text-uppercase">
                                        {{ __('Free') }}
                                    </span>
                                </h1>
                            </div>

                            <div class="plan-year d-none">
                                <h1 class="mb-1">
                                    <span class="font-weight-bold text-uppercase">
                                        {{ __('Free') }}
                                    </span>
                                </h1>
                            </div>

                            <div class="plan-month d-none d-block">
                                <span class="text-muted text-lowercase">{{ __('Month') }}</span>
                            </div>

                            <div class="plan-year d-none">
                                <span class="text-muted text-lowercase">{{ __('Year') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="row m-n2 pt-2">
                        <div class="col-12 p-2 d-flex align-items-center">
                            @if($plan->features->links != 0)
                                @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                            @else
                                @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                            @endif

                            <div class="{{ ($plan->features->links == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                                @if($plan->features->links < 0)
                                    {{ __('Unlimited links') }}
                                @elseif($plan->features->links)
                                    {{ __(($plan->features->links == 1 ? ':number link' : ':number links'), ['number' => number_format($plan->features->links, 0, __('.'), __(','))]) }}
                                @endif
                            </div>
                        </div>

                        <div class="col-12 p-2 d-flex align-items-center">
                            @if($plan->features->spaces != 0)
                                @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                            @else
                                @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                            @endif

                            <div class="{{ ($plan->features->spaces == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                                @if($plan->features->spaces < 0)
                                    {{ __('Unlimited spaces') }}
                                @elseif($plan->features->spaces)
                                    {{ __(($plan->features->spaces == 1 ? ':number space' : ':number spaces'), ['number' => number_format($plan->features->spaces, 0, __('.'), __(','))]) }}
                                @endif
                            </div>
                        </div>

                        <div class="col-12 p-2 d-flex align-items-center">
                            @if($plan->features->domains != 0)
                                @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                            @else
                                @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                            @endif

                            <div class="{{ ($plan->features->domains == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                                @if($plan->features->domains < 0)
                                    {{ __('Unlimited domains') }}
                                @elseif($plan->features->domains)
                                    {{ __(($plan->features->domains == 1 ? ':number domain' : ':number domains'), ['number' => number_format($plan->features->domains, 0, __('.'), __(','))]) }}
                                @endif
                            </div>
                        </div>

                        <div class="col-12 p-2 d-flex align-items-center">
                            @if($plan->features->pixels != 0)
                                @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                            @else
                                @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                            @endif

                            <div class="{{ ($plan->features->pixels == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                                @if($plan->features->pixels < 0)
                                    {{ __('Unlimited pixels') }}
                                @elseif($plan->features->pixels)
                                    {{ __(($plan->features->pixels == 1 ? ':number pixel' : ':number pixels'), ['number' => number_format($plan->features->pixels, 0, __('.'), __(','))]) }}
                                @endif
                            </div>
                        </div>

                        @if(count($domains))
                            <div class="col-12 p-2 d-flex align-items-center">
                                @if($plan->features->global_domains)
                                    @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                                @else
                                    @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                                @endif

                                <div class="{{ ($plan->features->global_domains == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                                    {{ __('Additional domains') }}
                                </div>

                                <div class="d-flex align-content-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}" data-tooltip="true" title="{{ __('Access to additional domains: :domains.', ['domains' => implode(', ', $domains)]) }}">@include('icons.info', ['class' => 'text-muted width-4 height-4 fill-current'])</div>
                            </div>
                        @endif

                        <div class="col-12 p-2 d-flex align-items-center">
                            @if($plan->features->link_stats)
                                @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                            @else
                                @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                            @endif

                            <div class="{{ ($plan->features->link_stats == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                                {{ __('Link stats') }}
                            </div>
                        </div>

                        <div class="col-12 p-2 d-flex align-items-center">
                            @if($plan->features->link_targeting)
                                @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                            @else
                                @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                            @endif

                            <div class="{{ ($plan->features->link_targeting == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                                {{ __('Link targeting') }}
                            </div>
                        </div>

                        <div class="col-12 p-2 d-flex align-items-center">
                            @if($plan->features->link_password)
                                @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                            @else
                                @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                            @endif

                            <div class="{{ ($plan->features->link_password == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                                {{ __('Link password') }}
                            </div>
                        </div>

                        <div class="col-12 p-2 d-flex align-items-center">
                            @if($plan->features->link_expiration)
                                @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                            @else
                                @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                            @endif

                            <div class="{{ ($plan->features->link_expiration == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                                {{ __('Link expiration') }}
                            </div>
                        </div>

                        <div class="col-12 p-2 d-flex align-items-center">
                            @if($plan->features->link_disabling)
                                @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                            @else
                                @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                            @endif

                            <div class="{{ ($plan->features->link_disabling == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                                {{ __('Link disabling') }}
                            </div>
                        </div>

                        <div class="col-12 p-2 d-flex align-items-center">
                            @if($plan->features->link_deep)
                                @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                            @else
                                @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                            @endif

                            <div class="{{ ($plan->features->link_deep == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                                {{ __('Deep linking') }}
                            </div>
                        </div>

                        <div class="col-12 p-2 d-flex align-items-center">
                            @if($plan->features->link_utm)
                                @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                            @else
                                @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                            @endif

                            <div class="{{ ($plan->features->link_utm == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                                {{ __('UTM builder') }}
                            </div>
                        </div>

                        <div class="col-12 p-2 d-flex align-items-center">
                            @if($plan->features->data_export)
                                @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                            @else
                                @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                            @endif

                            <div class="{{ ($plan->features->data_export == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                                {{ __('Data export') }}
                            </div>
                        </div>

                        <div class="col-12 p-2 d-flex align-items-center">
                            @if($plan->features->api)
                                @include('icons.checkmark', ['class' => 'flex-shrink-0 text-success fill-current width-4 height-4'])
                            @else
                                @include('icons.close', ['class' => 'flex-shrink-0 text-muted fill-current width-4 height-4'])
                            @endif

                            <div class="{{ ($plan->features->api == 0 ? 'text-muted' : '') }} {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-3') }}">
                                {{ __('API') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer border-0 pt-0 pr-4 pb-4 pl-4 d-flex">
                    <div class="z-1 w-100">
                        @auth
                            @if(!$plan->isDefault())
                                @if(Auth::user()->plan->id == $plan->id)
                                    <div class="btn btn-primary btn-block text-uppercase py-2 disabled">{{ __('Active') }}</div>
                                @else
                                    <div class="plan-no-animation plan-month d-none d-block">
                                        <a href="{{ route('checkout.index', ['id' => $plan->id, 'interval' => 'month']) }}" class="btn btn-primary btn-block text-uppercase py-2">
                                            @if($plan->trial_days > 0 && ! Auth::user()->plan_trial_ends_at)
                                                {{ __('Free trial') }}
                                            @else
                                                {{ __('Subscribe') }}
                                            @endif
                                        </a>
                                    </div>
                                    <div class="plan-no-animation plan-year d-none">
                                        <a href="{{ route('checkout.index', ['id' => $plan->id, 'interval' => 'year']) }}" class="btn btn-primary btn-block text-uppercase py-2">
                                            @if($plan->trial_days > 0 && ! Auth::user()->plan_trial_ends_at)
                                                {{ __('Free trial') }}
                                            @else
                                                {{ __('Subscribe') }}
                                            @endif
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="btn btn-primary btn-block text-uppercase py-2 disabled">{{ __('Free') }}</div>
                            @endif
                        @else
                            @if(config('settings.registration'))
                                <div class="plan-no-animation plan-month d-none d-block">
                                    <a href="{{ route('register', ['plan' => $plan->id, 'interval' => 'month']) }}" class="btn btn-primary btn-block text-uppercase py-2">{{ __('Register') }}</a>
                                </div>
                                <div class="plan-no-animation plan-year d-none">
                                    <a href="{{ route('register', ['plan' => $plan->id, 'interval' => 'year']) }}" class="btn btn-primary btn-block text-uppercase py-2">{{ __('Register') }}</a>
                                </div>
                            @else
                                <div class="plan-no-animation plan-month d-none d-block">
                                    <a href="{{ route('login', ['plan' => $plan->id, 'interval' => 'month']) }}" class="btn btn-primary btn-block text-uppercase py-2">{{ __('Login') }}</a>
                                </div>
                                <div class="plan-no-animation plan-year d-none">
                                    <a href="{{ route('login', ['plan' => $plan->id, 'interval' => 'year']) }}" class="btn btn-primary btn-block text-uppercase py-2">{{ __('Login') }}</a>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>