<?php
// File: theme-options/services.php
use StoutLogic\AcfBuilder\FieldsBuilder;

$servicesFields = new FieldsBuilder('services_fields');

$servicesFields
  ->addGroup('services_settings', ['label' => 'Services Settings'])

    // — Background —
    ->addImage('hero_background_image', [
      'label'         => 'Hero Background Image',
      'instructions'  => 'Upload a hero background; if blank, we fall back to background color.',
      'return_format' => 'array',
      'preview_size'  => 'medium',
    ])
    ->addColorPicker('hero_background_color', [
      'label'         => 'Hero Background Color (fallback)',
      'default_value' => '#FFFFFF',
    ])
    ->addColorPicker('divider_color', [
      'label'         => 'Hero Divider Color',
      'default_value' => '#7C3AED',
    ])

    // — Heading & Intro —
    ->addSelect('hero_heading_tag', [
      'label'        => 'Hero Heading Tag',
      'choices'      => [
        'h1'=>'<h1>','h2'=>'<h2>','h3'=>'<h3>','h4'=>'<h4>','h5'=>'<h5>','h6'=>'<h6>','span'=>'<span>','p'=>'<p>',
      ],
      'default_value'=> 'h1',
      'ui'           => 1,
    ])
    ->addText('hero_heading_text', [
      'label'        => 'Hero Heading Text',
      'default_value'=> 'Services',
    ])
    ->addWysiwyg('hero_intro_content', [
      'label'        => 'Hero Intro Content',
      'instructions' => 'Optional content under the heading.',
      'media_upload' => 0,
      'wrapper'      => ['class' => 'wp_editor'],
    ])

    // — Filter Title —
    ->addText('filter_section_title', [
      'label'        => 'Filter Section Title',
      'default_value'=> 'Filter by',
    ])

    // — Responsive hero paddings (rem) —
    ->addRepeater('padding_settings', [
      'label'        => 'Hero Padding Settings',
      'instructions' => 'Customize hero padding for different screen sizes (rem).',
      'button_label' => 'Add Screen Size Padding',
    ])
      ->addSelect('screen_size', [
        'label'   => 'Screen Size',
        'choices' => [
          'xxs'=>'xxs','xs'=>'xs','mob'=>'mob','sm'=>'sm','md'=>'md','lg'=>'lg','xl'=>'xl','xxl'=>'xxl','ultrawide'=>'ultrawide',
        ],
      ])
      ->addNumber('padding_top', [
        'label'  => 'Padding Top', 'min'=>0,'max'=>20,'step'=>0.1,'append'=>'rem',
      ])
      ->addNumber('padding_bottom', [
        'label'  => 'Padding Bottom', 'min'=>0,'max'=>20,'step'=>0.1,'append'=>'rem',
      ])
    ->endRepeater()

  ->endGroup();

return $servicesFields;
