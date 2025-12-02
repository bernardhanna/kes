<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

$title_section = new FieldsBuilder('title_section', [
    'label' => 'title Section',
]);

$title_section
    ->addTab('Content', ['label' => 'Content'])
    ->addText('heading', [
        'label' => 'Heading Text',
        'instructions' => 'Enter the main heading text for the title section.',
        'default_value' => '',
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
        'default_value' => 'h2',
        'required' => 1,
    ])
    ->addWysiwyg('content', [
        'label' => 'Content',
        'instructions' => 'Enter the main content text for the title section.',
        'default_value' => '',
        'media_upload' => 0,
        'tabs' => 'all',
        'toolbar' => 'full',
        'required' => 1,
    ])
    ->addTab('Design', ['label' => 'Design'])
    ->addColorPicker('background_color', [
        'label' => 'Background Color',
        'instructions' => 'Select the background color for the section.',
        'default_value' => '#ffffff',
    ])
    ->addColorPicker('divider_color', [
        'label' => 'Divider Color',
        'instructions' => 'Select the color for the heading underline divider.',
        'default_value' => '#00ACD8',
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

return $title_section;
