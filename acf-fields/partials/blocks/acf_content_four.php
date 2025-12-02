<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

$content_four = new FieldsBuilder('content_four', [
    'label' => 'Content Four',
]);

$content_four
    ->addTab('content_tab', ['label' => 'Content'])
        ->addTrueFalse('show_section', [
            'label' => 'Show Section',
            'ui' => 1,
            'default_value' => 1,
        ])
        ->addSelect('heading_tag', [
            'label' => 'Heading Tag',
            'choices' => [
                'h1'=>'H1','h2'=>'H2','h3'=>'H3','h4'=>'H4','h5'=>'H5','h6'=>'H6','span'=>'span','p'=>'p'
            ],
            'default_value' => 'h1',
        ])
        ->addText('heading', [
            'label' => 'Heading',
            'placeholder' => 'About us',
        ])
        ->addWysiwyg('description', [
            'label' => 'Description',
            'media_upload' => 0,
            'delay' => 0,
        ])
        ->addImage('image', [
            'label' => 'Right Image',
            'return_format' => 'array',
        ])

    ->addTab('design_tab', ['label' => 'Design'])
        ->addSelect('background_color', [
            'label' => 'Background Color',
            'choices' => [
                'bg-white'    => 'White',
                'bg-gray-50'  => 'Gray 50',
                'bg-blue-50'  => 'Blue 50',
            ],
            'default_value' => 'bg-white',
        ])
        ->addSelect('text_color', [
            'label' => 'Text Color',
            'choices' => [
                'text-gray-800'  => 'Gray 800',
                'text-blue-500'  => 'Blue 500',
                'text-black'     => 'Black',
            ],
            'default_value' => 'text-gray-800',
        ])
        ->addSelect('heading_color', [
            'label' => 'Heading Color',
            'choices' => [
                'text-blue-500'  => 'Blue 500',
                'text-gray-900'  => 'Gray 900',
                'text-black'     => 'Black',
            ],
            'default_value' => 'text-blue-500',
        ])
        ->addSelect('accent_bar_color', [
            'label' => 'Accent Bar Color',
            'choices' => [
                'bg-blue-100' => 'Blue 100',
                'bg-blue-500' => 'Blue 500',
                'bg-gray-200' => 'Gray 200',
            ],
            'default_value' => 'bg-blue-100',
        ])
        ->addSelect('image_radius', [
            'label' => 'Image Border Radius',
            'choices' => [
                'rounded-none' => 'None',
                'rounded'      => 'Rounded',
                'rounded-md'   => 'Rounded md',
                'rounded-lg'   => 'Rounded lg',
                'rounded-xl'   => 'Rounded xl',
                'rounded-2xl'  => 'Rounded 2xl',
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
                    'xxs'=>'xxs','xs'=>'xs','mob'=>'mob','sm'=>'sm','md'=>'md','lg'=>'lg','xl'=>'xl','xxl'=>'xxl','ultrawide'=>'ultrawide'
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

return $content_four;
