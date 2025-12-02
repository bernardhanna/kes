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

    ->addTab('design_tab', ['label' => 'Design'])
        ->addSelect('background_color', [
            'label' => 'Background Color',
            'choices' => [
                'bg-white' => 'White',
                'bg-gray-50' => 'Gray 50',
                'bg-blue-dark' => 'Blue Dark',
            ],
            'default_value' => 'bg-gray-50',
        ])
        ->addSelect('heading_color', [
            'label' => 'Heading Color',
            'choices' => [
                'text-blue-dark' => 'Blue Dark',
                'text-gray-900'  => 'Gray 900',
                'text-white'     => 'White',
            ],
            'default_value' => 'text-blue-dark',
        ])
        ->addSelect('text_color', [
            'label' => 'Body Text Color',
            'choices' => [
                'text-gray-800' => 'Gray 800',
                'text-gray-700' => 'Gray 700',
                'text-white'    => 'White',
            ],
            'default_value' => 'text-gray-800',
        ])
        ->addSelect('accent_bar_color', [
            'label' => 'Accent Bar Color',
            'choices' => [
                'bg-blue-bright' => 'Blue Bright',
                'bg-black'       => 'Black',
                'bg-white'       => 'White',
                'bg-gray-900'    => 'Gray 900',
            ],
            'default_value' => 'bg-blue-bright',
        ])
        ->addSelect('card_rounded', [
            'label' => 'Card Border Radius',
            'choices' => [
                'rounded-none' => 'None',
                'rounded'      => 'Rounded',
                'rounded-md'   => 'Rounded md',
                'rounded-lg'   => 'Rounded lg',
                'rounded-xl'   => 'Rounded xl',
                'rounded-2xl'  => 'Rounded 2xl',
            ],
            'default_value' => 'rounded-lg',
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
