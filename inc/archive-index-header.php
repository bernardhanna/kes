<?php
/**
 * Renders the index/archive title band (heading, accent bar, optional WYSIWYG intro).
 *
 * @param array $cfg {
 *   @type string $heading      Visible title (empty = optional fallback in caller).
 *   @type string $heading_tag  h1–h6.
 *   @type string $intro        HTML from WYSIWYG.
 *   @type string $bg_color     Section background (hex).
 *   @type string $accent_color         Underline bar color (hex).
 *   @type string $inner_wrapper_class Optional. Full class string for the inner title container; default pt-8 wrapper.
 * }
 */
function matrix_starter_render_archive_index_header(array $cfg): void
{
    $cfg = wp_parse_args($cfg, [
        'heading'               => '',
        'heading_tag'           => 'h2',
        'intro'                 => '',
        'bg_color'              => '#ffffff',
        'accent_color'          => '#00ACD8',
        'bg_image_url'          => '',
        'section_class'         => '',
        'inner_wrapper_class'   => '',
    ]);

    $heading = trim((string) $cfg['heading']);
    $intro   = trim((string) $cfg['intro']);

    if ($heading === '' && $intro === '') {
        return;
    }

    $allowed = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
    $tag     = in_array(strtolower($cfg['heading_tag']), $allowed, true)
        ? strtolower($cfg['heading_tag'])
        : 'h2';

    $section_id      = 'title-section-' . wp_generate_password(6, false, false);
    $bg_color        = $cfg['bg_color'] ?: '#ffffff';
    $accent          = $cfg['accent_color'] ?: '#00ACD8';
    $bg_image_url    = ! empty($cfg['bg_image_url']) ? esc_url($cfg['bg_image_url']) : '';
    $section_class   = trim((string) $cfg['section_class']);

    $inner_wrapper_class = trim((string) $cfg['inner_wrapper_class']);
    if ($inner_wrapper_class === '') {
        $inner_wrapper_class = 'flex flex-col items-center pt-8 pb-5 mx-auto w-full max-w-container max-xl:px-5';
    }

    $aria_labelledby = '';
    if ($heading !== '') {
        $aria_labelledby = $section_id . '-heading';
    }

    include locate_template('template-parts/archive/index-title-section.php');
}
