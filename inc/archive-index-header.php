<?php
/**
 * Renders the index/archive title band (heading, accent bar, optional WYSIWYG intro).
 *
 * @param array $cfg {
 *   @type string $heading      Visible title (empty = optional fallback in caller).
 *   @type string $heading_tag  h1–h6.
 *   @type string $intro        HTML from WYSIWYG.
 *   @type string $bg_color     Section background (hex).
 *   @type string $accent_color Underline bar color (hex).
 * }
 */
function matrix_starter_render_archive_index_header(array $cfg): void
{
    $cfg = wp_parse_args($cfg, [
        'heading'        => '',
        'heading_tag'    => 'h2',
        'intro'          => '',
        'bg_color'       => '#ffffff',
        'accent_color'   => '#00ACD8',
        'bg_image_url'   => '',
        'section_class'  => '',
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

    $aria_labelledby = '';
    if ($heading !== '') {
        $aria_labelledby = $section_id . '-heading';
    }

    include locate_template('template-parts/archive/index-title-section.php');
}
