<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

$team_001 = new FieldsBuilder('team_001', [
    'label' => 'Team Section',
]);

$team_001
    ->addTab('Content', ['label' => 'Content'])
    ->addSelect('team_source', [
        'label'         => 'How team members are chosen',
        'instructions'  => 'All team members loads every published team post (you can hide individuals below). Selected lets you pick individuals (search by name). Manual uses only the repeater below.',
        'choices'       => [
            'all'      => 'All team members — from CPT',
            'selected' => 'Selected — pick from CPT',
            'manual'   => 'Manual — repeater below',
        ],
        'default_value' => 'all',
    ])
    ->addTaxonomy('all_team_category', [
        'label'             => 'Filter by category (optional)',
        'instructions'      => 'Leave empty to show all categories.',
        'taxonomy'          => 'team_category',
        'field_type'        => 'select',
        'allow_null'        => 1,
        'add_term'          => 0,
        'save_terms'        => 0,
        'load_terms'        => 0,
        'return_format'     => 'id',
        'conditional_logic' => [[[
            'field'    => 'team_source',
            'operator' => '==',
            'value'    => 'all',
        ]]],
    ])
    ->addSelect('all_orderby', [
        'label'             => 'Order by',
        'choices'           => [
            'menu_order' => 'Menu order',
            'title'      => 'Name',
            'date'       => 'Date published',
        ],
        'default_value'     => 'menu_order',
        'conditional_logic' => [[[
            'field'    => 'team_source',
            'operator' => '==',
            'value'    => 'all',
        ]]],
    ])
    ->addSelect('all_order', [
        'label'             => 'Sort direction',
        'choices'           => [
            'ASC'  => 'Ascending',
            'DESC' => 'Descending',
        ],
        'default_value'     => 'ASC',
        'conditional_logic' => [[[
            'field'    => 'team_source',
            'operator' => '==',
            'value'    => 'all',
        ]]],
    ])
    ->addRelationship('excluded_team', [
        'label'             => 'Hide team members (optional)',
        'instructions'      => 'When using “All team members”, pick anyone who should not appear in this section.',
        'post_type'         => ['team'],
        'filters'           => ['search', 'taxonomy'],
        'taxonomy'          => ['team_category'],
        'return_format'     => 'object',
        'max'               => 48,
        'conditional_logic' => [[[
            'field'    => 'team_source',
            'operator' => '==',
            'value'    => 'all',
        ]]],
    ])
    ->addRelationship('selected_team', [
        'label'             => 'Team members to show',
        'instructions'      => 'Search by name, then drag to reorder cards on the page.',
        'post_type'         => ['team'],
        'filters'           => ['search', 'taxonomy'],
        'taxonomy'          => ['team_category'],
        'return_format'     => 'object',
        'max'               => 48,
        'conditional_logic' => [[[
            'field'    => 'team_source',
            'operator' => '==',
            'value'    => 'selected',
        ]]],
    ])
    ->addRepeater('team_members', [
        'label'             => 'Team Members',
        'instructions'      => 'Add team members to display in the section.',
        'button_label'      => 'Add Team Member',
        'layout'            => 'block',
        'conditional_logic' => [[[
            'field'    => 'team_source',
            'operator' => '==',
            'value'    => 'manual',
        ]]],
    ])
        ->addImage('image', [
            'label'         => 'Team Member Photo',
            'instructions'  => 'Upload a photo of the team member.',
            'return_format' => 'id',
            'preview_size'  => 'medium',
            'required'      => 1,
        ])
        ->addText('name', [
            'label'         => 'Name',
            'instructions'  => 'Enter the team member\'s full name.',
            'required'      => 1,
            'default_value' => 'Team Member Name',
        ])
        ->addText('job_title', [
            'label'         => 'Job Title',
            'instructions'  => 'Enter the team member\'s job title or position.',
            'required'      => 1,
            'default_value' => 'Job Title',
        ])
        ->addWysiwyg('description', [
            'label'         => 'Description',
            'instructions'  => 'Add a brief description or bio of the team member.',
            'required'      => 1,
            'default_value' => 'Team member description and background information.',
            'media_upload'  => 0,
            'tabs'          => 'visual,text',
            'toolbar'       => 'basic',
        ])
    ->endRepeater()
    ->addLink('button', [
        'label'         => 'Call to Action Button',
        'instructions'  => 'Add a button link to view more team members or related content.',
        'return_format' => 'array',
        'default_value' => [
            'title'  => 'View our people',
            'url'    => '#',
            'target' => '_self',
        ],
    ])
    ->addTab('Design', ['label' => 'Design'])
    ->addColorPicker('background_color', [
        'label'         => 'Background Color',
        'instructions'  => 'Choose the background color for the team section.',
        'default_value' => '#ffffff',
    ])
    ->addTab('Layout', ['label' => 'Layout'])
    ->addRepeater('padding_settings', [
        'label'         => 'Padding Settings',
        'instructions'  => 'Customize padding for different screen sizes.',
        'button_label'  => 'Add Screen Size Padding',
        'min'           => 0,
        'max'           => 9,
        'layout'        => 'table',
    ])
        ->addSelect('screen_size', [
            'label'   => 'Screen Size',
            'instructions' => 'Select the screen size for this padding setting.',
            'choices' => [
                'xxs'       => 'XXS (Extra Extra Small)',
                'xs'        => 'XS (Extra Small)',
                'mob'       => 'Mobile',
                'sm'        => 'SM (Small)',
                'md'        => 'MD (Medium)',
                'lg'        => 'LG (Large)',
                'xl'        => 'XL (Extra Large)',
                'xxl'       => 'XXL (Extra Extra Large)',
                'ultrawide' => 'Ultrawide',
            ],
            'required' => 1,
        ])
        ->addNumber('padding_top', [
            'label'         => 'Padding Top',
            'instructions'  => 'Set the top padding in rem units.',
            'min'           => 0,
            'max'           => 20,
            'step'          => 0.1,
            'append'        => 'rem',
            'default_value' => 5,
        ])
        ->addNumber('padding_bottom', [
            'label'         => 'Padding Bottom',
            'instructions'  => 'Set the bottom padding in rem units.',
            'min'           => 0,
            'max'           => 20,
            'step'          => 0.1,
            'append'        => 'rem',
            'default_value' => 5,
        ])
    ->endRepeater();

return $team_001;
