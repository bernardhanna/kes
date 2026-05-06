<?php
// File: theme-options/blog.php

use StoutLogic\AcfBuilder\FieldsBuilder;

$blogFields = new FieldsBuilder('blog_fields');

$blogFields
  ->addGroup('blog_settings', [
    'label' => 'Blog Settings',
  ])

    // — Background Image Upload —
    ->addImage('hero_background_image', [
      'label'         => 'Hero Background Image',
      'instructions'  => 'Upload a hero background; if blank, we fall back to green.',
      'return_format' => 'array',
      'preview_size'  => 'medium',
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
      'default_value'=> "What's new at Tyrecare",
    ])

    // — Hero Sub-heading —
    ->addText('hero_subheading_text', [
      'label'        => 'Hero Sub-heading Text',
      'default_value'=> 'Latest and greatest.',
    ])

    // — Filter Section Title —
    ->addText('filter_section_title', [
      'label'        => 'Filter Section Title',
      'default_value'=> 'Filter by',
    ])

    // — Posts index title band (above filters) —
    ->addMessage(
      'blog_index_header_note',
      'Title band shown on the blog / posts index (above "Filter by"). Edit the intro below.'
    )
    ->addTrueFalse('show_blog_index_header', [
      'label'         => 'Show posts index header',
      'instructions'  => 'White band with heading, accent line, and optional intro text.',
      'default_value' => 1,
      'ui'            => 1,
    ])
    ->addSelect('blog_index_heading_tag', [
      'label'         => 'Index header heading tag',
      'choices'       => [
        'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6',
      ],
      'default_value' => 'h2',
      'ui'            => 1,
    ])
    ->addText('blog_index_heading', [
      'label'         => 'Index header title',
      'instructions'  => 'Leave empty to use the Posts page title.',
    ])
    ->addWysiwyg('blog_index_intro', [
      'label'         => 'Index header intro',
      'instructions'  => 'Optional text below the heading (e.g. short description).',
      'media_upload'  => 0,
      'toolbar'       => 'basic',
      'tabs'          => 'visual',
      'wrapper'       => ['class' => 'wp_editor'],
    ])
    ->addColorPicker('blog_index_bg', [
      'label'         => 'Index header background',
      'default_value' => '#FFFFFF',
    ])
    ->addColorPicker('blog_index_underline', [
      'label'         => 'Index header underline color',
      'default_value' => '#00ACD8',
    ])

  ->endGroup();

return $blogFields;
