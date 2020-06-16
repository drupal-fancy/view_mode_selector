<?php

namespace Drupal\view_mode_selector\Plugin\Field\FieldWidget;

use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for the 'view_mode_selector_*' widgets.
 */
abstract class ViewModeSelectorWidgetBase extends WidgetBase implements ContainerFactoryPluginInterface {

  /**
   * List of available view modes.
   *
   * @var array $viewModes
   */
  protected $viewModes = [];

  /**
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $entityDisplayRepository;

  /**
   * ViewModeSelectorWidgetBase constructor.
   *
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   * @param array $settings
   * @param array $third_party_settings
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entity_display_repository
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, EntityDisplayRepositoryInterface $entity_display_repository) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->entityDisplayRepository = $entity_display_repository;

    $field_settings = $field_definition->getSettings();
    $entity_type = $field_definition->getTargetEntityTypeId();
    $bundle = $field_definition->getTargetBundle();

    // Get all view modes for the current bundle.
    $view_modes = $this->entityDisplayRepository->getViewModeOptionsByBundle($entity_type, $bundle);

    // Reduce options by enabled view modes
    foreach (array_keys($view_modes) as $view_mode) {
      if (isset($field_settings['view_modes'][$view_mode]['enable']) && $field_settings['view_modes'][$view_mode]['enable']) {
        continue;
      }
      unset($view_modes[$view_mode]);
    }

    // Show all view modes in widget when no view modes are enabled.
    if (!count($view_modes)) {
      $view_modes = $this->entityDisplayRepository->getViewModeOptionsByBundle($entity_type, $bundle);
    }

    $this->viewModes = $view_modes;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($plugin_id, $plugin_definition, $configuration['field_definition'], $configuration['settings'], $configuration['third_party_settings'], $container->get('entity_display.repository'));
  }
}
