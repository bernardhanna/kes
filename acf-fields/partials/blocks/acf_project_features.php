<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

$project_features = new FieldsBuilder('project_features', [
    'label' => 'Project Features',
]);

$project_features
    ->addTab('Content', [
        'label' => 'Content',
        'placement' => 'top'
    ])
    ->addText('section_heading', [
        'label' => 'Section Heading',
        'instructions' => 'Optional heading for the features section.',
        'default_value' => 'Our Key Features',
    ])
    ->addSelect('section_heading_tag', [
        'label' => 'Section Heading Tag',
        'instructions' => 'Choose the appropriate heading level for SEO and accessibility.',
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
    ->addRepeater('features', [
        'label' => 'Features',
        'instructions' => 'Add feature items. Each feature includes an icon, heading, and description.',
        'button_label' => 'Add Feature',
        'min' => 1,
        'max' => 6,
        'layout' => 'block',
    ])
        ->addImage('feature_image', [
            'label' => 'Feature Icon/Image',
            'instructions' => 'Upload an icon or image for this feature. Recommended size: 80x74px.',
            'return_format' => 'id',
            'preview_size' => 'thumbnail',
            'library' => 'all',
        ])
        ->addText('feature_heading', [
            'label' => 'Feature Heading',
            'instructions' => 'Enter the heading text for this feature.',
            'default_value' => 'Key feature / info h4',
            'required' => 1,
        ])
        ->addTextarea('feature_description', [
            'label' => 'Feature Description',
            'instructions' => 'Enter a brief description of this feature.',
            'default_value' => 'At vero eos et accusam et justo duo dolores et ea rebum.',
            'rows' => 3,
            'required' => 1,
        ])
    ->endRepeater()

    ->addTab('Design', [
        'label' => 'Design',
        'placement' => 'top'
    ])
    ->addColorPicker('background_color', [
        'label' => 'Background Color',
        'instructions' => 'Choose the background color for the features section.',
        'default_value' => '#FFFFFF',
    ])

    ->addTab('Layout', [
        'label' => 'Layout',
        'placement' => 'top'
    ])
    ->addRepeater('padding_settings', [
        'label' => 'Padding Settings',
        'instructions' => 'Customize padding for different screen sizes.',
        'button_label' => 'Add Screen Size Padding',
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

return $project_features;
