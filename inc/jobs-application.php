<?php
/**
 * Careers job application — config + country list.
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Resolve ACF image field to attachment ID.
 */
function matrix_acf_image_to_id($value): int {
    if (is_numeric($value)) {
        return (int) $value;
    }
    if (is_array($value)) {
        if (! empty($value['ID'])) {
            return (int) $value['ID'];
        }
        if (! empty($value['id'])) {
            return (int) $value['id'];
        }
    }

    return 0;
}

/**
 * Side image URL — attachment, else theme placeholder (always returns a URL).
 */
function matrix_job_application_image_url(int $image_id = 0): string {
    if ($image_id > 0) {
        $url = wp_get_attachment_image_url($image_id, 'large');
        if (is_string($url) && $url !== '') {
            return $url;
        }
    }

    return get_theme_file_uri('assets/images/placeholder.png');
}

/**
 * Resolve job post ID for the application form template.
 */
function matrix_resolve_job_application_id($candidate = 0): int {
    if (is_numeric($candidate) && (int) $candidate > 0) {
        return (int) $candidate;
    }

    if (! empty($GLOBALS['matrix_job_application_post_id'])) {
        return (int) $GLOBALS['matrix_job_application_post_id'];
    }

    if (isset($GLOBALS['args']) && is_array($GLOBALS['args']) && ! empty($GLOBALS['args']['job_id'])) {
        return (int) $GLOBALS['args']['job_id'];
    }

    if (is_singular('jobs')) {
        return (int) get_queried_object_id();
    }

    return 0;
}

/**
 * Render the job application form (sets context then loads template).
 */
function matrix_render_job_application_form(int $job_id = 0): void {
    $job_id = matrix_resolve_job_application_id($job_id);
    if ($job_id <= 0) {
        return;
    }

    $GLOBALS['matrix_job_application_post_id'] = $job_id;

    $template = locate_template('template-parts/jobs/application-form.php');
    if ($template) {
        load_template($template, false, ['job_id' => $job_id]);
    }

    unset($GLOBALS['matrix_job_application_post_id']);
}

/**
 * Ensure single job posts use single-jobs.php.
 */
add_filter('template_include', static function (string $template): string {
    if (is_singular('jobs')) {
        $jobs_template = locate_template('single-jobs.php');
        if ($jobs_template) {
            return $jobs_template;
        }
    }

    return $template;
}, 20);

/**
 * Country options for the application form (Ireland first per Figma).
 *
 * @return array<string, string> value => label
 */
function matrix_job_application_countries(): array {
    $countries = [
        'Ireland'        => __('Ireland', 'matrix-starter'),
        'United Kingdom' => __('United Kingdom', 'matrix-starter'),
        'Northern Ireland' => __('Northern Ireland', 'matrix-starter'),
        'United States'  => __('United States', 'matrix-starter'),
        'Canada'         => __('Canada', 'matrix-starter'),
        'Australia'      => __('Australia', 'matrix-starter'),
        'Germany'        => __('Germany', 'matrix-starter'),
        'France'         => __('France', 'matrix-starter'),
        'Netherlands'    => __('Netherlands', 'matrix-starter'),
        'Spain'          => __('Spain', 'matrix-starter'),
        'Poland'         => __('Poland', 'matrix-starter'),
        'Other'          => __('Other', 'matrix-starter'),
    ];

    return apply_filters('matrix_job_application_countries', $countries);
}

/**
 * Resolved application page settings for a job post.
 *
 * @return array{
 *   job_id:int,
 *   job_title:string,
 *   page_heading:string,
 *   intro:string,
 *   image_id:int,
 *   image_url:string,
 *   job_url:string,
 *   privacy_url:string,
 *   email_to:string,
 *   email_subject:string,
 *   form_name:string,
 *   save_to_db:bool,
 *   cv_max_mb:int,
 *   enable_autoresponder:bool,
 *   autoresponder_subject:string,
 *   autoresponder_message:string,
 * }
 */
function matrix_job_application_config(int $job_id): array {
    $job_id    = max(0, $job_id);
    $job_title = $job_id ? get_the_title($job_id) : '';

    $opt_intro   = function_exists('get_field') ? (string) get_field('careers_application_intro', 'option') : '';
    $opt_image   = function_exists('get_field') ? matrix_acf_image_to_id(get_field('careers_application_image', 'option')) : 0;
    $opt_privacy = function_exists('get_field') ? (string) get_field('careers_privacy_policy_url', 'option') : '';
    $opt_email_raw = function_exists('get_field') ? get_field('careers_applications_email', 'option') : '';
    if ($opt_email_raw === '' || $opt_email_raw === null) {
        $opt_email_raw = function_exists('get_field') ? get_field('careers_apply_email', 'option') : '';
    }
    $opt_email   = sanitize_email((string) $opt_email_raw);
    $opt_subject = function_exists('get_field') ? (string) get_field('careers_application_subject', 'option') : '';
    $opt_cv_mb   = function_exists('get_field') ? (int) get_field('careers_cv_max_mb', 'option') : 5;
    $opt_save    = function_exists('get_field') ? (bool) get_field('careers_save_applications', 'option') : true;
    $opt_auto    = function_exists('get_field') ? (bool) get_field('careers_enable_autoresponder', 'option') : false;
    $opt_auto_sub = function_exists('get_field') ? (string) get_field('careers_autoresponder_subject', 'option') : '';
    $opt_auto_msg = function_exists('get_field') ? (string) get_field('careers_autoresponder_message', 'option') : '';

    $job_intro  = $job_id && function_exists('get_field') ? (string) get_field('application_intro', $job_id) : '';
    $job_image  = $job_id && function_exists('get_field') ? matrix_acf_image_to_id(get_field('application_image', $job_id)) : 0;

    $intro = $job_intro !== '' ? $job_intro : $opt_intro;
    if ($intro === '' && $job_id) {
        $intro = get_the_excerpt($job_id);
    }

    // Side image: custom ACF → job featured image → Theme Options default → placeholder.
    $featured_image_id = $job_id ? (int) get_post_thumbnail_id($job_id) : 0;
    $image_id          = $job_image > 0 ? $job_image : $featured_image_id;
    if ($image_id <= 0) {
        $image_id = $opt_image;
    }

    if ($opt_email === '' || ! is_email($opt_email)) {
        $opt_email = sanitize_email(get_option('admin_email'));
    }

    if ($opt_subject === '') {
        $opt_subject = __('Application: {job_title}', 'matrix-starter');
    }
    $email_subject = str_replace('{job_title}', $job_title, $opt_subject);

    $cv_max_mb = max(1, min(50, $opt_cv_mb ?: 5));
    $job_url   = $job_id ? get_permalink($job_id) : '';

    return [
        'job_id'                 => $job_id,
        'job_title'              => $job_title,
        'job_url'                => is_string($job_url) ? $job_url : '',
        'image_url'              => matrix_job_application_image_url($image_id),
        'page_heading'           => $job_title !== ''
            ? sprintf(
                /* translators: %s: job title */
                __('%s: Application form', 'matrix-starter'),
                $job_title
            )
            : __('Application form', 'matrix-starter'),
        'intro'                  => $intro,
        'image_id'               => $image_id,
        'privacy_url'            => $opt_privacy !== '' ? $opt_privacy : '#',
        'email_to'               => $opt_email,
        'email_subject'          => $email_subject,
        'form_name'              => $job_title !== ''
            ? sprintf(__('Job Application — %s', 'matrix-starter'), $job_title)
            : __('Job Application', 'matrix-starter'),
        'save_to_db'             => $opt_save,
        'cv_max_mb'              => $cv_max_mb,
        'enable_autoresponder'   => $opt_auto,
        'autoresponder_subject'  => $opt_auto_sub !== '' ? $opt_auto_sub : __('Thank you for your application', 'matrix-starter'),
        'autoresponder_message'  => $opt_auto_msg !== '' ? $opt_auto_msg : '<p>' . esc_html__('Thank you for your application. We will review it and be in touch soon.', 'matrix-starter') . '</p>',
    ];
}

/**
 * Build HTML email intro for job application submissions.
 *
 * @param array<string, mixed> $fields Normalized field values.
 */
function matrix_job_application_email_intro(array $fields): string {
    $job_id    = absint($fields['job_id'] ?? 0);
    $job_title = sanitize_text_field($fields['job_position'] ?? '');
    $job_url   = esc_url($fields['job_url'] ?? '');

    if ($job_id && $job_title === '') {
        $job_title = get_the_title($job_id);
    }
    if ($job_id && $job_url === '') {
        $job_url = get_permalink($job_id);
    }

    if ($job_title === '' && $job_id <= 0) {
        return '';
    }

    ob_start();
    ?>
    <div style="margin:0 0 24px;padding:16px 20px;background:#CBE9E1;border-left:4px solid #2B3990;">
        <p style="margin:0 0 4px;font-size:14px;color:#344054;"><?php esc_html_e('Position applied for', 'matrix-starter'); ?></p>
        <p style="margin:0;font-size:20px;font-weight:700;color:#262262;"><?php echo esc_html($job_title); ?></p>
        <?php if ($job_url) : ?>
            <p style="margin:8px 0 0;">
                <a href="<?php echo esc_url($job_url); ?>" style="color:#2B3990;"><?php echo esc_html($job_url); ?></a>
            </p>
        <?php endif; ?>
    </div>
    <?php
    $html = ob_get_clean();

    return is_string($html) ? $html : '';
}
