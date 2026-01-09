<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

$content_block_one = new FieldsBuilder('content_block_one', [
    'label' => 'Content Section One',
]);

$content_block_one
    ->addTab('content_tab', ['label' => 'Content'])
        ->addTrueFalse('show_section', [
            'label' => 'Show Section',
            'ui' => 1,
            'default_value' => 1,
        ])
        ->addImage('image', [
            'label' => 'Main Image',
            'return_format' => 'array',
            'instructions' => 'Upload the image shown on the left/right.',
        ])
        ->addSelect('heading_tag', [
            'label' => 'Heading Tag',
            'choices' => [
                'h1' => 'H1','h2' => 'H2','h3' => 'H3','h4' => 'H4','h5' => 'H5','h6' => 'H6',
                'span' => 'span','p' => 'p',
            ],
            'default_value' => 'h2',
        ])
        ->addText('heading', [
            'label' => 'Heading',
            'placeholder' => 'Why Choose KES Group?',
        ])
        ->addWysiwyg('description', [
            'label' => 'Description',
            'media_upload' => 0,
            'delay' => 0,
        ])
        ->addRepeater('benefits', [
            'label' => 'Benefits',
            'button_label' => 'Add Benefit',
            'layout' => 'table',
            'min' => 0,
        ])
            ->addText('text', [
                'label' => 'Benefit Text',
                'placeholder' => 'Duis autem vel eum iriure dolor in hendrerit',
            ])
        ->endRepeater()
        ->addLink('cta_link', [
            'label' => 'CTA Link',
            'return_format' => 'array',
        ])

    ->addTab('layout_tab', ['label' => 'Layout'])
        ->addTrueFalse('reverse_layout', [
            'label' => 'Reverse Layout (Image Right, Content Left)',
            'ui' => 1,
            'default_value' => 0,
        ])
        ->addSelect('image_radius', [
            'label' => 'Image Border Radius',
            'choices' => [
                'rounded-none' => 'None',
                'rounded'      => 'Rounded',
                'rounded-sm'   => 'Rounded sm',
                'rounded-md'   => 'Rounded md',
                'rounded-lg'   => 'Rounded lg',
                'rounded-xl'   => 'Rounded xl',
                'rounded-2xl'  => 'Rounded 2xl',
                'rounded-3xl'  => 'Rounded 3xl',
            ],
            'default_value' => 'rounded-none', // editor default; template renders 10px unless changed
        ])
        ->addRepeater('padding_settings', [
            'label'        => 'Padding Settings',
            'instructions' => 'Customize padding for different screen sizes.',
            'button_label' => 'Add Screen Size Padding',
        ])
            ->addSelect('screen_size', [
                'label' => 'Screen Size',
                'choices' => [
                    'xxs' => 'xxs','xs' => 'xs','mob' => 'mob','sm' => 'sm','md' => 'md',
                    'lg' => 'lg','xl' => 'xl','xxl' => 'xxl','ultrawide' => 'ultrawide',
                ],
            ])
            ->addNumber('padding_top', [
                'label' => 'Padding Top',
                'instructions' => 'Set the top padding in rem.',
                'min' => 0,'max' => 20,'step' => 0.1,'append' => 'rem',
            ])
            ->addNumber('padding_bottom', [
                'label' => 'Padding Bottom',
                'instructions' => 'Set the bottom padding in rem.',
                'min' => 0,'max' => 20,'step' => 0.1,'append' => 'rem',
            ])
        ->endRepeater();

return $content_block_one;
