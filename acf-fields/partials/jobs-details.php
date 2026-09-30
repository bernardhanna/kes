<?php
/**
 * Structured job details (CPT: jobs). All fields optional.
 * Used by the Careers jobs flexi card expand panel.
 */
use StoutLogic\AcfBuilder\FieldsBuilder;

if (! function_exists('acf_add_local_field_group')) {
    return;
}

$jobs_details = new FieldsBuilder('jobs_details', [
    'title'    => 'Job details',
    'position' => 'normal',
    'style'    => 'default',
]);

$jobs_details
    ->setLocation('post_type', '==', 'jobs')

    ->addTab('meta', [
        'label' => 'Meta',
    ])
    ->addText('job_location', [
        'label'        => 'Location',
        'instructions' => 'Optional. e.g. Data Hall Construction Sites (Netherlands, Germany, …)',
        'required'     => 0,
    ])
    ->addText('job_salary', [
        'label'        => 'Salary',
        'instructions' => 'Optional. e.g. £70,000–£80,000 per year, plus daily allowance',
        'required'     => 0,
    ])
    ->addText('job_schedule', [
        'label'        => 'Schedule (rotation)',
        'instructions' => 'Optional. e.g. 3-week rotations',
        'required'     => 0,
    ])
    ->addText('job_working_days', [
        'label'        => 'Working days',
        'instructions' => 'Optional. e.g. Monday to Friday',
        'required'     => 0,
    ])
    ->addText('job_application_deadline', [
        'label'    => 'Application deadline',
        'required' => 0,
    ])
    ->addText('job_additional_pay', [
        'label'        => 'Additional pay',
        'instructions' => 'Optional. e.g. Overtime pay',
        'required'     => 0,
    ])
    ->addText('job_type', [
        'label'        => 'Job type',
        'instructions' => 'Optional. e.g. Full-time, Permanent',
        'required'     => 0,
    ])
    ->addText('job_licence', [
        'label'        => 'Licence / certification',
        'instructions' => 'Optional. e.g. Driving License (preferred)',
        'required'     => 0,
    ])
    ->addText('job_work_location', [
        'label'        => 'Work location',
        'instructions' => 'Optional. e.g. On the road / In person',
        'required'     => 0,
    ])
    ->addTextarea('job_application_question', [
        'label'        => 'Application question',
        'instructions' => 'Optional question shown in the job description.',
        'rows'         => 2,
        'new_lines'    => '',
        'required'     => 0,
    ])

    ->addTab('overview', [
        'label' => 'Overview & responsibilities',
    ])
    ->addTextarea('job_role_overview', [
        'label'        => 'Role overview',
        'instructions' => 'Optional introductory paragraph.',
        'rows'         => 5,
        'new_lines'    => 'wpautop',
        'required'     => 0,
    ])
    ->addRepeater('job_responsibilities', [
        'label'        => 'Key responsibilities',
        'instructions' => 'Optional. One responsibility per row.',
        'layout'       => 'table',
        'button_label' => 'Add responsibility',
        'required'     => 0,
    ])
        ->addTextarea('item', [
            'label'     => 'Responsibility',
            'rows'      => 2,
            'new_lines' => '',
            'required'  => 0,
        ])
    ->endRepeater()

    ->addTab('requirements', [
        'label' => 'Requirements',
    ])
    ->addRepeater('job_requirements', [
        'label'        => 'Requirements',
        'instructions' => 'Optional. Label on its own line; body text underneath on the front end.',
        'layout'       => 'block',
        'button_label' => 'Add requirement',
        'required'     => 0,
    ])
        ->addText('label', [
            'label'    => 'Label',
            'required' => 0,
        ])
        ->addTextarea('text', [
            'label'     => 'Text',
            'rows'      => 4,
            'new_lines' => 'wpautop',
            'required'  => 0,
        ])
    ->endRepeater()

    ->addTab('offer', [
        'label' => 'What we offer',
    ])
    ->addRepeater('job_offers', [
        'label'        => 'What we offer',
        'instructions' => 'Optional. Label on its own line; body text underneath on the front end.',
        'layout'       => 'block',
        'button_label' => 'Add offer item',
        'required'     => 0,
    ])
        ->addText('label', [
            'label'    => 'Label',
            'required' => 0,
        ])
        ->addTextarea('text', [
            'label'     => 'Text',
            'rows'      => 3,
            'new_lines' => 'wpautop',
            'required'  => 0,
        ])
    ->endRepeater();

acf_add_local_field_group($jobs_details->build());
