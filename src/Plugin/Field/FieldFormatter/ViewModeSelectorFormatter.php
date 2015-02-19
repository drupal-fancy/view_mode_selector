<?php

/**
 * @file
 * Contains \Drupal\view_mode_selector\Plugin\Field\FieldFormatter\ViewModeSelectorFormatter.
 */

namespace Drupal\view_mode_selector\Plugin\Field\FieldFormatter;

use Drupal\text\Plugin\Field\FieldFormatter\TextDefaultFormatter;

/**
 * Plugin extends the 'text_default' formatter.
 *
 * @FieldFormatter(
 *   id = "view_mode_selector",
 *   label = @Translation("Selected view mode name as text"),
 *   field_types = {
 *     "view_mode_selector"
 *   }
 * )
 */
class ViewModeSelectorFormatter extends TextDefaultFormatter {}
