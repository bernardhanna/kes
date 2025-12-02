<?php
// === Variables (always use get_sub_field) ===
$heading = get_sub_field('heading') ?: 'Contact Us';
$heading_tag = get_sub_field('heading_tag') ?: 'h2';
$description = get_sub_field('description') ?: 'Request a call back by completing this form below, or just get in touch about vacancies, opportunities, and collaboration.';

// Contact information
$contact_heading = get_sub_field('contact_heading') ?: 'Contact our office';
$contact_heading_tag = get_sub_field('contact_heading_tag') ?: 'h3';
$office_name = get_sub_field('office_name') ?: 'Sligo Office';
$address = get_sub_field('address') ?: '28, Foster Avenue, Blackrock, D04 A021, Ireland';
$phone = get_sub_field('phone') ?: '+353 83 045 87 46';
$email = get_sub_field('email') ?: 'info@Kes.ie';
$business_hours = get_sub_field('business_hours');

// Form configuration
$form_markup = get_sub_field('form_markup', false, false);
if ($form_markup) {
    $form_markup = preg_replace('#</?p[^>]*>#i', '', $form_markup);
    $form_markup = preg_replace('#<br\s*/?>#i', '', $form_markup);
}

$privacy_policy_url = get_sub_field('privacy_policy_url') ?: '#';
$terms_conditions_url = get_sub_field('terms_conditions_url') ?: '#';

// Design options
$background_color = get_sub_field('background_color') ?: '#ffffff';
$text_color = get_sub_field('text_color') ?: '#0a0a0a';

// Padding classes
$padding_classes = [];
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
$section_id = 'contact-form-' . esc_attr(wp_generate_uuid4());

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
        esc_attr(wp_generate_uuid4())
    );

    if ($name = get_sub_field('form_name')) {
        $hidden .= '<input type="hidden" name="_theme_form_name" value="' . esc_attr($name) . '">';
    }
    if (get_sub_field('save_entries_to_db')) {
        $hidden .= '<input type="hidden" name="_theme_save_to_db" value="1">';
    }

    // Mail config (posted)
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
         style="background-color: <?php echo esc_attr($background_color); ?>; color: <?php echo esc_attr($text_color); ?>;">
    <div class="flex flex-col items-center w-full mx-auto max-w-container pt-5 pb-5 max-lg:px-5">

        <div class="flex flex-wrap gap-3 items-start w-full px-16 pt-16 pb-20 bg-white max-md:px-5">

            <!-- Left Column - Contact Information -->
            <div class="flex flex-col flex-1 shrink justify-center p-11 text-base rounded-2xl border-4 border-emerald-100 border-solid basis-14 min-w-60 text-slate-800 max-md:px-5 max-md:max-w-full">
                <div class="flex flex-col justify-center w-full bg-white max-md:max-w-full">

                    <?php if ($heading): ?>
                        <<?php echo esc_attr($heading_tag); ?> class="text-2xl font-bold leading-none text-violet-950 max-md:max-w-full">
                            <?php echo esc_html($heading); ?>
                        </<?php echo esc_attr($heading_tag); ?>>
                    <?php endif; ?>

                    <!-- Office Information -->
                    <div class="mt-4 w-full leading-none max-md:max-w-full">
                        <div class="flex flex-wrap gap-4 items-start w-full leading-5 text-slate-500 max-md:max-w-full">
                            <img src="https://api.builder.io/api/v1/image/assets/f35586c581c84ecf82b6de32c55ed39e/d47571abbd4e7feceda566fa5fa1764b81ca076f?placeholderIfAbsent=true"
                                 alt="Location icon"
                                 class="object-contain shrink-0 w-6 aspect-square" />
                            <div class="flex-1 shrink basis-0 max-md:max-w-full">
                                <span class="font-bold text-violet-950"><?php echo esc_html($office_name); ?></span><br>
                                <?php
                                $address_lines = explode(',', $address);
                                foreach ($address_lines as $line):
                                ?>
                                    <span class="text-slate-800"><?php echo esc_html(trim($line)); ?></span><br>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <?php if ($phone): ?>
                            <div class="flex flex-wrap gap-4 items-start mt-4 w-full max-md:max-w-full">
                                <img src="https://api.builder.io/api/v1/image/assets/f35586c581c84ecf82b6de32c55ed39e/8889f71c88b07cfc8b739d9421b6c093d7291635?placeholderIfAbsent=true"
                                     alt="Phone icon"
                                     class="object-contain shrink-0 w-6 aspect-square" />
                                <div class="flex-1 shrink basis-0 text-slate-800 max-md:max-w-full">
                                    <a href="tel:<?php echo esc_attr(preg_replace('/[^+\d]/', '', $phone)); ?>"
                                       class="hover:underline focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500">
                                        <?php echo esc_html($phone); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($email): ?>
                            <div class="flex flex-wrap gap-4 items-start mt-4 w-full whitespace-nowrap max-md:max-w-full">
                                <img src="https://api.builder.io/api/v1/image/assets/f35586c581c84ecf82b6de32c55ed39e/2267d29e3dff0abce8be70227b72b78825520cbc?placeholderIfAbsent=true"
                                     alt="Email icon"
                                     class="object-contain shrink-0 w-6 aspect-square" />
                                <div class="flex-1 shrink basis-0 text-slate-800 max-md:max-w-full">
                                    <a href="mailto:<?php echo esc_attr($email); ?>"
                                       class="hover:underline focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500">
                                        <?php echo esc_html($email); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($business_hours): ?>
                            <div class="flex flex-wrap gap-4 items-start mt-4 w-full max-md:max-w-full">
                                <img src="https://api.builder.io/api/v1/image/assets/f35586c581c84ecf82b6de32c55ed39e/30a1c67a8531c25c3b66294ad38d7c5b323bb32d?placeholderIfAbsent=true"
                                     alt="Hours icon"
                                     class="object-contain shrink-0 w-6 aspect-square" />
                                <div class="flex gap-8 items-start min-w-60">
                                    <div class="text-slate-800 wp_editor">
                                        <?php echo wp_kses_post($business_hours); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Map Image -->
                    <img src="https://api.builder.io/api/v1/image/assets/f35586c581c84ecf82b6de32c55ed39e/457a2cfb1b32c2bbc82d7510132225199c987229?placeholderIfAbsent=true"
                         alt="Office location map"
                         class="object-contain mt-4 w-full aspect-[1.34] min-h-[360px] max-md:max-w-full" />
                </div>
            </div>

            <!-- Right Column - Contact Form -->
            <div class="flex flex-col flex-1 shrink justify-center px-20 py-14 rounded-2xl basis-0 min-w-60 max-md:px-5 max-md:max-w-full">
                <div class="w-full max-md:max-w-full">
                    <div class="flex flex-col justify-center w-full max-md:max-w-full">
                        <div class="w-full text-4xl font-bold tracking-tighter leading-none text-violet-950 max-md:max-w-full">
                            <div class="text-violet-950 max-md:max-w-full">
                                How can we help?
                            </div>
                            <div class="flex mt-1 w-8 bg-cyan-500 min-h-1"></div>
                        </div>

                        <?php if ($description): ?>
                            <div class="mt-6 text-lg leading-6 text-slate-800 max-md:max-w-full">
                                <?php echo esc_html($description); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($form_markup): ?>
                        <div class="mt-4 w-full text-base max-md:max-w-full">
                            <?php
                            echo wp_kses(
                                $form_markup,
                                [
                                    'form' => ['class' => [], 'role' => [], 'aria-labelledby' => [], 'novalidate' => [], 'action' => [], 'method' => [], 'enctype' => [], 'data-theme-form' => []],
                                    'div' => ['class' => [], 'id' => [], 'role' => [], 'aria-live' => [], 'aria-describedby' => []],
                                    'label' => ['for' => [], 'class' => [], 'id' => []],
                                    'input' => ['type' => [], 'id' => [], 'name' => [], 'placeholder' => [], 'required' => [], 'aria-required' => [], 'aria-describedby' => [], 'autocomplete' => [], 'class' => [], 'value' => []],
                                    'select' => ['id' => [], 'name' => [], 'required' => [], 'aria-required' => [], 'aria-describedby' => [], 'class' => []],
                                    'option' => ['value' => [], 'selected' => [], 'disabled' => []],
                                    'textarea' => ['id' => [], 'name' => [], 'placeholder' => [], 'required' => [], 'aria-required' => [], 'aria-describedby' => [], 'rows' => [], 'class' => []],
                                    'button' => ['type' => [], 'class' => [], 'aria-describedby' => []],
                                    'span' => ['class' => [], 'id' => []],
                                    'a' => ['href' => [], 'class' => [], 'target' => [], 'aria-label' => []],
                                ]
                            );
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
