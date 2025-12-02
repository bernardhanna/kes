<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

$application_form = new FieldsBuilder('application_form', [
    'label' => 'Application Form',
]);

$application_form
    ->addTab('Content')
        ->addText('heading', [
            'label' => 'Main Heading',
            'default_value' => 'Contact us'
        ])
        ->addSelect('heading_tag', [
            'label' => 'Main Heading Tag',
            'choices' => [
                'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6', 'p' => 'Paragraph', 'span' => 'Span'
            ],
            'default_value' => 'h2',
        ])
        ->addText('form_heading', [
            'label' => 'Form Heading',
            'default_value' => 'Apply for this position'
        ])
        ->addSelect('form_heading_tag', [
            'label' => 'Form Heading Tag',
            'choices' => [
                'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6', 'p' => 'Paragraph', 'span' => 'Span'
            ],
            'default_value' => 'h2',
        ])
        ->addWysiwyg('form_markup', [
            'label' => 'Form HTML (paste static form here)',
            'instructions' => 'Paste the static HTML form code here.',
            'toolbar' => 'basic',
            'media_upload' => 0,
            'wrapper' => ['class' => 'wp_editor'],
        ])
        ->addUrl('privacy_policy_url', [
            'label' => 'Privacy Policy URL',
            'default_value' => '#'
        ])
        ->addImage('image', [
            'label' => 'Side Image',
            'return_format' => 'id',
            'preview_size' => 'medium',
            'instructions' => 'Image displayed alongside the form'
        ])

    ->addTab('Email')
        ->addText('form_name', [
            'label' => 'Internal Form Name',
            'instructions' => 'Saved with each entry & used in email subject.',
            'default_value' => 'Application Form'
        ])
        ->addText('from_name', [
            'label' => 'From Name (override)',
            'instructions' => 'Optional. Leave empty to use Theme Options.',
        ])
        ->addEmail('from_email', [
            'label' => 'From Email (override)',
            'instructions' => 'Use an address on your domain (e.g. no-reply@domain.com). Leave empty to use Theme Options.',
        ])
        ->addText('email_to', [
            'label' => 'Send To',
            'instructions' => 'One or more addresses. Separate with commas or semicolons.',
            'placeholder' => 'hr@domain.com, manager@domain.com',
            'default_value' => get_option('admin_email'),
        ])
        ->addText('email_bcc', [
            'label' => 'BCC',
            'instructions' => 'Optional. Separate multiple with commas or semicolons.',
            'placeholder' => 'archive@domain.com; backup@domain.com',
        ])
        ->addText('email_subject', [
            'label' => 'Subject',
            'default_value' => 'New job application received'
        ])
        ->addTrueFalse('save_entries_to_db', [
            'label' => 'Save to DB?',
            'ui' => 1,
            'default_value' => 1
        ])

    ->addTab('Autoresponder')
        ->addTrueFalse('enable_autoresponder', [
            'label' => 'Enable?',
            'ui' => 1
        ])
        ->addText('autoresponder_subject', [
            'label' => 'Autoresponder Subject',
            'conditional_logic' => [[['field' => 'enable_autoresponder', 'operator' => '==', 'value' => 1]]],
            'default_value' => 'Thank you for your application'
        ])
        ->addWysiwyg('autoresponder_message', [
            'label' => 'Autoresponder Message',
            'conditional_logic' => [[['field' => 'enable_autoresponder', 'operator' => '==', 'value' => 1]]],
            'wrapper' => ['class' => 'wp_editor'],
            'default_value' => '<p>Thank you for your application. We will review it and get back to you soon.</p>'
        ])

    ->addTab('Design')
        ->addColorPicker('background_color', [
            'label' => 'Background Color',
            'default_value' => '#ffffff'
        ])
        ->addText('background_css', [
            'label' => 'Background CSS (optional)',
            'instructions' => 'Full CSS value, e.g. linear-gradient(270deg, rgba(242,245,247,0.83) 0%, rgba(242,245,247,0.30) 51.56%, #F2F5F7 100%)',
            'default_value' => '',
        ])
        ->addColorPicker('text_color', [
            'label' => 'Text Color',
            'default_value' => '#0a0a0a'
        ])

    ->addTab('Layout')
        ->addRepeater('padding_settings', [
            'label' => 'Padding Settings',
            'instructions' => 'Customize padding for different screen sizes.',
            'button_label' => 'Add Padding',
        ])
            ->addSelect('screen_size', [
                'label' => 'Screen Size',
                'choices' => [
                    'xxs' => 'xxs', 'xs' => 'xs', 'mob' => 'mob', 'sm' => 'sm', 'md' => 'md', 'lg' => 'lg', 'xl' => 'xl', 'xxl' => 'xxl', 'ultrawide' => 'ultrawide',
                ],
            ])
            ->addNumber('padding_top', [
                'label' => 'Padding Top',
                'min' => 0, 'max' => 20, 'step' => 0.1, 'append' => 'rem',
            ])
            ->addNumber('padding_bottom', [
                'label' => 'Padding Bottom',
                'min' => 0, 'max' => 20, 'step' => 0.1, 'append' => 'rem',
            ])
        ->endRepeater();

return $application_form;
