<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

$gallery = new FieldsBuilder('gallery', [
    'label' => 'Gallery',
]);

$gallery
    ->addTab('Content', ['label' => 'Content'])
    ->addRepeater('gallery_images', [
        'label' => 'Gallery Images',
        'instructions' => 'Add images to the gallery. The first two images will appear in the left column, and the third image will appear in the right column.',
        'button_label' => 'Add Image',
        'min' => 1,
        'max' => 10,
        'layout' => 'block',
    ])
        ->addImage('image', [
            'label' => 'Image',
            'instructions' => 'Select an image for the gallery.',
            'return_format' => 'id',
            'preview_size' => 'medium',
            'library' => 'all',
        ])
    ->endRepeater()

    ->addTab('Layout', ['label' => 'Layout'])
    ->addRepeater('padding_settings', [
        'label' => 'Padding Settings',
        'instructions' => 'Customize padding for different screen sizes.',
        'button_label' => 'Add Screen Size Padding',
        'min' => 0,
        'max' => 10,
        'layout' => 'table',
    ])
        ->addSelect('screen_size', [
            'label' => 'Screen Size',
            'instructions' => 'Select the screen size for this padding setting.',
            'choices' => [
                'xxs' => 'XXS (Extra Extra Small)',
                'xs' => 'XS (Extra Small)',
                'mob' => 'Mobile',
                'sm' => 'SM (Small)',
                'md' => 'MD (Medium)',
                'lg' => 'LG (Large)',
                'xl' => 'XL (Extra Large)',
                'xxl' => 'XXL (Extra Extra Large)',
                'ultrawide' => 'Ultrawide',
            ],
            'default_value' => 'md',
            'allow_null' => 0,
            'multiple' => 0,
        ])
        ->addNumber('padding_top', [
            'label' => 'Padding Top',
            'instructions' => 'Set the top padding in rem units.',
            'min' => 0,
            'max' => 20,
            'step' => 0.1,
            'append' => 'rem',
            'default_value' => 5,
        ])
        ->addNumber('padding_bottom', [
            'label' => 'Padding Bottom',
            'instructions' => 'Set the bottom padding in rem units.',
            'min' => 0,
            'max' => 20,
            'step' => 0.1,
            'append' => 'rem',
            'default_value' => 5,
        ])
    ->endRepeater();

return $gallery;
