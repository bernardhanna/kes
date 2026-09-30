<?php
/**
 * Per-job application page overrides.
 */
use StoutLogic\AcfBuilder\FieldsBuilder;

if (! function_exists('acf_add_local_field_group')) {
    return;
}

$jobs_application = new FieldsBuilder('jobs_application', [
    'title'    => 'Application page',
    'position' => 'side',
]);

$jobs_application
    ->setLocation('post_type', '==', 'jobs')
    ->addTextarea('application_intro', [
        'label'         => 'Intro text',
        'instructions'  => 'Shown under the page title. Leave empty to use Theme Options → Careers default or the job excerpt.',
        'rows'          => 4,
        'new_lines'     => 'br',
    ])
    ->addImage('application_image', [
        'label'         => 'Side image',
        'instructions'  => 'Optional override beside the form. If empty, the job featured image is used, then Theme Options → Careers default.',
        'return_format' => 'id',
        'preview_size'  => 'medium',
    ]);

acf_add_local_field_group($jobs_application->build());
