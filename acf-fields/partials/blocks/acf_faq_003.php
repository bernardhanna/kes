<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

$faq_003 = new FieldsBuilder('faq_003', [
  'label' => 'FAQ Section',
]);

$faq_003
  ->addTab('Content', ['label' => 'Content'])
    ->addSelect('heading_tag', [
      'label' => 'Heading Tag',
      'choices' => [
        'h1' => 'H1','h2' => 'H2','h3' => 'H3','h4' => 'H4','h5' => 'H5','h6' => 'H6',
      ],
      'default_value' => 'h2',
    ])
    ->addText('heading_text', [
      'label' => 'Heading Text',
      'default_value' => 'FAQs',
    ])
    ->addTrueFalse('show_cta', [
    'label'         => 'Show bottom CTA button',
    'ui'            => 1,
    'default_value' => 0,
    ])
    ->addLink('cta_link', [
      'label'            => 'CTA Link',
      'instructions'     => 'Select the link for the bottom button.',
      'return_format'    => 'array',
      'conditional_logic'=> [
        [ ['field' => 'show_cta', 'operator' => '==', 'value' => 1] ],
      ],
    ])
    // Manual select is the default behaviour
    ->addTrueFalse('display_all', [
      'label' => 'Display all FAQs (ignore manual selection)',
      'ui' => 1,
      'default_value' => 0,
    ])
    ->addRelationship('faq_items', [
      'label' => 'Select FAQs (Manual)',
      'post_type' => ['faqs'],
      'max' => 10,
      'instructions' => 'Select the FAQs to display in this section.',
      'return_format' => 'object',
      'conditional_logic' => [
        [['field' => 'display_all', 'operator' => '!=', 'value' => 1]],
      ],
    ])
    ->addNumber('all_count', [
      'label' => 'How many FAQs (when “Display all” is ON)',
      'default_value' => 6,
      'min' => 1,
      'max' => 50,
      'step' => 1,
      'conditional_logic' => [
        [['field' => 'display_all', 'operator' => '==', 'value' => 1]],
      ],
    ])
    ->addSelect('all_orderby', [
      'label' => 'Order by',
      'choices' => [
        'date' => 'Date',
        'title' => 'Title',
        'menu_order' => 'Menu Order',
      ],
      'default_value' => 'date',
      'conditional_logic' => [
        [['field' => 'display_all', 'operator' => '==', 'value' => 1]],
      ],
    ])
    ->addSelect('all_order', [
      'label' => 'Order',
      'choices' => [
        'DESC' => 'DESC',
        'ASC'  => 'ASC',
      ],
      'default_value' => 'DESC',
      'conditional_logic' => [
        [['field' => 'display_all', 'operator' => '==', 'value' => 1]],
      ],
    ])

  ->addTab('Design', ['label' => 'Design'])
    ->addColorPicker('section_background', [
      'label' => 'Section Background Color',
      'default_value' => '#FFFFFF',
    ])
    ->addColorPicker('heading_color', [
      'label' => 'Heading Color',
      'default_value' => '#262262', // Blue-ish (tailwind blue-500)
    ])
    ->addColorPicker('accent_bar_color', [
      'label' => 'Accent Bar Color',
      'default_value' => '#00ACD8', // Blue-100 feel
    ])
    ->addColorPicker('question_color', [
      'label' => 'Question Text Color',
      'default_value' => '#2B399B', // Blue-600 feel
    ])
    ->addColorPicker('border_color', [
      'label' => 'Border Color (closed)',
      'default_value' => '#E5E7EB', // Gray-200
    ])
    ->addColorPicker('active_border_color', [
      'label' => 'Active Border Color',
      'default_value' => '#CBE9E1', // Blue-300/50 vibe
    ])
    ->addColorPicker('focus_ring_color', [
      'label' => 'Focus Ring Color',
      'default_value' => '#262262',
    ])

  ->addTab('Layout', ['label' => 'Layout'])
    ->addRepeater('padding_settings', [
      'label' => 'Padding Settings',
      'instructions' => 'Customize padding for different screen sizes.',
      'button_label' => 'Add Padding',
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
        'min' => 0,'max' => 20,'step' => 0.1,'append' => 'rem',
      ])
      ->addNumber('padding_bottom', [
        'label' => 'Padding Bottom',
        'min' => 0,'max' => 20,'step' => 0.1,'append' => 'rem',
      ])
    ->endRepeater();

return $faq_003;
