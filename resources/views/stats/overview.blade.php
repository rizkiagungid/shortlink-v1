@section('site_title', formatTitle([$link->alias, __('Overview'), __('Stats'), config('settings.title')]))

@if($link->user->can('stats', ['App\Models\Link']) || (Auth::check() && Auth::user()->role == 1))
    <div class="card border-0 rounded-top shadow-sm mb-3 overflow-hidden" id="trend-chart-container">
        <div class="px-3 border-bottom">
            <div class="row">
                <!-- Title -->
                <div class="col-12 col-md-auto d-none d-xl-flex align-items-center border-bottom border-bottom-md-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-md' : 'border-right-md') }}">
                    <div class="px-2 py-4 d-flex">
                        <div class="d-flex position-relative text-primary width-10 height-10 align-items-center justify-content-center flex-shrink-0">
                            <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-xl"></div>
                            @include('icons.assesment', ['class' => 'fill-current width-5 height-5'])
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md">
                    <div class="row">
                        <!-- Clicks -->
                        <div class="col-12 col-lg-4 border-bottom border-bottom-lg-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-lg' : 'border-right-lg')  }}">
                            <div class="px-2 py-4">
                                <div class="d-flex">
                                    <div class="text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                        <div class="d-flex align-items-center text-truncate">
                                            <div class="d-flex align-items-center justify-content-center bg-primary rounded width-4 height-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}" id="clicks-legend"></div>

                                            <div class="flex-grow-1 d-flex font-weight-bold text-truncate">
                                                <div class="text-truncate">{{ __('Clicks') }}</div>
                                                <div class="flex-shrink-0 d-flex align-items-center mx-2" data-tooltip="true" title="{{ __('The total number of clicks for the current dataset.') }}">
                                                    @include('icons.info', ['class' => 'width-4 height-4 fill-current text-muted'])
                                                </div>
                                            </div>
                                        </div>

                                        @include('stats.growth', ['growthCurrent' => $totalClicks, 'growthPrevious' => $totalClicksOld])
                                    </div>

                                    <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }}">
                                        <div class="h2 font-weight-bold mb-0">{{ number_format($totalClicks, 0, __('.'), __(',')) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Most -->
                        <div class="col-12 col-lg-4 border-bottom border-bottom-lg-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-lg' : 'border-right-lg')  }}">
                            <div class="px-2 py-4">
                                <div class="row">
                                    <div class="col">
                                        <div class="d-flex align-items-center text-truncate">
                                            @if(max($clicksMap) > 0)
                                                <div class="flex-grow-1 font-weight-bold text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                    @if($range['unit'] == 'hour')
                                                        {{ \Carbon\Carbon::createFromFormat('H', array_search(max($clicksMap), $clicksMap))->format('H:i') }}
                                                    @elseif($range['unit'] == 'day')
                                                        {{ \Carbon\Carbon::parse(array_search(max($clicksMap), $clicksMap))->format(__('Y-m-d')) }}
                                                    @elseif($range['unit'] == 'month')
                                                        {{ \Carbon\Carbon::parse(array_search(max($clicksMap), $clicksMap))->format(__('Y-m')) }}
                                                    @else
                                                        {{ array_search(max($clicksMap), $clicksMap) }}
                                                    @endif
                                                </div>
                                            @else
                                                <div class="flex-grow-1 font-weight-bold text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">{{ __('No data') }}</div>
                                            @endif
                                            <div class="align-self-end">
                                                @if(max($clicksMap) > 0)
                                                    <div class="d-flex align-items-center justify-content-end {{ (__('lang_dir') == 'rtl' ? 'mr-3' : 'ml-3') }}">
                                                        {{ number_format(max($clicksMap), 0, __('.'), __(',')) }}
                                                    </div>
                                                @else
                                                    —
                                                @endif
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center text-truncate text-success">
                                            <div class="d-flex align-items-center justify-content-center width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">@include('icons.trending-up', ['class' => 'fill-current width-3 height-3'])</div>

                                            <div class="flex-grow-1 text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">{{ mb_strtolower(__('Most popular')) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Least -->
                        <div class="col-12 col-lg-4">
                            <div class="px-2 py-4">
                                <div class="row">
                                    <div class="col">
                                        <div class="d-flex align-items-center text-truncate">
                                            <div class="flex-grow-1 font-weight-bold text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                @if($range['unit'] == 'hour')
                                                    {{ \Carbon\Carbon::createFromFormat('H', array_search(min($clicksMap), $clicksMap))->format('H:i') }}
                                                @elseif($range['unit'] == 'day')
                                                    {{ \Carbon\Carbon::parse(array_search(min($clicksMap), $clicksMap))->format(__('Y-m-d')) }}
                                                @elseif($range['unit'] == 'month')
                                                    {{ \Carbon\Carbon::parse(array_search(min($clicksMap), $clicksMap))->format(__('Y-m')) }}
                                                @else
                                                    {{ array_search(min($clicksMap), $clicksMap) }}
                                                @endif
                                            </div>
                                            <div class="align-self-end">
                                                <div class="d-flex align-items-center justify-content-end {{ (__('lang_dir') == 'rtl' ? 'mr-3' : 'ml-3') }}">
                                                    {{ number_format(min($clicksMap), 0, __('.'), __(',')) }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center text-truncate text-danger">
                                            <div class="d-flex align-items-center justify-content-center width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">@include('icons.trending-down', ['class' => 'fill-current width-3 height-3'])</div>

                                            <div class="flex-grow-1 text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">{{ mb_strtolower(__('Least popular')) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div style="height: 230px">
                <canvas id="trendChart"></canvas>
            </div>
            <script>
                'use strict';

                window.addEventListener("DOMContentLoaded", function () {
                    Chart.defaults.font = {
                        family: "Inter, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'",
                        size: 12
                    };

                    const phBgColor = window.getComputedStyle(document.getElementById('trend-chart-container')).getPropertyValue('background-color');
                    const clicksColor = window.getComputedStyle(document.getElementById('clicks-legend')).getPropertyValue('background-color');

                    const ctx = document.querySelector('#trendChart').getContext('2d');
                    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, clicksColor.replace('rgb', 'rgba').replace(')', ', 0.35)'));
                    gradient.addColorStop(1, clicksColor.replace('rgb', 'rgba').replace(')', ', 0.01)'));

                    let tooltipTitles = [
                        @foreach($clicksMap as $date => $value)
                            @if($range['unit'] == 'hour')
                                '{{ \Carbon\Carbon::createFromFormat('H', $date)->format(__('H:i')) }}',
                            @elseif($range['unit'] == 'day')
                                '{{ \Carbon\Carbon::parse($date)->format(__('Y-m-d')) }}',
                            @elseif($range['unit'] == 'month')
                                '{{ \Carbon\Carbon::parse($date)->format(__('Y-m')) }}',
                            @else
                                '{{ $date }}',
                            @endif
                        @endforeach
                    ];

                    const lineOptions = {
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        hitRadius: 5,
                        pointHoverBorderWidth: 3,
                        lineTension: 0,
                    }

                    let trendChart = new Chart(ctx, {
                        type: 'line',

                        data: {
                            labels: [
                                @foreach($clicksMap as $date => $value)
                                        @if($range['unit'] == 'hour')
                                    '{{ \Carbon\Carbon::createFromFormat('H', $date)->format(__('H:i')) }}',
                                @elseif($range['unit'] == 'day')
                                    '{{ __(':month :day', ['month' => mb_substr(__(\Carbon\Carbon::parse($date)->format('F')), 0, 3), 'day' => __(\Carbon\Carbon::parse($date)->format('j'))]) }}',
                                @elseif($range['unit'] == 'month')
                                    '{{ __(':year :month', ['year' => \Carbon\Carbon::parse($date)->format('Y'), 'month' => mb_substr(__(\Carbon\Carbon::parse($date)->format('F')), 0, 3)]) }}',
                                @else
                                    '{{ $date }}',
                                @endif
                                @endforeach
                            ],
                            datasets: [{
                                label: '{{ __('Clicks') }}',
                                data: [
                                    @foreach($clicksMap as $date => $value)
                                    {{ $value }},
                                    @endforeach
                                ],
                                fill: true,
                                backgroundColor: gradient,
                                borderColor: clicksColor,
                                pointBorderColor: clicksColor,
                                pointBackgroundColor: clicksColor,
                                pointHoverBackgroundColor: phBgColor,
                                pointHoverBorderColor: clicksColor,
                                ...lineOptions
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false
                            },
                            plugins: {
                                legend: {
                                    rtl: {{ (__('lang_dir') == 'rtl' ? 'true' : 'false') }},
                                    display: false
                                },
                                tooltip: {
                                    rtl: {{ (__('lang_dir') == 'rtl' ? 'true' : 'false') }},
                                    mode: 'index',
                                    intersect: false,
                                    reverse: true,

                                    padding: {
                                        top: 14,
                                        right: 16,
                                        bottom: 16,
                                        left: 16
                                    },

                                    backgroundColor: '{{ (config('settings.dark_mode') == 1 ? '#FFF' : '#000') }}',

                                    titleColor: '{{ (config('settings.dark_mode') == 1 ? '#000' : '#FFF') }}',
                                    titleMarginBottom: 7,
                                    titleFont: {
                                        size: 16,
                                        weight: 'normal'
                                    },

                                    bodyColor: '{{ (config('settings.dark_mode') == 1 ? '#000' : '#FFF') }}',
                                    bodySpacing: 7,
                                    bodyFont: {
                                        size: 14
                                    },

                                    footerMarginTop: 10,
                                    footerFont: {
                                        size: 12,
                                        weight: 'normal'
                                    },

                                    cornerRadius: 4,
                                    caretSize: 7,

                                    boxPadding: 4,

                                    callbacks: {
                                        label: function (tooltipItem) {
                                            return ' ' + tooltipItem.dataset.label + ': ' + parseFloat(tooltipItem.dataset.data[tooltipItem.dataIndex]).format(0, 3, '{{ __(',') }}').toString();
                                        },
                                        title: function (tooltipItem) {
                                            return tooltipTitles[tooltipItem[0].dataIndex];
                                        }
                                    }
                                },
                            },
                            scales: {
                                x: {
                                    display: true,
                                    grid: {
                                        lineWidth: 0,
                                        tickLength: 0
                                    },
                                    ticks: {
                                        maxTicksLimit: @if($range['unit'] == 'day') 12 @else 15 @endif,
                                        padding: 10,
                                    }
                                },
                                y: {
                                    display: true,
                                    beginAtZero: true,
                                    grid: {
                                        tickLength: 0
                                    },
                                    ticks: {
                                        maxTicksLimit: 8,
                                        padding: 10,
                                        callback: function (value) {
                                            return commarize(value, 1000);
                                        }
                                    }
                                },
                            }
                        }
                    });

                    // The time to wait before attempting to change the colors on first attempt
                    let colorSchemeTimer = 500;

                    // Update the chart colors when the color scheme changes
                    const observer = new MutationObserver(function (mutationsList, observer) {
                        for (const mutation of mutationsList) {
                            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                                setTimeout(function () {
                                    const phBgColor = window.getComputedStyle(document.getElementById('trend-chart-container')).getPropertyValue('background-color');
                                    const clicksColor = window.getComputedStyle(document.getElementById('clicks-legend')).getPropertyValue('background-color');

                                    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                                    gradient.addColorStop(0, clicksColor.replace('rgb', 'rgba').replace(')', ', 0.35)'));
                                    gradient.addColorStop(1, clicksColor.replace('rgb', 'rgba').replace(')', ', 0.01)'));

                                    trendChart.data.datasets[0].backgroundColor = gradient;
                                    trendChart.data.datasets[0].borderColor = clicksColor;
                                    trendChart.data.datasets[0].pointBorderColor = clicksColor;
                                    trendChart.data.datasets[0].pointBackgroundColor = clicksColor;
                                    trendChart.data.datasets[0].pointHoverBackgroundColor = phBgColor;
                                    trendChart.data.datasets[0].pointHoverBorderColor = clicksColor;

                                    trendChart.options.plugins.tooltip.backgroundColor = (document.querySelector('html').classList.contains('dark') == 0 ? '#000' : '#FFF');
                                    trendChart.options.plugins.tooltip.titleColor = (document.querySelector('html').classList.contains('dark') == 0 ? '#FFF' : '#000');
                                    trendChart.options.plugins.tooltip.bodyColor = (document.querySelector('html').classList.contains('dark') == 0 ? '#FFF' : '#000');
                                    trendChart.update();

                                    // Update the color scheme timer to be faster next time it's used
                                    colorSchemeTimer = 100;
                                }, colorSchemeTimer);
                            }
                        }
                    });

                    observer.observe(document.querySelector('html'), { attributes: true });
                });
            </script>
        </div>
    </div>

    <div class="row m-n2">
        <div class="col-12 col-lg-6 p-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md"><div class="font-weight-medium py-1">{{ __('Referrers') }}</div></div>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($referrers) == 0)
                        {{ __('No data') }}.
                    @else
                        <div class="list-group list-group-flush my-n3">
                            <div class="list-group-item px-0 text-muted">
                                <div class="row align-items-center">
                                    <div class="col">
                                        {{ __('Website') }}
                                    </div>
                                    <div class="col-auto">
                                        {{ __('Clicks') }}
                                    </div>
                                </div>
                            </div>

                            @foreach($referrers as $referrer)
                                <div class="list-group-item px-0 border-0">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="d-flex text-truncate align-items-center">
                                                @if($referrer->value)
                                                    <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                        <img src="https://icons.duckduckgo.com/ip3/{{ $referrer->value }}.ico" rel="noreferrer" class="width-4 height-4">
                                                    </div>

                                                    <div class="d-flex text-truncate">
                                                        <div class="text-truncate" dir="ltr">{{ $referrer->value }}</div> <a href="http://{{ $referrer->value }}" target="_blank" rel="nofollow noreferrer noopener" class="text-secondary d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">@include('icons.open-in-new', ['class' => 'fill-current width-3 height-3'])</a>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                        <img src="{{ asset('/images/icons/referrers/unknown.svg') }}" rel="noreferrer" class="width-4 height-4">
                                                    </div>

                                                    <div class="text-truncate">
                                                        {{ __('Direct, Email, SMS') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="d-flex align-items-baseline {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                                <div>
                                                    {{ number_format($referrer->count, 0, __('.'), __(',')) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress height-1.25 w-100">
                                            <div class="progress-bar rounded" role="progressbar" style="width: {{ (($referrer->count / $totalReferrers) * 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if(count($referrers) > 0)
                    <div class="card-footer bg-base-2 border-0">
                        <a href="{{ route('stats.referrers', ['id' => $link->id, 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('View all') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-12 col-lg-6 p-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md"><div class="font-weight-medium py-1">{{ __('Countries') }}</div></div>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($countries) == 0)
                        {{ __('No data') }}.
                    @else
                        <div class="list-group list-group-flush my-n3">
                            <div class="list-group-item px-0 text-muted">
                                <div class="row align-items-center">
                                    <div class="col">
                                        {{ __('Name') }}
                                    </div>
                                    <div class="col-auto">
                                        {{ __('Clicks') }}
                                    </div>
                                </div>
                            </div>

                            @foreach($countries as $country)
                                <div class="list-group-item px-0 border-0">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="d-flex text-truncate align-items-center">
                                                <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"><img src="{{ asset('/images/icons/countries/'. formatFlag($country->value)) }}.svg" class="width-4 height-4"></div>
                                                <div class="text-truncate">
                                                    @if(!empty(explode(':', $country->value)[1]))
                                                        <a href="{{ route('stats.cities', ['id' => $link->id, 'search' => explode(':', $country->value)[0].':', 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-body" data-tooltip="true" title="{{ __(explode(':', $country->value)[1]) }}">{{ explode(':', $country->value)[1] }}</a>
                                                    @else
                                                        {{ __('Unknown') }}
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-baseline {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                                <div>
                                                    {{ number_format($country->count, 0, __('.'), __(',')) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress height-1.25 w-100">
                                            <div class="progress-bar rounded" role="progressbar" style="width: {{ (($country->count / $totalClicks) * 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if(count($countries) > 0)
                    <div class="card-footer bg-base-2 border-0">
                        <a href="{{ route('stats.countries', ['id' => $link->id, 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('View all') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-12 col-lg-6 p-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md"><div class="font-weight-medium py-1">{{ __('Browsers') }}</div></div>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($browsers) == 0)
                        {{ __('No data') }}.
                    @else
                        <div class="list-group list-group-flush my-n3">
                            <div class="list-group-item px-0 text-muted">
                                <div class="row align-items-center">
                                    <div class="col">
                                        {{ __('Name') }}
                                    </div>
                                    <div class="col-auto">
                                        {{ __('Clicks') }}
                                    </div>
                                </div>
                            </div>

                            @foreach($browsers as $browser)
                                <div class="list-group-item px-0 border-0">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="d-flex text-truncate align-items-center">
                                                <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"><img src="{{ asset('/images/icons/browsers/'.formatBrowser($browser->value)) }}.svg" class="width-4 height-4"></div>
                                                <div class="text-truncate">
                                                    @if($browser->value)
                                                        {{ $browser->value }}
                                                    @else
                                                        {{ __('Unknown') }}
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-baseline {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                                <div>
                                                    {{ number_format($browser->count, 0, __('.'), __(',')) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress height-1.25 w-100">
                                            <div class="progress-bar rounded" role="progressbar" style="width: {{ (($browser->count / $totalClicks) * 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if(count($browsers) > 0)
                    <div class="card-footer bg-base-2 border-0">
                        <a href="{{ route('stats.browsers', ['id' => $link->id, 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('View all') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-12 col-lg-6 p-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md"><div class="font-weight-medium py-1">{{ __('Platforms') }}</div></div>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($platforms) == 0)
                        {{ __('No data') }}.
                    @else
                        <div class="list-group list-group-flush my-n3">
                            <div class="list-group-item px-0 text-muted">
                                <div class="row align-items-center">
                                    <div class="col">
                                        {{ __('Name') }}
                                    </div>
                                    <div class="col-auto">
                                        {{ __('Clicks') }}
                                    </div>
                                </div>
                            </div>

                            @foreach($platforms as $platform)
                                <div class="list-group-item px-0 border-0">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="d-flex text-truncate align-items-center">
                                                <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"><img src="{{ asset('/images/icons/platforms/'.formatPlatform($platform->value)) }}.svg" class="width-4 height-4"></div>
                                                <div class="text-truncate">
                                                    @if($platform->value)
                                                        {{ $platform->value }}
                                                    @else
                                                        {{ __('Unknown') }}
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-baseline {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                                <div>
                                                    {{ number_format($platform->count, 0, __('.'), __(',')) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress height-1.25 w-100">
                                            <div class="progress-bar rounded" role="progressbar" style="width: {{ (($platform->count / $totalClicks) * 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if(count($platforms) > 0)
                    <div class="card-footer bg-base-2 border-0">
                        <a href="{{ route('stats.platforms', ['id' => $link->id, 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('View all') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="d-flex flex-column">
        <div class="card border-0 shadow-sm">
            @if(paymentProcessors())
                @if(Auth::check() && $link->user->id == Auth::user()->id)
                    @include('shared.features.locked')
                @else
                    @include('shared.features.unavailable')
                @endif
            @else
                @include('shared.features.unavailable')
            @endif
        </div>
    </div>
@endif