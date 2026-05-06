<?php
// File: theme-options/projects.php

use StoutLogic\AcfBuilder\FieldsBuilder;

$projectsFields = new FieldsBuilder('projects_fields');

$projectsFields
  ->addGroup('projects_settings', [
    'label' => 'Projects Settings',
  ])

    // — Background Image Upload —
    ->addImage('hero_background_image', [
      'label'         => 'Hero Background Image',
      'instructions'  => 'Upload a hero background; if blank, we fall back to background color.',
      'return_format' => 'array',
      'preview_size'  => 'medium',
    ])

    // — Colors —
    ->addColorPicker('hero_background_color', [
      'label'         => 'Hero Background Color (fallback)',
      'default_value' => '#FFFFFF',
    ])
    ->addColorPicker('divider_color', [
      'label'         => 'Hero Divider Color',
      'default_value' => '#00ACD8',
    ])

    // — Hero Heading Tag & Text —
    ->addSelect('hero_heading_tag', [
      'label'        => 'Hero Heading Tag',
      'choices'      => [
        'h1'   => '<h1>',
        'h2'   => '<h2>',
        'h3'   => '<h3>',
        'h4'   => '<h4>',
        'h5'   => '<h5>',
        'h6'   => '<h6>',
        'span' => '<span>',
        'p'    => '<p>',
      ],
      'default_value'=> 'h1',
      'ui'           => 1,
    ])
    ->addText('hero_heading_text', [
      'label'        => 'Hero Heading Text',
      'default_value'=> 'Projects',
    ])

    // — Hero Sub-heading / Intro —
    ->addWysiwyg('hero_intro_content', [
      'label'         => 'Hero Intro Content',
      'instructions'  => 'Optional content under the heading.',
      'media_upload'  => 0,
      'wrapper'       => ['class' => 'wp_editor'],
    ])

    // — Filter Section Title —
    ->addText('filter_section_title', [
      'label'        => 'Filter Section Title',
      'default_value'=> 'Filter by',
    ])

    // — Archive map (projects pins map) —
    ->addTrueFalse('show_projects_map', [
      'label'         => 'Show projects map above header',
      'instructions'  => 'Displays an interactive projects map (pins pulled from Projects lat/long fields).',
      'default_value' => 0,
      'ui'            => 1,
    ])
    ->addNumber('projects_map_center_lat', [
      'label'            => 'Map center latitude',
      'instructions'     => 'e.g. 53.349805',
      'step'             => 'any',
      'min'              => -90,
      'max'              => 90,
      'default_value'    => 53.349805,
      'conditional_logic'=> [['field' => 'show_projects_map', 'operator' => '==', 'value' => 1]],
    ])
    ->addNumber('projects_map_center_lng', [
      'label'            => 'Map center longitude',
      'instructions'     => 'e.g. -6.26031',
      'step'             => 'any',
      'min'              => -180,
      'max'              => 180,
      'default_value'    => -6.26031,
      'conditional_logic'=> [['field' => 'show_projects_map', 'operator' => '==', 'value' => 1]],
    ])
    ->addNumber('projects_map_zoom', [
      'label'            => 'Map zoom',
      'instructions'     => 'Initial zoom level (e.g. 6–10 for Ireland).',
      'min'              => 1,
      'max'              => 18,
      'default_value'    => 7,
      'conditional_logic'=> [['field' => 'show_projects_map', 'operator' => '==', 'value' => 1]],
    ])
    ->addSelect('projects_map_tile_provider', [
      'label'            => 'Map style (tiles)',
      'choices'          => [
        'osm'             => 'OpenStreetMap (default)',
        'cartodb_voyager' => 'CartoDB Voyager (blue water)',
        'jawg-light'      => 'Jawg Light (requires API key)',
        'jawg-dark'       => 'Jawg Dark (requires API key)',
        'jawg-custom'     => 'Jawg Custom raster (requires API key + raster style ID)',
        'jawg-vector'     => 'Jawg Vector / MapLibre (requires API key + vector style ID)',
      ],
      'default_value'    => 'cartodb_voyager',
      'conditional_logic'=> [['field' => 'show_projects_map', 'operator' => '==', 'value' => 1]],
    ])
    ->addText('projects_map_tile_api_key', [
      'label'            => 'Jawg API key (optional)',
      'instructions'     => 'Required for Jawg styles. Leave empty to use OpenStreetMap (raster only).',
      'conditional_logic'=> [['field' => 'show_projects_map', 'operator' => '==', 'value' => 1]],
    ])
    ->addText('projects_map_jawg_style_id', [
      'label'            => 'Jawg custom raster style ID',
      'instructions'     => 'Required for Custom raster. From Jawg Lab -> your style -> Leaflet section.',
      'conditional_logic'=> [
        ['field' => 'show_projects_map', 'operator' => '==', 'value' => 1],
        ['field' => 'projects_map_tile_provider', 'operator' => '==', 'value' => 'jawg-custom'],
      ],
    ])
    ->addText('projects_map_jawg_vector_style_id', [
      'label'            => 'Jawg vector style ID (UUID)',
      'instructions'     => 'Required for Jawg Vector. From Jawg Lab -> your style -> MapLibre/Vector section.',
      'conditional_logic'=> [
        ['field' => 'show_projects_map', 'operator' => '==', 'value' => 1],
        ['field' => 'projects_map_tile_provider', 'operator' => '==', 'value' => 'jawg-vector'],
      ],
    ])
    ->addTrueFalse('projects_map_style_blue', [
      'label'            => 'Apply blue tint (map styling)',
      'ui'               => 1,
      'default_value'    => 1,
      'conditional_logic'=> [['field' => 'show_projects_map', 'operator' => '==', 'value' => 1]],
    ])
    ->addImage('projects_map_marker_image', [
      'label'            => 'Custom map marker image',
      'return_format'    => 'array',
      'instructions'     => 'Optional. Upload a custom pin/marker for this map.',
      'conditional_logic'=> [['field' => 'show_projects_map', 'operator' => '==', 'value' => 1]],
    ])
    ->addNumber('projects_map_height', [
      'label'            => 'Map height (px)',
      'min'              => 280,
      'max'              => 900,
      'step'             => 1,
      'default_value'    => 533,
      'conditional_logic'=> [['field' => 'show_projects_map', 'operator' => '==', 'value' => 1]],
    ])

    // — Optional responsive paddings for the hero —
    ->addRepeater('padding_settings', [
      'label'         => 'Hero Padding Settings',
      'instructions'  => 'Customize hero padding for different screen sizes (rem).',
      'button_label'  => 'Add Screen Size Padding',
    ])
      ->addSelect('screen_size', [
        'label'   => 'Screen Size',
        'choices' => [
          'xxs'=>'xxs','xs'=>'xs','mob'=>'mob','sm'=>'sm','md'=>'md','lg'=>'lg','xl'=>'xl','xxl'=>'xxl','ultrawide'=>'ultrawide',
        ],
      ])
      ->addNumber('padding_top', [
        'label'        => 'Padding Top',
        'min'          => 0,
        'max'          => 20,
        'step'         => 0.1,
        'append'       => 'rem',
      ])
      ->addNumber('padding_bottom', [
        'label'        => 'Padding Bottom',
        'min'          => 0,
        'max'          => 20,
        'step'         => 0.1,
        'append'       => 'rem',
      ])
    ->endRepeater()

  ->endGroup()

  ->addPostObject('projects_archive_blocks_page', [
    'label'         => 'Projects Archive Blocks Source Page',
    'instructions'  => 'Optional. Select a page whose Hero Blocks render above the Projects title, and whose Page Content Blocks render below it. This lets the Projects archive reuse the exact same block builders as normal pages.',
    'post_type'     => ['page'],
    'return_format' => 'id',
    'ui'            => 1,
  ]);

return $projectsFields;
