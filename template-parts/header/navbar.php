<?php
$logo_id = get_field('logo', 'option') ?: get_theme_mod('custom_logo');
$logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : '';
$logo_alt = $logo_id ? get_post_meta($logo_id, '_wp_attachment_image_alt', true) : get_bloginfo('name');
$logo_position = get_field('logo_position', 'option');
$logo_position_class = ($logo_position === 'center') ? 'justify-center' : 'justify-start';

use Log1x\Navi\Navi;

$primary_navigation = Navi::make()->build('primary');
$secondary_navigation = Navi::make()->build('secondary');
?>
<section
  id="site-nav"
  x-data="{
    isOpen: false,
    activeDropdown: null,
    toggleDropdown(index) {
      this.activeDropdown = (this.activeDropdown === index ? null : index);
    },
    checkWindowSize() {
      if (window.innerWidth > 1084) {
        this.isOpen = false;
        this.activeDropdown = null;
      }
    }
  }"
  x-init="window.addEventListener('resize', () => checkWindowSize())"
  class="py-4 bg-[#F9FAFB]"
  x-effect="isOpen ? document.body.style.overflow = 'hidden' : document.body.style.overflow = ''">
  <nav class="flex justify-between items-center w-full mx-auto max-w-[1280px] max-sm:pl-5 max-sm:pr-0 px-5 navbar:px-0">
    <a style="z-index: 1000;" class="flex lg:px-5 xl:pl-10 xxl:pl-14 <?php echo esc_attr($logo_position_class); ?>" href="<?php echo esc_url(home_url('/')); ?>">
      <?php if ($logo_url) : ?>
        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($logo_alt); ?>" />
      <?php else : ?>
        <span><?php echo esc_html(get_bloginfo('name')); ?></span>
      <?php endif; ?>
    </a>

    <?php if ($primary_navigation->isNotEmpty()) : ?>
      <?php
        // Reindex to ensure 0-based numeric keys
        $items = array_values($primary_navigation->toArray());
        $total = count($items);
      ?>
      <ul id="primary-menu" class="hidden gap-9 items-center leading-loose text-black max-md:gap-6 lg:flex">
        <?php foreach ($items as $i => $item) : ?>
          <?php
            $is_last = ($i === $total - 1);
            $li_classes = trim((string) $item->classes);
            $is_request_call = str_contains($li_classes, 'request-call');

            // Callback / custom CTA: menu classes on the link control color (avoid forced white on light bar).
            if ($is_request_call) {
              $text_class        = '';
              $underline_class   = '';
            } elseif ($is_last) {
              $text_class        = 'text-white';
              $underline_class   = '';
            } else {
              $text_class        = 'text-blue-500';
              $underline_class   = 'relative pb-3 after:content-[""] after:block after:absolute after:left-0 after:bottom-[0.5rem] after:h-[2px] after:w-0 after:bg-primary after:transition-[width_0.3s_ease] hover:after:w-full [&.active-item]:after:w-full';
            }
            // Last primary item: pill CTA (always on the <a>, even if the menu only added classes on <li>).
            $last_primary_cta_class = $is_last ? 'btn-primary' : '';
          ?>
          <li class="relative group pt-3 <?php echo esc_attr($item->classes); ?> <?php echo $item->active ? 'current-item' : ''; ?>">
            <a href="<?php echo esc_url($item->url); ?>"
               class="gap-2.5 self-stretch my-auto whitespace-nowrap font-secondary text-base font-medium leading-[22px] flex items-center <?php echo esc_attr($li_classes); ?> <?php echo $item->active ? 'active-item' : ''; ?> <?php echo esc_attr($text_class); ?> <?php echo esc_attr($underline_class); ?> <?php echo esc_attr($last_primary_cta_class); ?>">
              <?php echo esc_html($item->label); ?>
              <?php if (!empty($item->children)) : ?>
                <span class="ml-[2px] inline-flex transition-transform duration-200 group-hover:rotate-180" aria-hidden="true">
                  <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false">
                    <path d="M4.25 6.375L8.5 10.625L12.75 6.375" />
                  </svg>
                </span>
              <?php endif; ?>
            </a>

            <?php if (!empty($item->children)) : ?>
              <ul class="absolute left-0 top-full pt-1 hidden min-w-[200px] z-50 group-hover:block rounded-lg border border-gray-200 bg-white py-2 shadow-lg" aria-label="<?php echo esc_attr__('Submenu', 'matrix-starter'); ?>">
                <?php foreach ($item->children as $child) : ?>
                  <li class="<?php echo esc_attr($child->classes); ?> <?php echo $child->active ? 'current-item' : ''; ?>">
                    <a href="<?php echo esc_url($child->url); ?>"
                       class="block px-4 py-2.5 font-secondary text-sm font-medium leading-[22px] text-blue-500 hover:bg-gray-50 hover:text-primary-dark">
                      <?php echo esc_html($child->label); ?>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <?php get_template_part('template-parts/header/navbar/mobile'); ?>

    <?php if ($secondary_navigation->isNotEmpty()) : ?>
      <ul class="flex gap-4 px-4 text-black xl:gap-6">
        <?php foreach ($secondary_navigation->toArray() as $item) : ?>
          <li class="relative group <?php echo esc_attr($item->classes); ?>">
            <a href="<?php echo esc_url($item->url); ?>" class="flex items-center text-base font-normal text-primary hover:text-primary-light">
              <?php echo esc_html($item->label); ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </nav>
</section>
