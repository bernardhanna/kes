<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

$hero_projects = new FieldsBuilder('hero_projects', [
    'label' => 'Hero Projects',
]);

$hero_projects
    ->addTab('Content', ['label' => 'Content'])
    ->addText('heading', [
        'label' => 'Heading Text',
        'instructions' => 'Enter the main heading for the hero section.',
        'default_value' => 'Confidential Data Centre',
        'required' => 1,
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
        'default_value' => 'h1',
    ])
    ->addTextarea('description', [
        'label' => 'Description',
        'instructions' => 'Enter the description text that appears below the heading.',
        'default_value' => 'Full HVAC Mechanical commissioning package on a Large State of the art data centre located in Dublin.',
        'rows' => 3,
    ])
    ->addImage('background_image', [
        'label' => 'Background Image',
        'instructions' => 'Upload the background image for the hero section. Recommended size: 2560px wide or larger.',
        'return_format' => 'id',
        'preview_size' => 'medium',
        'required' => 1,
    ])

    ->addTab('Design', ['label' => 'Design'])
    ->addText('gradient_overlay', [
        'label' => 'Gradient Overlay',
        'instructions' => 'Enter a CSS gradient for the overlay. Leave empty to use the default gradient.',
        'default_value' => 'linear-gradient(90deg, rgba(38, 34, 98, 0.90) 20.39%, rgba(43, 57, 144, 0.00) 80.17%)',
        'placeholder' => 'linear-gradient(90deg, rgba(38, 34, 98, 0.90) 20.39%, rgba(43, 57, 144, 0.00) 80.17%)',
    ])

    ->addTab('Layout', ['label' => 'Layout'])
    ->addRepeater('padding_settings', [
        'label' => 'Padding Settings',
        'instructions' => 'Customize padding for different screen sizes.',
        'button_label' => 'Add Screen Size Padding',
        'min' => 0,
        'max' => 10,
    ])
        ->addSelect('screen_size', [
            'label' => 'Screen Size',
            'instructions' => 'Select the screen size for this padding setting.',
            'choices' => [
                'xxs' => 'XXS',
                'xs' => 'XS',
                'mob' => 'Mobile',
                'sm' => 'Small',
                'md' => 'Medium',
                'lg' => 'Large',
                'xl' => 'Extra Large',
                'xxl' => 'XXL',
                'ultrawide' => 'Ultrawide',
            ],
            'required' => 1,
        ])
        ->addNumber('padding_top', [
            'label' => 'Padding Top',
            'instructions' => 'Set the top padding in rem.',
            'min' => 0,
            'max' => 20,
            'step' => 0.1,
            'append' => 'rem',
            'default_value' => 0,
        ])
        ->addNumber('padding_bottom', [
            'label' => 'Padding Bottom',
            'instructions' => 'Set the bottom padding in rem.',
            'min' => 0,
            'max' => 20,
            'step' => 0.1,
            'append' => 'rem',
            'default_value' => 0,
        ])
    ->endRepeater();

return $hero_projects;
