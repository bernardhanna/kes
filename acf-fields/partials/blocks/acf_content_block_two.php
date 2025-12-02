<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

$content_block_two = new FieldsBuilder('content_block_two', [
    'label' => 'Content Section Two',
]);

$content_block_two
    ->addTab('Content', ['label' => 'Content'])
    ->addText('heading', [
        'label' => 'Heading Text',
        'instructions' => 'Enter the main heading for the Content Two.',
        'default_value' => 'Sustainability',
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
        'instructions' => 'Enter the main content for the Content Two.',
        'default_value' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris. At vero eos et accusam et justo duo dolores et ea rebum.</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris. At vero eos et accusam et justo duo dolores et ea rebum.</p>',
        'media_upload' => 1,
        'tabs' => 'all',
        'toolbar' => 'full',
    ])
    ->addImage('image', [
        'label' => 'Image',
        'instructions' => 'Upload an image for the Content Two.',
        'return_format' => 'id',
        'preview_size' => 'medium',
        'library' => 'all',
    ])
    ->addLink('primary_button', [
        'label' => 'Primary Button',
        'instructions' => 'Configure the primary action button.',
        'return_format' => 'array',
    ])
    ->addLink('secondary_button', [
        'label' => 'Secondary Button',
        'instructions' => 'Configure the secondary action button.',
        'return_format' => 'array',
    ])

    ->addTab('Design', ['label' => 'Design'])
    ->addColorPicker('background_color', [
        'label' => 'Background Color',
        'instructions' => 'Set the background color for the section.',
        'default_value' => '#ffffff',
    ])
    ->addColorPicker('heading_color', [
        'label' => 'Heading Color',
        'instructions' => 'Set the color for the heading text.',
        'default_value' => '#262262',
    ])
    ->addColorPicker('text_color', [
        'label' => 'Text Color',
        'instructions' => 'Set the color for the main content text.',
        'default_value' => '#1e293b',
    ])
    ->addColorPicker('divider_color', [
        'label' => 'Divider Color',
        'instructions' => 'Set the color for the heading underline divider.',
        'default_value' => '#06b6d4',
    ])

    ->addTab('Layout', ['label' => 'Layout'])
    ->addTrueFalse('reverse_layout', [
        'label' => 'Reverse Layout',
        'instructions' => 'Toggle to reverse the content and image positions (image on left, content on right).',
        'default_value' => 0,
        'ui' => 1,
    ])
    ->addRepeater('padding_settings', [
        'label' => 'Padding Settings',
        'instructions' => 'Customize padding for different screen sizes.',
        'button_label' => 'Add Screen Size Padding',
        'min' => 0,
        'max' => 10,
        'layout' => 'table',
    ])
    ->addSelect('screen_size', [
        'label' => 'Screen Size',
        'instructions' => 'Select the screen size for this padding setting.',
        'choices' => [
            'xxs' => 'XXS (320px+)',
            'xs' => 'XS (480px+)',
            'mob' => 'Mobile (575px+)',
            'sm' => 'Small (640px+)',
            'md' => 'Medium (768px+)',
            'lg' => 'Large (1100px+)',
            'xl' => 'Extra Large (1280px+)',
            'xxl' => 'XXL (1440px+)',
            'ultrawide' => 'Ultra Wide (1920px+)',
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

return $content_block_two;
