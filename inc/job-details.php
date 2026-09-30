<?php
/**
 * Structured job details helpers (ACF group: jobs_details).
 */

if (! function_exists('matrix_job_has_structured_details')) {
    /**
     * Whether a job has any structured ACF details filled in.
     *
     * @param int $job_id Job post ID.
     */
    function matrix_job_has_structured_details($job_id) {
        $job_id = (int) $job_id;
        if ($job_id <= 0 || ! function_exists('get_field')) {
            return false;
        }

        $meta_keys = [
            'job_location',
            'job_salary',
            'job_schedule',
            'job_working_days',
            'job_application_deadline',
            'job_additional_pay',
            'job_type',
            'job_licence',
            'job_work_location',
            'job_application_question',
            'job_role_overview',
        ];

        foreach ($meta_keys as $key) {
            $val = get_field($key, $job_id);
            if (is_string($val) && trim($val) !== '') {
                return true;
            }
        }

        foreach (['job_responsibilities', 'job_requirements', 'job_offers'] as $rep) {
            $rows = get_field($rep, $job_id);
            if (! empty($rows) && is_array($rows)) {
                return true;
            }
        }

        return false;
    }
}

if (! function_exists('matrix_job_details_plain_text')) {
    /**
     * Normalize ACF textarea to escaped HTML paragraphs (preserves blank-line breaks).
     *
     * @param string $text Raw text.
     */
    function matrix_job_details_plain_text($text) {
        $text = trim((string) $text);
        if ($text === '') {
            return '';
        }

        // Already HTML from wpautop storage? Prefer plain → paragraphs.
        $text = wp_strip_all_tags($text, true);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return wpautop(esc_html($text));
    }
}

if (! function_exists('matrix_render_job_details_html')) {
    /**
     * Build HTML for the careers card “Job description” panel from ACF fields.
     *
     * @param int $job_id Job post ID.
     * @return string HTML (may be empty).
     */
    function matrix_render_job_details_html($job_id) {
        $job_id = (int) $job_id;
        if ($job_id <= 0 || ! function_exists('get_field')) {
            return '';
        }

        if (! matrix_job_has_structured_details($job_id)) {
            return '';
        }

        ob_start();

        /**
         * @param array<string,mixed> $pairs Label => value.
         */
        $render_labeled_pairs = static function (array $pairs, $wrapper_class = '') {
            $items = [];
            foreach ($pairs as $label => $value) {
                if (is_string($value) && trim($value) !== '') {
                    $items[ $label ] = trim($value);
                }
            }
            if (empty($items)) {
                return;
            }
            $class = 'job-card-careers__meta';
            if ($wrapper_class !== '') {
                $class .= ' ' . $wrapper_class;
            }
            echo '<div class="' . esc_attr($class) . '">';
            foreach ($items as $label => $value) {
                echo '<div class="job-card-careers__labeled-item">';
                echo '<h4 class="job-card-careers__item-label">' . esc_html($label) . '</h4>';
                echo '<p class="job-card-careers__item-body">' . esc_html($value) . '</p>';
                echo '</div>';
            }
            echo '</div>';
        };

        // Top meta (role essentials).
        $render_labeled_pairs([
            'Location'             => get_field('job_location', $job_id),
            'Salary'               => get_field('job_salary', $job_id),
            'Schedule'             => get_field('job_schedule', $job_id),
            'Application deadline' => get_field('job_application_deadline', $job_id),
            'Additional pay'       => get_field('job_additional_pay', $job_id),
        ], 'mb-6');

        $overview = get_field('job_role_overview', $job_id);
        if (is_string($overview) && trim($overview) !== '') :
            ?>
            <h3 class="job-card-careers__block-heading"><?php esc_html_e('Role Overview', 'matrix-starter'); ?></h3>
            <div class="job-card-careers__overview">
              <?php echo matrix_job_details_plain_text($overview); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <?php
        endif;

        $responsibilities = get_field('job_responsibilities', $job_id);
        if (! empty($responsibilities) && is_array($responsibilities)) :
            $items = [];
            foreach ($responsibilities as $row) {
                $item = isset($row['item']) ? trim((string) $row['item']) : '';
                if ($item !== '') {
                    $items[] = $item;
                }
            }
            if (! empty($items)) :
                ?>
                <h3 class="job-card-careers__block-heading"><?php esc_html_e('Key Responsibilities', 'matrix-starter'); ?></h3>
                <ul>
                  <?php foreach ($items as $item) : ?>
                    <li><span><?php echo esc_html($item); ?></span></li>
                  <?php endforeach; ?>
                </ul>
                <?php
            endif;
        endif;

        $requirements = get_field('job_requirements', $job_id);
        if (! empty($requirements) && is_array($requirements)) :
            $req_rows = [];
            foreach ($requirements as $row) {
                $label = isset($row['label']) ? trim((string) $row['label']) : '';
                $text  = isset($row['text']) ? trim((string) $row['text']) : '';
                if ($label === '' && $text === '') {
                    continue;
                }
                $req_rows[] = ['label' => $label, 'text' => $text];
            }
            if (! empty($req_rows)) :
                ?>
                <h3 class="job-card-careers__block-heading"><?php esc_html_e('Requirements', 'matrix-starter'); ?></h3>
                <div class="job-card-careers__labeled-list">
                  <?php foreach ($req_rows as $row) : ?>
                    <div class="job-card-careers__labeled-item">
                      <?php if ($row['label'] !== '') : ?>
                        <h4 class="job-card-careers__item-label"><?php echo esc_html($row['label']); ?></h4>
                      <?php endif; ?>
                      <?php if ($row['text'] !== '') : ?>
                        <div class="job-card-careers__item-body">
                          <?php echo matrix_job_details_plain_text($row['text']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </div>
                      <?php endif; ?>
                    </div>
                  <?php endforeach; ?>
                </div>
                <?php
            endif;
        endif;

        $offers = get_field('job_offers', $job_id);
        if (! empty($offers) && is_array($offers)) :
            $offer_rows = [];
            foreach ($offers as $row) {
                $label = isset($row['label']) ? trim((string) $row['label']) : '';
                $text  = isset($row['text']) ? trim((string) $row['text']) : '';
                if ($label === '' && $text === '') {
                    continue;
                }
                $offer_rows[] = ['label' => $label, 'text' => $text];
            }
            if (! empty($offer_rows)) :
                ?>
                <h3 class="job-card-careers__block-heading"><?php esc_html_e('What We Offer', 'matrix-starter'); ?></h3>
                <div class="job-card-careers__labeled-list">
                  <?php foreach ($offer_rows as $row) : ?>
                    <div class="job-card-careers__labeled-item">
                      <?php if ($row['label'] !== '') : ?>
                        <h4 class="job-card-careers__item-label"><?php echo esc_html($row['label']); ?></h4>
                      <?php endif; ?>
                      <?php if ($row['text'] !== '') : ?>
                        <div class="job-card-careers__item-body">
                          <?php echo matrix_job_details_plain_text($row['text']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </div>
                      <?php endif; ?>
                    </div>
                  <?php endforeach; ?>
                </div>
                <?php
            endif;
        endif;

        // Footer meta (employment extras).
        $footer_pairs = [
            'Job type'             => get_field('job_type', $job_id),
            'Working days'         => get_field('job_working_days', $job_id),
            'Licence'              => get_field('job_licence', $job_id),
            'Work location'        => get_field('job_work_location', $job_id),
            'Application question' => get_field('job_application_question', $job_id),
        ];
        $has_footer = false;
        foreach ($footer_pairs as $value) {
            if (is_string($value) && trim($value) !== '') {
                $has_footer = true;
                break;
            }
        }
        if ($has_footer) {
            echo '<h3 class="job-card-careers__block-heading">' . esc_html__('Additional details', 'matrix-starter') . '</h3>';
            $render_labeled_pairs($footer_pairs);
        }

        return (string) ob_get_clean();
    }
}
