<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$singleColumnText = get_sub_field('single_column');
?>
<section class="single-column-text">
  <?php echo $singleColumnText; ?>
</section>