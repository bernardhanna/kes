<?php
// === Variables (always use get_sub_field) ===
$heading = get_sub_field('heading') ?: 'Contact us';
$heading_tag = get_sub_field('heading_tag') ?: 'h2';
$form_heading = get_sub_field('form_heading') ?: 'Apply for this position';
$form_heading_tag = get_sub_field('form_heading_tag') ?: 'h2';

// WYSIWYG unformatted (prevent wpautop)
$form_markup = get_sub_field('form_markup', false, false);
if ($form_markup) {
    $form_markup = preg_replace('#</?p[^>]*>#i', '', $form_markup);
    $form_markup = preg_replace('#<br\s*/?>#i', '', $form_markup);
}

$privacy_policy_url = get_sub_field('privacy_policy_url') ?: '#';
$image = get_sub_field('image');
$image_alt = $image ? get_post_meta($image, '_wp_attachment_image_alt', true) ?: 'Application form image' : '';

$background_color = get_sub_field('background_color') ?: '#ffffff';
$background_css = get_sub_field('background_css'); // gradient override
$text_color = get_sub_field('text_color') ?: '#0a0a0a';

// Padding classes
$padding_classes = ['', ''];
if (have_rows('padding_settings')) {
    while (have_rows('padding_settings')) {
        the_row();
        $screen_size = get_sub_field('screen_size');
        $padding_top = (string) get_sub_field('padding_top');
        $padding_bottom = (string) get_sub_field('padding_bottom');
        if ($screen_size !== '') {
            $padding_classes[] = "{$screen_size}:pt-[{$padding_top}rem]";
            $padding_classes[] = "{$screen_size}:pb-[{$padding_bottom}rem]";
        }
    }
}

// Unique section id
$section_id = 'application-form-' . esc_attr(wp_generate_uuid4());

// ===== Form plumbing: inject action, nonce, posted mail config, privacy link =====
if ($form_markup) {
    $form_markup = str_replace(
        '<form',
        sprintf(
            '<form action="%1$s" method="post" enctype="multipart/form-data" data-theme-form="%2$s"',
            esc_url(admin_url('admin-post.php')),
            esc_attr(get_row_index())
        ),
        $form_markup
    );

    $hidden = sprintf(
        '<input type="hidden" name="action" value="theme_form_submit">
        <input type="hidden" name="theme_form_nonce" value="%1$s">
        <input type="hidden" name="_theme_form_id" value="%2$s">
        <input type="hidden" name="_submission_uid" value="%3$s">',
        esc_attr(wp_create_nonce('theme_form_submit')),
        esc_attr(get_row_index()),
        esc_attr(wp_generate_uuid4()) // <-- one-time idempotency token
    );

    if ($name = get_sub_field('form_name')) {
        $hidden .= '<input type="hidden" name="_theme_form_name" value="' . esc_attr($name) . '">';
    }
    if (get_sub_field('save_entries_to_db')) {
        $hidden .= '<input type="hidden" name="_theme_save_to_db" value="1">';
    }

    // 🔐 Mail config (posted)
    $cfg_to = get_sub_field('email_to') ?: get_option('admin_email');
    $cfg_bcc = get_sub_field('email_bcc') ?: '';
    $cfg_subject = get_sub_field('email_subject') ?: '';
    $cfg_from_name = get_sub_field('from_name') ?: '';
    $cfg_from_email = get_sub_field('from_email') ?: '';

    $hidden_cfg = '';
    $hidden_cfg .= '<input type="hidden" name="_cfg_to" value="' . esc_attr($cfg_to) . '">';
    $hidden_cfg .= '<input type="hidden" name="_cfg_bcc" value="' . esc_attr($cfg_bcc) . '">';
    $hidden_cfg .= '<input type="hidden" name="_cfg_subject" value="' . esc_attr($cfg_subject) . '">';
    $hidden_cfg .= '<input type="hidden" name="_cfg_from_name" value="' . esc_attr($cfg_from_name) . '">';
    $hidden_cfg .= '<input type="hidden" name="_cfg_from_email" value="' . esc_attr($cfg_from_email) . '">';

    if (get_sub_field('enable_autoresponder')) {
        $hidden_cfg .= '<input type="hidden" name="_cfg_auto_enabled" value="1">';
        $hidden_cfg .= '<input type="hidden" name="_cfg_auto_subject" value="' . esc_attr(get_sub_field('autoresponder_subject') ?: '') . '">';
        $hidden_cfg .= '<input type="hidden" name="_cfg_auto_message" value="' . esc_attr(get_sub_field('autoresponder_message') ?: '') . '">';
    }

    $form_markup = str_replace('</form>', ($hidden . $hidden_cfg) . '</form>', $form_markup);
    $form_markup = str_replace('href="#"', 'href="' . esc_url($privacy_policy_url) . '"', $form_markup);
}
?>

<section id="<?php echo esc_attr($section_id); ?>"
         class="relative flex overflow-hidden <?php echo esc_attr(implode(' ', $padding_classes)); ?>"
         style="<?php echo esc_attr($background_css ? ("background: {$background_css}; color: {$text_color};") : ("background-color: {$background_color}; color: {$text_color};")); ?>">
    <div class="flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5">

        <?php if ($heading): ?>
            <header class="w-full text-center mb-8">
                <<?php echo esc_attr($heading_tag); ?> class="text-4xl font-medium max-md:text-3xl">
                    <?php echo esc_html($heading); ?>
                </<?php echo esc_attr($heading_tag); ?>>
            </header>
        <?php endif; ?>

        <div class="flex relative gap-16 items-start w-full max-md:flex-col max-md:gap-8 max-sm:gap-6">

            <!-- Form Column -->
            <div class="flex relative flex-col gap-4 items-start w-full max-w-[377.5px] max-md:w-full max-sm:gap-3">

                <?php if ($form_heading): ?>
                    <<?php echo esc_attr($form_heading_tag); ?> id="form-heading-<?php echo esc_attr(get_row_index()); ?>" class="mb-4 text-2xl font-medium">
                        <?php echo esc_html($form_heading); ?>
                    </<?php echo esc_attr($form_heading_tag); ?>>
                <?php endif; ?>

                <?php if ($form_markup): ?>
                    <?php
                    echo wp_kses(
                        $form_markup,
                        [
                            'form' => ['class' => [], 'role' => [], 'aria-labelledby' => [], 'novalidate' => [], 'action' => [], 'method' => [], 'enctype' => [], 'data-theme-form' => []],
                            'header' => ['class' => []],
                            'section' => ['class' => []],
                            'div' => ['class' => [], 'id' => [], 'role' => [], 'aria-live' => [], 'aria-describedby' => [], 'onclick' => [], 'onkeydown' => [], 'tabindex' => []],
                            'label' => ['for' => [], 'class' => [], 'id' => []],
                            'input' => ['type' => [], 'id' => [], 'name' => [], 'placeholder' => [], 'required' => [], 'aria-required' => [], 'aria-describedby' => [], 'autocomplete' => [], 'class' => [], 'value' => [], 'accept' => [], 'rows' => []],
                            'select' => ['id' => [], 'name' => [], 'required' => [], 'aria-required' => [], 'aria-describedby' => [], 'class' => []],
                            'option' => ['value' => [], 'selected' => [], 'disabled' => []],
                            'textarea' => ['id' => [], 'name' => [], 'placeholder' => [], 'required' => [], 'aria-required' => [], 'aria-describedby' => [], 'rows' => [], 'class' => []],
                            'button' => ['type' => [], 'class' => [], 'aria-describedby' => []],
                            'span' => ['class' => [], 'id' => []],
                            'svg' => ['class' => [], 'fill' => [], 'stroke' => [], 'viewBox' => [], 'xmlns' => [], 'aria-hidden' => [], 'width' => [], 'height' => []],
                            'path' => ['stroke-linecap' => [], 'stroke-linejoin' => [], 'stroke-width' => [], 'd' => [], 'stroke' => []],
                            'a' => ['href' => [], 'class' => [], 'target' => [], 'aria-label' => []],
                            'h1' => ['class' => [], 'id' => []], 'h2' => ['class' => [], 'id' => []], 'h3' => ['class' => [], 'id' => []], 'h4' => ['class' => [], 'id' => []], 'h5' => ['class' => [], 'id' => []], 'h6' => ['class' => [], 'id' => []], 'p' => ['class' => []],
                            'img' => ['src' => [], 'alt' => [], 'class' => [], 'width' => [], 'height' => []],
                        ]
                    );
                    ?>
                <?php endif; ?>
            </div>

            <!-- Image Column -->
            <?php if ($image): ?>
                <div class="flex relative flex-col justify-center items-center rounded-lg flex-[1_0_0] h-[973px] max-md:h-[400px] max-sm:h-[300px]">
                    <?php echo wp_get_attachment_image($image, 'full', false, [
                        'alt' => esc_attr($image_alt),
                        'class' => 'absolute top-0 left-0 shrink-0 rounded-lg h-[973px] w-[567px] max-md:object-cover max-md:relative max-md:top-0 max-md:left-0 max-md:w-full max-md:h-[400px] max-sm:h-[300px]',
                    ]); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
