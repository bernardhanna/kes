<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

$careers = new FieldsBuilder('careers_settings', [
  'label' => 'Careers',
]);

$careers
  ->addEmail('careers_apply_email', [
    'label'         => 'Job applications email',
    'instructions'  => 'Apply now on Jobs flexible blocks opens the visitor mail client to this address, with the job title in the subject line. If empty, the button links to the job post instead.',
    'required'      => 0,
    'placeholder'   => 'careers@example.com',
  ]);

return $careers;
