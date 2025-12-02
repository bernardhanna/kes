<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

$counters_001 = new FieldsBuilder('counters_001', [
    'label' => 'Counters Section',
]);

$counters_001
    ->addTab('Content', ['label' => 'Content'])
    ->addText('heading', [
        'label' => 'Section Heading',
        'instructions' => 'Optional heading for the counters section.',
        'default_value' => 'Our Achievements',
    ])
    ->addSelect('heading_tag', [
        'label' => 'Heading Tag',
        'instructions' => 'Select the HTML tag for the heading.',
        'choices' => [
            'h1' => 'H1',
            'h2' => 'H2',
            'h3' => 'H3',
            'h4' => 'H4',
            'h5' => 'H5',
            'h6' => 'H6',
            'p' => 'Paragraph',
            'span' => 'Span',
        ],
        'default_value' => 'h2',
    ])
    ->addRepeater('counter_items', [
        'label' => 'Counter Items',
        'instructions' => 'Add counter items. Each item will animate when it comes into view.',
        'button_label' => 'Add Counter',
        'min' => 1,
        'max' => 6,
        'layout' => 'block',
    ])
        ->addText('counter_number', [
            'label' => 'Counter Number',
            'instructions' => 'Enter the number to count up to (e.g., 25, 100, 500).',
            'required' => 1,
            'default_value' => '25',
        ])
        ->addText('counter_suffix', [
            'label' => 'Counter Suffix',
            'instructions' => 'Optional suffix to display after the number (e.g., +, %, K).',
            'default_value' => '+',
        ])
        ->addText('description', [
            'label' => 'Description',
            'instructions' => 'Description text that appears below the counter.',
            'required' => 1,
            'default_value' => 'Years of experience',
        ])
        ->addImage('icon', [
            'label' => 'Icon',
            'instructions' => 'Upload an icon for this counter item.',
            'return_format' => 'id',
            'preview_size' => 'thumbnail',
            'library' => 'all',
        ])
    ->endRepeater()

    ->addTab('Design', ['label' => 'Design'])
    ->addColorPicker('background_color', [
        'label' => 'Background Color',
        'instructions' => 'Set the background color for the section.',
        'default_value' => '#ffffff',
    ])

    ->addTab('Layout', ['label' => 'Layout'])
    ->addRepeater('padding_settings', [
        'label' => 'Padding Settings',
        'instructions' => 'Customize padding for different screen sizes.',
        'button_label' => 'Add Screen Size Padding',
        'layout' => 'table',
    ])
        ->addSelect('screen_size', [
            'label' => 'Screen Size',
            'choices' => [
                'xxs' => 'XXS (320px+)',
                'xs' => 'XS (480px+)',
                'mob' => 'Mobile (575px+)',
                'sm' => 'Small (640px+)',
                'md' => 'Medium (768px+)',
                'lg' => 'Large (1100px+)',
                'xl' => 'XL (1280px+)',
                'xxl' => 'XXL (1440px+)',
                'ultrawide' => 'Ultrawide (1920px+)',
            ],
            'default_value' => 'md',
        ])
        ->addNumber('padding_top', [
            'label' => 'Padding Top',
            'instructions' => 'Set the top padding in rem.',
            'min' => 0,
            'max' => 20,
            'step' => 0.1,
            'append' => 'rem',
            'default_value' => 5,
        ])
        ->addNumber('padding_bottom', [
            'label' => 'Padding Bottom',
            'instructions' => 'Set the bottom padding in rem.',
            'min' => 0,
            'max' => 20,
            'step' => 0.1,
            'append' => 'rem',
            'default_value' => 5,
        ])
    ->endRepeater();

return $counters_001;
