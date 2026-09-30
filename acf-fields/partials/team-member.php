<?php
/**
 * Per team member fields (CPT: team).
 */
use StoutLogic\AcfBuilder\FieldsBuilder;

if (! function_exists('acf_add_local_field_group')) {
    return;
}

$team_member = new FieldsBuilder('team_member', [
    'title' => 'Team member details',
]);

$team_member
    ->setLocation('post_type', '==', 'team')
    ->addText('job_title', [
        'label'        => 'Job title',
        'instructions' => 'Role or position shown on team cards.',
        'required'     => 1,
    ])
    ->addWysiwyg('bio', [
        'label'        => 'Bio',
        'instructions' => 'Short biography shown on team cards.',
        'media_upload' => 0,
        'tabs'         => 'visual,text',
        'toolbar'      => 'basic',
        'required'     => 1,
    ]);

acf_add_local_field_group($team_member->build());
