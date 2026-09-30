<?php
/**
 * Single post author + share + prev/next nav
 */

if (! defined('ABSPATH')) {
    exit;
}

$post_id    = get_the_ID();
$author_id  = get_post_field('post_author', $post_id);
$author_name = get_the_author_meta('display_name', $author_id);

// Avatar (48px like your design)
$author_avatar = get_avatar(
    $author_id,
    48,
    '',
    $author_name,
    array(
        'class' => 'object-contain shrink-0 my-auto w-12 aspect-square rounded-full',
    )
);

// Sharing URLs
$permalink_raw = get_permalink($post_id);
$permalink     = urlencode($permalink_raw);
$title         = urlencode(get_the_title($post_id));

$share_links = [
    [
        'url'      => 'https://www.facebook.com/sharer/sharer.php?u=' . $permalink,
        'label'    => __('Share on Facebook', 'matrix'),
        'icon'     => matrix_starter_get_social_icon_url('facebook'),
        'external' => true,
    ],
    [
        'url'      => 'https://x.com/intent/tweet?url=' . $permalink . '&text=' . $title,
        'label'    => __('Share on X', 'matrix'),
        'icon'     => matrix_starter_get_social_icon_url('x'),
        'external' => true,
    ],
    [
        'url'      => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $permalink . '&title=' . $title,
        'label'    => __('Share on LinkedIn', 'matrix'),
        'icon'     => matrix_starter_get_social_icon_url('linkedin'),
        'external' => true,
    ],
    [
        'url'      => 'https://api.whatsapp.com/send?text=' . $title . '%20' . $permalink,
        'label'    => __('Share on WhatsApp', 'matrix'),
        'icon'     => matrix_starter_get_social_icon_url('whatsapp'),
        'external' => true,
    ],
    [
        'url'      => 'mailto:?subject=' . $title . '&body=' . $title . '%20' . $permalink,
        'label'    => __('Share via email', 'matrix'),
        'icon'     => matrix_starter_get_social_icon_url('email'),
        'external' => false,
    ],
    [
        'action'   => 'copy',
        'url'      => $permalink_raw,
        'label'    => __('Copy link', 'matrix'),
        'copied'   => __('Link copied', 'matrix'),
        'icon'     => matrix_starter_get_social_icon_url('link'),
    ],
];

// Prev / next posts
$prev_post = get_previous_post();
$next_post = get_next_post();
?>

<div class="mx-auto w-full max-w-container max-xl:px-5">
<!-- Author + Share -->
<section
    class="flex flex-wrap gap-10 justify-between items-center py-4 w-full border-t-2 border-solid border-t-emerald-100 max-md:max-w-full"
    role="region"
    aria-labelledby="author-heading"
>
    <!-- Author Information -->
    <div class="flex gap-4 items-center self-stretch my-auto text-slate-800">
        <?php if ($author_avatar) : ?>
            <?php echo $author_avatar; ?>
        <?php endif; ?>

        <div class="flex flex-col justify-center my-auto">
            <p class="text-base leading-none text-slate-800" id="author-heading">
                Published by
            </p>
            <h3 class="text-lg font-medium leading-none text-slate-800">
                <?php echo esc_html($author_name); ?>
            </h3>
        </div>
    </div>

    <!-- Social Sharing Section -->
    <div
        class="flex gap-4 items-center self-stretch my-auto min-w-60"
        role="region"
        aria-labelledby="share-heading"
    >
        <p class="my-auto text-base font-medium leading-none text-primary" id="share-heading">
            Share on:
        </p>

        <?php matrix_starter_a11y_live_region('share-copy-status'); ?>
        <div class="flex gap-4 items-center my-auto min-w-60" role="group" aria-label="<?php esc_attr_e('Social media sharing options', 'matrix'); ?>">
            <?php foreach ($share_links as $share) :
                if (empty($share['icon']) || (empty($share['url']) && empty($share['action']))) {
                    continue;
                }

                $icon_style = "--footer-social-icon: url('" . esc_url($share['icon']) . "');";
                $is_copy    = ! empty($share['action']) && $share['action'] === 'copy';

                if ($is_copy) :
                    ?>
                <button
                    type="button"
                    class="btn footer-social-link p-0 bg-transparent border-0 cursor-pointer"
                    style="<?php echo esc_attr($icon_style); ?>"
                    data-copy-url="<?php echo esc_url($share['url']); ?>"
                    data-default-label="<?php echo esc_attr($share['label']); ?>"
                    data-copied-label="<?php echo esc_attr($share['copied'] ?? __('Link copied', 'matrix')); ?>"
                    aria-label="<?php echo esc_attr($share['label']); ?>"
                >
                    <span class="sr-only"><?php echo esc_html($share['label']); ?></span>
                </button>
                    <?php
                    continue;
                endif;
                ?>
                <a
                    href="<?php echo esc_url($share['url']); ?>"
                    class="footer-social-link"
                    style="<?php echo esc_attr($icon_style); ?>"
                    aria-label="<?php echo esc_attr($share['label']); ?>"
                    <?php if (! empty($share['external'])) : ?>
                        target="_blank"
                        rel="noopener noreferrer"
                    <?php endif; ?>
                >
                    <span class="sr-only"><?php echo esc_html($share['label']); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
(function () {
    document.querySelectorAll('[data-copy-url]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var url = btn.getAttribute('data-copy-url');
            if (!url) return;

            var copiedLabel = btn.getAttribute('data-copied-label') || 'Link copied';
            var defaultLabel = btn.getAttribute('data-default-label') || 'Copy link';

            var liveRegion = document.getElementById('share-copy-status');

            function onCopied() {
                btn.setAttribute('aria-label', copiedLabel);
                if (liveRegion) {
                    liveRegion.textContent = copiedLabel;
                }
                window.setTimeout(function () {
                    btn.setAttribute('aria-label', defaultLabel);
                    if (liveRegion) {
                        liveRegion.textContent = '';
                    }
                }, 2000);
            }

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(onCopied).catch(function () {
                    window.prompt('Copy link:', url);
                });
                return;
            }

            var input = document.createElement('input');
            input.value = url;
            document.body.appendChild(input);
            input.select();
            try {
                document.execCommand('copy');
                onCopied();
            } catch (e) {
                window.prompt('Copy link:', url);
            }
            document.body.removeChild(input);
        });
    });
})();
</script>

<?php
$prev_post = get_previous_post();
$next_post = get_next_post();
?>

<nav
    class="flex flex-col justify-center py-4 w-full bg-white max-md:max-w-full"
    aria-label="Article navigation"
>
    <div class="flex flex-wrap gap-10 justify-between items-center w-full max-md:max-w-full">
        <!-- Previous Article -->
        <?php if ($prev_post) : ?>
            <a
                href="<?php echo esc_url(get_permalink($prev_post)); ?>"
                class="flex gap-1 items-center py-1 pr-1 pl-2.5 my-auto w-fit whitespace-nowrap font-red-hat-text text-[16px] font-bold leading-[22px] text-[color:var(--Blue-300,#2B3990)] hover:text-[color:var(--Blue-100,#00ACD8)] transition-colors rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                aria-label="<?php echo esc_attr('Go to previous article: ' . get_the_title($prev_post)); ?>"
            >
                <svg class="shrink-0 w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                    <path d="M20 24L12 16L20 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <span class="my-auto">Previous article</span>
            </a>
        <?php else : ?>
            <!-- Disabled state when no previous post -->
            <span
                class="flex gap-1 items-center py-1 pr-1 pl-2.5 my-auto w-fit whitespace-nowrap rounded opacity-40 cursor-default font-red-hat-text text-[16px] font-bold leading-[22px] text-[color:var(--Blue-300,#2B3990)]"
                aria-disabled="true"
            >
                <svg class="shrink-0 w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                    <path d="M20 24L12 16L20 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <span class="my-auto">Previous article</span>
            </span>
        <?php endif; ?>

        <!-- Next Article -->
        <?php if ($next_post) : ?>
            <a
                href="<?php echo esc_url(get_permalink($next_post)); ?>"
                class="flex gap-1 items-center py-1 pr-1 pl-2.5 my-auto w-fit whitespace-nowrap font-red-hat-text text-[16px] font-bold leading-[22px] text-[color:var(--Blue-300,#2B3990)] hover:text-[color:var(--Blue-100,#00ACD8)] transition-colors rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                aria-label="<?php echo esc_attr('Go to next article: ' . get_the_title($next_post)); ?>"
            >
                <span class="my-auto">Next article</span>
                <svg class="shrink-0 w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                    <path d="M12 24L20 16L12 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </a>
        <?php else : ?>
            <!-- Disabled state when no next post -->
            <span
                class="flex gap-1 items-center py-1 pr-1 pl-2.5 my-auto w-fit whitespace-nowrap rounded opacity-40 cursor-default font-red-hat-text text-[16px] font-bold leading-[22px] text-[color:var(--Blue-300,#2B3990)]"
                aria-disabled="true"
            >
                <span class="my-auto">Next article</span>
                <svg class="shrink-0 w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                    <path d="M12 24L20 16L12 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </span>
        <?php endif; ?>
    </div>
</nav>
</div>
