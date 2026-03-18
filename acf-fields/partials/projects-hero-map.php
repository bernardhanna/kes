<?php
/**
 * Map coordinates on Projects — pins on the hero slider map slide.
 */
use StoutLogic\AcfBuilder\FieldsBuilder;

if (! function_exists('acf_add_local_field_group')) {
    return;
}

$projects_hero_map = new FieldsBuilder('projects_hero_map', [
    'title' => 'Hero map',
    'position' => 'side',
]);

$projects_hero_map
    ->setLocation('post_type', '==', 'projects')
    ->addNumber('map_latitude', [
        'label'         => 'Latitude',
        'instructions'  => 'Hero slider map: set latitude and longitude on this project to show a map pin. Both are required; projects missing either are skipped. Example: 53.349805',
        'step'          => 'any',
        'min'           => -90,
        'max'           => 90,
    ])
    ->addNumber('map_longitude', [
        'label'         => 'Longitude',
        'instructions'  => 'Pair with latitude. Example: -6.26031',
        'step'          => 'any',
        'min'           => -180,
        'max'           => 180,
    ])
    ->addText('project_location', [
        'label'         => 'Project location',
        'instructions'  => 'Shown on project cards and used as map marker label (e.g. Dublin).',
    ]);

acf_add_local_field_group($projects_hero_map->build());
