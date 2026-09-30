<?php
/**
 * Service CPT default copy and one-time sync to ACF flexible content.
 *
 * Source of truth for editors: docs/services/*.md (same structure).
 */

declare(strict_types=1);

const MATRIX_STARTER_SERVICE_CONTENT_VERSION = '1';

/**
 * @return array<string, array{excerpt: string, blocks: callable(array): array}>
 */
function matrix_starter_service_content_definitions(): array
{
    return [
        'flushing-and-water-treatment' => [
            'excerpt' => __(
                'Fully integrated pipework flushing and water treatment for mission-critical cooling systems—exceeding BSRIA, ASHRAE, and international standards.',
                'matrix-starter'
            ),
            'blocks'  => 'matrix_starter_service_blocks_flushing',
        ],
        'commissioning'                => [
            'excerpt' => __(
                'Independent TAB for air and water systems—validating design intent, thermal stability, and efficiency in line with BSRIA, CIBSE, and ASHRAE.',
                'matrix-starter'
            ),
            'blocks'  => 'matrix_starter_service_blocks_commissioning',
        ],
        'heat-load-testing'            => [
            'excerpt' => __(
                'Large-scale thermal load banks and staged testing to prove cooling and electrical resilience for data centres, AI, and HPC facilities.',
                'matrix-starter'
            ),
            'blocks'  => 'matrix_starter_service_blocks_heat_load',
        ],
    ];
}

function matrix_starter_seed_service_content(): void
{
    if (! function_exists('get_field') || ! function_exists('update_field')) {
        return;
    }

    $stored_version = (string) get_option('matrix_starter_service_content_version', '');
    $version_bump   = $stored_version !== MATRIX_STARTER_SERVICE_CONTENT_VERSION;

    foreach (matrix_starter_service_content_definitions() as $slug => $definition) {
        $post = get_page_by_path($slug, OBJECT, 'services');
        if (! $post instanceof WP_Post) {
            continue;
        }

        if (! $version_bump && ! matrix_starter_service_has_placeholder_content($post)) {
            continue;
        }

        $existing = get_field('flexible_content_blocks', $post->ID);
        $existing = is_array($existing) ? $existing : [];

        $blocks_callback = $definition['blocks'];
        $blocks          = is_callable($blocks_callback)
            ? (array) call_user_func($blocks_callback, $existing)
            : [];

        if ($blocks !== []) {
            update_field('flexible_content_blocks', $blocks, $post->ID);
        }

        if ($definition['excerpt'] !== '') {
            wp_update_post(
                [
                    'ID'           => $post->ID,
                    'post_excerpt' => $definition['excerpt'],
                ],
                true
            );
        }
    }

    if ($version_bump) {
        update_option('matrix_starter_service_content_version', MATRIX_STARTER_SERVICE_CONTENT_VERSION, false);
    }
}
add_action('init', 'matrix_starter_seed_service_content', 99);

function matrix_starter_service_has_placeholder_content(WP_Post $post): bool
{
    $blocks = get_field('flexible_content_blocks', $post->ID);
    if (! is_array($blocks) || $blocks === []) {
        return true;
    }

    $json = wp_json_encode($blocks);
    if (! is_string($json)) {
        return true;
    }

    return stripos($json, 'lorem ipsum') !== false
        || stripos($json, 'quis nostrud exerci') !== false;
}

/**
 * @param array<int, array<string, mixed>> $existing
 */
function matrix_starter_service_preserve_image(array $existing, string $layout, string $field): mixed
{
    $block = matrix_starter_service_find_block($existing, $layout);
    if (! is_array($block)) {
        return null;
    }

    return $block[$field] ?? null;
}

/**
 * @param array<int, array<string, mixed>> $existing
 * @return array<string, mixed>|null
 */
function matrix_starter_service_find_block(array $existing, string $layout): ?array
{
    foreach ($existing as $block) {
        if (is_array($block) && ($block['acf_fc_layout'] ?? '') === $layout) {
            return $block;
        }
    }

    return null;
}

/**
 * @param list<string> $items
 * @return list<array{item_text: string}>
 */
function matrix_starter_service_list_items(array $items): array
{
    $rows = [];
    foreach ($items as $item) {
        $rows[] = ['item_text' => $item];
    }

    return $rows;
}

/**
 * @param array<int, array<string, mixed>> $existing
 * @return list<array<string, mixed>>
 */
function matrix_starter_service_blocks_flushing(array $existing): array
{
    $content_four_image = matrix_starter_service_preserve_image($existing, 'content_four', 'image');
    $block_006_image    = matrix_starter_service_preserve_image($existing, 'content_block_006', 'left_image');
    $block_two_image    = matrix_starter_service_preserve_image($existing, 'content_block_two', 'image');

    return [
        [
            'acf_fc_layout'    => 'content_four',
            'show_section'     => 1,
            'heading_tag'      => 'h1',
            'heading'          => __('Flushing and Water Treatment', 'matrix-starter'),
            'description'      => '<p>' . esc_html__(
                'Ensuring resilience and efficiency for mission-critical cooling systems. KES provides a fully integrated service for pipework flushing and water treatment, serving as a vital technical bridge between a facility’s construction and its live operation. We use bespoke equipment and methodologies that exceed BSRIA, ASHRAE, and international industry standards so cooling systems are resilient, design-compliant, and ready for commissioning.',
                'matrix-starter'
            ) . '</p>',
            'image'            => $content_four_image,
            'background_color' => 'bg-white',
            'text_color'       => 'text-gray-800',
            'heading_color'    => 'text-blue-500',
            'accent_bar_color' => 'bg-blue-100',
        ],
        [
            'acf_fc_layout'   => 'content_block_006',
            'show_section'    => 1,
            'left_image'      => $block_006_image,
            'heading_tag'     => 'h2',
            'heading_text'    => __('Why flushing is critical for new data halls', 'matrix-starter'),
            'description'     => '<p>' . esc_html__(
                'Pre-commissioning cleaning protects high-value plant, maintains design performance, and gives a verified baseline for ongoing water treatment.',
                'matrix-starter'
            ) . '</p>',
            'services'        => matrix_starter_service_list_items(
                [
                    __('Contaminant removal — Clears construction debris, welding slag, dirt, and foreign particles from pipework before systems go live.', 'matrix-starter'),
                    __('Blockage prevention — Restores free flow through cooling coils, heat exchangers, and control valves.', 'matrix-starter'),
                    __('Equipment protection — Safeguards pumps, chillers, and sensors from abrasive particulates and early failure.', 'matrix-starter'),
                    __('Optimised heat transfer — Clean internal surfaces are essential for design heat exchange and stable cooling performance.', 'matrix-starter'),
                    __('Corrosion mitigation — Debris removal and a clean hydraulic base support effective inhibition and long-term system health.', 'matrix-starter'),
                ]
            ),
            'gradient_from'   => '#262262',
            'gradient_to'     => '#2B3990',
            'heading_color'   => 'text-white',
            'text_color'      => 'text-white',
            'accent_bar_color'=> 'bg-blue-50',
        ],
        [
            'acf_fc_layout' => 'content_block_two',
            'show_section'  => 1,
            'heading_tag'   => 'h2',
            'heading'       => __('Our process', 'matrix-starter'),
            'wysiwyg_one'   => '<p>' . esc_html__(
                'From temporary plant to final sign-off, our teams manage each stage with documented chemical analysis and photographic records so clients have a clear audit trail at handover.',
                'matrix-starter'
            ) . '</p>',
            'wysiwyg_two'   => '<ol>'
                . '<li><strong>' . esc_html__('Preparation', 'matrix-starter') . '</strong> — '
                . esc_html__('Temporary filling and flushing equipment is brought to site and connected to the system under test.', 'matrix-starter') . '</li>'
                . '<li><strong>' . esc_html__('Filling and testing', 'matrix-starter') . '</strong> — '
                . esc_html__('The system is filled with treated water, pressure tested, and purged of air.', 'matrix-starter') . '</li>'
                . '<li><strong>' . esc_html__('Circulation and debris removal', 'matrix-starter') . '</strong> — '
                . esc_html__('Water is circulated and filtered continuously; low points and strainers are dropped to remove debris.', 'matrix-starter') . '</li>'
                . '<li><strong>' . esc_html__('Onsite analysis', 'matrix-starter') . '</strong> — '
                . esc_html__('Systematic chemical analysis is performed and documented with photographic records.', 'matrix-starter') . '</li>'
                . '<li><strong>' . esc_html__('Criteria achievement', 'matrix-starter') . '</strong> — '
                . esc_html__('Filtration continues until water meets specified technical criteria.', 'matrix-starter') . '</li>'
                . '<li><strong>' . esc_html__('Final sign-off', 'matrix-starter') . '</strong> — '
                . esc_html__('Samples are sent to a laboratory for final analysis and formal project sign-off.', 'matrix-starter') . '</li>'
                . '</ol>',
            'image'         => $block_two_image,
            'reverse_layout'=> 0,
        ],
        [
            'acf_fc_layout'      => 'content_block_three',
            'heading'            => __('Specialist equipment', 'matrix-starter'),
            'heading_tag'        => 'h2',
            'description'        => '<p>' . esc_html__(
                'We deploy dedicated circulating, filtration, and dosing plant supported by onsite digital test kits for rapid chemical and microbiological results.',
                'matrix-starter'
            ) . '</p>',
            'content_section_1'  => '<ul>'
                . '<li>' . esc_html__('Circulating pump skids for large-bore systems', 'matrix-starter') . '</li>'
                . '<li>' . esc_html__('Filtration pump skids sized to project duty', 'matrix-starter') . '</li>'
                . '<li>' . esc_html__('Fill and dosing rigs for controlled chemical addition', 'matrix-starter') . '</li>'
                . '</ul>',
            'content_section_2'  => '<ul>'
                . '<li>' . esc_html__('Digital test kits for instant onsite chemical and microbiological results', 'matrix-starter') . '</li>'
                . '<li>' . esc_html__('Full documentation pack for client and commissioning teams', 'matrix-starter') . '</li>'
                . '<li>' . esc_html__('Experienced site supervisors aligned with KES commissioning services', 'matrix-starter') . '</li>'
                . '</ul>',
            'background_color'   => '#f9fafb',
        ],
    ];
}

/**
 * @param array<int, array<string, mixed>> $existing
 * @return list<array<string, mixed>>
 */
function matrix_starter_service_blocks_commissioning(array $existing): array
{
    $content_four_image = matrix_starter_service_preserve_image($existing, 'content_four', 'image');
    $block_006_image    = matrix_starter_service_preserve_image($existing, 'content_block_006', 'left_image');
    $block_two_image    = matrix_starter_service_preserve_image($existing, 'content_block_two', 'image');

    return [
        [
            'acf_fc_layout'    => 'content_four',
            'show_section'     => 1,
            'heading_tag'      => 'h1',
            'heading'          => __('Commissioning: Testing, Adjusting, and Balancing (TAB)', 'matrix-starter'),
            'description'      => '<p>' . esc_html__(
                'Validation of design intent and operational capacity. Our TAB specialists measure and adjust air and water flow rates to confirm design criteria are achieved and system capacity is proven. We work in strict compliance with BSRIA, CIBSE, and ASHRAE standards, delivering documented evidence for handover, warranties, and ongoing operations.',
                'matrix-starter'
            ) . '</p>',
            'image'            => $content_four_image,
            'background_color' => 'bg-white',
            'text_color'       => 'text-gray-800',
            'heading_color'    => 'text-blue-500',
            'accent_bar_color' => 'bg-blue-100',
        ],
        [
            'acf_fc_layout'   => 'content_block_006',
            'show_section'    => 1,
            'left_image'      => $block_006_image,
            'heading_tag'     => 'h2',
            'heading_text'    => __('Key benefits', 'matrix-starter'),
            'description'     => '<p>' . esc_html__(
                'Independent TAB closes the gap between installed plant and design performance—before critical loads are applied.',
                'matrix-starter'
            ) . '</p>',
            'services'        => matrix_starter_service_list_items(
                [
                    __('Thermal stability — Verifies chilled air or water reaches server intakes and maintains a stable environment under load.', 'matrix-starter'),
                    __('Energy efficiency — Balancing matches delivery to real load so plant runs at the lowest safe settings, reducing energy and cost.', 'matrix-starter'),
                    __('Equipment protection — TAB reports support warranty validation and confirm operation within manufacturer limits.', 'matrix-starter'),
                    __('Design validation — Establishes baseline performance at handover and confirms the original design intent.', 'matrix-starter'),
                ]
            ),
            'gradient_from'   => '#262262',
            'gradient_to'     => '#2B3990',
            'heading_color'   => 'text-white',
            'text_color'      => 'text-white',
            'accent_bar_color'=> 'bg-blue-50',
        ],
        [
            'acf_fc_layout' => 'content_block_two',
            'show_section'  => 1,
            'heading_tag'   => 'h2',
            'heading'       => __('The commissioning workflow', 'matrix-starter'),
            'wysiwyg_one'   => '<p>' . esc_html__(
                'Our workflow is structured to catch design and installation issues early, then prove performance under realistic conditions before final certification.',
                'matrix-starter'
            ) . '</p>',
            'wysiwyg_two'   => '<ul>'
                . '<li><strong>' . esc_html__('Initial design review', 'matrix-starter') . '</strong> — '
                . esc_html__('Feasibility input so cooling systems can be flushed, balanced, and commissioned as intended.', 'matrix-starter') . '</li>'
                . '<li><strong>' . esc_html__('Feasibility study', 'matrix-starter') . '</strong> — '
                . esc_html__('Engineers review design documents against practical commissioning requirements.', 'matrix-starter') . '</li>'
                . '<li><strong>' . esc_html__('Site inspection', 'matrix-starter') . '</strong> — '
                . esc_html__('Physical installation is checked against schematics; dampers and valves must be accessible.', 'matrix-starter') . '</li>'
                . '<li><strong>' . esc_html__('Proportional balancing', 'matrix-starter') . '</strong> — '
                . esc_html__('Air and water flows are adjusted systematically to design setpoints across all plant.', 'matrix-starter') . '</li>'
                . '<li><strong>' . esc_html__('Performance testing', 'matrix-starter') . '</strong> — '
                . esc_html__('Systems are tested under simulated load to verify thermal stability and pressure control.', 'matrix-starter') . '</li>'
                . '<li><strong>' . esc_html__('Discrepancy reporting', 'matrix-starter') . '</strong> — '
                . esc_html__('Deviations from design intent are recorded and tracked to resolution.', 'matrix-starter') . '</li>'
                . '<li><strong>' . esc_html__('Final certification', 'matrix-starter') . '</strong> — '
                . esc_html__('A comprehensive TAB report confirms data hall cooling capacity and efficiency.', 'matrix-starter') . '</li>'
                . '</ul>',
            'image'         => $block_two_image,
            'reverse_layout'=> 0,
        ],
        [
            'acf_fc_layout'      => 'content_block_three',
            'heading'            => __('Standards and reporting', 'matrix-starter'),
            'heading_tag'        => 'h2',
            'description'        => '<p>' . esc_html__(
                'Every project is delivered with traceable test data, clear remedial actions, and sign-off documentation suitable for clients, M&E contractors, and FM teams.',
                'matrix-starter'
            ) . '</p>',
            'content_section_1'  => '<ul>'
                . '<li>' . esc_html__('Compliance with BSRIA, CIBSE, and ASHRAE guidance', 'matrix-starter') . '</li>'
                . '<li>' . esc_html__('Method statements and risk assessments for live environments', 'matrix-starter') . '</li>'
                . '<li>' . esc_html__('As-built test sheets tied to design schedules', 'matrix-starter') . '</li>'
                . '</ul>',
            'content_section_2'  => '<ul>'
                . '<li>' . esc_html__('Discrepancy logs with agreed close-out', 'matrix-starter') . '</li>'
                . '<li>' . esc_html__('Handover packs for operations and maintenance', 'matrix-starter') . '</li>'
                . '<li>' . esc_html__('Coordination with KES flushing and water treatment where required', 'matrix-starter') . '</li>'
                . '</ul>',
            'background_color'   => '#f9fafb',
        ],
    ];
}

/**
 * @param array<int, array<string, mixed>> $existing
 * @return list<array<string, mixed>>
 */
function matrix_starter_service_blocks_heat_load(array $existing): array
{
    $content_four_image = matrix_starter_service_preserve_image($existing, 'content_four', 'image');
    $block_006_image    = matrix_starter_service_preserve_image($existing, 'content_block_006', 'left_image');
    $block_two_image    = matrix_starter_service_preserve_image($existing, 'content_block_two', 'image');

    return [
        [
            'acf_fc_layout'    => 'content_four',
            'show_section'     => 1,
            'heading_tag'      => 'h1',
            'heading'          => __('Large-Scale Heat Load Testing', 'matrix-starter'),
            'description'      => '<p>' . esc_html__(
                'Stress-testing infrastructure for the AI and HPC revolution. KES provides large-scale heat load testing to simulate real-world thermal stress using advanced air and liquid load banks. Our methods support the extreme densities required for AI and high-performance computing, giving owners confidence before live IT load is introduced.',
                'matrix-starter'
            ) . '</p>',
            'image'            => $content_four_image,
            'background_color' => 'bg-white',
            'text_color'       => 'text-gray-800',
            'heading_color'    => 'text-blue-500',
            'accent_bar_color' => 'bg-blue-100',
        ],
        [
            'acf_fc_layout'   => 'content_block_006',
            'show_section'    => 1,
            'left_image'      => $block_006_image,
            'heading_tag'     => 'h2',
            'heading_text'    => __('Essential validation for data centres', 'matrix-starter'),
            'description'     => '<p>' . esc_html__(
                'Staged thermal testing proves cooling and power paths under controlled load—without risking production equipment.',
                'matrix-starter'
            ) . '</p>',
            'services'        => matrix_starter_service_list_items(
                [
                    __('Design intent verification — Confirms the cooling system can handle maximum designed thermal load before servers are installed.', 'matrix-starter'),
                    __('Real-world simulation — Heater banks mimic heat output and airflow patterns of high-density racks.', 'matrix-starter'),
                    __('Hotspot identification — Highlights stagnant air or insufficient cooling so floor tiles and containment can be adjusted.', 'matrix-starter'),
                    __('Redundancy testing — Verifies remaining plant maintains setpoint during simulated mechanical failure.', 'matrix-starter'),
                    __('Electrical stress testing — Confirms UPS, generators, and distribution remain stable at full load.', 'matrix-starter'),
                    __('Performance baselines — Delivers data to support SLAs for uptime and operational acceptance.', 'matrix-starter'),
                ]
            ),
            'gradient_from'   => '#262262',
            'gradient_to'     => '#2B3990',
            'heading_color'   => 'text-white',
            'text_color'      => 'text-white',
            'accent_bar_color'=> 'bg-blue-50',
        ],
        [
            'acf_fc_layout' => 'content_block_two',
            'show_section'  => 1,
            'heading_tag'   => 'h2',
            'heading'       => __('Deployment and capacity', 'matrix-starter'),
            'wysiwyg_one'   => '<p>' . esc_html__(
                'We manage the full test environment—from load bank positioning and sensor integration to incremental load steps that map the cooling response curve.',
                'matrix-starter'
            ) . '</p>',
            'wysiwyg_two'   => '<p>' . esc_html__(
                'Thermal stress is applied in stages (typically 25%, 50%, 75%, and 100% of target load) so engineers can observe plant response, adjust controls, and sign off each milestone before the next increment. Real-time monitoring captures temperatures, flows, and alarms throughout.',
                'matrix-starter'
            ) . '</p>',
            'image'         => $block_two_image,
            'reverse_layout'=> 0,
        ],
        [
            'acf_fc_layout'      => 'content_block_three',
            'heading'            => __('In-house capacity', 'matrix-starter'),
            'heading_tag'        => 'h2',
            'description'        => '<p>' . esc_html__(
                'KES maintains significant in-house heater and boiler capacity, with turnkey distribution, cabling, controls, and site labour.',
                'matrix-starter'
            ) . '</p>',
            'content_section_1'  => '<ul>'
                . '<li><strong>' . esc_html__('500 kW boilers', 'matrix-starter') . '</strong> — '
                . esc_html__('25 MW in-house capacity', 'matrix-starter') . '</li>'
                . '<li><strong>' . esc_html__('200 kW heaters', 'matrix-starter') . '</strong> — '
                . esc_html__('28 MW in-house capacity', 'matrix-starter') . '</li>'
                . '<li><strong>' . esc_html__('100 kW heaters', 'matrix-starter') . '</strong> — '
                . esc_html__('8 MW in-house capacity', 'matrix-starter') . '</li>'
                . '</ul>',
            'content_section_2'  => '<ul>'
                . '<li><strong>' . esc_html__('15 kW heaters', 'matrix-starter') . '</strong> — '
                . esc_html__('16 MW in-house capacity', 'matrix-starter') . '</li>'
                . '<li>' . esc_html__('Distribution panels, cabling, and controls supplied by KES', 'matrix-starter') . '</li>'
                . '<li>' . esc_html__('Experienced teams for multi-megawatt hall deployments', 'matrix-starter') . '</li>'
                . '</ul>',
            'background_color'   => '#f9fafb',
        ],
    ];
}
