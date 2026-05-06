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
      'default_value' => 'Career Opportunities',
    ])
    ->addSelect('jobs_source', [
      'label' => 'How jobs are chosen',
      'instructions' => 'Automated pulls the newest published jobs (with optional pinned first job). Manual uses only the roles you pick, in order.',
      'choices' => [
        'automated' => 'Automated — latest jobs',
        'manual'    => 'Manual — select jobs below',
      ],
      'default_value' => 'automated',
    ])
    ->addRelationship('selected_jobs', [
      'label' => 'Jobs to show',
      'instructions' => 'Drag to reorder cards on the page.',
      'post_type' => ['jobs'],
      'filters' => ['search'],
      'return_format' => 'object',
      'max' => 24,
      'conditional_logic' => [[[
        'field'    => 'jobs_source',
        'operator' => '==',
        'value'    => 'manual',
      ]]],
    ])
    ->addNumber('jobs_to_show', [
      'label' => 'How many job cards to show',
      'instructions' => 'Filled from newest published jobs.',
      'default_value' => 4,
      'min' => 1,
      'max' => 24,
      'step' => 1,
      'conditional_logic' => [[[
        'field'    => 'jobs_source',
        'operator' => '==',
        'value'    => 'automated',
      ]]],
    ])
    ->addTrueFalse('manual_featured', [
      'label' => 'Pin one job first (optional)',
      'instructions' => 'When on, choose a job below; it appears above the automated list.',
      'ui' => 1,
      'default_value' => 0,
      'conditional_logic' => [[[
        'field'    => 'jobs_source',
        'operator' => '==',
        'value'    => 'automated',
      ]]],
    ])
    ->addRelationship('featured_job', [
      'label' => 'Pinned job',
      'post_type' => ['jobs'],
      'return_format' => 'object',
      'max' => 1,
      'conditional_logic' => [
        [
          [
            'field'    => 'jobs_source',
            'operator' => '==',
            'value'    => 'automated',
          ],
          [
            'field'    => 'manual_featured',
            'operator' => '==',
            'value'    => 1,
          ],
        ],
      ],
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
