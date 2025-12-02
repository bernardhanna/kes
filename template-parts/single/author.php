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
$permalink    = urlencode(get_permalink($post_id));
$title        = urlencode(get_the_title($post_id));

$facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $permalink;
$twitter_url  = 'https://twitter.com/intent/tweet?url=' . $permalink . '&text=' . $title;
$linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $permalink . '&title=' . $title;
$whatsapp_url = 'https://api.whatsapp.com/send?text=' . $title . '%20' . $permalink;
$email_url    = 'mailto:?subject=' . $title . '&body=' . $title . '%20' . $permalink;

// Prev / next posts
$prev_post = get_previous_post();
$next_post = get_next_post();
?>

<!-- Author + Share -->
<section
    class="flex flex-wrap gap-10 justify-between items-center px-6 py-4 w-full border-t-2 border-solid border-t-emerald-100 max-md:px-5 max-md:max-w-full"
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
        <p class="my-auto text-base font-medium leading-none text-violet-950" id="share-heading">
            Share on:
        </p>

        <div class="flex gap-4 items-center my-auto min-w-60" role="group" aria-label="Social media sharing options">
            <!-- Facebook -->
            <a
                href="<?php echo esc_url($facebook_url); ?>"
                class="w-fit whitespace-nowrap hover:opacity-80 transition-opacity focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 rounded-full"
                aria-label="Share on Facebook"
                target="_blank"
                rel="noopener noreferrer"
            >
                <img
                    src="https://api.builder.io/api/v1/image/assets/f35586c581c84ecf82b6de32c55ed39e/930bf432fbe3455d361448e72c58e97d69531d37?placeholderIfAbsent=true"
                    alt="Facebook"
                    class="object-contain shrink-0 my-auto w-9 aspect-square"
                />
            </a>

            <!-- X / Twitter -->
            <a
                href="<?php echo esc_url($twitter_url); ?>"
                class="w-fit whitespace-nowrap hover:opacity-80 transition-opacity focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 rounded-full"
                aria-label="Share on Twitter"
                target="_blank"
                rel="noopener noreferrer"
            >
                <img
                    src="https://api.builder.io/api/v1/image/assets/f35586c581c84ecf82b6de32c55ed39e/95697c55f88d4cf1ed1330bfad71aa3cf9b21532?placeholderIfAbsent=true"
                    alt="Twitter"
                    class="object-contain shrink-0 my-auto w-9 aspect-square"
                />
            </a>

            <!-- LinkedIn -->
            <a
                href="<?php echo esc_url($linkedin_url); ?>"
                class="w-fit whitespace-nowrap hover:opacity-80 transition-opacity focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 rounded-full"
                aria-label="Share on LinkedIn"
                target="_blank"
                rel="noopener noreferrer"
            >
                <img
                    src="https://api.builder.io/api/v1/image/assets/f35586c581c84ecf82b6de32c55ed39e/23eba74aec03ddb987503b7bf8bd0bc3c91ed1ba?placeholderIfAbsent=true"
                    alt="LinkedIn"
                    class="object-contain shrink-0 my-auto w-9 aspect-square"
                />
            </a>

            <!-- WhatsApp (instead of Instagram, more useful for sharing) -->
            <a
                href="<?php echo esc_url($whatsapp_url); ?>"
                class="w-fit whitespace-nowrap hover:opacity-80 transition-opacity focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 rounded-full"
                aria-label="Share on WhatsApp"
                target="_blank"
                rel="noopener noreferrer"
            >
                <img
                    src="https://api.builder.io/api/v1/image/assets/f35586c581c84ecf82b6de32c55ed39e/a8a25d2c15005ce57f8fa696dc6ef89dd555d2e0?placeholderIfAbsent=true"
                    alt="WhatsApp"
                    class="object-contain shrink-0 my-auto w-9 aspect-square"
                />
            </a>

            <!-- Email -->
            <a
                href="<?php echo esc_url($email_url); ?>"
                class="w-fit whitespace-nowrap hover:opacity-80 transition-opacity focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 rounded-full"
                aria-label="Share via email"
            >
                <img
                    src="https://api.builder.io/api/v1/image/assets/f35586c581c84ecf82b6de32c55ed39e/c06a9777d257e294dc549e31105bf68fd189ec88?placeholderIfAbsent=true"
                    alt="Email"
                    class="object-contain shrink-0 my-auto w-9 aspect-square"
                />
            </a>
        </div>
    </div>
</section>

<?php
$prev_post = get_previous_post();
$next_post = get_next_post();
?>

<nav
    class="flex flex-col justify-center py-4 w-full text-base font-bold leading-none text-blue-900 bg-white max-md:max-w-full"
    aria-label="Article navigation"
>
    <div class="flex flex-wrap gap-10 justify-between items-center w-full max-md:max-w-full">
        <!-- Previous Article -->
        <?php if ($prev_post) : ?>
            <a
                href="<?php echo esc_url(get_permalink($prev_post)); ?>"
                class="flex gap-1 items-center py-1 pr-1 pl-2.5 my-auto w-fit whitespace-nowrap hover:bg-blue-50 transition-colors rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                aria-label="<?php echo esc_attr('Go to previous article: ' . get_the_title($prev_post)); ?>"
            >
                <img
                    src="https://api.builder.io/api/v1/image/assets/f35586c581c84ecf82b6de32c55ed39e/245ae87e4fda5bf621f15ad7d582fa9f5d7e8520?placeholderIfAbsent=true"
                    alt=""
                    class="object-contain shrink-0 my-auto w-6 aspect-square"
                    aria-hidden="true"
                />
                <span class="my-auto text-blue-900">Previous article</span>
            </a>
        <?php else : ?>
            <!-- Disabled state when no previous post -->
            <span
                class="flex gap-1 items-center py-1 pr-1 pl-2.5 my-auto w-fit whitespace-nowrap rounded opacity-40 cursor-default"
                aria-disabled="true"
            >
                <img
                    src="https://api.builder.io/api/v1/image/assets/f35586c581c84ecf82b6de32c55ed39e/245ae87e4fda5bf621f15ad7d582fa9f5d7e8520?placeholderIfAbsent=true"
                    alt=""
                    class="object-contain shrink-0 my-auto w-6 aspect-square"
                    aria-hidden="true"
                />
                <span class="my-auto text-blue-900">Previous article</span>
            </span>
        <?php endif; ?>

        <!-- Next Article -->
        <?php if ($next_post) : ?>
            <a
                href="<?php echo esc_url(get_permalink($next_post)); ?>"
                class="flex gap-1 items-center py-1 pr-1 pl-2.5 my-auto w-fit whitespace-nowrap hover:bg-blue-50 transition-colors rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                aria-label="<?php echo esc_attr('Go to next article: ' . get_the_title($next_post)); ?>"
            >
                <span class="my-auto text-blue-900">Next article</span>
                <img
                    src="https://api.builder.io/api/v1/image/assets/f35586c581c84ecf82b6de32c55ed39e/28dd02238dd46f2bd8a2091d38967b34b1f45438?placeholderIfAbsent=true"
                    alt=""
                    class="object-contain shrink-0 my-auto w-6 aspect-square"
                    aria-hidden="true"
                />
            </a>
        <?php else : ?>
            <!-- Disabled state when no next post -->
            <span
                class="flex gap-1 items-center py-1 pr-1 pl-2.5 my-auto w-fit whitespace-nowrap rounded opacity-40 cursor-default"
                aria-disabled="true"
            >
                <span class="my-auto text-blue-900">Next article</span>
                <img
                    src="https://api.builder.io/api/v1/image/assets/f35586c581c84ecf82b6de32c55ed39e/28dd02238dd46f2bd8a2091d38967b34b1f45438?placeholderIfAbsent=true"
                    alt=""
                    class="object-contain shrink-0 my-auto w-6 aspect-square"
                    aria-hidden="true"
                />
            </span>
        <?php endif; ?>
    </div>
</nav>
