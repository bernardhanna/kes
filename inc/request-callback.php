<?php
/**
 * Request-a-callback modal — admin-post handler + helpers.
 * Mail configuration is read only from Theme Options (not POST).
 */

if (! defined('ABSPATH')) {
  exit;
}

/**
 * @param string|string[] $val
 * @return string[]
 */
function matrix_request_callback_parse_emails($val): array {
  $raw = [];
  if (is_array($val)) {
    foreach ($val as $v) {
      $raw[] = $v;
    }
  } elseif (is_string($val)) {
    $raw = preg_split('/[,\s;]+/u', $val);
  }
  $out = [];
  foreach ($raw as $e) {
    $e = trim((string) $e);
    if ($e === '') {
      continue;
    }
    $e = sanitize_email($e);
    if ($e && is_email($e) && ! in_array($e, $out, true)) {
      $out[] = $e;
    }
  }
  return $out;
}

function matrix_request_callback_is_ajax(): bool {
  return (isset($_POST['is_ajax']) && $_POST['is_ajax'] === '1')
    || (! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
}

function matrix_request_callback_response(bool $ok, string $message = '', array $extra = []): void {
  if (matrix_request_callback_is_ajax()) {
    if ($ok) {
      wp_send_json_success(['message' => $message ?: __('Thank you. We will be in touch soon.', 'matrix-starter')] + $extra);
    }
    wp_send_json_error(['message' => $message ?: __('Something went wrong.', 'matrix-starter')] + $extra);
  }
  $qs = $ok ? 'callback_sent=1' : 'callback_error=1';
  wp_safe_redirect(add_query_arg($qs, wp_get_referer() ?: home_url('/')));
  exit;
}

function matrix_request_callback_captcha_ok(): bool {
  $provider = function_exists('get_field') ? (get_field('captcha_provider', 'option') ?: 'none') : 'none';
  $provider = strtolower((string) $provider);
  if ($provider === 'none') {
    return true;
  }

  if ($provider === 'recaptcha_v3') {
    $token = sanitize_text_field($_POST['g-recaptcha-response'] ?? '');
    if ($token === '') {
      return false;
    }
    $secret = function_exists('get_field') ? get_field('recaptcha_secret_key', 'option') : '';
    $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
      'body'    => [
        'secret'   => $secret,
        'response' => $token,
      ],
      'timeout' => 10,
    ]);
    $json = json_decode(wp_remote_retrieve_body($response), true);
    return ! empty($json['success']);
  }

  if ($provider === 'turnstile') {
    $token = sanitize_text_field($_POST['cf-turnstile-response'] ?? '');
    if ($token === '') {
      return false;
    }
    $secret = function_exists('get_field') ? get_field('turnstile_secret_key', 'option') : '';
    $response = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
      'body'    => [
        'secret'   => $secret,
        'response' => $token,
      ],
      'timeout' => 10,
    ]);
    $json = json_decode(wp_remote_retrieve_body($response), true);
    return ! empty($json['success']);
  }

  return true;
}

/**
 * Build HTML body for visitor auto-reply (logo + WYSIWYG from options).
 */
function matrix_request_callback_build_autoresponder_html(): string {
  $body = function_exists('get_field') ? (string) get_field('callback_autoresponder_message', 'option') : '';
  $body = wp_kses_post($body);

  $logo_html = '';
  $logo_id   = function_exists('get_field') ? (int) get_field('callback_autoresponder_logo', 'option') : 0;
  if ($logo_id) {
    $logo_url = wp_get_attachment_image_url($logo_id, 'medium');
    if ($logo_url) {
      $site = get_bloginfo('name');
      $logo_html = sprintf(
        '<div style="text-align:center;margin-bottom:24px;"><img src="%s" alt="%s" width="200" style="max-width:200px;height:auto;display:inline-block;" /></div>',
        esc_url($logo_url),
        esc_attr($site)
      );
    }
  }

  $wrapper = '<div style="font-family:system-ui,-apple-system,sans-serif;font-size:16px;line-height:1.5;color:#1e293b;">%s%s</div>';
  return sprintf($wrapper, $logo_html, $body !== '' ? $body : '<p>' . esc_html__('Thank you for contacting us.', 'matrix-starter') . '</p>');
}

function matrix_request_callback_handle(): void {
  $nonce = isset($_POST['request_callback_nonce']) ? (string) wp_unslash($_POST['request_callback_nonce']) : '';
  if ($nonce === '' || ! wp_verify_nonce($nonce, 'matrix_request_callback')) {
    matrix_request_callback_response(false, __('Invalid security token. Please reload the page and try again.', 'matrix-starter'));
  }

  $modal_on = function_exists('get_field') ? get_field('callback_modal_enabled', 'option') : null;
  if ($modal_on === false || $modal_on === 0 || $modal_on === '0') {
    matrix_request_callback_response(false, __('This form is not available.', 'matrix-starter'));
  }

  if (! matrix_request_callback_captcha_ok()) {
    matrix_request_callback_response(false, __('Captcha verification failed.', 'matrix-starter'));
  }

  $uid_raw = isset($_POST['_submission_uid']) ? sanitize_text_field(wp_unslash($_POST['_submission_uid'])) : '';
  if ($uid_raw === '') {
    matrix_request_callback_response(false, __('Missing submission token.', 'matrix-starter'));
  }

  $fullname = isset($_POST['fullname']) ? sanitize_text_field(wp_unslash($_POST['fullname'])) : '';
  $email    = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
  $phone    = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
  $privacy  = ! empty($_POST['privacy_agreement']);

  if ($fullname === '' || $email === '' || ! is_email($email) || $phone === '' || ! $privacy) {
    matrix_request_callback_response(false, __('Please complete all required fields.', 'matrix-starter'));
  }

  // Idempotency only after validation (avoid consuming the lock on failed attempts).
  $lock_key = 'matrix_request_callback_lock_' . md5($uid_raw);
  $first    = false;
  if (function_exists('wp_cache_add')) {
    $first = wp_cache_add($lock_key, 1, '', 600);
  } else {
    if (! get_transient($lock_key)) {
      set_transient($lock_key, 1, 600);
      $first = true;
    }
  }
  if (! $first) {
    matrix_request_callback_response(true, __('Already received — thank you.', 'matrix-starter'));
  }

  $to = function_exists('get_field') ? get_field('callback_notify_to', 'option') : '';
  $to = is_string($to) ? sanitize_email($to) : '';
  if (! $to || ! is_email($to)) {
    $to = sanitize_email((string) get_option('admin_email'));
  }

  $cc_list  = matrix_request_callback_parse_emails((string) (function_exists('get_field') ? get_field('callback_notify_cc', 'option') : ''));
  $bcc_list = matrix_request_callback_parse_emails((string) (function_exists('get_field') ? get_field('callback_notify_bcc', 'option') : ''));

  $subject = function_exists('get_field') ? trim((string) get_field('callback_email_subject', 'option')) : '';
  if ($subject === '') {
    $subject = __('New callback request', 'matrix-starter');
  }
  $subject = wp_strip_all_tags($subject);

  $from_name  = function_exists('get_field') ? trim((string) get_field('email_from_name', 'option')) : get_bloginfo('name');
  $from_email = function_exists('get_field') ? sanitize_email((string) get_field('email_from_address', 'option')) : '';
  if (! $from_email) {
    $host       = parse_url(home_url(), PHP_URL_HOST) ?: 'localhost';
    $from_email = 'no-reply@' . $host;
  }

  $headers   = ['Content-Type: text/html; charset=UTF-8'];
  $headers[] = 'From: ' . sprintf('%s <%s>', $from_name, $from_email);
  $headers[] = 'Reply-To: ' . $email;
  foreach ($cc_list as $cc) {
    $headers[] = 'Cc: ' . $cc;
  }
  foreach ($bcc_list as $bcc) {
    $headers[] = 'Bcc: ' . $bcc;
  }

  ob_start();
  echo '<h2>' . esc_html__('Callback request', 'matrix-starter') . '</h2><table style="border-collapse:collapse;">';
  printf(
    '<tr><th style="text-align:left;padding:8px;border:1px solid #e5e7eb;">%s</th><td style="padding:8px;border:1px solid #e5e7eb;">%s</td></tr>',
    esc_html__('Full name', 'matrix-starter'),
    esc_html($fullname)
  );
  printf(
    '<tr><th style="text-align:left;padding:8px;border:1px solid #e5e7eb;">%s</th><td style="padding:8px;border:1px solid #e5e7eb;">%s</td></tr>',
    esc_html__('Email', 'matrix-starter'),
    esc_html($email)
  );
  printf(
    '<tr><th style="text-align:left;padding:8px;border:1px solid #e5e7eb;">%s</th><td style="padding:8px;border:1px solid #e5e7eb;">%s</td></tr>',
    esc_html__('Phone', 'matrix-starter'),
    esc_html($phone)
  );
  echo '</table>';
  $message = ob_get_clean();

  $mail_guard_key = 'matrix_request_callback_mail_' . md5($uid_raw);
  if (function_exists('wp_cache_add')) {
    if (! wp_cache_add($mail_guard_key, 1, '', 600)) {
      matrix_request_callback_response(true, __('Already received — thank you.', 'matrix-starter'));
    }
  } else {
    if (get_transient($mail_guard_key)) {
      matrix_request_callback_response(true, __('Already received — thank you.', 'matrix-starter'));
    }
    set_transient($mail_guard_key, 1, 600);
  }

  $phpmailer_sender = $from_email;
  $set_sender       = static function ($phpmailer) use ($phpmailer_sender) {
    $phpmailer->Sender = $phpmailer_sender;
  };
  add_action('phpmailer_init', $set_sender);

  $from_filter      = static function () use ($from_email) {
    return $from_email;
  };
  $from_name_filter = static function () use ($from_name) {
    return $from_name;
  };
  add_filter('wp_mail_from', $from_filter);
  add_filter('wp_mail_from_name', $from_name_filter);

  $mail_error = '';
  $mail_err_cb = static function ($wp_error) use (&$mail_error) {
    if ($wp_error instanceof \WP_Error) {
      $mail_error = $wp_error->get_error_message();
    }
  };
  add_action('wp_mail_failed', $mail_err_cb);

  $sent = wp_mail($to, $subject, $message, $headers);

  remove_action('wp_mail_failed', $mail_err_cb);
  remove_filter('wp_mail_from', $from_filter);
  remove_filter('wp_mail_from_name', $from_name_filter);
  remove_action('phpmailer_init', $set_sender);

  if (! $sent) {
    if (function_exists('wp_cache_delete')) {
      wp_cache_delete($lock_key, '');
      wp_cache_delete($mail_guard_key, '');
    } else {
      delete_transient($lock_key);
      delete_transient($mail_guard_key);
    }
    matrix_request_callback_response(
      false,
      __('We could not send your message. Please try again later.', 'matrix-starter'),
      [
        'mail_error' => $mail_error,
        'to'         => $to,
      ]
    );
  }

  $auto_on = function_exists('get_field') && get_field('callback_autoresponder_enabled', 'option');
  if ($auto_on) {
    $auto_subject = function_exists('get_field') ? trim((string) get_field('callback_autoresponder_subject', 'option')) : '';
    if ($auto_subject === '') {
      $auto_subject = __('We received your request', 'matrix-starter');
    }
    $auto_subject = wp_strip_all_tags($auto_subject);
    $auto_body    = matrix_request_callback_build_autoresponder_html();
    $auto_headers = ['Content-Type: text/html; charset=UTF-8', 'From: ' . sprintf('%s <%s>', $from_name, $from_email)];

    add_filter('wp_mail_from', $from_filter);
    add_filter('wp_mail_from_name', $from_name_filter);
    add_action('phpmailer_init', $set_sender);
    wp_mail($email, $auto_subject, $auto_body, $auto_headers);
    remove_action('phpmailer_init', $set_sender);
    remove_filter('wp_mail_from', $from_filter);
    remove_filter('wp_mail_from_name', $from_name_filter);
  }

  matrix_request_callback_response(true, __('Thank you! Your request has been sent.', 'matrix-starter'));
}

add_action('admin_post_nopriv_matrix_request_callback', 'matrix_request_callback_handle');
add_action('admin_post_matrix_request_callback', 'matrix_request_callback_handle');
