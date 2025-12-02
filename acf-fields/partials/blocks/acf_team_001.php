<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

$team_001 = new FieldsBuilder('team_001', [
    'label' => 'Team Section',
]);

$team_001
    ->addTab('Content', ['label' => 'Content'])
    ->addRepeater('team_members', [
        'label' => 'Team Members',
        'instructions' => 'Add team members to display in the section.',
        'button_label' => 'Add Team Member',
        'layout' => 'block',
    ])
        ->addImage('image', [
            'label' => 'Team Member Photo',
            'instructions' => 'Upload a photo of the team member.',
            'return_format' => 'id',
            'preview_size' => 'medium',
            'required' => 1,
        ])
        ->addText('name', [
            'label' => 'Name',
            'instructions' => 'Enter the team member\'s full name.',
            'required' => 1,
            'default_value' => 'Team Member Name',
        ])
        ->addText('job_title', [
            'label' => 'Job Title',
            'instructions' => 'Enter the team member\'s job title or position.',
            'required' => 1,
            'default_value' => 'Job Title',
        ])
        ->addWysiwyg('description', [
            'label' => 'Description',
            'instructions' => 'Add a brief description or bio of the team member.',
            'required' => 1,
            'default_value' => 'Team member description and background information.',
            'media_upload' => 0,
            'tabs' => 'visual,text',
            'toolbar' => 'basic',
        ])
    ->endRepeater()
    ->addLink('button', [
        'label' => 'Call to Action Button',
        'instructions' => 'Add a button link to view more team members or related content.',
        'return_format' => 'array',
        'default_value' => [
            'title' => 'View our people',
            'url' => '#',
            'target' => '_self',
        ],
    ])
    ->addTab('Design', ['label' => 'Design'])
    ->addColorPicker('background_color', [
        'label' => 'Background Color',
        'instructions' => 'Choose the background color for the team section.',
        'default_value' => '#ffffff',
    ])
    ->addTab('Layout', ['label' => 'Layout'])
    ->addRepeater('padding_settings', [
        'label' => 'Padding Settings',
        'instructions' => 'Customize padding for different screen sizes.',
        'button_label' => 'Add Screen Size Padding',
        'min' => 0,
        'max' => 9,
        'layout' => 'table',
    ])
        ->addSelect('screen_size', [
            'label' => 'Screen Size',
            'instructions' => 'Select the screen size for this padding setting.',
            'choices' => [
                'xxs' => 'XXS (Extra Extra Small)',
                'xs' => 'XS (Extra Small)',
                'mob' => 'Mobile',
                'sm' => 'SM (Small)',
                'md' => 'MD (Medium)',
                'lg' => 'LG (Large)',
                'xl' => 'XL (Extra Large)',
                'xxl' => 'XXL (Extra Extra Large)',
                'ultrawide' => 'Ultrawide',
            ],
            'required' => 1,
        ])
        ->addNumber('padding_top', [
            'label' => 'Padding Top',
            'instructions' => 'Set the top padding in rem units.',
            'min' => 0,
            'max' => 20,
            'step' => 0.1,
            'append' => 'rem',
            'default_value' => 5,
        ])
        ->addNumber('padding_bottom', [
            'label' => 'Padding Bottom',
            'instructions' => 'Set the bottom padding in rem units.',
            'min' => 0,
            'max' => 20,
            'step' => 0.1,
            'append' => 'rem',
            'default_value' => 5,
        ])
    ->endRepeater();

return $team_001;
