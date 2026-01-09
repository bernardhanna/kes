<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

$latest_projects = new FieldsBuilder('latest_projects', [
    'label' => 'Latest Projects',
]);

$latest_projects
    ->addTab('content_tab', ['label' => 'Content'])
        ->addTrueFalse('show_section', [
            'label' => 'Show Section',
            'ui' => 1,
            'default_value' => 1,
        ])
        ->addText('heading', [
            'label' => 'Heading',
            'default_value' => 'Projects',
        ])
        ->addWysiwyg('intro', [
            'label' => 'Intro Text',
            'media_upload' => 0,
            'delay' => 0,
            'instructions' => 'Short description under the heading.',
        ])
        ->addLink('cta_link', [
            'label' => '“View all projects” Link',
            'return_format' => 'array',
        ])
        ->addTrueFalse('use_manual_selection', [
            'label' => 'Manually select 3 projects',
            'ui' => 1,
            'default_value' => 0,
        ])
        ->addRelationship('manual_projects', [
            'label' => 'Select Projects (exactly 3)',
            'post_type' => ['project'],
            'filters' => ['search', 'taxonomy'],
            'return_format' => 'object',
            'min' => 3,
            'max' => 3,
            'instructions' => 'Pick exactly 3 projects to display.',
            'conditional_logic' => [
                [
                    [
                        'field' => 'use_manual_selection',
                        'operator' => '==',
                        'value' => 1,
                    ],
                ],
            ],
        ])

    ->addTab('layout_tab', ['label' => 'Layout'])
        ->addRepeater('padding_settings', [
            'label' => 'Padding Settings',
            'instructions' => 'Customize padding for different screen sizes.',
            'button_label' => 'Add Screen Size Padding',
        ])
            ->addSelect('screen_size', [
                'label' => 'Screen Size',
                'choices' => [
                    'xxs' => 'xxs',
                    'xs' => 'xs',
                    'mob' => 'mob',
                    'sm' => 'sm',
                    'md' => 'md',
                    'lg' => 'lg',
                    'xl' => 'xl',
                    'xxl' => 'xxl',
                    'ultrawide' => 'ultrawide',
                ],
            ])
            ->addNumber('padding_top', [
                'label' => 'Padding Top',
                'instructions' => 'Set the top padding in rem.',
                'min' => 0, 'max' => 20, 'step' => 0.1, 'append' => 'rem',
            ])
            ->addNumber('padding_bottom', [
                'label' => 'Padding Bottom',
                'instructions' => 'Set the bottom padding in rem.',
                'min' => 0, 'max' => 20, 'step' => 0.1, 'append' => 'rem',
            ])
        ->endRepeater();

return $latest_projects;
