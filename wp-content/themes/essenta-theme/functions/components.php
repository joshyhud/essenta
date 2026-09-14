<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Function get_component_prefix()
 * @param mixed $acf_prefix
 * @return array
 */
function get_component_prefix($acf_prefix)
{
	$prefix['acf'] = $acf_prefix;
	$prefix['class'] = str_replace('_', '-', $acf_prefix);
	return $prefix;
}
/**
 * Function get_background()
 * Return the component image or a colour based on the type selected
 * @param mixed $prefix
 * @param mixed $lazyload
 * @return array
 */
function get_background($prefix = '', $lazyload = true)
{
	if (have_rows($prefix . '_background_image')) :
		while (have_rows($prefix . '_background_image')) : the_row();

			// Set default values
			$bg_options = [];
			$bg_options['bg_type'] = get_sub_field('background_type');
			$bg_options['bg_color'] = (get_sub_field('bg_color')) ? 'background-color: var(--color-' . get_sub_field('bg_color') . ')' : '';
			$bg_options['bg_overlay_color'] = (get_sub_field('bg_overlay_color')) ? get_sub_field('bg_overlay_color') : '';
			$bg_options['bg_overlay_amount'] = get_sub_field('bg_overlay_amount'); // Default 50

			// Get background images
			$bg_desktop_image = get_sub_field('bg_desktop_image'); // Required
			$bg_tablet_image = get_sub_field('bg_tablet_image'); // Optional
			$bg_mobile_image = get_sub_field('bg_mobile_image'); // Optional

			$srcset = [];
			$sizes = [];

			// Get mobile background image
			if ($bg_mobile_image) :
				$img_url = $bg_mobile_image['url'];
				$img_alt = ($bg_mobile_image['alt']) ? ' alt="' . esc_attr($bg_mobile_image['alt']) . '"' : '';
				$srcset[] = esc_url($bg_mobile_image['url']) . " 510w";
				$sizes[] = "(max-width: 510px) 100vw";
			endif;

			// Get tablet background image
			if ($bg_tablet_image) :
				$img_url = $bg_tablet_image['url'];
				$img_alt = ($bg_tablet_image['alt']) ? ' alt="' . esc_attr($bg_tablet_image['alt']) . '"' : '';
				$srcset[] = esc_url($bg_tablet_image['url']) . " 768w";
				$sizes[] = "(max-width: 768px) 100vw";
			endif;

			// Get desktop background image
			if ($bg_desktop_image) :
				$img_url = $bg_desktop_image['url'];
				$img_alt = ($bg_desktop_image['alt']) ? ' alt="' . esc_attr($bg_desktop_image['alt']) . '"' : '';
				$srcset[] = esc_url($bg_desktop_image['url']) . " 1200w";
				$sizes[] = '1200px';
			endif;

			// Convert srcset array to string
			$srcset_attr = !empty($srcset) ? ' srcset="' . esc_attr(implode(", ", $srcset)) . '"' : '';

			// Define sizes if multiple images exist
			$sizes_attr = !empty($sizes) ? ' sizes="' . esc_attr(implode(", ", $sizes)) . '"' : '';
			// Add lazyload attribute
			$lazyload_attr = $lazyload ? ' loading="lazy"' : '';
			// Add background image
			$bg_options['bg_img'] = '<img class="bg-image ' . str_replace('_', '-', $prefix) . '-bg" src="' . esc_url($img_url) . '"' . $img_alt . $srcset_attr . $sizes_attr . $lazyload_attr . '>';

		endwhile;
	endif;

	if ($bg_options['bg_type'] == 'bg_image') {
		// Set default values
		$bg_options['bg_img'] = isset($bg_options['bg_img']) ? $bg_options['bg_img'] : '';
		// Add overlay if set
		$bg_options['bg_overlay'] = ($bg_options['bg_overlay_color'] != '') ? ' style="background-color: var(--color-' . esc_attr($bg_options['bg_overlay_color']) . '); opacity: ' . esc_attr($bg_options['bg_overlay_amount']) . ';"' : '';
		// Remove background color if bg_image is set
		$bg_options['bg_color'] = '';
	} else {
		// Remove background image if bg_color is set
		$bg_options['bg_img'] = '';
	}

	return $bg_options;
}
