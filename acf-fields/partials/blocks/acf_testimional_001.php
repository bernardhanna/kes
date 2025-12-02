<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

$testimonial_001 = new FieldsBuilder('testimonial_001', [
    'label' => 'Testimonial Section',
]);

$testimonial_001

// Content Tab
->addTab('Content', ['placement' => 'top'])
->addRepeater('testimonials', [
    'label' => 'Testimonials',
    'instructions' => 'Add one or more testimonials. Multiple testimonials will create a slider.',
    'min' => 1,
    'button_label' => 'Add Testimonial',
])
    ->addWysiwyg('quote', [
        'label' => 'Testimonial Quote',
        'instructions' => 'The main testimonial text.',
        'default_value' => '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do"',
        'media_upload' => 0,
        'tabs' => 'visual,text',
        'toolbar' => 'basic',
    ])
    ->addTextarea('author', [
        'label' => 'Author Name & Title',
        'instructions' => 'Author name and their title/company.',
        'placeholder' => 'Name Surname Lead marketing specialist and company logo below',
        'default_value' => 'Name Surname Lead marketing specialist and company logo below',
        'rows' => 3,
    ])
    ->addImage('profile_image', [
        'label' => 'Profile Image',
        'instructions' => 'Upload the author\'s profile image.',
        'return_format' => 'id',
        'preview_size' => 'medium',
    ])
    ->addTrueFalse('show_quote_icon', [
        'label' => 'Show Quote Icon',
        'instructions' => 'Display the decorative quote marks.',
        'ui' => 1,
        'default_value' => 1,
    ])
->endRepeater()

// Design Tab
->addTab('Design', ['placement' => 'top'])
->addTrueFalse('use_gradient', [
    'label' => 'Use Gradient Background',
    'instructions' => 'Toggle between solid color and gradient background.',
    'ui' => 1,
    'default_value' => 0,
])
->addColorPicker('background_color', [
    'label' => 'Background Color',
    'instructions' => 'Choose a solid background color.',
    'default_value' => '#1D2939',
    'conditional_logic' => [
        [
            [
                'field' => 'use_gradient',
                'operator' => '!=',
                'value' => '1',
            ],
        ],
    ],
])
->addText('background_gradient', [
    'label' => 'Background Gradient',
    'instructions' => 'Enter a CSS gradient (e.g., linear-gradient(135deg, #667eea 0%, #764ba2 100%))',
    'placeholder' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
    'conditional_logic' => [
        [
            [
                'field' => 'use_gradient',
                'operator' => '==',
                'value' => '1',
            ],
        ],
    ],
])

// Layout Tab
->addTab('Layout', ['placement' => 'top'])
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

return $testimonial_001;