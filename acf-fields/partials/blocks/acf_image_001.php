<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

$image_001 = new FieldsBuilder('image_001', [
  'label' => 'Image 001',
]);

$image_001
  ->addTab('Content', ['label' => 'Content'])
    ->addImage('image', [
      'label'         => 'Image',
      'instructions'  => 'Upload/select a single image (target display: 1088 × 720).',
      'return_format' => 'id',
      'preview_size'  => 'medium',
      'required'      => 1,
    ])
    ->addLink('link', [
      'label'         => 'Optional Link',
      'instructions'  => 'Wrap the image with this link (ACF link array).',
      'return_format' => 'array',
    ])
    ->addWysiwyg('caption', [
      'label'         => 'Caption',
      'instructions'  => 'Optional caption under the image.',
      'media_upload'  => 0,
      'toolbar'       => 'basic',
      'tabs'          => 'all',
    ])

  ->addTab('Design', ['label' => 'Design'])
    ->addColorPicker('background_color', [
      'label'         => 'Section Background Color',
      'default_value' => '#ffffff',
    ])
    ->addSelect('border_radius', [
      'label'         => 'Image Border Radius',
      'choices'       => [
        'none' => 'None',
        'sm'   => 'Small',
        'md'   => 'Medium',
        'lg'   => 'Large',
        'xl'   => 'XL',
        '2xl'  => '2XL',
        'full' => 'Full',
      ],
      'default_value' => 'none', // rounded none as default
      'ui'            => 1,
    ])

  ->addTab('Layout', ['label' => 'Layout'])
    ->addRepeater('padding_settings', [
      'label'         => 'Padding Settings',
      'instructions'  => 'Customize padding for different screen sizes.',
      'button_label'  => 'Add Screen Size Padding',
      'layout'        => 'table',
    ])
      ->addSelect('screen_size', [
        'label'   => 'Screen Size',
        'choices' => [
          'xxs'       => 'xxs',
          'xs'        => 'xs',
          'mob'       => 'mob',
          'sm'        => 'sm',
          'md'        => 'md',
          'lg'        => 'lg',
          'xl'        => 'xl',
          'xxl'       => 'xxl',
          'ultrawide' => 'ultrawide',
        ],
        'required' => 1,
      ])
      ->addNumber('padding_top', [
        'label'         => 'Padding Top',
        'instructions'  => 'Top padding in rem.',
        'min'           => 0,
        'max'           => 20,
        'step'          => 0.1,
        'append'        => 'rem',
        'default_value' => 5,
      ])
      ->addNumber('padding_bottom', [
        'label'         => 'Padding Bottom',
        'instructions'  => 'Bottom padding in rem.',
        'min'           => 0,
        'max'           => 20,
        'step'          => 0.1,
        'append'        => 'rem',
        'default_value' => 5,
      ])
    ->endRepeater();

return $image_001;
