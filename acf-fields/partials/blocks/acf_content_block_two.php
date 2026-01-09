<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

$content_block_two = new FieldsBuilder('content_block_two', [
    'label' => 'Content Section Two',
]);

$content_block_two
    ->addTab('content_tab', ['label' => 'Content'])
        ->addTrueFalse('show_section', [
            'label' => 'Show Section',
            'ui' => 1,
            'default_value' => 1,
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
            'default_value' => 'Sustainability',
            'instructions' => 'Main section heading.',
        ])
        ->addWysiwyg('wysiwyg_one', [
            'label' => 'Intro Content (18/24)',
            'instructions' => 'Rendered with text-[18px] leading-[24px], font-normal.',
            'media_upload' => 0,
            'delay' => 0,
        ])
        ->addWysiwyg('wysiwyg_two', [
            'label' => 'Detail Content (16/20)',
            'instructions' => 'Rendered with text-[16px] leading-[20px], font-normal.',
            'media_upload' => 0,
            'delay' => 0,
        ])
        ->addImage('image', [
            'label' => 'Side Image',
            'return_format' => 'array',
            'instructions' => 'Image shown in the image column.',
        ])
        ->addLink('primary_cta', [
            'label' => 'Primary CTA (gradient button)',
            'return_format' => 'array',
        ])
        ->addLink('secondary_cta', [
            'label' => 'Secondary CTA (outlined button)',
            'return_format' => 'array',
        ])

    ->addTab('layout_tab', ['label' => 'Layout'])
        ->addTrueFalse('reverse_layout', [
            'label' => 'Reverse Layout (Image Left, Content Right)',
            'ui' => 1,
            'default_value' => 0,
        ])
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
                'min' => 0, 'max' => 20, 'step' => 0.1, 'append' => 'rem',
            ])
            ->addNumber('padding_bottom', [
                'label' => 'Padding Bottom',
                'instructions' => 'Set the bottom padding in rem.',
                'min' => 0, 'max' => 20, 'step' => 0.1, 'append' => 'rem',
            ])
        ->endRepeater();

return $content_block_two;
