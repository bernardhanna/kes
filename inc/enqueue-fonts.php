<?php
// File: inc/enqueue-fonts.php

/**
 * Enqueue Google Fonts (Lato and Poppins)
 */
function matrix_starter_enqueue_fonts() {
  // Use a clean, new ID like 'my-google-fonts'
  wp_enqueue_style(
    'my-google-fonts',
    // THIS IS YOUR COMPLETE URL WITH ALL FAMILIES
    'https://fonts.googleapis.com/css2?family=Great+Vibes&family=League+Spartan:wght@100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Red+Hat+Display:ital,wght@0,300..900;1,300..900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap',
    [],
    null // or a version number if you like
  );

  // OPTIONAL: Try to dequeue any duplicate or conflicting font enqueues
  // You may have other code or plugins adding the specific Ubuntu/Great Vibes links.
  // If you want ONLY your bundled link to load the fonts, try adding:
  wp_deregister_style('ubuntu-css');
  wp_deregister_style('great-fonts-css');
  wp_deregister_style('google-fonts-bundle'); // Deregisterring your original incomplete bundle
}
add_action('wp_enqueue_scripts', 'matrix_starter_enqueue_fonts', 95); // Use a higher priority like 15 to load later than