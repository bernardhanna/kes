<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

$careers = new FieldsBuilder('careers_settings', [
  'label' => 'Careers',
]);

$careers
  ->addEmail('careers_applications_email', [
    'label'         => 'Applications email',
    'instructions'  => 'Job application form submissions are sent to this address.',
    'required'      => 0,
    'placeholder'   => 'careers@example.com',
  ])
  ->addText('careers_application_subject', [
    'label'         => 'Email subject',
    'instructions'  => 'Use {job_title} for the role name.',
    'default_value' => 'Application: {job_title}',
  ])
  ->addTextarea('careers_application_intro', [
    'label'         => 'Default intro text',
    'instructions'  => 'Shown under “{Job title}: Application form” unless overridden on the job.',
    'rows'          => 3,
    'new_lines'     => 'br',
  ])
  ->addImage('careers_application_image', [
    'label'         => 'Default side image',
    'instructions'  => 'Displayed next to the application form (Figma right column).',
    'return_format' => 'id',
    'preview_size'  => 'medium',
  ])
  ->addUrl('careers_privacy_policy_url', [
    'label'         => 'Privacy policy URL',
    'default_value' => '',
  ])
  ->addNumber('careers_cv_max_mb', [
    'label'         => 'CV max file size (MB)',
    'default_value' => 5,
    'min'           => 1,
    'max'           => 50,
    'step'          => 1,
  ])
  ->addTrueFalse('careers_save_applications', [
    'label'         => 'Save applications to database',
    'ui'            => 1,
    'default_value' => 1,
  ])
  ->addTrueFalse('careers_enable_autoresponder', [
    'label' => 'Send applicant confirmation email',
    'ui'    => 1,
  ])
  ->addText('careers_autoresponder_subject', [
    'label'             => 'Autoresponder subject',
    'default_value'     => 'Thank you for your application',
    'conditional_logic' => [[['field' => 'careers_enable_autoresponder', 'operator' => '==', 'value' => 1]]],
  ])
  ->addWysiwyg('careers_autoresponder_message', [
    'label'             => 'Autoresponder message',
    'toolbar'           => 'basic',
    'media_upload'      => 0,
    'conditional_logic' => [[['field' => 'careers_enable_autoresponder', 'operator' => '==', 'value' => 1]]],
    'default_value'     => '<p>Thank you for your application. We will review it and be in touch soon.</p>',
  ]);

return $careers;
