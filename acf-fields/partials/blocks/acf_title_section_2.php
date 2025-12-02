<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

$title_section_2 = new FieldsBuilder('title_section_2', [
    'label' => 'Title Section 2',
]);

$title_section_2
    ->addTab('Content', ['label' => 'Content'])
    ->addText('heading', [
        'label' => 'Heading Text',
        'instructions' => 'Enter the heading text for this section.',
        'default_value' => 'Other projects:',
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
            'p'  => 'Paragraph',
            'span' => 'Span',
        ],
        'default_value' => 'h2',
        'required' => 1,
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
        'required' => 1,
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

return $title_section_2;
