<?php
$text_content = get_sub_field('text_content');
$text_content = str_ireplace('<p>', '<p style="font-size:16px;">', $text_content);

$padding_classes = [];
if (have_rows('padding_settings')) {
  while (have_rows('padding_settings')) {
    the_row();
    $screen = get_sub_field('screen_size');
    $pt = get_sub_field('padding_top');
    $pb = get_sub_field('padding_bottom');
    $padding_classes[] = "{$screen}:pt-[{$pt}rem]";
    $padding_classes[] = "{$screen}:pb-[{$pb}rem]";
  }
}
?>

<section data-matrix-block="<?php echo esc_attr(str_replace('_', '-', get_row_layout()) . '-' . get_row_index()); ?>" class="flex overflow-hidden relative wp_editor">
  <div class="w-full mx-auto max-w-container flex flex-col justify-between  max-xxl:px-5 py-10">
   
      <div class="relative text-[16px] max-w-[745px]">
        <?php if ($text_content): ?>
          <?= wp_kses_post($text_content); ?>
        <?php endif; ?>
      </div>

  </div>
</section>

