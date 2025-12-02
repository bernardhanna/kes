<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

$hero_slider = new FieldsBuilder('hero_slider', [
    'label' => 'Hero Slider',
]);

$hero_slider
    ->addTab('content_tab', ['label' => 'Content'])
        ->addRepeater('slides', [
            'label' => 'Slides',
            'button_label' => 'Add Slide',
            'layout' => 'block',
            'min' => 1,
        ])
            ->addImage('background_image', [
                'label' => 'Background Image',
                'return_format' => 'array',
                'instructions' => 'Upload the background image for this slide.',
            ])
            ->addTrueFalse('show_gradient', [
                'label' => 'Enable Gradient Overlay',
                'ui' => 1,
                'default_value' => 1,
            ])
            ->addSelect('title_tag', [
                'label' => 'Title Tag',
                'choices' => [
                    'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3',
                    'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6',
                    'span' => 'span', 'p' => 'p',
                ],
                'default_value' => 'h1',
            ])
            ->addWysiwyg('title', [
                'label' => 'Title',
                'media_upload' => 0,
                'tabs' => 'all',
                'delay' => 0,
                'instructions' => 'You can include <span> for emphasis if desired.',
            ])
            ->addWysiwyg('description', [
                'label' => 'Description',
                'media_upload' => 0,
                'tabs' => 'all',
                'delay' => 0,
            ])
            ->addRepeater('buttons', [
                'label' => 'Buttons',
                'button_label' => 'Add Button',
                'layout' => 'table',
                'min' => 0,
                'max' => 2,
            ])
                ->addLink('button_link', [
                    'label' => 'Button Link',
                    'return_format' => 'array',
                ])
                ->addSelect('button_style', [
                    'label' => 'Button Style',
                    'choices' => [
                        'primary' => 'Primary',
                        'secondary' => 'Secondary',
                    ],
                    'default_value' => 'primary',
                ])
            ->endRepeater()
        ->endRepeater()

    ->addTab('design_tab', ['label' => 'Design'])
        ->addSelect('text_color', [
            'label' => 'Text Color',
            'choices' => [
                'text-white' => 'White',
                'text-black' => 'Black',
                'text-gray-900' => 'Gray 900',
            ],
            'default_value' => 'text-white',
        ])
        ->addSelect('overlay_from', [
            'label' => 'Gradient From',
            'choices' => [
                'from-blue-dark/90' => 'Blue Dark / 90',
                'from-black/80'     => 'Black / 80',
                'from-gray-900/80'  => 'Gray 900 / 80',
            ],
            'default_value' => 'from-blue-dark/90',
            'conditional_logic' => [['field' => 'show_gradient', 'operator' => '==', 'value' => 1]],
        ])
        ->addSelect('overlay_via', [
            'label' => 'Gradient Via',
            'choices' => [
                'via-blue-dark/50' => 'Blue Dark / 50',
                'via-black/50'     => 'Black / 50',
                'via-gray-900/50'  => 'Gray 900 / 50',
                'via-transparent'  => 'Transparent',
            ],
            'default_value' => 'via-blue-dark/50',
            'conditional_logic' => [['field' => 'show_gradient', 'operator' => '==', 'value' => 1]],
        ])
        ->addSelect('overlay_to', [
            'label' => 'Gradient To',
            'choices' => [
                'to-transparent'   => 'Transparent',
                'to-black/0'       => 'Black / 0',
                'to-blue-dark/0'   => 'Blue Dark / 0',
            ],
            'default_value' => 'to-transparent',
            'conditional_logic' => [['field' => 'show_gradient', 'operator' => '==', 'value' => 1]],
        ])
        ->addImage('arrow_prev', [
            'label' => 'Arrow Previous (Image)',
            'return_format' => 'array',
            'instructions' => 'Upload the left/previous arrow image.',
        ])
        ->addImage('arrow_next', [
            'label' => 'Arrow Next (Image)',
            'return_format' => 'array',
            'instructions' => 'Upload the right/next arrow image.',
        ])
        ->addTrueFalse('show_dots', [
            'label' => 'Show Indicators (Dots)',
            'ui' => 1,
            'default_value' => 1,
        ])
        ->addSelect('rounded', [
            'label' => 'Border Radius',
            'choices' => [
                'rounded-none' => 'None',
                'rounded' => 'Rounded',
                'rounded-md' => 'Rounded md',
                'rounded-lg' => 'Rounded lg',
                'rounded-xl' => 'Rounded xl',
                'rounded-2xl' => 'Rounded 2xl',
            ],
            'default_value' => 'rounded-none',
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

return $hero_slider;
