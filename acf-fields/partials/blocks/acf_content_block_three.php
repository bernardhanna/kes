<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

$content_block_three = new FieldsBuilder('content_block_three', [
    'label' => 'Content Section Three',
]);

$content_block_three
    ->addTab('Content', ['label' => 'Content'])
    ->addText('heading', [
        'label' => 'Heading Text',
        'instructions' => 'Enter the main heading for the section.',
        'default_value' => 'Our expertise/ Who we are/Values',
        'required' => 1,
    ])
    ->addSelect('heading_tag', [
        'label' => 'Heading Tag',
        'instructions' => 'Select the appropriate HTML heading tag for SEO and accessibility.',
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
        'required' => 1,
    ])
    ->addWysiwyg('description', [
        'label' => 'Main Description',
        'instructions' => 'Enter the main description text that appears below the heading.',
        'default_value' => '',
        'media_upload' => 0,
        'tabs' => 'all',
        'toolbar' => 'full',
        'required' => 1,
    ])
    ->addWysiwyg('content_section_1', [
        'label' => 'Content Section 1',
        'instructions' => 'Enter the content for the first text section.',
        'default_value' => '',
        'media_upload' => 0,
        'tabs' => 'all',
        'toolbar' => 'full',
    ])
    ->addWysiwyg('content_section_2', [
        'label' => 'Content Section 2',
        'instructions' => 'Enter the content for the second text section.',
        'default_value' => '',
        'media_upload' => 0,
        'tabs' => 'all',
        'toolbar' => 'full',
    ])

    ->addTab('Design', ['label' => 'Design'])
    ->addColorPicker('background_color', [
        'label' => 'Background Color',
        'instructions' => 'Choose the background color for the section.',
        'default_value' => '#f9fafb',
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

return $content_block_three;
