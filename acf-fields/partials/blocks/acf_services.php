<?php
// File: acf/services_grid.php
// Flexible Content block: Services 2 Column Grid (with per-item width control)

use StoutLogic\AcfBuilder\FieldsBuilder;

$services_grid = new FieldsBuilder('services_grid', [
    'label' => 'Services 2 Column Grid',
]);

$services_grid
    ->addTab('Content', ['label' => 'Content'])

        ->addRepeater('services', [
            'label'         => 'Services',
            'instructions'  => 'Add service items. Each item renders as a card. You can make an item span both columns.',
            'button_label'  => 'Add Service',
            'layout'        => 'block',
            'min'           => 0,
        ])
            ->addImage('image', [
                'label'         => 'Service Image',
                'instructions'  => 'Upload/select a service image.',
                'return_format' => 'id',
                'preview_size'  => 'medium',
            ])
            ->addText('title', [
                'label'         => 'Service Title',
                'instructions'  => 'Title for the service item.',
                'default_value' => 'Service Title',
                'required'      => 1,
            ])
            ->addSelect('title_tag', [
                'label'         => 'Title HTML Tag',
                'instructions'  => 'Choose the HTML tag for the service title.',
                'choices'       => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'p'  => 'Paragraph',
                    'span' => 'Span',
                ],
                'default_value' => 'h3',
            ])
            ->addWysiwyg('description', [
                'label'         => 'Service Description',
                'instructions'  => 'Describe the service. (WYSIWYG output uses .wp_editor class in the template.)',
                'default_value' => 'Service description goes here. Provide details about what this service offers.',
                'media_upload'  => 0,
                'tabs'          => 'all',
                'toolbar'       => 'basic',
                'wrapper'       => ['class' => 'wp_editor'],
            ])
            ->addLink('link', [
                'label'         => 'Service Link (optional)',
                'instructions'  => 'When provided, the whole card becomes a link.',
                'return_format' => 'array', // ACF Link Array
            ])
            ->addColorPicker('underline_color', [
                'label'         => 'Title Underline Color',
                'instructions'  => 'Color for the small underline beneath the title.',
                'default_value' => '#06b6d4',
            ])
            ->addSelect('width', [
                'label'         => 'Item Width',
                'instructions'  => 'For ≥ md screens: make this card half-width (default) or full-width (span both columns).',
                'choices'       => [
                    'half' => 'Half (1/2 column)',
                    'full' => 'Full (span both columns)',
                ],
                'default_value' => 'half',
            ])
        ->endRepeater()

    ->addTab('Design', ['label' => 'Design'])
        ->addColorPicker('background_color', [
            'label'         => 'Section Background Color',
            'instructions'  => 'Background color for the entire section.',
            'default_value' => '#f9fafb',
        ])

    ->addTab('Layout', ['label' => 'Layout'])
        ->addRepeater('padding_settings', [
            'label'         => 'Padding Settings',
            'instructions'  => 'Customize top/bottom padding (in rem) per breakpoint. These will be applied to the container.',
            'button_label'  => 'Add Screen Size Padding',
            'layout'        => 'table',
        ])
            ->addSelect('screen_size', [
                'label'   => 'Screen Size',
                'choices' => [
                    // Use exact keys per your global convention
                    'xxs'       => 'xxs',
                    'xs'        => 'xs',
                    'mob'       => 'mob',
                    'sm'        => 'sm',
                    'md'        => 'md',
                    'lg'        => 'lg',
                    'xl'        => 'xl',
                    'xxl'       => 'xxl',
                    'ultrawide' => 'ultrawide',
                ],
                'required' => 1,
            ])
            ->addNumber('padding_top', [
                'label'         => 'Padding Top',
                'instructions'  => 'Top padding in rem.',
                'min'           => 0,
                'max'           => 20,
                'step'          => 0.1,
                'append'        => 'rem',
                'default_value' => 5,
            ])
            ->addNumber('padding_bottom', [
                'label'         => 'Padding Bottom',
                'instructions'  => 'Bottom padding in rem.',
                'min'           => 0,
                'max'           => 20,
                'step'          => 0.1,
                'append'        => 'rem',
                'default_value' => 5,
            ])
        ->endRepeater();

return $services_grid;
