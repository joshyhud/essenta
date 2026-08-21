<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

// Finds and adds an additional link to parents of submenu's so we can toggle this in mobile.
class Custom_Nav_Walker extends Walker_Nav_Menu
{
  function start_lvl(&$output, $depth = 0, $args = array())
  {
    $output .= "\n<ul class=\"sub-menu\">\n";
  }

  function end_lvl(&$output, $depth = 0, $args = array())
  {
    $output .= "</ul>\n";
  }

  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
  {
    $has_submenu = !empty($item->classes) && in_array('menu-item-has-children', $item->classes);

    if ($has_submenu) {
      $output .= sprintf("\n<li class='" .  implode(" ", $item->classes) . "'><a href=\"%s\">%s</a><a class=\"sub-menu-link\" href=\"#\"><img loading='lazy' src='" . get_stylesheet_directory_uri() . "/assets/arrow.svg' /></a>", $item->url, $item->title);
    } else {
      $output .= sprintf("\n<li class='" .  implode(" ", $item->classes) . "'><a href=\"%s\">%s</a>", $item->url, $item->title);
    }
  }

  function end_el(&$output, $item, $depth = 0, $args = array())
  {
    $output .= "</li>\n";
  }
}


class Mobile_Menu_Walker extends Walker_Nav_Menu
{
  public function start_lvl(&$output, $depth = 0, $args = null)
  {
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<ul class=\"sub-menu\">\n";
  }

  public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
  {
    $classes = empty($item->classes) ? array() : (array)$item->classes;
    $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
    $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

    $output .= sprintf('<li id="menu-item-%s" %s>', $item->ID, $class_names);

    $link_attributes = sprintf(
      ' href="%s" class="menu-link"',
      esc_url($item->url)
    );

    $arrow = '';
    if (in_array('menu-item-has-children', $classes)) {
      $arrow = '<button class="dropdown-toggle" aria-expanded="false" aria-label="Expand submenu"></button>';
    }

    $output .= sprintf(
      '<a%s>%s</a>%s',
      $link_attributes,
      esc_html($item->title),
      $arrow
    );
  }

  public function end_el(&$output, $item, $depth = 0, $args = null)
  {
    $output .= "</li>\n";
  }
}
