<?php

namespace Drupal\view_mode_selector\Plugin\Field\FieldWidget;

use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Render\RendererInterface;
use Drupal\file\FileInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @FieldWidget(
 *  id = "view_mode_selector_icons",
 *  label = @Translation("Icons"),
 *  field_types = {"view_mode_selector"}
 * )
 */
class ViewModeSelectorIcons extends ViewModeSelectorRadios {

  /**
   * @var \Drupal\Core\Render\RendererInterface $renderer
   */
  protected $renderer;

  /**
   * ViewModeSelectorIcons constructor.
   *
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   * @param array $settings
   * @param array $third_party_settings
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   * @param \Drupal\Core\Render\RendererInterface $renderer
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, EntityManagerInterface $entity_manager, RendererInterface $renderer) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings, $entity_manager);
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($plugin_id, $plugin_definition, $configuration['field_definition'], $configuration['settings'], $configuration['third_party_settings'], $container->get('entity.manager'), $container->get('renderer'));
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);
    $settings = $this->getFieldSettings();

    $element['#attached'] = [
      'library' => [
        'view_mode_selector/widget_styles',
      ],
    ];

    foreach (array_keys($this->viewModes) as $view_mode) {
      // Add view mode defaults.
      // @todo Move to a separate method.
      $settings['view_modes'][$view_mode] += [
        'enable' => 0,
        'hide_title' => 0,
        'icon' => [],
      ];

      $output = [];

      if (!empty($settings['view_modes'][$view_mode]['icon'][0])) {
        /** @var FileInterface $icon */
        $icon = $this->entityManager->getStorage('file')->load($settings['view_modes'][$view_mode]['icon'][0]);

        if (!$icon) {
          continue;
        }

        $render = [
          '#theme' => 'image',
          '#uri' => $icon->getFileUri(),
          '#alt' => t('Sample original image'),
          '#title' => $this->viewModes[$view_mode],
        ];

        $output[] = $this->renderer->render($render);
      }
      /*
      elseif (\Drupal::moduleHandler()->moduleExists('ds') && isset($ds_view_modes[$view_mode])) {
        $field = $items->getFieldDefinition();
        $entity_type = $field->getTargetEntityTypeId();
        $bundle = $field->getTargetBundle();

        // When Display Suite is installed we can show a nice preview.
        // @todo integrate with DS regions
        $layout = $ds_view_modes[$view_mode]['layout'];

        // Create a new empty entity for the preview.
        $entity_properties = ['type' => $entity_bundle, 'id' => FALSE];
        $entity = entity_create($entity_type, $entity_properties);
        $entity_view = entity_view($entity_type, [$entity], $view_mode);

        // Render one field containing a placeholder <div> in every region.
        foreach ($layout['settings']['regions'] as $region_settings) {
          foreach ($region_settings as $field) {
            $entity_view[$entity_type][0][$field] = [
              '#type' => 'html_tag',
              '#tag' => 'div',
              '#value' => '',
              '#attributes' => ['class' => 'placeholder'],
              '#field_name' => $field,
            ];

            continue;
          }
        }

        // Disable contextual links.
        $entity_view[$entity_type][0]['#contextual_links'] = FALSE;

        // Render the preview.
        $output[] = drupal_render($entity_view);
      }
      */
      else {
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
