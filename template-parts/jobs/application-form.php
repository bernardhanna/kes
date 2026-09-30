<?php
/**
 * Job application form — Figma node 784:2889.
 *
 * @var int $job_id Job post ID (passed via get_template_part $args).
 */

if (! defined('ABSPATH')) {
    exit;
}

$job_id = matrix_resolve_job_application_id(
    isset($job_id) ? (int) $job_id : (isset($args) && is_array($args) && ! empty($args['job_id']) ? (int) $args['job_id'] : 0)
);
if ($job_id <= 0) {
    return;
}

$config = matrix_job_application_config($job_id);

$section_id       = 'job-application-' . $job_id;
$form_id_prefix   = 'job-app-' . $job_id . '-';
$theme_form_id    = 'job-' . $job_id;
$submission_uid   = wp_generate_uuid4();
$countries        = matrix_job_application_countries();

$captcha_provider = function_exists('get_field')
    ? strtolower((string) (get_field('captcha_provider', 'option') ?: 'none'))
    : 'none';

$input_wrap  = 'flex items-center w-full bg-white rounded border border-[#667085] border-solid min-h-[52px]';
$input_cls   = 'flex-1 w-full px-4 py-3 text-[#1D2939] bg-transparent border-none outline-none focus:ring-0 font-red-hat-text text-[16px] font-normal leading-[20px] placeholder:font-red-hat-text placeholder:text-[16px] placeholder:font-normal placeholder:leading-[20px] placeholder:text-[color:var(--Gray-500,#667085)]';
$select_cls  = 'flex-1 w-full appearance-none bg-none bg-white px-4 py-3 pr-10 text-[#1D2939] border-none outline-none focus:ring-0 font-red-hat-text text-[16px] font-normal leading-[20px] [background-image:none]';
$label_cls   = 'block w-full font-red-hat-text text-[16px] font-medium leading-[22px] text-[color:var(--Gray-700,#344054)]';
$cv_hint_cls = 'font-red-hat-text text-[16px] font-normal leading-[20px] text-[color:var(--Gray-500,#667085)]';
$btn_cls     = 'font-red-hat-text text-[18px] font-medium leading-[24px] text-[#FFF]';
$field_gap   = 'flex flex-col gap-1 w-full';
?>

<section
    id="apply"
    class="pb-20 bg-white job-application scroll-mt-24 px-5"
    aria-labelledby="<?php echo esc_attr($section_id); ?>-heading"
>
    <div class="mx-auto w-full max-w-container px-4 sm:px-8 lg:px-16">
        <div class="rounded-2xl border-4 border-solid border-[#CBE9E1] px-6 py-10 sm:px-12 lg:px-[72px] lg:py-14">
            <div class="flex flex-col gap-8 items-start lg:flex-row lg:gap-16">
                <div class="relative order-1 h-[380px] w-full overflow-hidden rounded-lg lg:order-2 lg:h-auto lg:min-h-[400px] lg:flex-1 xl:min-h-[600px]">
                    <img
                        src="<?php echo esc_url($config['image_url']); ?>"
                        alt="<?php echo esc_attr(
                            $config['job_title'] !== ''
                                ? sprintf(__('Applying for %s', 'matrix-starter'), $config['job_title'])
                                : __('Job application', 'matrix-starter')
                        ); ?>"
                        class="h-[380px] w-full rounded-lg object-cover lg:h-full"
                        loading="lazy"
                        decoding="async"
                    />
                </div>

                <div class="flex order-2 w-full max-w-[377.5px] flex-col gap-4 lg:order-1 lg:shrink-0">
                    <form
                        class="flex flex-col gap-4 w-full"
                        action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
                        method="post"
                        enctype="multipart/form-data"
                        data-theme-form="<?php echo esc_attr($theme_form_id); ?>"
                        novalidate
                    >
                        <input type="hidden" name="action" value="theme_form_submit">
                        <input type="hidden" name="theme_form_nonce" value="<?php echo esc_attr(wp_create_nonce('theme_form_submit')); ?>">
                        <input type="hidden" name="_theme_form_id" value="<?php echo esc_attr($theme_form_id); ?>">
                        <input type="hidden" name="_submission_uid" value="<?php echo esc_attr($submission_uid); ?>">
                        <input type="hidden" name="_theme_form_name" value="<?php echo esc_attr($config['form_name']); ?>">
                        <input type="hidden" name="job_id" value="<?php echo esc_attr((string) $job_id); ?>">
                        <input type="hidden" name="job_position" value="<?php echo esc_attr($config['job_title']); ?>">
                        <input type="hidden" name="job_url" value="<?php echo esc_url($config['job_url']); ?>">

                        <div class="mb-2 w-full hidden rounded-lg border border-solid border-[#CBE9E1] bg-[#CBE9E1]/40 px-4 py-3" role="status">
                            <p class="font-red-hat-text text-[14px] font-normal leading-[20px] text-[#344054]">
                                <?php esc_html_e('Applying for', 'matrix-starter'); ?>
                            </p>
                            <p class="font-red-hat-display text-[20px] font-bold leading-[26px] text-[#262262]">
                                <?php echo esc_html($config['job_title']); ?>
                            </p>
                        </div>

                        <?php if ($config['save_to_db']) : ?>
                            <input type="hidden" name="_theme_save_to_db" value="1">
                        <?php endif; ?>

                        <input type="hidden" name="_cfg_to" value="<?php echo esc_attr($config['email_to']); ?>">
                        <input type="hidden" name="_cfg_subject" value="<?php echo esc_attr($config['email_subject']); ?>">

                        <?php if ($config['enable_autoresponder']) : ?>
                            <input type="hidden" name="_cfg_auto_enabled" value="1">
                            <input type="hidden" name="_cfg_auto_subject" value="<?php echo esc_attr($config['autoresponder_subject']); ?>">
                            <input type="hidden" name="_cfg_auto_message" value="<?php echo esc_attr($config['autoresponder_message']); ?>">
                        <?php endif; ?>

                        <div class="<?php echo esc_attr($field_gap); ?>">
                            <label class="<?php echo esc_attr($label_cls); ?>" for="<?php echo esc_attr($form_id_prefix . 'fullname'); ?>">
                                <?php esc_html_e('Full name', 'matrix-starter'); ?><span aria-hidden="true">*</span>
                            </label>
                            <div class="<?php echo esc_attr($input_wrap); ?>">
                                <input
                                    id="<?php echo esc_attr($form_id_prefix . 'fullname'); ?>"
                                    class="<?php echo esc_attr($input_cls); ?>"
                                    type="text"
                                    name="fullname"
                                    required
                                    autocomplete="given-name"
                                    placeholder="<?php esc_attr_e('Joe', 'matrix-starter'); ?>"
                                />
                            </div>
                        </div>

                        <div class="<?php echo esc_attr($field_gap); ?>">
                            <label class="<?php echo esc_attr($label_cls); ?>" for="<?php echo esc_attr($form_id_prefix . 'surname'); ?>">
                                <?php esc_html_e('Surname', 'matrix-starter'); ?><span aria-hidden="true">*</span>
                            </label>
                            <div class="<?php echo esc_attr($input_wrap); ?>">
                                <input
                                    id="<?php echo esc_attr($form_id_prefix . 'surname'); ?>"
                                    class="<?php echo esc_attr($input_cls); ?>"
                                    type="text"
                                    name="surname"
                                    required
                                    autocomplete="family-name"
                                    placeholder="<?php esc_attr_e('Bloggs', 'matrix-starter'); ?>"
                                />
                            </div>
                        </div>

                        <div class="<?php echo esc_attr($field_gap); ?>">
                            <label class="<?php echo esc_attr($label_cls); ?>" for="<?php echo esc_attr($form_id_prefix . 'email'); ?>">
                                <?php esc_html_e('Email', 'matrix-starter'); ?><span aria-hidden="true">*</span>
                            </label>
                            <div class="<?php echo esc_attr($input_wrap); ?>">
                                <input
                                    id="<?php echo esc_attr($form_id_prefix . 'email'); ?>"
                                    class="<?php echo esc_attr($input_cls); ?>"
                                    type="email"
                                    name="email"
                                    required
                                    autocomplete="email"
                                    placeholder="<?php esc_attr_e('Email', 'matrix-starter'); ?>"
                                />
                            </div>
                        </div>

                        <div class="<?php echo esc_attr($field_gap); ?>">
                            <label class="<?php echo esc_attr($label_cls); ?>" for="<?php echo esc_attr($form_id_prefix . 'phone'); ?>">
                                <?php esc_html_e('Phone number', 'matrix-starter'); ?><span aria-hidden="true">*</span>
                            </label>
                            <div class="<?php echo esc_attr($input_wrap); ?>">
                                <input
                                    id="<?php echo esc_attr($form_id_prefix . 'phone'); ?>"
                                    class="<?php echo esc_attr($input_cls); ?>"
                                    type="tel"
                                    name="phone"
                                    required
                                    autocomplete="tel"
                                    placeholder="<?php esc_attr_e('+353 86 012 1234', 'matrix-starter'); ?>"
                                />
                            </div>
                        </div>

                        <div class="<?php echo esc_attr($field_gap); ?>">
                            <label class="<?php echo esc_attr($label_cls); ?>" for="<?php echo esc_attr($form_id_prefix . 'city'); ?>">
                                <?php esc_html_e('City', 'matrix-starter'); ?><span aria-hidden="true">*</span>
                            </label>
                            <div class="<?php echo esc_attr($input_wrap); ?>">
                                <input
                                    id="<?php echo esc_attr($form_id_prefix . 'city'); ?>"
                                    class="<?php echo esc_attr($input_cls); ?>"
                                    type="text"
                                    name="city"
                                    required
                                    autocomplete="address-level2"
                                    placeholder="<?php esc_attr_e('Dublin', 'matrix-starter'); ?>"
                                />
                            </div>
                        </div>

                        <div class="<?php echo esc_attr($field_gap); ?>">
                            <label class="<?php echo esc_attr($label_cls); ?>" for="<?php echo esc_attr($form_id_prefix . 'country'); ?>">
                                <?php esc_html_e('Country', 'matrix-starter'); ?><span aria-hidden="true">*</span>
                            </label>
                            <div class="<?php echo esc_attr($input_wrap); ?> relative">
                                <select
                                    id="<?php echo esc_attr($form_id_prefix . 'country'); ?>"
                                    class="<?php echo esc_attr($select_cls); ?>"
                                    name="country"
                                    required
                                >
                                    <?php foreach ($countries as $value => $label) : ?>
                                        <option value="<?php echo esc_attr($value); ?>"<?php selected($value, 'Ireland'); ?>>
                                            <?php echo esc_html($label); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 h-6 w-6 -translate-y-1/2 shrink-0 text-[#667085]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 w-full">
                            <span class="<?php echo esc_attr($label_cls); ?>" id="<?php echo esc_attr($form_id_prefix . 'cv-label'); ?>">
                                <?php esc_html_e('CV', 'matrix-starter'); ?><span aria-hidden="true">*</span>
                            </span>
                            <label
                                class="job-application__cv-drop flex cursor-pointer flex-col items-center justify-center gap-2 rounded-lg border border-solid border-[#667085] px-8 py-12 text-center transition-colors hover:border-[#2B3990] hover:bg-[#F2F5F7]/50 data-[drag-active=true]:border-[#2B3990] data-[drag-active=true]:bg-[#F2F5F7]/50"
                                for="<?php echo esc_attr($form_id_prefix . 'cv'); ?>"
                            >
                                <svg class="h-6 w-6 text-[#2B3990]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M4 16L4 17C4 18.6569 5.34315 20 7 20L17 20C18.6569 20 20 18.6569 20 17L20 16M16 8L12 4M12 4L8 8M12 4L12 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="<?php echo esc_attr($cv_hint_cls); ?>" data-cv-label>
                                    <?php esc_html_e('Drop your C.V here, or upload', 'matrix-starter'); ?>
                                </span>
                                <input
                                    id="<?php echo esc_attr($form_id_prefix . 'cv'); ?>"
                                    class="sr-only"
                                    type="file"
                                    name="cv"
                                    required
                                    accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                    aria-labelledby="<?php echo esc_attr($form_id_prefix . 'cv-label'); ?>"
                                    data-max-mb="<?php echo esc_attr((string) $config['cv_max_mb']); ?>"
                                />
                            </label>
                            <p class="<?php echo esc_attr($cv_hint_cls); ?>">
                                <?php
                                printf(
                                    /* translators: %d: max file size in megabytes */
                                    esc_html__('Info: Max size: %dmb', 'matrix-starter'),
                                    (int) $config['cv_max_mb']
                                );
                                ?>
                            </p>
                        </div>

                        <div class="<?php echo esc_attr($field_gap); ?>">
                            <label class="<?php echo esc_attr($label_cls); ?>" for="<?php echo esc_attr($form_id_prefix . 'cover_letter'); ?>">
                                <?php esc_html_e('Cover Letter', 'matrix-starter'); ?>
                            </label>
                            <div class="flex min-h-[165px] w-full rounded border border-solid border-[#667085] bg-white px-4 py-3">
                                <textarea
                                    id="<?php echo esc_attr($form_id_prefix . 'cover_letter'); ?>"
                                    class="<?php echo esc_attr($input_cls); ?> min-h-[140px] resize-y px-0"
                                    name="cover_letter"
                                    rows="6"
                                    placeholder="<?php esc_attr_e('Optional cover letter', 'matrix-starter'); ?>"
                                ></textarea>
                            </div>
                        </div>

                        <?php if ($captcha_provider === 'turnstile') : ?>
                            <div class="cf-turnstile" data-size="normal" data-theme="light"></div>
                        <?php endif; ?>

                        <div class="flex w-full max-w-[362px] items-center gap-2">
                            <input
                                id="<?php echo esc_attr($form_id_prefix . 'privacy-policy'); ?>"
                                class="h-6 w-6 shrink-0 rounded border border-solid border-[#667085] text-[#2B3990] focus:ring-2 focus:ring-[#00ACD8]"
                                type="checkbox"
                                name="privacy-policy"
                                value="1"
                                required
                            />
                            <label class="cursor-pointer font-red-hat-text text-[14px] font-normal leading-[20px] text-[#475467]" for="<?php echo esc_attr($form_id_prefix . 'privacy-policy'); ?>">
                                <?php esc_html_e('By submitting, you agree with the', 'matrix-starter'); ?>
                                <a class="underline hover:text-[#2B3990] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#00ACD8]" href="<?php echo esc_url($config['privacy_url']); ?>">
                                    <?php esc_html_e('Privacy Policy', 'matrix-starter'); ?>
                                </a>
                            </label>
                        </div>

                        <button
                            type="submit"
                            class="btn flex h-[52px] w-full items-center justify-center gap-2 rounded-[100px] bg-gradient-to-r from-[#2B3990] to-[#006EC8] px-6 py-4 transition hover:from-[#006EC8] hover:to-[#2B3990] active:bg-[#262262] active:bg-none focus:outline-none focus-visible:ring-2 focus-visible:ring-[#00ACD8] focus-visible:ring-offset-2"
                        >
                            <span class="<?php echo esc_attr($btn_cls); ?>">
                                <?php esc_html_e('Apply now', 'matrix-starter'); ?>
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
(function () {
  var root = document.getElementById('apply');
  if (!root) return;

  var input = root.querySelector('input[type="file"][name="cv"]');
  var dropZone = root.querySelector('.job-application__cv-drop');
  var label = root.querySelector('[data-cv-label]');
  if (!input || !dropZone || !label) return;

  var defaultText = <?php echo wp_json_encode(__('Drop your C.V here, or upload', 'matrix-starter')); ?>;
  var tooLargeText = <?php echo wp_json_encode(__('File is too large. Please choose a smaller file.', 'matrix-starter')); ?>;
  var maxBytes = parseInt(input.getAttribute('data-max-mb') || '5', 10) * 1024 * 1024;

  function setDragActive(active) {
    dropZone.setAttribute('data-drag-active', active ? 'true' : 'false');
  }

  function assignFile(file) {
    if (!file) {
      label.textContent = defaultText;
      return;
    }
    if (file.size > maxBytes) {
      alert(tooLargeText);
      input.value = '';
      label.textContent = defaultText;
      return;
    }
    try {
      var dt = new DataTransfer();
      dt.items.add(file);
      input.files = dt.files;
    } catch (e) {
      return;
    }
    label.textContent = file.name;
    input.dispatchEvent(new Event('change', { bubbles: true }));
  }

  input.addEventListener('change', function () {
    assignFile(input.files && input.files[0] ? input.files[0] : null);
  });

  ['dragenter', 'dragover'].forEach(function (eventName) {
    dropZone.addEventListener(eventName, function (e) {
      e.preventDefault();
      e.stopPropagation();
      setDragActive(true);
    });
  });

  ['dragleave', 'drop'].forEach(function (eventName) {
    dropZone.addEventListener(eventName, function (e) {
      e.preventDefault();
      e.stopPropagation();
      setDragActive(false);
    });
  });

  dropZone.addEventListener('drop', function (e) {
    var file = e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0];
    if (file) {
      assignFile(file);
    }
  });
})();
</script>
