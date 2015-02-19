<?php /**
 * @file
 * Contains \Drupal\view_mode_selector\Plugin\Field\FieldWidget\ViewModeSelectorWidgetBase.
 */

namespace Drupal\view_mode_selector\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\FieldDefinitionInterface;

/**
 * Base class for the 'view_mode_selector_*' widgets.
 */
abstract class ViewModeSelectorWidgetBase extends WidgetBase {

  /**
   * List of available view modes.
   */
  protected $viewModes = [];

  /**
   * Gather all available view modes.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);

    $entity_type = $field_definition->getTargetEntityTypeId();

    // Get all view modes of the entity type.
    $view_modes = view_mode_selector_get_view_mode_options($entity_type);

    // Reduce options by enabled view modes
    foreach (array_keys($view_modes) as $view_mode) {
      if(isset($settings['view_modes'][$view_mode]['enable']) && $settings['view_modes'][$view_mode]['enable']) {
        continue;
      }
      unset($view_modes[$view_mode]);
    }

    // Show all view modes in widget when no view modes are enabled.
    if (!count($view_modes)) {
      $view_modes = view_mode_selector_get_view_mode_options($entity_type);
    }

    $this->viewModes = $view_modes;
  }
}
