<?php
/**
 * ACF field group for Locations CPT (map markers).
 */
use StoutLogic\AcfBuilder\FieldsBuilder;

if (!function_exists('acf_add_local_field_group')) {
    return;
}

$locations = new FieldsBuilder('locations', [
    'title' => 'Location (Map Marker)',
]);

$locations
    ->setLocation('post_type', '==', 'locations')
    ->addTab('location_tab', ['label' => 'Location'])
    ->addNumber('latitude', [
        'label' => 'Latitude',
        'instructions' => 'e.g. 53.349805',
        'required' => 1,
        'step' => 'any',
        'min' => -90,
        'max' => 90,
    ])
    ->addNumber('longitude', [
        'label' => 'Longitude',
        'instructions' => 'e.g. -6.26031',
        'required' => 1,
        'step' => 'any',
        'min' => -180,
        'max' => 180,
    ])
    ->addText('address', [
        'label' => 'Address',
        'instructions' => 'Shown in map popup (optional).',
    ])
    ->addUrl('url', [
        'label' => 'Link URL',
        'instructions' => 'Optional link from popup (e.g. location page).',
    ])
    ->addText('link_label', [
        'label' => 'Link label',
        'instructions' => 'e.g. "View details". Leave empty to use URL.',
        'default_value' => 'View details',
    ]);

acf_add_local_field_group($locations->build());
