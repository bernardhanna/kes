<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

$hero_slider = new FieldsBuilder('hero_slider', [
    'label' => 'Hero Slider',
]);

$hero_slider
    ->addTab('content_tab', ['label' => 'Content'])
        ->addRepeater('slides', [
            'label' => 'Slides',
            'button_label' => 'Add Slide',
            'layout' => 'block',
            'min' => 1,
        ])
            ->addSelect('slide_type', [
                'label' => 'Slide type',
                'choices' => [
                    'content' => 'Content (image + text + buttons)',
                    'map' => 'Map (pins from Projects with lat/long in sidebar)',
                ],
                'default_value' => 'content',
            ])
            ->addImage('background_image', [
                'label' => 'Background Image',
                'return_format' => 'array',
                'instructions' => 'Upload the background image for this slide.',
                'conditional_logic' => [['field' => 'slide_type', 'operator' => '==', 'value' => 'content']],
            ])
            ->addImage('background_image_mobile', [
                'label' => 'Background Image (Mobile)',
                'return_format' => 'array',
                'instructions' => 'Optional. Replaces the background image from 640px and down. Leave empty to use the main background image on all screens.',
                'conditional_logic' => [['field' => 'slide_type', 'operator' => '==', 'value' => 'content']],
            ])
            ->addTrueFalse('show_gradient', [
                'label' => 'Enable Gradient Overlay',
                'ui' => 1,
                'default_value' => 1,
                'conditional_logic' => [['field' => 'slide_type', 'operator' => '==', 'value' => 'content']],
            ])
            ->addNumber('map_center_lat', [
                'label' => 'Map center latitude',
                'instructions' => 'e.g. 53.349805',
                'step' => 'any',
                'min' => -90,
                'max' => 90,
                'default_value' => 53.349805,
                'conditional_logic' => [['field' => 'slide_type', 'operator' => '==', 'value' => 'map']],
            ])
            ->addNumber('map_center_lng', [
                'label' => 'Map center longitude',
                'instructions' => 'e.g. -6.26031',
                'step' => 'any',
                'min' => -180,
                'max' => 180,
                'default_value' => -6.26031,
                'conditional_logic' => [['field' => 'slide_type', 'operator' => '==', 'value' => 'map']],
            ])
            ->addNumber('map_zoom', [
                'label' => 'Map zoom',
                'instructions' => 'Initial zoom level (e.g. 6–10 for Ireland).',
                'min' => 1,
                'max' => 18,
                'default_value' => 7,
                'conditional_logic' => [['field' => 'slide_type', 'operator' => '==', 'value' => 'map']],
            ])
            ->addSelect('map_tile_provider', [
                'label' => 'Map style (tiles)',
                'choices' => [
                    'osm' => 'OpenStreetMap (default)',
                    'cartodb_voyager' => 'CartoDB Voyager (blue water)',
                    'jawg-light' => 'Jawg Light (requires API key)',
                    'jawg-dark' => 'Jawg Dark (requires API key)',
                    'jawg-custom' => 'Jawg Custom raster (requires API key + raster style ID)',
                    'jawg-vector' => 'Jawg Vector / MapLibre (requires API key + vector style ID)',
                ],
                'default_value' => 'cartodb_voyager',
                'conditional_logic' => [['field' => 'slide_type', 'operator' => '==', 'value' => 'map']],
            ])
            ->addText('map_tile_api_key', [
                'label' => 'Jawg API key (optional)',
                'instructions' => 'Required for Jawg styles. Leave empty to use OpenStreetMap (raster only).',
                'conditional_logic' => [['field' => 'slide_type', 'operator' => '==', 'value' => 'map']],
            ])
            ->addText('map_jawg_style_id', [
                'label' => 'Jawg custom raster style ID',
                'instructions' => 'Required for Custom raster. From Jawg Lab → your style → Leaflet section.',
                'conditional_logic' => [
                    ['field' => 'slide_type', 'operator' => '==', 'value' => 'map'],
                    ['field' => 'map_tile_provider', 'operator' => '==', 'value' => 'jawg-custom'],
                ],
            ])
            ->addText('map_jawg_vector_style_id', [
                'label' => 'Jawg vector style ID (UUID)',
                'instructions' => 'Required for Jawg Vector. From Jawg Lab → your style → MapLibre/Vector section. The UUID in the style URL (e.g. 0cdb0ece-66e3-4aca-818a-8cf1a3855db6).',
                'conditional_logic' => [
                    ['field' => 'slide_type', 'operator' => '==', 'value' => 'map'],
                    ['field' => 'map_tile_provider', 'operator' => '==', 'value' => 'jawg-vector'],
                ],
            ])
            ->addTrueFalse('map_style_blue', [
                'label' => 'Apply blue tint (map styling)',
                'ui' => 1,
                'default_value' => 1,
                'instructions' => 'Blue land/water style similar to reference. Uses CSS filter on map.',
                'conditional_logic' => [['field' => 'slide_type', 'operator' => '==', 'value' => 'map']],
            ])
            ->addTrueFalse('map_show_gradient', [
                'label' => 'Show gradient overlay on map',
                'ui' => 1,
                'default_value' => 1,
                'conditional_logic' => [['field' => 'slide_type', 'operator' => '==', 'value' => 'map']],
            ])
            ->addImage('map_marker_image', [
                'label' => 'Custom map marker image',
                'return_format' => 'array',
                'instructions' => 'Optional. Upload a custom pin/marker for this map. Leave empty to use the default location pin.',
                'conditional_logic' => [['field' => 'slide_type', 'operator' => '==', 'value' => 'map']],
            ])
            ->addSelect('title_tag', [
                'label' => 'Title Tag',
                'choices' => [
                    'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3',
                    'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6',
                    'span' => 'span', 'p' => 'p',
                ],
                'default_value' => 'h1',
                'conditional_logic' => [['field' => 'slide_type', 'operator' => '==', 'value' => 'content']],
            ])
            ->addWysiwyg('title', [
                'label' => 'Title',
                'media_upload' => 0,
                'tabs' => 'all',
                'delay' => 0,
                'instructions' => 'You can include <span> for emphasis if desired.',
                'conditional_logic' => [['field' => 'slide_type', 'operator' => '==', 'value' => 'content']],
            ])
            ->addWysiwyg('description', [
                'label' => 'Description',
                'media_upload' => 0,
                'tabs' => 'all',
                'delay' => 0,
                'conditional_logic' => [['field' => 'slide_type', 'operator' => '==', 'value' => 'content']],
            ])
            ->addRepeater('buttons', [
                'label' => 'Buttons',
                'button_label' => 'Add Button',
                'layout' => 'table',
                'min' => 0,
                'max' => 2,
                'conditional_logic' => [['field' => 'slide_type', 'operator' => '==', 'value' => 'content']],
            ])
                ->addLink('button_link', [
                    'label' => 'Button Link',
                    'return_format' => 'array',
                ])
                ->addSelect('button_style', [
                    'label' => 'Button Style',
                    'choices' => [
                        'primary' => 'Primary',
                        'secondary' => 'Secondary',
                    ],
                    'default_value' => 'primary',
                ])
            ->endRepeater()
        ->endRepeater()

    ->addTab('design_tab', ['label' => 'Design'])
        ->addSelect('text_color', [
            'label' => 'Text Color',
            'choices' => [
                'text-white' => 'White',
                'text-black' => 'Black',
                'text-gray-900' => 'Gray 900',
            ],
            'default_value' => 'text-white',
        ])
        ->addSelect('overlay_from', [
            'label' => 'Gradient From',
            'choices' => [
                'from-blue-dark/90' => 'Blue Dark / 90',
                'from-black/80'     => 'Black / 80',
                'from-gray-900/80'  => 'Gray 900 / 80',
            ],
            'default_value' => 'from-blue-dark/90',
            'conditional_logic' => [['field' => 'show_gradient', 'operator' => '==', 'value' => 1]],
        ])
        ->addSelect('overlay_via', [
            'label' => 'Gradient Via',
            'choices' => [
                'via-blue-dark/50' => 'Blue Dark / 50',
                'via-black/50'     => 'Black / 50',
                'via-gray-900/50'  => 'Gray 900 / 50',
                'via-transparent'  => 'Transparent',
            ],
            'default_value' => 'via-blue-dark/50',
            'conditional_logic' => [['field' => 'show_gradient', 'operator' => '==', 'value' => 1]],
        ])
        ->addSelect('overlay_to', [
            'label' => 'Gradient To',
            'choices' => [
                'to-transparent'   => 'Transparent',
                'to-black/0'       => 'Black / 0',
                'to-blue-dark/0'   => 'Blue Dark / 0',
            ],
            'default_value' => 'to-transparent',
            'conditional_logic' => [['field' => 'show_gradient', 'operator' => '==', 'value' => 1]],
        ])
        ->addImage('arrow_prev', [
            'label' => 'Arrow Previous (Image)',
            'return_format' => 'array',
            'instructions' => 'Upload the left/previous arrow image.',
        ])
        ->addImage('arrow_next', [
            'label' => 'Arrow Next (Image)',
            'return_format' => 'array',
            'instructions' => 'Upload the right/next arrow image.',
        ])
        ->addTrueFalse('show_dots', [
            'label' => 'Show Indicators (Dots)',
            'ui' => 1,
            'default_value' => 1,
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
                'min' => 0, 'max' => 20, 'step' => 0.1, 'append' => 'rem',
            ])
            ->addNumber('padding_bottom', [
                'label' => 'Padding Bottom',
                'instructions' => 'Set the bottom padding in rem.',
                'min' => 0, 'max' => 20, 'step' => 0.1, 'append' => 'rem',
            ])
        ->endRepeater();

return $hero_slider;
