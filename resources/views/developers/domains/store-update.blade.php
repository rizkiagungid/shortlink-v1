@php
    if($type) {
        $parameters[] = [
            'name' => 'name',
            'type' => $type,
            'format' => 'string',
            'description' => __('The domain name.')
        ];
    }

    $parameters[] = [
        'name' => 'index_page',
        'type' => 0,
        'format' => 'string',
        'description' => __('The index page to redirect to.')
    ];

    $parameters[] = [
        'name' => 'not_found_page',
        'type' => 0,
        'format' => 'string',
        'description' => __('The 404 page to redirect to.')
    ];
@endphp

@include('developers.parameters')