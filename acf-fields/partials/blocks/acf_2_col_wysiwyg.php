<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

$two_col_wysiwyg = new FieldsBuilder('two_col_wysiwyg', [
    'label' => 'Two Column WYSIWYG',
]);

$two_col_wysiwyg
    ->addTab('Content', ['label' => 'Content'])
    ->addWysiwyg('content_left', [
        'label' => 'Left Column Content',
        'instructions' => 'Add content for the left column.',
        'default_value' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>',
        'media_upload' => 1,
        'tabs' => 'all',
        'toolbar' => 'full',
    ])
    ->addWysiwyg('content_right', [
        'label' => 'Right Column Content',
        'instructions' => 'Add content for the right column.',
        'default_value' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>',
        'media_upload' => 1,
        'tabs' => 'all',
        'toolbar' => 'full',
    ])
    ->addTab('Layout', ['label' => 'Layout'])
    ->addRepeater('padding_settings', [
        'label' => 'Padding Settings',
        'instructions' => 'Customize padding for different screen sizes.',
        'button_label' => 'Add Screen Size Padding',
    ])
    ->addSelect('screen_size', [
        'label' => 'Screen Size',
        'choices' => [
            'xxs' => 'xxs',
            'xs' => 'xs',
            'mob' => 'mob',
            'sm' => 'sm',
            'md' => 'md',
            'lg' => 'lg',
            'xl' => 'xl',
            'xxl' => 'xxl',
            'ultrawide' => 'ultrawide',
        ],
    ])
    ->addNumber('padding_top', [
        'label' => 'Padding Top',
        'instructions' => 'Set the top padding in rem.',
        'min' => 0,
        'max' => 20,
        'step' => 0.1,
        'append' => 'rem',
        'default_value' => 5,
    ])
    ->addNumber('padding_bottom', [
        'label' => 'Padding Bottom',
        'instructions' => 'Set the bottom padding in rem.',
        'min' => 0,
        'max' => 20,
        'step' => 0.1,
        'append' => 'rem',
        'default_value' => 5,
    ])
    ->endRepeater();

return $two_col_wysiwyg;
