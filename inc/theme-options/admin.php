<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

$fields = new FieldsBuilder('admin');

$fields
    ->addAccordion('admin_settings_start', [
        'label' => 'WordPress admin',
    ])
    ->addTrueFalse('hide_acf_admin_ui', [
        'label'         => 'Hide ACF from dashboard menu',
        'instructions'  => 'Removes the ACF plugin menus (Field Groups, Tools, etc.) from wp-admin. Content fields on pages and posts still work. Theme Options stays available so you can turn this off again.',
        'ui'            => 1,
        'default_value' => 0,
    ])
    ->addTrueFalse('hide_wp_mail_smtp_menu', [
        'label'         => 'Hide WP Mail SMTP from dashboard menu',
        'instructions'  => 'Removes the WP Mail SMTP sidebar item. You can still open it from Plugins → WP Mail SMTP → Settings.',
        'ui'            => 1,
        'default_value' => 0,
    ])
    ->addAccordion('admin_settings_end')->endpoint();

return $fields;
