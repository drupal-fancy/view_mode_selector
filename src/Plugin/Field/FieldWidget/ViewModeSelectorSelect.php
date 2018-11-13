<?php /**
 * @file
 * Contains \Drupal\view_mode_selector\Plugin\Field\FieldWidget\ViewModeSelectorSelect.
 */

namespace Drupal\view_mode_selector\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * @FieldWidget(
 *  id = "view_mode_selector_select",
 *  label = @Translation("Select list"),
 *  field_types = {"view_mode_selector"}
 * )
 */
class ViewModeSelectorSelect extends ViewModeSelectorWidgetBase {
  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $view_mode_keys = array_keys($this->viewModes);
    $element['value'] = $element + [
        '#type' => 'select',
        '#options' => $this->viewModes,
        '#default_value' => $items[$delta]->value ?: reset($view_mode_keys),
      ];
    return $element;
  }
}
