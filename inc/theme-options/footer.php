<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

$footer = new FieldsBuilder('footer');

$footer
    ->addImage('footer_logo', [
        'label'         => 'Footer Logo',
        'return_format' => 'array',
    ])
    ->addRepeater('footer_primary_links', [
        'label'         => 'Footer Links',
        'button_label'  => 'Add Link',
        'layout'        => 'table',
        'min'           => 0,
        'instructions'  => 'Add the primary footer navigation links.',
    ])
        ->addLink('link', [
            'label'         => 'Link',
            'return_format' => 'array', // ACF link array
        ])
    ->endRepeater()
    ->addText('follow_us_text', [
        'label'         => 'Social Label',
        'default_value' => 'Follow us',
    ])
    ->addRepeater('social_links', [
        'label'         => 'Social Links',
        'button_label'  => 'Add Social Link',
        'layout'        => 'table',
        'min'           => 0,
    ])
        ->addText('label', [
            'label'         => 'Label (e.g., Facebook)',
            'required'      => 1,
        ])
        ->addUrl('url', [
            'label'         => 'URL',
            'required'      => 1,
        ])
        ->addImage('icon_image', [
            'label'         => 'Icon Image',
            'return_format' => 'array',
            'instructions'  => 'Upload a square icon (SVG/PNG).',
            'required'      => 1,
        ])
    ->endRepeater();

return $footer;
