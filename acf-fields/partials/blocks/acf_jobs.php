<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

$jobs = new FieldsBuilder('jobs', [
  'label' => 'Jobs',
]);

$jobs
  ->addTab('content_tab', ['label' => 'Content'])
    ->addTrueFalse('show_section', [
      'label' => 'Show Section',
      'ui' => 1,
      'default_value' => 1,
    ])
    ->addText('sr_heading', [
      'label' => 'Screen Reader Heading',
      'instructions' => 'Optional hidden H1 for accessibility.',
      'default_value' => 'Available Job Positions',
    ])
    ->addTrueFalse('manual_featured', [
      'label' => 'Choose Featured Job Manually',
      'ui' => 1,
      'default_value' => 0,
    ])
    ->addRelationship('featured_job', [
      'label' => 'Featured Job',
      'post_type' => ['jobs'],
      'return_format' => 'object',
      'max' => 1,
      'conditional_logic' => [[['field' => 'manual_featured','operator' => '==','value' => 1]]],
    ])
    ->addNumber('jobs_to_show', [
      'label' => 'How many jobs to list on the left',
      'default_value' => 4,
      'min' => 1,
      'max' => 24,
      'step' => 1,
    ])

  ->addTab('design_tab', ['label' => 'Design'])
    ->addSelect('background_color', [
      'label' => 'Background',
      'choices' => [
        'bg-white' => 'White',
        'bg-gray-50' => 'Gray 50',
      ],
      'default_value' => 'bg-white',
    ])
    ->addSelect('border_color', [
      'label' => 'Card Border Color',
      'choices' => [
        'border-emerald-100' => 'Emerald 100',
        'border-gray-200'    => 'Gray 200',
      ],
      'default_value' => 'border-emerald-100',
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
          'xxs'=>'xxs','xs'=>'xs','mob'=>'mob','sm'=>'sm','md'=>'md','lg'=>'lg','xl'=>'xl','xxl'=>'xxl','ultrawide'=>'ultrawide',
        ],
      ])
      ->addNumber('padding_top', [
        'label' => 'Padding Top','min'=>0,'max'=>20,'step'=>0.1,'append'=>'rem',
      ])
      ->addNumber('padding_bottom', [
        'label' => 'Padding Bottom','min'=>0,'max'=>20,'step'=>0.1,'append'=>'rem',
      ])
    ->endRepeater();

return $jobs;
