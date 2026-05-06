<?php
// === Variables (always use get_sub_field) ===
$heading = get_sub_field('heading') ?: 'Contact Us';
$heading_tag = get_sub_field('heading_tag') ?: 'h2';
$description = get_sub_field('description') ?: 'Request a call back by completing this form below, or just get in touch about vacancies, opportunities, and collaboration.';

// Form configuration
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

// ===== Form plumbing (server handler expects these hidden fields) =====
$theme_form_action_url = esc_url(admin_url('admin-post.php'));
$theme_form_id         = (string) get_row_index();
$theme_form_nonce      = wp_create_nonce('theme_form_submit');
$submission_uid        = wp_generate_uuid4();

$form_name       = (string) (get_sub_field('form_name') ?: '');
$save_entries    = (bool) get_sub_field('save_entries_to_db');

$cfg_to         = (string) (get_sub_field('email_to') ?: get_option('admin_email'));
$cfg_bcc        = (string) (get_sub_field('email_bcc') ?: '');
$cfg_subject    = (string) (get_sub_field('email_subject') ?: '');
$cfg_from_name  = (string) (get_sub_field('from_name') ?: '');
$cfg_from_email = (string) (get_sub_field('from_email') ?: '');

$auto_enabled = (bool) get_sub_field('enable_autoresponder');
$auto_subject = (string) (get_sub_field('autoresponder_subject') ?: '');
$auto_message = (string) (get_sub_field('autoresponder_message') ?: '');

$captcha_provider = function_exists('get_field')
  ? strtolower((string) (get_field('captcha_provider', 'option') ?: 'none'))
  : 'none';

$form_id_prefix = 'contact-form-' . esc_attr(get_row_index()) . '-';

// ===== Offices (repeater preferred; single-office fields as fallback) =====
$offices = [];
if (have_rows('offices')) {
  while (have_rows('offices')) {
    the_row();

    $show_map_raw = get_sub_field('show_map');
    // Back-compat: rows created before this toggle existed should still show maps.
    $show_map = ($show_map_raw === null || $show_map_raw === '') ? true : (bool) $show_map_raw;
    $lat_raw = (string) (get_sub_field('map_lat') ?: '');
    $lng_raw = (string) (get_sub_field('map_lng') ?: '');
    $lat_val = $lat_raw !== '' ? str_replace(',', '.', trim($lat_raw)) : '';
    $lng_val = $lng_raw !== '' ? str_replace(',', '.', trim($lng_raw)) : '';

    $offices[] = [
      'name'  => (string) (get_sub_field('office_name') ?: ''),
      'address' => (string) (get_sub_field('address') ?: ''),
      'phone' => (string) (get_sub_field('phone') ?: ''),
      'email' => (string) (get_sub_field('email') ?: ''),
      'hours' => (string) (get_sub_field('business_hours') ?: ''),
      'lat'   => ($show_map && $lat_val !== '' && is_numeric($lat_val)) ? (float) $lat_val : null,
      'lng'   => ($show_map && $lng_val !== '' && is_numeric($lng_val)) ? (float) $lng_val : null,
      'zoom'  => (int) (get_sub_field('map_zoom') ?: 13),
    ];
  }
}

if (empty($offices)) {
  $offices[] = [
    'name'    => (string) __('Office', 'matrix-starter'),
    'address' => '',
    'phone'   => '',
    'email'   => '',
    'hours'   => '',
    'lat'     => null,
    'lng'     => null,
    'zoom'    => 13,
  ];
}

$map_id_prefix = $section_id . '-office-map-';
?>

<section id="<?php echo esc_attr($section_id); ?>"
         data-matrix-block="<?php echo esc_attr(str_replace('_', '-', get_row_layout()) . '-' . get_row_index()); ?>"
         class="relative flex overflow-hidden <?php echo esc_attr(implode(' ', $padding_classes)); ?>"
         style="background-color: <?php echo esc_attr($background_color); ?>; color: <?php echo esc_attr($text_color); ?>;">
    <div class="flex flex-col items-center pt-5 pb-5 mx-auto w-full lg:pt-16 lg:pb-20 max-w-[1152px] max-xxl:px-5">

        <div class="grid grid-cols-1 lg:grid-cols-[50%_50%] gap-3 items-start w-full bg-white max-md:px-5">

            <!-- Left Column - Contact Information -->
            <div class="flex flex-col justify-center w-full p-11 text-base rounded-[16px] border-4 border-[#CBE9E1] border-solid text-slate-800 max-md:px-5">
                <div class="flex flex-col justify-center w-full bg-white max-md:max-w-full">

                    <?php if ($heading): ?>
                        <<?php echo esc_attr($heading_tag); ?> class="text-[#262262] font-primary text-[24px] font-bold leading-[32px] max-md:max-w-full">
                            <?php echo esc_html($heading); ?>
                        </<?php echo esc_attr($heading_tag); ?>>
                    <?php endif; ?>

                    <!-- Office Information -->
                    <div class="mt-4 w-full leading-none max-md:max-w-full">
                        <div class="flex flex-col gap-8">
                          <?php foreach ($offices as $idx => $o) : ?>
                            <?php
                              $o_name  = trim((string) ($o['name'] ?? ''));
                              $o_addr  = trim((string) ($o['address'] ?? ''));
                              $o_phone = trim((string) ($o['phone'] ?? ''));
                              $o_email = trim((string) ($o['email'] ?? ''));
                              $o_hours = (string) ($o['hours'] ?? '');
                              $o_lat   = $o['lat'] ?? null;
                              $o_lng   = $o['lng'] ?? null;
                              $o_zoom  = (int) (($o['zoom'] ?? 13) ?: 13);
                              $o_map_id = $map_id_prefix . $idx;
                            ?>

                            <div class="flex flex-col gap-4">
                              <div class="flex flex-wrap items-start w-full leading-5 text-slate-500 max-md:max-w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="20" viewBox="0 0 16 20" fill="none">
                                  <path d="M8 10C8.55 10 9.02083 9.80417 9.4125 9.4125C9.80417 9.02083 10 8.55 10 8C10 7.45 9.80417 6.97917 9.4125 6.5875C9.02083 6.19583 8.55 6 8 6C7.45 6 6.97917 6.19583 6.5875 6.5875C6.19583 6.97917 6 7.45 6 8C6 8.55 6.19583 9.02083 6.5875 9.4125C6.97917 9.80417 7.45 10 8 10ZM8 17.35C10.0333 15.4833 11.5417 13.7875 12.525 12.2625C13.5083 10.7375 14 9.38333 14 8.2C14 6.38333 13.4208 4.89583 12.2625 3.7375C11.1042 2.57917 9.68333 2 8 2C6.31667 2 4.89583 2.57917 3.7375 3.7375C2.57917 4.89583 2 6.38333 2 8.2C2 9.38333 2.49167 10.7375 3.475 12.2625C4.45833 13.7875 5.96667 15.4833 8 17.35ZM8 20C5.31667 17.7167 3.3125 15.5958 1.9875 13.6375C0.6625 11.6792 0 9.86667 0 8.2C0 5.7 0.804167 3.70833 2.4125 2.225C4.02083 0.741667 5.88333 0 8 0C10.1167 0 11.9792 0.741667 13.5875 2.225C15.1958 3.70833 16 5.7 16 8.2C16 9.86667 15.3375 11.6792 14.0125 13.6375C12.6875 15.5958 10.6833 17.7167 8 20Z" fill="#2B3990"/>
                                  </svg>
                                <div class="flex-1 ml-4 shrink basis-0 max-md:max-w-full">
                                  <?php if ($o_name !== '') : ?>
                                    <span class="text-[#262262] font-secondary text-[16px] font-bold leading-[20px]"><?php echo esc_html($o_name); ?></span><br>
                                  <?php endif; ?>
                                  <?php
                                    if ($o_addr !== '') {
                                      $address_lines = array_filter(array_map('trim', explode(',', $o_addr)));
                                      foreach ($address_lines as $line) {
                                        echo '<span class="text-[#1D2939] font-secondary text-[16px] font-normal leading-[24px]">' . esc_html($line) . '</span><br>';
                                      }
                                    }
                                  ?>
                                </div>
                              </div>

                              <?php if ($o_phone !== '') : ?>
                                <div class="flex flex-wrap items-start py-2 w-full max-md:max-w-full">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M21.9994 16.92V19.92C22.0006 20.1985 21.9435 20.4742 21.832 20.7294C21.7204 20.9845 21.5567 21.2136 21.3515 21.4019C21.1463 21.5901 20.904 21.7335 20.6402 21.8227C20.3764 21.9119 20.0968 21.9451 19.8194 21.92C16.7423 21.5856 13.7864 20.5342 11.1894 18.85C8.77327 17.3147 6.72478 15.2662 5.18945 12.85C3.49942 10.2412 2.44769 7.271 2.11944 4.18001C2.09446 3.90347 2.12732 3.62477 2.21595 3.36163C2.30457 3.09849 2.44702 2.85669 2.63421 2.65163C2.82141 2.44656 3.04925 2.28271 3.30324 2.17053C3.55722 2.05834 3.83179 2.00027 4.10945 2.00001H7.10945C7.59475 1.99523 8.06524 2.16708 8.43321 2.48354C8.80118 2.79999 9.04152 3.23945 9.10944 3.72001C9.23607 4.68007 9.47089 5.62273 9.80945 6.53001C9.94399 6.88793 9.97311 7.27692 9.89335 7.65089C9.8136 8.02485 9.62831 8.36812 9.35944 8.64001L8.08945 9.91001C9.513 12.4136 11.5859 14.4865 14.0894 15.91L15.3594 14.64C15.6313 14.3711 15.9746 14.1859 16.3486 14.1061C16.7225 14.0263 17.1115 14.0555 17.4694 14.19C18.3767 14.5286 19.3194 14.7634 20.2794 14.89C20.7652 14.9585 21.2088 15.2032 21.526 15.5775C21.8431 15.9518 22.0116 16.4296 21.9994 16.92Z" stroke="#2B3990" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
                                  <div class="flex-1 ml-4 shrink basis-0 text-[#1D2939] font-secondary text-[16px] font-normal leading-[20px] max-md:max-w-full">
                                    <a href="tel:<?php echo esc_attr(preg_replace('/[^+\d]/', '', $o_phone)); ?>"
                                       class="hover:underline focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500">
                                      <?php echo esc_html($o_phone); ?>
                                    </a>
                                  </div>
                                </div>
                              <?php endif; ?>

                              <?php if ($o_email !== '') : ?>
                                <div class="flex flex-wrap items-start w-full whitespace-nowrap max-md:max-w-full">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M22 6C22 4.9 21.1 4 20 4H4C2.9 4 2 4.9 2 6M22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6M22 6L12 13L2 6" stroke="#2B3990" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
                                  <div class="flex-1 ml-4 shrink basis-0 text-[#1D2939] font-secondary text-[16px] font-normal leading-[20px] max-md:max-w-full">
                                    <a href="mailto:<?php echo esc_attr($o_email); ?>"
                                       class="hover:underline focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500">
                                      <?php echo esc_html($o_email); ?>
                                    </a>
                                  </div>
                                </div>
                              <?php endif; ?>

                              <?php if (trim(wp_strip_all_tags($o_hours)) !== '') : ?>
                                <div class="flex flex-wrap items-start py-2 w-full max-md:max-w-full">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
<path d="M12 6V12L16 14M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="#2B3990" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
                                  <div class="flex gap-8 items-start ml-4 min-w-60">
                                    <div class="text-slate-800 wp_editor">
                                      <?php echo wp_kses_post($o_hours); ?>
                                    </div>
                                  </div>
                                </div>
                              <?php endif; ?>

                              <?php if (!empty($o_lat) && !empty($o_lng)) : ?>
                                <div
                                  id="<?php echo esc_attr($o_map_id); ?>"
                                  class="mt-4 w-full max-w-[482px] h-[360px] rounded-xl overflow-hidden border border-[#CBE9E1] bg-slate-100 min-w-0"
                                  data-office-map
                                  data-lat="<?php echo esc_attr((float) $o_lat); ?>"
                                  data-lng="<?php echo esc_attr((float) $o_lng); ?>"
                                  data-zoom="<?php echo esc_attr($o_zoom); ?>"
                                  role="application"
                                  aria-label="<?php echo esc_attr__('Office location map', 'matrix-starter'); ?>"
                                ></div>
                              <?php else : ?>
                                <div class="mt-4 w-full max-w-[482px] rounded-xl border border-[#CBE9E1] bg-slate-50  text-sm text-slate-600">
                                  <?php esc_html_e('Map is hidden or missing coordinates. Enable “Show Map?” and add latitude/longitude on the office row to display the map.', 'matrix-starter'); ?>
                                </div>
                              <?php endif; ?>
                            </div>
                          <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Contact Form -->
            <div class="flex flex-col justify-center px-20 py-14 w-full rounded-2xl max-md:px-5">
                <div class="w-full max-md:max-w-full">
                    <div class="flex flex-col justify-center w-full max-md:max-w-full">
                        <div class="w-full max-md:max-w-full">
                            <div class="text-[#262262] font-primary text-[36px] font-bold leading-[44px] tracking-[-0.72px] max-md:max-w-full">
                                How can we help?
                            </div>
                            <div class="flex mt-1 w-8 bg-cyan-500 min-h-1"></div>
                        </div>

                        <?php if ($description): ?>
                            <div class="mt-6 text-[#1D2939] font-secondary text-[18px] font-normal leading-[24px] max-md:max-w-full">
                                <?php echo esc_html($description); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-4 w-full text-base max-md:max-w-full">
                      <form
                        class="w-full"
                        role="form"
                        novalidate
                        aria-labelledby="<?php echo esc_attr($form_id_prefix . 'heading'); ?>"
                        action="<?php echo esc_url($theme_form_action_url); ?>"
                        method="post"
                        enctype="multipart/form-data"
                        data-theme-form="<?php echo esc_attr($theme_form_id); ?>"
                      >
                        <input type="hidden" name="action" value="theme_form_submit">
                        <input type="hidden" name="theme_form_nonce" value="<?php echo esc_attr($theme_form_nonce); ?>">
                        <input type="hidden" name="_theme_form_id" value="<?php echo esc_attr($theme_form_id); ?>">
                        <input type="hidden" name="_submission_uid" value="<?php echo esc_attr($submission_uid); ?>">
                        <?php if ($form_name !== '') : ?>
                          <input type="hidden" name="_theme_form_name" value="<?php echo esc_attr($form_name); ?>">
                        <?php endif; ?>
                        <?php if ($save_entries) : ?>
                          <input type="hidden" name="_theme_save_to_db" value="1">
                        <?php endif; ?>

                        <input type="hidden" name="_cfg_to" value="<?php echo esc_attr($cfg_to); ?>">
                        <input type="hidden" name="_cfg_bcc" value="<?php echo esc_attr($cfg_bcc); ?>">
                        <input type="hidden" name="_cfg_subject" value="<?php echo esc_attr($cfg_subject); ?>">
                        <input type="hidden" name="_cfg_from_name" value="<?php echo esc_attr($cfg_from_name); ?>">
                        <input type="hidden" name="_cfg_from_email" value="<?php echo esc_attr($cfg_from_email); ?>">
                        <?php if ($auto_enabled) : ?>
                          <input type="hidden" name="_cfg_auto_enabled" value="1">
                          <input type="hidden" name="_cfg_auto_subject" value="<?php echo esc_attr($auto_subject); ?>">
                          <input type="hidden" name="_cfg_auto_message" value="<?php echo esc_attr($auto_message); ?>">
                        <?php endif; ?>

                        <div class="mt-4 w-full text-base max-md:max-w-full">
                          <label class="flex items-center w-full text-[#344054] font-secondary text-[16px] font-medium leading-[22px] max-md:max-w-full" for="<?php echo esc_attr($form_id_prefix . 'fullname'); ?>">
                            <?php esc_html_e('Full name', 'matrix-starter'); ?><span aria-hidden="true">*</span>
                          </label>
                          <div class="mt-1 w-full leading-none text-gray-500 whitespace-nowrap max-md:max-w-full">
                            <div class="flex items-center w-full bg-white rounded border border-[#667085] border-solid min-h-[52px] max-md:max-w-full">
                              <input
                                id="<?php echo esc_attr($form_id_prefix . 'fullname'); ?>"
                                class="flex-1 gap-2 self-stretch my-auto w-full text-gray-500 bg-transparent border-none outline-none shrink basis-0 min-w-60 max-md:max-w-full focus:ring-0 placeholder:text-[#667085] placeholder:font-secondary placeholder:text-[16px] placeholder:font-normal placeholder:leading-[20px]"
                                name="fullname"
                                required
                                type="text"
                                placeholder="<?php esc_attr_e('Enter your full name', 'matrix-starter'); ?>"
                                aria-required="true"
                                aria-describedby="<?php echo esc_attr($form_id_prefix . 'fullname-error'); ?>"
                                autocomplete="name"
                              />
                            </div>
                            <div id="<?php echo esc_attr($form_id_prefix . 'fullname-error'); ?>" class="hidden mt-1 text-xs text-red-600" role="alert" aria-live="polite"></div>
                          </div>
                        </div>

                        <div class="mt-4 w-full text-base whitespace-nowrap max-md:max-w-full">
                          <label class="flex items-center w-full text-[#344054] font-secondary text-[16px] font-medium leading-[22px] max-md:max-w-full" for="<?php echo esc_attr($form_id_prefix . 'surname'); ?>">
                            <?php esc_html_e('Surname', 'matrix-starter'); ?><span aria-hidden="true">*</span>
                          </label>
                          <div class="mt-1 w-full leading-none text-gray-500 max-md:max-w-full">
                            <div class="flex items-center w-full bg-white rounded border border-[#667085] border-solid min-h-[52px] max-md:max-w-full">
                              <input
                                id="<?php echo esc_attr($form_id_prefix . 'surname'); ?>"
                                class="flex-1 gap-2 self-stretch my-auto w-full text-gray-500 bg-transparent border-none outline-none shrink basis-0 min-w-60 max-md:max-w-full focus:ring-0 placeholder:text-[#667085] placeholder:font-secondary placeholder:text-[16px] placeholder:font-normal placeholder:leading-[20px]"
                                name="surname"
                                required
                                type="text"
                                placeholder="<?php esc_attr_e('Enter your surname', 'matrix-starter'); ?>"
                                aria-required="true"
                                aria-describedby="<?php echo esc_attr($form_id_prefix . 'surname-error'); ?>"
                              />
                            </div>
                            <div id="<?php echo esc_attr($form_id_prefix . 'surname-error'); ?>" class="hidden mt-1 text-xs text-red-600" role="alert" aria-live="polite"></div>
                          </div>
                        </div>

                        <div class="mt-4 w-full text-base whitespace-nowrap max-md:max-w-full">
                          <label class="flex items-center w-full text-[#344054] font-secondary text-[16px] font-medium leading-[22px] max-md:max-w-full" for="<?php echo esc_attr($form_id_prefix . 'email'); ?>">
                            <?php esc_html_e('Email', 'matrix-starter'); ?><span aria-hidden="true">*</span>
                          </label>
                          <div class="mt-1 w-full leading-none text-gray-500 max-md:max-w-full">
                            <div class="flex items-center w-full bg-white rounded border border-[#667085] border-solid min-h-[52px] max-md:max-w-full">
                              <input
                                id="<?php echo esc_attr($form_id_prefix . 'email'); ?>"
                                class="flex-1 gap-2 self-stretch my-auto w-full text-gray-500 bg-transparent border-none outline-none shrink basis-0 min-w-60 max-md:max-w-full focus:ring-0 placeholder:text-[#667085] placeholder:font-secondary placeholder:text-[16px] placeholder:font-normal placeholder:leading-[20px]"
                                autocomplete="email"
                                name="email"
                                required
                                type="email"
                                placeholder="<?php esc_attr_e('Enter your email address', 'matrix-starter'); ?>"
                                aria-required="true"
                                aria-describedby="<?php echo esc_attr($form_id_prefix . 'email-error'); ?>"
                              />
                            </div>
                            <div id="<?php echo esc_attr($form_id_prefix . 'email-error'); ?>" class="hidden mt-1 text-xs text-red-600" role="alert" aria-live="polite"></div>
                          </div>
                        </div>

                        <div class="mt-4 w-full text-base max-md:max-w-full">
                          <label class="flex items-center w-full text-[#344054] font-secondary text-[16px] font-medium leading-[22px] max-md:max-w-full" for="<?php echo esc_attr($form_id_prefix . 'phone'); ?>">
                            <?php esc_html_e('Phone number', 'matrix-starter'); ?><span aria-hidden="true">*</span>
                          </label>
                          <div class="mt-1 w-full leading-none text-gray-500 max-md:max-w-full">
                            <div class="flex items-center w-full bg-white rounded border border-[#667085] border-solid min-h-[52px] max-md:max-w-full">
                              <input
                                id="<?php echo esc_attr($form_id_prefix . 'phone'); ?>"
                                class="flex-1 gap-2 self-stretch my-auto w-full text-gray-500 bg-transparent border-none outline-none shrink basis-0 min-w-60 max-md:max-w-full focus:ring-0 placeholder:text-[#667085] placeholder:font-secondary placeholder:text-[16px] placeholder:font-normal placeholder:leading-[20px]"
                                autocomplete="tel"
                                name="phone"
                                required
                                type="tel"
                                placeholder="<?php esc_attr_e('Enter your phone number', 'matrix-starter'); ?>"
                                aria-required="true"
                                aria-describedby="<?php echo esc_attr($form_id_prefix . 'phone-error'); ?>"
                              />
                            </div>
                            <div id="<?php echo esc_attr($form_id_prefix . 'phone-error'); ?>" class="hidden mt-1 text-xs text-red-600" role="alert" aria-live="polite"></div>
                          </div>
                        </div>

                        <div class="mt-4 w-full text-base max-md:max-w-full">
                          <label class="flex items-center w-full text-[#344054] font-secondary text-[16px] font-medium leading-[22px] whitespace-nowrap max-md:max-w-full" for="<?php echo esc_attr($form_id_prefix . 'subject'); ?>">
                            <?php esc_html_e('Subject', 'matrix-starter'); ?>
                          </label>
                          <div class="mt-1 w-full leading-none text-gray-500 max-md:max-w-full">
                            <div class="flex items-center px-4 w-full bg-white rounded border border-[#667085] border-solid h-[52px] max-md:max-w-full">
                              <select
                                id="<?php echo esc_attr($form_id_prefix . 'subject'); ?>"
                                class="flex-1 gap-2 self-stretch my-auto w-full h-full text-gray-500 bg-transparent border-none outline-none shrink basis-0 min-w-60 max-md:max-w-full focus:ring-0"
                                name="subject"
                                aria-describedby="<?php echo esc_attr($form_id_prefix . 'subject-help'); ?>"
                              >
                                <option value=""><?php esc_html_e('Choose subject', 'matrix-starter'); ?></option>
                                <option value="general"><?php esc_html_e('General Inquiry', 'matrix-starter'); ?></option>
                                <option value="support"><?php esc_html_e('Support', 'matrix-starter'); ?></option>
                                <option value="sales"><?php esc_html_e('Sales', 'matrix-starter'); ?></option>
                                <option value="partnership"><?php esc_html_e('Partnership', 'matrix-starter'); ?></option>
                                <option value="other"><?php esc_html_e('Other', 'matrix-starter'); ?></option>
                              </select>
                            </div>
                            <div id="<?php echo esc_attr($form_id_prefix . 'subject-help'); ?>" class="hidden mt-1 text-xs text-gray-500">
                              <?php esc_html_e('Please select a subject for your inquiry', 'matrix-starter'); ?>
                            </div>
                          </div>
                        </div>

                        <div class="mt-4 w-full min-h-[165px] max-md:max-w-full">
                          <label class="flex items-center w-full text-[#344054] font-secondary text-[16px] font-medium leading-[22px] max-md:max-w-full" for="<?php echo esc_attr($form_id_prefix . 'message'); ?>">
                            <?php esc_html_e('Your message', 'matrix-starter'); ?>
                          </label>
                          <div class="flex-1 mt-2 w-full text-sm leading-none text-gray-500 max-md:max-w-full">
                            <div class="flex flex-1 items-start px-4 py-3 bg-white rounded border border-solid border-[#667085] size-full max-md:max-w-full">
                              <textarea
                                id="<?php echo esc_attr($form_id_prefix . 'message'); ?>"
                                class="flex-1 gap-2 self-stretch w-full text-gray-500 bg-transparent border-none outline-none resize-none shrink basis-0 min-w-60 max-md:max-w-full focus:ring-0 placeholder:text-[#667085] placeholder:font-secondary placeholder:text-[16px] placeholder:font-normal placeholder:leading-[20px]"
                                name="message"
                                rows="6"
                                placeholder="<?php esc_attr_e('Your message here', 'matrix-starter'); ?>"
                                aria-describedby="<?php echo esc_attr($form_id_prefix . 'message-help'); ?>"
                              ></textarea>
                            </div>
                            <div id="<?php echo esc_attr($form_id_prefix . 'message-help'); ?>" class="hidden mt-1 text-xs text-gray-500">
                              <?php esc_html_e('Please provide details about your inquiry', 'matrix-starter'); ?>
                            </div>
                          </div>
                        </div>

                        <?php if ($captcha_provider === 'turnstile') : ?>
                          <div class="mt-4 cf-turnstile" data-size="normal" data-theme="light"></div>
                        <?php endif; ?>

                        <div class="flex gap-2 items-center mt-4 max-w-full w-[362px]">
                          <div class="flex overflow-hidden flex-col justify-center items-center self-stretch my-auto w-6 rounded min-h-6">
                            <input
                              id="<?php echo esc_attr($form_id_prefix . 'privacy-policy'); ?>"
                              class="w-6 h-6 rounded border border-[#667085] border-solid min-h-6 focus:ring-2 focus:ring-blue-500"
                              name="privacy-policy"
                              required
                              type="checkbox"
                              aria-required="true"
                              aria-describedby="<?php echo esc_attr($form_id_prefix . 'privacy-error'); ?>"
                              value="1"
                            />
                          </div>
                          <label class="flex-1 self-stretch my-auto cursor-pointer shrink basis-0 text-[#475467] font-secondary text-[14px] font-normal leading-[20px]" for="<?php echo esc_attr($form_id_prefix . 'privacy-policy'); ?>">
                            <?php esc_html_e('By submitting, you agree with the', 'matrix-starter'); ?>
                            <a
                              class="underline rounded hover:text-blue-600 focus:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                              href="<?php echo esc_url($terms_conditions_url); ?>"
                            >
                              <?php esc_html_e('Terms and Conditions', 'matrix-starter'); ?>
                            </a>
                            <?php esc_html_e('and', 'matrix-starter'); ?>
                            <a
                              class="underline rounded hover:text-blue-600 focus:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                              href="<?php echo esc_url($privacy_policy_url); ?>"
                            >
                              <?php esc_html_e('Privacy Policy', 'matrix-starter'); ?>
                            </a>
                          </label>
                          <div id="<?php echo esc_attr($form_id_prefix . 'privacy-error'); ?>" class="hidden mt-1 w-full text-xs text-red-600" role="alert" aria-live="polite"></div>
                        </div>

                        <button
                          class="btn flex gap-2 justify-center items-center px-6 py-3.5 mt-4 w-full whitespace-nowrap min-h-[52px] rounded-[100px] max-md:px-5 max-md:max-w-full text-white font-secondary text-[18px] font-medium leading-[24px] bg-gradient-to-r from-[#2B3990] to-[#006EC8] hover:from-[#006EC8] hover:to-[#2B3990] active:bg-[#262262] active:bg-none active:from-transparent active:to-transparent transition"
                          type="submit"
                          aria-describedby="<?php echo esc_attr($form_id_prefix . 'submit-help'); ?>"
                        >
                          <span class="self-stretch my-auto"><?php esc_html_e('Submit', 'matrix-starter'); ?></span>
                        </button>
                        <div id="<?php echo esc_attr($form_id_prefix . 'submit-help'); ?>" class="mt-2 text-xs text-gray-500">
                          <?php esc_html_e('Please fill in all required fields before submitting.', 'matrix-starter'); ?>
                        </div>

                        <div id="<?php echo esc_attr($form_id_prefix . 'form-messages'); ?>" class="hidden mt-4" role="alert" aria-live="polite"></div>
                      </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($offices)) : ?>
<script>
(function () {
  var LEAFLET_CSS = "https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css";
  var LEAFLET_JS  = "https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js";
  var sectionId = <?php echo wp_json_encode($section_id); ?>;
  var MARKER_ICON_URL = <?php echo wp_json_encode(home_url('/wp-content/uploads/2026/03/Location.svg')); ?>;

  function loadLeaflet(cb) {
    if (typeof window.L !== "undefined") { cb(); return; }
    if (!document.querySelector('link[data-leaflet-css]')) {
      var link = document.createElement("link");
      link.rel = "stylesheet";
      link.href = LEAFLET_CSS;
      link.setAttribute("data-leaflet-css", "1");
      document.head.appendChild(link);
    }
    if (document.querySelector('script[data-leaflet-js]')) {
      var t = setInterval(function() { if (typeof window.L !== "undefined") { clearInterval(t); cb(); } }, 30);
      setTimeout(function() { clearInterval(t); }, 8000);
      return;
    }
    var script = document.createElement("script");
    script.src = LEAFLET_JS;
    script.defer = true;
    script.setAttribute("data-leaflet-js", "1");
    script.onload = cb;
    document.body.appendChild(script);
  }

  function initOfficeMap(container) {
    if (!container || container.dataset.officeMapInit === "1") return;
    if (typeof window.L === "undefined") return;

    var zoom = parseInt(container.getAttribute("data-zoom"), 10) || 13;
    var lat0 = parseFloat(container.getAttribute("data-lat"));
    var lng0 = parseFloat(container.getAttribute("data-lng"));
    if (!Number.isFinite(lat0) || !Number.isFinite(lng0)) return;

    var map = L.map(container, { scrollWheelZoom: false, minZoom: 4 }).setView([lat0, lng0], zoom);
    // Light basemap; tinted via CSS for “blueprint” look.
    L.tileLayer("https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png", {
      maxZoom: 20,
      attribution: "&copy; OpenStreetMap &copy; CARTO"
    }).addTo(map);

    var markerIcon = null;
    if (MARKER_ICON_URL) {
      markerIcon = L.icon({
        iconUrl: MARKER_ICON_URL,
        iconSize: [28, 36],
        iconAnchor: [14, 36],
        popupAnchor: [0, -34]
      });
    }

    L.marker([lat0, lng0], markerIcon ? { icon: markerIcon } : {}).addTo(map);

    container.dataset.officeMapInit = "1";
    setTimeout(function() { map.invalidateSize(); }, 50);
    setTimeout(function() { map.invalidateSize(); }, 250);
  }

  function boot() {
    var section = document.getElementById(sectionId);
    if (!section) return;
    var els = section.querySelectorAll("[data-office-map]");
    if (!els || !els.length) return;
    loadLeaflet(function() {
      for (var i = 0; i < els.length; i++) initOfficeMap(els[i]);
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", boot);
  } else {
    boot();
  }
})();
</script>
<?php endif; ?>
