<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

$project_details = new FieldsBuilder('project_details', [
    'label' => 'Project Details Section',
]);

$project_details
    ->addTab('Content', ['label' => 'Content'])
    ->addText('heading', [
        'label' => 'Project Heading',
        'instructions' => 'Enter the main heading for the project section.',
        'default_value' => 'About Project:',
        'required' => 1,
    ])
    ->addSelect('heading_tag', [
        'label' => 'Heading Tag',
        'instructions' => 'Select the HTML tag for the heading.',
        'choices' => [
            'h1' => 'H1',
            'h2' => 'H2',
            'h3' => 'H3',
            'h4' => 'H4',
            'h5' => 'H5',
            'h6' => 'H6',
            'p' => 'Paragraph',
            'span' => 'Span',
        ],
        'default_value' => 'h2',
        'required' => 1,
    ])
    ->addWysiwyg('description', [
        'label' => 'Project Description',
        'instructions' => 'Enter the detailed description of the project.',
        'default_value' => 'A full time Commissioning management team onsite for 12 months.<br>Commissioning of all HVAC systems in both the Data centre and support areas.<br>Generation and execution of commissioning test packs.',
        'media_upload' => 0,
        'tabs' => 'all',
        'toolbar' => 'full',
        'required' => 1,
    ])
    ->addImage('project_image', [
        'label' => 'Project Image',
        'instructions' => 'Upload an image representing the project.',
        'return_format' => 'id',
        'preview_size' => 'medium',
        'library' => 'all',
    ])
    ->addText('status', [
        'label' => 'Project Status',
        'instructions' => 'Enter the current status of the project.',
        'default_value' => 'Project Complete',
        'placeholder' => 'e.g., Project Complete, In Progress, Planning',
    ])
    ->addText('completion_year', [
        'label' => 'Completion Year',
        'instructions' => 'Enter the year the project was completed.',
        'default_value' => '2024',
        'placeholder' => 'e.g., 2024',
    ])
    ->addWysiwyg('size_details', [
        'label' => 'Size Details',
        'instructions' => 'Enter the size specifications of the project.',
        'default_value' => 'Area: 2500 square feet<br>Height: 143 meters<br>Stories: 42',
        'media_upload' => 0,
        'tabs' => 'visual,text',
        'toolbar' => 'basic',
    ])
    ->addText('client', [
        'label' => 'Client',
        'instructions' => 'Enter the client name or organization.',
        'default_value' => 'Confidential Data Centre, Dublin',
        'placeholder' => 'e.g., Company Name, Location',
    ])
    ->addWysiwyg('team_details', [
        'label' => 'Team Details',
        'instructions' => 'Enter information about the project team.',
        'default_value' => 'Lead Engineer: Joe Blogs<br>PM: Joe Blogs',
        'media_upload' => 0,
        'tabs' => 'visual,text',
        'toolbar' => 'basic',
    ])

    ->addTab('Design', ['label' => 'Design'])
    ->addColorPicker('background_color', [
        'label' => 'Background Color',
        'instructions' => 'Choose the background color for the section.',
        'default_value' => '#FFFFFF',
    ])

    ->addTab('Layout', ['label' => 'Layout'])
    ->addRepeater('padding_settings', [
        'label' => 'Padding Settings',
        'instructions' => 'Customize padding for different screen sizes.',
        'button_label' => 'Add Screen Size Padding',
        'layout' => 'table',
        'min' => 0,
        'max' => 10,
    ])
    ->addSelect('screen_size', [
        'label' => 'Screen Size',
        'instructions' => 'Select the screen size for this padding setting.',
        'choices' => [
            'xxs' => 'XXS',
            'xs' => 'XS',
            'mob' => 'Mobile',
            'sm' => 'Small',
            'md' => 'Medium',
            'lg' => 'Large',
            'xl' => 'Extra Large',
            'xxl' => 'XXL',
            'ultrawide' => 'Ultra Wide',
        ],
        'required' => 1,
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

return $project_details;
