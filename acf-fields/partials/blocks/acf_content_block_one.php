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
        ->addTrueFalse('description_full_width', [
            'label' => 'Description full width',
            'instructions' => 'When enabled, the description uses full available width instead of 400px.',
            'ui' => 1,
            'default_value' => 0,
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
        ->addTrueFalse('enable_left_offset', [
            'label' => 'Enable left offset (xl/xxl)',
            'instructions' => 'When enabled, the section content is shifted right on xl (5rem) and xxl (5.2rem) breakpoints. Disabled by default.',
            'ui' => 1,
            'default_value' => 0,
        ])
        ->addTrueFalse('reverse_layout', [
            'label' => 'Reverse Layout (Image Right, Content Left)',
            'ui' => 1,
            'default_value' => 0,
        ])
        ->addSelect('container_width_mode', [
            'label' => 'Container max width',
            'instructions' => 'Choose the section container width behavior.',
            'choices' => [
                '1088'    => '1088px (default)',
                '1048'    => '1048px',
                '1180'    => '1180px',
                'default' => '1088px (legacy default)',
                'theme'   => 'Theme default (1200 / 1100 on single projects)',
                'none'    => 'No max width',
            ],
            'default_value' => '1088',
            'ui' => 1,
        ])
        ->addTrueFalse('center_text_vertically', [
            'label' => 'Center text vertically',
            'instructions' => 'Vertically centers the text column against the image on desktop.',
            'ui' => 1,
            'default_value' => 0,
        ])
        ->addTrueFalse('limit_heading_width', [
            'label' => 'Limit heading max width',
            'instructions' => 'Enable to constrain heading width.',
            'ui' => 1,
            'default_value' => 0,
        ])
        ->addNumber('heading_max_width_px', [
            'label' => 'Heading max width (px)',
            'min' => 200,
            'max' => 1200,
            'step' => 1,
            'default_value' => 554,
            'append' => 'px',
            'conditional_logic' => [
                ['field' => 'limit_heading_width', 'operator' => '==', 'value' => 1],
            ],
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
