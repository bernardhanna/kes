<?php
/**
 * Blog index “Sort by” dropdown (Figma: sort pill + menu).
 *
 * @package matrix-starter
 *
 * @var string $current_sort Current sort key.
 */

if (! defined('ABSPATH')) {
    exit;
}

$current_sort = isset($current_sort) ? (string) $current_sort : matrix_starter_get_blog_sort();
$options      = matrix_starter_blog_sort_options();
$menu_id      = 'blog-sort-menu-' . wp_rand(1000, 9999);
?>
<div
    class="relative z-30 w-full sm:w-auto"
    x-data="{ sortOpen: false }"
    @keydown.escape.window="sortOpen = false"
>
    <button
        type="button"
        id="<?php echo esc_attr($menu_id); ?>-trigger"
        class="inline-flex gap-2 items-center justify-center h-[35px] px-4 py-3 w-full sm:w-auto whitespace-nowrap rounded-[100px] border border-solid border-[#262262] bg-white font-red-hat-text text-[14px] font-medium leading-5 text-[#262262] transition-[color,background-color,border-color] duration-200 hover:border-[#00ACD8] hover:bg-[#00ACD8] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#00ACD8] focus-visible:ring-offset-2 focus-visible:ring-offset-white"
        :aria-expanded="sortOpen ? 'true' : 'false'"
        aria-haspopup="listbox"
        aria-controls="<?php echo esc_attr($menu_id); ?>"
        @click="sortOpen = !sortOpen"
    >
        <?php esc_html_e('Sort by', 'matrix-starter'); ?>
        <svg class="shrink-0 transition-transform duration-200" :class="sortOpen ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none" aria-hidden="true">
            <path d="M4.25 6.375L8.5 10.625L12.75 6.375" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>

    <div
        x-show="sortOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-1"
        @click.outside="sortOpen = false"
        id="<?php echo esc_attr($menu_id); ?>"
        role="listbox"
        aria-labelledby="<?php echo esc_attr($menu_id); ?>-trigger"
        class="absolute right-0 top-full z-50 mt-2 min-w-[180px] rounded-lg border border-solid border-[#EAECF0] bg-white py-1 shadow-lg"
    >
        <?php foreach ($options as $key => $label) :
            $is_active = $key === $current_sort;
            ?>
            <a
                href="<?php echo esc_url(matrix_starter_blog_sort_url($key)); ?>"
                role="option"
                aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                class="block px-4 py-2.5 font-red-hat-text text-[14px] leading-5 transition-colors <?php echo $is_active ? 'bg-[#F9FAFB] font-medium text-[#262262]' : 'font-normal text-[#344054] hover:bg-[#F9FAFB]'; ?>"
                @click="sortOpen = false"
            >
                <?php echo esc_html($label); ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>
