<?php
/**
 * Social icon URLs and helpers (mask-based hover recolor).
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Theme-bundled social icon SVG URL.
 *
 * @param string $icon facebook|x|linkedin|whatsapp|email|instagram|link
 */
function matrix_starter_get_social_icon_url(string $icon): string
{
    $allowed = ['facebook', 'x', 'linkedin', 'whatsapp', 'email', 'instagram', 'link'];

    if (! in_array($icon, $allowed, true)) {
        return '';
    }

    return get_theme_file_uri("assets/images/social/{$icon}.svg");
}

/**
 * Use the X icon when the CMS label refers to Twitter/X.
 */
function matrix_starter_resolve_social_icon_url(string $label, string $icon_url): string
{
    $label_key = strtolower(trim($label));
    $icon_key  = strtolower($icon_url);

    $is_x = $label_key === 'x'
        || str_contains($label_key, 'twitter')
        || str_contains($icon_key, 'socials-3');

    if ($is_x) {
        $x_icon = matrix_starter_get_social_icon_url('x');
        if ($x_icon) {
            return $x_icon;
        }
    }

    return $icon_url;
}
