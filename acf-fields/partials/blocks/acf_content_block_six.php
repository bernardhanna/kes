<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

$content_block_006 = new FieldsBuilder('content_block_006', [
    'label' => 'Content Block 006',
]);

$content_block_006
    ->addTab('content_tab', ['label' => 'Content'])
        ->addTrueFalse('show_section', [
            'label' => 'Show Section',
            'ui' => 1,
            'default_value' => 1,
        ])
        ->addImage('left_image', [
            'label' => 'Left Image',
            'return_format' => 'array',
        ])
        ->addSelect('heading_tag', [
            'label' => 'Heading Tag',
            'choices' => [
                'h1'=>'H1','h2'=>'H2','h3'=>'H3','h4'=>'H4','h5'=>'H5','h6'=>'H6','span'=>'span','p'=>'p'
            ],
            'default_value' => 'h1',
        ])
        ->addText('heading_text', [
            'label' => 'Heading',
            'default_value' => 'Treatment includes:',
        ])
        ->addWysiwyg('description', [
            'label' => 'Description',
            'media_upload' => 0,
            'delay' => 0,
        ])
        ->addRepeater('services', [
            'label' => 'Services List',
            'button_label' => 'Add Service',
            'min' => 0,
            'layout' => 'row',
        ])
            ->addText('item_text', [
                'label' => 'Service Text',
            ])
        ->endRepeater()
        ->addLink('download_button', [
            'label' => 'Download Button Link',
            'return_format' => 'array',
        ])
        ->addText('download_button_label', [
            'label' => 'Download Button Label (override)',
            'default_value' => 'Download brochure',
        ])

    ->addTab('design_tab', ['label' => 'Design'])
        ->addColorPicker('gradient_from', [
            'label' => 'Gradient From',
            'default_value' => '#262262',
        ])
        ->addColorPicker('gradient_to', [
            'label' => 'Gradient To',
            'default_value' => '#2B3990',
        ])
        ->addSelect('heading_color', [
            'label' => 'Heading Color',
            'choices' => [
                'text-white' => 'White',
                'text-blue-50' => 'Blue 50',
                'text-gray-100' => 'Gray 100',
            ],
            'default_value' => 'text-white',
        ])
        ->addSelect('text_color', [
            'label' => 'Body Text Color',
            'choices' => [
                'text-white' => 'White',
                'text-gray-100' => 'Gray 100',
                'text-blue-50' => 'Blue 50',
            ],
            'default_value' => 'text-white',
        ])
        ->addSelect('accent_bar_color', [
            'label' => 'Accent Bar Color',
            'choices' => [
                'bg-blue-50' => 'Blue 50',
                'bg-white' => 'White',
                'bg-gray-100' => 'Gray 100',
            ],
            'default_value' => 'bg-blue-50',
        ])
        ->addSelect('image_radius', [
            'label' => 'Image Border Radius',
            'choices' => [
                'rounded-none'=>'None','rounded'=>'Rounded','rounded-md'=>'Rounded md',
                'rounded-lg'=>'Rounded lg','rounded-xl'=>'Rounded xl','rounded-2xl'=>'Rounded 2xl'
            ],
            'default_value' => 'rounded-none',
        ])

    ->addTab('layout_tab', ['label' => 'Layout'])
        ->addRepeater('padding_settings', [
            'label' => 'Padding Settings',
            'instructions' => 'Customize padding for different screen sizes.',
            'button_label' => 'Add Screen Size Padding',
        ])
            ->addSelect('screen_size', [
                'label' => 'Screen Size',
                'choices' => [
                    'xxs'=>'xxs','xs'=>'xs','mob'=>'mob','sm'=>'sm','md'=>'md',
                    'lg'=>'lg','xl'=>'xl','xxl'=>'xxl','ultrawide'=>'ultrawide',
                ],
            ])
            ->addNumber('padding_top', [
                'label' => 'Padding Top',
                'instructions' => 'Set the top padding in rem.',
                'min' => 0, 'max' => 20, 'step' => 0.1, 'append' => 'rem',
            ])
            ->addNumber('padding_bottom', [
                'label' => 'Padding Bottom',
                'instructions' => 'Set the bottom padding in rem.',
                'min' => 0, 'max' => 20, 'step' => 0.1, 'append' => 'rem',
            ])
        ->endRepeater();

return $content_block_006;
