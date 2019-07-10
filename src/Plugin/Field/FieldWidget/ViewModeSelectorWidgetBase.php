<?php

namespace Drupal\view_mode_selector\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Base class for the 'view_mode_selector_*' widgets.
 */
abstract class ViewModeSelectorWidgetBase extends WidgetBase {

  /**
   * List of available view modes.
   *
   * @var array
   */
  protected $viewModes = [];

  /**
   * {@inheritdoc}
   */
  public function form(FieldItemListInterface $items, array &$form, FormStateInterface $form_state, $get_delta = NULL) {
    $field_definition = $items[0]->getFieldDefinition();
    if (empty($this->viewModes)) {
      $this->viewModes = view_mode_selector_get_enabled_view_modes($field_definition, $field_definition->getTargetBundle());
    }

    return parent::form($items, $form, $form_state, $get_delta);
  }

}
