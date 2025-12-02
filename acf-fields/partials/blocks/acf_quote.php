<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

$quote = new FieldsBuilder('quote', [
    'label' => 'Quote Block',
]);

$quote
    ->addTab('Content', ['label' => 'Content'])
    ->addWysiwyg('quote_text', [
        'label' => 'Quote Text',
        'instructions' => 'Enter the quote or testimonial text.',
        'default_value' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        'media_upload' => 0,
        'tabs' => 'all',
        'toolbar' => 'full',
        'required' => 1,
    ])

    ->addTab('Design', ['label' => 'Design'])
    ->addText('background_color', [
        'label' => 'Background Color/Gradient',
        'instructions' => 'Enter a CSS background value (color, gradient, etc.). Example: linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        'default_value' => 'linear-gradient(90deg, #2B3990 0%, #006EC8 100%)',
        'placeholder' => '#1a1a1a or linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
    ])
    ->addColorPicker('text_color', [
        'label' => 'Text Color',
        'instructions' => 'Choose the text color for optimal contrast.',
        'default_value' => '#ffffff',
    ])

    ->addTab('Layout', ['label' => 'Layout'])
    ->addRepeater('padding_settings', [
        'label' => 'Padding Settings',
        'instructions' => 'Customize padding for different screen sizes. Default values match the original design.',
        'button_label' => 'Add Screen Size Padding',
        'layout' => 'table',
    ])
    ->addSelect('screen_size', [
        'label' => 'Screen Size',
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
        'instructions' => 'Set the top padding in rem.',
        'min' => 0,
        'max' => 20,
        'step' => 0.1,
        'append' => 'rem',
    ])
    ->addNumber('padding_bottom', [
        'label' => 'Padding Bottom',
        'instructions' => 'Set the bottom padding in rem.',
        'min' => 0,
        'max' => 20,
        'step' => 0.1,
        'append' => 'rem',
    ])
    ->addNumber('padding_left', [
        'label' => 'Padding Left',
        'instructions' => 'Set the left padding in rem.',
        'min' => 0,
        'max' => 20,
        'step' => 0.1,
        'append' => 'rem',
    ])
    ->addNumber('padding_right', [
        'label' => 'Padding Right',
        'instructions' => 'Set the right padding in rem.',
        'min' => 0,
        'max' => 20,
        'step' => 0.1,
        'append' => 'rem',
    ])
    ->endRepeater();

return $quote;
