<?php
/**
 * One-time migration: manual team_001 repeater rows → team CPT posts.
 */

declare(strict_types=1);

const MATRIX_STARTER_TEAM_CONTENT_VERSION = '1';

function matrix_starter_seed_team_from_manual_blocks(): void
{
    if (! function_exists('get_field') || ! post_type_exists('team')) {
        return;
    }

    $stored_version = (string) get_option('matrix_starter_team_content_version', '');
    if ($stored_version === MATRIX_STARTER_TEAM_CONTENT_VERSION) {
        return;
    }

    $seen_names = [];

    $pages = get_posts([
        'post_type'      => 'page',
        'post_status'    => ['publish', 'draft', 'private'],
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ]);

    foreach ($pages as $page_id) {
        $page_id = (int) $page_id;
        $blocks  = get_field('flexible_content_blocks', $page_id);
        if (! is_array($blocks)) {
            continue;
        }

        foreach ($blocks as $block) {
            if (! is_array($block) || ($block['acf_fc_layout'] ?? '') !== 'team_001') {
                continue;
            }

            $members = $block['team_members'] ?? null;
            if (! is_array($members) || $members === []) {
                continue;
            }

            foreach ($members as $member) {
                if (! is_array($member)) {
                    continue;
                }

                $name_key = strtolower(trim((string) ($member['name'] ?? '')));
                if ($name_key === '' || isset($seen_names[$name_key])) {
                    continue;
                }

                $seen_names[$name_key] = true;
                matrix_starter_team_upsert_from_manual_row($member, 'senior-management');
            }
        }
    }

    update_option('matrix_starter_team_content_version', MATRIX_STARTER_TEAM_CONTENT_VERSION, false);
}

add_action('init', 'matrix_starter_seed_team_from_manual_blocks', 99);
