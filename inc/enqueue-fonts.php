<?php
// File: inc/enqueue-fonts.php

/**
 * Enqueue Google Fonts (Lato and Poppins)
 */
function matrix_starter_enqueue_fonts() {
  // Split font requests to avoid overly-long URLs (some proxies/CDNs can 414).
  wp_enqueue_style(
    'matrix-google-fonts-core',
    'https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap',
    [],
    null
  );

  wp_enqueue_style(
    'matrix-google-fonts-redhat',
    'https://fonts.googleapis.com/css2?family=Red+Hat+Display:ital,wght@0,300..900;1,300..900&family=Red+Hat+Text:ital,wght@0,300..700;1,300..700&display=swap',
    [],
    null
  );

  wp_enqueue_style(
    'matrix-google-fonts-accent',
    'https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap',
    [],
    null
  );
}
add_action('wp_enqueue_scripts', 'matrix_starter_enqueue_fonts', 5);