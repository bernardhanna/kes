<?php
/**
 * Theme-wide accessibility helpers (WCAG 2.1 AA).
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Skip link — first focusable element after body open (2.4.1 Bypass Blocks).
 */
function matrix_starter_skip_link(): void
{
    echo '<a class="skip-link btn" href="#main-content">' . esc_html__('Skip to main content', 'matrix') . '</a>';
}
add_action('wp_body_open', 'matrix_starter_skip_link', 5);

/**
 * Attribute string for primary main landmark.
 */
function matrix_starter_main_id_attr(): string
{
    return 'id="main-content"';
}

/**
 * Live region markup for status messages (4.1.3 Status Messages).
 */
function matrix_starter_a11y_live_region(string $id = 'a11y-live-status'): void
{
    printf(
        '<div id="%1$s" class="sr-only" role="status" aria-live="polite" aria-atomic="true"></div>',
        esc_attr($id)
    );
}
