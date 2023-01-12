@php
    $parameters = [
        [
            'name' => 'name',
            'type' => 1,
            'format' => 'string',
            'description' => __('The name of the statistic.') . ' ' . __('Possible values are: :values.', ['values' => '<code>'.implode('</code>, <code>', config('stats.types')).'</code>'])
        ], [
            'name' => 'from',
            'type' => 1,
            'format' => 'string',
            'description' => __('The starting date in :format format.', ['format' => '<code>Y-m-d</code>'])
        ], [
            'name' => 'to',
            'type' => 1,
            'format' => 'string',
            'description' => __('The ending date in :format format.', ['format' => '<code>Y-m-d</code>'])
        ], [
            'name' => 'search',
            'type' => 0,
            'format' => 'string',
            'description' => __('The search query.')
        ], [
            'name' => 'search_by',
            'type' => 0,
            'format' => 'string',
            'description' => __('Search by') . '. ' . __('Possible values are: :values.', [
                'values' => implode(', ', [
                    __(':value for :name', ['value' => '<code>value</code>', 'name' => '<span class="font-weight-medium">'.__('Value').'</span>'])
                    ])
                ]) .' ' . __('Defaults to: :value.', ['value' => '<code>url</code>'])
        ], [
            'name' => 'sort_by',
            'type' => 0,
            'format' => 'string',
            'description' => __('Sort by') . '. ' . __('Possible values are: :values.', [
                'values' => implode(', ', [
                    __(':value for :name', ['value' => '<code>count</code>', 'name' => '<span class="font-weight-medium">'.__('Count').'</span>']),
                    __(':value for :name', ['value' => '<code>value</code>', 'name' => '<span class="font-weight-medium">'.__('Value').'</span>']),
                    ])
                ]) .' ' . __('Defaults to: :value.', ['value' => '<code>count</code>'])
        ], [
            'name' => 'sort',
            'type' => 0,
            'format' => 'string',
            'description' => __('Sort') . '. ' . __('Possible values are: :values.', [
                'values' => implode(', ', [
                    __(':value for :name', ['value' => '<code>desc</code>', 'name' => '<span class="font-weight-medium">'.__('Descending').'</span>']),
                    __(':value for :name', ['value' => '<code>asc</code>', 'name' => '<span class="font-weight-medium">'.__('Ascending').'</span>'])
                    ])
                ]) .' ' . __('Defaults to: :value.', ['value' => '<code>desc</code>'])
        ], [
            'name' => 'per_page',
            'type' => 0,
            'format' => 'int',
            'description' => __('Results per page') . '. '. __('Possible values are: :values.', [
                'values' => '<code>' . implode('</code>, <code>', [10, 25, 50, 100]) . '</code>'
                ]) .' ' . __('Defaults to: :value.', ['value' => '<code>'.config('settings.paginate').'</code>'])
        ]
    ];
@endphp

@include('developers.parameters')