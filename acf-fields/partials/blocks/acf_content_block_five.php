<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

$content_block_five = new FieldsBuilder('content_block_five', [
    'label' => 'Content block Five',
]);

$content_block_five
    ->addTab('Content', ['label' => 'Content'])
    ->addText('heading', [
        'label' => 'Heading Text',
        'instructions' => 'Enter the main heading for this section.',
        'default_value' => 'Quality, Health & Safety',
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
    ->addColorPicker('heading_underline_color', [
        'label' => 'Heading Underline Color',
        'instructions' => 'Choose the color for the decorative line under the heading.',
        'default_value' => '#06B6D4',
    ])
    ->addWysiwyg('main_content', [
        'label' => 'Main Content',
        'instructions' => 'Enter the primary content text. This will appear directly below the heading.',
        'default_value' => '',
        'media_upload' => 0,
        'tabs' => 'all',
        'toolbar' => 'full',
    ])
    ->addWysiwyg('secondary_content', [
        'label' => 'Secondary Content',
        'instructions' => 'Enter additional content text. This will appear below the main content in a smaller font.',
        'default_value' => '',
        'media_upload' => 0,
        'tabs' => 'all',
        'toolbar' => 'full',
    ])
    ->addImage('image', [
        'label' => 'Section Image',
        'instructions' => 'Upload an image that represents the content. Recommended size: 502px wide.',
        'return_format' => 'id',
        'preview_size' => 'medium',
        'library' => 'all',
    ])
    ->addLink('button', [
        'label' => 'Call to Action Button',
        'instructions' => 'Add a button link. Leave empty to hide the button.',
        'return_format' => 'array',
    ])

    ->addTab('Design', ['label' => 'Design'])
    ->addColorPicker('background_color', [
        'label' => 'Background Color',
        'instructions' => 'Choose a solid background color. This will be overridden if a gradient is set.',
        'default_value' => '#1E293B',
    ])
    ->addTextarea('background_gradient', [
        'label' => 'Background Gradient (Optional)',
        'instructions' => 'Enter a CSS gradient value to override the solid background color. Example: linear-gradient(135deg, #1E293B 0%, #0F172A 100%)',
        'placeholder' => 'linear-gradient(135deg, #1E293B 0%, #0F172A 100%)',
        'rows' => 3,
    ])

    ->addTab('Layout', ['label' => 'Layout'])
    ->addTrueFalse('reverse_layout', [
        'label' => 'Reverse Layout',
        'instructions' => 'Toggle to switch the image and content positions (image on left, content on right).',
        'default_value' => 0,
        'ui' => 1,
    ])
    ->addRepeater('padding_settings', [
        'label' => 'Padding Settings',
        'instructions' => 'Customize padding for different screen sizes. Default padding is applied if no settings are added.',
        'button_label' => 'Add Screen Size Padding',
        'layout' => 'table',
    ])
        ->addSelect('screen_size', [
            'label' => 'Screen Size',
            'instructions' => 'Select the screen size for this padding setting.',
            'choices' => [
                'xxs' => 'Extra Extra Small',
                'xs' => 'Extra Small',
                'mob' => 'Mobile',
                'sm' => 'Small',
                'md' => 'Medium',
                'lg' => 'Large',
                'xl' => 'Extra Large',
                'xxl' => 'Extra Extra Large',
                'ultrawide' => 'Ultra Wide',
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

return $content_block_five;
