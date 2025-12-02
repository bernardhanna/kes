<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

$contact_form = new FieldsBuilder('contact_form', [
    'label' => 'Contact Form with Information',
]);

$contact_form
    ->addTab('Content')
        ->addText('heading', [
            'label' => 'Main Heading',
            'default_value' => 'Contact Us'
        ])
        ->addSelect('heading_tag', [
            'label' => 'Main Heading Tag',
            'choices' => [
                'h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6',
                'p' => 'Paragraph',
                'span' => 'Span'
            ],
            'default_value' => 'h2',
        ])
        ->addTextarea('description', [
            'label' => 'Description Text',
            'default_value' => 'Request a call back by completing this form below, or just get in touch about vacancies, opportunities, and collaboration.',
            'rows' => 3
        ])

        // Contact Information Section
        ->addText('contact_heading', [
            'label' => 'Contact Section Heading',
            'default_value' => 'Contact our office'
        ])
        ->addSelect('contact_heading_tag', [
            'label' => 'Contact Heading Tag',
            'choices' => [
                'h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6',
                'p' => 'Paragraph',
                'span' => 'Span'
            ],
            'default_value' => 'h3',
        ])
        ->addText('office_name', [
            'label' => 'Office Name',
            'default_value' => 'Sligo Office'
        ])
        ->addTextarea('address', [
            'label' => 'Office Address',
            'default_value' => '28, Foster Avenue, Blackrock, D04 A021, Ireland',
            'rows' => 3
        ])
        ->addText('phone', [
            'label' => 'Phone Number',
            'default_value' => '+353 83 045 87 46'
        ])
        ->addEmail('email', [
            'label' => 'Email Address',
            'default_value' => 'info@Kes.ie'
        ])
        ->addWysiwyg('business_hours', [
            'label' => 'Business Hours',
            'wrapper' => ['class' => 'wp_editor'],
            'media_upload' => 0,
            'tabs' => 'visual',
            'default_value' => '<p>Mon - Fri: 09:00 - 20:00<br>Sat - Sun: 10:00 - 17:00<br>Bank Holidays: 10:00 – 16:00</p>'
        ])

        // Form Configuration
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
        ->addUrl('terms_conditions_url', [
            'label' => 'Terms & Conditions URL',
            'default_value' => '#'
        ])

    ->addTab('Email')
        ->addText('form_name', [
            'label' => 'Internal Form Name',
            'instructions' => 'Saved with each entry & used in email subject.',
            'default_value' => 'Contact Form'
        ])
        ->addText('from_name', [
            'label' => 'From Name (override)',
            'instructions' => 'Optional. Leave empty to use Theme Options.',
        ])
        ->addEmail('from_email', [
            'label' => 'From Email (override)',
            'instructions' => 'Use an address on your domain. Leave empty to use Theme Options.',
        ])
        ->addText('email_to', [
            'label' => 'Send To',
            'instructions' => 'One or more addresses. Separate with commas or semicolons.',
            'placeholder' => 'name@domain.ie, other@domain.ie',
            'default_value' => get_option('admin_email'),
        ])
        ->addText('email_bcc', [
            'label' => 'BCC',
            'instructions' => 'Optional. Separate multiple with commas or semicolons.',
            'placeholder' => 'first@domain.ie; second@domain.ie',
        ])
        ->addText('email_subject', [
            'label' => 'Subject',
            'default_value' => 'Website contact form enquiry'
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
            'default_value' => 'Thank you for your message'
        ])
        ->addWysiwyg('autoresponder_message', [
            'label' => 'Autoresponder Message',
            'conditional_logic' => [[['field' => 'enable_autoresponder', 'operator' => '==', 'value' => 1]]],
            'wrapper' => ['class' => 'wp_editor'],
            'default_value' => '<p>Thank you for contacting us. We will get back to you as soon as possible.</p>'
        ])

    ->addTab('Design')
        ->addColorPicker('background_color', [
            'label' => 'Background Color',
            'default_value' => '#ffffff'
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
                'min' => 0,
                'max' => 20,
                'step' => 0.1,
                'append' => 'rem',
            ])
            ->addNumber('padding_bottom', [
                'label' => 'Padding Bottom',
                'min' => 0,
                'max' => 20,
                'step' => 0.1,
                'append' => 'rem',
            ])
        ->endRepeater();

return $contact_form;
