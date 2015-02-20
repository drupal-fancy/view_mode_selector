<?php /**
 * @file
 * Contains \Drupal\view_mode_selector\Plugin\Field\FieldWidget\ViewModeSelectorIcons.
 */

namespace Drupal\view_mode_selector\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Renderer;


/**
 * @FieldWidget(
 *  id = "view_mode_selector_icons",
 *  label = @Translation("Icons"),
 *  field_types = {"view_mode_selector"}
 * )
 */
class ViewModeSelectorIcons extends ViewModeSelectorRadios {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);
    /** @var \Drupal\field\Entity\FieldConfig $field */
    $field = $items->getFieldDefinition();
    $entity_type = $field->getTargetEntityTypeId();
    $bundle = $field->getTargetBundle();
    $settings = $this->getFieldSettings();

    $element['#attached'] = [
      'library' => [
        'view_mode_selector/widget_styles',
      ],
    ];

    foreach (array_keys($this->viewModes) as $view_mode) {
      $output = [];

      if(isset($settings['view_modes'][$view_mode]['icon']) && $settings['view_modes'][$view_mode]['icon']) {
        $icon = \Drupal::entityManager()
          ->getStorage('file')
          ->load($settings['view_modes'][$view_mode]['icon'][0]);

        if (!$icon) {
          continue;
        }

        $render = [
          '#theme' => 'image',
          '#uri' => $icon->getFileUri(),
          '#alt' => t('Sample original image'),
          '#title' => $this->viewModes[$view_mode],
        ];

        $output[] = \Drupal::service('renderer')->render($render);
      } elseif (\Drupal::moduleHandler()->moduleExists('ds') && isset($ds_view_modes[$view_mode])) {
        // When Display Suite is installed we can show a nice preview.
        // @todo integrate with DS regions
//        $layout = $ds_view_modes[$view_mode]['layout'];
//
//        // Create a new empty entity for the preview.
//        $entity_properties = ['type' => $entity_bundle, 'id' => FALSE];
//        $entity = entity_create($entity_type, $entity_properties);
//        $entity_view = entity_view($entity_type, [$entity], $view_mode);
//
//        // Render one field containing a placeholder <div> in every region.
//        foreach ($layout['settings']['regions'] as $region_settings) {
//          foreach ($region_settings as $field) {
//            $entity_view[$entity_type][0][$field] = [
//              '#type' => 'html_tag',
//              '#tag' => 'div',
//              '#value' => '',
//              '#attributes' => ['class' => 'placeholder'],
//              '#field_name' => $field,
//            ];
//
//            continue;
//          }
//        }
//
//        // Disable contextual links.
//        $entity_view[$entity_type][0]['#contextual_links'] = FALSE;
//
//        // Render the preview.
//        $output[] = drupal_render($entity_view);
      } else {
        $element['value'][$view_mode]['#attributes']['class'][] = 'no-preview';
      }

      if (!$settings['view_modes'][$view_mode]['hide_title']) {
        $output[] = '<small>' . $this->viewModes[$view_mode] . '</small>';
      }

      // Use the generated markup as our label value.
      $element['value']['#options'][$view_mode] = implode($output, '');
    }

    return $element;
  }

}
