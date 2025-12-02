<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

$cta = new FieldsBuilder('cta', [
    'label' => 'CTA',
]);

$cta
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
    ->addText('heading_text', [
      'label' => 'Heading',
      'default_value' => 'How can we help?',
    ])
    ->addWysiwyg('subheading', [
      'label' => 'Subheading',
      'media_upload' => 0,
      'delay' => 0,
      'default_value' => "Can't find what you're looking for? feel free to contact us",
    ])
    ->addLink('button_link', [
      'label' => 'Button Link',
      'return_format' => 'array',
    ])
    ->addText('button_label', [
      'label' => 'Button Label (override link text)',
      'instructions' => 'If empty, the link title is used.',
    ])

  ->addTab('design_tab', ['label' => 'Design'])
    ->addSelect('background_color', [
      'label' => 'Background',
      'choices' => [
        'bg-white' => 'White',
        'bg-gray-50' => 'Gray 50',
        'bg-blue-50' => 'Blue 50',
      ],
      'default_value' => 'bg-white',
    ])
    ->addSelect('heading_color', [
      'label' => 'Heading Color',
      'choices' => [
        'text-blue-500' => 'Blue 500',
        'text-gray-900' => 'Gray 900',
        'text-black' => 'Black',
      ],
      'default_value' => 'text-blue-500',
    ])
    ->addSelect('accent_bar_color', [
      'label' => 'Accent Bar Color',
      'choices' => [
        'bg-blue-100' => 'Blue 100',
        'bg-gray-200' => 'Gray 200',
        'bg-blue-500' => 'Blue 500',
      ],
      'default_value' => 'bg-blue-100',
    ])
    ->addSelect('text_color', [
      'label' => 'Body Text Color',
      'choices' => [
        'text-gray-800' => 'Gray 800',
        'text-gray-700' => 'Gray 700',
        'text-black' => 'Black',
      ],
      'default_value' => 'text-gray-800',
    ])
    ->addSelect('button_style', [
      'label' => 'Button Style',
      'choices' => [
        'gradient-blue' => 'Gradient Blue',
        'solid-primary' => 'Solid Primary',
        'outline' => 'Outline',
      ],
      'default_value' => 'gradient-blue',
    ])
    ->addSelect('button_radius', [
      'label' => 'Button Radius',
      'choices' => [
        'rounded-none' => 'None',
        'rounded' => 'Rounded',
        'rounded-md' => 'Rounded md',
        'rounded-lg' => 'Rounded lg',
        'rounded-xl' => 'Rounded xl',
        'rounded-full' => 'Rounded full',
      ],
      'default_value' => 'rounded-full',
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
        'min' => 0, 'max' => 20, 'step' => 0.1, 'append' => 'rem',
      ])
      ->addNumber('padding_bottom', [
        'label' => 'Padding Bottom',
        'min' => 0, 'max' => 20, 'step' => 0.1, 'append' => 'rem',
      ])
    ->endRepeater();

return $cta;
