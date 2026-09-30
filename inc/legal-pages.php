<?php
/**
 * Default legal page content (Ireland / GDPR) and one-time sync to WordPress pages.
 *
 * Pages: privacy-notice, cookie-policy, accessibility
 */

declare(strict_types=1);

const MATRIX_STARTER_LEGAL_PAGES_VERSION = '1';

/**
 * @return array<string, array{title: string, content: callable(): string}>
 */
function matrix_starter_legal_page_definitions(): array
{
    return [
        'privacy-notice' => [
            'title'   => __('Privacy Policy', 'matrix-starter'),
            'content' => 'matrix_starter_legal_privacy_notice_content',
        ],
        'cookie-policy' => [
            'title'   => __('Cookie Policy', 'matrix-starter'),
            'content' => 'matrix_starter_legal_cookie_policy_content',
        ],
        'accessibility' => [
            'title'   => __('Accessibility', 'matrix-starter'),
            'content' => 'matrix_starter_legal_accessibility_content',
        ],
    ];
}

/**
 * Seed or refresh legal pages when version changes or content is still placeholder.
 */
function matrix_starter_seed_legal_pages(): void
{
    $stored_version = (string) get_option('matrix_starter_legal_pages_version', '');
    if ($stored_version === MATRIX_STARTER_LEGAL_PAGES_VERSION) {
        return;
    }

    foreach (matrix_starter_legal_page_definitions() as $slug => $definition) {
        $page = get_page_by_path($slug, OBJECT, 'page');
        if (! $page instanceof WP_Post) {
            continue;
        }

        $should_update = matrix_starter_legal_page_needs_update($page, $slug);
        if (! $should_update) {
            continue;
        }

        $content_callback = $definition['content'];
        $content          = is_callable($content_callback) ? (string) call_user_func($content_callback) : '';

        wp_update_post(
            [
                'ID'           => $page->ID,
                'post_title'   => $definition['title'],
                'post_content' => $content,
            ],
            true
        );
    }

    update_option('matrix_starter_legal_pages_version', MATRIX_STARTER_LEGAL_PAGES_VERSION, false);
}
add_action('init', 'matrix_starter_seed_legal_pages', 99);

/**
 * @param WP_Post $page
 */
function matrix_starter_legal_page_needs_update(WP_Post $page, string $slug): bool
{
    $content = trim((string) $page->post_content);

    if ($content === '') {
        return true;
    }

    if ($slug === 'privacy-notice' && matrix_starter_is_default_privacy_policy_content($content)) {
        return true;
    }

    $stored_version = (string) get_option('matrix_starter_legal_pages_version', '');
    return $stored_version !== MATRIX_STARTER_LEGAL_PAGES_VERSION;
}

function matrix_starter_is_default_privacy_policy_content(string $content): bool
{
    return str_contains($content, 'privacy-policy-tutorial')
        || str_contains($content, 'Suggested text:');
}

/**
 * @return array{email: string, phone: string, phone_display: string, company: string, site_name: string}
 */
function matrix_starter_legal_contact_details(): array
{
    return [
        'company'       => 'KES Group',
        'site_name'     => get_bloginfo('name') ?: 'KES Group',
        'email'         => 'info@Kes.ie',
        'phone'         => '+353830458746',
        'phone_display' => '+353 83 045 8746',
    ];
}

/**
 * @param list<string> $parts
 */
function matrix_starter_legal_join_content(array $parts): string
{
    return implode("\n\n", array_filter($parts));
}

/**
 * @param string $text
 */
function matrix_starter_legal_heading(int $level, string $text): string
{
    $level = max(2, min(4, $level));
    $tag   = 'h' . $level;

    return sprintf(
        '<!-- wp:heading {"level":%1$d} --><%2$s class="wp-block-heading">%3$s</%2$s><!-- /wp:heading -->',
        $level,
        $tag,
        esc_html($text)
    );
}

/**
 * @param string $html Allowed basic HTML (links, strong, etc.)
 */
function matrix_starter_legal_paragraph(string $html): string
{
    return sprintf('<!-- wp:paragraph --><p>%s</p><!-- /wp:paragraph -->', $html);
}

function matrix_starter_legal_list(array $items): string
{
    $lis = '';
    foreach ($items as $item) {
        $lis .= '<li>' . $item . '</li>';
    }

    return sprintf('<!-- wp:list --><ul class="wp-block-list">%s</ul><!-- /wp:list -->', $lis);
}

function matrix_starter_legal_privacy_notice_content(): string
{
    $d              = matrix_starter_legal_contact_details();
    $site_url       = esc_url(home_url('/'));
    $cookie_url     = esc_url(home_url('/cookie-policy/'));
    $access_url     = esc_url(home_url('/accessibility/'));
    $email          = esc_html($d['email']);
    $email_link     = esc_url('mailto:' . $d['email']);
    $phone_link     = esc_url('tel:' . $d['phone']);
    $phone          = esc_html($d['phone_display']);
    $company        = esc_html($d['company']);
    $updated        = esc_html(date_i18n('j F Y'));
    $dpa_url        = 'https://www.dataprotection.ie';

    return matrix_starter_legal_join_content(
        [
            matrix_starter_legal_paragraph(
                sprintf(
                    /* translators: 1: company name, 2: website URL */
                    __('This Privacy Policy explains how %1$s (“we”, “us”, “our”) collects, uses, stores, and protects personal data when you visit <a href="%2$s">%2$s</a> or otherwise interact with us. We process personal data in accordance with the General Data Protection Regulation (EU) 2016/679 (“GDPR”) and the Data Protection Act 2018 (Ireland).', 'matrix-starter'),
                    $company,
                    $site_url
                )
            ),
            matrix_starter_legal_paragraph(
                sprintf(
                    __('This policy was last updated on %s.', 'matrix-starter'),
                    '<strong>' . $updated . '</strong>'
                )
            ),
            matrix_starter_legal_heading(2, __('Who we are', 'matrix-starter')),
            matrix_starter_legal_paragraph(
                sprintf(
                    __('%1$s is the data controller responsible for your personal data. We are based in Ireland and provide construction and related services.', 'matrix-starter'),
                    $company
                )
            ),
            matrix_starter_legal_heading(2, __('Contact us', 'matrix-starter')),
            matrix_starter_legal_paragraph(
                sprintf(
                    __('If you have questions about this policy or wish to exercise your data protection rights, contact us at <a href="%1$s">%2$s</a> or <a href="%3$s">%4$s</a>.', 'matrix-starter'),
                    $email_link,
                    $email,
                    $phone_link,
                    $phone
                )
            ),
            matrix_starter_legal_heading(2, __('Personal data we collect', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('Depending on how you use our website and services, we may collect:', 'matrix-starter')),
            matrix_starter_legal_list(
                [
                    esc_html__('Identity and contact details (for example name, email address, phone number, company name, and address) when you submit an enquiry, request a callback, apply for a role, or contact us.', 'matrix-starter'),
                    esc_html__('Recruitment information (for example CV, cover letter, work history, and references) when you apply for a job.', 'matrix-starter'),
                    esc_html__('Technical and usage data (for example IP address, browser type, device information, pages viewed, and referral source) collected through cookies and similar technologies.', 'matrix-starter'),
                    esc_html__('Communications you send to us (for example emails or messages) and our responses.', 'matrix-starter'),
                ]
            ),
            matrix_starter_legal_heading(2, __('How we collect personal data', 'matrix-starter')),
            matrix_starter_legal_list(
                [
                    esc_html__('Directly from you when you complete a form, subscribe to updates, apply for a position, or contact us.', 'matrix-starter'),
                    esc_html__('Automatically when you browse our website (see our Cookie Policy).', 'matrix-starter'),
                    esc_html__('From third parties where permitted by law (for example professional references, recruitment platforms, or service providers that support our website).', 'matrix-starter'),
                ]
            ),
            matrix_starter_legal_heading(2, __('Why we use your data and lawful bases', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('We only use personal data where we have a lawful basis under GDPR. The table below summarises typical purposes:', 'matrix-starter')),
            matrix_starter_legal_list(
                [
                    esc_html__('To respond to enquiries and provide information you request — lawful bases: consent and/or legitimate interests (running and growing our business).', 'matrix-starter'),
                    esc_html__('To process job applications and manage recruitment — lawful bases: steps prior to a contract, contract, and legitimate interests.', 'matrix-starter'),
                    esc_html__('To operate, secure, and improve our website — lawful bases: legitimate interests and, where required, consent (for non-essential cookies).', 'matrix-starter'),
                    esc_html__('To comply with legal obligations (for example tax, employment, or regulatory requirements) — lawful basis: legal obligation.', 'matrix-starter'),
                    esc_html__('To establish, exercise, or defend legal claims — lawful basis: legitimate interests and/or legal obligation.', 'matrix-starter'),
                ]
            ),
            matrix_starter_legal_heading(2, __('Cookies', 'matrix-starter')),
            matrix_starter_legal_paragraph(
                sprintf(
                    __('We use cookies and similar technologies on this website. For details on the cookies we use, how long they last, and how to manage your preferences, please read our <a href="%s">Cookie Policy</a>.', 'matrix-starter'),
                    $cookie_url
                )
            ),
            matrix_starter_legal_heading(2, __('Who we share personal data with', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('We do not sell your personal data. We may share it with trusted third parties who process data on our instructions, including:', 'matrix-starter')),
            matrix_starter_legal_list(
                [
                    esc_html__('Website hosting, IT support, and security providers.', 'matrix-starter'),
                    esc_html__('Email, CRM, and form delivery services used to handle enquiries and applications.', 'matrix-starter'),
                    esc_html__('Analytics and performance tools (only where you have consented to non-essential cookies, where applicable).', 'matrix-starter'),
                    esc_html__('Professional advisers (for example legal or accounting advisers) where necessary.', 'matrix-starter'),
                    esc_html__('Regulators, courts, or law enforcement when required by law.', 'matrix-starter'),
                ]
            ),
            matrix_starter_legal_paragraph(__('We require processors to protect your data under written terms consistent with GDPR.', 'matrix-starter')),
            matrix_starter_legal_heading(2, __('International transfers', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('Some service providers may process data outside the European Economic Area (EEA). Where this occurs, we ensure appropriate safeguards are in place, such as Standard Contractual Clauses approved by the European Commission or an adequacy decision, unless a specific exception applies.', 'matrix-starter')),
            matrix_starter_legal_heading(2, __('How long we keep personal data', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('We retain personal data only for as long as necessary for the purposes described in this policy, including to satisfy legal, accounting, or reporting requirements. Typical retention periods include:', 'matrix-starter')),
            matrix_starter_legal_list(
                [
                    esc_html__('Enquiry and marketing records: up to 24 months after our last meaningful contact, unless you ask us to delete sooner or a longer period is required by law.', 'matrix-starter'),
                    esc_html__('Job applications: up to 24 months after the recruitment process ends, unless you consent to a longer retention for future opportunities.', 'matrix-starter'),
                    esc_html__('Website logs and analytics: as set out in our Cookie Policy or provider documentation.', 'matrix-starter'),
                ]
            ),
            matrix_starter_legal_heading(2, __('Your rights', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('Under GDPR, you may have the following rights (subject to conditions and exemptions):', 'matrix-starter')),
            matrix_starter_legal_list(
                [
                    esc_html__('Access — request a copy of the personal data we hold about you.', 'matrix-starter'),
                    esc_html__('Rectification — ask us to correct inaccurate or incomplete data.', 'matrix-starter'),
                    esc_html__('Erasure — ask us to delete your data in certain circumstances.', 'matrix-starter'),
                    esc_html__('Restriction — ask us to limit processing in certain circumstances.', 'matrix-starter'),
                    esc_html__('Data portability — receive certain data in a structured, commonly used format.', 'matrix-starter'),
                    esc_html__('Objection — object to processing based on legitimate interests, and to direct marketing at any time.', 'matrix-starter'),
                    esc_html__('Withdraw consent — where processing is based on consent, you may withdraw it at any time without affecting prior processing.', 'matrix-starter'),
                ]
            ),
            matrix_starter_legal_paragraph(
                sprintf(
                    __('To exercise these rights, contact us at <a href="%1$s">%2$s</a>. We may need to verify your identity before responding.', 'matrix-starter'),
                    $email_link,
                    $email
                )
            ),
            matrix_starter_legal_heading(2, __('Complaints', 'matrix-starter')),
            matrix_starter_legal_paragraph(
                sprintf(
                    __('You have the right to lodge a complaint with the Data Protection Commission (Ireland) if you believe we have not handled your personal data properly. Visit <a href="%1$s" rel="noopener noreferrer">%1$s</a> for contact details.', 'matrix-starter'),
                    esc_url($dpa_url)
                )
            ),
            matrix_starter_legal_heading(2, __('Security', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('We implement appropriate technical and organisational measures to protect personal data against unauthorised access, alteration, disclosure, or destruction. No method of transmission over the internet is completely secure; we cannot guarantee absolute security.', 'matrix-starter')),
            matrix_starter_legal_heading(2, __('Children', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('Our website is not directed at children under 16, and we do not knowingly collect personal data from children. If you believe a child has provided us with personal data, please contact us so we can delete it.', 'matrix-starter')),
            matrix_starter_legal_heading(2, __('Changes to this policy', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('We may update this Privacy Policy from time to time. The “last updated” date at the top will change when we do. We encourage you to review this page periodically.', 'matrix-starter')),
            matrix_starter_legal_heading(2, __('Related information', 'matrix-starter')),
            matrix_starter_legal_paragraph(
                sprintf(
                    __('Read our <a href="%1$s">Cookie Policy</a> and <a href="%2$s">Accessibility Statement</a>.', 'matrix-starter'),
                    $cookie_url,
                    $access_url
                )
            ),
        ]
    );
}

function matrix_starter_legal_cookie_policy_content(): string
{
    $d          = matrix_starter_legal_contact_details();
    $site_url   = esc_url(home_url('/'));
    $privacy    = esc_url(home_url('/privacy-notice/'));
    $email      = esc_html($d['email']);
    $email_link = esc_url('mailto:' . $d['email']);
    $phone_link = esc_url('tel:' . $d['phone']);
    $phone      = esc_html($d['phone_display']);
    $company    = esc_html($d['company']);
    $updated    = esc_html(date_i18n('j F Y'));

    return matrix_starter_legal_join_content(
        [
            matrix_starter_legal_paragraph(
                sprintf(
                    __('This Cookie Policy explains how %1$s (“we”, “us”) uses cookies and similar technologies on <a href="%2$s">%2$s</a>. It should be read together with our <a href="%3$s">Privacy Policy</a>.', 'matrix-starter'),
                    $company,
                    $site_url,
                    $privacy
                )
            ),
            matrix_starter_legal_paragraph(
                sprintf(
                    __('This policy was last updated on %s. We are established in Ireland and comply with the ePrivacy rules (as implemented in Ireland) and the GDPR.', 'matrix-starter'),
                    '<strong>' . $updated . '</strong>'
                )
            ),
            matrix_starter_legal_heading(2, __('What are cookies?', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('Cookies are small text files placed on your device when you visit a website. They help the site work, remember preferences, and understand how visitors use the site. Similar technologies (such as pixels, local storage, or SDKs) are also covered by this policy.', 'matrix-starter')),
            matrix_starter_legal_heading(2, __('How we use cookies', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('We group cookies into the categories below. Strictly necessary cookies do not require consent under Irish/EU law. For other categories, we ask for your consent before setting non-essential cookies where required.', 'matrix-starter')),
            matrix_starter_legal_heading(3, __('Strictly necessary', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('These cookies are essential for the website to function. They enable core features such as security, network management, form submission, and accessibility preferences. You cannot opt out of these through our site, but you may block them in your browser (which may affect how the site works).', 'matrix-starter')),
            matrix_starter_legal_list(
                [
                    esc_html__('WordPress session and authentication cookies (for example to keep you logged in if you have an account, or to maintain form security).', 'matrix-starter'),
                    esc_html__('Cookie consent preference storage (to remember choices you make in our cookie banner).', 'matrix-starter'),
                ]
            ),
            matrix_starter_legal_heading(3, __('Functional', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('These cookies allow enhanced functionality and personalisation, such as remembering choices you make. They may be set by us or by third-party providers whose services we use.', 'matrix-starter')),
            matrix_starter_legal_heading(3, __('Analytics and performance', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('These cookies help us understand how visitors use our website (for example which pages are visited and whether errors occur) so we can improve performance and content. We only use these cookies with your consent where required by law.', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('If we use Google Analytics or similar tools, typical cookies may include identifiers used to distinguish users and sessions. Retention periods are set in the relevant tool configuration.', 'matrix-starter')),
            matrix_starter_legal_heading(3, __('Marketing', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('These cookies may be used to deliver relevant advertising or measure campaign effectiveness on third-party platforms. We only use marketing cookies with your consent where required.', 'matrix-starter')),
            matrix_starter_legal_heading(2, __('Third-party cookies', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('Some content on our site may be provided by third parties (for example embedded videos, maps, or social media feeds). Those providers may set their own cookies when you interact with their content. We do not control third-party cookies; please review the relevant provider’s privacy and cookie notices.', 'matrix-starter')),
            matrix_starter_legal_heading(2, __('How long cookies last', 'matrix-starter')),
            matrix_starter_legal_list(
                [
                    esc_html__('Session cookies — deleted when you close your browser.', 'matrix-starter'),
                    esc_html__('Persistent cookies — remain for a set period (from days up to 24 months) unless you delete them earlier.', 'matrix-starter'),
                ]
            ),
            matrix_starter_legal_heading(2, __('Managing your preferences', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('When you first visit our website, you can accept or reject non-essential cookies through our cookie banner or preference centre (where available). You can also control cookies through your browser settings:', 'matrix-starter')),
            matrix_starter_legal_list(
                [
                    sprintf(
                        '<a href="https://support.google.com/chrome/answer/95647" rel="noopener noreferrer">%s</a>',
                        esc_html__('Google Chrome', 'matrix-starter')
                    ),
                    sprintf(
                        '<a href="https://support.apple.com/en-ie/guide/safari/sfri11471/mac" rel="noopener noreferrer">%s</a>',
                        esc_html__('Safari', 'matrix-starter')
                    ),
                    sprintf(
                        '<a href="https://support.mozilla.org/en-US/kb/enhanced-tracking-protection-firefox-desktop" rel="noopener noreferrer">%s</a>',
                        esc_html__('Mozilla Firefox', 'matrix-starter')
                    ),
                    sprintf(
                        '<a href="https://support.microsoft.com/en-us/microsoft-edge/delete-cookies-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" rel="noopener noreferrer">%s</a>',
                        esc_html__('Microsoft Edge', 'matrix-starter')
                    ),
                ]
            ),
            matrix_starter_legal_paragraph(__('Blocking all cookies may prevent some parts of the website from working correctly.', 'matrix-starter')),
            matrix_starter_legal_heading(2, __('Changes to this policy', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('We may update this Cookie Policy when we change how we use cookies or when laws change. Please check this page periodically.', 'matrix-starter')),
            matrix_starter_legal_heading(2, __('Contact us', 'matrix-starter')),
            matrix_starter_legal_paragraph(
                sprintf(
                    __('Questions about cookies or this policy: <a href="%1$s">%2$s</a> or <a href="%3$s">%4$s</a>.', 'matrix-starter'),
                    $email_link,
                    $email,
                    $phone_link,
                    $phone
                )
            ),
        ]
    );
}

function matrix_starter_legal_accessibility_content(): string
{
    $d          = matrix_starter_legal_contact_details();
    $site_url   = esc_url(home_url('/'));
    $privacy    = esc_url(home_url('/privacy-notice/'));
    $email      = esc_html($d['email']);
    $email_link = esc_url('mailto:' . $d['email']);
    $phone_link = esc_url('tel:' . $d['phone']);
    $phone      = esc_html($d['phone_display']);
    $company    = esc_html($d['company']);
    $updated    = esc_html(date_i18n('j F Y'));

    return matrix_starter_legal_join_content(
        [
            matrix_starter_legal_paragraph(
                sprintf(
                    __('%1$s is committed to making <a href="%2$s">%2$s</a> accessible to as many people as possible, including people with disabilities. We aim to conform to level AA of the Web Content Accessibility Guidelines (WCAG) 2.1.', 'matrix-starter'),
                    $company,
                    $site_url
                )
            ),
            matrix_starter_legal_paragraph(
                sprintf(
                    __('This accessibility statement was prepared on %s and applies to this website.', 'matrix-starter'),
                    '<strong>' . $updated . '</strong>'
                )
            ),
            matrix_starter_legal_heading(2, __('Measures we take', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('We work to improve accessibility through design, development, and content practices, including:', 'matrix-starter')),
            matrix_starter_legal_list(
                [
                    esc_html__('Using semantic HTML and a logical heading structure on pages.', 'matrix-starter'),
                    esc_html__('Providing a “Skip to main content” link at the start of each page.', 'matrix-starter'),
                    esc_html__('Ensuring visible keyboard focus styles on interactive elements.', 'matrix-starter'),
                    esc_html__('Associating form fields with visible labels and accessible error messages.', 'matrix-starter'),
                    esc_html__('Supplying text alternatives for meaningful images and marking decorative images appropriately.', 'matrix-starter'),
                    esc_html__('Designing navigation and menus to work with keyboard and assistive technologies where possible.', 'matrix-starter'),
                    esc_html__('Testing key templates with automated accessibility checks as part of our quality process.', 'matrix-starter'),
                ]
            ),
            matrix_starter_legal_heading(2, __('Conformance status', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('We consider this website to be partially conformant with WCAG 2.1 level AA. “Partially conformant” means that some parts of the content may not yet fully meet the accessibility standard. We are actively working to address known issues.', 'matrix-starter')),
            matrix_starter_legal_heading(2, __('Known limitations', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('Despite our efforts, some areas may not yet be fully accessible, for example:', 'matrix-starter')),
            matrix_starter_legal_list(
                [
                    esc_html__('Third-party embedded content (such as videos, maps, or social media widgets) controlled by external providers.', 'matrix-starter'),
                    esc_html__('PDF or document downloads that may not have been remediated for accessibility.', 'matrix-starter'),
                    esc_html__('Older content published before our current accessibility standards.', 'matrix-starter'),
                ]
            ),
            matrix_starter_legal_paragraph(__('We welcome your help in identifying issues so we can fix them.', 'matrix-starter')),
            matrix_starter_legal_heading(2, __('Feedback and contact', 'matrix-starter')),
            matrix_starter_legal_paragraph(
                sprintf(
                    __('If you have difficulty using any part of this website, or need information in an alternative format, please contact us at <a href="%1$s">%2$s</a> or <a href="%3$s">%4$s</a>. Tell us which page you were on and describe the problem so we can assist you and improve the site.', 'matrix-starter'),
                    $email_link,
                    $email,
                    $phone_link,
                    $phone
                )
            ),
            matrix_starter_legal_paragraph(__('We aim to respond to accessibility feedback within 5 working days.', 'matrix-starter')),
            matrix_starter_legal_heading(2, __('Enforcement procedure', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('If you are not satisfied with our response, you may contact the Irish Human Rights and Equality Commission for guidance on your rights under equality legislation in Ireland.', 'matrix-starter')),
            matrix_starter_legal_heading(2, __('Technical specifications', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('Accessibility depends on the following technologies working with your browser and assistive technologies: HTML, CSS, JavaScript, and WAI-ARIA where appropriate.', 'matrix-starter')),
            matrix_starter_legal_heading(2, __('Assessment approach', 'matrix-starter')),
            matrix_starter_legal_paragraph(__('We assess accessibility through a combination of internal review, automated testing tools, and manual checks of key user journeys (such as navigation, forms, and contact flows).', 'matrix-starter')),
            matrix_starter_legal_heading(2, __('Related policies', 'matrix-starter')),
            matrix_starter_legal_paragraph(
                sprintf(
                    __('See also our <a href="%s">Privacy Policy</a>.', 'matrix-starter'),
                    $privacy
                )
            ),
        ]
    );
}
