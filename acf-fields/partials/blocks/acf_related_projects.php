<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

$related_projects = new FieldsBuilder('related_projects', [
    'label' => 'Related Projects',
]);

$related_projects
    ->addTab('Content', ['label' => 'Content'])
    ->addText('heading', [
        'label' => 'Section Heading',
        'instructions' => 'Enter the heading for the related projects section.',
        'default_value' => 'Related Projects',
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
    ->addNumber('number_of_projects', [
        'label' => 'Number of Projects',
        'instructions' => 'Set the number of projects to display (used when auto-selecting related projects).',
        'default_value' => 3,
        'min' => 1,
        'max' => 12,
    ])
    ->addPostObject('selected_projects', [
        'label' => 'Select Specific Projects',
        'instructions' => 'Manually select specific projects to display. If left empty, related projects will be automatically selected based on categories.',
        'post_type' => ['projects'],
        'return_format' => 'object',
        'multiple' => 1,
        'allow_null' => 1,
    ])

    ->addTab('Design', ['label' => 'Design'])
    ->addColorPicker('background_color', [
        'label' => 'Background Color',
        'instructions' => 'Set the background color for the section.',
        'default_value' => '#f9fafb',
    ])

    ->addTab('Layout', ['label' => 'Layout'])
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

return $related_projects;
