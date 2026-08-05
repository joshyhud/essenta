<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

// Loads base theme setup and enqueues
require_once('functions/setup.php');

// Helpers and utility functions
require_once('functions/helpers.php');

// Handles ACF custom JSON paths and field definitions
require_once('functions/acf.php');

// Registers custom theme components
require_once('functions/components.php');

// Registers custom post types like Testimonials and FAQs
require_once('functions/cpt.php');

// Registers and manages theme navigation menus
require_once('functions/menu.php');
