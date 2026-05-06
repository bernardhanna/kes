<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

$request_callback = new FieldsBuilder('request_callback_settings', [
  'label' => 'Request a callback',
]);

$request_callback
  ->addTrueFalse('callback_modal_enabled', [
    'label'         => 'Enable callback modal',
    'instructions'  => 'When on, the modal is loaded in the footer. Add CSS class <code>request-call</code> to the menu item (<code>&lt;li&gt;</code>) or link, or use <code>data-request-callback</code>. The link URL is ignored when the modal opens.',
    'ui'            => 1,
    'default_value' => 1,
  ])
  ->addText('callback_form_title', [
    'label'         => 'Form title',
    'default_value' => 'Request a call back',
  ])
  ->addTextarea('callback_form_intro', [
    'label'         => 'Intro text',
    'rows'          => 3,
    'default_value' => "Request a call back by completing this form below and we'll get back to you asap.",
  ])
  ->addUrl('callback_privacy_policy_url', [
    'label'         => 'Privacy policy URL',
    'instructions'  => 'Used in the consent checkbox label.',
  ])
  ->addEmail('callback_notify_to', [
    'label'         => 'Send submissions to',
    'instructions'  => 'Primary recipient for callback requests.',
    'required'      => 0,
  ])
  ->addText('callback_notify_cc', [
    'label'         => 'CC',
    'instructions'  => 'Optional. Comma-separated email addresses.',
  ])
  ->addText('callback_notify_bcc', [
    'label'         => 'BCC',
    'instructions'  => 'Optional. Comma-separated email addresses.',
  ])
  ->addText('callback_email_subject', [
    'label'         => 'Email subject',
    'default_value' => 'New callback request',
  ])
  ->addTrueFalse('callback_autoresponder_enabled', [
    'label'         => 'Send auto-reply to the visitor',
    'ui'            => 1,
    'default_value' => 0,
  ])
  ->addText('callback_autoresponder_subject', [
    'label'             => 'Auto-reply subject',
    'default_value'     => 'We received your request',
    'conditional_logic' => [
      [['field' => 'callback_autoresponder_enabled', 'operator' => '==', 'value' => 1]],
    ],
  ])
  ->addWysiwyg('callback_autoresponder_message', [
    'label'             => 'Auto-reply message (HTML)',
    'instructions'      => 'Shown in the visitor email. Optional logo is inserted above when set.',
    'wrapper'           => ['class' => 'wp_editor'],
    'default_value'     => '<p>Thank you for contacting us. We will get back to you as soon as possible.</p>',
    'conditional_logic' => [
      [['field' => 'callback_autoresponder_enabled', 'operator' => '==', 'value' => 1]],
    ],
  ])
  ->addImage('callback_autoresponder_logo', [
    'label'             => 'Logo in auto-reply',
    'instructions'      => 'Optional image at the top of the auto-reply email.',
    'return_format'     => 'id',
    'preview_size'      => 'medium',
    'conditional_logic' => [
      [['field' => 'callback_autoresponder_enabled', 'operator' => '==', 'value' => 1]],
    ],
  ]);

return $request_callback;
