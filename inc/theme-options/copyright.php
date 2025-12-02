<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

$copyright = new FieldsBuilder('copyright');

$copyright
    ->addRepeater('footer_links', [
        'label'         => 'Footer Links',
        'button_label'  => 'Add Link',
        'layout'        => 'table',
        'min'           => 0,
        'instructions'  => 'Add footer navigation links.',
    ])
        ->addLink('link', [
            'label'         => 'Link',
            'return_format' => 'array',
        ])
    ->endRepeater()
    ->addText('credits_text', [
        'label'         => 'Credits Text',
        'default_value' => 'Designed & Developed by Matrix Internet',
    ]);

return $copyright;
