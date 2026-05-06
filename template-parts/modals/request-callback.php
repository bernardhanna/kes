<?php
/**
 * Request-a-callback modal (Theme Options → Request a callback).
 * Open from any link/button with class .request-call or attribute data-request-callback.
 */

if (! defined('ABSPATH')) {
  exit;
}

if (! function_exists('get_field')) {
  return;
}
$callback_modal_on = get_field('callback_modal_enabled', 'option');
if ($callback_modal_on === false || $callback_modal_on === 0 || $callback_modal_on === '0') {
  return;
}

$title        = get_field('callback_form_title', 'option') ?: __('Request a call back', 'matrix-starter');
$intro        = get_field('callback_form_intro', 'option') ?: '';
$privacy_url  = get_field('callback_privacy_policy_url', 'option');
$privacy_url  = is_string($privacy_url) ? esc_url($privacy_url) : '';
$nonce        = wp_create_nonce('matrix_request_callback');
$uid          = wp_generate_uuid4();
$action_url   = esc_url(admin_url('admin-post.php'));
$captcha      = function_exists('get_field') ? strtolower((string) (get_field('captcha_provider', 'option') ?: 'none')) : 'none';

if ($privacy_url) {
  $privacy_inner = wp_kses(
    sprintf(
      /* translators: %s: privacy policy URL */
      __('By submitting, you agree with the <a href="%s" class="underline text-primary">Privacy Policy</a>.', 'matrix-starter'),
      $privacy_url
    ),
    [
      'a' => [
        'href'  => true,
        'class' => true,
      ],
    ]
  );
} else {
  $privacy_inner = esc_html__('By submitting, you agree with the Privacy Policy.', 'matrix-starter');
}
?>
<div
  id="request-callback-modal"
  class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
  x-data="matrixRequestCallbackModal()"
  x-show="open"
  x-cloak
  x-transition.opacity
  @keydown.escape.window="if (open) close()"
  role="presentation"
>
  <div
    class="absolute inset-0 bg-slate-900/60"
    @click="close()"
    aria-hidden="true"
  ></div>

  <div
    class="relative z-10 flex w-full max-w-screen-md flex-col overflow-y-auto max-h-[90vh] rounded-2xl bg-white shadow-xl"
    @click.stop
    role="dialog"
    aria-modal="true"
    aria-labelledby="request-callback-form-title"
    x-ref="panel"
  >
    <section class="relative flex overflow-hidden">
      <div class="flex w-full flex-col items-center mx-auto max-w-container pt-5 pb-5 max-lg:px-5">
        <form
          class="relative flex w-full flex-col justify-center px-6 py-10 sm:px-14 sm:py-12 max-w-screen-md bg-white rounded-2xl max-md:px-5"
          method="post"
          action="<?php echo esc_url($action_url); ?>"
          data-matrix-request-callback="1"
          novalidate
        >
          <input type="hidden" name="action" value="matrix_request_callback" />
          <input type="hidden" name="request_callback_nonce" value="<?php echo esc_attr($nonce); ?>" />
          <input type="hidden" name="_submission_uid" value="<?php echo esc_attr($uid); ?>" />

          <div class="flex z-0 flex-col w-full max-md:max-w-full">
            <header class="flex flex-col justify-center w-full max-md:max-w-full pr-10">
              <div class="w-full text-3xl sm:text-4xl font-bold tracking-tighter leading-none text-primary max-md:max-w-full">
                <h2 id="request-callback-form-title" class="text-primary max-md:max-w-full">
                  <?php echo esc_html($title); ?>
                </h2>
                <div class="flex mt-1 w-8 bg-cyan-500 min-h-1" aria-hidden="true"></div>
              </div>
              <?php if ($intro !== '') : ?>
                <p class="mt-6 text-lg leading-6 text-slate-800 max-md:max-w-full">
                  <?php echo esc_html($intro); ?>
                </p>
              <?php endif; ?>
            </header>

            <div class="mt-4 w-full text-base max-md:max-w-full">
              <label for="request-callback-fullname" class="flex justify-between items-center w-full font-medium leading-none text-slate-700 max-md:max-w-full">
                <span class="flex-1 shrink self-stretch my-auto basis-0 text-slate-700 max-md:max-w-full">
                  <?php esc_html_e('Full name', 'matrix-starter'); ?><span class="text-red-600" aria-hidden="true">*</span>
                </span>
              </label>
              <div class="mt-1 w-full leading-none text-gray-500 max-md:max-w-full">
                <input
                  type="text"
                  id="request-callback-fullname"
                  name="fullname"
                  required
                  autocomplete="name"
                  placeholder="<?php esc_attr_e('Your name', 'matrix-starter'); ?>"
                  class="flex justify-between items-center p-4 w-full bg-white rounded border border-gray-500 border-solid min-h-[52px] max-md:max-w-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 focus:border-cyan-500"
                />
              </div>
            </div>

            <div class="mt-4 w-full text-base max-md:max-w-full">
              <label for="request-callback-email" class="flex justify-between items-center w-full font-medium leading-none text-slate-700 max-md:max-w-full">
                <span class="flex-1 shrink self-stretch my-auto basis-0 text-slate-700 max-md:max-w-full">
                  <?php esc_html_e('Email', 'matrix-starter'); ?><span class="text-red-600" aria-hidden="true">*</span>
                </span>
              </label>
              <div class="mt-1 w-full leading-none text-gray-500 max-md:max-w-full">
                <input
                  type="email"
                  id="request-callback-email"
                  name="email"
                  required
                  autocomplete="email"
                  placeholder="<?php esc_attr_e('Email', 'matrix-starter'); ?>"
                  class="flex justify-between items-center p-4 w-full bg-white rounded border border-gray-500 border-solid min-h-[52px] max-md:max-w-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 focus:border-cyan-500"
                />
              </div>
            </div>

            <div class="mt-4 w-full text-base max-md:max-w-full">
              <label for="request-callback-phone" class="flex justify-between items-center w-full font-medium leading-none text-slate-700 max-md:max-w-full">
                <span class="flex-1 shrink self-stretch my-auto basis-0 text-slate-700 max-md:max-w-full">
                  <?php esc_html_e('Phone number', 'matrix-starter'); ?><span class="text-red-600" aria-hidden="true">*</span>
                </span>
              </label>
              <div class="mt-1 w-full leading-none text-gray-500 max-md:max-w-full">
                <input
                  type="tel"
                  id="request-callback-phone"
                  name="phone"
                  required
                  autocomplete="tel"
                  placeholder="<?php esc_attr_e('Phone', 'matrix-starter'); ?>"
                  class="flex justify-between items-center p-4 w-full bg-white rounded border border-gray-500 border-solid min-h-[52px] max-md:max-w-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 focus:border-cyan-500"
                />
              </div>
            </div>

            <?php if ($captcha === 'turnstile') : ?>
              <div class="cf-turnstile mt-4" data-size="normal" data-theme="light"></div>
            <?php endif; ?>

            <div class="flex flex-wrap gap-2 items-start self-start mt-4 max-md:max-w-full">
              <input
                type="checkbox"
                id="request-callback-privacy"
                name="privacy_agreement"
                value="1"
                required
                class="mt-1 h-4 w-4 shrink-0 rounded border-gray-500 text-primary focus:ring-cyan-500"
              />
              <label for="request-callback-privacy" class="max-w-full cursor-pointer text-sm leading-snug text-slate-600">
                <?php echo wp_kses_post($privacy_inner); ?>
              </label>
            </div>

            <button
              type="submit"
              class="btn flex gap-2 justify-center items-center px-6 py-3.5 mt-6 w-full sm:w-fit text-lg font-medium leading-none text-white min-h-[52px] rounded-[100px] max-md:px-5 bg-gradient-to-r from-primary to-blue-bright hover:opacity-95 transition-opacity duration-200 whitespace-nowrap"
            >
              <?php esc_html_e('Request a callback', 'matrix-starter'); ?>
            </button>

            <div id="request-callback-form-status" class="sr-only" role="status" aria-live="polite"></div>
          </div>

          <button
            type="button"
            class="btn absolute right-4 top-4 z-10 flex h-9 w-9 items-center justify-center rounded-full border-0 bg-gray-100 p-0 text-slate-700 hover:bg-gray-200 transition-colors"
            @click="close()"
            aria-label="<?php esc_attr_e('Close', 'matrix-starter'); ?>"
          >
            <span class="sr-only"><?php esc_html_e('Close', 'matrix-starter'); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" />
            </svg>
          </button>
        </form>
      </div>
    </section>
  </div>
</div>

<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('matrixRequestCallbackModal', () => ({
      open: false,
      _opener: null,
      init() {
        this.$watch('open', (value) => {
          if (value) {
            document.body.style.overflow = 'hidden';
            this.$nextTick(() => {
              const el = this.$refs.panel && this.$refs.panel.querySelector('input:not([type="hidden"])');
              if (el) el.focus();
            });
          } else {
            document.body.style.overflow = '';
            if (this._opener && typeof this._opener.focus === 'function') {
              try { this._opener.focus(); } catch (e) {}
            }
            this._opener = null;
          }
        });
        this._onDocClick = (e) => {
          // Match .request-call on <li> (WP menus) or <a>/<button>, or data-request-callback.
          const t = e.target.closest('.request-call, [data-request-callback]');
          if (!t) return;
          e.preventDefault();
          this._opener = document.activeElement;
          this.open = true;
        };
        document.addEventListener('click', this._onDocClick, true);
      },
      close() {
        this.open = false;
      },
    }));
  });
</script>
