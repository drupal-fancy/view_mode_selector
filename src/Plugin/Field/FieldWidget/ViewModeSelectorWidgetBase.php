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

    $field_settings = $field_definition->getSettings();
    $entity_type = $field_definition->getTargetEntityTypeId();
    $bundle = $field_definition->getTargetBundle();

    // Get all view modes for the current bundle.
    $view_modes = \Drupal::entityManager()->getViewModeOptionsByBundle($entity_type, $bundle);

    // Reduce options by enabled view modes
    foreach (array_keys($view_modes) as $view_mode) {
      if(isset($field_settings['view_modes'][$view_mode]['enable']) && $field_settings['view_modes'][$view_mode]['enable']) {
        continue;
      }
      unset($view_modes[$view_mode]);
    }

    // Show all view modes in widget when no view modes are enabled.
    if (!count($view_modes)) {
      $view_modes = \Drupal::entityManager()->getViewModeOptionsByBundle($entity_type, $bundle);
    }

    $this->viewModes = $view_modes;
  }
}
